<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once 'db-connection.php';
require_once __DIR__ . '/../includes/cache.php';
$pdo = getConnection();
if (!$pdo) { http_response_code(500); echo json_encode(['success' => false, 'message' => 'DB failed']); exit; }

$action = $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $category = $_GET['category'] ?? '';
            $search   = $_GET['search'] ?? '';
            $featured = $_GET['featured'] ?? '';
            $sort     = $_GET['sort'] ?? 'newest';

            // Pagination — accept BOTH page/per_page AND limit/offset
            $page    = max(1, intval($_GET['page'] ?? 1));
            $perPage = intval($_GET['per_page'] ?? $_GET['limit'] ?? 12);
            $perPage = max(1, min(100, $perPage));

            $offset = ($page - 1) * $perPage;

            // ---------- Build WHERE clause with NAMED params ----------
            $where = "WHERE p.is_active = 1";
            $params = [];

            if ($category) {
                $where .= " AND c.slug = :category";
                $params[':category'] = $category;
            }
            if ($search) {
                $where .= " AND (p.name LIKE :search1 OR p.short_description LIKE :search2 OR p.brand LIKE :search3)";
                $like = "%$search%";
                $params[':search1'] = $like;
                $params[':search2'] = $like;
                $params[':search3'] = $like;
            }
            if ($featured === '1') {
                $where .= " AND p.is_featured = 1";
            }

            // ---------- Count total ----------
            $countQuery = "SELECT COUNT(*) AS total
                           FROM shop_products p
                           JOIN shop_categories c ON p.category_id = c.id
                           $where";
            $countStmt = $pdo->prepare($countQuery);
            $countStmt->execute($params);
            $total = (int)$countStmt->fetch()['total'];

            // ---------- ORDER BY ----------
            $orderBy = "ORDER BY p.created_at DESC";
            switch ($sort) {
                case 'price_asc':  $orderBy = "ORDER BY p.price ASC"; break;
                case 'price_desc': $orderBy = "ORDER BY p.price DESC"; break;
                case 'name':       $orderBy = "ORDER BY p.name ASC"; break;
            }

            // ---------- Fetch page (all named params, no mixing) ----------
            $query = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
                      FROM shop_products p
                      JOIN shop_categories c ON p.category_id = c.id
                      $where
                      $orderBy
                      LIMIT :limit OFFSET :offset";

            $stmt = $pdo->prepare($query);

            // Bind WHERE params (all strings)
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            // Bind LIMIT/OFFSET as integers
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $products = $stmt->fetchAll();

            echo json_encode([
                'success'  => true,
                'data'     => $products,
                'total'    => $total,
                'page'     => $page,
                'per_page' => $perPage,
                'pages'    => max(1, (int)ceil($total / $perPage))
            ]);
            break;

        case 'single':
            $slug = $_GET['slug'] ?? '';
            if (!$slug) { echo json_encode(['success' => false, 'message' => 'Slug required']); exit; }

            $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug
                                   FROM shop_products p
                                   JOIN shop_categories c ON p.category_id = c.id
                                   WHERE p.slug = ? AND p.is_active = 1 LIMIT 1");
            $stmt->execute([$slug]);
            $product = $stmt->fetch();

            if (!$product) { echo json_encode(['success' => false, 'message' => 'Not found']); exit; }

            $stmt = $pdo->prepare("SELECT id, name, slug, price, image FROM shop_products
                                   WHERE category_id = ? AND id != ? AND is_active = 1 LIMIT 4");
            $stmt->execute([$product['category_id'], $product['id']]);
            $product['related'] = $stmt->fetchAll();

            echo json_encode(['success' => true, 'data' => $product]);
            break;

        case 'categories':
            $categories = cache_remember('shop_categories', 900, function() use ($pdo) {
                $stmt = $pdo->query("SELECT * FROM shop_categories WHERE is_active = 1 ORDER BY display_order ASC");
                return $stmt->fetchAll();
            });
            echo json_encode(['success' => true, 'data' => $categories]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}