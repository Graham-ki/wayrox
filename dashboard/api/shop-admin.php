<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once '../config/auth.php';
require_once '../config/database.php';

// Auth check
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    // GET actions
    if ($method === 'GET') {
        switch ($action) {
            case 'categories':
                $stmt = $pdo->query("SELECT * FROM shop_categories ORDER BY display_order, name");
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
                break;

            case 'products':
                $stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM shop_products p LEFT JOIN shop_categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
                break;

            case 'orders':
                $status = $_GET['status'] ?? '';
                if ($status) {
                    $stmt = $pdo->prepare("SELECT * FROM shop_orders WHERE order_status = ? ORDER BY created_at DESC");
                    $stmt->execute([$status]);
                } else {
                    $stmt = $pdo->query("SELECT * FROM shop_orders ORDER BY created_at DESC");
                }
                $orders = $stmt->fetchAll();

                // Attach items to each order
                $itemStmt = $pdo->prepare("SELECT * FROM shop_order_items WHERE order_id = ?");
                foreach ($orders as &$o) {
                    $itemStmt->execute([$o['id']]);
                    $o['items'] = $itemStmt->fetchAll();
                }

                echo json_encode(['success' => true, 'data' => $orders]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
        exit;
    }

    // POST actions
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? $action;

        switch ($action) {
            case 'create-product':
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $data['name'])) . '-' . time();
                $stmt = $pdo->prepare("INSERT INTO shop_products (category_id, name, slug, short_description, description, brand, sku, price, stock_quantity, image, is_featured) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->execute([
                    $data['category_id'], $data['name'], $slug,
                    $data['short_description'] ?? '', $data['description'] ?? '',
                    $data['brand'] ?? '', $data['sku'] ?? null,
                    $data['price'], $data['stock_quantity'],
                    $data['image'] ?? '', $data['is_featured'] ?? 0
                ]);
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
                break;

            case 'update-product':
                $stmt = $pdo->prepare("UPDATE shop_products SET category_id=?, name=?, short_description=?, description=?, brand=?, sku=?, price=?, stock_quantity=?, image=?, is_featured=? WHERE id=?");
                $stmt->execute([
                    $data['category_id'], $data['name'],
                    $data['short_description'] ?? '', $data['description'] ?? '',
                    $data['brand'] ?? '', $data['sku'] ?? null,
                    $data['price'], $data['stock_quantity'],
                    $data['image'] ?? '', $data['is_featured'] ?? 0,
                    $data['id']
                ]);
                echo json_encode(['success' => true]);
                break;

            case 'delete-product':
                $stmt = $pdo->prepare("DELETE FROM shop_products WHERE id = ?");
                $stmt->execute([$data['id']]);
                echo json_encode(['success' => true]);
                break;

            case 'update-order-status':
                $stmt = $pdo->prepare("UPDATE shop_orders SET order_status = ? WHERE id = ?");
                $stmt->execute([$data['status'], $data['id']]);
                echo json_encode(['success' => true]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
        }
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}