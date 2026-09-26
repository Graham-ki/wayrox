<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db-connection.php';
require_once __DIR__ . '/../includes/cache.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$currentUser = getCurrentUser();

if (!in_array($currentUser['role'], ['admin', 'editor'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    // ==================== GET ACTIONS ====================
    if ($method === 'GET') {
        switch ($action) {

            // ---------- CATEGORIES ----------
            case 'categories':
                $stmt = $pdo->query("
                    SELECT 
                        c.*,
                        (SELECT COUNT(*) FROM shop_products p WHERE p.category_id = c.id) AS product_count
                    FROM shop_categories c
                    ORDER BY c.display_order, c.name
                ");
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
                break;

            // ---------- PRODUCTS ----------
            case 'products':
                $stmt = $pdo->query("
                    SELECT p.*, c.name AS category_name 
                    FROM shop_products p 
                    LEFT JOIN shop_categories c ON p.category_id = c.id 
                    ORDER BY p.created_at DESC
                ");
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
                break;

            // ---------- ORDERS ----------
            case 'orders':
                $status = $_GET['status'] ?? '';
                if ($status) {
                    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE order_status = ? ORDER BY created_at DESC");
                    $stmt->execute([$status]);
                } else {
                    $stmt = $pdo->query("SELECT * FROM shop_orders ORDER BY created_at DESC");
                }
                $orders = $stmt->fetchAll();

                $itemStmt = $pdo->prepare("SELECT * FROM shop_order_items WHERE order_id = ?");
                $histStmt = $pdo->prepare("SELECT * FROM shop_order_history WHERE order_id = ? ORDER BY created_at ASC");
                $retStmt = $pdo->prepare("SELECT * FROM shop_returns WHERE order_id = ? ORDER BY created_at DESC");

                foreach ($orders as &$o) {
                    $itemStmt->execute([$o['id']]);
                    $o['items'] = $itemStmt->fetchAll();

                    $histStmt->execute([$o['id']]);
                    $o['history'] = $histStmt->fetchAll();

                    $retStmt->execute([$o['id']]);
                    $o['returns'] = $retStmt->fetchAll();

                    $o['active_return_count'] = count(array_filter($o['returns'], function($r) {
                        return in_array($r['status'], ['pending', 'approved']);
                    }));
                }
                unset($o);

                echo json_encode(['success' => true, 'data' => $orders]);
                break;

            // ---------- RETURNS LIST ----------
            case 'returns':
                $status = $_GET['status'] ?? '';
                $query = "
                    SELECT 
                        r.*,
                        o.order_number,
                        o.customer_name,
                        o.customer_email,
                        o.customer_phone,
                        o.total AS order_total,
                        o.order_status AS order_status,
                        u.full_name AS account_name,
                        u.email AS account_email,
                        (SELECT COUNT(*) FROM shop_return_items ri WHERE ri.return_id = r.id) AS item_count
                    FROM shop_returns r
                    JOIN shop_orders o ON r.order_id = o.id
                    LEFT JOIN users u ON r.user_id = u.id
                ";
                $params = [];
                if ($status && $status !== 'all') {
                    $query .= " WHERE r.status = ?";
                    $params[] = $status;
                }
                $query .= " ORDER BY r.created_at DESC";

                $stmt = $pdo->prepare($query);
                $stmt->execute($params);
                $returns = $stmt->fetchAll();

                $itemStmt = $pdo->prepare("
                    SELECT ri.*, oi.product_name, oi.product_price, oi.quantity AS order_qty, oi.id AS order_item_id
                    FROM shop_return_items ri
                    JOIN shop_order_items oi ON ri.order_item_id = oi.id
                    WHERE ri.return_id = ?
                ");
                foreach ($returns as &$r) {
                    $itemStmt->execute([$r['id']]);
                    $r['items'] = $itemStmt->fetchAll();
                }
                unset($r);

                echo json_encode(['success' => true, 'data' => $returns]);
                break;

            // ---------- SINGLE RETURN ----------
            case 'return-single':
                $id = intval($_GET['id'] ?? 0);
                if (!$id) { echo json_encode(['success' => false, 'message' => 'ID required']); exit; }

                $stmt = $pdo->prepare("
                    SELECT 
                        r.*,
                        o.order_number,
                        o.customer_name,
                        o.customer_email,
                        o.customer_phone,
                        o.delivery_address,
                        o.delivery_city,
                        o.region_name,
                        o.total AS order_total,
                        o.order_status AS order_status,
                        o.delivered_at,
                        o.return_window_ends_at
                    FROM shop_returns r
                    JOIN shop_orders o ON r.order_id = o.id
                    WHERE r.id = ?
                ");
                $stmt->execute([$id]);
                $return = $stmt->fetch();

                if (!$return) { echo json_encode(['success' => false, 'message' => 'Return not found']); exit; }

                $itemStmt = $pdo->prepare("
                    SELECT ri.*, oi.product_name, oi.product_price, oi.quantity AS order_qty
                    FROM shop_return_items ri
                    JOIN shop_order_items oi ON ri.order_item_id = oi.id
                    WHERE ri.return_id = ?
                ");
                $itemStmt->execute([$id]);
                $return['items'] = $itemStmt->fetchAll();

                echo json_encode(['success' => true, 'data' => $return]);
                break;

            // ---------- PENDING RETURNS COUNT ----------
            case 'returns-pending-count':
                $stmt = $pdo->query("SELECT COUNT(*) AS c FROM shop_returns WHERE status = 'pending'");
                $count = (int)$stmt->fetch()['c'];
                echo json_encode(['success' => true, 'count' => $count]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
        exit;
    }

    // ==================== POST ACTIONS ====================
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) { echo json_encode(['success' => false, 'message' => 'Invalid data']); exit; }
        $action = $data['action'] ?? $action;

        switch ($action) {

            // ---------- CATEGORIES ----------
            case 'create-category':
                $name = trim($data['name'] ?? '');
                if ($name === '') {
                    echo json_encode(['success' => false, 'message' => 'Category name is required']);
                    break;
                }

                $slug = trim($data['slug'] ?? '');
                if ($slug === '') {
                    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
                    $slug = trim($slug, '-');
                } else {
                    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $slug));
                    $slug = trim($slug, '-');
                }

                // Check slug uniqueness
                $check = $pdo->prepare("SELECT id FROM shop_categories WHERE slug = ? LIMIT 1");
                $check->execute([$slug]);
                if ($check->fetch()) {
                    $slug .= '-' . time();
                }

                $stmt = $pdo->prepare("
                    INSERT INTO shop_categories 
                    (name, slug, icon, description, is_active) 
                    VALUES (?,?,?,?,?)
                ");
                $stmt->execute([
                    $name,
                    $slug,
                    $data['icon'] ?? '',
                    $data['description'] ?? '',
                    isset($data['is_active']) ? intval($data['is_active']) : 1
                ]);

                echo json_encode([
                    'success' => true,
                    'id' => $pdo->lastInsertId(),
                    'slug' => $slug
                ]);
                break;

            case 'update-category':
                $id = intval($data['id'] ?? 0);
                if (!$id) {
                    echo json_encode(['success' => false, 'message' => 'Category ID required']);
                    break;
                }

                $name = trim($data['name'] ?? '');
                if ($name === '') {
                    echo json_encode(['success' => false, 'message' => 'Category name is required']);
                    break;
                }

                $slug = trim($data['slug'] ?? '');
                if ($slug === '') {
                    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
                    $slug = trim($slug, '-');
                } else {
                    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $slug));
                    $slug = trim($slug, '-');
                }

                // Check slug uniqueness (exclude self)
                $check = $pdo->prepare("SELECT id FROM shop_categories WHERE slug = ? AND id != ? LIMIT 1");
                $check->execute([$slug, $id]);
                if ($check->fetch()) {
                    $slug .= '-' . time();
                }

                $stmt = $pdo->prepare("
                    UPDATE shop_categories 
                    SET name = ?, slug = ?, icon = ?, description = ?, is_active = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $name,
                    $slug,
                    $data['icon'] ?? '',
                    $data['description'] ?? '',
                    isset($data['is_active']) ? intval($data['is_active']) : 1,
                    $id
                ]);

                echo json_encode(['success' => true, 'slug' => $slug]);
                break;

            case 'delete-category':
                $id = intval($data['id'] ?? 0);
                if (!$id) {
                    echo json_encode(['success' => false, 'message' => 'Category ID required']);
                    break;
                }

                // Optionally: check if products use this category
                $check = $pdo->prepare("SELECT COUNT(*) AS c FROM shop_products WHERE category_id = ?");
                $check->execute([$id]);
                $productCount = (int)$check->fetch()['c'];

                // Delete the category (products keep their category_id, becoming orphaned)
                $stmt = $pdo->prepare("DELETE FROM shop_categories WHERE id = ?");
                $stmt->execute([$id]);

                cache_forget_pattern('products_*');

                echo json_encode([
                    'success' => true,
                    'orphaned_products' => $productCount
                ]);
                break;

            // ---------- PRODUCTS ----------
            case 'create-product':
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $data['name'])) . '-' . time();
                $stmt = $pdo->prepare("
                    INSERT INTO shop_products 
                    (category_id, name, slug, short_description, description, brand, sku, price, stock_quantity, image, is_featured) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)
                ");
                $stmt->execute([
                    $data['category_id'], $data['name'], $slug,
                    $data['short_description'] ?? '', $data['description'] ?? '',
                    $data['brand'] ?? '', $data['sku'] ?? null,
                    $data['price'], $data['stock_quantity'],
                    $data['image'] ?? '', $data['is_featured'] ?? 0
                ]);
                cache_forget_pattern('products_*');
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
                break;

            case 'update-product':
                $stmt = $pdo->prepare("
                    UPDATE shop_products 
                    SET category_id=?, name=?, short_description=?, description=?, brand=?, sku=?, price=?, stock_quantity=?, image=?, is_featured=? 
                    WHERE id=?
                ");
                $stmt->execute([
                    $data['category_id'], $data['name'],
                    $data['short_description'] ?? '', $data['description'] ?? '',
                    $data['brand'] ?? '', $data['sku'] ?? null,
                    $data['price'], $data['stock_quantity'],
                    $data['image'] ?? '', $data['is_featured'] ?? 0,
                    $data['id']
                ]);
                cache_forget_pattern('products_*');
                echo json_encode(['success' => true]);
                break;

            case 'delete-product':
                $stmt = $pdo->prepare("DELETE FROM shop_products WHERE id = ?");
                $stmt->execute([$data['id']]);
                cache_forget_pattern('products_*');
                echo json_encode(['success' => true]);
                break;

            // ---------- ORDERS ----------
            case 'update-order-status':
                $id = intval($data['id'] ?? 0);
                $status = $data['status'] ?? '';

                if (!$id || !$status) {
                    echo json_encode(['success' => false, 'message' => 'ID and status required']);
                    break;
                }

                $validStatuses = ['pending','confirmed','processing','shipped','delivered','cancelled','returned','refunded'];
                if (!in_array($status, $validStatuses)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid status']);
                    break;
                }

                $stmt = $pdo->prepare("UPDATE shop_orders SET order_status = ? WHERE id = ?");
                $stmt->execute([$status, $id]);

                // Log to history
                $histStmt = $pdo->prepare("
                    INSERT INTO shop_order_history (order_id, status, note, changed_by)
                    VALUES (?, ?, ?, ?)
                ");
                $histStmt->execute([
                    $id, $status,
                    'Status changed to ' . $status . ' by admin',
                    $currentUser['id']
                ]);

                echo json_encode(['success' => true]);
                break;

            // ---------- RETURNS ----------
            case 'update-return-status':
                $id = intval($data['id'] ?? 0);
                $status = $data['status'] ?? '';
                $adminNote = trim($data['admin_note'] ?? '');
                $refundAmount = isset($data['refund_amount']) ? floatval($data['refund_amount']) : null;
                $restockItems = !empty($data['restock_items']);

                if (!$id || !$status) {
                    echo json_encode(['success' => false, 'message' => 'ID and status required']);
                    break;
                }

                $validStatuses = ['pending', 'approved', 'rejected', 'completed'];
                if (!in_array($status, $validStatuses)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid status']);
                    break;
                }

                try {
                    $pdo->beginTransaction();

                    $stmt = $pdo->prepare("
                        SELECT r.*, o.order_status AS current_order_status 
                        FROM shop_returns r
                        JOIN shop_orders o ON r.order_id = o.id
                        WHERE r.id = ?
                        FOR UPDATE
                    ");
                    $stmt->execute([$id]);
                    $return = $stmt->fetch();

                    if (!$return) {
                        $pdo->rollBack();
                        echo json_encode(['success' => false, 'message' => 'Return not found']);
                        break;
                    }

                    $fields = ['status = ?'];
                    $params = [$status];

                    if ($adminNote !== '') {
                        $fields[] = 'admin_note = ?';
                        $params[] = $adminNote;
                    }
                    if ($refundAmount !== null) {
                        $fields[] = 'refund_amount = ?';
                        $params[] = $refundAmount;
                    }
                    $params[] = $id;

                    $update = $pdo->prepare("UPDATE shop_returns SET " . implode(', ', $fields) . " WHERE id = ?");
                    $update->execute($params);

                    // If approved AND restock requested → put items back in inventory
                    if ($status === 'approved' && $restockItems) {
                        $itemsStmt = $pdo->prepare("
                            SELECT ri.quantity, oi.product_id 
                            FROM shop_return_items ri
                            JOIN shop_order_items oi ON ri.order_item_id = oi.id
                            WHERE ri.return_id = ?
                        ");
                        $itemsStmt->execute([$id]);
                        $items = $itemsStmt->fetchAll();

                        $restock = $pdo->prepare("UPDATE shop_products SET stock_quantity = stock_quantity + ? WHERE id = ?");
                        foreach ($items as $it) {
                            $restock->execute([$it['quantity'], $it['product_id']]);
                        }
                        cache_forget_pattern('products_*');
                    }

                    // If completed → mark order as refunded or returned
                    if ($status === 'completed') {
                        $orderStatus = $return['return_type'] === 'exchange' ? 'returned' : 'refunded';

                        $ordUpdate = $pdo->prepare("
                            UPDATE shop_orders 
                            SET order_status = ?, 
                                payment_status = CASE WHEN ? = 'refunded' THEN 'refunded' ELSE payment_status END
                            WHERE id = ?
                        ");
                        $ordUpdate->execute([$orderStatus, $orderStatus, $return['order_id']]);

                        $hist = $pdo->prepare("
                            INSERT INTO shop_order_history (order_id, status, note, changed_by)
                            VALUES (?, ?, ?, ?)
                        ");
                        $hist->execute([
                            $return['order_id'],
                            $orderStatus,
                            'Return ' . $return['return_type'] . ' completed by admin',
                            $currentUser['id']
                        ]);
                    } else {
                        $hist = $pdo->prepare("
                            INSERT INTO shop_order_history (order_id, status, note, changed_by)
                            VALUES (?, ?, ?, ?)
                        ");
                        $hist->execute([
                            $return['order_id'],
                            $return['current_order_status'],
                            'Return ' . $status . ($adminNote ? ': ' . $adminNote : ''),
                            $currentUser['id']
                        ]);
                    }

                    $pdo->commit();
                    echo json_encode(['success' => true, 'message' => 'Return updated']);
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}