<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/db-connection.php';

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$fullName = trim($data['full_name'] ?? '');
$email = trim($data['email'] ?? '');
$username = trim($data['username'] ?? '');
$phone = trim($data['phone'] ?? '');
$password = $data['password'] ?? '';

// Validate
if (!$fullName || !$email || !$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

if (strlen($username) < 3) {
    echo json_encode(['success' => false, 'message' => 'Username must be at least 3 characters']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit;
}

try {
    // Check for existing user
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check->execute([$email, $username]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email or username already registered']);
        exit;
    }

    // Insert
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, full_name, phone, role, is_active)
        VALUES (?, ?, ?, ?, ?, 'customer', 1)
    ");
    $stmt->execute([$username, $email, $password, $fullName, $phone]);

    echo json_encode([
        'success' => true,
        'user_id' => $pdo->lastInsertId(),
        'message' => 'Account created successfully'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}