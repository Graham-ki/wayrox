<?php
$pageTitle = 'Receipt';
$currentAccountPage = 'orders';
require_once __DIR__ . '/includes/header.php';

$orderId = intval($_GET['id'] ?? 0);
if (!$orderId) { header('Location: orders.php'); exit; }
?>

<style>
    .rcp-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .rcp-toolbar h1 {
        font-size: clamp(1.2rem, 3vw, 1.5rem);
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .rcp-toolbar .paid-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        background: rgba(16,185,129,0.1);
        color: #10b981;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .rcp-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .rcp-btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .rcp-btn.primary {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
    }
    .rcp-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    .rcp-btn.secondary {
        background: #fff;
        color: var(--ink, #0B1026);
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
    }
    .rcp-btn.secondary:hover {
        border-color: var(--blue, #2563FF);
        color: var(--blue, #2563FF);
    }

    .rcp-paper {
        background: #fff;
        border-radius: 18px;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        padding: 32px;
        box-shadow: 0 8px 30px rgba(11,16,38,0.08);
        max-width: 700px;
        margin: 0 auto;
    }

    .rcp-header {
        text-align: center;
        padding-bottom: 24px;
        border-bottom: 2px dashed var(--line, rgba(11,16,38,0.1));
        margin-bottom: 24px;
    }
    .rcp-logo {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .rcp-logo svg { width: 32px; height: 32px; }
    .rcp-header h2 {
        font-size: clamp(1.3rem, 4vw, 1.6rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 6px;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .rcp-number {
        display: inline-block;
        padding: 6px 14px;
        background: var(--light, #F7F8FC);
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: var(--blue, #2563FF);
        margin-top: 6px;
        word-break: break-all;
        max-width: 100%;
    }
    .rcp-business {
        margin-top: 14px;
        font-size: 0.8rem;
        color: var(--ink-mute, #5A6180);
        line-height: 1.6;
    }

    .rcp-stamp {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 18px;
        margin: 22px 0;
        background: rgba(16,185,129,0.08);
        border: 2px dashed rgba(16,185,129,0.3);
        border-radius: 12px;
        color: #10b981;
        font-weight: 800;
        font-size: 1rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .rcp-stamp .check {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: #10b981;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .rcp-section {
        padding: 16px 0;
        border-bottom: 1px dashed var(--line, rgba(11,16,38,0.1));
    }
    .rcp-section:last-child { border-bottom: none; }

    .rcp-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 8px 0;
        font-size: 0.9rem;
        flex-wrap: wrap;
    }
    .rcp-row .label { color: var(--ink-mute, #5A6180); }
    .rcp-row .value {
        font-weight: 600;
        text-align: right;
        color: var(--ink, #0B1026);
        word-break: break-word;
        max-width: 60%;
    }
    .rcp-row.total {
        font-size: 1.15rem;
        font-weight: 800;
        padding-top: 14px;
        margin-top: 8px;
        border-top: 2px solid var(--line, rgba(11,16,38,0.1));
    }
    .rcp-row.total .value {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 1.3rem;
    }

    .rcp-items h4 {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink-mute, #5A6180);
        font-weight: 700;
        margin-bottom: 12px;
    }
    .rcp-item {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        font-size: 0.88rem;
        border-bottom: 1px solid var(--line, rgba(11,16,38,0.1));
        flex-wrap: wrap;
    }
    .rcp-item:last-child { border-bottom: none; }
    .rcp-item .name { flex: 1; min-width: 0; color: var(--ink, #0B1026); }
    .rcp-item .qty { color: var(--ink-mute, #5A6180); font-size: 0.78rem; margin-top: 2px; }
    .rcp-item .price { font-weight: 700; white-space: nowrap; }

    .rcp-notice {
        background: linear-gradient(120deg, rgba(37,99,255,0.06), rgba(124,58,237,0.04));
        border-left: 4px solid var(--blue, #2563FF);
        padding: 14px 16px;
        border-radius: 10px;
        font-size: 0.86rem;
        line-height: 1.6;
        margin-top: 20px;
        color: var(--ink, #0B1026);
    }
    .rcp-notice strong {
        display: block;
        color: var(--blue, #2563FF);
        margin-bottom: 6px;
        font-size: 0.88rem;
    }

    .rcp-footer {
        text-align: center;
        padding-top: 24px;
        border-top: 2px dashed var(--line, rgba(11,16,38,0.1));
        margin-top: 24px;
        font-size: 0.8rem;
        color: var(--ink-mute, #5A6180);
        line-height: 1.7;
    }
    .rcp-footer strong {
        color: var(--ink, #0B1026);
        display: block;
        margin-bottom: 4px;
        font-size: 0.92rem;
    }

    @media (max-width: 640px) {
        .rcp-paper { padding: 20px 16px; border-radius: 14px; }
        .rcp-toolbar { flex-direction: column; align-items: stretch; }
        .rcp-actions { width: 100%; }
        .rcp-btn { flex: 1; justify-content: center; }
        .rcp-row .value { max-width: 55%; }
        .rcp-stamp { font-size: 0.9rem; padding: 12px 14px; }
    }

    /* PRINT */
    @media print {
        body { background: #fff !important; }
        .account-topnav,
        .account-sidebar,
        .account-mobile-toggle,
        .rcp-toolbar,
        .shop-footer {
            display: none !important;
        }
        .account-shell {
            display: block !important;
            padding: 0 !important;
            max-width: 100% !important;
            grid-template-columns: 1fr !important;
        }
        .account-content { padding: 0 !important; }
        .rcp-paper {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
        @page { margin: 1cm; }
    }
</style>

<div id="receiptContent">
    <div style="text-align:center;padding:60px 20px;color:var(--ink-mute);">
        <div style="display:inline-block;width:38px;height:38px;border:3px solid rgba(11,16,38,0.1);border-top-color:#2563FF;border-radius:50%;animation:acct-spin 0.9s linear infinite;"></div>
        <p style="margin-top:12px;">Loading receipt...</p>
    </div>
</div>

<style>
    @keyframes acct-spin { to { transform: rotate(360deg); } }
</style>

<script>
const ORDER_ID = <?php echo $orderId; ?>;
const API = '../../api/shop-my-orders.php';

function formatPrice(n) { return 'UGX ' + Number(n || 0).toLocaleString('en-US'); }
function formatDate(d) {
    return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}
function formatDateTime(d) {
    return new Date(d).toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}
function escapeHtml(s) { const d = document.createElement('div'); d.textContent = s || ''; return d.innerHTML; }

async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    const text = await res.text();
    if (text.trim().startsWith('<')) throw new Error('Server returned unexpected response');
    return JSON.parse(text);
}

async function loadReceipt() {
    const container = document.getElementById('receiptContent');
    try {
        const data = await fetchJSON(`${API}?action=single&id=${ORDER_ID}&_=${Date.now()}`, {
            credentials: 'same-origin'
        });

        if (!data.success) throw new Error(data.message || 'Receipt not found');
        const order = data.data;

        if (order.order_status !== 'delivered' && !order.receipt_number) {
            container.innerHTML = `
                <div class="acct-card" style="text-align:center;padding:50px 20px;">
                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">📦</div>
                    <h2 style="justify-content:center;">Receipt not available yet</h2>
                    <p style="color:var(--ink-mute);margin-bottom:16px;">The receipt is issued once you confirm delivery of your order.</p>
                    <a href="order-details.php?id=${order.id}" class="rcp-btn primary">View Order</a>
                </div>
            `;
            return;
        }

        const itemsHtml = (order.items || []).map(item => `
            <div class="rcp-item">
                <div class="name">
                    ${escapeHtml(item.product_name)}
                    <div class="qty">× ${item.quantity} @ ${formatPrice(item.product_price)}</div>
                </div>
                <div class="price">${formatPrice(item.subtotal)}</div>
            </div>
        `).join('');

        const returnWindowText = order.return_window_ends_at
            ? formatDate(order.return_window_ends_at)
            : null;

        container.innerHTML = `
            <div class="rcp-toolbar">
                <h1>
                    ✅ Receipt
                    <span class="paid-badge">✓ Delivered</span>
                </h1>
                <div class="rcp-actions">
                    <a href="order-details.php?id=${order.id}" class="rcp-btn secondary">← Back</a>
                    <button class="rcp-btn primary" onclick="window.print()">🖨️ Print / PDF</button>
                </div>
            </div>

            <div class="rcp-paper">
                <div class="rcp-header">
                    <div class="rcp-logo">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 4L20 20M20 4L4 20" stroke="url(#rcpGrad)" stroke-width="2.6" stroke-linecap="round"/>
                            <defs>
                                <linearGradient id="rcpGrad" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0" stop-color="#2563FF"/>
                                    <stop offset="1" stop-color="#7C3AED"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        WayronX
                    </div>
                    <h2>Official Receipt</h2>
                    <div class="rcp-number">${escapeHtml(order.receipt_number || 'N/A')}</div>
                    <div class="rcp-business">
                        <strong>WayronX Ltd</strong> · Kampala, Uganda<br>
                        wayronx01@gmail.com · +256 795 885 548
                    </div>
                </div>

                <div class="rcp-stamp">
                    <div class="check">✓</div>
                    <span>Payment Received</span>
                </div>

                <div class="rcp-section">
                    <div class="rcp-row">
                        <span class="label">Receipt Date</span>
                        <span class="value">${formatDateTime(order.receipt_issued_at || order.delivered_at || order.created_at)}</span>
                    </div>
                    <div class="rcp-row">
                        <span class="label">Order Number</span>
                        <span class="value">${escapeHtml(order.order_number)}</span>
                    </div>
                    <div class="rcp-row">
                        <span class="label">Invoice Number</span>
                        <span class="value">${escapeHtml(order.invoice_number || '—')}</span>
                    </div>
                    <div class="rcp-row">
                        <span class="label">Customer</span>
                        <span class="value">${escapeHtml(order.customer_name)}</span>
                    </div>
                    <div class="rcp-row">
                        <span class="label">Contact</span>
                        <span class="value">${escapeHtml(order.customer_phone)}</span>
                    </div>
                    <div class="rcp-row">
                        <span class="label">Payment Method</span>
                        <span class="value" style="text-transform:capitalize;">${escapeHtml((order.payment_method || '').replace(/_/g, ' '))}</span>
                    </div>
                    <div class="rcp-row">
                        <span class="label">Delivery Address</span>
                        <span class="value">${escapeHtml(order.delivery_address)}${order.delivery_city ? ', ' + escapeHtml(order.delivery_city) : ''}</span>
                    </div>
                </div>

                <div class="rcp-section rcp-items">
                    <h4>Items Purchased</h4>
                    ${itemsHtml}
                </div>

                <div class="rcp-section">
                    <div class="rcp-row">
                        <span class="label">Subtotal</span>
                        <span class="value">${formatPrice(order.subtotal)}</span>
                    </div>
                    <div class="rcp-row">
                        <span class="label">Delivery</span>
                        <span class="value">${Number(order.delivery_fee) === 0 ? 'FREE' : formatPrice(order.delivery_fee)}</span>
                    </div>
                    <div class="rcp-row total">
                        <span>Total Paid</span>
                        <span class="value">${formatPrice(order.total)}</span>
                    </div>
                </div>

                ${returnWindowText ? `
                    <div class="rcp-notice">
                        <strong>↩️ Return & Exchange Window</strong>
                        You can request a return or exchange for items in this order until <strong>${returnWindowText}</strong>. After this date, the return window closes.
                    </div>
                ` : ''}

                <div class="rcp-footer">
                    <strong>Thank you for your business!</strong>
                    This receipt confirms delivery and payment of the above items.<br>
                    Questions? Contact wayronx01@gmail.com or +256 795 885 548.<br>
                    <span style="margin-top:8px;display:inline-block;font-size:0.72rem;">This is a computer-generated receipt. No signature required.</span>
                </div>
            </div>
        `;
        document.title = `Receipt ${order.receipt_number} — WayronX`;
    } catch (e) {
        container.innerHTML = `
            <div class="acct-card" style="text-align:center;padding:50px 20px;">
                <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">⚠️</div>
                <h2 style="justify-content:center;">Receipt not available</h2>
                <p style="color:var(--ink-mute);margin-bottom:16px;">${escapeHtml(e.message)}</p>
                <a href="orders.php" class="rcp-btn primary">Back to Orders</a>
            </div>
        `;
    }
}

loadReceipt();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>