<?php
require_once '../config/auth.php';
requireLogin();
$currentUser = getCurrentUser();

// Only admins/editors
if (!in_array($currentUser['role'], ['admin', 'editor'])) {
    header('Location: index.php');
    exit;
}

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
<title>Shop Categories — WayronX Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root{--midnight:#050816;--blue:#2563FF;--purple:#7C3AED;--light:#F7F8FC;--ink:#0B1026;--ink-mute:#5A6180;--line:rgba(11,16,38,0.10);--grad:linear-gradient(120deg,#2563FF,#7C3AED);--success:#10b981;--danger:#ef4444;--warning:#f59e0b;--sidebar-width:260px;}
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Manrope',sans-serif;background:#f1f5f9;color:var(--ink);overflow-x:hidden;}
    a{text-decoration:none;color:inherit;}
    ul{list-style:none;}

    /* ============ SIDEBAR ============ */
    .sidebar{width:var(--sidebar-width);background:var(--midnight);color:#fff;position:fixed;top:0;left:0;height:100vh;padding:20px 0;display:flex;flex-direction:column;z-index:1000;transition:transform 0.3s ease;}
    .sidebar-header{padding:0 20px 20px;border-bottom:1px solid rgba(255,255,255,0.1);font-weight:800;color:#fff;font-size:1.05rem;display:flex;gap:10px;align-items:center;min-height:70px;}
    .sidebar-header .logo{display:flex;align-items:center;gap:10px;color:#fff;}
    .sidebar-header .logo svg{width:28px;height:28px;flex-shrink:0;}
    .sidebar-nav{flex:1;padding:16px 0;overflow-y:auto;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,0.15) transparent;}
    .sidebar-nav::-webkit-scrollbar{width:6px;}
    .sidebar-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.15);border-radius:3px;}
    .sidebar-nav a{display:flex;gap:12px;padding:12px 20px;color:rgba(255,255,255,0.7);font-weight:500;font-size:0.92rem;transition:background 0.2s,color 0.2s;align-items:center;position:relative;-webkit-tap-highlight-color:transparent;}
    .sidebar-nav a:hover,.sidebar-nav a.active{background:rgba(255,255,255,0.08);color:#fff;}
    .sidebar-nav a.active::before{content:'';position:absolute;left:0;top:0;height:100%;width:3px;background:var(--grad);}
    .sidebar-nav .icon{font-size:1.15rem;width:22px;text-align:center;flex-shrink:0;line-height:1;}
    .sidebar-nav .badge{margin-left:auto;background:var(--danger);color:#fff;padding:2px 8px;border-radius:10px;font-size:0.72rem;font-weight:700;min-width:20px;text-align:center;}
    .sidebar-nav .nav-section{padding:16px 20px 6px;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);}
    .sidebar-footer{padding:20px;border-top:1px solid rgba(255,255,255,0.1);}
    .sidebar-footer a{display:flex;gap:10px;color:rgba(255,255,255,0.7);font-weight:500;font-size:0.9rem;transition:color 0.2s;}
    .sidebar-footer a:hover{color:#fff;}

    /* ============ MAIN ============ */
    .main{margin-left:var(--sidebar-width);padding:24px;min-height:100vh;width:calc(100% - var(--sidebar-width));min-width:0;}
    .top-bar{background:#fff;border-radius:12px;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);gap:16px;flex-wrap:wrap;}
    .top-bar h1{font-size:1.3rem;font-weight:700;margin:0;line-height:1.2;}
    .top-bar .welcome{font-size:0.9rem;color:var(--ink-mute);}

    .toolbar-admin{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
    .toolbar-left{flex:1;min-width:0;display:flex;gap:10px;flex-wrap:wrap;}
    .btn-add{padding:12px 22px;background:var(--grad);color:#fff;border:none;border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;display:inline-flex;gap:8px;align-items:center;font-size:0.9rem;transition:transform 0.15s,box-shadow 0.2s;-webkit-tap-highlight-color:transparent;white-space:nowrap;}
    .btn-add:hover{transform:translateY(-2px);box-shadow:0 8px 16px rgba(37,99,255,0.3);}
    .btn-add:focus-visible{outline:2px solid var(--blue);outline-offset:2px;}

    .search-admin{padding:12px 16px;border:1.5px solid var(--line);border-radius:8px;font-family:inherit;font-size:0.95rem;background:#fff;color:var(--ink);min-width:0;width:100%;max-width:340px;transition:border-color 0.2s,box-shadow 0.2s;}
    .search-admin:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,255,0.1);}

    .cat-table{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);}
    .cat-table-inner{overflow-x:auto;}
    table{width:100%;border-collapse:collapse;min-width:640px;}
    th{background:var(--light);text-align:left;padding:14px 16px;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--ink-mute);font-weight:700;border-bottom:1px solid var(--line);white-space:nowrap;}
    td{padding:14px 16px;border-bottom:1px solid var(--line);font-size:0.9rem;vertical-align:middle;}
    tr:last-child td{border-bottom:none;}
    tr{transition:background 0.15s;}
    tr:hover{background:var(--light);}

    .cat-icon{width:44px;height:44px;border-radius:8px;background:var(--light);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;}
    .cat-name{font-weight:600;color:var(--ink);word-break:break-word;}
    .cat-slug{font-size:0.75rem;color:var(--ink-mute);margin-top:2px;font-family:'Courier New',monospace;word-break:break-word;}
    .cat-desc{font-size:0.82rem;color:var(--ink-mute);line-height:1.4;margin-top:4px;word-break:break-word;}

    .status-badge{display:inline-flex;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:700;white-space:nowrap;}
    .status-badge.active{background:rgba(16,185,129,0.1);color:var(--success);}
    .status-badge.inactive{background:rgba(239,68,68,0.1);color:var(--danger);}

    .count-badge{display:inline-flex;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:700;background:rgba(37,99,255,0.1);color:var(--blue);white-space:nowrap;}

    .action-btns{display:flex;gap:6px;}
    .btn-icon{padding:6px 10px;background:var(--light);border:none;border-radius:6px;cursor:pointer;font-size:0.85rem;font-family:inherit;transition:background 0.2s,color 0.2s;-webkit-tap-highlight-color:transparent;white-space:nowrap;}
    .btn-icon:hover{background:var(--blue);color:#fff;}
    .btn-icon.danger:hover{background:var(--danger);color:#fff;}
    .btn-icon:focus-visible{outline:2px solid var(--blue);outline-offset:2px;}

    /* Modal */
    .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:2000;padding:20px;}
    .modal-overlay.active{display:flex;}
    .modal{background:#fff;border-radius:16px;max-width:600px;width:100%;max-height:90vh;overflow-y:auto;padding:32px;animation:modalIn 0.25s ease;}
    @keyframes modalIn{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
    .modal h2{font-size:1.3rem;font-weight:700;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--line);margin-top:0;}
    .form-group{margin-bottom:16px;}
    label{display:block;font-weight:600;font-size:0.88rem;margin-bottom:6px;color:var(--ink);}
    label .req{color:var(--danger);}
    label .hint{font-weight:400;color:var(--ink-mute);font-size:0.78rem;margin-left:4px;}
    input,select,textarea{width:100%;padding:10px 14px;border:1.5px solid var(--line);border-radius:8px;font-family:inherit;font-size:0.95rem;background:#fff;color:var(--ink);transition:border-color 0.2s,box-shadow 0.2s;box-sizing:border-box;}
    input:focus,select:focus,textarea:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,255,0.1);}
    textarea{resize:vertical;min-height:70px;}
    .modal-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);}
    .btn-cancel{padding:12px 24px;background:var(--light);border:none;border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;font-size:0.9rem;transition:background 0.2s;-webkit-tap-highlight-color:transparent;}
    .btn-cancel:hover{background:rgba(11,16,38,0.08);}
    .btn-save{padding:12px 24px;background:var(--grad);color:#fff;border:none;border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;font-size:0.9rem;transition:transform 0.15s,box-shadow 0.2s;-webkit-tap-highlight-color:transparent;}
    .btn-save:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,255,0.3);}
    .btn-save:disabled{opacity:0.6;cursor:not-allowed;transform:none;}

    .toast{position:fixed;bottom:24px;right:24px;background:var(--success);color:#fff;padding:14px 20px;border-radius:10px;font-weight:600;font-size:0.9rem;z-index:9999;animation:slideUp 0.25s ease-out;box-shadow:0 10px 30px rgba(0,0,0,0.2);max-width:calc(100vw - 32px);word-break:break-word;}
    .toast.error{background:var(--danger);}
    .toast.info{background:var(--blue);}
    @keyframes slideUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}

    /* Sidebar overlay */
    .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;opacity:0;transition:opacity 0.3s;}
    .sidebar-overlay.active{display:block;opacity:1;}

    /* Menu toggle */
    .menu-toggle{display:none;background:none;border:none;cursor:pointer;font-size:1.5rem;color:var(--ink);padding:5px 8px;border-radius:8px;-webkit-tap-highlight-color:transparent;}
    .menu-toggle:hover{background:var(--light);}

    /* Accessibility */
    .menu-toggle:focus-visible,.sidebar-nav a:focus-visible{outline:2px solid var(--blue);outline-offset:2px;}
    .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;}

    @media (max-width:768px){
        .sidebar{transform:translateX(-100%);}
        .sidebar.active{transform:translateX(0);box-shadow:0 0 40px rgba(0,0,0,0.3);}
        .main{margin-left:0;width:100%;padding:15px;}
        .menu-toggle{display:block;}
        .toolbar-admin{flex-direction:column;align-items:stretch;}
        .toolbar-left{width:100%;}
        .search-admin{max-width:none;}
        .btn-add{justify-content:center;}
        .modal{padding:22px 18px;border-radius:14px;}
        .top-bar{padding:14px 16px;}
    }
    @media (max-width:480px){
        .main{padding:12px;}
        .top-bar{padding:10px 12px;}
        .top-bar h1{font-size:1.1rem;}
        .modal-actions{flex-direction:column-reverse;}
        .modal-actions .btn-cancel,.modal-actions .btn-save{width:100%;justify-content:center;}
        .toast{left:16px;right:16px;bottom:16px;text-align:center;}
        th,td{padding:10px 12px;font-size:0.85rem;}
    }
</style>
</head>
<body>

<!-- Sidebar overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

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
            <li class="nav-section">Overview</li>
            <li><a href="index.php"><span class="icon">📊</span>Overview</a></li>
            <li><a href="#"><span class="icon">📈</span>Analytics</a></li>

            <li class="nav-section">Content</li>
            <li><a href="#"><span class="icon">📝</span>Blog Posts</a></li>
            <li><a href="#"><span class="icon">🛠️</span>Services</a></li>
            <li><a href="#"><span class="icon">💡</span>Solutions</a></li>

            <li class="nav-section">Shop</li>
            <li><a href="shop-products.php"><span class="icon">🛍️</span>Products</a></li>
            <li><a href="shop-orders.php"><span class="icon">📦</span>Orders</a></li>
            <li><a href="shop-categories.php" class="active"><span class="icon">📁</span>Categories</a></li>
            <li><a href="shop-delivery.php"><span class="icon">🚚</span>Delivery Regions</a></li>
            <li><a href="shop-settings.php"><span class="icon">⚙️</span>Shop Settings</a></li>

            <li class="nav-section">People</li>
            <li><a href="#"><span class="icon">👥</span>Users</a></li>
            <li><a href="messages.php"><span class="icon">✉️</span>Messages <?php if ($unreadCount > 0): ?><span class="badge"><?php echo $unreadCount; ?></span><?php endif; ?></a></li>
            <li><a href="#"><span class="icon">👨‍💼</span>Team (HRM)</a></li>
            <li><a href="#"><span class="icon">💼</span>Careers</a></li>

            <li class="nav-section">Finance</li>
            <li><a href="#"><span class="icon">💰</span>Finances</a></li>

            <li class="nav-section">System</li>
            <li><a href="#"><span class="icon">⚙️</span>Settings</a></li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="https://wayronx.com/"><span>🏠</span> Back to Website</a>
    </div>
</aside>

<main class="main">
    <div class="top-bar">
        <div style="display:flex;align-items:center;gap:16px;min-width:0;">
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">☰</button>
            <h1>📁 Shop Categories</h1>
        </div>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($currentUser['full_name']); ?></div>
    </div>

    <div class="toolbar-admin">
        <div class="toolbar-left">
            <label for="searchAdmin" class="sr-only">Search categories</label>
            <input type="text" class="search-admin" id="searchAdmin" placeholder="Search categories...">
        </div>
        <button class="btn-add" onclick="openCategoryModal()">+ Add Category</button>
    </div>

    <div class="cat-table">
        <div class="cat-table-inner">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--ink-mute);">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Category Modal -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal">
        <h2 id="modalTitle">Add Category</h2>
        <form id="categoryForm">
            <input type="hidden" id="categoryId">

            <div class="form-group">
                <label>Category Name <span class="req">*</span></label>
                <input type="text" id="catName" required placeholder="e.g. Laptops, Printers, Networking">
            </div>

            <div class="form-group">
                <label>Slug <span class="hint">(auto-generated if left blank)</span></label>
                <input type="text" id="catSlug" placeholder="e.g. laptops-printers">
            </div>

            <div class="form-group">
                <label>Icon <span class="hint">(emoji)</span></label>
                <input type="text" id="catIcon" placeholder="💻" maxlength="8">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea id="catDescription" placeholder="Short description of what this category contains" maxlength="500"></textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select id="catActive">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
const API = '../api/shop-admin.php';
let categoriesList = [];

/* ---------- Sidebar toggle (UI only) ---------- */
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
if (menuToggle && sidebar && sidebarOverlay) {
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    });
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    });
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
        throw new Error('The server returned an unexpected response. Check the browser console.');
    }

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error('Invalid JSON from API:', text.substring(0, 500));
        throw new Error('The server sent invalid data. Please try again.');
    }
}

async function loadCategories() {
    const tbody = document.getElementById('categoriesTableBody');
    tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:40px;">Loading...</td></tr>';
    try {
        const data = await fetchJSON(`${API}?action=categories&_=${Date.now()}`);
        if (!data.success) {
            tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:40px;color:var(--danger);">${data.message || 'Failed to load'}</td></tr>`;
            return;
        }
        categoriesList = data.data || [];

        if (categoriesList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:40px;color:var(--ink-mute);">No categories yet. Click "Add Category" to get started.</td></tr>';
            return;
        }

        const search = document.getElementById('searchAdmin').value.toLowerCase();
        const filtered = search
            ? categoriesList.filter(c =>
                (c.name || '').toLowerCase().includes(search) ||
                (c.slug || '').toLowerCase().includes(search))
            : categoriesList;

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:40px;color:var(--ink-mute);">No categories match "${escapeHtml(search)}"</td></tr>`;
            return;
        }

        tbody.innerHTML = filtered.map(c => {
            const isActive = c.is_active == 1;
            const productCount = c.product_count || 0;
            return `
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="cat-icon">${escapeHtml(c.icon || '📦')}</div>
                            <div style="min-width:0;">
                                <div class="cat-name">${escapeHtml(c.name)}</div>
                                <div class="cat-slug">/${escapeHtml(c.slug || '')}</div>
                                ${c.description ? `<div class="cat-desc">${escapeHtml(c.description)}</div>` : ''}
                            </div>
                        </div>
                    </td>
                    <td><span class="count-badge">${productCount} product${productCount === 1 ? '' : 's'}</span></td>
                    <td><span class="status-badge ${isActive ? 'active' : 'inactive'}">${isActive ? 'Active' : 'Inactive'}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon" onclick="editCategory(${c.id})" title="Edit">✏️</button>
                            <button class="btn-icon danger" onclick="deleteCategory(${c.id})" title="Delete">🗑️</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:40px;color:var(--danger);">
            ⚠️ ${escapeHtml(e.message)}
            <div style="margin-top:12px;"><button onclick="loadCategories()" style="padding:8px 16px;background:var(--grad);color:#fff;border:none;border-radius:6px;font-family:inherit;font-weight:600;cursor:pointer;">Retry</button></div>
        </td></tr>`;
    }
}

function openCategoryModal() {
    document.getElementById('modalTitle').textContent = 'Add Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('catActive').value = '1';
    document.getElementById('categoryModal').classList.add('active');
}

function closeModal() {
    document.getElementById('categoryModal').classList.remove('active');
}

function editCategory(id) {
    const c = categoriesList.find(x => x.id == id);
    if (!c) return;
    document.getElementById('modalTitle').textContent = 'Edit Category';
    document.getElementById('categoryId').value = c.id;
    document.getElementById('catName').value = c.name || '';
    document.getElementById('catSlug').value = c.slug || '';
    document.getElementById('catIcon').value = c.icon || '';
    document.getElementById('catDescription').value = c.description || '';
    document.getElementById('catActive').value = c.is_active == 1 ? '1' : '0';
    document.getElementById('categoryModal').classList.add('active');
}

async function deleteCategory(id) {
    const c = categoriesList.find(x => x.id == id);
    const name = c ? c.name : 'this category';

    if (c && c.product_count > 0) {
        if (!confirm(`"${name}" has ${c.product_count} product(s). Deleting it will leave those products without a category. Continue?`)) return;
    } else {
        if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;
    }

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'delete-category', id })
        });
        if (data.success) { loadCategories(); showToast('Category deleted'); }
        else showToast(data.message || 'Failed', 'error');
    } catch (e) { showToast(e.message, 'error'); }
}

/* Auto-slug: if user types name and slug is empty, generate from name */
document.getElementById('catName').addEventListener('input', function() {
    const slugField = document.getElementById('catSlug');
    // Only auto-fill if slug is empty OR matches what the previous name would have generated
    if (!slugField.value || slugField.dataset.autoFilled === '1') {
        slugField.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugField.dataset.autoFilled = '1';
    }
});
document.getElementById('catSlug').addEventListener('input', function() {
    // User manually edited — stop auto-filling
    delete this.dataset.autoFilled;
});

document.getElementById('categoryForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('categoryId').value;
    const payload = {
        action: id ? 'update-category' : 'create-category',
        id: id || undefined,
        name: document.getElementById('catName').value.trim(),
        slug: document.getElementById('catSlug').value.trim(),
        icon: document.getElementById('catIcon').value.trim(),
        description: document.getElementById('catDescription').value.trim(),
        is_active: document.getElementById('catActive').value
    };

    const btn = document.querySelector('#categoryForm .btn-save');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        if (data.success) {
            closeModal();
            loadCategories();
            showToast(id ? 'Category updated' : 'Category added');
        } else {
            showToast(data.message || 'Failed', 'error');
        }
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
});

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : (type === 'info' ? ' info' : ''));
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

/* ---------- Close modal / sidebar on overlay click + Escape (UI only) ---------- */
document.getElementById('categoryModal').addEventListener('click', (e) => {
    if (e.target.id === 'categoryModal') closeModal();
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeModal();
        if (sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
});

document.getElementById('searchAdmin').addEventListener('input', loadCategories);

loadCategories();
</script>

</body>
</html>