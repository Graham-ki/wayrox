<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once '../config/db-connection.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

$pdo = getConnection();

function cartWithDetails($pdo, $cart) {
    if (empty($cart)) return ['items' => [], 'subtotal' => 0, 'count' => 0];
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, slug, price, image, stock_quantity FROM shop_products WHERE id IN ($placeholders) AND is_active = 1");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    $items = [];
    $subtotal = 0;
    $count = 0;
    foreach ($products as $p) {
        $qty = $cart[$p['id']];
        $line = $p['price'] * $qty;
        $subtotal += $line;
        $count += $qty;
        $items[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'slug' => $p['slug'],
            'price' => $p['price'],
            'image' => $p['image'],
            'stock_quantity' => $p['stock_quantity'],
            'quantity' => $qty,
            'line_total' => $line
        ];
    }
    return ['items' => $items, 'subtotal' => $subtotal, 'count' => $count];
}

switch ($action) {
    case 'add':
        $productId = intval($data['product_id'] ?? 0);
        $qty = max(1, intval($data['quantity'] ?? 1));
        if (!$productId) { echo json_encode(['success' => false, 'message' => 'Product ID required']); exit; }

        $stmt = $pdo->prepare("SELECT stock_quantity FROM shop_products WHERE id = ? AND is_active = 1");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        if (!$product) { echo json_encode(['success' => false, 'message' => 'Product not found']); exit; }

        $newQty = ($_SESSION['cart'][$productId] ?? 0) + $qty;
        if ($newQty > $product['stock_quantity']) $newQty = $product['stock_quantity'];
        $_SESSION['cart'][$productId] = $newQty;

        echo json_encode(['success' => true, 'cart' => cartWithDetails($pdo, $_SESSION['cart'])]);
        break;

    case 'update':
        $productId = intval($data['product_id'] ?? 0);
        $qty = intval($data['quantity'] ?? 0);
        if ($qty <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $qty;
        }
        echo json_encode(['success' => true, 'cart' => cartWithDetails($pdo, $_SESSION['cart'])]);
        break;

    case 'remove':
        $productId = intval($data['product_id'] ?? 0);
        unset($_SESSION['cart'][$productId]);
        echo json_encode(['success' => true, 'cart' => cartWithDetails($pdo, $_SESSION['cart'])]);
        break;

    case 'get':
        echo json_encode(['success' => true, 'cart' => cartWithDetails($pdo, $_SESSION['cart'])]);
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'cart' => cartWithDetails($pdo, [])]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
}