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
    $q = trim($_GET['q'] ?? '');
    $limit = min(50, max(1, intval($_GET['limit'] ?? 10)));
    $category = trim($_GET['category'] ?? '');

    if (strlen($q) < 2) {
        echo json_encode(['success' => true, 'data' => [], 'message' => 'Query too short']);
        exit;
    }

    // Cache per unique query+category+limit combo for 2 minutes
    $cacheKey = 'search_' . md5(serialize([$q, $category, $limit]));

    $results = cache_remember($cacheKey, 120, function() use ($pdo, $q, $category, $limit) {
        $query = "
            SELECT p.id, p.name, p.slug, p.price, p.sale_price, p.image,
                   p.stock_quantity, c.name AS category_name, c.slug AS category_slug
            FROM shop_products p
            JOIN shop_categories c ON p.category_id = c.id
            WHERE p.is_active = 1
              AND (p.name LIKE ? OR p.short_description LIKE ? OR p.brand LIKE ?)
        ";
        $like = '%' . $q . '%';
        $params = [$like, $like, $like];

        if ($category) {
            $query .= " AND c.slug = ?";
            $params[] = $category;
        }

        $query .= " ORDER BY 
                    CASE 
                        WHEN p.name LIKE ? THEN 1
                        WHEN p.name LIKE ? THEN 2
                        ELSE 3
                    END,
                    p.name ASC
                    LIMIT $limit";
        $params[] = $q . '%';      // starts with
        $params[] = '%' . $q . '%'; // contains

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    });

    echo json_encode(['success' => true, 'data' => $results, 'query' => $q]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}