<?php
require_once __DIR__ . '/../config/auth.php';
requireLogin();
$currentUser = getCurrentUser();

// Only admins/editors
if (!in_array($currentUser['role'], ['admin', 'editor'])) {
    header('Location: index.php');
    exit;
}

//require_once __DIR__ . '/../config/database.php';
$pdo = getConnection();

// Unread messages count for sidebar
$stmtUnread = $pdo->prepare("SELECT COUNT(id) as total FROM messages WHERE is_read = 0");
$stmtUnread->execute();
$unreadCount = $stmtUnread->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delivery Regions — WayronX Admin</title>
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

    .info-banner{
        background:linear-gradient(120deg,rgba(37,99,255,0.06),rgba(124,58,237,0.06));
        border:1px solid rgba(37,99,255,0.15);
        border-radius:12px;padding:16px 20px;margin-bottom:20px;
        display:flex;gap:14px;align-items:flex-start;
        font-size:0.9rem;line-height:1.55;
    }
    .info-banner .icon{font-size:1.4rem;flex-shrink:0;line-height:1;}
    .info-banner strong{color:var(--blue);}
    .info-banner .free-note{
        display:inline-block;padding:3px 8px;
        background:rgba(16,185,129,0.12);color:var(--success);
        border-radius:6px;font-weight:700;font-size:0.8rem;
        margin-left:6px;
    }

    /* Toolbar */
    .toolbar-admin{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
    .btn-add{
        padding:12px 22px;background:var(--grad);color:#fff;border:none;
        border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;
        display:inline-flex;gap:8px;align-items:center;font-size:0.9rem;
        transition:all 0.2s;
    }
    .btn-add:hover{transform:translateY(-2px);box-shadow:0 8px 16px rgba(37,99,255,0.3);}

    /* Table */
    .region-table{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);}
    table{width:100%;border-collapse:collapse;}
    th{background:var(--light);text-align:left;padding:14px 16px;font-size:0.78rem;text-transform:uppercase;color:var(--ink-mute);font-weight:700;letter-spacing:0.05em;border-bottom:1px solid var(--line);}
    td{padding:16px;border-bottom:1px solid var(--line);font-size:0.9rem;vertical-align:middle;}
    tr:last-child td{border-bottom:none;}
    tr:hover{background:var(--light);}

    .region-name{font-weight:700;font-size:0.98rem;margin-bottom:2px;}
    .region-desc{font-size:0.78rem;color:var(--ink-mute);line-height:1.4;}

    .fee-badge{
        display:inline-flex;align-items:center;gap:6px;
        padding:6px 12px;border-radius:8px;
        font-weight:700;font-size:0.85rem;
    }
    .fee-badge.paid{background:rgba(37,99,255,0.1);color:var(--blue);}
    .fee-badge.free{background:rgba(16,185,129,0.1);color:var(--success);}

    .days-badge{
        display:inline-block;padding:4px 10px;
        background:var(--light);border-radius:6px;
        font-size:0.8rem;font-weight:600;color:var(--ink-mute);
    }

    .status-badge{
        display:inline-flex;padding:4px 10px;border-radius:6px;
        font-size:0.72rem;font-weight:700;
    }
    .status-badge.active{background:rgba(16,185,129,0.1);color:var(--success);}
    .status-badge.inactive{background:rgba(239,68,68,0.1);color:var(--danger);}

    .action-btns{display:flex;gap:6px;}
    .btn-icon{
        padding:6px 12px;background:var(--light);border:none;border-radius:6px;
        cursor:pointer;font-size:0.85rem;font-family:inherit;font-weight:500;
        transition:all 0.2s;
    }
    .btn-icon:hover{background:var(--blue);color:#fff;}
    .btn-icon.danger:hover{background:var(--danger);}

    /* Empty state */
    .empty-state{text-align:center;padding:60px 20px;}
    .empty-state .icon{font-size:3.5rem;margin-bottom:14px;opacity:0.5;}
    .empty-state h3{font-size:1.1rem;font-weight:700;margin-bottom:8px;}
    .empty-state p{color:var(--ink-mute);margin-bottom:20px;font-size:0.9rem;}

    /* Modal */
    .modal-overlay{
        position:fixed;inset:0;background:rgba(0,0,0,0.6);
        display:none;align-items:center;justify-content:center;
        z-index:1000;padding:20px;
    }
    .modal-overlay.active{display:flex;}
    .modal{
        background:#fff;border-radius:16px;max-width:520px;width:100%;
        max-height:90vh;overflow-y:auto;padding:32px;
        animation:modalIn 0.25s ease;
    }
    @keyframes modalIn{
        from{opacity:0;transform:translateY(12px);}
        to{opacity:1;transform:translateY(0);}
    }
    .modal h2{font-size:1.25rem;font-weight:700;margin-bottom:8px;}
    .modal .modal-sub{color:var(--ink-mute);font-size:0.88rem;margin-bottom:24px;line-height:1.5;}

    .form-group{margin-bottom:16px;}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    label{display:block;font-weight:600;font-size:0.88rem;margin-bottom:6px;}
    label .req{color:var(--danger);}
    input,select,textarea{
        width:100%;padding:11px 14px;
        border:1.5px solid var(--line);border-radius:8px;
        font-family:inherit;font-size:0.95rem;
        background:#fff;color:var(--ink);
        transition:border-color 0.2s;
    }
    input:focus,select:focus,textarea:focus{
        outline:none;border-color:var(--blue);
        box-shadow:0 0 0 3px rgba(37,99,255,0.1);
    }
    textarea{resize:vertical;min-height:70px;}

    .checkbox-row{
        display:flex;align-items:center;gap:10px;
        padding:12px 14px;background:var(--light);border-radius:8px;
        cursor:pointer;
    }
    .checkbox-row input{width:auto;margin:0;cursor:pointer;accent-color:var(--blue);}
    .checkbox-row .checkbox-text{font-size:0.88rem;font-weight:500;line-height:1.4;}
    .checkbox-row .checkbox-text small{display:block;color:var(--ink-mute);font-size:0.78rem;font-weight:400;margin-top:2px;}

    .fee-input-group{position:relative;}
    .fee-input-group input{padding-left:56px;}
    .fee-input-group .prefix{
        position:absolute;left:14px;top:50%;transform:translateY(-50%);
        font-weight:700;font-size:0.9rem;color:var(--ink-mute);
        pointer-events:none;
    }
    .fee-input-group.free input{
        background:rgba(16,185,129,0.05);
        color:var(--ink-mute);
        cursor:not-allowed;
    }

    .modal-actions{
        display:flex;gap:12px;justify-content:flex-end;
        margin-top:24px;padding-top:20px;
        border-top:1px solid var(--line);
    }
    .btn-cancel{
        padding:12px 24px;background:var(--light);border:none;
        border-radius:8px;font-family:inherit;font-weight:600;
        cursor:pointer;font-size:0.9rem;
    }
    .btn-cancel:hover{background:rgba(11,16,38,0.08);}
    .btn-save{
        padding:12px 24px;background:var(--grad);color:#fff;border:none;
        border-radius:8px;font-family:inherit;font-weight:600;
        cursor:pointer;font-size:0.9rem;transition:all 0.2s;
    }
    .btn-save:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,255,0.3);}
    .btn-save:disabled{opacity:0.6;cursor:not-allowed;transform:none;}

    /* Confirm modal */
    .modal.confirm{max-width:420px;text-align:center;}
    .modal.confirm .icon{
        width:64px;height:64px;border-radius:50%;
        background:rgba(239,68,68,0.1);color:var(--danger);
        display:flex;align-items:center;justify-content:center;
        font-size:1.8rem;margin:0 auto 16px;
    }
    .modal.confirm .btn-cancel{background:var(--light);}
    .modal.confirm .btn-danger{
        padding:12px 24px;background:var(--danger);color:#fff;
        border:none;border-radius:8px;font-family:inherit;
        font-weight:600;cursor:pointer;font-size:0.9rem;
    }
    .modal.confirm .btn-danger:hover{background:#dc2626;}

    /* Toast */
    .toast{
        position:fixed;bottom:24px;right:24px;
        background:var(--success);color:#fff;
        padding:14px 20px;border-radius:10px;
        font-weight:600;font-size:0.9rem;
        box-shadow:0 10px 30px rgba(0,0,0,0.2);
        z-index:9999;animation:toastIn 0.3s;
        display:flex;align-items:center;gap:8px;
    }
    .toast.error{background:var(--danger);}
    .toast.info{background:var(--blue);}
    @keyframes toastIn{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}

    @media (max-width:768px){
        .sidebar{transform:translateX(-100%);transition:transform 0.3s;}
        .sidebar.active{transform:translateX(0);}
        .main{margin-left:0;width:100%;}
        .form-row{grid-template-columns:1fr;}
        th,td{padding:12px 10px;font-size:0.85rem;}
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
            <li><a href="shop-delivery.php" class="active"><span class="icon">🚚</span>Delivery Regions</a></li>
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
        <h1>🚚 Delivery Regions</h1>
        <div style="font-size:0.9rem;color:var(--ink-mute);">Welcome, <?php echo htmlspecialchars($currentUser['full_name']); ?></div>
    </div>

    <div class="info-banner">
        <div class="icon">💡</div>
        <div>
            <strong>How this works:</strong> Set the delivery fee for each region. When customers check out, they'll select their region and see the exact delivery cost added to their order total before confirming.
            <br>
            💚 Set the fee to <strong>0</strong> to make delivery <span class="free-note">FREE</span> for that region (e.g. local pickup, within city, or promotional).
        </div>
    </div>

    <div class="toolbar-admin">
        <div style="font-size:0.9rem;color:var(--ink-mute);" id="countLabel"></div>
        <button class="btn-add" onclick="openModal()">+ Add Region</button>
    </div>

    <div class="region-table">
        <table>
            <thead>
                <tr>
                    <th>Region</th>
                    <th>Delivery Fee</th>
                    <th>Est. Delivery</th>
                    <th>Status</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody id="regionsBody">
                <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--ink-mute);">Loading regions...</td></tr>
            </tbody>
        </table>
    </div>
</main>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="regionModal">
    <div class="modal">
        <h2 id="modalTitle">Add Region</h2>
        <p class="modal-sub" id="modalSub">Define a delivery region and set its fee. Set fee to 0 for free delivery.</p>
        <form id="regionForm">
            <input type="hidden" id="regionId">
            
            <div class="form-group">
                <label>Region Name <span class="req">*</span></label>
                <input type="text" id="regionName" required placeholder="e.g. Central Region, Within Kampala" maxlength="100">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Delivery Fee (UGX) <span class="req">*</span></label>
                    <div class="fee-input-group" id="feeWrapper">
                        <span class="prefix">UGX</span>
                        <input type="number" id="regionFee" required min="0" step="500" placeholder="0" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <label>Est. Delivery Time</label>
                    <input type="text" id="regionDays" placeholder="e.g. 1-2 days, Same day" maxlength="50">
                </div>
            </div>

            <div class="form-group">
                <label>Description (optional)</label>
                <textarea id="regionDesc" placeholder="Cities, towns, or districts covered by this region" maxlength="500"></textarea>
            </div>

            <div class="form-group">
                <label class="checkbox-row">
                    <input type="checkbox" id="regionActive" checked>
                    <span class="checkbox-text">
                        Active region
                        <small>Inactive regions won't appear at checkout</small>
                    </span>
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save" id="modalSaveBtn">Save Region</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal confirm">
        <div class="icon">🗑️</div>
        <h2>Delete Region?</h2>
        <p class="modal-sub" style="margin-bottom:20px;">
            This will remove <strong id="deleteRegionName"></strong> from the delivery options. Existing orders are not affected.
        </p>
        <div class="modal-actions" style="justify-content:center;">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" id="confirmDeleteBtn" onclick="confirmDelete()">Delete Region</button>
        </div>
    </div>
</div>

<script>
const API = '../api/shop-delivery.php';
let regions = [];
let pendingDeleteId = null;

function formatPrice(n) {
    n = Number(n) || 0;
    return n === 0 ? 'FREE' : 'UGX ' + n.toLocaleString('en-US');
}

/**
 * Safe fetch that detects HTML error pages
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
        console.error('Invalid JSON:', text.substring(0, 500));
        throw new Error('The server sent invalid data.');
    }
}

async function loadRegions() {
    const tbody = document.getElementById('regionsBody');
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--ink-mute);">Loading regions...</td></tr>';

    try {
        const data = await fetchJSON(`${API}?action=list&_=${Date.now()}`, {
            credentials: 'same-origin'
        });

        if (!data.success) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--danger);">${data.message || 'Failed to load'}</td></tr>`;
            return;
        }

        regions = data.data || [];
        document.getElementById('countLabel').textContent = 
            `${regions.length} region${regions.length === 1 ? '' : 's'} configured`;

        if (regions.length === 0) {
            tbody.innerHTML = `
                <tr><td colspan="5">
                    <div class="empty-state">
                        <div class="icon">🚚</div>
                        <h3>No delivery regions yet</h3>
                        <p>Add your first region to start accepting orders.</p>
                        <button class="btn-add" onclick="openModal()" style="margin:0 auto;">+ Add Region</button>
                    </div>
                </td></tr>
            `;
            return;
        }

        tbody.innerHTML = regions.map(r => {
            const fee = Number(r.delivery_fee) || 0;
            const isFree = fee === 0;
            const feeClass = isFree ? 'free' : 'paid';
            const feeIcon = isFree ? '🎁' : '💰';

            return `
                <tr>
                    <td>
                        <div class="region-name">${escapeHtml(r.name)}</div>
                        ${r.description ? `<div class="region-desc">${escapeHtml(r.description)}</div>` : ''}
                    </td>
                    <td>
                        <span class="fee-badge ${feeClass}">
                            ${feeIcon} ${isFree ? 'FREE' : 'UGX ' + fee.toLocaleString('en-US')}
                        </span>
                    </td>
                    <td>
                        ${r.estimated_days ? `<span class="days-badge">📅 ${escapeHtml(r.estimated_days)}</span>` : '<span style="color:var(--ink-mute);font-size:0.82rem;">—</span>'}
                    </td>
                    <td>
                        <span class="status-badge ${r.is_active == 1 ? 'active' : 'inactive'}">
                            ${r.is_active == 1 ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon" onclick="editRegion(${r.id})" title="Edit">✏️ Edit</button>
                            <button class="btn-icon danger" onclick="askDelete(${r.id})" title="Delete">🗑️</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

    } catch (e) {
        tbody.innerHTML = `
            <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--danger);">
                ⚠️ ${escapeHtml(e.message)}
                <div style="margin-top:12px;">
                    <button onclick="loadRegions()" style="padding:8px 16px;background:var(--grad);color:#fff;border:none;border-radius:6px;font-family:inherit;font-weight:600;cursor:pointer;">Retry</button>
                </div>
            </td></tr>
        `;
    }
}

// ============================================================
//                     MODAL — ADD/EDIT
// ============================================================
function openModal(region = null) {
    const modal = document.getElementById('regionModal');
    const title = document.getElementById('modalTitle');
    const sub = document.getElementById('modalSub');
    const form = document.getElementById('regionForm');

    form.reset();
    document.getElementById('regionId').value = '';

    if (region) {
        title.textContent = 'Edit Region';
        sub.textContent = 'Update the region details. Set fee to 0 for free delivery.';
        document.getElementById('regionId').value = region.id;
        document.getElementById('regionName').value = region.name;
        document.getElementById('regionFee').value = region.delivery_fee;
        document.getElementById('regionDays').value = region.estimated_days || '';
        document.getElementById('regionDesc').value = region.description || '';
        document.getElementById('regionActive').checked = region.is_active == 1;
    } else {
        title.textContent = 'Add Region';
        sub.textContent = 'Define a delivery region and set its fee. Set fee to 0 for free delivery.';
        document.getElementById('regionFee').value = 0;
        document.getElementById('regionActive').checked = true;
    }

    updateFeeVisual();
    modal.classList.add('active');
    setTimeout(() => document.getElementById('regionName').focus(), 100);
}

function closeModal() {
    document.getElementById('regionModal').classList.remove('active');
}

function updateFeeVisual() {
    const feeInput = document.getElementById('regionFee');
    const wrapper = document.getElementById('feeWrapper');
    const fee = parseFloat(feeInput.value) || 0;

    if (fee === 0) {
        wrapper.classList.add('free');
    } else {
        wrapper.classList.remove('free');
    }
}

document.getElementById('regionFee').addEventListener('input', updateFeeVisual);

// ============================================================
//                     FORM SUBMIT
// ============================================================
document.getElementById('regionForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn = document.getElementById('modalSaveBtn');
    const id = document.getElementById('regionId').value;
    const name = document.getElementById('regionName').value.trim();
    const fee = parseFloat(document.getElementById('regionFee').value) || 0;
    const days = document.getElementById('regionDays').value.trim();
    const desc = document.getElementById('regionDesc').value.trim();
    const active = document.getElementById('regionActive').checked ? 1 : 0;

    if (!name) {
        showToast('Region name is required', 'error');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Saving...';

    const payload = {
        action: id ? 'update' : 'create',
        id: id || undefined,
        name: name,
        delivery_fee: fee,
        estimated_days: days,
        description: desc,
        is_active: active
    };

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });

        if (data.success) {
            closeModal();
            await loadRegions();
            showToast(id ? '✓ Region updated' : '✓ Region added');
        } else {
            showToast(data.message || 'Failed to save', 'error');
        }
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Region';
    }
});

// ============================================================
//                     EDIT
// ============================================================
function editRegion(id) {
    const r = regions.find(x => x.id == id);
    if (!r) return;
    openModal(r);
}

// ============================================================
//                     DELETE
// ============================================================
function askDelete(id) {
    const r = regions.find(x => x.id == id);
    if (!r) return;
    pendingDeleteId = id;
    document.getElementById('deleteRegionName').textContent = r.name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    pendingDeleteId = null;
}

async function confirmDelete() {
    if (!pendingDeleteId) return;
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = true;
    btn.textContent = 'Deleting...';

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'delete', id: pendingDeleteId })
        });

        if (data.success) {
            closeDeleteModal();
            await loadRegions();
            showToast('✓ Region deleted');
        } else {
            showToast(data.message || 'Failed to delete', 'error');
        }
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Delete Region';
    }
}

// ============================================================
//                     HELPERS
// ============================================================
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : (type === 'info' ? ' info' : ''));
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => {
        t.style.opacity = '0';
        t.style.transition = 'opacity 0.3s';
        setTimeout(() => t.remove(), 300);
    }, 2500);
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.classList.remove('active');
        }
    });
});

// Close on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
        pendingDeleteId = null;
    }
});

// ============================================================
//                     INIT
// ============================================================
loadRegions();
</script>

</body>
</html>