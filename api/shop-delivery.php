<?php
//session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/auth.php';
//require_once __DIR__ . '/../config/db-connection.php';
require_once __DIR__ . '/../includes/cache.php';

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

$method = $_SERVER['REQUEST_METHOD'];

try {
    // ============ GET — LIST ============
    if ($method === 'GET') {
        $action = $_GET['action'] ?? 'list';

        if ($action === 'list') {
            if (!isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Not authenticated']);
                exit;
            }
            $stmt = $pdo->query("SELECT * FROM shop_delivery_regions ORDER BY display_order, name");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            exit;
        }

        if ($action === 'public') {
            $regions = cache_remember('shop_regions', 600, function() use ($pdo) {
                $stmt = $pdo->query("
                    SELECT id, name, slug, delivery_fee, estimated_days, description
                    FROM shop_delivery_regions
                    WHERE is_active = 1
                    ORDER BY display_order, name
                ");
                return $stmt->fetchAll();
            });
            echo json_encode(['success' => true, 'data' => $regions]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Unknown GET action']);
        exit;
    }

    // ============ POST — WRITE ============
    if ($method === 'POST') {
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

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) { echo json_encode(['success' => false, 'message' => 'Invalid data']); exit; }

        $action = $data['action'] ?? '';

        switch ($action) {
            case 'create':
                $name = trim($data['name'] ?? '');
                if (!$name) { echo json_encode(['success' => false, 'message' => 'Name required']); exit; }

                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
                // Ensure unique slug
                $check = $pdo->prepare("SELECT id FROM shop_delivery_regions WHERE slug = ?");
                $check->execute([$slug]);
                if ($check->fetch()) {
                    $slug .= '-' . time();
                }

                $stmt = $pdo->prepare("
                    INSERT INTO shop_delivery_regions 
                    (name, slug, delivery_fee, estimated_days, description, is_active)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $name,
                    $slug,
                    floatval($data['delivery_fee'] ?? 0),
                    $data['estimated_days'] ?? '',
                    $data['description'] ?? '',
                    intval($data['is_active'] ?? 1)
                ]);

                cache_forget('shop_regions');
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
                break;

            case 'update':
                $id = intval($data['id'] ?? 0);
                $name = trim($data['name'] ?? '');
                if (!$id || !$name) { echo json_encode(['success' => false, 'message' => 'ID and name required']); exit; }

                $stmt = $pdo->prepare("
                    UPDATE shop_delivery_regions 
                    SET name = ?, delivery_fee = ?, estimated_days = ?, description = ?, is_active = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $name,
                    floatval($data['delivery_fee'] ?? 0),
                    $data['estimated_days'] ?? '',
                    $data['description'] ?? '',
                    intval($data['is_active'] ?? 1),
                    $id
                ]);

                cache_forget('shop_regions');
                echo json_encode(['success' => true]);
                break;

            case 'delete':
                $id = intval($data['id'] ?? 0);
                if (!$id) { echo json_encode(['success' => false, 'message' => 'ID required']); exit; }

                $stmt = $pdo->prepare("DELETE FROM shop_delivery_regions WHERE id = ?");
                $stmt->execute([$id]);

                cache_forget('shop_regions');
                echo json_encode(['success' => true]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}