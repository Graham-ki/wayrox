<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Cache-Control: no-store'); // user-specific

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db-connection.php';
require_once __DIR__ . '/../includes/cache.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user = getCurrentUser();
$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

try {
    $action = $_GET['action'] ?? 'list';

    if ($action === 'list') {
        $status = $_GET['status'] ?? '';

        $query = "SELECT * FROM shop_orders WHERE user_id = ?";
        $params = [$user['id']];

        if ($status && $status !== 'all') {
            $query .= " AND order_status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        // Attach item count (fast — one query)
        if (!empty($orders)) {
            $ids = array_column($orders, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $itemStmt = $pdo->prepare("
                SELECT order_id, COUNT(*) as count, SUM(quantity) as total_qty
                FROM shop_order_items
                WHERE order_id IN ($placeholders)
                GROUP BY order_id
            ");
            $itemStmt->execute($ids);
            $itemMap = [];
            foreach ($itemStmt->fetchAll() as $row) {
                $itemMap[$row['order_id']] = $row;
            }
            foreach ($orders as &$o) {
                $o['item_count'] = isset($itemMap[$o['id']]) ? (int)$itemMap[$o['id']]['count'] : 0;
                $o['total_qty'] = isset($itemMap[$o['id']]) ? (int)$itemMap[$o['id']]['total_qty'] : 0;
            }
        }

        echo json_encode(['success' => true, 'data' => $orders]);
        exit;
    }

    if ($action === 'single') {
        $id = intval($_GET['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'Order ID required']); exit; }

        // Ensure the order belongs to this user
        $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $user['id']]);
        $order = $stmt->fetch();

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            exit;
        }

        // Attach items
        $itemStmt = $pdo->prepare("SELECT * FROM shop_order_items WHERE order_id = ?");
        $itemStmt->execute([$id]);
        $order['items'] = $itemStmt->fetchAll();

        // Attach history
        $histStmt = $pdo->prepare("SELECT * FROM shop_order_history WHERE order_id = ? ORDER BY created_at ASC");
        $histStmt->execute([$id]);
        $order['history'] = $histStmt->fetchAll();

        // Attach return requests (if any)
        $retStmt = $pdo->prepare("SELECT * FROM shop_returns WHERE order_id = ? ORDER BY created_at DESC");
        $retStmt->execute([$id]);
        $order['returns'] = $retStmt->fetchAll();

        // Compute action availability
        $settings = cache_remember('shop_settings', 300, function() use ($pdo) {
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM shop_settings");
            $map = [];
            foreach ($stmt->fetchAll() as $r) $map[$r['setting_key']] = $r['setting_value'];
            return $map;
        });

        $cancelUntil = $settings['cancel_window_status'] ?? 'shipped';
        $statusOrder = ['pending' => 0, 'confirmed' => 1, 'processing' => 2, 'shipped' => 3, 'delivered' => 4];

        $currentIdx = $statusOrder[$order['order_status']] ?? 99;
        $limitIdx = $statusOrder[$cancelUntil] ?? 3;

        $order['can_cancel'] = (
            !in_array($order['order_status'], ['cancelled', 'returned', 'refunded']) &&
            $currentIdx < $limitIdx
        );

        // Return window: within N days after delivered
        $returnDays = intval($settings['return_window_days'] ?? 7);
        $order['can_return'] = false;
        if ($order['order_status'] === 'delivered' && !empty($order['delivered_at'])) {
            $deliveredAt = strtotime($order['delivered_at']);
            $order['can_return'] = (time() - $deliveredAt) <= ($returnDays * 86400);
            $order['return_window_ends'] = date('Y-m-d H:i:s', $deliveredAt + ($returnDays * 86400));
        }

        $order['can_confirm_delivery'] = ($order['order_status'] === 'shipped');
        $order['has_active_return'] = false;
        foreach ($order['returns'] as $r) {
            if (in_array($r['status'], ['pending', 'approved'])) {
                $order['has_active_return'] = true;
                break;
            }
        }

        echo json_encode(['success' => true, 'data' => $order]);
        exit;
    }

    if ($action === 'stats') {
        // Overview stats for account dashboard
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN order_status IN ('pending','confirmed','processing','shipped') THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                COALESCE(SUM(CASE WHEN order_status NOT IN ('cancelled','refunded') THEN total ELSE 0 END), 0) as total_spent
            FROM shop_orders
            WHERE user_id = ?
        ");
        $stmt->execute([$user['id']]);
        $stats = $stmt->fetch();

        // Recent 3 orders
        $stmt = $pdo->prepare("SELECT id, order_number, total, order_status, created_at FROM shop_orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 3");
        $stmt->execute([$user['id']]);
        $stats['recent_orders'] = $stmt->fetchAll();

        echo json_encode(['success' => true, 'data' => $stats]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Unknown action']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}