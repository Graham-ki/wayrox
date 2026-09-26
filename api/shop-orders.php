<?php
//session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db-connection.php';
require_once __DIR__ . '/../includes/cache.php';

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) { echo json_encode(['success' => false, 'message' => 'Invalid data']); exit; }
    $action = $data['action'] ?? $action;

    switch ($action) {
        // ================= CREATE ORDER =================
        case 'create':
            // Require login
            if (!isLoggedIn()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Please log in to place an order',
                    'requires_login' => true
                ]);
                exit;
            }

            $user = getCurrentUser();
            $userId = $user['id'];

            // Validate required fields
            $required = ['customer_name', 'customer_email', 'customer_phone', 'delivery_address', 'items'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    echo json_encode(['success' => false, 'message' => "Missing: $field"]);
                    exit;
                }
            }

            if (!is_array($data['items']) || empty($data['items'])) {
                echo json_encode(['success' => false, 'message' => 'No items in order']);
                exit;
            }

            try {
                $pdo->beginTransaction();

                // Generate order number + invoice number
                $orderNumber = 'WX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                // Calculate totals from DB prices
                $subtotal = 0;
                $orderItems = [];

                foreach ($data['items'] as $item) {
                    $stmt = $pdo->prepare("
                        SELECT id, name, price, stock_quantity 
                        FROM shop_products 
                        WHERE id = ? AND is_active = 1
                    ");
                    $stmt->execute([$item['product_id']]);
                    $product = $stmt->fetch();

                    if (!$product) {
                        throw new Exception("Product not found: {$item['product_id']}");
                    }
                    if ($product['stock_quantity'] < $item['quantity']) {
                        throw new Exception("Insufficient stock for {$product['name']}");
                    }

                    $lineTotal = $product['price'] * $item['quantity'];
                    $subtotal += $lineTotal;

                    $orderItems[] = [
                        'product_id' => $product['id'],
                        'product_name' => $product['name'],
                        'product_price' => $product['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $lineTotal
                    ];
                }

                // Validate delivery fee against region (security: don't trust client)
                $regionId = intval($data['region_id'] ?? 0);
                $regionName = '';
                $deliveryFee = 0;

                if ($regionId) {
                    $stmt = $pdo->prepare("
                        SELECT name, delivery_fee 
                        FROM shop_delivery_regions 
                        WHERE id = ? AND is_active = 1
                    ");
                    $stmt->execute([$regionId]);
                    $region = $stmt->fetch();
                    if ($region) {
                        $regionName = $region['name'];
                        $deliveryFee = floatval($region['delivery_fee']);
                    }
                }

                // Free delivery threshold check
                $settings = cache_remember('shop_settings', 300, function() use ($pdo) {
                    $stmt = $pdo->query("SELECT setting_key, setting_value FROM shop_settings");
                    $map = [];
                    foreach ($stmt->fetchAll() as $r) $map[$r['setting_key']] = $r['setting_value'];
                    return $map;
                });

                $freeThreshold = floatval($settings['free_delivery_threshold'] ?? 0);
                if ($freeThreshold > 0 && $subtotal >= $freeThreshold) {
                    $deliveryFee = 0;
                }

                $total = $subtotal + $deliveryFee;

                // Insert order
                $stmt = $pdo->prepare("
                    INSERT INTO shop_orders
                    (user_id, order_number, invoice_number, invoice_issued_at,
                     customer_name, customer_email, customer_phone,
                     delivery_address, delivery_city, region_id, region_name, delivery_notes,
                     subtotal, delivery_fee, total, payment_method, order_status)
                    VALUES (?,?,?,NOW(),?,?,?,?,?,?,?,?,?,?,?,?,?)
                ");
                $stmt->execute([
                    $userId,
                    $orderNumber,
                    $invoiceNumber,
                    $data['customer_name'],
                    $data['customer_email'],
                    $data['customer_phone'],
                    $data['delivery_address'],
                    $data['delivery_city'] ?? '',
                    $regionId ?: null,
                    $regionName,
                    $data['delivery_notes'] ?? '',
                    $subtotal,
                    $deliveryFee,
                    $total,
                    $data['payment_method'] ?? 'cash_on_delivery',
                    'pending'
                ]);

                $orderId = $pdo->lastInsertId();

                // Insert items + reduce stock
                $stmt = $pdo->prepare("
                    INSERT INTO shop_order_items
                    (order_id, product_id, product_name, product_price, quantity, subtotal)
                    VALUES (?,?,?,?,?,?)
                ");

                $updateStock = $pdo->prepare("
                    UPDATE shop_products 
                    SET stock_quantity = stock_quantity - ? 
                    WHERE id = ?
                ");

                foreach ($orderItems as $item) {
                    $stmt->execute([
                        $orderId,
                        $item['product_id'],
                        $item['product_name'],
                        $item['product_price'],
                        $item['quantity'],
                        $item['subtotal']
                    ]);
                    $updateStock->execute([$item['quantity'], $item['product_id']]);
                }

                // Log initial history
                $histStmt = $pdo->prepare("
                    INSERT INTO shop_order_history (order_id, status, note, changed_by)
                    VALUES (?, 'pending', ?, ?)
                ");
                $histStmt->execute([
                    $orderId,
                    'Order placed. Invoice ' . $invoiceNumber . ' issued.',
                    $userId
                ]);

                $pdo->commit();

                // Bust product cache (stock changed)
                cache_forget_pattern('products_*');
                cache_forget_pattern('search_*');

                echo json_encode([
                    'success' => true,
                    'order_number' => $orderNumber,
                    'invoice_number' => $invoiceNumber,
                    'order_id' => $orderId,
                    'total' => $total
                ]);

            } catch (Exception $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;

        // ================= TRACK ORDER (public, email required) =================
        case 'track':
            $orderNumber = trim($data['order_number'] ?? '');
            $email = trim($data['email'] ?? '');

            if (!$orderNumber || !$email) {
                echo json_encode(['success' => false, 'message' => 'Order number and email required']);
                exit;
            }

            $stmt = $pdo->prepare("
                SELECT * FROM shop_orders 
                WHERE order_number = ? AND customer_email = ? 
                LIMIT 1
            ");
            $stmt->execute([$orderNumber, $email]);
            $order = $stmt->fetch();

            if (!$order) {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
                exit;
            }

            $stmt = $pdo->prepare("SELECT * FROM shop_order_items WHERE order_id = ?");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll();

            $stmt = $pdo->prepare("SELECT * FROM shop_order_history WHERE order_id = ? ORDER BY created_at ASC");
            $stmt->execute([$order['id']]);
            $order['history'] = $stmt->fetchAll();

            echo json_encode(['success' => true, 'data' => $order]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);