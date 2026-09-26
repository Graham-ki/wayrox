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
$returnType = $data['return_type'] ?? 'refund'; // refund | exchange
$reason = trim($data['reason'] ?? '');
$itemIds = $data['items'] ?? []; // array of order_item ids

if (!$orderId || !$reason) {
    echo json_encode(['success' => false, 'message' => 'Order ID and reason required']);
    exit;
}

if (!in_array($returnType, ['refund', 'exchange'])) {
    $returnType = 'refund';
}

try {
    $pdo->beginTransaction();

    // Verify order belongs to user
    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE id = ? AND user_id = ? FOR UPDATE");
    $stmt->execute([$orderId, $user['id']]);
    $order = $stmt->fetch();

    if (!$order) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    if ($order['order_status'] !== 'delivered') {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Only delivered orders can be returned']);
        exit;
    }

    // Check return window
    $settings = cache_remember('shop_settings', 300, function() use ($pdo) {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM shop_settings");
        $map = [];
        foreach ($stmt->fetchAll() as $r) $map[$r['setting_key']] = $r['setting_value'];
        return $map;
    });

    $returnDays = intval($settings['return_window_days'] ?? 7);
    $deliveredAt = $order['delivered_at'] ? strtotime($order['delivered_at']) : null;

    if (!$deliveredAt || (time() - $deliveredAt) > ($returnDays * 86400)) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'The return window for this order has expired']);
        exit;
    }

    // Check for existing active return
    $checkStmt = $pdo->prepare("
        SELECT id FROM shop_returns 
        WHERE order_id = ? AND status IN ('pending', 'approved')
        LIMIT 1
    ");
    $checkStmt->execute([$orderId]);
    if ($checkStmt->fetch()) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'You already have an active return for this order']);
        exit;
    }

    // Calculate refund amount
    $totalRefund = 0;
    $itemsToReturn = [];

    if (!empty($itemIds)) {
        $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
        $itemStmt = $pdo->prepare("
            SELECT * FROM shop_order_items 
            WHERE id IN ($placeholders) AND order_id = ?
        ");
        $params = $itemIds;
        $params[] = $orderId;
        $itemStmt->execute($params);
        $itemsToReturn = $itemStmt->fetchAll();
    } else {
        // Full order return
        $itemStmt = $pdo->prepare("SELECT * FROM shop_order_items WHERE order_id = ?");
        $itemStmt->execute([$orderId]);
        $itemsToReturn = $itemStmt->fetchAll();
    }

    if (empty($itemsToReturn)) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'No items selected for return']);
        exit;
    }

    foreach ($itemsToReturn as $item) {
        $totalRefund += floatval($item['subtotal']);
    }

    // Auto-approve if setting is on
    $autoApprove = ($settings['auto_approve_returns'] ?? '1') === '1';
    $initialStatus = $autoApprove ? 'approved' : 'pending';

    // Insert return record
    $insertStmt = $pdo->prepare("
        INSERT INTO shop_returns 
        (order_id, user_id, reason, return_type, status, refund_amount)
        VALUES (?,?,?,?,?,?)
    ");
    $insertStmt->execute([
        $orderId,
        $user['id'],
        $reason,
        $returnType,
        $initialStatus,
        $totalRefund
    ]);
    $returnId = $pdo->lastInsertId();

    // Insert return items
    $retItemStmt = $pdo->prepare("
        INSERT INTO shop_return_items (return_id, order_item_id, quantity)
        VALUES (?,?,?)
    ");
    foreach ($itemsToReturn as $item) {
        $retItemStmt->execute([$returnId, $item['id'], $item['quantity']]);
    }

    // Log history on the order
    $histStmt = $pdo->prepare("
        INSERT INTO shop_order_history (order_id, status, note, changed_by)
        VALUES (?, ?, ?, ?)
    ");
    $histStmt->execute([
        $orderId,
        $order['order_status'],
        'Return/Exchange request submitted (' . $returnType . ')',
        $user['id']
    ]);

    $pdo->commit();

    // Bust product cache (stock will change once processed)
    cache_forget_pattern('products_*');

    echo json_encode([
        'success' => true,
        'return_id' => $returnId,
        'status' => $initialStatus,
        'message' => $autoApprove
            ? 'Return request approved. Please ship the item(s) back within 7 days.'
            : 'Return request submitted. You will be notified once reviewed.',
        'refund_amount' => $totalRefund
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}