<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$action = $data['action'] ?? 'login';

switch ($action) {
    case 'login':
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Username and password are required']);
            exit;
        }
        
        $result = loginUser($username, $password);
        echo json_encode($result);
        break;
        
    case 'logout':
        $result = logoutUser();
        echo json_encode($result);
        break;
        
    case 'check':
        if (isLoggedIn()) {
            echo json_encode([
                'success' => true,
                'logged_in' => true,
                'user' => getCurrentUser()
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'logged_in' => false,
                'user' => null
            ]);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
        break;
}
?>