<?php
require_once '../config/auth.php';
requireLogin();
$currentUser = getCurrentUser();

$pdo = getConnection();

$stmtUnread = $pdo->prepare("SELECT COUNT(id) as total FROM messages WHERE is_read = 0");
$stmtUnread->execute();
$unreadCount = $stmtUnread->fetch()['total'];

// Count pending returns for sidebar badge
$stmtPendingReturns = $pdo->prepare("SELECT COUNT(*) AS c FROM shop_returns WHERE status = 'pending'");
$stmtPendingReturns->execute();
$pendingReturnsCount = (int)$stmtPendingReturns->fetch()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shop Orders — WayronX Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root{--midnight:#050816;--blue:#2563FF;--purple:#7C3AED;--light:#F7F8FC;--ink:#0B1026;--ink-mute:#5A6180;--line:rgba(11,16,38,0.10);--grad:linear-gradient(120deg,#2563FF,#7C3AED);--success:#10b981;--danger:#ef4444;--warning:#f59e0b;--sidebar-width:260px;}
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Manrope',sans-serif;background:#f1f5f9;color:var(--ink);}
    a{text-decoration:none;color:inherit;}
    ul{list-style:none;}

    /* SIDEBAR */
    .sidebar{width:var(--sidebar-width);background:var(--midnight);color:#fff;position:fixed;height:100vh;padding:20px 0;display:flex;flex-direction:column;z-index:100;}
    .sidebar-header{padding:0 20px 20px;border-bottom:1px solid rgba(255,255,255,0.1);font-weight:800;color:#fff;font-size:1.05rem;display:flex;gap:10px;align-items:center;min-height:70px;}
    .sidebar-header .logo{display:flex;align-items:center;gap:10px;color:#fff;}
    .sidebar-header .logo svg{width:28px;height:28px;flex-shrink:0;}
    .sidebar-nav{flex:1;padding:16px 0;overflow-y:auto;}
    .sidebar-nav a{display:flex;gap:12px;padding:12px 20px;color:rgba(255,255,255,0.7);font-weight:500;font-size:0.92rem;transition:all 0.2s;align-items:center;position:relative;}
    .sidebar-nav a:hover,.sidebar-nav a.active{background:rgba(255,255,255,0.08);color:#fff;}
    .sidebar-nav a.active::before{content:'';position:absolute;left:0;top:0;height:100%;width:3px;background:var(--grad);}
    .sidebar-nav .icon{font-size:1.15rem;width:22px;text-align:center;flex-shrink:0;}
    .sidebar-nav .badge{margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.72rem;font-weight:700;min-width:20px;text-align:center;}
    .sidebar-footer{padding:20px;border-top:1px solid rgba(255,255,255,0.1);}
    .sidebar-footer a{display:flex;gap:10px;color:rgba(255,255,255,0.7);font-weight:500;font-size:0.9rem;}
    .sidebar-footer a:hover{color:#fff;}

    .main{margin-left:var(--sidebar-width);padding:24px;min-height:100vh;width:calc(100% - var(--sidebar-width));}
    .top-bar{background:#fff;border-radius:12px;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);gap:16px;flex-wrap:wrap;}
    .top-bar h1{font-size:1.3rem;font-weight:700;}
    .top-bar-right{display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
    .refresh-info{font-size:0.82rem;color:var(--ink-mute);display:flex;align-items:center;gap:6px;}
    .refresh-info .dot{width:8px;height:8px;background:var(--success);border-radius:50%;animation:pulse 2s infinite;}
    @keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.4;}}
    .btn-refresh{padding:8px 16px;background:var(--light);border:1.5px solid var(--line);border-radius:8px;font-family:inherit;font-weight:600;font-size:0.85rem;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all 0.2s;}
    .btn-refresh:hover{border-color:var(--blue);color:var(--blue);}
    .btn-refresh.spinning span:first-child{display:inline-block;animation:spin 1s linear infinite;}
    @keyframes spin{to{transform:rotate(360deg);}}

    .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;}
    .stat-box{background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 10px rgba(0,0,0,0.04);position:relative;overflow:hidden;}
    .stat-box::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;background:var(--grad);}
    .stat-box .label{font-size:0.78rem;color:var(--ink-mute);text-transform:uppercase;letter-spacing:0.05em;font-weight:700;margin-bottom:8px;}
    .stat-box .value{font-size:1.8rem;font-weight:800;color:var(--ink);}

    /* View switcher */
    .view-switcher{display:flex;gap:4px;padding:4px;background:#fff;border-radius:12px;margin-bottom:20px;box-shadow:0 2px 10px rgba(0,0,0,0.04);width:fit-content;}
    .view-tab{padding:10px 22px;border-radius:8px;border:none;background:transparent;font-family:inherit;font-weight:700;font-size:0.9rem;cursor:pointer;color:var(--ink-mute);transition:all 0.2s;display:inline-flex;align-items:center;gap:8px;}
    .view-tab:hover{background:var(--light);color:var(--ink);}
    .view-tab.active{background:var(--grad);color:#fff;box-shadow:0 4px 12px rgba(37,99,255,0.3);}
    .pending-badge{background:var(--danger);color:#fff;font-size:0.7rem;padding:2px 8px;border-radius:10px;font-weight:700;min-width:20px;text-align:center;}
    .view-tab.active .pending-badge{background:rgba(255,255,255,0.25);}

    .filter-tabs{display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;}
    .filter-tab,.return-filter-tab{padding:8px 18px;border-radius:20px;border:1.5px solid var(--line);background:#fff;cursor:pointer;font-family:inherit;font-weight:600;font-size:0.85rem;color:var(--ink-mute);transition:all 0.2s;}
    .filter-tab:hover,.return-filter-tab:hover{border-color:var(--blue);color:var(--blue);}
    .filter-tab.active,.return-filter-tab.active{background:var(--grad);border-color:transparent;color:#fff;}

    .orders-table{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);}
    table{width:100%;border-collapse:collapse;}
    th{background:var(--light);text-align:left;padding:14px 16px;font-size:0.75rem;text-transform:uppercase;color:var(--ink-mute);font-weight:700;letter-spacing:0.05em;}
    td{padding:14px 16px;border-top:1px solid var(--line);font-size:0.88rem;vertical-align:top;}

    .order-number{font-weight:700;color:var(--blue);cursor:pointer;}
    .order-number:hover{text-decoration:underline;}

    .status-select{padding:6px 10px;border:1.5px solid var(--line);border-radius:6px;font-family:inherit;font-size:0.8rem;font-weight:600;cursor:pointer;}
    .status-select:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,255,0.1);}

    .status-badge{display:inline-flex;padding:4px 10px;border-radius:6px;font-size:0.72rem;font-weight:700;text-transform:capitalize;}
    .status-badge.pending{background:rgba(245,158,11,0.1);color:var(--warning);}
    .status-badge.confirmed{background:rgba(37,99,255,0.1);color:var(--blue);}
    .status-badge.processing{background:rgba(124,58,237,0.1);color:#7C3AED;}
    .status-badge.shipped{background:rgba(34,211,238,0.1);color:#06b6d4;}
    .status-badge.delivered{background:rgba(16,185,129,0.1);color:var(--success);}
    .status-badge.cancelled{background:rgba(239,68,68,0.1);color:var(--danger);}
    .status-badge.returned{background:rgba(124,58,237,0.1);color:#7C3AED;}
    .status-badge.refunded{background:rgba(16,185,129,0.15);color:var(--success);}

    .payment-badge{display:inline-flex;padding:3px 8px;border-radius:5px;font-size:0.7rem;font-weight:700;text-transform:capitalize;}
    .payment-badge.pending{background:rgba(245,158,11,0.1);color:var(--warning);}
    .payment-badge.paid{background:rgba(16,185,129,0.1);color:var(--success);}
    .payment-badge.failed{background:rgba(239,68,68,0.1);color:var(--danger);}
    .payment-badge.refunded{background:rgba(124,58,237,0.1);color:var(--purple);}

    .return-indicator{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:5px;font-size:0.7rem;font-weight:700;background:rgba(124,58,237,0.1);color:var(--purple);}

    .return-type-badge{display:inline-flex;padding:3px 8px;border-radius:5px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.03em;}
    .return-type-badge.refund{background:rgba(37,99,255,0.1);color:var(--blue);}
    .return-type-badge.exchange{background:rgba(124,58,237,0.1);color:var(--purple);}

    .btn-icon{padding:6px 12px;background:var(--light);border:none;border-radius:6px;cursor:pointer;font-size:0.85rem;font-family:inherit;font-weight:500;transition:all 0.2s;}
    .btn-icon:hover{background:var(--blue);color:#fff;}

    /* MODAL */
    .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px;}
    .modal-overlay.active{display:flex;}
    .modal{background:#fff;border-radius:16px;max-width:780px;width:100%;max-height:90vh;overflow-y:auto;padding:32px;}
    .modal h2{font-size:1.3rem;font-weight:700;margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;gap:12px;}

    .modal-section{margin-bottom:24px;}
    .modal-section h3{font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--ink-mute);margin-bottom:12px;}

    .detail-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--line);font-size:0.9rem;gap:16px;}
    .detail-row:last-child{border-bottom:none;}
    .detail-row .label{color:var(--ink-mute);flex-shrink:0;}
    .detail-row .value{font-weight:600;text-align:right;word-break:break-word;}

    .item-line{display:flex;justify-content:space-between;padding:8px 0;font-size:0.9rem;gap:12px;}
    .item-line .qty{color:var(--ink-mute);font-size:0.82rem;}

    .mini-timeline{position:relative;padding-left:24px;}
    .mini-timeline::before{content:'';position:absolute;left:7px;top:8px;bottom:8px;width:2px;background:var(--line);}
    .mini-step{position:relative;padding-bottom:16px;font-size:0.85rem;}
    .mini-step:last-child{padding-bottom:0;}
    .mini-step::before{content:'';position:absolute;left:-24px;top:4px;width:16px;height:16px;border-radius:50%;background:#fff;border:3px solid var(--blue);}
    .mini-step .step-time{font-size:0.72rem;color:var(--ink-mute);margin-top:2px;}
    .mini-step .step-note{font-size:0.8rem;color:var(--ink-mute);margin-top:4px;padding:6px 10px;background:var(--light);border-radius:6px;line-height:1.5;}
    .mini-step strong{font-size:0.88rem;text-transform:capitalize;}

    .btn-close{padding:12px 24px;background:var(--light);border:none;border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;margin-top:20px;}

    .toast{position:fixed;bottom:24px;right:24px;background:var(--success);color:#fff;padding:14px 20px;border-radius:10px;font-weight:600;z-index:9999;animation:slideUp 0.3s;max-width:340px;}
    .toast.error{background:var(--danger);}
    .toast.info{background:var(--blue);}
    @keyframes slideUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}

    @media (max-width:768px) {
        .sidebar{transform:translateX(-100%);transition:transform 0.3s;}
        .sidebar.active{transform:translateX(0);}
        .main{margin-left:0;width:100%;}
        table{font-size:0.8rem;}
        th,td{padding:10px 8px;}
        .top-bar{padding:14px 16px;}
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
            <li>
                <a href="shop-orders.php" class="active">
                    <span class="icon">📦</span>Shop Orders
                    <?php if ($pendingReturnsCount > 0): ?>
                        <span class="badge" id="sidebarReturnsBadge"><?php echo $pendingReturnsCount; ?></span>
                    <?php else: ?>
                        <span class="badge" id="sidebarReturnsBadge" style="display:none;">0</span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="shop-categories.php"><span class="icon">📁</span>Categories</a></li>
            <li><a href="shop-delivery.php"><span class="icon">🚚</span>Delivery Regions</a></li>
            <li><a href="shop-settings.php"><span class="icon">⚙️</span>Shop Settings</a></li>
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
        <h1>📦 Shop Orders & Returns</h1>
        <div class="top-bar-right">
            <div class="refresh-info">
                <span class="dot"></span>
                <span id="lastRefresh">Auto-refreshing every 30s</span>
            </div>
            <button class="btn-refresh" id="refreshBtn" onclick="manualRefresh()">
                <span>🔄</span> Refresh
            </button>
            <div style="font-size:0.9rem;color:var(--ink-mute);">Welcome, <?php echo htmlspecialchars($currentUser['full_name']); ?></div>
        </div>
    </div>

    <div class="stats-row" id="statsRow"></div>

    <!-- VIEW SWITCHER -->
    <div class="view-switcher">
        <button class="view-tab active" data-view="orders" onclick="switchView('orders')">
            📦 Orders
        </button>
        <button class="view-tab" data-view="returns" onclick="switchView('returns')">
            ↩️ Returns
            <span class="pending-badge" id="pendingReturnsBadge" style="display:none;">0</span>
        </button>
    </div>

    <!-- ORDERS VIEW -->
    <div id="ordersView">
        <div class="filter-tabs" id="filterTabs">
            <button class="filter-tab active" data-status="">All Orders</button>
            <button class="filter-tab" data-status="pending">Pending</button>
            <button class="filter-tab" data-status="confirmed">Confirmed</button>
            <button class="filter-tab" data-status="processing">Processing</button>
            <button class="filter-tab" data-status="shipped">Shipped</button>
            <button class="filter-tab" data-status="delivered">Delivered</button>
            <button class="filter-tab" data-status="returned">Returned</button>
            <button class="filter-tab" data-status="refunded">Refunded</button>
            <button class="filter-tab" data-status="cancelled">Cancelled</button>
        </div>

        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--ink-mute);">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RETURNS VIEW -->
    <div id="returnsView" style="display:none;">
        <div class="filter-tabs" id="returnFilterTabs">
            <button class="return-filter-tab active" data-status="">All Returns</button>
            <button class="return-filter-tab" data-status="pending">Pending</button>
            <button class="return-filter-tab" data-status="approved">Approved</button>
            <button class="return-filter-tab" data-status="rejected">Rejected</button>
            <button class="return-filter-tab" data-status="completed">Completed</button>
        </div>

        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Return #</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Refund</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="returnsBody">
                    <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--ink-mute);">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal-overlay" id="orderModal">
    <div class="modal" id="modalContent"></div>
</div>

<script>
const API = '../api/shop-admin.php';
let currentStatus = '';
let ordersList = [];
let returnsList = [];
let currentReturnFilter = '';
let returnsLoaded = false;
let currentView = 'orders';
let autoRefreshTimer = null;

function formatPrice(n){return 'UGX ' + Number(n || 0).toLocaleString('en-US');}
function formatDate(d){
    if (!d) return 'N/A';
    return new Date(d).toLocaleString('en-US', {year:'numeric',month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
}
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    const text = await res.text();

    if (text.trim().startsWith('<')) {
        console.error('API returned HTML:', text.substring(0, 500));
        throw new Error('Something went wrong with the request.');
    }

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error('Invalid JSON from API:', text.substring(0, 500));
        throw new Error('The server sent invalid data.');
    }
}

// ==================== VIEW SWITCH ====================
function switchView(view) {
    currentView = view;
    document.querySelectorAll('.view-tab').forEach(t => {
        t.classList.toggle('active', t.dataset.view === view);
    });
    document.getElementById('ordersView').style.display = view === 'orders' ? '' : 'none';
    document.getElementById('returnsView').style.display = view === 'returns' ? '' : 'none';

    if (view === 'returns') {
        loadReturns();
        returnsLoaded = true;
    }
}

// ==================== ORDERS ====================
async function loadOrders(silent = false) {
    const tbody = document.getElementById('ordersBody');
    if (!silent) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;">Loading...</td></tr>';
    }

    try {
        const params = new URLSearchParams({ action: 'orders', status: currentStatus, _t: Date.now() });
        const data = await fetchJSON(`${API}?${params}`);

        if (!data.success) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--danger);">${data.message || 'Failed to load'}</td></tr>`;
            return;
        }
        ordersList = data.data;

        const stats = {
            pending: 0, confirmed: 0, processing: 0, shipped: 0,
            delivered: 0, returned: 0, refunded: 0, cancelled: 0,
            total: ordersList.length, revenue: 0
        };
        ordersList.forEach(o => {
            if (stats[o.order_status] !== undefined) stats[o.order_status]++;
            if (o.order_status === 'delivered') {
        stats.revenue += parseFloat(o.total);
    }
        });

        document.getElementById('statsRow').innerHTML = `
            <div class="stat-box"><div class="label">Total Orders</div><div class="value">${stats.total}</div></div>
            <div class="stat-box"><div class="label">Pending</div><div class="value" style="color:var(--warning);">${stats.pending}</div></div>
            <div class="stat-box"><div class="label">Delivered</div><div class="value" style="color:var(--success);">${stats.delivered}</div></div>
            <div class="stat-box"><div class="label">Returns</div><div class="value" style="color:var(--purple);">${stats.returned + stats.refunded}</div></div>
            <div class="stat-box"><div class="label">Revenue</div><div class="value" style="color:var(--blue);font-size:1.3rem;">${formatPrice(stats.revenue)}</div></div>
        `;

        if (ordersList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--ink-mute);">No orders found</td></tr>';
            return;
        }

        tbody.innerHTML = ordersList.map(o => {
            const hasReturn = (o.has_return === '1' || o.has_return === 1 || o.active_return_count > 0);
            const paymentStatus = o.payment_status || 'pending';

            return `
                <tr>
                    <td>
                        <span class="order-number" onclick="viewOrder(${o.id})">${escapeHtml(o.order_number)}</span>
                        ${hasReturn ? '<div style="margin-top:4px;"><span class="return-indicator">↩️ Return</span></div>' : ''}
                    </td>
                    <td>
                        <div style="font-weight:600;">${escapeHtml(o.customer_name)}</div>
                        <div style="font-size:0.78rem;color:var(--ink-mute);">${escapeHtml(o.customer_phone || '')}</div>
                    </td>
                    <td><strong>${formatPrice(o.total)}</strong></td>
                    <td>
                        <select class="status-select" onchange="updateStatus(${o.id}, this.value)">
                            ${['pending','confirmed','processing','shipped','delivered','cancelled','returned','refunded'].map(s =>
                                `<option value="${s}" ${o.order_status === s ? 'selected' : ''}>${s.charAt(0).toUpperCase() + s.slice(1)}</option>`
                            ).join('')}
                        </select>
                    </td>
                    <td><span class="payment-badge ${paymentStatus}">${paymentStatus}</span></td>
                    <td style="font-size:0.82rem;color:var(--ink-mute);">${formatDate(o.created_at)}</td>
                    <td><button class="btn-icon" onclick="viewOrder(${o.id})">View</button></td>
                </tr>
            `;
        }).join('');

        const now = new Date();
        document.getElementById('lastRefresh').textContent =
            'Last updated ' + now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

    } catch (e) {
        if (!silent) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--danger);">
                ⚠️ ${e.message}
                <div style="margin-top:12px;"><button onclick="loadOrders()" style="padding:8px 16px;background:var(--grad);color:#fff;border:none;border-radius:6px;font-family:inherit;font-weight:600;cursor:pointer;">Retry</button></div>
            </td></tr>`;
        }
    }
}

function viewOrder(id) {
    const o = ordersList.find(x => x.id == id);
    if (!o) return;

    const itemsHtml = (o.items || []).map(i => `
        <div class="item-line">
            <span>${escapeHtml(i.product_name)} <span class="qty">× ${i.quantity}</span></span>
            <span>${formatPrice(i.subtotal)}</span>
        </div>
    `).join('') || '<p style="color:var(--ink-mute);">No items loaded</p>';

    let historyHtml = '';
    if (o.history && o.history.length > 0) {
        historyHtml = `
            <div class="modal-section">
                <h3>Order Timeline</h3>
                <div class="mini-timeline">
                    ${o.history.map(h => `
                        <div class="mini-step">
                            <strong>${escapeHtml(h.status)}</strong>
                            <div class="step-time">${formatDate(h.created_at)}</div>
                            ${h.note ? `<div class="step-note">${escapeHtml(h.note)}</div>` : ''}
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    let returnsHtml = '';
    if (o.returns && o.returns.length > 0) {
        returnsHtml = `
            <div class="modal-section">
                <h3>Return Requests</h3>
                ${o.returns.map(r => `
                    <div style="background:var(--light);border-radius:10px;padding:14px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:8px;">
                            <strong style="text-transform:capitalize;">${escapeHtml(r.return_type || 'refund')}</strong>
                            <span class="status-badge ${r.status === 'approved' || r.status === 'completed' ? 'delivered' : (r.status === 'rejected' ? 'cancelled' : 'pending')}">${escapeHtml(r.status)}</span>
                        </div>
                        <div style="font-size:0.85rem;color:var(--ink-mute);line-height:1.6;">${escapeHtml(r.reason || '')}</div>
                        <div style="font-size:0.78rem;color:var(--ink-mute);margin-top:8px;">
                            Requested ${formatDate(r.created_at)} · Refund: ${formatPrice(r.refund_amount)}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    document.getElementById('modalContent').innerHTML = `
        <h2>
            Order ${escapeHtml(o.order_number)}
            <button class="btn-close" onclick="closeModal()" style="padding:6px 14px;margin:0;font-size:0.82rem;">✕</button>
        </h2>

        <div class="modal-section">
            <h3>Customer</h3>
            <div class="detail-row"><span class="label">Name</span><span class="value">${escapeHtml(o.customer_name)}</span></div>
            <div class="detail-row"><span class="label">Email</span><span class="value">${escapeHtml(o.customer_email)}</span></div>
            <div class="detail-row"><span class="label">Phone</span><span class="value">${escapeHtml(o.customer_phone)}</span></div>
            ${o.user_id ? `<div class="detail-row"><span class="label">User ID</span><span class="value">#${o.user_id}</span></div>` : ''}
        </div>

        <div class="modal-section">
            <h3>Delivery</h3>
            <div class="detail-row"><span class="label">Address</span><span class="value">${escapeHtml(o.delivery_address)}${o.delivery_city ? ', ' + escapeHtml(o.delivery_city) : ''}</span></div>
            ${o.region_name ? `<div class="detail-row"><span class="label">Region</span><span class="value">${escapeHtml(o.region_name)}</span></div>` : ''}
            ${o.delivery_notes ? `<div class="detail-row"><span class="label">Notes</span><span class="value">${escapeHtml(o.delivery_notes)}</span></div>` : ''}
        </div>

        <div class="modal-section">
            <h3>Payment & Status</h3>
            <div class="detail-row"><span class="label">Payment Method</span><span class="value">${escapeHtml((o.payment_method || '').replace(/_/g, ' '))}</span></div>
            <div class="detail-row"><span class="label">Payment Status</span><span class="value"><span class="payment-badge ${o.payment_status || 'pending'}">${o.payment_status || 'pending'}</span></span></div>
            <div class="detail-row"><span class="label">Order Status</span><span class="value"><span class="status-badge ${o.order_status}">${escapeHtml(o.order_status)}</span></span></div>
            ${o.invoice_number ? `<div class="detail-row"><span class="label">Invoice</span><span class="value">${escapeHtml(o.invoice_number)}</span></div>` : ''}
            ${o.receipt_number ? `<div class="detail-row"><span class="label">Receipt</span><span class="value">${escapeHtml(o.receipt_number)}</span></div>` : ''}
            <div class="detail-row"><span class="label">Placed</span><span class="value">${formatDate(o.created_at)}</span></div>
            ${o.delivered_at ? `<div class="detail-row"><span class="label">Delivered</span><span class="value">${formatDate(o.delivered_at)}</span></div>` : ''}
            ${o.cancelled_at ? `<div class="detail-row"><span class="label">Cancelled</span><span class="value">${formatDate(o.cancelled_at)}</span></div>` : ''}
        </div>

        <div class="modal-section">
            <h3>Items (${(o.items || []).length})</h3>
            ${itemsHtml}
            <div class="item-line" style="border-top:1px solid var(--line);margin-top:12px;padding-top:12px;">
                <span>Subtotal</span><span>${formatPrice(o.subtotal)}</span>
            </div>
            <div class="item-line">
                <span>Delivery</span><span>${Number(o.delivery_fee) === 0 ? 'FREE' : formatPrice(o.delivery_fee)}</span>
            </div>
            <div class="item-line" style="font-weight:800;font-size:1rem;border-top:2px solid var(--line);margin-top:8px;padding-top:12px;">
                <span>Total</span><span style="color:var(--blue);">${formatPrice(o.total)}</span>
            </div>
        </div>

        ${returnsHtml}
        ${historyHtml}
    `;

    document.getElementById('orderModal').classList.add('active');
}

async function updateStatus(id, status) {
    if (['refunded', 'cancelled'].includes(status)) {
        if (!confirm(`Change status to "${status}"? This will notify the customer.`)) {
            loadOrders(true);
            return;
        }
    }

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'update-order-status', id, status })
        });
        if (data.success) {
            showToast(`Status updated to "${status}"`);
            loadOrders(true);
        } else {
            showToast(data.message || 'Failed', 'error');
            loadOrders(true);
        }
    } catch (e) {
        showToast(e.message, 'error');
        loadOrders(true);
    }
}

// ==================== RETURNS ====================
async function loadReturns(silent = false) {
    const tbody = document.getElementById('returnsBody');
    if (!silent) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:40px;">Loading returns...</td></tr>';
    }

    try {
        const params = new URLSearchParams({
            action: 'returns',
            status: currentReturnFilter,
            _t: Date.now()
        });
        const data = await fetchJSON(`${API}?${params}`);

        if (!data.success) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--danger);">${data.message || 'Failed to load'}</td></tr>`;
            return;
        }

        returnsList = data.data || [];

        if (returnsList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--ink-mute);">No return requests found</td></tr>';
            return;
        }

        tbody.innerHTML = returnsList.map(r => {
            const type = r.return_type || 'refund';
            const statusClass = r.status === 'approved' || r.status === 'completed' ? 'delivered'
                              : r.status === 'rejected' ? 'cancelled'
                              : 'pending';
            return `
                <tr>
                    <td>
                        <span class="order-number" onclick="viewReturn(${r.id})">R-${String(r.id).padStart(4, '0')}</span>
                        ${r.item_count > 0 ? `<div style="font-size:0.72rem;color:var(--ink-mute);margin-top:4px;">${r.item_count} item${r.item_count === 1 ? '' : 's'}</div>` : ''}
                    </td>
                    <td><span class="order-number" onclick="viewOrder(${r.order_id})">${escapeHtml(r.order_number)}</span></td>
                    <td>
                        <div style="font-weight:600;">${escapeHtml(r.customer_name)}</div>
                        <div style="font-size:0.78rem;color:var(--ink-mute);">${escapeHtml(r.customer_phone || '')}</div>
                    </td>
                    <td><span class="return-type-badge ${type}">${type}</span></td>
                    <td><strong>${formatPrice(r.refund_amount || 0)}</strong></td>
                    <td><span class="status-badge ${statusClass}">${escapeHtml(r.status)}</span></td>
                    <td style="font-size:0.82rem;color:var(--ink-mute);">${formatDate(r.created_at)}</td>
                    <td><button class="btn-icon" onclick="viewReturn(${r.id})">View</button></td>
                </tr>
            `;
        }).join('');

    } catch (e) {
        if (!silent) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--danger);">
                ⚠️ ${e.message}
                <div style="margin-top:12px;"><button onclick="loadReturns()" style="padding:8px 16px;background:var(--grad);color:#fff;border:none;border-radius:6px;font-family:inherit;font-weight:600;cursor:pointer;">Retry</button></div>
            </td></tr>`;
        }
    }
}

function viewReturn(id) {
    const r = returnsList.find(x => x.id == id);
    if (!r) return;

    const itemsHtml = (r.items || []).map(i => `
        <div class="item-line">
            <span>${escapeHtml(i.product_name)} <span class="qty">× ${i.quantity}</span></span>
            <span>${formatPrice((i.product_price || 0) * (i.quantity || 1))}</span>
        </div>
    `).join('') || '<p style="color:var(--ink-mute);">No items</p>';

    const isPending = r.status === 'pending';
    const isApproved = r.status === 'approved';

    document.getElementById('modalContent').innerHTML = `
        <h2>
            Return R-${String(r.id).padStart(4, '0')}
            <button class="btn-close" onclick="closeModal()" style="padding:6px 14px;margin:0;font-size:0.82rem;">✕</button>
        </h2>

        <div class="modal-section">
            <h3>Return Summary</h3>
            <div class="detail-row"><span class="label">Order</span><span class="value">${escapeHtml(r.order_number)}</span></div>
            <div class="detail-row"><span class="label">Type</span><span class="value"><span class="return-type-badge ${r.return_type || 'refund'}">${r.return_type || 'refund'}</span></span></div>
            <div class="detail-row"><span class="label">Refund Amount</span><span class="value" style="color:var(--blue);font-weight:800;">${formatPrice(r.refund_amount || 0)}</span></div>
            <div class="detail-row"><span class="label">Status</span><span class="value"><span class="status-badge ${r.status === 'approved' || r.status === 'completed' ? 'delivered' : (r.status === 'rejected' ? 'cancelled' : 'pending')}">${escapeHtml(r.status)}</span></span></div>
            <div class="detail-row"><span class="label">Requested</span><span class="value">${formatDate(r.created_at)}</span></div>
        </div>

        <div class="modal-section">
            <h3>Customer</h3>
            <div class="detail-row"><span class="label">Name</span><span class="value">${escapeHtml(r.customer_name)}</span></div>
            <div class="detail-row"><span class="label">Email</span><span class="value">${escapeHtml(r.customer_email || '')}</span></div>
            <div class="detail-row"><span class="label">Phone</span><span class="value">${escapeHtml(r.customer_phone || '')}</span></div>
        </div>

        <div class="modal-section">
            <h3>Reason from Customer</h3>
            <div style="background:var(--light);padding:14px;border-radius:10px;font-size:0.9rem;line-height:1.6;color:var(--ink);">
                ${escapeHtml(r.reason || 'No reason provided')}
            </div>
        </div>

        <div class="modal-section">
            <h3>Items Being Returned</h3>
            ${itemsHtml}
        </div>

        ${r.admin_note ? `
        <div class="modal-section">
            <h3>Admin Note</h3>
            <div style="background:rgba(37,99,255,0.06);padding:14px;border-radius:10px;font-size:0.9rem;line-height:1.6;">
                ${escapeHtml(r.admin_note)}
            </div>
        </div>
        ` : ''}

        ${isPending || isApproved ? `
        <div class="modal-section">
            <h3>Admin Action</h3>
            <label style="display:block;font-weight:600;font-size:0.88rem;margin-bottom:8px;">Admin note (optional)</label>
            <textarea id="adminReturnNote" rows="2" placeholder="Add a note about this decision..." style="width:100%;padding:12px 14px;border:1.5px solid var(--line);border-radius:8px;font-family:inherit;font-size:0.95rem;resize:vertical;margin-bottom:14px;"></textarea>

            ${isPending ? `
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <button onclick="updateReturnStatus(${r.id}, 'approved')"
                        style="flex:1;min-width:140px;padding:12px 20px;background:var(--success);color:#fff;border:none;border-radius:10px;font-family:inherit;font-weight:700;cursor:pointer;">
                        ✅ Approve Return
                    </button>
                    <button onclick="updateReturnStatus(${r.id}, 'rejected')"
                        style="flex:1;min-width:140px;padding:12px 20px;background:var(--danger);color:#fff;border:none;border-radius:10px;font-family:inherit;font-weight:700;cursor:pointer;">
                        ❌ Reject Return
                    </button>
                </div>
            ` : `
                <div style="background:rgba(16,185,129,0.06);padding:14px;border-radius:10px;font-size:0.88rem;line-height:1.6;margin-bottom:14px;">
                    Return approved. Waiting for items to arrive back. Once received, mark as <strong>Completed</strong> to finalize the refund.
                </div>
                <button onclick="updateReturnStatus(${r.id}, 'completed')"
                    style="width:100%;padding:14px 20px;background:var(--grad);color:#fff;border:none;border-radius:10px;font-family:inherit;font-weight:700;cursor:pointer;">
                    ✅ Mark as Completed (Issue Refund)
                </button>
            `}
        </div>
        ` : ''}

        <button class="btn-close" onclick="closeModal()" style="width:100%;margin-top:20px;">Close</button>
    `;

    document.getElementById('orderModal').classList.add('active');
}

async function updateReturnStatus(id, status) {
    const note = document.getElementById('adminReturnNote')?.value.trim() || '';

    const payload = {
        action: 'update-return-status',
        id: id,
        status: status,
        admin_note: note
    };

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (data.success) {
            showToast(`Return ${status}`);
            closeModal();
            loadReturns(true);
            loadOrders(true);
            refreshPendingReturnsCount();
        } else {
            showToast(data.message || 'Failed', 'error');
        }
    } catch (e) {
        showToast(e.message, 'error');
    }
}

async function refreshPendingReturnsCount() {
    try {
        const data = await fetchJSON(`${API}?action=returns-pending-count&_=${Date.now()}`);
        if (data.success) {
            // Tab badge
            const badge = document.getElementById('pendingReturnsBadge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }
            }
            // Sidebar badge
            const sidebarBadge = document.getElementById('sidebarReturnsBadge');
            if (sidebarBadge) {
                if (data.count > 0) {
                    sidebarBadge.textContent = data.count;
                    sidebarBadge.style.display = '';
                } else {
                    sidebarBadge.style.display = 'none';
                }
            }
        }
    } catch (e) { /* silent */ }
}

// ==================== SHARED ====================
function closeModal() {
    document.getElementById('orderModal').classList.remove('active');
}

function manualRefresh() {
    const btn = document.getElementById('refreshBtn');
    btn.classList.add('spinning');
    const tasks = currentView === 'returns' ? [loadReturns(true), refreshPendingReturnsCount()] : [loadOrders(true)];
    Promise.all(tasks).finally(() => {
        setTimeout(() => btn.classList.remove('spinning'), 500);
    });
}

function showToast(msg, type='success'){
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : (type === 'info' ? ' info' : ''));
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

// ==================== EVENT LISTENERS ====================
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        currentStatus = tab.getAttribute('data-status');
        loadOrders();
    });
});

document.querySelectorAll('.return-filter-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.return-filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        currentReturnFilter = tab.getAttribute('data-status');
        loadReturns();
    });
});

document.getElementById('orderModal').addEventListener('click', (e) => {
    if (e.target.id === 'orderModal') closeModal();
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
});

// Auto-refresh every 30 seconds
function startAutoRefresh() {
    if (autoRefreshTimer) clearInterval(autoRefreshTimer);
    autoRefreshTimer = setInterval(() => {
        if (currentView === 'returns') {
            loadReturns(true);
        } else {
            loadOrders(true);
        }
        refreshPendingReturnsCount();
    }, 30000);
}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        if (autoRefreshTimer) clearInterval(autoRefreshTimer);
        autoRefreshTimer = null;
    } else {
        if (currentView === 'returns') loadReturns(true);
        else loadOrders(true);
        refreshPendingReturnsCount();
        startAutoRefresh();
    }
});

// ==================== INIT ====================
loadOrders();
refreshPendingReturnsCount();
startAutoRefresh();
</script>

</body>
</html>