<?php
// Match the exact session cookie path used by shop/login.php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db-connection.php';

if (!isLoggedIn()) {
    echo json_encode(['logged_in' => false]);
    exit;
}

$user = getCurrentUser();

// Enrich with profile fields if the columns exist
$userData = [
    'id' => $user['id'] ?? null,
    'username' => $user['username'] ?? '',
    'full_name' => $user['full_name'] ?? '',
    'email' => $user['email'] ?? '',
    'role' => $user['role'] ?? 'customer',
    'phone' => '',
    'default_address' => '',
    'default_city' => '',
    'default_region_id' => null,
];

try {
    $pdo = getConnection();
    if ($pdo) {
        $stmt = $pdo->query("SHOW COLUMNS FROM users");
        $cols = [];
        foreach ($stmt->fetchAll() as $row) {
            $cols[] = $row['Field'];
        }

        $select = ['id'];
        if (in_array('phone', $cols)) $select[] = 'phone';
        if (in_array('default_address', $cols)) $select[] = 'default_address';
        if (in_array('default_city', $cols)) $select[] = 'default_city';
        if (in_array('default_region_id', $cols)) $select[] = 'default_region_id';

        $sql = "SELECT " . implode(', ', $select) . " FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user['id']]);
        $row = $stmt->fetch();

        if ($row) {
            if (isset($row['phone'])) $userData['phone'] = $row['phone'];
            if (isset($row['default_address'])) $userData['default_address'] = $row['default_address'];
            if (isset($row['default_city'])) $userData['default_city'] = $row['default_city'];
            if (isset($row['default_region_id'])) $userData['default_region_id'] = $row['default_region_id'];
        }
    }
} catch (Exception $e) {
    error_log('shop-auth-check enrichment failed: ' . $e->getMessage());
}

echo json_encode([
    'logged_in' => true,
    'user' => $userData,
    'is_admin' => in_array($userData['role'], ['admin', 'editor', 'viewer']),
    'is_customer' => $userData['role'] === 'customer',
]);