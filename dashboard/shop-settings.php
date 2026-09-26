<?php
require_once __DIR__ . '/../config/auth.php';
requireLogin();
$currentUser = getCurrentUser();

// Only admins/editors
if (!in_array($currentUser['role'], ['admin', 'editor'])) {
    header('Location: index.php');
    exit;
}

//require_once __DIR__ . '../../config/db-connection.php';
$pdo = getConnection();

// Unread messages count for sidebar badge
$stmtUnread = $pdo->prepare("SELECT COUNT(id) as total FROM messages WHERE is_read = 0");
$stmtUnread->execute();
$unreadCount = $stmtUnread->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shop Settings — WayronX Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root{--midnight:#050816;--blue:#2563FF;--purple:#7C3AED;--light:#F7F8FC;--ink:#0B1026;--ink-mute:#5A6180;--line:rgba(11,16,38,0.10);--grad:linear-gradient(120deg,#2563FF,#7C3AED);--success:#10b981;--danger:#ef4444;--warning:#f59e0b;--sidebar-width:260px;}
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Manrope',sans-serif;background:#f1f5f9;color:var(--ink);}
    a{text-decoration:none;color:inherit;}
    ul{list-style:none;}

    /* ============ SIDEBAR (unified) ============ */
    .sidebar{width:var(--sidebar-width);background:var(--midnight);color:#fff;position:fixed;height:100vh;padding:20px 0;display:flex;flex-direction:column;z-index:100;}
    .sidebar-header{padding:0 20px 20px;border-bottom:1px solid rgba(255,255,255,0.1);font-weight:800;color:#fff;font-size:1.05rem;display:flex;gap:10px;align-items:center;min-height:70px;}
    .sidebar-header .logo{display:flex;align-items:center;gap:10px;color:#fff;}
    .sidebar-header .logo svg{width:28px;height:28px;flex-shrink:0;}
    .sidebar-nav{flex:1;padding:16px 0;overflow-y:auto;}
    .sidebar-nav a{display:flex;gap:12px;padding:12px 20px;color:rgba(255,255,255,0.7);font-weight:500;font-size:0.92rem;transition:all 0.2s;align-items:center;position:relative;}
    .sidebar-nav a:hover,.sidebar-nav a.active{background:rgba(255,255,255,0.08);color:#fff;}
    .sidebar-nav a.active::before{content:'';position:absolute;left:0;top:0;height:100%;width:3px;background:var(--grad);}
    .sidebar-nav .icon{font-size:1.15rem;width:22px;text-align:center;flex-shrink:0;}
    .sidebar-nav .badge{margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.72rem;font-weight:700;}
    .sidebar-footer{padding:20px;border-top:1px solid rgba(255,255,255,0.1);}
    .sidebar-footer a{display:flex;gap:10px;color:rgba(255,255,255,0.7);font-weight:500;font-size:0.9rem;}
    .sidebar-footer a:hover{color:#fff;}

    /* ============ MAIN ============ */
    .main{margin-left:var(--sidebar-width);padding:24px;min-height:100vh;width:calc(100% - var(--sidebar-width));}
    .top-bar{background:#fff;border-radius:12px;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);}
    .top-bar h1{font-size:1.3rem;font-weight:700;}

    .settings-card{background:#fff;border-radius:16px;padding:32px;box-shadow:0 2px 10px rgba(0,0,0,0.04);margin-bottom:20px;max-width:900px;}
    .settings-card h2{font-size:1.1rem;font-weight:700;margin-bottom:6px;}
    .settings-card .desc{color:var(--ink-mute);font-size:0.88rem;margin-bottom:24px;}

    .setting-row{display:grid;grid-template-columns:1fr 200px;gap:20px;padding:16px 0;border-bottom:1px solid var(--line);align-items:center;}
    .setting-row:last-child{border-bottom:none;padding-bottom:0;}
    .setting-row .info h3{font-size:0.98rem;font-weight:600;margin-bottom:4px;}
    .setting-row .info p{color:var(--ink-mute);font-size:0.82rem;line-height:1.5;}
    .setting-row input[type="text"],
    .setting-row input[type="number"],
    .setting-row select{
        padding:10px 14px;
        border:1.5px solid var(--line);
        border-radius:8px;
        font-family:inherit;
        font-size:0.95rem;
        width:100%;
        background:#fff;
    }
    .setting-row input:focus,
    .setting-row select:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,255,0.1);}

    .save-bar{position:sticky;bottom:20px;background:#fff;border-radius:12px;padding:16px 24px;box-shadow:0 -4px 20px rgba(0,0,0,0.08);display:flex;justify-content:space-between;align-items:center;max-width:900px;margin-top:20px;}
    .btn-save{padding:14px 32px;background:var(--grad);color:#fff;border:none;border-radius:10px;font-family:inherit;font-weight:700;font-size:1rem;cursor:pointer;transition:all 0.2s;}
    .btn-save:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,255,0.3);}
    .btn-save:disabled{opacity:0.6;cursor:not-allowed;transform:none;}

    .toast{position:fixed;bottom:24px;right:24px;background:var(--success);color:#fff;padding:14px 20px;border-radius:10px;font-weight:600;z-index:9999;animation:slideUp 0.3s;}
    .toast.error{background:var(--danger);}
    @keyframes slideUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}

    @media (max-width:768px) {
        .sidebar{transform:translateX(-100%);transition:transform 0.3s;}
        .sidebar.active{transform:translateX(0);}
        .main{margin-left:0;width:100%;}
        .setting-row{grid-template-columns:1fr;}
    }
</style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="index.php" class="logo">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 4L20 20M20 4L4 20" stroke="url(#sideX)" stroke-width="2.6" stroke-linecap="round"/>
                <defs>
                    <linearGradient id="sideX" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#2563FF"/>
                        <stop offset="1" stop-color="#7C3AED"/>
                    </linearGradient>
                </defs>
            </svg>
            <span>WayronX Admin</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li><a href="index.php"><span class="icon">📊</span>Overview</a></li>
            <li><a href="#"><span class="icon">👥</span>Users</a></li>
            <li><a href="messages.php"><span class="icon">✉️</span>Messages <?php if ($unreadCount > 0): ?><span class="badge"><?php echo $unreadCount; ?></span><?php endif; ?></a></li>
            <li><a href="#"><span class="icon">📝</span>Blog Posts</a></li>
            <li><a href="#"><span class="icon">🛠️</span>Services</a></li>
            <li><a href="shop-products.php"><span class="icon">🛍️</span>Shop Products</a></li>
            <li><a href="shop-orders.php"><span class="icon">📦</span>Shop Orders</a></li>
            <li><a href="shop-categories.php"><span class="icon">📁</span>Categories</a></li>
            <li><a href="shop-delivery.php"><span class="icon">🚚</span>Delivery Regions</a></li>
            <li><a href="shop-settings.php" class="active"><span class="icon">⚙️</span>Shop Settings</a></li>
            <li><a href="#"><span class="icon">💡</span>Solutions</a></li>
            <li><a href="#"><span class="icon">💼</span>Careers</a></li>
            <li><a href="#"><span class="icon">👨‍💼</span>Team (HRM)</a></li>
            <li><a href="#"><span class="icon">💰</span>Finances</a></li>
            <li><a href="#"><span class="icon">📈</span>Analytics</a></li>
            <li><a href="#"><span class="icon">⚙️</span>Settings</a></li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="https://wayronx.com/"><span>🏠</span> Back to Website</a>
    </div>
</aside>

<main class="main">
    <div class="top-bar">
        <h1>⚙️ Shop Settings</h1>
        <div style="font-size:0.9rem;color:var(--ink-mute);">Welcome, <?php echo htmlspecialchars($currentUser['full_name']); ?></div>
    </div>

    <div id="settingsContainer">
        <div class="settings-card" style="text-align:center;color:var(--ink-mute);">Loading settings...</div>
    </div>

    <div class="save-bar">
        <span style="color:var(--ink-mute);font-size:0.9rem;">Changes take effect immediately.</span>
        <button class="btn-save" id="saveBtn" onclick="saveSettings()">Save Settings</button>
    </div>
</main>

<script>
const SETTINGS_API = '../api/shop-settings.php';
const SAVE_API = '../api/shop-admin-settings.php';

let currentSettings = {};

// Metadata for each setting: label, description, type
const SETTING_META = {
    return_window_days: {
        title: 'Return Window (Days)',
        desc: 'Number of days after delivery within which customers can request a return or exchange.',
        type: 'number',
        min: 1,
        max: 90
    },
    cancel_window_status: {
        title: 'Cancel Window (Until Status)',
        desc: 'Customers can cancel orders until the order reaches this status. Once it reaches "shipped" or beyond, cancellation is no longer allowed.',
        type: 'select',
        options: ['confirmed', 'processing', 'shipped']
    },
    refund_window_days: {
        title: 'Refund Processing Window (Days)',
        desc: 'Number of days after a return is approved for the refund to be processed.',
        type: 'number',
        min: 1,
        max: 60
    },
    free_delivery_threshold: {
        title: 'Free Delivery Threshold (UGX)',
        desc: 'Orders above this amount get free delivery automatically.',
        type: 'number',
        min: 0,
        step: 10000
    },
    allow_guest_checkout: {
        title: 'Allow Guest Checkout',
        desc: 'If enabled, non-logged-in users can place orders. Recommended: OFF so you can track customers.',
        type: 'toggle'
    },
    auto_approve_returns: {
        title: 'Auto-Approve Returns',
        desc: 'If enabled, return/exchange requests are approved instantly without admin review.',
        type: 'toggle'
    },
    auto_approve_cancels: {
        title: 'Auto-Approve Cancellations',
        desc: 'If enabled, customer cancellations are processed instantly without admin review.',
        type: 'toggle'
    }
};

/**
 * Safe fetch that detects HTML error pages before trying to parse JSON
 */
async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    const text = await res.text();

    if (text.trim().startsWith('<')) {
        console.error('API returned HTML:', text.substring(0, 500));
        throw new Error('The server returned an unexpected response. Check the browser console.');
    }

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error('Invalid JSON from API:', text.substring(0, 500));
        throw new Error('The server sent invalid data.');
    }
}

async function loadSettings() {
    try {
        const data = await fetchJSON(SETTINGS_API + '?_=' + Date.now());
        if (!data.success) { renderError(data.message || 'Failed to load'); return; }
        currentSettings = data.data;
        renderForm();
    } catch (e) {
        renderError(e.message);
    }
}

function renderForm() {
    const container = document.getElementById('settingsContainer');
    let html = '<div class="settings-card"><h2>Shop Configuration</h2><p class="desc">Control how customers interact with your shop.</p>';

    Object.keys(SETTING_META).forEach(key => {
        const meta = SETTING_META[key];
        const value = currentSettings[key] ?? '';

        html += '<div class="setting-row">';
        html += '<div class="info"><h3>' + meta.title + '</h3><p>' + meta.desc + '</p></div>';
        html += '<div>';

        if (meta.type === 'number') {
            html += `<input type="number" id="set_${key}" value="${value}" min="${meta.min || 0}" max="${meta.max || ''}" step="${meta.step || 1}">`;
        } else if (meta.type === 'toggle') {
            const checked = value === '1' ? 'checked' : '';
            html += `<select id="set_${key}"><option value="1" ${checked}>Enabled</option><option value="0" ${!checked ? 'selected' : ''}>Disabled</option></select>`;
        } else if (meta.type === 'select') {
            html += `<select id="set_${key}">`;
            meta.options.forEach(opt => {
                html += `<option value="${opt}" ${value === opt ? 'selected' : ''}>${opt.charAt(0).toUpperCase() + opt.slice(1)}</option>`;
            });
            html += `</select>`;
        }

        html += '</div></div>';
    });

    html += '</div>';
    container.innerHTML = html;
}

function renderError(msg) {
    document.getElementById('settingsContainer').innerHTML = `
        <div class="settings-card" style="text-align:center;color:var(--danger);">
            ⚠️ ${msg}
            <div style="margin-top:16px;"><button class="btn-save" onclick="loadSettings()">Retry</button></div>
        </div>
    `;
}

async function saveSettings() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const payload = { settings: {} };
    Object.keys(SETTING_META).forEach(key => {
        const el = document.getElementById('set_' + key);
        if (el) payload.settings[key] = el.value;
    });

    try {
        const data = await fetchJSON(SAVE_API, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        if (data.success) {
            showToast('✓ Settings saved');
            currentSettings = payload.settings;
        } else {
            showToast(data.message || 'Failed to save', 'error');
        }
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Settings';
    }
}

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : '');
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

loadSettings();
</script>

</body>
</html>