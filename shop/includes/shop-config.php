<?php
/**
 * shop-config.php
 * 
 * Included at the very top of every shop and account page.
 * Handles session + auth setup ONCE. Outputs nothing.
 */

// Suppress warnings we don't want shown
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

// Start session safely
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Load auth + database
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db-connection.php';

// Current user
$shopUser = isLoggedIn() ? getCurrentUser() : null;
$isLoggedIn = $shopUser !== null;

// Current page for nav highlighting
$shopCurrentPage = $shopCurrentPage ?? 'shop';

// Cart count (from session)
$shopCartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $shopCartCount = array_sum($_SESSION['cart']);
}

// Base path helper for links (from shop/ root)
$shopBase = '/waynrox/shop';
?>