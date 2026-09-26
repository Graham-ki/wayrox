<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/db-connection.php';
require_once __DIR__ . '/../includes/cache.php';

$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

try {
    // Cache for 5 minutes
    $settings = cache_remember('shop_settings', 300, function() use ($pdo) {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM shop_settings");
        $rows = $stmt->fetchAll();
        $map = [];
        foreach ($rows as $r) {
            $map[$r['setting_key']] = $r['setting_value'];
        }
        return $map;
    });

    // HTTP cache for 60 seconds in browser
    cache_http_headers(60);

    echo json_encode(['success' => true, 'data' => $settings]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}