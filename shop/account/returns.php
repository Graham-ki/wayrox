<?php
$pageTitle = 'Returns & Exchanges';
$currentAccountPage = 'returns';
require_once __DIR__ . '/includes/header.php';
?>

<style>
    .return-card {
        background: #fff; border-radius: 14px;
        border: 1px solid var(--line); padding: 20px;
        margin-bottom: 14px;
        transition: all 0.2s;
    }
    .return-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .return-header {
        display: flex; justify-content: space-between;
        align-items: flex-start; gap: 16px; flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .return-header h3 {
        font-size: 1rem; font-weight: 700; margin-bottom: 4px;
    }
    .return-header .meta {
        font-size: 0.82rem; color: var(--ink-mute);
    }
    .return-type-badge {
        padding: 4px 10px; border-radius: 6px;
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .return-type-badge.refund { background: rgba(37,99,255,0.1); color: var(--blue); }
    .return-type-badge.exchange { background: rgba(124,58,237,0.1); color: var(--purple); }

    .return-status {
        padding: 5px 12px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 700;
        text-transform: capitalize;
    }
    .return-status.pending { background: rgba(245,158,11,0.1); color: var(--warning); }
    .return-status.approved { background: rgba(16,185,129,0.1); color: var(--success); }
    .return-status.rejected { background: rgba(239,68,68,0.1); color: var(--danger); }
    .return-status.completed { background: rgba(16,185,129,0.15); color: var(--success); }

    .return-reason {
        background: var(--light); border-radius: 8px;
        padding: 12px 14px; font-size: 0.88rem;
        color: var(--ink); margin-bottom: 12px;
        line-height: 1.55;
    }
    .return-reason strong { display: block; margin-bottom: 4px; font-weight: 700; color: var(--ink-mute); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; }

    .return-footer {
        display: flex; justify-content: space-between; align-items: center;
        gap: 12px; flex-wrap: wrap;
        padding-top: 12px; border-top: 1px solid var(--line);
    }
    .return-footer .amount {
        font-weight: 800; font-size: 1.05rem; color: var(--blue);
    }
    .return-footer .actions a {
        padding: 8px 14px; border-radius: 8px;
        background: var(--light); color: var(--ink);
        font-weight: 600; font-size: 0.82rem;
        transition: all 0.2s;
    }
    .return-footer .actions a:hover {
        background: var(--blue); color: #fff;
    }

    .empty-returns {
        text-align: center; padding: 80px 20px;
    }
    .empty-returns .icon { font-size: 4rem; margin-bottom: 20px; opacity: 0.5; }
    .empty-returns h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 8px; }
    .empty-returns p { color: var(--ink-mute); margin-bottom: 20px; }
</style>

<div class="account-page-header">
    <h1>↩️ Returns & Exchanges</h1>
    <p>Track your return and exchange requests here.</p>
</div>

<div id="returnsContainer">
    <div style="text-align:center;padding:60px 20px;color:var(--ink-mute);">
        <div style="display:inline-block;width:40px;height:40px;border:3px solid var(--line);border-top-color:var(--blue);border-radius:50%;animation:spin 0.9s linear infinite;"></div>
        <p style="margin-top:14px;">Loading returns...</p>
    </div>
</div>

<style>@keyframes spin{to{transform:rotate(360deg);}}</style>

<script>
const API = '../../api/shop-my-orders.php';

function formatPrice(n){return 'UGX ' + Number(n).toLocaleString('en-US');}
function formatDate(d){return new Date(d).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit' });}
function escapeHtml(s){const d=document.createElement('div');d.textContent=s||'';return d.innerHTML;}

async function loadReturns() {
    const container = document.getElementById('returnsContainer');

    try {
        // Fetch all user orders, then get returns for each. Simpler: fetch orders and filter those with returns.
        const res = await fetch(`${API}?action=list&status=all&_=${Date.now()}`, { credentials: 'same-origin' });
        const data = await res.json();

        if (!data.success) {
            container.innerHTML = `<div class="empty-returns"><div class="icon">⚠️</div><h3>Couldn't load returns</h3><p>${data.message || 'Please try again'}</p></div>`;
            return;
        }

        // For each order, get its returns
        const allReturns = [];
        for (const order of (data.data || [])) {
            try {
                const r = await fetch(`${API}?action=single&id=${order.id}&_=${Date.now()}`, { credentials: 'same-origin' });
                const rd = await r.json();
                if (rd.success && rd.data.returns && rd.data.returns.length) {
                    rd.data.returns.forEach(ret => {
                        ret.order_number = rd.data.order_number;
                        ret.order_id = rd.data.id;
                        ret.total = rd.data.total;
                        allReturns.push(ret);
                    });
                }
            } catch (e) { /* skip */ }
        }

        if (allReturns.length === 0) {
            container.innerHTML = `
                <div class="empty-returns">
                    <div class="icon">📭</div>
                    <h3>No return requests</h3>
                    <p>You haven't made any return or exchange requests yet.</p>
                    <a href="orders.php" style="display:inline-block;padding:12px 24px;background:var(--grad);color:#fff;border-radius:10px;font-weight:600;">View My Orders</a>
                </div>
            `;
            return;
        }

        // Sort by newest
        allReturns.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        container.innerHTML = allReturns.map(r => `
            <div class="return-card">
                <div class="return-header">
                    <div>
                        <h3>${r.order_number}</h3>
                        <div class="meta">
                            Requested ${formatDate(r.created_at)}
                            <span style="margin:0 8px;">·</span>
                            <span class="return-type-badge ${r.return_type || 'refund'}">${r.return_type || 'refund'}</span>
                        </div>
                    </div>
                    <span class="return-status ${r.status}">${r.status}</span>
                </div>

                <div class="return-reason">
                    <strong>Your reason</strong>
                    ${escapeHtml(r.reason)}
                </div>

                ${r.admin_note ? `
                <div class="return-reason" style="background:rgba(37,99,255,0.05);">
                    <strong>Admin note</strong>
                    ${escapeHtml(r.admin_note)}
                </div>
                ` : ''}

                <div class="return-footer">
                    <div>
                        ${r.refund_amount > 0 ? `<span style="font-size:0.85rem;color:var(--ink-mute);">Refund amount:</span> <span class="amount">${formatPrice(r.refund_amount)}</span>` : ''}
                    </div>
                    <div class="actions">
                        <a href="order-details.php?id=${r.order_id}">View Order</a>
                    </div>
                </div>
            </div>
        `).join('');

    } catch (e) {
        container.innerHTML = `<div class="empty-returns"><div class="icon">📡</div><h3>Connection error</h3><p>Please check your connection.</p></div>`;
    }
}

loadReturns();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>