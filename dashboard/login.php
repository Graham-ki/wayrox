<?php
//session_start();
require_once 'config/auth.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — WayronX Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --midnight: #050816;
        --navy: #0B1026;
        --blue: #2563FF;
        --purple: #7C3AED;
        --cyan: #22D3EE;
        --white: #FFFFFF;
        --light: #F7F8FC;
        --ink: #0B1026;
        --ink-mute: #5A6180;
        --line-dark: rgba(255,255,255,0.10);
        --line-light: rgba(11,16,38,0.10);
        --grad: linear-gradient(120deg, #2563FF, #7C3AED);
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Manrope', sans-serif;
        background: var(--midnight);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    /* Animated background */
    .login-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }
    
    .bg-circle {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    }
    
    .bg-circle:nth-child(1) {
        width: 400px;
        height: 400px;
        background: var(--blue);
        top: -100px;
        right: -100px;
        animation-delay: 0s;
    }
    
    .bg-circle:nth-child(2) {
        width: 300px;
        height: 300px;
        background: var(--purple);
        bottom: -50px;
        left: -50px;
        animation-delay: 2s;
    }
    
    .bg-circle:nth-child(3) {
        width: 200px;
        height: 200px;
        background: var(--cyan);
        top: 50%;
        left: 50%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-30px) scale(1.1); }
    }
    
    /* Login Container */
    .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 420px;
        padding: 20px;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
    }
    
    .login-logo {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .login-logo .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ink);
    }
    
    .login-logo .logo svg {
        width: 40px;
        height: 40px;
    }
    
    .login-logo p {
        color: var(--ink-mute);
        font-size: 0.9rem;
        margin-top: 10px;
    }
    
    .login-title {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .login-title h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 5px;
    }
    
    .login-title p {
        color: var(--ink-mute);
        font-size: 0.9rem;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--ink);
    }
    
    .form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--line-light);
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--ink);
    }
    
    .form-group input:focus {
        outline: none;
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 255, 0.1);
    }
    
    .form-group .input-icon {
        position: relative;
    }
    
    .form-group .input-icon input {
        padding-left: 45px;
    }
    
    .form-group .input-icon .icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        color: var(--ink-mute);
    }
    
    .login-btn {
        width: 100%;
        padding: 15px;
        background: var(--grad);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37, 99, 255, 0.3);
    }
    
    .login-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .login-error {
        display: none;
        padding: 12px;
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
    }
    
    .login-error.active {
        display: block;
    }
    
    .login-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 0.85rem;
        color: var(--ink-mute);
    }
    
    .login-footer a {
        color: var(--blue);
        font-weight: 600;
    }
    
    .remember-me {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .remember-me input[type="checkbox"] {
        width: auto;
        cursor: pointer;
    }
    
    .remember-me label {
        font-size: 0.9rem;
        color: var(--ink-mute);
        cursor: pointer;
    }
</style>
</head>
<body>

<!-- Animated Background -->
<div class="login-bg">
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
</div>

<!-- Login Container -->
<div class="login-container">
    <div class="login-card">
        <div class="login-logo">
            <div class="logo">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4 4L20 20M20 4L4 20" stroke="url(#loginX)" stroke-width="2.6" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="loginX" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2563FF"/>
                            <stop offset="1" stop-color="#7C3AED"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span>WayronX Admin</span>
            </div>
            <p>Building intelligent digital solutions</p>
        </div>
        
        <div class="login-title">
            <h1>Welcome Back</h1>
            <p>Sign in to access your dashboard</p>
        </div>
        
        <div class="login-error" id="loginError"></div>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <div class="input-icon">
                    <span class="icon">👤</span>
                    <input type="text" id="username" name="username" placeholder="Enter your username or email" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon">
                    <span class="icon">🔒</span>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            
            <button type="submit" class="login-btn" id="loginBtn">
                Sign In <span>→</span>
            </button>
        </form>
        
        
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const loginBtn = document.getElementById('loginBtn');
        const loginError = document.getElementById('loginError');
        
        // Hide previous error
        loginError.classList.remove('active');
        
        // Validation
        if (!username || !password) {
            showError('Please enter both username and password');
            return;
        }
        
        // Update button state
        loginBtn.textContent = 'Signing In...';
        loginBtn.disabled = true;
        
        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'login',
                    username: username,
                    password: password
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Success - redirect to dashboard
                window.location.href = 'index.php';
            } else {
                showError(result.message || 'Invalid credentials');
                loginBtn.textContent = 'Sign In →';
                loginBtn.disabled = false;
            }
        } catch (error) {
            console.error('Login error:', error);
            showError('Network error. Please try again.');
            loginBtn.textContent = 'Sign In →';
            loginBtn.disabled = false;
        }
    });
    
    function showError(message) {
        const loginError = document.getElementById('loginError');
        loginError.textContent = message;
        loginError.classList.add('active');
        
        // Shake animation
        const card = document.querySelector('.login-card');
        card.style.animation = 'none';
        setTimeout(() => {
            card.style.animation = 'shake 0.5s ease';
        }, 10);
    }
    
    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
    `;
    document.head.appendChild(style);
</script>
</body>
</html>