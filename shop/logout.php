<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/../config/auth.php';

$currentUser = isLoggedIn() ? getCurrentUser() : null;

// If not logged in, redirect to login
if (!$currentUser) {
    header('Location: login.php');
    exit;
}

// Handle confirmation
$confirmed = $_GET['confirm'] ?? null;
if ($confirmed === '1') {
    logoutUser();
    header('Location: login.php?loggedout=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logout — WayronX Shop</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .logout-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 15% 20%, rgba(37,99,255,0.15), transparent 40%),
            radial-gradient(circle at 85% 80%, rgba(124,58,237,0.15), transparent 40%),
            linear-gradient(135deg, #050816 0%, #0B1026 100%);
        padding: 24px;
    }
    .logout-card {
        background: rgba(255,255,255,0.97);
        border-radius: 24px;
        padding: 44px 40px;
        width: 100%;
        max-width: 440px;
        text-align: center;
        box-shadow: 0 30px 80px rgba(0,0,0,0.4);
    }
    .logout-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--grad-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.4rem;
        margin: 0 auto 20px;
    }
    .logout-card h1 {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 10px;
    }
    .logout-card p {
        color: var(--ink-mute);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 8px;
    }
    .user-preview {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: var(--light);
        border-radius: 12px;
        margin: 24px 0;
        text-align: left;
    }
    .user-avatar-lg {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--grad);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .user-preview strong {
        display: block;
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .user-preview span {
        display: block;
        font-size: 0.82rem;
        color: var(--ink-mute);
        word-break: break-all;
    }
    .logout-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
    }
    .btn-cancel {
        flex: 1;
        padding: 14px;
        background: var(--light);
        color: var(--ink);
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s;
    }
    .btn-cancel:hover {
        background: var(--grad-soft);
        color: var(--blue);
    }
    .btn-logout {
        flex: 1;
        padding: 14px;
        background: var(--grad);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s;
    }
    .btn-logout:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(37,99,255,0.3);
    }
</style>
</head>
<body>

<div class="logout-page">
    <div class="logout-card">
        <div class="logout-icon">🚪</div>
        <h1>Log out?</h1>
        <p>You're about to end your session. You'll need to sign in again to place orders.</p>

        <div class="user-preview">
            <div class="user-avatar-lg"><?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?></div>
            <div>
                <strong><?php echo htmlspecialchars($currentUser['full_name']); ?></strong>
                <span><?php echo htmlspecialchars($currentUser['email']); ?></span>
            </div>
        </div>

        <div class="logout-actions">
            <a href="index.php" class="btn-cancel">Stay Logged In</a>
            <a href="logout.php?confirm=1" class="btn-logout">Yes, Log Out</a>
        </div>
    </div>
</div>

</body>
</html>