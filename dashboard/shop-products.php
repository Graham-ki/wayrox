<?php
require_once '../config/auth.php';
requireLogin();
$currentUser = getCurrentUser();
//require_once '../config/database.php';

$pdo = getConnection();

// Unread messages count
$stmtUnread = $pdo->prepare("SELECT COUNT(id) as total FROM messages WHERE is_read = 0");
$stmtUnread->execute();
$unreadCount = $stmtUnread->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shop Products — WayronX Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root{--midnight:#050816;--blue:#2563FF;--purple:#7C3AED;--light:#F7F8FC;--ink:#0B1026;--ink-mute:#5A6180;--line:rgba(11,16,38,0.10);--grad:linear-gradient(120deg,#2563FF,#7C3AED);--success:#10b981;--danger:#ef4444;--warning:#f59e0b;--sidebar-width:260px;}
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Manrope',sans-serif;background:#f1f5f9;color:var(--ink);}
    a{text-decoration:none;color:inherit;}
    ul{list-style:none;}

    /* ============ SIDEBAR ============ */
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
    .sidebar-footer a{display:flex;gap:10px;color:rgba(255,255,255,0.7);font-weight:500;font-size:0.9rem;transition:color 0.2s;}
    .sidebar-footer a:hover{color:#fff;}

    /* ============ MAIN ============ */
    .main{margin-left:var(--sidebar-width);padding:24px;min-height:100vh;width:calc(100% - var(--sidebar-width));}
    .top-bar{background:#fff;border-radius:12px;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);}
    .top-bar h1{font-size:1.3rem;font-weight:700;}

    .toolbar-admin{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
    .btn-add-prod{padding:12px 22px;background:var(--grad);color:#fff;border:none;border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;display:inline-flex;gap:8px;align-items:center;font-size:0.9rem;}
    .btn-add-prod:hover{transform:translateY(-2px);box-shadow:0 8px 16px rgba(37,99,255,0.3);}

    .search-admin{padding:12px 16px;border:1.5px solid var(--line);border-radius:8px;font-family:inherit;min-width:250px;}

    .product-table{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);}
    table{width:100%;border-collapse:collapse;}
    th{background:var(--light);text-align:left;padding:14px 16px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--ink-mute);font-weight:700;border-bottom:1px solid var(--line);}
    td{padding:14px 16px;border-bottom:1px solid var(--line);font-size:0.9rem;}
    tr:last-child td{border-bottom:none;}
    tr:hover{background:var(--light);}

    .prod-thumb{width:50px;height:50px;border-radius:8px;background:var(--light);display:flex;align-items:center;justify-content:center;font-size:1.5rem;overflow:hidden;}
    .prod-thumb img{width:100%;height:100%;object-fit:cover;}
    .prod-name{font-weight:600;}
    .prod-sku{font-size:0.75rem;color:var(--ink-mute);}

    .status-badge{display:inline-flex;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:700;}
    .status-badge.active{background:rgba(16,185,129,0.1);color:var(--success);}
    .status-badge.inactive{background:rgba(239,68,68,0.1);color:var(--danger);}
    .status-badge.low{background:rgba(245,158,11,0.1);color:var(--warning);}

    .action-btns{display:flex;gap:6px;}
    .btn-icon{padding:6px 10px;background:var(--light);border:none;border-radius:6px;cursor:pointer;font-size:0.85rem;font-family:inherit;transition:all 0.2s;}
    .btn-icon:hover{background:var(--blue);color:#fff;}
    .btn-icon.danger:hover{background:var(--danger);}

    /* Modal */
    .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px;}
    .modal-overlay.active{display:flex;}
    .modal{background:#fff;border-radius:16px;max-width:700px;width:100%;max-height:90vh;overflow-y:auto;padding:32px;}
    .modal h2{font-size:1.4rem;font-weight:700;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--line);}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}
    .form-group{margin-bottom:16px;}
    label{display:block;font-weight:600;font-size:0.9rem;margin-bottom:6px;}
    input,select,textarea{width:100%;padding:10px 14px;border:1.5px solid var(--line);border-radius:8px;font-family:inherit;font-size:0.95rem;}
    textarea{resize:vertical;min-height:80px;}
    .modal-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid var(--line);}
    .btn-cancel{padding:12px 24px;background:var(--light);border:none;border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;}
    .btn-save{padding:12px 24px;background:var(--grad);color:#fff;border:none;border-radius:8px;font-family:inherit;font-weight:600;cursor:pointer;}

    .toast{position:fixed;bottom:24px;right:24px;background:var(--success);color:#fff;padding:14px 20px;border-radius:10px;font-weight:600;z-index:9999;animation:slideUp 0.3s;}
    .toast.error{background:var(--danger);}
    @keyframes slideUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}

    @media (max-width:768px) {
        .sidebar{transform:translateX(-100%);transition:transform 0.3s;}
        .sidebar.active{transform:translateX(0);}
        .main{margin-left:0;width:100%;}
        .form-row{grid-template-columns:1fr;}
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
            <li><a href="shop-products.php" class="active"><span class="icon">🛍️</span>Shop Products</a></li>
            <li><a href="shop-orders.php"><span class="icon">📦</span>Shop Orders</a></li>
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
        <h1>🛍️ Shop Products</h1>
        <div style="font-size:0.9rem;color:var(--ink-mute);">Welcome, <?php echo htmlspecialchars($currentUser['full_name']); ?></div>
    </div>

    <div class="toolbar-admin">
        <input type="text" class="search-admin" id="searchAdmin" placeholder="Search products...">
        <button class="btn-add-prod" onclick="openProductModal()">+ Add Product</button>
    </div>

    <div class="product-table">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--ink-mute);">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</main>

<!-- Product Modal -->
<div class="modal-overlay" id="productModal">
    <div class="modal">
        <h2 id="modalTitle">Add Product</h2>
        <form id="productForm">
            <input type="hidden" id="productId">
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" id="prodName" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select id="prodCategory" required></select>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <input type="text" id="prodBrand">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Price (UGX) *</label>
                    <input type="number" id="prodPrice" required min="0" step="1000">
                </div>
                <div class="form-group">
                    <label>Stock Quantity *</label>
                    <input type="number" id="prodStock" required min="0">
                </div>
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <input type="text" id="prodShort" maxlength="300">
            </div>
            <div class="form-group">
                <label>Full Description</label>
                <textarea id="prodDescription"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Image Path</label>
                    <input type="text" id="prodImage" placeholder="assets/shop/products/example.jpg">
                </div>
                <div class="form-group">
                    <label>SKU</label>
                    <input type="text" id="prodSku">
                </div>
            </div>
            <div class="form-group">
                <label>Featured</label>
                <select id="prodFeatured">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Product</button>
            </div>
        </form>
    </div>
</div>

<script>
const API = '../api/shop-admin.php';
let categoriesList = [];
let productsList = [];

function formatPrice(n){return 'UGX ' + Number(n).toLocaleString('en-US');}

/**
 * Safe fetch that returns JSON, or throws a clean error if server returns HTML
 */
async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    const text = await res.text();

    // If it looks like HTML (starts with '<'), the API is broken
    if (text.trim().startsWith('<')) {
        console.error('API returned HTML instead of JSON:', text.substring(0, 500));
        throw new Error('The server returned an unexpected response. Check the browser console for details.');
    }

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error('Invalid JSON from API:', text.substring(0, 500));
        throw new Error('The server sent invalid data. Please try again.');
    }
}

async function loadCategories() {
    try {
        const data = await fetchJSON(`${API}?action=categories`);
        if (data.success) {
            categoriesList = data.data;
            const sel = document.getElementById('prodCategory');
            sel.innerHTML = '<option value="">Select category</option>' + categoriesList.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        }
    } catch (e) {
        console.error('Category load failed:', e.message);
    }
}

async function loadProducts() {
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;">Loading...</td></tr>';
    try {
        const data = await fetchJSON(`${API}?action=products`);
        if (!data.success) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--danger);">${data.message || 'Failed to load'}</td></tr>`;
            return;
        }
        productsList = data.data;

        if (productsList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--ink-mute);">No products yet. Click "Add Product" to get started.</td></tr>';
            return;
        }

        const search = document.getElementById('searchAdmin').value.toLowerCase();
        const filtered = search ? productsList.filter(p => p.name.toLowerCase().includes(search) || (p.sku||'').toLowerCase().includes(search)) : productsList;

        tbody.innerHTML = filtered.map(p => {
            const stockBadge = p.stock_quantity === 0 ? 'inactive' : (p.stock_quantity <= 5 ? 'low' : 'active');
            const stockText = p.stock_quantity === 0 ? 'Out of Stock' : p.stock_quantity;
            return `
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="prod-thumb">${p.image ? `<img src="../${p.image}">` : '💻'}</div>
                            <div>
                                <div class="prod-name">${p.name}</div>
                                <div class="prod-sku">${p.sku || 'No SKU'}</div>
                            </div>
                        </div>
                    </td>
                    <td>${p.category_name || '—'}</td>
                    <td><strong>${formatPrice(p.price)}</strong></td>
                    <td><span class="status-badge ${stockBadge}">${stockText}</span></td>
                    <td><span class="status-badge ${p.is_active == 1 ? 'active' : 'inactive'}">${p.is_active == 1 ? 'Active' : 'Hidden'}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon" onclick="editProduct(${p.id})">✏️</button>
                            <button class="btn-icon danger" onclick="deleteProduct(${p.id})">🗑️</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--danger);">
            ⚠️ ${e.message}
            <div style="margin-top:12px;"><button onclick="loadProducts()" style="padding:8px 16px;background:var(--grad);color:#fff;border:none;border-radius:6px;font-family:inherit;font-weight:600;cursor:pointer;">Retry</button></div>
        </td></tr>`;
    }
}

function openProductModal() {
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('productModal').classList.add('active');
}

function closeModal() {
    document.getElementById('productModal').classList.remove('active');
}

function editProduct(id) {
    const p = productsList.find(x => x.id == id);
    if (!p) return;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = p.id;
    document.getElementById('prodName').value = p.name;
    document.getElementById('prodCategory').value = p.category_id;
    document.getElementById('prodBrand').value = p.brand || '';
    document.getElementById('prodPrice').value = p.price;
    document.getElementById('prodStock').value = p.stock_quantity;
    document.getElementById('prodShort').value = p.short_description || '';
    document.getElementById('prodDescription').value = p.description || '';
    document.getElementById('prodImage').value = p.image || '';
    document.getElementById('prodSku').value = p.sku || '';
    document.getElementById('prodFeatured').value = p.is_featured || '0';
    document.getElementById('productModal').classList.add('active');
}

async function deleteProduct(id) {
    if (!confirm('Delete this product? This cannot be undone.')) return;
    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'delete-product', id })
        });
        if (data.success) { loadProducts(); showToast('Product deleted'); }
        else showToast(data.message || 'Failed', 'error');
    } catch (e) { showToast(e.message, 'error'); }
}

document.getElementById('productForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('productId').value;
    const payload = {
        action: id ? 'update-product' : 'create-product',
        id: id || undefined,
        name: document.getElementById('prodName').value,
        category_id: document.getElementById('prodCategory').value,
        brand: document.getElementById('prodBrand').value,
        price: document.getElementById('prodPrice').value,
        stock_quantity: document.getElementById('prodStock').value,
        short_description: document.getElementById('prodShort').value,
        description: document.getElementById('prodDescription').value,
        image: document.getElementById('prodImage').value,
        sku: document.getElementById('prodSku').value,
        is_featured: document.getElementById('prodFeatured').value
    };

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        if (data.success) {
            closeModal();
            loadProducts();
            showToast(id ? 'Product updated' : 'Product added');
        } else showToast(data.message || 'Failed', 'error');
    } catch (e) { showToast(e.message, 'error'); }
});

function showToast(msg, type='success'){
    const t = document.createElement('div');
    t.className = 'toast' + (type==='error'?' error':'');
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

document.getElementById('searchAdmin').addEventListener('input', loadProducts);

loadCategories();
loadProducts();
</script>

</body>
</html>