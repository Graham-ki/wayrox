<?php
session_start();
require_once __DIR__ . '/../config/auth.php';
$shopCurrentPage = 'confirm';
$orderNumber = $_GET['order'] ?? '';
$email       = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmed — WayronX Shop</title>
<meta name="description" content="Your order has been received. Track your delivery and view order details.">
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .confirm-wrap { padding: 40px 0 60px; }
    .confirm-container { max-width: 720px; margin: 0 auto; padding: 0 24px; }

    .confirm-card {
        background: #fff; border-radius: 24px;
        padding: 48px 40px; text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        border: 1px solid var(--line, #e5e7eb);
        position: relative; overflow: hidden;
    }
    .confirm-card::before {
        content: ''; position: absolute; top: -30%; right: -10%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(37,99,255,0.06), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .confirm-card-inner { position: relative; z-index: 1; }

    /* ---------- SUCCESS STATE ---------- */
    .success-icon {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: #fff;
        margin: 0 auto 24px;
        box-shadow: 0 12px 30px rgba(16,185,129,0.3);
        animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes popIn {
        0%   { transform: scale(0); }
        70%  { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px;
        background: rgba(16,185,129,0.1);
        color: var(--success, #10b981);
        border-radius: 20px;
        font-size: 0.85rem; font-weight: 700;
        margin-bottom: 20px;
    }

    .confirm-card h1 {
        font-size: clamp(1.4rem, 3vw, 1.9rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 12px;
        line-height: 1.2;
    }
    .confirm-subtitle {
        color: var(--ink-mute, #6b7280);
        font-size: clamp(0.92rem, 2vw, 1rem);
        line-height: 1.6;
        max-width: 48ch;
        margin: 0 auto 30px;
        padding: 0 4px;
    }
    .confirm-subtitle strong { color: var(--ink, #0f172a); word-break: break-word; }

    /* ---------- ORDER NUMBER ---------- */
    .order-number-box {
        background: #f8fafc;
        border: 2px dashed var(--line, #e5e7eb);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 24px;
    }
    .order-number-box .label {
        font-size: 0.78rem;
        color: var(--ink-mute, #6b7280);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .order-number-box .number {
        font-size: clamp(1.1rem, 3vw, 1.4rem);
        font-weight: 800;
        color: var(--blue, #2563FF);
        letter-spacing: 0.02em;
        word-break: break-all;
        line-height: 1.3;
    }

    /* ---------- INFO GRID ---------- */
    .info-grid {
        text-align: left;
        background: #f8fafc;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 20px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 10px 0;
        border-bottom: 1px solid var(--line, #e5e7eb);
        font-size: 0.92rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: var(--ink-mute, #6b7280); flex-shrink: 0; }
    .info-row .value {
        font-weight: 600;
        text-align: right;
        min-width: 0;
        word-break: break-word;
    }

    /* ---------- ITEMS LIST ---------- */
    .items-list { text-align: left; margin-bottom: 24px; }
    .items-list h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 12px;
        margin-top: 0;
    }
    .item-row {
        display: flex; justify-content: space-between; gap: 12px;
        padding: 10px 0; border-bottom: 1px solid var(--line, #e5e7eb);
        font-size: 0.92rem;
        align-items: flex-start;
    }
    .item-row:last-child { border-bottom: none; }
    .item-row > span:first-child { flex: 1; min-width: 0; word-break: break-word; }
    .item-row .qty { color: var(--ink-mute, #6b7280); font-size: 0.85rem; }
    .item-row > span:last-child { font-weight: 600; white-space: nowrap; }

    .total-row {
        display: flex; justify-content: space-between;
        padding: 16px 0; margin-top: 12px;
        border-top: 2px solid var(--line, #e5e7eb);
        font-size: 1.15rem; font-weight: 800;
        align-items: baseline;
        gap: 12px;
    }
    .total-row .value {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 1.35rem;
        white-space: nowrap;
    }

    /* ---------- NEXT STEPS ---------- */
    .next-steps {
        background: linear-gradient(120deg, rgba(37,99,255,0.06), rgba(124,58,237,0.04));
        border-left: 4px solid var(--blue, #2563FF);
        padding: 18px 20px;
        border-radius: 10px;
        text-align: left;
        margin-bottom: 24px;
        font-size: 0.92rem;
        line-height: 1.65;
    }
    .next-steps strong {
        display: block;
        margin-bottom: 8px;
        color: var(--blue, #2563FF);
    }
    .next-steps strong.inline { display: inline; color: var(--ink, #0f172a); }

    /* ---------- ACTIONS ---------- */
    .confirm-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .confirm-actions .btn-primary,
    .confirm-actions .btn-secondary {
        flex: 1 1 auto;
        min-width: 180px;
        text-align: center;
    }

    /* ---------- STATES ---------- */
    .state-loading {
        text-align: center;
        padding: 40px 20px;
        color: var(--ink-mute, #6b7280);
    }
    .state-loading .spinner {
        display: inline-block;
        width: 40px; height: 40px;
        border: 3px solid var(--line, #e5e7eb);
        border-top-color: var(--blue, #2563FF);
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .state-error {
        text-align: center;
        padding: 20px;
    }
    .state-error .icon {
        font-size: 3rem;
        margin-bottom: 14px;
        line-height: 1;
    }
    .state-error h1 {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 12px;
    }
    .state-error p {
        color: var(--ink-mute, #6b7280);
        margin-bottom: 20px;
        line-height: 1.6;
        max-width: 44ch;
        margin-left: auto;
        margin-right: auto;
        word-break: break-word;
    }

    /* ---------- ACCESSIBILITY ---------- */
    .btn-primary:focus-visible,
    .btn-secondary:focus-visible {
        outline: 2px solid var(--blue, #2563FF);
        outline-offset: 2px;
    }
    .sr-only {
        position: absolute; width: 1px; height: 1px;
        padding: 0; margin: -1px; overflow: hidden;
        clip: rect(0,0,0,0); white-space: nowrap; border: 0;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 640px) {
        .confirm-wrap { padding: 24px 0 40px; }
        .confirm-container { padding: 0 16px; }
        .confirm-card { padding: 32px 20px; border-radius: 20px; }
        .success-icon { width: 72px; height: 72px; font-size: 2.2rem; margin-bottom: 18px; }
        .info-grid { padding: 16px 18px; }
        .items-list { margin-bottom: 18px; }
        .next-steps { padding: 16px; font-size: 0.88rem; }
        .order-number-box { padding: 16px; }
    }
    @media (max-width: 420px) {
        .confirm-actions { flex-direction: column; }
        .confirm-actions .btn-primary,
        .confirm-actions .btn-secondary {
            width: 100%;
            min-width: 0;
        }
        .info-row { flex-direction: column; gap: 4px; }
        .info-row .value { text-align: left; }
        .item-row { font-size: 0.88rem; }
    }
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/shop-header.php'; ?>

<section class="page-hero">
    <div class="container page-hero-content">
        <h1>✅ Order <span class="accent">Confirmed</span></h1>
        <div class="page-hero-subtitle" aria-label="Checkout progress">
            <span class="step-badge done">✓ Cart</span>
            <span aria-hidden="true">→</span>
            <span class="step-badge done">✓ Checkout</span>
            <span aria-hidden="true">→</span>
            <span class="step-badge active" aria-current="step">3. Confirmation</span>
        </div>
    </div>
</section>

<div class="confirm-wrap">
    <div class="confirm-container">
        <div class="confirm-card" id="confirmCard" aria-live="polite" aria-busy="true">
            <div class="confirm-card-inner">
                <div class="state-loading">
                    <div class="spinner" role="status" aria-label="Loading order"></div>
                    <p style="margin-top:14px;">Loading your order…</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/shop-footer.php'; ?>

<script>
const API = '../api/shop-orders.php';

// Server-injected values (safe JSON encoding)
const orderNumber = <?php echo json_encode($orderNumber, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
const email       = <?php echo json_encode($email,       JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

// ----- Helpers -----
function formatPrice(n) {
    return 'UGX ' + Number(n || 0).toLocaleString('en-US');
}

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

function formatDate(d) {
    if (!d) return '—';
    const date = new Date(d);
    if (isNaN(date.getTime())) return '—';
    return date.toLocaleDateString('en-US', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
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
    const el = document.getElementById('confirmCard');
    if (el) el.setAttribute('aria-busy', isBusy ? 'true' : 'false');
}

// ----- State renderers -----
function renderError(title, message) {
    const card = document.getElementById('confirmCard');
    card.innerHTML = `
        <div class="confirm-card-inner">
            <div class="state-error">
                <div class="icon" aria-hidden="true">⚠️</div>
                <h1>${escapeHtml(title)}</h1>
                <p>${escapeHtml(message)}</p>
                <a href="index.php" class="btn-primary">Back to Shop</a>
            </div>
        </div>
    `;
}

function renderOrder(order) {
    const card = document.getElementById('confirmCard');

    const itemsHtml = (order.items || []).map(i => `
        <div class="item-row">
            <span>${escapeHtml(i.product_name || 'Product')} <span class="qty">× ${Number(i.quantity) || 0}</span></span>
            <span>${formatPrice(i.subtotal)}</span>
        </div>
    `).join('');

    const paymentLabel = (order.payment_method || '').replace(/_/g, ' ');
    const addressLine = escapeHtml(order.delivery_address || '') +
        (order.delivery_city ? ', ' + escapeHtml(order.delivery_city) : '');

    card.innerHTML = `
        <div class="confirm-card-inner">
            <div class="success-icon" aria-hidden="true">✓</div>
            <div class="status-badge"><span aria-hidden="true">🎉</span> Order Confirmed</div>
            <h1>Thank you for your order!</h1>
            <p class="confirm-subtitle">
                We've received your order and will begin processing it right away.
                You'll receive updates at <strong>${escapeHtml(email)}</strong>.
            </p>

            <div class="order-number-box">
                <div class="label">Your Order Number</div>
                <div class="number">${escapeHtml(order.order_number)}</div>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <span class="label">Order Date</span>
                    <span class="value">${formatDate(order.created_at)}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status</span>
                    <span class="value" style="color:var(--blue, #2563FF);text-transform:capitalize;">${escapeHtml(order.order_status || '')}</span>
                </div>
                <div class="info-row">
                    <span class="label">Payment Method</span>
                    <span class="value" style="text-transform:capitalize;">${escapeHtml(paymentLabel)}</span>
                </div>
                <div class="info-row">
                    <span class="label">Delivery To</span>
                    <span class="value">${addressLine}</span>
                </div>
            </div>

            <div class="items-list">
                <h3>Order Items</h3>
                ${itemsHtml}
                <div class="total-row">
                    <span>Total</span>
                    <span class="value">${formatPrice(order.total)}</span>
                </div>
            </div>

            <div class="next-steps">
                <strong><span aria-hidden="true">📌</span> What happens next?</strong>
                Our team will contact you at
                <strong class="inline">${escapeHtml(order.customer_phone || '')}</strong>
                within 24 hours to confirm your order and delivery details. You can track your order
                status anytime using your order number.
            </div>

            <div class="confirm-actions">
                <a href="track-order.php?order=${encodeURIComponent(order.order_number)}&email=${encodeURIComponent(email)}" class="btn-primary">
                    <span aria-hidden="true">📦</span> Track Order
                </a>
                <a href="index.php" class="btn-secondary">Continue Shopping</a>
            </div>
        </div>
    `;
}

// ----- Load -----
async function loadOrder() {
    setBusy(true);

    // Guard against missing URL params
    if (!orderNumber || !email) {
        renderError(
            'Order not found',
            "We couldn't find that order. Please check the link or contact us."
        );
        setBusy(false);
        return;
    }

    try {
        const data = await fetchJSON(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'track',
                order_number: orderNumber,
                email: email
            })
        });

        if (!data.success || !data.data) {
            throw new Error(data.message || 'Order not found');
        }

        renderOrder(data.data);
    } catch (e) {
        renderError("Couldn't load order", e.message);
    } finally {
        setBusy(false);
    }
}

loadOrder();
</script>

</body>
</html>