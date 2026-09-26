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

$fullName = trim($data['full_name'] ?? '');
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$address = trim($data['default_address'] ?? '');
$city = trim($data['default_city'] ?? '');

if (!$fullName || !$username || !$email) {
    echo json_encode(['success' => false, 'message' => 'Name, username, and email are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

try {
    // Check if username/email taken by someone else
    $check = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $check->execute([$username, $email, $user['id']]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username or email already in use']);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE users 
        SET full_name = ?, username = ?, email = ?, phone = ?, default_address = ?, default_city = ?
        WHERE id = ?
    ");
    $stmt->execute([$fullName, $username, $email, $phone, $address, $city, $user['id']]);

    // Update session
    $_SESSION['full_name'] = $fullName;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;

    echo json_encode(['success' => true, 'message' => 'Profile updated']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}