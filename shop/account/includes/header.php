<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../config/db-connection.php';

if (!isLoggedIn()) {
    header('Location: ../../login.php?redirect=' . urlencode('shop/account/index.php'));
    exit;
}

$accountUser = getCurrentUser();
if (!$accountUser) {
    header('Location: ../../login.php');
    exit;
}

// Auto-detect current page from URL
$pageMap = [
    'index.php'         => 'overview',
    'orders.php'        => 'orders',
    'order-details.php' => 'orders',
    'returns.php'       => 'returns',
    'profile.php'       => 'profile',
    'invoice.php'       => 'orders',
    'receipt.php'       => 'orders',
];
$currentFile = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$accountPage = $accountPage ?? ($pageMap[$currentFile] ?? 'overview');
$currentAccountPage = $accountPage;
$pageTitle = $pageTitle ?? 'My Account';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?> — WayronX</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/shop.css">
<style>
    /* =========================================================
       ACCOUNT LAYOUT — fully responsive
       ========================================================= */
    .account-topnav {
        background: var(--midnight, #050816);
        color: #fff;
        padding: 14px 0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .account-topnav-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .account-topnav .shop-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        text-decoration: none;
        min-width: 0;
    }
    .account-topnav .shop-logo svg { width: 26px; height: 26px; flex-shrink: 0; }
    .account-topnav .shop-logo .badge {
        font-size: 0.62rem;
        padding: 3px 8px;
        background: linear-gradient(120deg, #2563FF, #7C3AED);
        border-radius: 20px;
        margin-left: 6px;
        font-weight: 700;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }
    .account-topnav-links {
        display: flex;
        align-items: center;
        gap: 22px;
        font-size: 0.9rem;
    }
    .account-topnav-links a {
        color: rgba(255,255,255,0.7);
        font-weight: 500;
        transition: color 0.2s;
        text-decoration: none;
    }
    .account-topnav-links a:hover { color: #fff; }
    .account-topnav-links a.active { color: #fff; font-weight: 700; }

    .account-topnav-user {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }
    .account-topnav-user .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(120deg, #2563FF, #7C3AED);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #fff;
    }
    .account-topnav-user .logout-icon {
        color: rgba(255,255,255,0.6);
        font-size: 1.1rem;
        transition: color 0.2s;
        text-decoration: none;
    }
    .account-topnav-user .logout-icon:hover { color: #fff; }

    /* SHELL — the whole page container */
    .account-shell {
        max-width: 1200px;
        margin: 0 auto;
        padding: 28px 20px 60px;
        display: grid;
        grid-template-columns: 240px minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    /* SIDEBAR */
    .account-sidebar {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        padding: 16px 0;
        height: fit-content;
        position: sticky;
        top: 90px;
        box-shadow: 0 4px 20px rgba(11,16,38,0.04);
    }
    .account-sidebar h3 {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink-mute, #5A6180);
        font-weight: 700;
        padding: 0 20px 12px;
    }
    .account-sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 20px;
        font-size: 0.92rem;
        font-weight: 500;
        color: var(--ink-mute, #5A6180);
        transition: all 0.2s;
        border-left: 3px solid transparent;
        text-decoration: none;
    }
    .account-sidebar a:hover {
        background: var(--light, #F7F8FC);
        color: var(--ink, #0B1026);
    }
    .account-sidebar a.active {
        background: linear-gradient(120deg, rgba(37,99,255,0.08), rgba(124,58,237,0.08));
        color: var(--blue, #2563FF);
        font-weight: 700;
        border-left-color: var(--blue, #2563FF);
    }
    .account-sidebar a .icon {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .account-sidebar a.divider {
        border-top: 1px solid var(--line, rgba(11,16,38,0.1));
        margin-top: 12px;
        padding-top: 16px;
    }

    /* CONTENT */
    .account-content {
        min-width: 0;
        max-width: 100%;
    }

    /* LIFTED CARD */
    .acct-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        padding: 24px;
        box-shadow:
            0 4px 20px rgba(11,16,38,0.05),
            0 1px 3px rgba(11,16,38,0.03);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }
    .acct-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(120deg, #2563FF, #7C3AED);
        opacity: 0.85;
    }
    .acct-card h2 {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        color: var(--ink, #0B1026);
        letter-spacing: -0.01em;
    }
    .acct-card h2 a {
        font-size: 0.82rem;
        color: var(--blue, #2563FF);
        font-weight: 600;
        text-decoration: none;
    }
    .acct-card h2 a:hover { text-decoration: underline; }

    /* PAGE HEADER */
    .acct-page-header {
        margin-bottom: 20px;
    }
    .acct-page-header h1 {
        font-size: clamp(1.3rem, 3vw, 1.6rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 6px;
        color: var(--ink, #0B1026);
    }
    .acct-page-header p {
        color: var(--ink-mute, #5A6180);
        font-size: 0.92rem;
    }
    .acct-page-header .breadcrumb {
        font-size: 0.82rem;
        color: var(--ink-mute, #5A6180);
        margin-bottom: 8px;
    }
    .acct-page-header .breadcrumb a {
        color: var(--blue, #2563FF);
        text-decoration: none;
    }
    .acct-page-header .breadcrumb span { margin: 0 8px; }

    /* SCROLLABLE TABLE WRAPPER */
    .acct-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0 -24px;
        padding: 0 24px;
        scrollbar-width: thin;
    }
    .acct-scroll::-webkit-scrollbar {
        height: 6px;
    }
    .acct-scroll::-webkit-scrollbar-thumb {
        background: rgba(11,16,38,0.15);
        border-radius: 3px;
    }
    .acct-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    /* MOBILE MENU TOGGLE */
    .account-mobile-toggle {
        display: none;
        background: #fff;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        border-radius: 12px;
        padding: 12px 18px;
        font-family: inherit;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        margin-bottom: 16px;
        width: 100%;
        text-align: left;
        justify-content: space-between;
        align-items: center;
        color: var(--ink, #0B1026);
        box-shadow: 0 2px 8px rgba(11,16,38,0.04);
    }

    /* MOBILE */
    @media (max-width: 900px) {
        .account-shell {
            grid-template-columns: 1fr;
            gap: 16px;
            padding: 20px 16px 40px;
        }
        .account-sidebar {
            position: static;
            display: none;
        }
        .account-sidebar.open { display: block; }
        .account-mobile-toggle { display: flex; }
        .account-topnav-inner { padding: 0 16px; }
        .account-topnav-links { display: none; }
        .acct-card { padding: 20px 16px; border-radius: 14px; }
        .acct-scroll {
            margin: 0 -16px;
            padding: 0 16px;
        }
    }
    @media (max-width: 480px) {
        .account-shell { padding: 16px 12px 32px; }
        .account-topnav .shop-logo .badge { display: none; }
        .acct-card h2 { font-size: 1rem; }
    }
</style>
</head>
<body>

<header class="account-topnav">
    <div class="account-topnav-inner">
        <a href="../index.php" class="shop-logo">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 4L20 20M20 4L4 20" stroke="url(#acctGrad)" stroke-width="2.6" stroke-linecap="round"/>
                <defs>
                    <linearGradient id="acctGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#2563FF"/>
                        <stop offset="1" stop-color="#7C3AED"/>
                    </linearGradient>
                </defs>
            </svg>
            <span>WayronX</span>
            <span class="badge">MY ACCOUNT</span>
        </a>

        <nav class="account-topnav-links">
            <a href="../index.php">Shop</a>
            <a href="orders.php" class="<?php echo $accountPage === 'orders' ? 'active' : ''; ?>">Orders</a>
            <a href="returns.php" class="<?php echo $accountPage === 'returns' ? 'active' : ''; ?>">Returns</a>
            <a href="profile.php" class="<?php echo $accountPage === 'profile' ? 'active' : ''; ?>">Profile</a>
        </nav>

        <div class="account-topnav-user">
            <div class="user-avatar"><?php echo strtoupper(substr(($accountUser['full_name'] ?? 'U'), 0, 1)); ?></div>
            <a href="../logout.php" class="logout-icon" title="Logout">🚪</a>
        </div>
    </div>
</header>

<div class="account-shell">

    <button class="account-mobile-toggle" onclick="document.getElementById('accountSidebar').classList.toggle('open')">
        <span>☰ Account Menu</span>
        <span>▾</span>
    </button>

    <aside class="account-sidebar" id="accountSidebar">
        <h3>Account Menu</h3>
        <a href="index.php" class="<?php echo $accountPage === 'overview' ? 'active' : ''; ?>">
            <span class="icon">🏠</span> Overview
        </a>
        <a href="orders.php" class="<?php echo $accountPage === 'orders' ? 'active' : ''; ?>">
            <span class="icon">📦</span> My Orders
        </a>
        <a href="returns.php" class="<?php echo $accountPage === 'returns' ? 'active' : ''; ?>">
            <span class="icon">↩️</span> Returns & Exchanges
        </a>
        <a href="profile.php" class="<?php echo $accountPage === 'profile' ? 'active' : ''; ?>">
            <span class="icon">👤</span> Profile
        </a>
        <a href="../index.php" class="divider">
            <span class="icon">🛍️</span> Continue Shopping
        </a>
    </aside>

    <main class="account-content">