<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db-connection.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// For POST requests, check if there's an action parameter in the body
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? $action;
}

$pdo = getConnection();

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

switch ($method) {
    case 'GET':
        // Get all messages or single message
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $message = $stmt->fetch();
            
            if ($message) {
                echo json_encode(['success' => true, 'data' => $message]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Message not found']);
            }
        } else {
            $filter = $_GET['filter'] ?? 'all';
            
            $query = "SELECT * FROM messages";
            $params = [];
            
            if ($filter === 'unread') {
                $query .= " WHERE is_read = 0";
            } elseif ($filter === 'read') {
                $query .= " WHERE is_read = 1";
            } elseif ($filter === 'important') {
                $query .= " WHERE is_important = 1";
            }
            
            $query .= " ORDER BY created_at DESC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $messages = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'data' => $messages]);
        }
        break;
        
    case 'POST':
        // Handle different actions
        switch ($action) {
            case 'create':
                // Create new message
                $stmt = $pdo->prepare("
                    INSERT INTO messages (name, email, phone, company, service, service_description)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([
                    $data['name'] ?? '',
                    $data['email'] ?? '',
                    $data['phone'] ?? null,
                    $data['company'] ?? null,
                    $data['service'] ?? null,
                    $data['service_description'] ?? null
                ]);
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Message created successfully',
                    'id' => $pdo->lastInsertId()
                ]);
                break;
                
            case 'update':
                // Update message (mark as read, important, etc.)
                if (!isset($data['id'])) {
                    echo json_encode(['success' => false, 'message' => 'Message ID required']);
                    break;
                }
                
                $fields = [];
                $params = [];
                
                if (isset($data['is_read'])) {
                    $fields[] = "is_read = ?";
                    $params[] = $data['is_read'];
                }
                
                if (isset($data['is_important'])) {
                    $fields[] = "is_important = ?";
                    $params[] = $data['is_important'];
                }
                
                if (empty($fields)) {
                    echo json_encode(['success' => false, 'message' => 'No fields to update']);
                    break;
                }
                
                $params[] = $data['id'];
                $query = "UPDATE messages SET " . implode(', ', $fields) . " WHERE id = ?";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute($params);
                
                echo json_encode(['success' => true, 'message' => 'Message updated successfully']);
                break;
                
            case 'delete':
                // Delete single message
                if (!isset($data['id'])) {
                    echo json_encode(['success' => false, 'message' => 'Message ID required']);
                    break;
                }
                
                $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
                $stmt->execute([$data['id']]);
                
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true, 'message' => 'Message deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Message not found']);
                }
                break;
                
            case 'delete-multiple':
                // Delete multiple messages
                if (!isset($data['ids']) || !is_array($data['ids']) || empty($data['ids'])) {
                    echo json_encode(['success' => false, 'message' => 'Message IDs required']);
                    break;
                }
                
                $placeholders = implode(',', array_fill(0, count($data['ids']), '?'));
                $stmt = $pdo->prepare("DELETE FROM messages WHERE id IN ($placeholders)");
                $stmt->execute($data['ids']);
                
                $deleted = $stmt->rowCount();
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Messages deleted successfully',
                    'deleted' => $deleted
                ]);
                break;
                
            case 'mark-all-read':
                // Mark all messages as read
                $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE is_read = 0");
                $stmt->execute();
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'All messages marked as read',
                    'updated' => $stmt->rowCount()
                ]);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
                break;
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>