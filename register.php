<?php
session_start();
require_once 'config/auth.php';

if (isLoggedIn()) {
    header('Location: account/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account — WayronX</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --midnight:#050816; --blue:#2563FF; --purple:#7C3AED; --cyan:#22D3EE;
        --ink:#0B1026; --ink-mute:#5A6180; --line:rgba(11,16,38,0.10);
        --grad:linear-gradient(120deg,#2563FF,#7C3AED);
        --danger:#ef4444;
    }
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Manrope',sans-serif;background:var(--midnight);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
    .card{background:rgba(255,255,255,0.96);border-radius:20px;padding:40px;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,0.3);}
    .logo{display:flex;align-items:center;justify-content:center;gap:10px;font-size:1.4rem;font-weight:800;color:var(--ink);margin-bottom:8px;}
    .logo svg{width:36px;height:36px;}
    .sub{text-align:center;color:var(--ink-mute);font-size:0.9rem;margin-bottom:28px;}
    h1{text-align:center;font-size:1.4rem;font-weight:700;color:var(--ink);margin-bottom:6px;}
    .form-group{margin-bottom:16px;}
    label{display:block;font-weight:600;font-size:0.9rem;margin-bottom:6px;color:var(--ink);}
    label .req{color:var(--danger);}
    input{width:100%;padding:13px 15px;border:1.5px solid var(--line);border-radius:10px;font-family:inherit;font-size:0.95rem;}
    input:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,255,0.1);}
    .btn{width:100%;padding:15px;background:var(--grad);color:#fff;border:none;border-radius:10px;font-family:inherit;font-weight:700;font-size:1rem;cursor:pointer;transition:all 0.2s;}
    .btn:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,255,0.3);}
    .btn:disabled{opacity:0.6;cursor:not-allowed;}
    .error{display:none;padding:12px;background:rgba(239,68,68,0.1);color:var(--danger);border-radius:8px;margin-bottom:16px;font-size:0.9rem;text-align:center;font-weight:500;}
    .error.active{display:block;}
    .foot{text-align:center;margin-top:20px;font-size:0.88rem;color:var(--ink-mute);}
    .foot a{color:var(--blue);font-weight:600;text-decoration:none;}
</style>
</head>
<body>

<div class="card">
    <div class="logo">
        <svg viewBox="0 0 24 24" fill="none"><path d="M4 4L20 20M20 4L4 20" stroke="url(#g)" stroke-width="2.6" stroke-linecap="round"/><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2563FF"/><stop offset="1" stop-color="#7C3AED"/></linearGradient></defs></svg>
        WayronX
    </div>
    <p class="sub">Create your account in seconds</p>

    <div class="error" id="regError"></div>

    <form id="regForm">
        <div class="form-group">
            <label>Full Name <span class="req">*</span></label>
            <input type="text" name="full_name" required placeholder="John Doe">
        </div>
        <div class="form-group">
            <label>Email <span class="req">*</span></label>
            <input type="email" name="email" required placeholder="you@example.com">
        </div>
        <div class="form-group">
            <label>Username <span class="req">*</span></label>
            <input type="text" name="username" required placeholder="johndoe" minlength="3">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="tel" name="phone" placeholder="+256 700 000 000">
        </div>
        <div class="form-group">
            <label>Password <span class="req">*</span></label>
            <input type="password" name="password" required placeholder="At least 6 characters" minlength="6">
        </div>

        <button type="submit" class="btn" id="regBtn">Create Account</button>
    </form>

    <div class="foot">
        Already have an account? <a href="login.php">Sign In</a>
    </div>
</div>

<script>
document.getElementById('regForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('regBtn');
    const errorBox = document.getElementById('regError');
    const form = e.target;

    errorBox.classList.remove('active');
    btn.disabled = true;
    btn.textContent = 'Creating account...';

    const payload = {
        full_name: form.full_name.value.trim(),
        email: form.email.value.trim(),
        username: form.username.value.trim(),
        phone: form.phone.value.trim(),
        password: form.password.value,
        action: 'register'
    };

    try {
        const res = await fetch('api/register.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
            // Auto-login after registration
            const loginRes = await fetch('api/login.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'login',
                    username: payload.email,
                    password: payload.password
                })
            });
            const loginData = await loginRes.json();

            // Redirect
            const params = new URLSearchParams(window.location.search);
            const redirect = params.get('redirect');
            const destination = (redirect && !redirect.includes('://'))
                ? redirect
                : 'account/index.php';

            btn.textContent = '✓ Welcome!';
            setTimeout(() => window.location.href = destination, 400);
        } else {
            errorBox.textContent = data.message || 'Registration failed';
            errorBox.classList.add('active');
            btn.disabled = false;
            btn.textContent = 'Create Account';
        }
    } catch (err) {
        errorBox.textContent = 'Network error. Please try again.';
        errorBox.classList.add('active');
        btn.disabled = false;
        btn.textContent = 'Create Account';
    }
});
</script>

</body>
</html>