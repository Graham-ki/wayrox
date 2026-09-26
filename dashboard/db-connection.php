<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'wayr_data');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
function getConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}
/**
 * Quick query helper with optional caching
 */
function db_query_cached($cacheKey, $ttl, $sql, $params = []) {
    require_once __DIR__ . '/../includes/cache.php';
    return cache_remember($cacheKey, $ttl, function() use ($sql, $params) {
        $pdo = getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    });
}

function db_execute($sql, $params = []) {
    $pdo = getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}
?>