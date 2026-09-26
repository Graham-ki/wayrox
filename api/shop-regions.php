<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/cache.php';

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

try {
    // Cache regions for 10 minutes
    $regions = cache_remember('shop_regions', 600, function() use ($pdo) {
        $stmt = $pdo->query("
            SELECT id, name, slug, delivery_fee, estimated_days, description 
            FROM shop_delivery_regions 
            WHERE is_active = 1 
            ORDER BY display_order, name
        ");
        return $stmt->fetchAll();
    });

    cache_http_headers(300);

    echo json_encode(['success' => true, 'data' => $regions]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}