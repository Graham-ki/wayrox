<?php
$shopCurrentPage = 'shop';

$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product — WayronX Shop</title>
<meta name="description" content="View product details, pricing, and availability. Fast delivery across Uganda.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .product-detail-wrap { padding: 40px 0 60px; }

    /* ---------- BREADCRUMB ---------- */
    .breadcrumb {
        font-size: 0.85rem;
        color: var(--ink-mute, #6b7280);
        margin-bottom: 24px;
        line-height: 1.5;
        word-break: break-word;
    }
    .breadcrumb a { color: var(--ink-mute, #6b7280); text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: var(--blue, #2563FF); }
    .breadcrumb span { margin: 0 8px; }
    .breadcrumb .current { color: var(--ink, #0f172a); font-weight: 600; }

    /* ---------- LAYOUT ---------- */
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 48px;
    }
    .product-gallery { position: sticky; top: 100px; height: fit-content; }
    .main-image {
        aspect-ratio: 1 / 1;
        background: #f8fafc;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        overflow: hidden;
        border: 1px solid var(--line, #e5e7eb);
    }
    .main-image img {
        width: 100%; height: 100%;
        object-fit: contain;
        padding: 20px;
        box-sizing: border-box;
    }

    /* ---------- PRODUCT INFO ---------- */
    .product-cat-tag {
        display: inline-block;
        padding: 5px 12px;
        background: #f8fafc;
        color: var(--purple, #7C3AED);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 14px;
        text-decoration: none;
    }
    h1.product-title {
        font-size: clamp(1.4rem, 2.5vw, 2rem);
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 14px;
        letter-spacing: -0.015em;
        margin-top: 0;
        word-break: break-word;
    }
    .product-sku {
        color: var(--ink-mute, #6b7280);
        font-size: 0.85rem;
        margin-bottom: 16px;
        word-break: break-word;
    }
    .product-price-large {
        display: flex;
        align-items: baseline;
        gap: 14px;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    .product-price-large .price {
        font-size: clamp(1.6rem, 4vw, 2rem);
        font-weight: 800;
        color: var(--blue, #2563FF);
    }
    .product-price-large .old {
        font-size: 1.1rem;
        color: var(--ink-mute, #6b7280);
        text-decoration: line-through;
    }
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .stock-badge.in  { background: rgba(16,185,129,0.1); color: var(--success, #10b981); }
    .stock-badge.low { background: rgba(245,158,11,0.1); color: var(--warning, #f59e0b); }
    .stock-badge.out { background: rgba(239,68,68,0.1);  color: var(--danger, #ef4444); }

    .product-description {
        color: var(--ink-mute, #6b7280);
        line-height: 1.7;
        margin-bottom: 16px;
        font-size: 0.98rem;
        word-break: break-word;
    }
    .product-description:last-of-type { margin-bottom: 24px; }

    /* ---------- QUANTITY ---------- */
    .quantity-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 24px 0;
        flex-wrap: wrap;
    }
    .quantity-row label {
        font-weight: 600;
        font-size: 0.95rem;
    }
    .qty-control {
        display: flex;
        align-items: center;
        border: 1.5px solid var(--line, #e5e7eb);
        border-radius: 8px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .qty-control:hover { border-color: var(--blue, #2563FF); }
    .qty-control button {
        background: none;
        border: none;
        width: 40px;
        height: 44px;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        color: var(--ink, #0f172a);
        font-family: inherit;
        transition: background 0.15s;
        -webkit-tap-highlight-color: transparent;
    }
    .qty-control button:hover:not(:disabled) { background: #f8fafc; }
    .qty-control button:disabled { opacity: 0.4; cursor: not-allowed; }
    .qty-control input {
        width: 50px;
        text-align: center;
        border: none;
        font-family: inherit;
        font-weight: 600;
        font-size: 1rem;
        outline: none;
        color: var(--ink, #0f172a);
        -moz-appearance: textfield;
        appearance: textfield;
        background: transparent;
    }
    .qty-control input::-webkit-outer-spin-button,
    .qty-control input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* ---------- ACTION BUTTONS ---------- */
    .btn-cart-large {
        width: 100%;
        padding: 16px;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff;
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 12px;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-cart-large:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(37,99,255,0.3);
    }
    .btn-cart-large:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-outline-large {
        display: block;
        width: 100%;
        padding: 16px;
        background: #fff;
        color: var(--ink, #0f172a);
        border: 1.5px solid var(--line, #e5e7eb);
        border-radius: 12px;
        font-family: inherit;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        box-sizing: border-box;
        transition: all 0.2s;
    }
    .btn-outline-large:hover { border-color: var(--blue, #2563FF); color: var(--blue, #2563FF); }

    /* ---------- FEATURES ---------- */
    .features-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        margin-top: 24px;
    }
    .feature-item {
        display: flex;
        gap: 8px;
        align-items: center;
        font-size: 0.9rem;
        color: var(--ink-mute, #6b7280);
    }
    .feature-item::before {
        content: '✓';
        color: var(--cyan, #22D3EE);
        font-weight: 700;
        flex-shrink: 0;
    }

    /* ---------- RELATED ---------- */
    .related-section {
        padding: 48px 0;
        border-top: 1px solid var(--line, #e5e7eb);
        margin-top: 40px;
    }
    .related-title {
        font-size: clamp(1.2rem, 2.5vw, 1.4rem);
        font-weight: 700;
        margin-bottom: 24px;
        margin-top: 0;
    }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    .related-card {
        padding: 16px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 12px;
        text-align: center;
        transition: all 0.2s;
        background: #fff;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .related-card:hover { border-color: var(--blue, #2563FF); transform: translateY(-3px); }
    .related-image {
        aspect-ratio: 1 / 1;
        background: #f8fafc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .related-image img {
        width: 100%; height: 100%;
        object-fit: contain;
        padding: 8px;
        box-sizing: border-box;
    }
    .related-name {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: var(--ink, #0f172a);
        /* clamp to 2 lines */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .related-price { color: var(--blue, #2563FF); font-weight: 700; }

    /* ---------- STATES ---------- */
    .state-loading {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--ink-mute, #6b7280);
    }
    .state-loading .spinner {
        display: inline-block;
        width: 44px; height: 44px;
        border: 3px solid var(--line, #e5e7eb);
        border-top-color: var(--blue, #2563FF);
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ---------- ACCESSIBILITY ---------- */
    .qty-control button:focus-visible,
    .qty-control input:focus-visible,
    .btn-cart-large:focus-visible,
    .btn-outline-large:focus-visible,
    .related-card:focus-visible {
        outline: 2px solid var(--blue, #2563FF);
        outline-offset: 2px;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 900px) {
        .product-detail { grid-template-columns: 1fr; gap: 28px; }
        .product-gallery { position: static; }
        .main-image { font-size: 6rem; }
        .related-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 14px; }
    }
    @media (max-width: 640px) {
        .product-detail-wrap { padding: 24px 0 40px; }
        .main-image { font-size: 5rem; border-radius: 16px; }
        .main-image img { padding: 14px; }
        .features-list { grid-template-columns: 1fr; gap: 10px; padding: 16px; }
        .related-section { padding: 32px 0; margin-top: 28px; }
        .related-card { padding: 12px; }
        .related-image { font-size: 2rem; }
    }
    @media (max-width: 420px) {
        .related-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/shop-header.php'; ?>

<div class="container product-detail-wrap">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Shop</a>
        <span aria-hidden="true">/</span>
        <span class="current" id="breadcrumbName">Loading…</span>
    </nav>

    <div id="productDetail" class="product-detail" aria-live="polite" aria-busy="true">
        <div class="state-loading">
            <div class="spinner" role="status" aria-label="Loading product"></div>
            <p style="margin-top:14px;">Loading product…</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/shop-footer.php'; ?>

<script>
const API  = '../api/shop-products.php';
const slug = new URLSearchParams(window.location.search).get('slug');

// ----- Helpers -----
function formatPrice(n) { return 'UGX ' + Number(n || 0).toLocaleString('en-US'); }

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    if (!res.ok) {
        const t = await res.text().catch(() => '');
        console.error('HTTP', res.status, t.substring(0, 300));
        throw new Error(`Request failed (${res.status})`);
    }
    const text = await res.text();
    if (text.trim().startsWith('<')) {
        console.error('HTML response:', text.substring(0, 500));
        throw new Error('Server returned unexpected response');
    }
    try { return JSON.parse(text); }
    catch { throw new Error('Invalid JSON response'); }
}

function setBusy(isBusy) {
    const el = document.getElementById('productDetail');
    if (el) el.setAttribute('aria-busy', isBusy ? 'true' : 'false');
}

// ----- Render states -----
function renderNotFound() {
    document.getElementById('productDetail').innerHTML = `
        <div class="state-loading" style="grid-column:1/-1;">
            <div style="font-size:4rem;line-height:1;margin-bottom:14px;" aria-hidden="true">❌</div>
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:10px;color:var(--ink, #0f172a);">Product not found</h2>
            <p style="max-width:40ch;margin:0 auto 20px;">The product you're looking for doesn't exist or has been removed.</p>
            <a href="index.php" class="btn-primary">Back to Shop</a>
        </div>
    `;
}

function renderError(msg) {
    document.getElementById('productDetail').innerHTML = `
        <div class="state-loading" style="grid-column:1/-1;">
            <div style="font-size:4rem;line-height:1;margin-bottom:14px;" aria-hidden="true">⚠️</div>
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:10px;color:var(--ink, #0f172a);">Couldn't load product</h2>
            <p style="max-width:40ch;margin:0 auto 20px;">${escapeHtml(msg)}</p>
            <button class="btn-primary" id="retryBtn" style="border:none;cursor:pointer;font-family:inherit;">🔄 Retry</button>
        </div>
    `;
    document.getElementById('retryBtn')?.addEventListener('click', loadProduct);
}

// ----- Load -----
async function loadProduct() {
    setBusy(true);

    if (!slug) {
        renderNotFound();
        setBusy(false);
        return;
    }

    try {
        const data = await fetchJSON(`${API}?action=single&slug=${encodeURIComponent(slug)}`);

        if (!data.success || !data.data) {
            renderNotFound();
            return;
        }

        renderProduct(data.data);
    } catch (e) {
        renderError(e.message);
    } finally {
        setBusy(false);
    }
}

// ----- Render product -----
function renderProduct(p) {
    // Update page title + breadcrumb
    document.getElementById('breadcrumbName').textContent = p.name || 'Product';
    document.title = (p.name || 'Product') + ' — WayronX Shop';

    const price      = formatPrice(p.price);
    const finalPrice = p.sale_price ? formatPrice(p.sale_price) : price;
    const oldPrice   = p.sale_price ? `<span class="old">${price}</span>` : '';

    const stockQty = Number(p.stock_quantity) || 0;
    const isOut    = stockQty <= 0;
    const isLow    = stockQty > 0 && stockQty <= 5;
    const stockClass = isOut ? 'out' : (isLow ? 'low' : 'in');
    const stockText  = isOut
        ? '<span aria-hidden="true">❌</span> Out of Stock'
        : (isLow
            ? `<span aria-hidden="true">⚠️</span> Only ${stockQty} left`
            : `<span aria-hidden="true">✅</span> In Stock (${stockQty})`);

    const imgSrc = p.image ? `../${p.image}` : '';
    const img = imgSrc
        ? `<img src="${escapeHtml(imgSrc)}" alt="${escapeHtml(p.name)}" onerror="this.style.display='none';this.parentElement.textContent='💻'">`
        : '<span aria-hidden="true">💻</span>';

    const skuLine = (p.sku || p.brand)
        ? `<div class="product-sku">${p.sku ? 'SKU: ' + escapeHtml(p.sku) : ''}${p.sku && p.brand ? ' • ' : ''}${p.brand ? escapeHtml(p.brand) : ''}</div>`
        : '';

    const catTag = p.category_name
        ? `<span class="product-cat-tag">${escapeHtml(p.category_name)}</span>`
        : '';

    const shortDesc = p.short_description
        ? `<p class="product-description">${escapeHtml(p.short_description)}</p>` : '';
    const longDesc  = p.description
        ? `<p class="product-description">${escapeHtml(p.description)}</p>` : '';

    const maxQty = isOut ? 1 : stockQty;

    document.getElementById('productDetail').innerHTML = `
        <div class="product-gallery">
            <div class="main-image">${img}</div>
        </div>
        <div>
            ${catTag}
            <h1 class="product-title">${escapeHtml(p.name)}</h1>
            ${skuLine}
            <div class="product-price-large">
                <span class="price">${finalPrice}</span>
                ${oldPrice}
            </div>
            <div class="stock-badge ${stockClass}">${stockText}</div>
            ${shortDesc}
            ${longDesc}

            <div class="quantity-row">
                <label for="qty">Quantity:</label>
                <div class="qty-control" role="group" aria-label="Quantity">
                    <button type="button" id="qtyDec" aria-label="Decrease quantity" ${isOut ? 'disabled' : ''}>−</button>
                    <input
                        type="number"
                        id="qty"
                        value="1"
                        min="1"
                        max="${maxQty}"
                        aria-label="Quantity"
                        ${isOut ? 'disabled' : ''}
                    >
                    <button type="button" id="qtyInc" aria-label="Increase quantity" ${isOut ? 'disabled' : ''}>+</button>
                </div>
            </div>

            <button class="btn-cart-large" id="addToCartBtn" ${isOut ? 'disabled' : ''}>
                ${isOut ? 'Out of Stock' : '<span aria-hidden="true">🛒</span> Add to Cart'}
            </button>
            <a href="cart.php" class="btn-outline-large">View Cart &amp; Checkout</a>

            <div class="features-list">
                <div class="feature-item">Genuine product</div>
                <div class="feature-item">Warranty included</div>
                <div class="feature-item">Fast delivery</div>
                <div class="feature-item">Support available</div>
            </div>
        </div>
    `;

    // Wire up quantity controls
    const qtyInput = document.getElementById('qty');
    document.getElementById('qtyDec')?.addEventListener('click', () => adjustQty(-1));
    document.getElementById('qtyInc')?.addEventListener('click', () => adjustQty(1));
    qtyInput?.addEventListener('change', () => {
        let v = parseInt(qtyInput.value, 10);
        if (isNaN(v) || v < 1) v = 1;
        if (v > maxQty) v = maxQty;
        qtyInput.value = v;
    });
    // Block non-numeric keys
    qtyInput?.addEventListener('keydown', e => {
        if (['e', 'E', '+', '-', '.'].includes(e.key)) e.preventDefault();
    });

    // Wire up add to cart
    document.getElementById('addToCartBtn')?.addEventListener('click', () => {
        addToCart(p.id);
    });

    // Render related products
    if (Array.isArray(p.related) && p.related.length > 0) {
        renderRelated(p.related);
    }
}

function renderRelated(related) {
    const div = document.createElement('div');
    div.className = 'related-section';
    div.innerHTML = `
        <h2 class="related-title">You may also like</h2>
        <div class="related-grid">
            ${related.map(r => {
                const rImgSrc = r.image ? `../${r.image}` : '';
                const rImg = rImgSrc
                    ? `<img src="${escapeHtml(rImgSrc)}" alt="${escapeHtml(r.name)}" loading="lazy" onerror="this.style.display='none';this.parentElement.textContent='💻'">`
                    : '<span aria-hidden="true">💻</span>';
                return `
                    <a href="product.php?slug=${encodeURIComponent(r.slug)}" class="related-card">
                        <div class="related-image">${rImg}</div>
                        <div class="related-name">${escapeHtml(r.name)}</div>
                        <div class="related-price">${formatPrice(r.sale_price || r.price)}</div>
                    </a>
                `;
            }).join('')}
        </div>
    `;
    document.querySelector('.product-detail-wrap')?.appendChild(div);
}

function adjustQty(delta) {
    const input = document.getElementById('qty');
    if (!input || input.disabled) return;

    const max = parseInt(input.max, 10) || 1;
    let val = parseInt(input.value, 10) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}

// ----- Cart -----
async function addToCart(productId) {
    const qtyInput = document.getElementById('qty');
    const qty = parseInt(qtyInput?.value, 10) || 1;
    const btn = document.getElementById('addToCartBtn');

    if (btn) btn.disabled = true;

    try {
        const data = await fetchJSON('../api/shop-cart.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', product_id: productId, quantity: qty })
        });
        if (data.success) {
            updateCartCount(data.cart?.count ?? 0);
            showToast(`✓ Added ${qty} to cart`);
        } else {
            showToast(data.message || 'Failed to add', 'error');
        }
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        if (btn) btn.disabled = false;
    }
}

async function loadCartCount() {
    try {
        const data = await fetchJSON('../api/shop-cart.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get' })
        });
        if (data.success) updateCartCount(data.cart?.count ?? 0);
    } catch (e) { /* silent */ }
}

function updateCartCount(n) {
    document.querySelectorAll('#cartCount, #headerCartCount, [data-cart-count]').forEach(el => {
        if (el) el.textContent = n;
    });
}

// ----- Toast -----
function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : '');
    t.setAttribute('role', 'status');
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

// ----- Init -----
loadProduct();
loadCartCount();
</script>

</body>
</html>