<?php
session_start();
$shopCurrentPage = 'cart';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Cart — WayronX Shop</title>
<meta name="description" content="Review your cart and proceed to secure checkout. Fast delivery across Uganda.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .cart-wrapper { padding: 40px 0 60px; }
    .cart-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 32px;
        align-items: start;
    }

    /* ---------- CART PANEL ---------- */
    .cart-panel {
        background: #fff;
        border-radius: 20px;
        border: 1px solid var(--line, #e5e7eb);
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(11,16,38,0.04);
    }
    .cart-panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--line, #e5e7eb);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        background: linear-gradient(180deg, #fff, #fafbff);
    }
    .cart-panel-header h2 {
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin: 0;
    }
    .cart-panel-header .count-pill {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        white-space: nowrap;
    }
    .cart-clear-btn {
        background: none;
        border: none;
        color: var(--ink-mute, #6b7280);
        font-family: inherit;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .cart-clear-btn:hover { background: rgba(239,68,68,0.08); color: var(--danger, #ef4444); }

    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 20px;
        padding: 24px;
        border-bottom: 1px solid var(--line, #e5e7eb);
        align-items: center;
        transition: background 0.25s;
    }
    .cart-item:last-child { border-bottom: none; }
    .cart-item:hover { background: linear-gradient(90deg, rgba(37,99,255,0.02), transparent); }

    .cart-item-image {
        width: 100px; height: 100px;
        background: var(--grad-soft, #f0f4ff);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        overflow: hidden;
        flex-shrink: 0;
        text-decoration: none;
    }
    .cart-item-image img {
        width: 100%; height: 100%;
        object-fit: contain;
        padding: 8px;
        box-sizing: border-box;
    }

    .cart-item-info { min-width: 0; }
    .cart-item-cat {
        font-size: 0.72rem;
        color: var(--purple, #7C3AED);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }
    .cart-item-name {
        font-size: 1.02rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--ink, #0f172a);
        line-height: 1.35;
        text-decoration: none;
        display: block;
        transition: color 0.2s;
    }
    .cart-item-name:hover { color: var(--blue, #2563FF); }
    .cart-item-price {
        color: var(--ink-mute, #6b7280);
        font-size: 0.9rem;
    }
    .cart-item-price .unit { color: var(--blue, #2563FF); font-weight: 700; }
    .stock-warning {
        color: var(--warning, #f59e0b);
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 4px;
    }

    .cart-item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
        min-width: 130px;
    }
    .cart-item-total {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--ink, #0f172a);
        white-space: nowrap;
    }
    .qty-control {
        display: inline-flex;
        align-items: center;
        background: #fff;
        border: 1.5px solid var(--line, #e5e7eb);
        border-radius: 10px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    .qty-control:hover { border-color: var(--blue, #2563FF); }
    .qty-control button {
        background: none;
        border: none;
        width: 34px;
        height: 38px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        color: var(--ink, #0f172a);
        transition: all 0.15s;
        font-family: inherit;
        -webkit-tap-highlight-color: transparent;
    }
    .qty-control button:hover:not(:disabled) { background: rgba(37,99,255,0.08); color: var(--blue, #2563FF); }
    .qty-control button:disabled { opacity: 0.3; cursor: not-allowed; }
    .qty-control input {
        width: 44px;
        text-align: center;
        border: none;
        font-family: inherit;
        font-weight: 700;
        font-size: 0.95rem;
        outline: none;
        background: transparent;
        color: var(--ink, #0f172a);
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .qty-control input::-webkit-outer-spin-button,
    .qty-control input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .btn-remove {
        background: none;
        border: none;
        color: var(--ink-mute, #6b7280);
        cursor: pointer;
        font-family: inherit;
        font-weight: 500;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-remove:hover { color: var(--danger, #ef4444); background: rgba(239,68,68,0.06); }

    /* ---------- SUMMARY ---------- */
    .cart-summary {
        background: #fff;
        border-radius: 20px;
        border: 1px solid var(--line, #e5e7eb);
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(11,16,38,0.04);
        position: sticky;
        top: 90px;
    }
    .summary-header {
        background: linear-gradient(135deg, #050816, #0B1026);
        color: #fff;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    .summary-header::before {
        content: '';
        position: absolute;
        top: -50%; right: -30%;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(37,99,255,0.3), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .summary-header h2 {
        position: relative;
        z-index: 1;
        font-size: 1.15rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    .summary-body { padding: 24px; }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        font-size: 0.95rem;
        gap: 12px;
    }
    .summary-row .label { color: var(--ink-mute, #6b7280); }
    .summary-row .value { font-weight: 600; color: var(--ink, #0f172a); text-align: right; }
    .summary-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--line, #e5e7eb), transparent);
        margin: 18px 0;
    }
    .summary-row.total {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--ink, #0f172a);
        padding-top: 4px;
    }
    .summary-row.total .value {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 1.5rem;
    }
    .btn-checkout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
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
        margin-top: 20px;
        transition: all 0.25s;
        text-decoration: none;
        box-sizing: border-box;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(37,99,255,0.35);
    }
    .btn-continue {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        background: transparent;
        color: var(--ink-mute, #6b7280);
        border: 1.5px solid var(--line, #e5e7eb);
        border-radius: 10px;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        margin-top: 10px;
        transition: all 0.2s;
        text-decoration: none;
        box-sizing: border-box;
    }
    .btn-continue:hover { border-color: var(--blue, #2563FF); color: var(--blue, #2563FF); background: rgba(37,99,255,0.03); }

    .trust-row {
        display: flex;
        justify-content: space-around;
        gap: 8px;
        padding: 16px 24px;
        margin-top: 0;
        border-top: 1px solid var(--line, #e5e7eb);
    }
    .trust-badge {
        flex: 1;
        text-align: center;
        font-size: 0.72rem;
        color: var(--ink-mute, #6b7280);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }
    .trust-badge .icon { font-size: 1.2rem; }

    /* ---------- SKELETON ---------- */
    .skeleton-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 20px;
        padding: 24px;
        border-bottom: 1px solid var(--line, #e5e7eb);
        align-items: center;
    }
    .skeleton-item:last-child { border-bottom: none; }
    .skeleton {
        background: linear-gradient(90deg, #f0f2f7 25%, #e6e9f2 50%, #f0f2f7 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s infinite;
        border-radius: 8px;
    }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    /* ---------- ACCESSIBILITY ---------- */
    .cart-clear-btn:focus-visible,
    .qty-control button:focus-visible,
    .qty-control input:focus-visible,
    .btn-remove:focus-visible,
    .btn-checkout:focus-visible,
    .btn-continue:focus-visible {
        outline: 2px solid var(--blue, #2563FF);
        outline-offset: 2px;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 900px) {
        .cart-layout { grid-template-columns: 1fr; gap: 24px; }
        .cart-summary { position: static; }
    }
    @media (max-width: 640px) {
        .cart-wrapper { padding: 24px 0 40px; }
        .cart-item {
            grid-template-columns: 80px 1fr;
            grid-template-areas:
                "img info"
                "actions actions";
            gap: 14px;
            padding: 18px;
        }
        .cart-item-image { grid-area: img; width: 80px; height: 80px; font-size: 2rem; }
        .cart-item-info { grid-area: info; }
        .cart-item-actions {
            grid-area: actions;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            min-width: 0;
            gap: 12px;
        }
        .cart-item-total { order: 3; }
        .qty-control { order: 1; }
        .btn-remove { order: 2; }

        .cart-panel-header { padding: 16px 18px; }
        .cart-panel-header h2 { font-size: 1rem; }
        .summary-body { padding: 18px; }
        .summary-header { padding: 18px; }
        .trust-row { padding: 14px 18px; }
    }
    @media (max-width: 400px) {
        .cart-item-actions {
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        .cart-item-total {
            width: 100%;
            text-align: left;
            order: 4;
            margin-top: 4px;
        }
    }
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/shop-header.php'; ?>

<section class="page-hero">
    <div class="container page-hero-content">
        <h1>🛒 Your <span class="accent">Shopping Cart</span></h1>
        <div class="page-hero-subtitle" aria-label="Checkout progress">
            <span class="step-badge active" aria-current="step">1. Cart</span>
            <span aria-hidden="true">→</span>
            <span class="step-badge">2. Checkout</span>
            <span aria-hidden="true">→</span>
            <span class="step-badge">3. Confirmation</span>
        </div>
    </div>
</section>

<div class="cart-wrapper">
    <div class="container">
        <div id="cartContent" aria-live="polite" aria-busy="true">
            <div class="cart-layout">
                <div class="cart-panel">
                    <div class="cart-panel-header">
                        <h2>Loading cart…</h2>
                    </div>
                    <div class="skeleton-item">
                        <div class="skeleton" style="width:100px;height:100px;border-radius:14px;"></div>
                        <div>
                            <div class="skeleton" style="width:60%;height:14px;margin-bottom:10px;"></div>
                            <div class="skeleton" style="width:40%;height:12px;"></div>
                        </div>
                        <div class="skeleton" style="width:80px;height:20px;"></div>
                    </div>
                </div>
                <div class="cart-summary">
                    <div class="summary-header"><h2>Order Summary</h2></div>
                    <div class="summary-body">
                        <div class="skeleton" style="width:100%;height:16px;margin-bottom:14px;"></div>
                        <div class="skeleton" style="width:80%;height:16px;margin-bottom:14px;"></div>
                        <div class="skeleton" style="width:100%;height:52px;margin-top:20px;border-radius:12px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/shop-footer.php'; ?>

<script>
const API = '../api/shop-cart.php';

// ----- Helpers -----
function formatPrice(n) { return 'UGX ' + Number(n).toLocaleString('en-US'); }

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
    const el = document.getElementById('cartContent');
    if (el) el.setAttribute('aria-busy', isBusy ? 'true' : 'false');
}

// ----- Load cart -----
async function loadCart() {
    setBusy(true);
    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get' })
        });

        if (!data.success || !data.cart || !Array.isArray(data.cart.items) || data.cart.items.length === 0) {
            renderEmpty();
            updateCartCount(0);
            return;
        }
        renderCart(data.cart);
        updateCartCount(data.cart.count);
    } catch (e) {
        renderError(e.message);
    } finally {
        setBusy(false);
    }
}

// ----- Render states -----
function renderEmpty() {
    document.getElementById('cartContent').innerHTML = `
        <div class="empty-state">
            <div class="icon" aria-hidden="true">🛒</div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added anything yet. Explore our shop and find something you'll love.</p>
            <a href="index.php" class="btn-primary">Browse Products →</a>
        </div>
    `;
}

function renderError(msg) {
    document.getElementById('cartContent').innerHTML = `
        <div class="empty-state">
            <div class="icon" aria-hidden="true">⚠️</div>
            <h2>Couldn't load your cart</h2>
            <p>${escapeHtml(msg)}</p>
            <button class="btn-primary" id="retryBtn" style="border:none;cursor:pointer;font-family:inherit;">🔄 Try Again</button>
        </div>
    `;
    document.getElementById('retryBtn')?.addEventListener('click', loadCart);
}

function renderCart(cart) {
    const itemsHtml = cart.items.map(renderItem).join('');
    const itemWord = cart.count === 1 ? 'item' : 'items';

    document.getElementById('cartContent').innerHTML = `
        <div class="cart-layout">
            <div class="cart-panel">
                <div class="cart-panel-header">
                    <h2>Cart Items <span class="count-pill">${cart.count} ${itemWord}</span></h2>
                    <button class="cart-clear-btn" id="clearCartBtn">Clear all</button>
                </div>
                ${itemsHtml}
            </div>

            <div class="cart-summary">
                <div class="summary-header"><h2>💳 Order Summary</h2></div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span class="label">Subtotal (${cart.count} ${itemWord})</span>
                        <span class="value">${formatPrice(cart.subtotal)}</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">🚚 Delivery</span>
                        <span class="value" style="color:var(--success, #10b981);">Calculated at checkout</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="value">${formatPrice(cart.subtotal)}</span>
                    </div>
                    <a href="checkout.php" class="btn-checkout">Proceed to Checkout <span aria-hidden="true">→</span></a>
                    <a href="index.php" class="btn-continue"><span aria-hidden="true">←</span> Continue Shopping</a>
                </div>
                <div class="trust-row">
                    <div class="trust-badge"><span class="icon" aria-hidden="true">🔒</span><span>Secure</span></div>
                    <div class="trust-badge"><span class="icon" aria-hidden="true">🛡️</span><span>Warranty</span></div>
                    <div class="trust-badge"><span class="icon" aria-hidden="true">🚚</span><span>Fast Ship</span></div>
                </div>
            </div>
        </div>
    `;

    // Wire up clear button (was inline onclick)
    document.getElementById('clearCartBtn')?.addEventListener('click', clearCart);
}

function renderItem(item) {
    const imgSrc = item.image ? `../${item.image}` : '';
    const img = imgSrc
        ? `<img src="${escapeHtml(imgSrc)}" alt="${escapeHtml(item.name)}" loading="lazy" onerror="this.style.display='none';this.parentElement.textContent='💻'">`
        : '<span aria-hidden="true">💻</span>';

    const lowStock = item.stock_quantity > 0 && item.stock_quantity <= 3;
    const stockNote = lowStock
        ? `<div class="stock-warning" role="note">⚠️ Only ${item.stock_quantity} left</div>`
        : '';

    const productUrl = `product.php?slug=${encodeURIComponent(item.slug || '')}`;
    const maxQty = Number(item.stock_quantity) || 99;

    return `
        <div class="cart-item" data-id="${item.id}">
            <a href="${productUrl}" class="cart-item-image" aria-label="${escapeHtml(item.name)}">${img}</a>
            <div class="cart-item-info">
                <div class="cart-item-cat">${escapeHtml(item.category_name || 'Product')}</div>
                <a href="${productUrl}" class="cart-item-name">${escapeHtml(item.name)}</a>
                <div class="cart-item-price"><span class="unit">${formatPrice(item.price)}</span> each</div>
                ${stockNote}
            </div>
            <div class="cart-item-actions">
                <div class="qty-control" role="group" aria-label="Quantity for ${escapeHtml(item.name)}">
                    <button type="button" data-action="dec" data-id="${item.id}" aria-label="Decrease quantity" ${item.quantity <= 1 ? 'disabled' : ''}>−</button>
                    <input
                        type="number"
                        value="${item.quantity}"
                        min="1"
                        max="${maxQty}"
                        aria-label="Quantity"
                        data-action="input"
                        data-id="${item.id}"
                    >
                    <button type="button" data-action="inc" data-id="${item.id}" aria-label="Increase quantity" ${item.quantity >= maxQty ? 'disabled' : ''}>+</button>
                </div>
                <div class="cart-item-total">${formatPrice(item.line_total)}</div>
                <button class="btn-remove" data-action="remove" data-id="${item.id}">
                    <span aria-hidden="true">🗑️</span> Remove
                </button>
            </div>
        </div>
    `;
}

// ----- Actions -----
async function updateQty(productId, qty) {
    qty = parseInt(qty, 10);
    if (isNaN(qty) || qty < 1) return;
    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'update', product_id: productId, quantity: qty })
        });
        if (data.success) loadCart();
        else showToast(data.message || 'Failed to update', 'error');
    } catch (e) { showToast(e.message, 'error'); }
}

async function removeItem(productId) {
    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'remove', product_id: productId })
        });
        if (data.success) {
            loadCart();
            showToast('Item removed', 'info');
        } else {
            showToast(data.message || 'Failed to remove', 'error');
        }
    } catch (e) { showToast(e.message, 'error'); }
}

async function clearCart() {
    if (!confirm('Remove all items from your cart?')) return;
    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'clear' })
        });
        if (data.success) {
            loadCart();
            showToast('Cart cleared', 'info');
        } else {
            showToast(data.message || 'Failed to clear', 'error');
        }
    } catch (e) { showToast(e.message, 'error'); }
}

// ----- Cart count in header -----
function updateCartCount(n) {
    document.querySelectorAll('#cartCount, #headerCartCount, [data-cart-count]').forEach(el => {
        if (el) el.textContent = n;
    });
}

// ----- Toast -----
function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : (type === 'info' ? ' info' : ''));
    t.setAttribute('role', 'status');
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

// ----- Event delegation (replaces all inline onclick) -----
document.getElementById('cartContent').addEventListener('click', e => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;

    const action = btn.dataset.action;
    const id = Number(btn.dataset.id);
    if (!id) return;

    if (action === 'dec') {
        const input = btn.parentElement.querySelector('input');
        const next = parseInt(input.value, 10) - 1;
        if (next >= 1) updateQty(id, next);
    } else if (action === 'inc') {
        const input = btn.parentElement.querySelector('input');
        const next = parseInt(input.value, 10) + 1;
        const max = parseInt(input.max, 10) || 99;
        if (next <= max) updateQty(id, next);
    } else if (action === 'remove') {
        removeItem(id);
    }
});

// Quantity input change (event delegation)
document.getElementById('cartContent').addEventListener('change', e => {
    const input = e.target.closest('input[data-action="input"]');
    if (!input) return;

    const id = Number(input.dataset.id);
    let qty = parseInt(input.value, 10);
    const max = parseInt(input.max, 10) || 99;

    if (isNaN(qty) || qty < 1) qty = 1;
    if (qty > max) qty = max;

    input.value = qty; // reflect clamped value
    updateQty(id, qty);
});

// Block non-numeric keys in qty input
document.getElementById('cartContent').addEventListener('keydown', e => {
    if (e.target.matches('input[data-action="input"]')) {
        if (['e', 'E', '+', '-', '.'].includes(e.key)) e.preventDefault();
    }
});

// ----- Init -----
loadCart();
</script>

</body>
</html>