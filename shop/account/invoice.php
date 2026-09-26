<?php
$pageTitle = 'Invoice';
$currentAccountPage = 'orders';
require_once __DIR__ . '/includes/header.php';

$orderId = intval($_GET['id'] ?? 0);
if (!$orderId) { header('Location: orders.php'); exit; }
?>

<style>
    .inv-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .inv-toolbar h1 {
        font-size: clamp(1.2rem, 3vw, 1.5rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .inv-toolbar .status-pill {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .inv-toolbar .status-pill.pending    { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .inv-toolbar .status-pill.confirmed  { background: rgba(37,99,255,0.1);  color: #2563FF; }
    .inv-toolbar .status-pill.processing { background: rgba(124,58,237,0.1); color: #7C3AED; }
    .inv-toolbar .status-pill.shipped    { background: rgba(34,211,238,0.1); color: #06b6d4; }
    .inv-toolbar .status-pill.delivered  { background: rgba(16,185,129,0.1); color: #10b981; }
    .inv-toolbar .status-pill.cancelled  { background: rgba(239,68,68,0.1);  color: #ef4444; }
    .inv-toolbar .status-pill.refunded   { background: rgba(16,185,129,0.15); color: #10b981; }

    .inv-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .inv-btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .inv-btn.primary {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
    }
    .inv-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    .inv-btn.secondary {
        background: #fff;
        color: var(--ink, #0B1026);
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
    }
    .inv-btn.secondary:hover {
        border-color: var(--blue, #2563FF);
        color: var(--blue, #2563FF);
    }

    /* INVOICE PAPER */
    .inv-paper {
        background: #fff;
        border-radius: 18px;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        padding: 32px;
        box-shadow: 0 8px 30px rgba(11,16,38,0.08);
        overflow: hidden;
    }

    .inv-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding-bottom: 24px;
        border-bottom: 2px solid var(--line, rgba(11,16,38,0.1));
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .inv-brand-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.3rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .inv-brand-logo svg { width: 32px; height: 32px; }
    .inv-brand-tagline {
        font-size: 0.82rem;
        color: var(--ink-mute, #5A6180);
        margin-top: 4px;
    }
    .inv-brand-contact {
        font-size: 0.8rem;
        color: var(--ink-mute, #5A6180);
        line-height: 1.7;
        margin-top: 8px;
    }
    .inv-brand-contact strong { color: var(--ink, #0B1026); }

    .inv-meta { text-align: right; }
    .inv-meta h2 {
        font-size: clamp(1.4rem, 4vw, 1.9rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 10px;
    }
    .inv-meta-row {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        font-size: 0.85rem;
        margin-bottom: 5px;
    }
    .inv-meta-row .label { color: var(--ink-mute, #5A6180); }
    .inv-meta-row .value { font-weight: 700; color: var(--ink, #0B1026); }

    /* Parties */
    .inv-parties {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    .inv-party h4 {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink-mute, #5A6180);
        font-weight: 700;
        margin-bottom: 10px;
    }
    .inv-party-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink, #0B1026);
        margin-bottom: 6px;
    }
    .inv-party-info {
        font-size: 0.88rem;
        color: var(--ink-mute, #5A6180);
        line-height: 1.6;
        word-break: break-word;
    }

    /* Items — responsive table */
    .inv-items-wrap {
        margin: 0 -32px 24px;
        padding: 0 32px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .inv-items {
        width: 100%;
        min-width: 500px;
        border-collapse: collapse;
    }
    .inv-items thead th {
        background: var(--light, #F7F8FC);
        padding: 12px 14px;
        text-align: left;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--ink-mute, #5A6180);
        font-weight: 700;
        border-bottom: 2px solid var(--line, rgba(11,16,38,0.1));
    }
    .inv-items thead th:last-child { text-align: right; }
    .inv-items thead th.center { text-align: center; }
    .inv-items tbody td {
        padding: 14px;
        border-bottom: 1px solid var(--line, rgba(11,16,38,0.1));
        font-size: 0.9rem;
        color: var(--ink, #0B1026);
    }
    .inv-items tbody td.center { text-align: center; }
    .inv-items tbody td:last-child { text-align: right; font-weight: 700; }
    .inv-items tbody tr:last-child td { border-bottom: none; }

    /* Totals */
    .inv-totals {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 24px;
    }
    .inv-totals-box { width: 100%; max-width: 320px; }
    .inv-total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 0.92rem;
    }
    .inv-total-row .label { color: var(--ink-mute, #5A6180); }
    .inv-total-row .value { font-weight: 600; color: var(--ink, #0B1026); }
    .inv-total-row.total {
        font-size: 1.15rem;
        font-weight: 800;
        padding-top: 14px;
        margin-top: 8px;
        border-top: 2px solid var(--line, rgba(11,16,38,0.1));
    }
    .inv-total-row.total .value {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 1.3rem;
    }

    .inv-notes {
        background: var(--light, #F7F8FC);
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 20px;
    }
    .inv-notes h4 {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--ink, #0B1026);
    }
    .inv-notes p {
        font-size: 0.86rem;
        color: var(--ink-mute, #5A6180);
        line-height: 1.65;
    }

    .inv-footer {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid var(--line, rgba(11,16,38,0.1));
        font-size: 0.8rem;
        color: var(--ink-mute, #5A6180);
        line-height: 1.7;
    }
    .inv-footer strong { color: var(--ink, #0B1026); }

    @media (max-width: 640px) {
        .inv-paper { padding: 20px 16px; border-radius: 14px; }
        .inv-items-wrap { margin: 0 -16px 20px; padding: 0 16px; }
        .inv-header { flex-direction: column; }
        .inv-meta { text-align: left; width: 100%; }
        .inv-meta-row { justify-content: space-between; }
        .inv-parties { grid-template-columns: 1fr; gap: 16px; }
        .inv-totals-box { max-width: 100%; }
        .inv-toolbar { flex-direction: column; align-items: stretch; }
        .inv-actions { width: 100%; }
        .inv-btn { flex: 1; justify-content: center; }
    }

    /* PRINT */
    @media print {
        body { background: #fff !important; }
        .account-topnav,
        .account-sidebar,
        .account-mobile-toggle,
        .inv-toolbar,
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
        .inv-paper {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }
        .inv-items-wrap { overflow: visible !important; margin: 0 0 20px !important; padding: 0 !important; }
        .inv-items { min-width: 0 !important; }
        @page { margin: 1cm; }
    }
</style>

<div id="invoiceContent">
    <div style="text-align:center;padding:60px 20px;color:var(--ink-mute);">
        <div style="display:inline-block;width:38px;height:38px;border:3px solid rgba(11,16,38,0.1);border-top-color:#2563FF;border-radius:50%;animation:acct-spin 0.9s linear infinite;"></div>
        <p style="margin-top:12px;">Loading invoice...</p>
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
function escapeHtml(s) { const d = document.createElement('div'); d.textContent = s || ''; return d.innerHTML; }

async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    const text = await res.text();
    if (text.trim().startsWith('<')) throw new Error('Server returned unexpected response');
    return JSON.parse(text);
}

async function loadInvoice() {
    const container = document.getElementById('invoiceContent');
    try {
        const data = await fetchJSON(`${API}?action=single&id=${ORDER_ID}&_=${Date.now()}`, {
            credentials: 'same-origin'
        });

        if (!data.success) throw new Error(data.message || 'Invoice not found');
        const order = data.data;

        const itemsHtml = (order.items || []).map(item => `
            <tr>
                <td>${escapeHtml(item.product_name)}</td>
                <td class="center">${item.quantity}</td>
                <td class="center">${formatPrice(item.product_price)}</td>
                <td>${formatPrice(item.subtotal)}</td>
            </tr>
        `).join('');

        const invoiceNumber = order.invoice_number || order.order_number;
        const issuedDate = order.invoice_issued_at || order.created_at;

        container.innerHTML = `
            <div class="inv-toolbar">
                <h1>
                    🧾 Invoice
                    <span class="status-pill ${order.order_status}">${order.order_status}</span>
                </h1>
                <div class="inv-actions">
                    <a href="order-details.php?id=${order.id}" class="inv-btn secondary">← Back</a>
                    <button class="inv-btn primary" onclick="window.print()">🖨️ Print / PDF</button>
                </div>
            </div>

            <div class="inv-paper">
                <div class="inv-header">
                    <div>
                        <div class="inv-brand-logo">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M4 4L20 20M20 4L4 20" stroke="url(#invGrad)" stroke-width="2.6" stroke-linecap="round"/>
                                <defs>
                                    <linearGradient id="invGrad" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0" stop-color="#2563FF"/>
                                        <stop offset="1" stop-color="#7C3AED"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            WayronX
                        </div>
                        <div class="inv-brand-tagline">Technology Solutions</div>
                        <div class="inv-brand-contact">
                            <strong>WayronX Ltd</strong><br>
                            Kampala, Uganda<br>
                            wayronx01@gmail.com<br>
                            +256 795 885 548
                        </div>
                    </div>

                    <div class="inv-meta">
                        <h2>INVOICE</h2>
                        <div class="inv-meta-row">
                            <span class="label">Invoice #</span>
                            <span class="value">${escapeHtml(invoiceNumber)}</span>
                        </div>
                        <div class="inv-meta-row">
                            <span class="label">Order #</span>
                            <span class="value">${escapeHtml(order.order_number)}</span>
                        </div>
                        <div class="inv-meta-row">
                            <span class="label">Issued</span>
                            <span class="value">${formatDate(issuedDate)}</span>
                        </div>
                        <div class="inv-meta-row">
                            <span class="label">Payment</span>
                            <span class="value" style="text-transform:capitalize;">${escapeHtml((order.payment_method || '').replace(/_/g, ' '))}</span>
                        </div>
                    </div>
                </div>

                <div class="inv-parties">
                    <div class="inv-party">
                        <h4>Bill To</h4>
                        <div class="inv-party-name">${escapeHtml(order.customer_name)}</div>
                        <div class="inv-party-info">
                            ${escapeHtml(order.customer_email)}<br>
                            ${escapeHtml(order.customer_phone)}
                        </div>
                    </div>
                    <div class="inv-party">
                        <h4>Deliver To</h4>
                        <div class="inv-party-name">${escapeHtml(order.customer_name)}</div>
                        <div class="inv-party-info">
                            ${escapeHtml(order.delivery_address)}${order.delivery_city ? '<br>' + escapeHtml(order.delivery_city) : ''}
                            ${order.region_name ? '<br>' + escapeHtml(order.region_name) : ''}
                        </div>
                    </div>
                </div>

                <div class="inv-items-wrap">
                    <table class="inv-items">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="center">Qty</th>
                                <th class="center">Unit Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>${itemsHtml}</tbody>
                    </table>
                </div>

                <div class="inv-totals">
                    <div class="inv-totals-box">
                        <div class="inv-total-row">
                            <span class="label">Subtotal</span>
                            <span class="value">${formatPrice(order.subtotal)}</span>
                        </div>
                        <div class="inv-total-row">
                            <span class="label">Delivery</span>
                            <span class="value">${Number(order.delivery_fee) === 0 ? 'FREE' : formatPrice(order.delivery_fee)}</span>
                        </div>
                        <div class="inv-total-row total">
                            <span>Total</span>
                            <span class="value">${formatPrice(order.total)}</span>
                        </div>
                    </div>
                </div>

                <div class="inv-notes">
                    <h4>📌 Notes</h4>
                    <p>This invoice is issued for order <strong>${escapeHtml(order.order_number)}</strong> placed on ${formatDate(order.created_at)}.
                    Thank you for shopping with WayronX. Questions? Contact <strong>wayronx01@gmail.com</strong> or <strong>+256 795 885 548</strong>.</p>
                </div>

                <div class="inv-footer">
                    <strong>Thank you for your business!</strong><br>
                    This is a computer-generated invoice. No signature required.
                </div>
            </div>
        `;
        document.title = `Invoice ${invoiceNumber} — WayronX`;
    } catch (e) {
        container.innerHTML = `
            <div class="acct-card" style="text-align:center;padding:50px 20px;">
                <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">⚠️</div>
                <h2 style="justify-content:center;">Invoice not available</h2>
                <p style="color:var(--ink-mute);margin-bottom:16px;">${escapeHtml(e.message)}</p>
                <a href="orders.php" class="inv-btn primary">Back to Orders</a>
            </div>
        `;
    }
}

loadInvoice();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>