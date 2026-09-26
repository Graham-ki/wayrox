<?php
session_start();
require_once __DIR__ . '/../config/auth.php';
$shopCurrentPage = 'track';
$prefillOrder = $_GET['order'] ?? '';
$prefillEmail = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Your Order — WayronX Shop</title>
<meta name="description" content="Track your WayronX order status, delivery progress, and order details.">
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/shop.css">
<style>
    .track-wrap { padding: 40px 0 60px; }
    .track-container { max-width: 800px; margin: 0 auto; padding: 0 24px; }

    /* ---------- FORM ---------- */
    .track-form {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 24px rgba(11,16,38,0.04);
        border: 1px solid var(--line, #e5e7eb);
        margin-bottom: 24px;
    }
    .form-group { margin-bottom: 18px; }
    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 8px;
        color: var(--ink, #0f172a);
    }
    .form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid var(--line, #e5e7eb);
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.95rem;
        background: #fff;
        color: var(--ink, #0f172a);
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none;
        border-color: var(--blue, #2563FF);
        box-shadow: 0 0 0 3px rgba(37,99,255,0.1);
    }

    .btn-track {
        width: 100%;
        padding: 16px;
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: inherit;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-track:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(37,99,255,0.3);
    }
    .btn-track:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-track .spinner {
        display: inline-block;
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ---------- ERROR ---------- */
    .error-box {
        background: rgba(239,68,68,0.08);
        color: var(--danger, #ef4444);
        padding: 14px 18px;
        border-radius: 10px;
        font-size: 0.9rem;
        margin-bottom: 16px;
        display: none;
        font-weight: 500;
        line-height: 1.5;
        word-break: break-word;
    }
    .error-box.active { display: block; }

    /* ---------- RESULT ---------- */
    .order-result { display: none; }
    .order-result.active { display: block; }

    .order-result-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(11,16,38,0.04);
        border: 1px solid var(--line, #e5e7eb);
        overflow: hidden;
    }
    .order-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--line, #e5e7eb);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
    }
    .order-header-info { min-width: 0; flex: 1; }
    .order-header-info h2 {
        font-size: clamp(1.1rem, 3vw, 1.3rem);
        font-weight: 800;
        color: var(--blue, #2563FF);
        margin-bottom: 6px;
        margin-top: 0;
        word-break: break-all;
    }
    .order-header-info p {
        color: var(--ink-mute, #6b7280);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .status-pill {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .status-pill.pending    { background: rgba(245,158,11,0.1); color: var(--warning, #f59e0b); }
    .status-pill.confirmed  { background: rgba(37,99,255,0.1);  color: var(--blue, #2563FF); }
    .status-pill.processing { background: rgba(124,58,237,0.1); color: var(--purple, #7C3AED); }
    .status-pill.shipped    { background: rgba(34,211,238,0.1); color: #06b6d4; }
    .status-pill.delivered  { background: rgba(16,185,129,0.1); color: var(--success, #10b981); }
    .status-pill.cancelled  { background: rgba(239,68,68,0.1);  color: var(--danger, #ef4444); }

    .order-body { padding: 28px; }

    /* ---------- TIMELINE ---------- */
    .timeline { position: relative; padding-left: 32px; margin: 20px 0; }
    .timeline::before {
        content: '';
        position: absolute;
        left: 11px; top: 12px; bottom: 12px;
        width: 2px;
        background: var(--line, #e5e7eb);
    }
    .timeline-step { position: relative; padding-bottom: 24px; }
    .timeline-step:last-child { padding-bottom: 0; }
    .timeline-dot {
        position: absolute;
        left: -32px; top: 2px;
        width: 24px; height: 24px;
        border-radius: 50%;
        background: var(--line, #e5e7eb);
        border: 4px solid #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem; font-weight: 700; color: #fff;
        box-sizing: border-box;
    }
    .timeline-step.active .timeline-dot {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
    }
    .timeline-step.completed .timeline-dot { background: var(--success, #10b981); }
    .timeline-step h4 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 4px;
        margin-top: 0;
    }
    .timeline-step p {
        font-size: 0.85rem;
        color: var(--ink-mute, #6b7280);
        line-height: 1.5;
        margin: 0;
    }

    /* ---------- DETAILS ---------- */
    .order-details {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--line, #e5e7eb);
    }
    .order-details h3 {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 16px;
        margin-top: 0;
    }
    .item-line {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--line, #e5e7eb);
        font-size: 0.95rem;
        align-items: flex-start;
    }
    .item-line:last-child { border-bottom: none; }
    .item-line > span:first-child { flex: 1; min-width: 0; word-break: break-word; }
    .item-line .qty { color: var(--ink-mute, #6b7280); font-size: 0.85rem; }
    .item-line > span:last-child { font-weight: 600; white-space: nowrap; }

    .total-line {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 12px;
        padding-top: 16px;
        margin-top: 16px;
        border-top: 2px solid var(--line, #e5e7eb);
        font-size: 1.1rem;
        font-weight: 800;
    }
    .total-line .value {
        background: var(--grad, linear-gradient(120deg,#2563FF,#7C3AED));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 1.25rem;
        white-space: nowrap;
    }

    /* ---------- DELIVERY INFO ---------- */
    .delivery-info {
        background: #f8fafc;
        border-radius: 10px;
        padding: 18px;
        margin-top: 20px;
    }
    .delivery-info h4 {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 10px;
        margin-top: 0;
        color: var(--ink-mute, #6b7280);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .delivery-info p {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--ink, #0f172a);
        word-break: break-word;
    }
    .delivery-info p + p { margin-top: 8px; }
    .delivery-info .muted { color: var(--ink-mute, #6b7280); font-size: 0.9rem; }

    /* ---------- CANCELLED ---------- */
    .cancelled-note {
        padding: 20px;
        background: rgba(239,68,68,0.08);
        border-radius: 10px;
        color: var(--danger, #ef4444);
        text-align: center;
        font-weight: 600;
        line-height: 1.5;
    }

    /* ---------- ACCESSIBILITY ---------- */
    .btn-track:focus-visible,
    .form-group input:focus-visible {
        outline: 2px solid var(--blue, #2563FF);
        outline-offset: 2px;
    }
    .sr-only {
        position: absolute; width: 1px; height: 1px;
        padding: 0; margin: -1px; overflow: hidden;
        clip: rect(0,0,0,0); white-space: nowrap; border: 0;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 600px) {
        .track-wrap { padding: 24px 0 40px; }
        .track-container { padding: 0 16px; }
        .track-form { padding: 22px 20px; border-radius: 16px; }
        .order-header { padding: 20px; }
        .order-body { padding: 20px; }
        .timeline { padding-left: 28px; }
        .timeline-dot { left: -28px; width: 22px; height: 22px; }
        .timeline::before { left: 10px; }
    }
    @media (max-width: 420px) {
        .order-header { flex-direction: column; align-items: stretch; }
        .status-pill { align-self: flex-start; }
        .item-line { font-size: 0.88rem; }
        .delivery-info { padding: 14px; }
    }
</style>
</head>
<body>

<?php require_once __DIR__ . '/includes/shop-header.php'; ?>

<section class="page-hero">
    <div class="container page-hero-content">
        <h1><span aria-hidden="true">📦</span> Track Your <span class="accent">Order</span></h1>
        <p class="page-hero-subtitle">Enter your order number and email to see your order status</p>
    </div>
</section>

<div class="track-wrap">
    <div class="track-container">
        <form class="track-form" id="trackForm" novalidate>
            <div class="error-box" id="errorBox" role="alert" aria-live="assertive"></div>

            <div class="form-group">
                <label for="orderNumber">Order Number</label>
                <input
                    type="text"
                    id="orderNumber"
                    name="order"
                    placeholder="WX-20260115-A1B2C"
                    value="<?php echo htmlspecialchars($prefillOrder, ENT_QUOTES); ?>"
                    autocomplete="off"
                    required
                >
            </div>
            <div class="form-group">
                <label for="orderEmail">Email Address</label>
                <input
                    type="email"
                    id="orderEmail"
                    name="email"
                    placeholder="you@example.com"
                    value="<?php echo htmlspecialchars($prefillEmail, ENT_QUOTES); ?>"
                    autocomplete="email"
                    required
                >
            </div>
            <button type="submit" class="btn-track" id="trackBtn">
                <span id="trackBtnLabel">Track Order</span>
                <span aria-hidden="true">→</span>
            </button>
        </form>

        <div class="order-result" id="orderResult" aria-live="polite" aria-busy="false"></div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/shop-footer.php'; ?>

<script>
const API = '../api/shop-orders.php';

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
    return date.toLocaleString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
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

// ----- Status steps -----
const STATUS_STEPS = [
    { key: 'pending',    label: 'Order Placed', desc: "We've received your order" },
    { key: 'confirmed',  label: 'Confirmed',    desc: 'Order confirmed by our team' },
    { key: 'processing', label: 'Processing',   desc: 'Preparing your items' },
    { key: 'shipped',    label: 'Shipped',      desc: 'On the way to you' },
    { key: 'delivered',  label: 'Delivered',    desc: 'Order received' }
];

const STATUS_KEYS = STATUS_STEPS.map(s => s.key);

function getStepIndex(status) {
    if (status === 'cancelled') return -1;
    return STATUS_KEYS.indexOf(status);
}

// ----- Track -----
async function trackOrder() {
    const orderNumber = document.getElementById('orderNumber').value.trim();
    const email       = document.getElementById('orderEmail').value.trim();
    const errorBox    = document.getElementById('errorBox');
    const btn         = document.getElementById('trackBtn');
    const btnLabel    = document.getElementById('trackBtnLabel');
    const result      = document.getElementById('orderResult');

    errorBox.classList.remove('active');
    result.classList.remove('active');
    result.innerHTML = '';

    // Client-side validation
    if (!orderNumber || !email) {
        showError('Please enter both order number and email');
        return;
    }
    // Basic email format check
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('Please enter a valid email address');
        return;
    }

    btn.disabled = true;
    btnLabel.textContent = 'Searching…';
    result.setAttribute('aria-busy', 'true');

    // swap arrow for spinner (preserve label)
    const arrow = btn.querySelector('span[aria-hidden="true"]');
    const originalArrow = arrow ? arrow.outerHTML : '';
    if (arrow) arrow.outerHTML = '<span class="spinner" aria-hidden="true"></span>';

    const resetBtn = () => {
        btn.disabled = false;
        btnLabel.textContent = 'Track Order';
        const sp = btn.querySelector('.spinner');
        if (sp) sp.outerHTML = originalArrow || '<span aria-hidden="true">→</span>';
    };

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.classList.add('active');
        resetBtn();
        result.setAttribute('aria-busy', 'false');
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

        if (data.success && data.data) {
            renderOrder(data.data);
            resetBtn();
        } else {
            showError(data.message || 'Order not found. Please check your details.');
        }
    } catch (e) {
        showError(e.message || 'Network error. Please try again.');
    } finally {
        result.setAttribute('aria-busy', 'false');
    }
}

// ----- Render -----
function renderOrder(order) {
    const result = document.getElementById('orderResult');
    const status = order.order_status || 'pending';
    const currentStep = getStepIndex(status);
    const isCancelled = status === 'cancelled';

    // Timeline
    let timelineHtml = '';
    if (!isCancelled) {
        timelineHtml = STATUS_STEPS.map((step, i) => {
            let cls = '';
            if (i < currentStep)      cls = 'completed';
            else if (i === currentStep) cls = 'active';

            const icon = cls === 'completed' ? '✓' : (cls === 'active' ? '●' : '');
            return `
                <div class="timeline-step ${cls}">
                    <div class="timeline-dot" aria-hidden="true">${icon}</div>
                    <h4>${escapeHtml(step.label)}</h4>
                    <p>${escapeHtml(step.desc)}</p>
                </div>
            `;
        }).join('');
    }

    // Items
    const items = Array.isArray(order.items) ? order.items : [];
    const itemsHtml = items.map(i => `
        <div class="item-line">
            <span>${escapeHtml(i.product_name || 'Product')} <span class="qty">× ${Number(i.quantity) || 0}</span></span>
            <span>${formatPrice(i.subtotal)}</span>
        </div>
    `).join('');

    // Address
    const addressLine = escapeHtml(order.delivery_address || '') +
        (order.delivery_city ? ', ' + escapeHtml(order.delivery_city) : '');

    result.innerHTML = `
        <div class="order-result-card">
            <div class="order-header">
                <div class="order-header-info">
                    <h2>${escapeHtml(order.order_number || '')}</h2>
                    <p>Placed on ${formatDate(order.created_at)}</p>
                </div>
                <span class="status-pill ${escapeHtml(status)}">${escapeHtml(status)}</span>
            </div>

            <div class="order-body">
                ${isCancelled
                    ? `<div class="cancelled-note"><span aria-hidden="true">❌</span> This order has been cancelled</div>`
                    : `<div class="timeline">${timelineHtml}</div>`
                }

                <div class="order-details">
                    <h3>Order Items</h3>
                    ${itemsHtml || '<p style="color:var(--ink-mute, #6b7280);font-size:0.9rem;">No items found</p>'}
                    <div class="total-line">
                        <span>Total</span>
                        <span class="value">${formatPrice(order.total)}</span>
                    </div>
                </div>

                <div class="delivery-info">
                    <h4><span aria-hidden="true">📦</span> Delivery Address</h4>
                    <p>${addressLine}</p>
                    ${order.delivery_notes ? `<p class="muted">Note: ${escapeHtml(order.delivery_notes)}</p>` : ''}
                    ${order.customer_phone ? `<p class="muted">Contact: ${escapeHtml(order.customer_phone)}</p>` : ''}
                </div>
            </div>
        </div>
    `;

    result.classList.add('active');
    result.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ----- Wire up form -----
document.getElementById('trackForm').addEventListener('submit', e => {
    e.preventDefault();
    trackOrder();
});

// ----- Auto-track if prefilled -----
<?php if ($prefillOrder && $prefillEmail): ?>
document.addEventListener('DOMContentLoaded', () => {
    // small delay so layout settles & fonts load
    setTimeout(trackOrder, 200);
});
<?php endif; ?>
</script>

</body>
</html>