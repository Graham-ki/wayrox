<?php
/**
 * Simple file-based cache.
 * 
 * Usage:
 *   cache_set('regions', $data, 600);       // store for 10 minutes
 *   $regions = cache_get('regions');        // returns null if expired
 *   cache_forget('regions');                // force refresh
 *   cache_remember('regions', 600, fn() => fetch_from_db()); // combine
 */

define('CACHE_DIR', __DIR__ . '/../cache');

function cache_ensure_dir() {
    if (!is_dir(CACHE_DIR)) {
        @mkdir(CACHE_DIR, 0755, true);
    }
    // Write .htaccess to block direct access
    $htaccess = CACHE_DIR . '/.htaccess';
    if (!file_exists($htaccess)) {
        @file_put_contents($htaccess, "Deny from all\n");
    }
}

function cache_path($key) {
    // Sanitize key for filename
    $safe = preg_replace('/[^a-z0-9_\-]/i', '_', $key);
    return CACHE_DIR . '/' . $safe . '.cache.json';
}

function cache_set($key, $value, $ttlSeconds = 300) {
    cache_ensure_dir();
    $payload = [
        'expires_at' => time() + $ttlSeconds,
        'created_at' => time(),
        'data' => $value,
    ];
    @file_put_contents(cache_path($key), json_encode($payload), LOCK_EX);
}

function cache_get($key) {
    $path = cache_path($key);
    if (!file_exists($path)) return null;

    $raw = @file_get_contents($path);
    if ($raw === false) return null;

    $payload = json_decode($raw, true);
    if (!is_array($payload)) return null;

    if (!isset($payload['expires_at']) || $payload['expires_at'] < time()) {
        @unlink($path);
        return null;
    }

    return $payload['data'] ?? null;
}

function cache_forget($key) {
    $path = cache_path($key);
    if (file_exists($path)) @unlink($path);
}

function cache_forget_pattern($pattern) {
    // For clearing multiple keys, e.g. cache_forget_pattern('products_*')
    cache_ensure_dir();
    $files = glob(CACHE_DIR . '/' . $pattern . '.cache.json');
    if ($files) {
        foreach ($files as $f) @unlink($f);
    }
}

/**
 * Remember pattern:
 *   $data = cache_remember('regions', 600, function() use ($pdo) {
 *       $stmt = $pdo->query("SELECT * FROM shop_delivery_regions");
 *       return $stmt->fetchAll();
 *   });
 */
function cache_remember($key, $ttlSeconds, callable $callback) {
    $cached = cache_get($key);
    if ($cached !== null) return $cached;

    $fresh = $callback();
    cache_set($key, $fresh, $ttlSeconds);
    return $fresh;
}

/**
 * Send HTTP cache headers for public API endpoints
 */
function cache_http_headers($seconds = 60) {
    header('Cache-Control: public, max-age=' . $seconds . ', s-maxage=' . $seconds);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
}