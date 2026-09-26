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

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user = getCurrentUser();
$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$current = $data['current_password'] ?? '';
$new = $data['new_password'] ?? '';

if (!$current || !$new) {
    echo json_encode(['success' => false, 'message' => 'Both fields required']);
    exit;
}

if (strlen($new) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit;
}

// Verify current password (plain text per your setup)
$stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND password = ?");
$stmt->execute([$user['id'], $current]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}

// Update password
$update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->execute([$new, $user['id']]);

echo json_encode(['success' => true, 'message' => 'Password updated']);