<?php
// Shop-specific login page
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

require_once __DIR__ . '/../config/auth.php';

// If already logged in, go where they were headed
if (isLoggedIn()) {
    $redirect = $_GET['redirect'] ?? null;
    $user = getCurrentUser();

    // Shop users always land in shop account area
    $destination = 'account/index.php';

    if ($redirect && !str_contains($redirect, '://') && !str_starts_with($redirect, '//')) {
        // If redirect is "shop/something", strip leading "shop/"
        if (str_starts_with($redirect, 'shop/')) {
            $redirect = substr($redirect, 5);
        }
        // If redirect points back to a shop page, send them there
        $destination = $redirect;
    }

    header('Location: ' . $destination);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — WayronX Shop</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 15% 20%, rgba(37,99,255,0.15), transparent 40%),
            radial-gradient(circle at 85% 80%, rgba(124,58,237,0.15), transparent 40%),
            linear-gradient(135deg, #050816 0%, #0B1026 100%);
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    .login-page::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background:
            radial-gradient(circle at 50% 50%, rgba(37,99,255,0.06), transparent 60%);
        pointer-events: none;
    }

    .login-card {
        position: relative;
        z-index: 1;
        background: rgba(255,255,255,0.97);
        border-radius: 24px;
        padding: 44px 40px;
        width: 100%;
        max-width: 440px;
        box-shadow:
            0 30px 80px rgba(0,0,0,0.4),
            0 0 0 1px rgba(255,255,255,0.1) inset;
        backdrop-filter: blur(20px);
    }

    .login-logo {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin-bottom: 30px;
    }
    .login-logo .logo-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .login-logo .logo-mark svg { width: 44px; height: 44px; }
    .login-logo .logo-mark span {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -0.02em;
    }
    .login-logo .shop-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        background: var(--grad-soft);
        color: var(--blue);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .login-title {
        text-align: center;
        margin-bottom: 28px;
    }
    .login-title h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 6px;
        letter-spacing: -0.02em;
    }
    .login-title p {
        color: var(--ink-mute);
        font-size: 0.9rem;
    }

    .login-error {
        display: none;
        padding: 12px 16px;
        background: rgba(239,68,68,0.08);
        color: var(--danger);
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 0.88rem;
        font-weight: 500;
        text-align: center;
        border: 1px solid rgba(239,68,68,0.2);
    }
    .login-error.active { display: block; }

    .form-group {
        margin-bottom: 18px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--ink);
    }
    .input-wrap {
        position: relative;
    }
    .input-wrap input {
        width: 100%;
        padding: 14px 16px 14px 46px;
        border: 2px solid var(--line);
        border-radius: 12px;
        font-family: inherit;
        font-size: 0.95rem;
        background: #fff;
        color: var(--ink);
        transition: all 0.2s;
    }
    .input-wrap input:focus {
        outline: none;
        border-color: var(--blue);
        box-shadow: 0 0 0 4px rgba(37,99,255,0.1);
    }
    .input-wrap .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.1rem;
        opacity: 0.6;
        pointer-events: none;
    }
    .input-wrap .toggle-password {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        opacity: 0.5;
        padding: 4px;
        transition: opacity 0.2s;
    }
    .input-wrap .toggle-password:hover { opacity: 1; }

    .form-row-between {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        font-size: 0.88rem;
    }
    .remember-box {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .remember-box input {
        width: auto;
        accent-color: var(--blue);
        cursor: pointer;
    }
    .remember-box label {
        color: var(--ink-mute);
        cursor: pointer;
        font-weight: 500;
    }
    .forgot-link {
        color: var(--blue);
        font-weight: 600;
    }
    .forgot-link:hover { text-decoration: underline; }

    .btn-login {
        width: 100%;
        padding: 15px;
        background: var(--grad);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
    }
    .btn-login::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    .btn-login:hover::before { left: 100%; }
    .btn-login:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(37,99,255,0.35);
    }
    .btn-login:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .login-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 24px 0;
        color: var(--ink-mute);
        font-size: 0.8rem;
        font-weight: 500;
    }
    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--line);
    }

    .login-footer {
        text-align: center;
        font-size: 0.9rem;
        color: var(--ink-mute);
    }
    .login-footer a {
        color: var(--blue);
        font-weight: 700;
        text-decoration: none;
    }
    .login-footer a:hover { text-decoration: underline; }

    .back-to-shop {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px;
        background: var(--light);
        color: var(--ink);
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .back-to-shop:hover {
        background: var(--grad-soft);
        color: var(--blue);
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
        20%, 40%, 60%, 80% { transform: translateX(8px); }
    }
    .shake { animation: shake 0.5s ease; }

    @media (max-width: 480px) {
        .login-card { padding: 32px 24px; }
        .login-logo .logo-mark span { font-size: 1.25rem; }
    }
</style>
</head>
<body>

<div class="login-page">
    <div class="login-card" id="loginCard">

        <div class="login-logo">
            <div class="logo-mark">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4 4L20 20M20 4L4 20" stroke="url(#loginGrad)" stroke-width="2.6" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="loginGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2563FF"/>
                            <stop offset="1" stop-color="#7C3AED"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span>WayronX</span>
            </div>
            <div class="shop-badge">🛍️ Shop Account</div>
        </div>

        <div class="login-title">
            <h1>Welcome Back</h1>
            <p>Sign in to place orders and track deliveries</p>
        </div>

        <div class="login-error" id="loginError"></div>

        <form id="loginForm">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <div class="input-wrap">
                    <span class="input-icon">👤</span>
                    <input type="text" id="username" name="username" placeholder="you@example.com" required autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Show password">👁️</button>
                </div>
            </div>

            <div class="form-row-between">
                <div class="remember-box">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="../contact.php" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <span>Sign In</span>
                <span>→</span>
            </button>
        </form>

        <div class="login-divider">OR</div>

        <a href="index.php" class="back-to-shop">
            <span>🛍️</span> Continue Shopping
        </a>

        <div style="margin-top:20px;text-align:center;font-size:0.9rem;color:var(--ink-mute);">
            Don't have an account?
            <a href="register.php<?php echo !empty($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" style="color:var(--blue);font-weight:700;">Create one</a>
        </div>

    </div>
</div>

<script>
const API = '../api/login.php';

function togglePassword() {
    const input = document.getElementById('password');
    const btn = event.currentTarget;
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁️';
    }
}

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const loginBtn = document.getElementById('loginBtn');
    const loginError = document.getElementById('loginError');
    const loginCard = document.getElementById('loginCard');

    loginError.classList.remove('active');

    if (!username || !password) {
        showError('Please enter both username and password');
        return;
    }

    loginBtn.disabled = true;
    loginBtn.innerHTML = '<span>Signing In...</span>';

    try {
        const response = await fetch(API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({
                action: 'login',
                username: username,
                password: password
            })
        });

        const result = await response.json();

        if (result.success) {
            // Read redirect param from URL
            const params = new URLSearchParams(window.location.search);
            let redirect = params.get('redirect') || 'account/index.php';

            // Sanitize
            if (redirect.includes('://') || redirect.startsWith('//')) {
                redirect = 'account/index.php';
            }
            // Strip leading "shop/" if present (since we're already in shop)
            if (redirect.startsWith('shop/')) {
                redirect = redirect.substring(5);
            }

            loginBtn.innerHTML = '<span>✓ Welcome!</span>';

            setTimeout(() => {
                window.location.href = redirect;
            }, 300);
        } else {
            showError(result.message || 'Invalid credentials');
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<span>Sign In</span><span>→</span>';
        }
    } catch (error) {
        console.error('Login error:', error);
        showError('Network error. Please try again.');
        loginBtn.disabled = false;
        loginBtn.innerHTML = '<span>Sign In</span><span>→</span>';
    }

    function showError(message) {
        loginError.textContent = message;
        loginError.classList.add('active');
        loginCard.classList.remove('shake');
        void loginCard.offsetWidth;
        loginCard.classList.add('shake');
        setTimeout(() => loginCard.classList.remove('shake'), 500);
    }
});
</script>
</body>
</html>