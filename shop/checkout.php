<?php
session_start();
$shopCurrentPage = 'checkout';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout — WayronX Shop</title>
<meta name="description" content="Securely complete your order. Cash on delivery, Mobile Money, and bank transfer accepted.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .checkout-wrapper { padding: 40px 0 60px; }
    .checkout-layout { display: grid; grid-template-columns: 1fr 400px; gap: 32px; align-items: start; }

    /* ---------- FORM CARD ---------- */
    .form-card {
        background: #fff; border-radius: 20px;
        border: 1px solid var(--line, #e5e7eb); padding: 28px;
        box-shadow: 0 4px 24px rgba(11,16,38,0.04);
        margin-bottom: 20px;
    }
    .form-card h2 {
        font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;
        padding-bottom: 14px; border-bottom: 1px solid var(--line, #e5e7eb);
        display: flex; align-items: center; gap: 10px;
        margin-top: 0;
    }
    .form-card h2 .step-num {
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED)); color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
    }

    .form-group { margin-bottom: 18px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 6px; }
    .form-group label .req { color: var(--danger, #ef4444); }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%; padding: 12px 14px;
        border: 1.5px solid var(--line, #e5e7eb); border-radius: 10px;
        font-family: inherit; font-size: 0.95rem;
        background: #fff; color: var(--ink, #0f172a);
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none; border-color: var(--blue, #2563FF);
        box-shadow: 0 0 0 3px rgba(37,99,255,0.1);
    }
    .form-group input:invalid:not(:placeholder-shown):not(:focus),
    .form-group textarea:invalid:not(:placeholder-shown):not(:focus) {
        border-color: var(--danger, #ef4444);
    }
    .form-group textarea { resize: vertical; min-height: 80px; }

    /* ---------- REGION FIELD ---------- */
    .region-field { position: relative; }
    .region-field select { padding-right: 44px; appearance: none; -webkit-appearance: none; cursor: pointer; }
    .region-field::after {
        content: '▼'; position: absolute; right: 16px; top: 50%;
        transform: translateY(-50%); font-size: 0.7rem;
        color: var(--ink-mute, #6b7280); pointer-events: none;
    }
    .region-info {
        margin-top: 10px; font-size: 0.85rem;
        display: none; align-items: flex-start; gap: 10px;
        padding: 12px 14px; background: #f8fafc; border-radius: 10px;
        border-left: 3px solid var(--blue, #2563FF);
        animation: slideDown 0.25s ease;
        line-height: 1.5;
    }
    .region-info.visible { display: flex; }
    .region-info.free { background: rgba(16,185,129,0.06); border-left-color: var(--success, #10b981); }
    .region-info .info-body { flex: 1; }
    .region-info .info-body strong { color: var(--blue, #2563FF); }
    .region-info.free .info-body strong { color: var(--success, #10b981); }
    .region-info .info-days {
        display: inline-block; padding: 2px 8px;
        background: rgba(37,99,255,0.1); color: var(--blue, #2563FF);
        border-radius: 6px; font-size: 0.75rem; font-weight: 700;
        margin-left: 6px;
        white-space: nowrap;
    }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }

    /* ---------- PAYMENT ---------- */
    .payment-options { display: grid; gap: 12px; }
    .payment-option {
        display: flex; align-items: center; gap: 14px;
        padding: 16px; border: 1.5px solid var(--line, #e5e7eb);
        border-radius: 12px; cursor: pointer; transition: all 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    .payment-option:hover { border-color: var(--blue, #2563FF); background: rgba(37,99,255,0.02); }
    .payment-option input {
        width: auto; margin: 0; padding: 0;
        accent-color: var(--blue, #2563FF);
        flex-shrink: 0;
        width: 18px; height: 18px;
    }
    .payment-option.selected { border-color: var(--blue, #2563FF); background: rgba(37,99,255,0.04); }
    .payment-option-info { flex: 1; min-width: 0; }
    .payment-option-info strong { display: block; font-size: 0.95rem; margin-bottom: 2px; }
    .payment-option-info span { font-size: 0.85rem; color: var(--ink-mute, #6b7280); }
    .payment-icon { font-size: 1.5rem; flex-shrink: 0; }

    /* ---------- ORDER SUMMARY ---------- */
    .order-summary {
        background: #fff; border-radius: 20px;
        border: 1px solid var(--line, #e5e7eb); overflow: hidden;
        box-shadow: 0 4px 24px rgba(11,16,38,0.04);
        position: sticky; top: 90px;
    }
    .summary-header {
        background: linear-gradient(135deg, #050816, #0B1026);
        color: #fff; padding: 22px 24px; position: relative; overflow: hidden;
    }
    .summary-header::before {
        content: ''; position: absolute; top: -50%; right: -30%;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(37,99,255,0.3), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .summary-header h2 { position: relative; z-index: 1; font-size: 1.1rem; font-weight: 700; margin: 0; }
    .summary-body { padding: 22px 24px; }

    .summary-items {
        max-height: 260px; overflow-y: auto;
        margin-bottom: 16px; padding-bottom: 16px;
        border-bottom: 1px solid var(--line, #e5e7eb);
        scrollbar-width: thin;
    }
    .summary-items::-webkit-scrollbar { width: 6px; }
    .summary-items::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
    .summary-item {
        display: flex; justify-content: space-between; gap: 12px;
        padding: 8px 0; font-size: 0.9rem;
        align-items: flex-start;
    }
    .summary-item .name { flex: 1; min-width: 0; word-break: break-word; }
    .summary-item .qty { color: var(--ink-mute, #6b7280); font-size: 0.82rem; }
    .summary-item .price { font-weight: 600; white-space: nowrap; }

    .summary-row {
        display: flex; justify-content: space-between;
        margin-bottom: 12px; font-size: 0.95rem; gap: 12px;
    }
    .summary-row .label { color: var(--ink-mute, #6b7280); }
    .summary-row .value { font-weight: 600; color: var(--ink, #0f172a); text-align: right; }
    .summary-row .value.free { color: var(--success, #10b981); font-weight: 700; }
    .summary-row.total {
        font-size: 1.25rem; font-weight: 800;
        padding-top: 16px; border-top: 2px solid var(--line, #e5e7eb);
        margin-top: 16px;
    }
    .summary-row.total .value {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        -webkit-background-clip: text; background-clip: text;
        color: transparent; font-size: 1.35rem;
    }

    .btn-place-order {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; padding: 16px; margin-top: 20px;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff; border: none; border-radius: 12px;
        font-family: inherit; font-weight: 700; font-size: 1rem;
        cursor: pointer; transition: all 0.25s;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-place-order:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(37,99,255,0.35);
    }
    .btn-place-order:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-place-order .spinner {
        display: inline-block;
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .trust-row {
        display: flex; justify-content: space-around; gap: 8px;
        padding: 16px 24px; border-top: 1px solid var(--line, #e5e7eb);
    }
    .trust-badge {
        flex: 1; text-align: center; font-size: 0.72rem; color: var(--ink-mute, #6b7280);
        display: flex; flex-direction: column; align-items: center; gap: 4px; font-weight: 600;
    }
    .trust-badge .icon { font-size: 1.2rem; }

    /* ---------- ALERTS & PROMPTS ---------- */
    .alert {
        display: none; align-items: flex-start; gap: 14px;
        padding: 16px 20px; border-radius: 12px;
        margin-bottom: 20px; border: 1px solid transparent;
        font-size: 0.92rem; line-height: 1.5;
    }
    .alert.active { display: flex; }
    .alert.error { background: rgba(239,68,68,0.06); border-color: rgba(239,68,68,0.2); color: #991b1b; }
    .alert .alert-icon { font-size: 1.3rem; flex-shrink: 0; line-height: 1; }

    .login-prompt {
        background: linear-gradient(120deg, rgba(37,99,255,0.06), rgba(124,58,237,0.06));
        border: 1px solid rgba(37,99,255,0.2);
        border-radius: 16px; padding: 20px 24px;
        display: flex; align-items: center; gap: 16px;
        margin-bottom: 24px;
    }
    .login-prompt .prompt-icon {
        width: 48px; height: 48px; border-radius: 50%;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED)); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }
    .login-prompt .prompt-body { flex: 1; min-width: 0; }
    .login-prompt .prompt-body strong { display: block; font-size: 0.98rem; font-weight: 700; margin-bottom: 3px; }
    .login-prompt .prompt-body p { color: var(--ink-mute, #6b7280); font-size: 0.87rem; line-height: 1.5; margin: 0; }
    .login-prompt .prompt-actions { display: flex; gap: 8px; flex-shrink: 0; }
    .login-prompt .btn-sm {
        padding: 10px 18px; border-radius: 8px;
        font-family: inherit; font-weight: 600; font-size: 0.85rem;
        cursor: pointer; border: none; white-space: nowrap; transition: all 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    .login-prompt .btn-sm.primary { background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED)); color: #fff; }
    .login-prompt .btn-sm.primary:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(37,99,255,0.3); }
    .login-prompt .btn-sm.secondary { background: #fff; color: var(--blue, #2563FF); border: 1.5px solid var(--blue, #2563FF); }
    .login-prompt .btn-sm.secondary:hover { background: var(--blue, #2563FF); color: #fff; }

    .threshold-banner {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px; border-radius: 10px;
        background: linear-gradient(120deg, rgba(16,185,129,0.08), rgba(34,211,238,0.06));
        border: 1px solid rgba(16,185,129,0.2);
        margin-bottom: 14px; font-size: 0.85rem; line-height: 1.5;
    }
    .threshold-banner .icon { font-size: 1.3rem; flex-shrink: 0; }
    .threshold-banner strong { color: var(--success, #10b981); }

    /* ---------- ACCESSIBILITY ---------- */
    .payment-option:focus-within { border-color: var(--blue, #2563FF); box-shadow: 0 0 0 3px rgba(37,99,255,0.1); }
    .btn-place-order:focus-visible,
    .login-prompt .btn-sm:focus-visible {
        outline: 2px solid var(--blue, #2563FF); outline-offset: 2px;
    }
    .sr-only {
        position: absolute; width: 1px; height: 1px;
        padding: 0; margin: -1px; overflow: hidden;
        clip: rect(0,0,0,0); white-space: nowrap; border: 0;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 900px) {
        .checkout-layout { grid-template-columns: 1fr; gap: 24px; }
        .order-summary { position: static; order: -1; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
        .login-prompt { flex-direction: column; text-align: center; padding: 18px 20px; }
        .login-prompt .prompt-actions { width: 100%; }
        .login-prompt .btn-sm { flex: 1; }
    }
    @media (max-width: 640px) {
        .checkout-wrapper { padding: 24px 0 40px; }
        .form-card { padding: 20px 18px; border-radius: 16px; }
        .form-card h2 { font-size: 1rem; }
        .summary-body { padding: 18px 18px; }
        .summary-header { padding: 18px 20px; }
        .trust-row { padding: 14px 18px; }
        .summary-items { max-height: 200px; }
    }
    @media (max-width: 400px) {
        .payment-option { padding: 14px; gap: 10px; }
        .payment-option-info strong { font-size: 0.9rem; }
        .payment-option-info span { font-size: 0.8rem; }
        .btn-place-order { font-size: 0.95rem; padding: 14px; }
    }
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/shop-header.php'; ?>

<section class="page-hero">
    <div class="container page-hero-content">
        <h1>🛒 Complete Your <span class="accent">Order</span></h1>
        <div class="page-hero-subtitle" aria-label="Checkout progress">
            <span class="step-badge done">✓ Cart</span>
            <span aria-hidden="true">→</span>
            <span class="step-badge active" aria-current="step">2. Checkout</span>
            <span aria-hidden="true">→</span>
            <span class="step-badge">3. Confirmation</span>
        </div>
    </div>
</section>

<div class="checkout-wrapper">
    <div class="container">
        <div id="checkoutContent" aria-live="polite" aria-busy="true">
            <div style="text-align:center;padding:80px 20px;color:var(--ink-mute, #6b7280);">
                <div style="display:inline-block;width:44px;height:44px;border:3px solid var(--line, #e5e7eb);border-top-color:var(--blue, #2563FF);border-radius:50%;animation:spin 0.9s linear infinite;"></div>
                <p style="margin-top:14px;">Preparing checkout…</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/shop-footer.php'; ?>

<script>
const CART_API     = '../api/shop-cart.php';
const ORDER_API    = '../api/shop-orders.php';
const REGIONS_API  = '../api/shop-delivery.php?action=public';
const SETTINGS_API = '../api/shop-settings.php';
const AUTH_API     = '../api/shop-auth-check.php';

// ----- State -----
let cartData      = null;
let regionsData   = [];
let settingsData  = {};
let currentUser   = null;
let deliveryFee   = 0;

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
    const el = document.getElementById('checkoutContent');
    if (el) el.setAttribute('aria-busy', isBusy ? 'true' : 'false');
}

// ----- Init -----
async function init() {
    setBusy(true);
    try {
        const [authRes, cartRes, regionsRes, settingsRes] = await Promise.all([
            fetchJSON(AUTH_API, { credentials: 'same-origin' }).catch(() => ({ logged_in: false })),
            fetchJSON(CART_API, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'get' })
            }).catch(() => ({ success: false })),
            fetchJSON(REGIONS_API).catch(() => ({ success: false, data: [] })),
            fetchJSON(SETTINGS_API).catch(() => ({ success: false, data: {} }))
        ]);

        currentUser  = authRes?.logged_in ? authRes.user : null;
        regionsData  = (regionsRes && regionsRes.success && Array.isArray(regionsRes.data)) ? regionsRes.data : [];
        settingsData = (settingsRes && settingsRes.success && settingsRes.data) ? settingsRes.data : {};

        if (!cartRes.success || !cartRes.cart || !Array.isArray(cartRes.cart.items) || cartRes.cart.items.length === 0) {
            renderEmptyCart();
            return;
        }
        cartData = cartRes.cart;
        renderCheckout();
    } catch (e) {
        renderError(e.message);
    } finally {
        setBusy(false);
    }
}

function renderError(msg) {
    document.getElementById('checkoutContent').innerHTML = `
        <div class="empty-state">
            <div class="icon" aria-hidden="true">⚠️</div>
            <h2>Could not load checkout</h2>
            <p>${escapeHtml(msg)}</p>
            <button class="btn-primary" id="retryBtn" style="border:none;cursor:pointer;font-family:inherit;">Retry</button>
        </div>
    `;
    document.getElementById('retryBtn')?.addEventListener('click', init);
}

function renderEmptyCart() {
    document.getElementById('checkoutContent').innerHTML = `
        <div class="empty-state">
            <div class="icon" aria-hidden="true">🛒</div>
            <h2>Your cart is empty</h2>
            <p>Add some products before checking out.</p>
            <a href="index.php" class="btn-primary">Browse Shop →</a>
        </div>
    `;
}

// ----- Render -----
function renderCheckout() {
    const itemsHtml = cartData.items.map(item => `
        <div class="summary-item">
            <span class="name">${escapeHtml(item.name)} <span class="qty">× ${item.quantity}</span></span>
            <span class="price">${formatPrice(item.line_total)}</span>
        </div>
    `).join('');

    const loginBanner = currentUser ? '' : `
        <div class="login-prompt" role="note">
            <div class="prompt-icon" aria-hidden="true">👤</div>
            <div class="prompt-body">
                <strong>You're not logged in</strong>
                <p>Log in or create an account to place your order.</p>
            </div>
            <div class="prompt-actions">
                <button type="button" class="btn-sm primary" id="loginBtn">Log In</button>
                <button type="button" class="btn-sm secondary" id="registerBtn">Sign Up</button>
            </div>
        </div>
    `;

    const regionsOptions = regionsData.length === 0
        ? '<option value="" disabled>No regions available — contact us</option>'
        : regionsData.map(r => {
            const fee = Number(r.delivery_fee) || 0;
            const feeLabel = fee === 0 ? 'FREE' : formatPrice(fee);
            const days = r.estimated_days ? ` • ${escapeHtml(r.estimated_days)}` : '';
            return `<option value="${escapeHtml(r.id)}" data-fee="${fee}" data-name="${escapeHtml(r.name)}" data-days="${escapeHtml(r.estimated_days || '')}">
                ${escapeHtml(r.name)} — ${feeLabel}${days}
            </option>`;
        }).join('');

    const userName  = escapeHtml(currentUser?.full_name || '');
    const userEmail = escapeHtml(currentUser?.email || '');
    const userPhone = escapeHtml(currentUser?.phone || '');
    const userAddr  = escapeHtml(currentUser?.default_address || '');
    const userCity  = escapeHtml(currentUser?.default_city || '');

    document.getElementById('checkoutContent').innerHTML = `
        <div class="checkout-layout">
            <div>
                ${loginBanner}

                <div class="alert error" id="errorAlert" role="alert" aria-live="assertive">
                    <div class="alert-icon" aria-hidden="true">⚠️</div>
                    <div id="errorMessage"></div>
                </div>

                <form id="checkoutForm" novalidate>
                    <div class="form-card">
                        <h2><span class="step-num" aria-hidden="true">1</span> Delivery Information</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="customer_name">Full Name <span class="req" aria-hidden="true">*</span></label>
                                <input type="text" id="customer_name" name="customer_name" required
                                    value="${userName}" placeholder="John Doe" autocomplete="name">
                            </div>
                            <div class="form-group">
                                <label for="customer_email">Email <span class="req" aria-hidden="true">*</span></label>
                                <input type="email" id="customer_email" name="customer_email" required
                                    value="${userEmail}" placeholder="john@example.com" autocomplete="email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="customer_phone">Phone Number <span class="req" aria-hidden="true">*</span></label>
                            <input type="tel" id="customer_phone" name="customer_phone" required
                                value="${userPhone}" placeholder="+256 700 000 000" autocomplete="tel">
                        </div>

                        <div class="form-group">
                            <label for="regionSelect">Delivery Region <span class="req" aria-hidden="true">*</span></label>
                            <div class="region-field">
                                <select name="region_id" id="regionSelect" required>
                                    <option value="">Select your delivery region...</option>
                                    ${regionsOptions}
                                </select>
                            </div>
                            <div class="region-info" id="regionInfo" role="status">
                                <span aria-hidden="true">📍</span>
                                <div class="info-body" id="regionInfoBody"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="delivery_address">Delivery Address <span class="req" aria-hidden="true">*</span></label>
                            <input type="text" id="delivery_address" name="delivery_address" required
                                value="${userAddr}" placeholder="Street, building, apartment..." autocomplete="street-address">
                        </div>

                        <div class="form-group">
                            <label for="delivery_city">City / Town</label>
                            <input type="text" id="delivery_city" name="delivery_city"
                                value="${userCity}" placeholder="e.g. Kampala" autocomplete="address-level2">
                        </div>

                        <div class="form-group">
                            <label for="delivery_notes">Delivery Notes (optional)</label>
                            <textarea id="delivery_notes" name="delivery_notes" placeholder="Special instructions..."></textarea>
                        </div>
                    </div>

                    <div class="form-card">
                        <h2><span class="step-num" aria-hidden="true">2</span> Payment Method</h2>
                        <div class="payment-options" role="radiogroup" aria-label="Payment method">
                            <label class="payment-option selected">
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                                <div class="payment-icon" aria-hidden="true">💵</div>
                                <div class="payment-option-info">
                                    <strong>Cash on Delivery</strong>
                                    <span>Pay when you receive your order</span>
                                </div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="mobile_money">
                                <div class="payment-icon" aria-hidden="true">📱</div>
                                <div class="payment-option-info">
                                    <strong>Mobile Money</strong>
                                    <span>MTN MoMo or Airtel Money</span>
                                </div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <div class="payment-icon" aria-hidden="true">🏦</div>
                                <div class="payment-option-info">
                                    <strong>Bank Transfer</strong>
                                    <span>We'll send you account details</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="order-summary">
                <div class="summary-header"><h2>💳 Order Summary</h2></div>
                <div class="summary-body">
                    <div id="thresholdBanner"></div>
                    <div class="summary-items">${itemsHtml}</div>
                    <div class="summary-row">
                        <span class="label">Subtotal</span>
                        <span class="value">${formatPrice(cartData.subtotal)}</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Delivery</span>
                        <span class="value" id="deliveryFeeDisplay">Select region</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="value" id="totalDisplay">${formatPrice(cartData.subtotal)}</span>
                    </div>
                    <button type="button" class="btn-place-order" id="placeOrderBtn">
                        <span id="placeOrderLabel">${currentUser ? 'Place Order' : 'Login to Place Order'}</span>
                        <span aria-hidden="true">→</span>
                    </button>
                </div>
                <div class="trust-row">
                    <div class="trust-badge"><span class="icon" aria-hidden="true">🔒</span><span>Secure</span></div>
                    <div class="trust-badge"><span class="icon" aria-hidden="true">🛡️</span><span>Warranty</span></div>
                    <div class="trust-badge"><span class="icon" aria-hidden="true">🚚</span><span>Fast Ship</span></div>
                </div>
            </div>
        </div>
    `;

    // Wire up events (no inline onclick)
    document.getElementById('loginBtn')?.addEventListener('click', goToLogin);
    document.getElementById('registerBtn')?.addEventListener('click', goToRegister);
    document.getElementById('placeOrderBtn')?.addEventListener('click', placeOrder);
    document.getElementById('regionSelect')?.addEventListener('change', onRegionChange);

    document.querySelectorAll('.payment-option input').forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
            input.closest('.payment-option').classList.add('selected');
        });
    });

    renderThresholdBanner();

    // Pre-select user's default region
    if (currentUser && currentUser.default_region_id) {
        const sel = document.getElementById('regionSelect');
        const opt = Array.from(sel.options).find(o => o.value == currentUser.default_region_id);
        if (opt) {
            sel.value = currentUser.default_region_id;
            onRegionChange();
        }
    }
}

// ----- Threshold banner -----
function renderThresholdBanner() {
    const threshold = parseFloat(settingsData.free_delivery_threshold || 0);
    const banner = document.getElementById('thresholdBanner');
    if (!banner || threshold <= 0) return;

    const subtotal = cartData.subtotal;
    if (subtotal >= threshold) {
        banner.innerHTML = `<div class="threshold-banner"><span class="icon" aria-hidden="true">🎉</span><div>You've unlocked <strong>FREE delivery</strong>!</div></div>`;
    } else {
        banner.innerHTML = `<div class="threshold-banner"><span class="icon" aria-hidden="true">🚚</span><div>Add <strong>${formatPrice(threshold - subtotal)}</strong> more for <strong>FREE delivery</strong>.</div></div>`;
    }
}

// ----- Region handling -----
function onRegionChange() {
    const sel = document.getElementById('regionSelect');
    const opt = sel.options[sel.selectedIndex];
    const info = document.getElementById('regionInfo');
    const infoBody = document.getElementById('regionInfoBody');

    if (!opt || !opt.value) {
        deliveryFee = 0;
        const feeEl = document.getElementById('deliveryFeeDisplay');
        feeEl.textContent = 'Select region';
        feeEl.classList.remove('free');
        document.getElementById('totalDisplay').textContent = formatPrice(cartData.subtotal);
        info.classList.remove('visible', 'free');
        return;
    }

    deliveryFee = parseFloat(opt.dataset.fee || 0);
    const regionName = opt.dataset.name;
    const days = opt.dataset.days;
    const threshold = parseFloat(settingsData.free_delivery_threshold || 0);
    let wasFreeByThreshold = false;

    if (threshold > 0 && cartData.subtotal >= threshold && deliveryFee > 0) {
        wasFreeByThreshold = true;
        deliveryFee = 0;
    }

    const total = cartData.subtotal + deliveryFee;
    const feeEl = document.getElementById('deliveryFeeDisplay');

    if (deliveryFee === 0) {
        feeEl.textContent = 'FREE';
        feeEl.classList.add('free');
        info.classList.add('visible', 'free');
        const reason = wasFreeByThreshold
            ? '🎉 Free delivery — order above threshold'
            : '🎁 Free delivery for this region';
        infoBody.innerHTML = `<strong>${escapeHtml(regionName)}</strong> — ${reason}${days ? `<span class="info-days">📅 ${escapeHtml(days)}</span>` : ''}`;
    } else {
        feeEl.textContent = formatPrice(deliveryFee);
        feeEl.classList.remove('free');
        info.classList.add('visible');
        info.classList.remove('free');
        infoBody.innerHTML = `<strong>${escapeHtml(regionName)}</strong> — Delivery fee: <strong>${formatPrice(deliveryFee)}</strong>${days ? `<span class="info-days">📅 ${escapeHtml(days)}</span>` : ''}`;
    }

    document.getElementById('totalDisplay').textContent = formatPrice(total);
}

// ----- Auth redirects -----
function goToLogin() {
    window.location.href = 'login.php?redirect=' + encodeURIComponent('shop/checkout.php');
}
function goToRegister() {
    window.location.href = 'register.php?redirect=' + encodeURIComponent('shop/checkout.php');
}

// ----- Place order -----
async function placeOrder() {
    if (!currentUser) {
        if (confirm('You need to be logged in to place an order.\n\nLog in or create an account now?')) {
            goToLogin();
        }
        return;
    }

    const form = document.getElementById('checkoutForm');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');
    const btn = document.getElementById('placeOrderBtn');
    const btnLabel = document.getElementById('placeOrderLabel');

    errorAlert.classList.remove('active');

    const formData = new FormData(form);
    const regionSelect = document.getElementById('regionSelect');
    const regionId = formData.get('region_id');
    const regionOpt = regionSelect.options[regionSelect.selectedIndex];

    const showError = (msg) => {
        errorMessage.textContent = msg;
        errorAlert.classList.add('active');
        window.scrollTo({ top: errorAlert.offsetTop - 100, behavior: 'smooth' });
    };

    // Validate required fields first
    if (!formData.get('customer_name') || !formData.get('customer_email') ||
        !formData.get('customer_phone') || !formData.get('delivery_address')) {
        showError('Please fill in all required fields');
        return;
    }

    if (!regionId) { showError('Please select your delivery region'); return; }

    const payload = {
        action: 'create',
        customer_name: formData.get('customer_name'),
        customer_email: formData.get('customer_email'),
        customer_phone: formData.get('customer_phone'),
        delivery_address: formData.get('delivery_address'),
        delivery_city: formData.get('delivery_city'),
        delivery_notes: formData.get('delivery_notes'),
        region_id: parseInt(regionId, 10),
        region_name: regionOpt ? regionOpt.dataset.name : '',
        payment_method: formData.get('payment_method'),
        delivery_fee: deliveryFee,
        items: cartData.items.map(i => ({ product_id: i.id, quantity: i.quantity }))
    };

    btn.disabled = true;
    btnLabel.textContent = 'Processing…';
    // swap arrow for spinner
    const arrow = btn.querySelector('span[aria-hidden="true"]');
    const originalArrow = arrow ? arrow.outerHTML : '';
    if (arrow) arrow.outerHTML = '<span class="spinner" aria-hidden="true"></span>';

    const resetBtn = () => {
        btn.disabled = false;
        btnLabel.textContent = 'Place Order';
        const sp = btn.querySelector('.spinner');
        if (sp) sp.outerHTML = originalArrow || '<span aria-hidden="true">→</span>';
    };

    try {
        const data = await fetchJSON(ORDER_API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (data.success) {
            // Clear cart (fire and forget, don't block redirect)
            fetch(CART_API, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'clear' })
            }).catch(() => {});

            window.location.href = `order-confirmation.php?order=${encodeURIComponent(data.order_number)}&email=${encodeURIComponent(payload.customer_email)}`;
        } else if (data.requires_login) {
            showError('Please log in again to place your order');
            resetBtn();
        } else {
            showError(data.message || 'Failed to place order');
            resetBtn();
        }
    } catch (e) {
        showError(e.message);
        resetBtn();
    }
}

// ----- Init -----
init();
</script>

</body>
</html>