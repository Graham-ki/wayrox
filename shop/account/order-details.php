<?php
$pageTitle = 'Order Details';
$currentAccountPage = 'orders';
require_once __DIR__ . '/includes/header.php';

$orderId = intval($_GET['id'] ?? 0);
if (!$orderId) { header('Location: orders.php'); exit; }
?>

<style>
    /* =========================================================
       ORDER DETAILS PAGE
       ========================================================= */

    /* Page header */
    .od-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .od-header .od-left h1 {
        font-size: clamp(1.25rem, 3.5vw, 1.6rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 4px;
        color: var(--ink, #0B1026);
    }
    .od-header .od-left p {
        color: var(--ink-mute, #5A6180);
        font-size: 0.88rem;
    }
    .od-status {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .od-status.pending    { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .od-status.confirmed  { background: rgba(37,99,255,0.1);  color: #2563FF; }
    .od-status.processing { background: rgba(124,58,237,0.1); color: #7C3AED; }
    .od-status.shipped    { background: rgba(34,211,238,0.1); color: #06b6d4; }
    .od-status.delivered  { background: rgba(16,185,129,0.1); color: #10b981; }
    .od-status.cancelled  { background: rgba(239,68,68,0.1);  color: #ef4444; }
    .od-status.returned   { background: rgba(124,58,237,0.1); color: #7C3AED; }
    .od-status.refunded   { background: rgba(16,185,129,0.15); color: #10b981; }

    /* Layout: main + sidebar */
    .od-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 20px;
        align-items: start;
    }
    .od-main { min-width: 0; }
    .od-side { min-width: 0; position: sticky; top: 90px; }

    @media (max-width: 900px) {
        .od-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .od-side { position: static; }
    }

    /* Timeline */
    .od-timeline {
        position: relative;
        padding-left: 28px;
        margin: 8px 0;
    }
    .od-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: var(--line, rgba(11,16,38,0.1));
    }
    .od-step {
        position: relative;
        padding-bottom: 20px;
    }
    .od-step:last-child { padding-bottom: 0; }
    .od-step-dot {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--line, rgba(11,16,38,0.1));
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 0 0 1px var(--line, rgba(11,16,38,0.1));
    }
    .od-step.active .od-step-dot {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
    }
    .od-step.completed .od-step-dot { background: #10b981; }
    .od-step h4 {
        font-size: 0.92rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: var(--ink, #0B1026);
    }
    .od-step .time {
        font-size: 0.76rem;
        color: var(--ink-mute, #5A6180);
    }
    .od-step .note {
        font-size: 0.82rem;
        color: var(--ink-mute, #5A6180);
        margin-top: 6px;
        padding: 8px 12px;
        background: var(--light, #F7F8FC);
        border-radius: 8px;
        line-height: 1.5;
    }

    /* Item line */
    .od-item {
        display: grid;
        grid-template-columns: 52px 1fr auto;
        gap: 14px;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid var(--line, rgba(11,16,38,0.1));
    }
    .od-item:last-child { border-bottom: none; }
    .od-thumb {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(120deg, rgba(37,99,255,0.08), rgba(124,58,237,0.08));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        overflow: hidden;
        flex-shrink: 0;
    }
    .od-thumb img { width: 100%; height: 100%; object-fit: contain; padding: 4px; box-sizing: border-box; }
    .od-item-info { min-width: 0; }
    .od-item-info strong {
        display: block;
        font-weight: 700;
        font-size: 0.92rem;
        margin-bottom: 3px;
        color: var(--ink, #0B1026);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .od-item-info span {
        font-size: 0.78rem;
        color: var(--ink-mute, #5A6180);
    }
    .od-item-right {
        text-align: right;
        flex-shrink: 0;
    }
    .od-item-right .qty {
        font-size: 0.78rem;
        color: var(--ink-mute, #5A6180);
        margin-bottom: 2px;
    }
    .od-item-right .price {
        font-weight: 800;
        font-size: 0.92rem;
        color: var(--blue, #2563FF);
        white-space: nowrap;
    }

    @media (max-width: 480px) {
        .od-item {
            grid-template-columns: 44px 1fr;
            grid-template-areas:
                "thumb info"
                "thumb right";
            gap: 8px 12px;
            align-items: flex-start;
        }
        .od-thumb { grid-area: thumb; width: 44px; height: 44px; font-size: 1.15rem; }
        .od-item-info { grid-area: info; }
        .od-item-right {
            grid-area: right;
            text-align: left;
            display: flex;
            gap: 12px;
            align-items: baseline;
        }
    }

    /* Delivery info */
    .od-delivery-row {
        padding: 10px 0;
        border-bottom: 1px solid var(--line, rgba(11,16,38,0.1));
        font-size: 0.9rem;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .od-delivery-row:last-child { border-bottom: none; }
    .od-delivery-row strong {
        color: var(--ink, #0B1026);
        font-weight: 700;
        min-width: 80px;
        flex-shrink: 0;
    }
    .od-delivery-row span {
        color: var(--ink-mute, #5A6180);
        word-break: break-word;
        flex: 1;
    }

    /* Order summary rows */
    .od-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding: 8px 0;
        font-size: 0.92rem;
        gap: 12px;
    }
    .od-summary-row .label { color: var(--ink-mute, #5A6180); }
    .od-summary-row .value { font-weight: 600; color: var(--ink, #0B1026); text-align: right; }
    .od-summary-row.total {
        padding-top: 14px;
        margin-top: 8px;
        border-top: 2px solid var(--line, rgba(11,16,38,0.1));
        font-size: 1.15rem;
        font-weight: 800;
    }
    .od-summary-row.total .value {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 1.3rem;
    }

    /* Action buttons */
    .od-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 16px;
    }
    .od-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 10px;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        text-align: center;
        width: 100%;
    }
    .od-btn.primary {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
    }
    .od-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,255,0.3);
    }
    .od-btn.secondary {
        background: #fff;
        color: var(--ink, #0B1026);
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
    }
    .od-btn.secondary:hover {
        border-color: var(--blue, #2563FF);
        color: var(--blue, #2563FF);
    }
    .od-btn.danger {
        background: #fff;
        color: #ef4444;
        border: 1.5px solid rgba(239,68,68,0.3);
    }
    .od-btn.danger:hover {
        background: rgba(239,68,68,0.06);
    }
    .od-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Alert cards */
    .od-alert-card {
        background: linear-gradient(120deg, rgba(34,211,238,0.06), rgba(37,99,255,0.04));
        border: 1px solid rgba(34,211,238,0.2);
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 0.86rem;
        line-height: 1.6;
        color: var(--ink, #0B1026);
        margin-bottom: 16px;
    }
    .od-alert-card strong {
        display: block;
        margin-bottom: 6px;
        color: var(--blue, #2563FF);
    }
    .od-alert-card.return {
        background: linear-gradient(120deg, rgba(245,158,11,0.06), rgba(239,68,68,0.03));
        border-color: rgba(245,158,11,0.2);
    }
    .od-alert-card.return strong { color: #f59e0b; }

    /* Modals — shared */
    .od-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        padding: 16px;
    }
    .od-modal-overlay.active { display: flex; }
    .od-modal {
        background: #fff;
        border-radius: 16px;
        max-width: 500px;
        width: 100%;
        padding: 26px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .od-modal h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--ink, #0B1026);
    }
    .od-modal p.help {
        color: var(--ink-mute, #5A6180);
        font-size: 0.86rem;
        margin-bottom: 18px;
        line-height: 1.55;
    }
    .od-modal label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 8px;
        color: var(--ink, #0B1026);
    }
    .od-modal textarea,
    .od-modal input[type="text"] {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.94rem;
        resize: vertical;
        box-sizing: border-box;
    }
    .od-modal textarea:focus,
    .od-modal input:focus {
        outline: none;
        border-color: var(--blue, #2563FF);
        box-shadow: 0 0 0 3px rgba(37,99,255,0.1);
    }
    .od-modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .od-modal-actions button {
        padding: 11px 20px;
        border-radius: 10px;
        font-family: inherit;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        flex: 1;
        min-width: 120px;
    }
    .od-modal-actions .cancel {
        background: var(--light, #F7F8FC);
        color: var(--ink, #0B1026);
    }
    .od-modal-actions .confirm {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
    }
    .od-modal-actions .confirm.danger {
        background: linear-gradient(120deg,#ef4444,#dc2626);
    }
    .od-modal-actions .confirm:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Return modal specifics */
    .od-return-info {
        background: var(--light, #F7F8FC);
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 16px;
        font-size: 0.86rem;
        line-height: 1.6;
        color: var(--ink, #0B1026);
    }
    .od-return-items {
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
        border-radius: 10px;
        max-height: 220px;
        overflow-y: auto;
        margin-bottom: 16px;
    }
    .od-return-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-bottom: 1px solid var(--line, rgba(11,16,38,0.1));
        cursor: pointer;
        transition: background 0.15s;
        font-weight: 500;
    }
    .od-return-row:last-child { border-bottom: none; }
    .od-return-row:hover { background: var(--light, #F7F8FC); }
    .od-return-row.checked { background: rgba(37,99,255,0.04); }
    .od-return-row input[type="checkbox"] {
        width: auto;
        margin: 0;
        flex-shrink: 0;
        accent-color: #2563FF;
    }
    .od-return-row .ri-info { flex: 1; min-width: 0; }
    .od-return-row .ri-info strong {
        display: block;
        font-weight: 600;
        font-size: 0.88rem;
        margin-bottom: 2px;
        color: var(--ink, #0B1026);
    }
    .od-return-row .ri-info span {
        font-size: 0.76rem;
        color: var(--ink-mute, #5A6180);
    }
    .od-return-types {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }
    .od-return-type {
        padding: 12px;
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 0.86rem;
        margin-bottom: 0;
    }
    .od-return-type:hover { border-color: #2563FF; }
    .od-return-type.selected {
        border-color: #2563FF;
        background: rgba(37,99,255,0.04);
    }
    .od-return-type input[type="radio"] {
        width: auto;
        margin: 0;
        flex-shrink: 0;
        accent-color: #2563FF;
    }
    .od-return-type strong { display: block; font-size: 0.86rem; margin-bottom: 2px; }
    .od-return-type .sub { font-size: 0.74rem; color: var(--ink-mute, #5A6180); font-weight: 400; }
    .od-return-summary {
        background: var(--light, #F7F8FC);
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 16px;
        font-size: 0.88rem;
        color: var(--ink, #0B1026);
    }

    @media (max-width: 480px) {
        .od-return-types { grid-template-columns: 1fr; }
        .od-modal { padding: 20px 16px; }
        .od-modal-actions button { flex: 1 1 100%; }
    }
</style>

<div class="acct-page-header">
    <div class="breadcrumb">
        <a href="orders.php">My Orders</a>
        <span>/</span>
        <span id="orderNumber">Loading...</span>
    </div>
</div>

<div id="orderDetailContainer">
    <div style="text-align:center;padding:60px 20px;color:var(--ink-mute);">
        <div style="display:inline-block;width:38px;height:38px;border:3px solid rgba(11,16,38,0.1);border-top-color:#2563FF;border-radius:50%;animation:acct-spin 0.9s linear infinite;"></div>
        <p style="margin-top:12px;">Loading order...</p>
    </div>
</div>

<style>
    @keyframes acct-spin { to { transform: rotate(360deg); } }
</style>

<!-- Cancel Modal -->
<div class="od-modal-overlay" id="cancelModal">
    <div class="od-modal">
        <h3>Cancel Order</h3>
        <p class="help">Are you sure you want to cancel this order? Items will be restocked and a refund will be processed.</p>
        <label for="cancelReason">Reason (optional)</label>
        <textarea id="cancelReason" rows="3" placeholder="Help us understand why you're cancelling..."></textarea>
        <div class="od-modal-actions">
            <button class="cancel" onclick="closeCancelModal()">Keep Order</button>
            <button class="confirm danger" id="confirmCancelBtn" onclick="confirmCancel()">Cancel Order</button>
        </div>
    </div>
</div>

<!-- Confirm Delivery Modal -->
<div class="od-modal-overlay" id="confirmDeliveryModal">
    <div class="od-modal">
        <h3>Confirm Delivery</h3>
        <p class="help">Please confirm that you've received your order. Once confirmed, a receipt will be issued and the return window will begin.</p>
        <div style="background:var(--light);border-radius:10px;padding:14px;margin-bottom:16px;">
            <label style="display:flex;align-items:flex-start;gap:10px;font-weight:500;font-size:0.9rem;cursor:pointer;margin-bottom:0;">
                <input type="checkbox" id="confirmCheck" style="margin-top:3px;flex-shrink:0;width:auto;accent-color:#2563FF;">
                <span>I confirm I have received all items in this order in good condition.</span>
            </label>
        </div>
        <div class="od-modal-actions">
            <button class="cancel" onclick="closeDeliveryModal()">Not Yet</button>
            <button class="confirm" id="confirmDeliveryBtn" onclick="confirmDelivery()" disabled>Confirm Receipt</button>
        </div>
    </div>
</div>

<!-- Return/Exchange Modal -->
<div class="od-modal-overlay" id="returnModal">
    <div class="od-modal" style="max-width: 560px;">
        <h3>↩️ Request Return / Exchange</h3>
        <p class="help">Select the items you want to return, choose whether you'd like a refund or an exchange, and tell us why.</p>

        <div class="od-return-info" id="returnOrderInfo"></div>

        <label style="margin-bottom:10px;">Items to return <span style="color:#ef4444;">*</span></label>
        <div class="od-return-items" id="returnItemsList"></div>

        <label style="margin-bottom:10px;">Return type <span style="color:#ef4444;">*</span></label>
        <div class="od-return-types">
            <label class="od-return-type selected" data-type="refund">
                <input type="radio" name="return_type" value="refund" checked>
                <div>
                    <strong>💵 Refund</strong>
                    <span class="sub">Get your money back</span>
                </div>
            </label>
            <label class="od-return-type" data-type="exchange">
                <input type="radio" name="return_type" value="exchange">
                <div>
                    <strong>🔄 Exchange</strong>
                    <span class="sub">Swap for another item</span>
                </div>
            </label>
        </div>

        <label for="returnReason">Reason for return <span style="color:#ef4444;">*</span></label>
        <textarea id="returnReason" rows="3" placeholder="Please tell us why you're returning these items..." style="margin-bottom:16px;"></textarea>

        <div class="od-return-summary" id="returnSummary">
            <span style="color:var(--ink-mute);">Select items to see the return value</span>
        </div>

        <div class="od-modal-actions">
            <button class="cancel" onclick="closeReturnModal()">Cancel</button>
            <button class="confirm" id="submitReturnBtn" onclick="submitReturn()">Submit Request</button>
        </div>
    </div>
</div>

<script>
const ORDER_API = '../../api/shop-my-orders.php';
const CANCEL_API = '../../api/shop-cancel-order.php';
const CONFIRM_API = '../../api/shop-confirm-delivery.php';
const RETURN_API = '../../api/shop-return-request.php';
const ORDER_ID = <?php echo $orderId; ?>;

let orderData = null;

function formatPrice(n) { return 'UGX ' + Number(n || 0).toLocaleString('en-US'); }
function formatDateTime(d) {
    if (!d) return 'N/A';
    return new Date(d).toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit' });
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
        console.error('HTML response:', text.substring(0, 500));
        throw new Error('Server returned unexpected response');
    }
    try { return JSON.parse(text); }
    catch (e) { throw new Error('Invalid JSON from server'); }
}

async function loadOrder() {
    try {
        const data = await fetchJSON(`${ORDER_API}?action=single&id=${ORDER_ID}&_=${Date.now()}`, {
            credentials: 'same-origin'
        });

        if (!data.success) {
            document.getElementById('orderDetailContainer').innerHTML = `
                <div class="acct-card" style="text-align:center;padding:50px 20px;">
                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">⚠️</div>
                    <h2 style="justify-content:center;">${escapeHtml(data.message || 'Order not found')}</h2>
                    <p style="color:var(--ink-mute);margin-bottom:16px;">You might not have access to this order.</p>
                    <a href="orders.php" class="od-btn primary" style="display:inline-flex;max-width:200px;margin:0 auto;">Back to Orders</a>
                </div>
            `;
            return;
        }

        orderData = data.data;
        document.getElementById('orderNumber').textContent = orderData.order_number;
        renderOrder();
    } catch (e) {
        document.getElementById('orderDetailContainer').innerHTML = `
            <div class="acct-card" style="text-align:center;padding:50px 20px;">
                <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">📡</div>
                <h2 style="justify-content:center;">Connection error</h2>
                <p style="color:var(--ink-mute);margin-bottom:16px;">${escapeHtml(e.message)}</p>
                <button class="od-btn primary" onclick="loadOrder()" style="max-width:200px;margin:0 auto;">Retry</button>
            </div>
        `;
    }
}

const STATUS_STEPS = [
    { key: 'pending',    label: 'Order Placed', desc: 'We received your order' },
    { key: 'confirmed',  label: 'Confirmed',    desc: 'Order confirmed' },
    { key: 'processing', label: 'Processing',   desc: 'Preparing your items' },
    { key: 'shipped',    label: 'Shipped',      desc: 'On the way to you' },
    { key: 'delivered',  label: 'Delivered',    desc: 'Order received' }
];

function getStepIndex(status) {
    if (['cancelled','returned','refunded'].includes(status)) return -1;
    return STATUS_STEPS.findIndex(s => s.key === status);
}

function renderOrder() {
    const o = orderData;
    const currentStep = getStepIndex(o.order_status);

    // ---------- Timeline ----------
    let timelineHtml = '';
    if (['cancelled','returned','refunded'].includes(o.order_status)) {
        timelineHtml = `
            <div class="od-step completed">
                <div class="od-step-dot">${o.order_status === 'cancelled' ? '✕' : '↩'}</div>
                <h4>${o.order_status.charAt(0).toUpperCase() + o.order_status.slice(1)}</h4>
                <div class="time">${o.cancelled_at ? formatDateTime(o.cancelled_at) : formatDateTime(o.updated_at || o.created_at)}</div>
                ${o.cancellation_reason ? `<div class="note">${escapeHtml(o.cancellation_reason)}</div>` : ''}
            </div>
        `;
    } else {
        timelineHtml = STATUS_STEPS.map((step, i) => {
            let cls = '';
            if (i < currentStep) cls = 'completed';
            else if (i === currentStep) cls = 'active';
            const icon = cls === 'completed' ? '✓' : (cls === 'active' ? '●' : '');

            const hist = (o.history || []).find(h => h.status === step.key);
            const time = hist ? formatDateTime(hist.created_at) : (i === 0 ? formatDateTime(o.created_at) : '');
            const note = hist && hist.note ? `<div class="note">${escapeHtml(hist.note)}</div>` : '';

            return `
                <div class="od-step ${cls}">
                    <div class="od-step-dot">${icon}</div>
                    <h4>${step.label}</h4>
                    <div class="time">${time}</div>
                    ${note}
                </div>
            `;
        }).join('');
    }

    // ---------- Items ----------
    const itemsHtml = (o.items || []).map(item => {
        const imgSrc = item.image ? `../${item.image}` : '';
        const img = imgSrc
            ? `<img src="${escapeHtml(imgSrc)}" alt="" onerror="this.style.display='none';this.parentElement.innerHTML='💻'">`
            : '💻';

        return `
            <div class="od-item">
                <div class="od-thumb">${img}</div>
                <div class="od-item-info">
                    <strong>${escapeHtml(item.product_name)}</strong>
                    <span>${formatPrice(item.product_price)} each</span>
                </div>
                <div class="od-item-right">
                    <div class="qty">× ${item.quantity}</div>
                    <div class="price">${formatPrice(item.subtotal)}</div>
                </div>
            </div>
        `;
    }).join('') || '<p style="color:var(--ink-mute);">No items found</p>';

    // ---------- Return info card ----------
    let returnInfoHtml = '';
    if (o.returns && o.returns.length > 0) {
        const activeReturns = o.returns.filter(r => ['pending', 'approved'].includes(r.status));
        if (activeReturns.length > 0) {
            const r = activeReturns[0];
            returnInfoHtml = `
                <div class="od-alert-card return">
                    <strong>↩️ Return ${r.status === 'approved' ? 'Approved' : 'Pending'}</strong>
                    ${r.return_type === 'exchange' ? 'Exchange' : 'Refund'} requested on ${formatDateTime(r.created_at)}<br>
                    ${r.status === 'approved' ? 'Ship the items back within 7 days.' : 'Awaiting admin review.'}
                    <div style="margin-top:10px;">
                        <a href="returns.php" style="font-size:0.84rem;font-weight:600;color:var(--blue);text-decoration:none;">View Return Details →</a>
                    </div>
                </div>
            `;
        }
    }

    // ---------- Action buttons ----------
    let actionsHtml = '';
    if (o.can_confirm_delivery) {
        actionsHtml += `<button class="od-btn primary" onclick="openDeliveryModal()">✅ Confirm Delivery</button>`;
    }
    if (o.can_return && !o.has_active_return) {
        actionsHtml += `<button class="od-btn secondary" onclick="openReturnModal()">↩️ Request Return/Exchange</button>`;
    }
    if (o.can_cancel) {
        actionsHtml += `<button class="od-btn danger" onclick="openCancelModal()">❌ Cancel Order</button>`;
    }
    if (o.invoice_number) {
        actionsHtml += `<a href="invoice.php?id=${o.id}" target="_blank" class="od-btn secondary">📄 View Invoice</a>`;
    }
    if (o.receipt_number) {
        actionsHtml += `<a href="receipt.php?id=${o.id}" target="_blank" class="od-btn secondary">🧾 View Receipt</a>`;
    }
    if (o.order_status === 'delivered' && !o.has_active_return && !o.can_return) {
        actionsHtml += `<div style="font-size:0.82rem;color:var(--ink-mute);text-align:center;padding:8px;">Return window has expired</div>`;
    }
    if (!actionsHtml) {
        actionsHtml = `<div style="font-size:0.82rem;color:var(--ink-mute);text-align:center;padding:8px;">No actions available</div>`;
    }

    // ---------- Render ----------
    document.getElementById('orderDetailContainer').innerHTML = `
        <div class="od-header">
            <div class="od-left">
                <h1>Order ${escapeHtml(o.order_number)}</h1>
                <p>Placed on ${formatDateTime(o.created_at)}</p>
            </div>
            <span class="od-status ${o.order_status}">${escapeHtml(o.order_status)}</span>
        </div>

        <div class="od-grid">
            <div class="od-main">

                <!-- Timeline -->
                <div class="acct-card">
                    <h2>Order Timeline</h2>
                    <div class="od-timeline">${timelineHtml}</div>
                </div>

                <!-- Items -->
                <div class="acct-card">
                    <h2>Items (${(o.items || []).length})</h2>
                    ${itemsHtml}
                </div>

                <!-- Delivery info -->
                <div class="acct-card">
                    <h2>Delivery Information</h2>
                    <div class="od-delivery-row">
                        <strong>Recipient:</strong>
                        <span>${escapeHtml(o.customer_name)}</span>
                    </div>
                    <div class="od-delivery-row">
                        <strong>Phone:</strong>
                        <span>${escapeHtml(o.customer_phone)}</span>
                    </div>
                    <div class="od-delivery-row">
                        <strong>Address:</strong>
                        <span>${escapeHtml(o.delivery_address)}${o.delivery_city ? ', ' + escapeHtml(o.delivery_city) : ''}</span>
                    </div>
                    ${o.region_name ? `
                        <div class="od-delivery-row">
                            <strong>Region:</strong>
                            <span>${escapeHtml(o.region_name)}</span>
                        </div>
                    ` : ''}
                    ${o.delivery_notes ? `
                        <div class="od-delivery-row">
                            <strong>Notes:</strong>
                            <span>${escapeHtml(o.delivery_notes)}</span>
                        </div>
                    ` : ''}
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="od-side">

                <!-- Summary -->
                <div class="acct-card">
                    <h2>Order Summary</h2>
                    <div class="od-summary-row">
                        <span class="label">Subtotal</span>
                        <span class="value">${formatPrice(o.subtotal)}</span>
                    </div>
                    <div class="od-summary-row">
                        <span class="label">Delivery</span>
                        <span class="value">${Number(o.delivery_fee) === 0 ? 'FREE' : formatPrice(o.delivery_fee)}</span>
                    </div>
                    <div class="od-summary-row">
                        <span class="label">Payment</span>
                        <span class="value" style="text-transform:capitalize;">${escapeHtml((o.payment_method || '').replace(/_/g, ' '))}</span>
                    </div>
                    <div class="od-summary-row total">
                        <span>Total</span>
                        <span class="value">${formatPrice(o.total)}</span>
                    </div>

                    <div class="od-actions">
                        ${actionsHtml}
                    </div>
                </div>

                ${returnInfoHtml}

                ${o.order_status === 'shipped' ? `
                    <div class="od-alert-card">
                        <strong>📬 Awaiting Delivery</strong>
                        Once you receive your order, click <strong>Confirm Delivery</strong> to issue your receipt and open the return window.
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

// ============ CANCEL ============
function openCancelModal() {
    document.getElementById('cancelReason').value = '';
    document.getElementById('cancelModal').classList.add('active');
}
function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('active');
}

async function confirmCancel() {
    const btn = document.getElementById('confirmCancelBtn');
    btn.disabled = true;
    btn.textContent = 'Cancelling...';

    const reason = document.getElementById('cancelReason').value.trim();

    try {
        const data = await fetchJSON(CANCEL_API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: ORDER_ID, reason })
        });

        if (data.success) {
            closeCancelModal();
            showToast('✓ Order cancelled successfully');
            setTimeout(loadOrder, 500);
        } else {
            showToast(data.message || 'Failed to cancel', 'error');
            btn.disabled = false;
            btn.textContent = 'Cancel Order';
        }
    } catch (e) {
        showToast(e.message, 'error');
        btn.disabled = false;
        btn.textContent = 'Cancel Order';
    }
}

// ============ CONFIRM DELIVERY ============
function openDeliveryModal() {
    document.getElementById('confirmCheck').checked = false;
    document.getElementById('confirmDeliveryBtn').disabled = true;
    document.getElementById('confirmDeliveryModal').classList.add('active');
}
function closeDeliveryModal() {
    document.getElementById('confirmDeliveryModal').classList.remove('active');
}

document.getElementById('confirmCheck').addEventListener('change', function () {
    document.getElementById('confirmDeliveryBtn').disabled = !this.checked;
});

async function confirmDelivery() {
    const btn = document.getElementById('confirmDeliveryBtn');
    btn.disabled = true;
    btn.textContent = 'Confirming...';

    try {
        const data = await fetchJSON(CONFIRM_API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: ORDER_ID })
        });

        if (data.success) {
            closeDeliveryModal();
            showToast('✓ Delivery confirmed! Receipt: ' + data.receipt_number);
            setTimeout(loadOrder, 500);
        } else {
            showToast(data.message || 'Failed', 'error');
            btn.disabled = false;
            btn.textContent = 'Confirm Receipt';
        }
    } catch (e) {
        showToast(e.message, 'error');
        btn.disabled = false;
        btn.textContent = 'Confirm Receipt';
    }
}

// ============ RETURN / EXCHANGE ============
function openReturnModal() {
    if (!orderData) return;

    const windowEnds = orderData.return_window_ends
        ? new Date(orderData.return_window_ends.replace(' ', 'T'))
        : null;
    const daysLeft = windowEnds
        ? Math.max(0, Math.ceil((windowEnds - new Date()) / 86400000))
        : 0;

    document.getElementById('returnOrderInfo').innerHTML = `
        <strong>Order ${escapeHtml(orderData.order_number)}</strong><br>
        <span style="color:var(--ink-mute);">
            Delivered on ${orderData.delivered_at ? formatDateTime(orderData.delivered_at) : 'N/A'}<br>
            ${windowEnds
                ? `Return window closes on <strong>${windowEnds.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</strong> — ${daysLeft} day${daysLeft === 1 ? '' : 's'} left`
                : ''}
        </span>
    `;

    const itemsHtml = (orderData.items || []).map(item => `
        <label class="od-return-row" data-item-id="${item.id}">
            <input type="checkbox" class="return-item-check" value="${item.id}"
                   data-price="${item.product_price}" data-qty="${item.quantity}">
            <div class="ri-info">
                <strong>${escapeHtml(item.product_name)}</strong>
                <span>${formatPrice(item.product_price)} each · Qty: ${item.quantity}</span>
            </div>
        </label>
    `).join('');
    document.getElementById('returnItemsList').innerHTML = itemsHtml;

    document.querySelectorAll('.return-item-check').forEach(cb => {
        cb.addEventListener('change', (e) => {
            const row = e.target.closest('.od-return-row');
            row.classList.toggle('checked', e.target.checked);
            updateReturnSummary();
        });
    });

    document.querySelectorAll('.od-return-type input').forEach(r => {
        r.addEventListener('change', () => {
            document.querySelectorAll('.od-return-type').forEach(o => o.classList.remove('selected'));
            r.closest('.od-return-type').classList.add('selected');
            updateReturnSummary();
        });
    });

    document.getElementById('returnReason').value = '';
    document.getElementById('returnSummary').innerHTML =
        '<span style="color:var(--ink-mute);">Select items to see the return value</span>';
    document.getElementById('submitReturnBtn').disabled = false;
    document.getElementById('submitReturnBtn').textContent = 'Submit Request';

    document.getElementById('returnModal').classList.add('active');
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.remove('active');
}

function updateReturnSummary() {
    const checked = Array.from(document.querySelectorAll('.return-item-check:checked'));
    let total = 0;
    checked.forEach(cb => {
        const price = parseFloat(cb.dataset.price);
        const qty = parseInt(cb.dataset.qty);
        total += price * qty;
    });

    const type = document.querySelector('input[name="return_type"]:checked')?.value || 'refund';
    const summary = document.getElementById('returnSummary');

    if (checked.length === 0) {
        summary.innerHTML = '<span style="color:var(--ink-mute);">Select items to see the return value</span>';
        return;
    }

    if (type === 'refund') {
        summary.innerHTML = `
            <strong style="color:#10b981;font-size:1rem;">Refund amount: ${formatPrice(total)}</strong><br>
            <span style="font-size:0.8rem;color:var(--ink-mute);">
                Refund will be processed after we receive and inspect the items.
            </span>
        `;
    } else {
        summary.innerHTML = `
            <strong style="color:#2563FF;font-size:1rem;">Exchange value: ${formatPrice(total)}</strong><br>
            <span style="font-size:0.8rem;color:var(--ink-mute);">
                We'll contact you to arrange the exchange.
            </span>
        `;
    }
}

async function submitReturn() {
    const checked = Array.from(document.querySelectorAll('.return-item-check:checked'))
        .map(cb => parseInt(cb.value));

    const reason = document.getElementById('returnReason').value.trim();
    const returnType = document.querySelector('input[name="return_type"]:checked')?.value || 'refund';

    if (checked.length === 0) {
        showToast('Please select at least one item', 'error');
        return;
    }
    if (!reason) {
        showToast('Please provide a reason for the return', 'error');
        return;
    }

    const btn = document.getElementById('submitReturnBtn');
    btn.disabled = true;
    btn.textContent = 'Submitting...';

    try {
        const data = await fetchJSON(RETURN_API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                order_id: ORDER_ID,
                return_type: returnType,
                reason: reason,
                items: checked
            })
        });

        if (data.success) {
            closeReturnModal();
            showToast('✓ Return request ' + (data.status === 'approved' ? 'approved!' : 'submitted!'));
            setTimeout(loadOrder, 800);
        } else {
            showToast(data.message || 'Failed to submit request', 'error');
            btn.disabled = false;
            btn.textContent = 'Submit Request';
        }
    } catch (e) {
        showToast(e.message, 'error');
        btn.disabled = false;
        btn.textContent = 'Submit Request';
    }
}

// ---------- Close on overlay / escape ----------
document.querySelectorAll('.od-modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('active');
    });
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.od-modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
});

// ---------- Init ----------
loadOrder();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>