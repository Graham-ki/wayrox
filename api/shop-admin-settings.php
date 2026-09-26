<?php
//session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '../../config/auth.php';
require_once __DIR__ . '../../config/db-connection.php';
require_once __DIR__ . '../../includes/cache.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user = getCurrentUser();
if (!in_array($user['role'], ['admin', 'editor'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$settings = $data['settings'] ?? [];

if (empty($settings)) {
    echo json_encode(['success' => false, 'message' => 'No settings provided']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO shop_settings (setting_key, setting_value)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
    ");

    foreach ($settings as $key => $value) {
        $stmt->execute([$key, $value]);
    }

    // Bust the cache so next request gets fresh values
    cache_forget('shop_settings');

    echo json_encode(['success' => true, 'message' => 'Settings saved']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}