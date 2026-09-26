<?php
$pageTitle = 'My Orders';
$currentAccountPage = 'orders';
require_once __DIR__ . '/includes/header.php';

$status = $_GET['status'] ?? 'all';

$statuses = [
    'all'        => ['label' => 'All Orders', 'icon' => '📦'],
    'pending'    => ['label' => 'Pending',    'icon' => '⏳'],
    'confirmed'  => ['label' => 'Confirmed',  'icon' => '✅'],
    'processing' => ['label' => 'Processing', 'icon' => '⚙️'],
    'shipped'    => ['label' => 'Shipped',    'icon' => '🚚'],
    'delivered'  => ['label' => 'Delivered',  'icon' => '📬'],
    'cancelled'  => ['label' => 'Cancelled',  'icon' => '❌'],
];
?>

<style>
    .ord-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .ord-filter {
        padding: 8px 16px;
        border-radius: 20px;
        border: 1.5px solid var(--line, rgba(11,16,38,0.1));
        background: #fff;
        cursor: pointer;
        font-family: inherit;
        font-weight: 600;
        font-size: 0.83rem;
        color: var(--ink-mute, #5A6180);
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .ord-filter:hover {
        border-color: var(--blue, #2563FF);
        color: var(--blue, #2563FF);
    }
    .ord-filter.active {
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 6px 14px rgba(37,99,255,0.25);
    }

    /* Order row — mobile-stackable */
    .ord-item {
        display: grid;
        grid-template-columns: 52px 1fr auto;
        gap: 16px;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--line, rgba(11,16,38,0.1));
        text-decoration: none;
        color: inherit;
        transition: background 0.2s;
    }
    .ord-item:last-child { border-bottom: none; padding-bottom: 0; }
    .ord-item:hover { background: rgba(37,99,255,0.02); }

    .ord-thumb {
        width: 52px; height: 52px;
        border-radius: 12px;
        background: linear-gradient(120deg, rgba(37,99,255,0.1), rgba(124,58,237,0.1));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .ord-main { min-width: 0; }
    .ord-main strong {
        display: block;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 3px;
        color: var(--ink, #0B1026);
    }
    .ord-main .meta {
        font-size: 0.8rem;
        color: var(--ink-mute, #5A6180);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .ord-main .meta .dot { opacity: 0.4; }

    .ord-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        text-align: right;
    }
    .ord-amount {
        font-weight: 800;
        font-size: 1rem;
        color: var(--blue, #2563FF);
        white-space: nowrap;
    }
    .ord-status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .ord-status-badge.pending    { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .ord-status-badge.confirmed  { background: rgba(37,99,255,0.1);  color: #2563FF; }
    .ord-status-badge.processing { background: rgba(124,58,237,0.1); color: #7C3AED; }
    .ord-status-badge.shipped    { background: rgba(34,211,238,0.1); color: #06b6d4; }
    .ord-status-badge.delivered  { background: rgba(16,185,129,0.1); color: #10b981; }
    .ord-status-badge.cancelled  { background: rgba(239,68,68,0.1);  color: #ef4444; }
    .ord-status-badge.returned   { background: rgba(124,58,237,0.1); color: #7C3AED; }
    .ord-status-badge.refunded   { background: rgba(16,185,129,0.15); color: #10b981; }

    @media (max-width: 640px) {
        .ord-item {
            grid-template-columns: 44px 1fr;
            grid-template-areas:
                "thumb main"
                "thumb right";
            gap: 10px 12px;
            padding: 14px 0;
            align-items: start;
        }
        .ord-thumb { grid-area: thumb; width: 44px; height: 44px; font-size: 1.15rem; }
        .ord-main { grid-area: main; }
        .ord-right {
            grid-area: right;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .ord-amount { font-size: 0.92rem; }
    }
</style>

<div class="acct-page-header">
    <h1>📦 My Orders</h1>
    <p>View and manage all your orders.</p>
</div>

<div class="ord-filters">
    <?php foreach ($statuses as $key => $meta): ?>
        <a href="?status=<?php echo urlencode($key); ?>"
           class="ord-filter <?php echo $status === $key ? 'active' : ''; ?>">
            <span><?php echo $meta['icon']; ?></span>
            <?php echo htmlspecialchars($meta['label']); ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="acct-card">
    <div id="ordersList">
        <div style="text-align:center;padding:60px 20px;color:var(--ink-mute);">
            <div style="display:inline-block;width:38px;height:38px;border:3px solid rgba(11,16,38,0.1);border-top-color:#2563FF;border-radius:50%;animation:acct-spin 0.9s linear infinite;"></div>
            <p style="margin-top:12px;">Loading orders...</p>
        </div>
    </div>
</div>

<style>
    @keyframes acct-spin { to { transform: rotate(360deg); } }
</style>

<script>
const ORDERS_API = '../../api/shop-my-orders.php';
const CURRENT_STATUS = <?php echo json_encode($status); ?>;

function formatPrice(n){return 'UGX ' + Number(n || 0).toLocaleString('en-US');}
function formatDate(d){
    return new Date(d).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
}
function escapeHtml(s){const d=document.createElement('div');d.textContent=s||'';return d.innerHTML;}

async function loadOrders() {
    const container = document.getElementById('ordersList');
    try {
        const res = await fetch(`${ORDERS_API}?action=list&status=${encodeURIComponent(CURRENT_STATUS)}&_=${Date.now()}`, {
            credentials: 'same-origin'
        });
        const data = await res.json();

        if (!data.success) {
            container.innerHTML = `<div style="text-align:center;padding:40px;color:var(--danger);">⚠️ ${escapeHtml(data.message || 'Failed to load orders')}</div>`;
            return;
        }

        if (!data.data || data.data.length === 0) {
            const isFiltered = CURRENT_STATUS !== 'all';
            container.innerHTML = `
                <div style="text-align:center;padding:50px 20px;color:var(--ink-mute);">
                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">${isFiltered ? '🔍' : '📭'}</div>
                    <p style="margin-bottom:16px;">${isFiltered ? 'No ' + CURRENT_STATUS + ' orders found.' : "You haven't placed any orders yet."}</p>
                    <a href="${isFiltered ? 'orders.php' : '../index.php'}" style="display:inline-block;padding:12px 24px;background:linear-gradient(120deg,#2563FF,#7C3AED);color:#fff;border-radius:10px;font-weight:600;text-decoration:none;">
                        ${isFiltered ? 'View All Orders' : 'Browse Shop →'}
                    </a>
                </div>
            `;
            return;
        }

        container.innerHTML = data.data.map(o => `
            <a href="order-details.php?id=${o.id}" class="ord-item">
                <div class="ord-thumb">📦</div>
                <div class="ord-main">
                    <strong>${escapeHtml(o.order_number)}</strong>
                    <div class="meta">
                        <span>${formatDate(o.created_at)}</span>
                        <span class="dot">·</span>
                        <span>${o.item_count || 0} item${o.item_count === 1 ? '' : 's'}</span>
                    </div>
                </div>
                <div class="ord-right">
                    <span class="ord-amount">${formatPrice(o.total)}</span>
                    <span class="ord-status-badge ${o.order_status}">${o.order_status}</span>
                </div>
            </a>
        `).join('');
    } catch (e) {
        container.innerHTML = `<div style="text-align:center;padding:40px;color:var(--danger);">⚠️ ${escapeHtml(e.message)}</div>`;
    }
}

loadOrders();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>