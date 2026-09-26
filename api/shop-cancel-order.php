<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db-connection.php';
require_once __DIR__ . '/../includes/cache.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in']);
    exit;
}

$user = getCurrentUser();
$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = intval($data['order_id'] ?? 0);
$reason = trim($data['reason'] ?? '');

if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Order ID required']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Lock the order row
    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ? AND user_id = ? FOR UPDATE");
    $stmt->execute([$orderId, $user['id']]);
    $order = $stmt->fetch();

    if (!$order) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // Check status
    if (in_array($order['order_status'], ['cancelled', 'returned', 'refunded'])) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'This order cannot be cancelled']);
        exit;
    }

    // Check cancel window
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

    if ($currentIdx >= $limitIdx) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Orders can no longer be cancelled once they reach "' . $cancelUntil . '" status. Please request a return instead.'
        ]);
        exit;
    }

    // Restock items
    $itemStmt = $pdo->prepare("SELECT product_id, quantity FROM shop_order_items WHERE order_id = ?");
    $itemStmt->execute([$orderId]);
    $items = $itemStmt->fetchAll();

    $restockStmt = $pdo->prepare("UPDATE shop_products SET stock_quantity = stock_quantity + ? WHERE id = ?");
    foreach ($items as $item) {
        $restockStmt->execute([$item['quantity'], $item['product_id']]);
    }

    // Update order
    $updateStmt = $pdo->prepare("
        UPDATE shop_orders 
        SET order_status = 'cancelled',
            cancellation_reason = ?,
            cancelled_at = NOW()
        WHERE id = ?
    ");
    $updateStmt->execute([$reason ?: 'Cancelled by customer', $orderId]);

    // Log history
    $histStmt = $pdo->prepare("
        INSERT INTO shop_order_history (order_id, status, note, changed_by)
        VALUES (?, 'cancelled', ?, ?)
    ");
    $histStmt->execute([$orderId, 'Cancelled by customer: ' . ($reason ?: 'No reason provided'), $user['id']]);

    $pdo->commit();

    // Bust caches that may include this order
    cache_forget_pattern('products_*'); // stock changed

    echo json_encode([
        'success' => true,
        'message' => 'Order cancelled successfully. A refund will be processed shortly.'
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}