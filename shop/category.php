<?php
$shopCurrentPage = 'shop';

$categorySlug = $_GET['slug'] ?? '';
if (!$categorySlug) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Category — WayronX Shop</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .category-page-wrap { padding: 40px 0 60px; }
    .category-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }
    .category-icon {
        width: 64px; height: 64px;
        border-radius: 16px;
        background: var(--grad);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
    }
    .category-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .category-header p {
        color: var(--ink-mute);
        font-size: 0.95rem;
    }
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
        padding: 24px 0;
    }
    .product-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.1);
        border-color: transparent;
    }
    .product-image {
        aspect-ratio: 1;
        background: var(--light);
        display: flex; align-items: center; justify-content: center;
        font-size: 3.5rem;
        overflow: hidden;
        text-decoration: none;
    }
    .product-image img { width: 100%; height: 100%; object-fit: cover; }
    .product-info { padding: 16px; flex: 1; display: flex; flex-direction: column; }
    .product-name { font-size: 1rem; font-weight: 700; margin-bottom: 6px; flex: 1; color: var(--ink); }
    .product-name:hover { color: var(--blue); }
    .product-price {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--blue);
        margin: 10px 0 14px;
    }
    .btn-add {
        width: 100%; padding: 10px;
        background: var(--grad); color: #fff;
        border: none; border-radius: 8px;
        font-family: inherit; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
        font-size: 0.9rem;
    }
    .btn-add:hover:not(:disabled) { transform: translateY(-2px); }
    .btn-add:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/shop-header.php'; ?>

<div class="container category-page-wrap">
    <div class="category-header" id="categoryHeader">
        <div class="category-icon">📦</div>
        <div>
            <h1>Loading...</h1>
            <p>Please wait...</p>
        </div>
    </div>

    <div class="product-grid" id="productGrid">
        <div style="grid-column:1/-1;text-align:center;padding:60px;">
            <div style="display:inline-block;width:40px;height:40px;border:3px solid var(--line);border-top-color:var(--blue);border-radius:50%;animation:spin 0.9s linear infinite;"></div>
        </div>
    </div>
</div>

<style>@keyframes spin{to{transform:rotate(360deg);}}</style>

<?php require_once __DIR__ . '/includes/shop-footer.php'; ?>

<script>
const API = '../api/shop-products.php';
const categorySlug = <?php echo json_encode($categorySlug); ?>;

function formatPrice(n) { return 'UGX ' + Number(n || 0).toLocaleString('en-US'); }

async function fetchJSON(url) {
    const res = await fetch(url);
    const text = await res.text();
    if (text.trim().startsWith('<')) throw new Error('Server error');
    return JSON.parse(text);
}

async function loadCategory() {
    try {
        const [catsRes, productsRes] = await Promise.all([
            fetchJSON(`${API}?action=categories`),
            fetchJSON(`${API}?action=list&category=${encodeURIComponent(categorySlug)}`)
        ]);

        // Find category
        const cat = catsRes.success ? catsRes.data.find(c => c.slug === categorySlug) : null;
        if (cat) {
            document.getElementById('categoryHeader').innerHTML = `
                <div class="category-icon">${cat.icon || '📦'}</div>
                <div>
                    <h1>${cat.name}</h1>
                    <p>${cat.description || 'Browse our ' + cat.name.toLowerCase()}</p>
                </div>
            `;
            document.title = cat.name + ' — WayronX Shop';
        }

        if (!productsRes.success || !productsRes.data || productsRes.data.length === 0) {
            document.getElementById('productGrid').innerHTML = `
                <div class="empty-state" style="grid-column:1/-1;">
                    <div class="icon">📦</div>
                    <h2>No products here yet</h2>
                    <p>Check back soon or browse all products.</p>
                    <a href="index.php" class="btn-primary">Browse All →</a>
                </div>
            `;
            return;
        }

        document.getElementById('productGrid').innerHTML = productsRes.data.map(p => {
            const isOut = p.stock_quantity <= 0;
            const img = p.image ? `<img src="../${p.image}" alt="${p.name}" loading="lazy" onerror="this.parentElement.innerHTML='💻'">` : '💻';
            return `
                <div class="product-card">
                    <a href="product.php?slug=${p.slug}" class="product-image">${img}</a>
                    <div class="product-info">
                        <a href="product.php?slug=${p.slug}"><div class="product-name">${p.name}</div></a>
                        <div class="product-price">${formatPrice(p.sale_price || p.price)}</div>
                        <button class="btn-add" onclick="addToCart(${p.id})" ${isOut ? 'disabled' : ''}>
                            ${isOut ? 'Out of Stock' : '🛒 Add to Cart'}
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    } catch (e) {
        document.getElementById('productGrid').innerHTML = `
            <div class="empty-state" style="grid-column:1/-1;">
                <div class="icon">⚠️</div>
                <h2>Couldn't load category</h2>
                <p>${e.message}</p>
                <a href="index.php" class="btn-primary">Back to Shop</a>
            </div>
        `;
    }
}

async function addToCart(productId) {
    try {
        const data = await fetchJSON('../api/shop-cart.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'add', product_id: productId, quantity: 1 })
        });
        if (data.success) {
            document.querySelectorAll('#cartCount, #headerCartCount').forEach(el => el.textContent = data.cart.count);
            showToast('✓ Added to cart');
        }
    } catch (e) { showToast(e.message, 'error'); }
}

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : '');
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

loadCategory();
</script>

</body>
</html>