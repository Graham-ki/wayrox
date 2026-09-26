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

$data = json_decode(file_get_contents('php://input'), true);
$orderId = intval($data['order_id'] ?? 0);

if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Order ID required']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ? AND user_id = ? FOR UPDATE");
    $stmt->execute([$orderId, $user['id']]);
    $order = $stmt->fetch();

    if (!$order) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    if ($order['order_status'] !== 'shipped') {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'This order is not awaiting delivery confirmation']);
        exit;
    }

    $receiptNumber = 'RCP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    $settings = cache_remember('shop_settings', 300, function() use ($pdo) {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM shop_settings");
        $map = [];
        foreach ($stmt->fetchAll() as $r) $map[$r['setting_key']] = $r['setting_value'];
        return $map;
    });
    $returnDays = intval($settings['return_window_days'] ?? 7);
    $returnWindowEnds = date('Y-m-d H:i:s', time() + ($returnDays * 86400));

    $updateStmt = $pdo->prepare("
        UPDATE shop_orders
        SET order_status = 'delivered',
            delivered_at = NOW(),
            receipt_number = ?,
            receipt_issued_at = NOW(),
            return_window_ends_at = ?
        WHERE id = ?
    ");
    $updateStmt->execute([$receiptNumber, $returnWindowEnds, $orderId]);

    $histStmt = $pdo->prepare("
        INSERT INTO shop_order_history (order_id, status, note, changed_by)
        VALUES (?, 'delivered', ?, ?)
    ");
    $histStmt->execute([
        $orderId,
        'Receipt issued: ' . $receiptNumber . '. Return window open until ' . $returnWindowEnds,
        $user['id']
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'receipt_number' => $receiptNumber,
        'return_window_ends' => $returnWindowEnds,
        'message' => 'Delivery confirmed. Your receipt has been issued.'
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}