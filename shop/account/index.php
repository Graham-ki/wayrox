<?php
$pageTitle = 'My Account';
$currentAccountPage = 'overview';
require_once __DIR__ . '/includes/header.php';

$pdo = getConnection();
$userId = (int)($accountUser['id'] ?? 0);
$userEmail = $accountUser['email'] ?? '';

// Stats
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_orders,
        COALESCE(SUM(CASE WHEN order_status IN ('pending','confirmed','processing','shipped') THEN 1 ELSE 0 END), 0) as active,
        COALESCE(SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END), 0) as delivered,
        COALESCE(SUM(CASE WHEN order_status = 'delivered' THEN total ELSE 0 END), 0) as total_spent
    FROM shop_orders 
    WHERE user_id = ? OR customer_email = ?
");
$stmt->execute([$userId, $userEmail]);
$stats = $stmt->fetch();
$stats['total_orders'] = (int)($stats['total_orders'] ?? 0);
$stats['active'] = (int)($stats['active'] ?? 0);
$stats['delivered'] = (int)($stats['delivered'] ?? 0);
$stats['total_spent'] = (float)($stats['total_spent'] ?? 0);

// Recent orders
$stmt = $pdo->prepare("
    SELECT id, order_number, total, order_status, created_at
    FROM shop_orders 
    WHERE user_id = ? OR customer_email = ?
    ORDER BY created_at DESC 
    LIMIT 3
");
$stmt->execute([$userId, $userEmail]);
$recentOrders = $stmt->fetchAll();

// Alerts
$stmt = $pdo->prepare("SELECT COUNT(*) as c FROM shop_returns WHERE user_id = ? AND status = 'approved'");
$stmt->execute([$userId]);
$activeReturns = (int)$stmt->fetch()['c'];

$stmt = $pdo->prepare("SELECT COUNT(*) as c FROM shop_orders WHERE (user_id = ? OR customer_email = ?) AND order_status = 'shipped'");
$stmt->execute([$userId, $userEmail]);
$awaitingConfirmation = (int)$stmt->fetch()['c'];
?>

<style>
    /* =========================================================
       OVERVIEW PAGE
       ========================================================= */
    .ov-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }
    .ov-stat {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        padding: 18px;
        box-shadow: 0 4px 20px rgba(11,16,38,0.05);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ov-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(11,16,38,0.08);
    }
    .ov-stat::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 4px;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
    }
    .ov-stat .icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: linear-gradient(120deg, rgba(37,99,255,0.1), rgba(124,58,237,0.1));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        margin-bottom: 10px;
    }
    .ov-stat .value {
        font-size: clamp(1.4rem, 4vw, 1.7rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1;
        margin-bottom: 4px;
        color: var(--ink, #0B1026);
    }
    .ov-stat .label {
        font-size: 0.8rem;
        color: var(--ink-mute, #5A6180);
        font-weight: 500;
    }

    /* Quick actions */
    .ov-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .ov-action {
        background: #fff;
        border: 1px solid var(--line, rgba(11,16,38,0.1));
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.25s;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 10px rgba(11,16,38,0.03);
    }
    .ov-action:hover {
        border-color: var(--blue, #2563FF);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37,99,255,0.1);
    }
    .ov-action .icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .ov-action .info strong {
        display: block;
        font-size: 0.92rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: var(--ink, #0B1026);
    }
    .ov-action .info span {
        font-size: 0.78rem;
        color: var(--ink-mute, #5A6180);
    }

    /* Action alert */
    .ov-alert {
        background: linear-gradient(120deg, rgba(37,99,255,0.06), rgba(124,58,237,0.06));
        border: 1px solid rgba(37,99,255,0.15);
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .ov-alert.warn {
        background: linear-gradient(120deg, rgba(245,158,11,0.06), rgba(239,68,68,0.04));
        border-color: rgba(245,158,11,0.2);
    }
    .ov-alert .alert-icon {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .ov-alert.warn .alert-icon {
        background: linear-gradient(120deg, #f59e0b, #ef4444);
    }
    .ov-alert .alert-body { flex: 1; min-width: 180px; }
    .ov-alert strong {
        display: block;
        font-weight: 700;
        font-size: 0.92rem;
        margin-bottom: 2px;
        color: var(--ink, #0B1026);
    }
    .ov-alert p {
        color: var(--ink-mute, #5A6180);
        font-size: 0.83rem;
        margin: 0;
    }
    .ov-alert .alert-btn {
        padding: 8px 18px;
        background: linear-gradient(120deg,#2563FF,#7C3AED);
        color: #fff;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.82rem;
        text-decoration: none;
        white-space: nowrap;
    }

    /* Recent order row — responsive: stacked on mobile */
    .ov-order {
        display: grid;
        grid-template-columns: 44px 1fr auto auto;
        gap: 16px;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid var(--line, rgba(11,16,38,0.1));
    }
    .ov-order:last-child { border-bottom: none; padding-bottom: 0; }
    .ov-order .o-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: linear-gradient(120deg, rgba(37,99,255,0.1), rgba(124,58,237,0.1));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .ov-order .o-info { min-width: 0; }
    .ov-order .o-info strong {
        display: block;
        font-weight: 700;
        font-size: 0.92rem;
        margin-bottom: 3px;
        color: var(--ink, #0B1026);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ov-order .o-info span {
        font-size: 0.78rem;
        color: var(--ink-mute, #5A6180);
    }
    .ov-order .o-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .o-status.pending    { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .o-status.confirmed  { background: rgba(37,99,255,0.1);  color: #2563FF; }
    .o-status.processing { background: rgba(124,58,237,0.1); color: #7C3AED; }
    .o-status.shipped    { background: rgba(34,211,238,0.1); color: #06b6d4; }
    .o-status.delivered  { background: rgba(16,185,129,0.1); color: #10b981; }
    .o-status.cancelled  { background: rgba(239,68,68,0.1);  color: #ef4444; }
    .o-status.returned   { background: rgba(124,58,237,0.1); color: #7C3AED; }
    .o-status.refunded   { background: rgba(16,185,129,0.15); color: #10b981; }

    .ov-order .o-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ov-order .o-total {
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--blue, #2563FF);
        white-space: nowrap;
    }
    .ov-order .o-view {
        padding: 8px 14px;
        border-radius: 8px;
        background: var(--light, #F7F8FC);
        color: var(--ink, #0B1026);
        font-weight: 600;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .ov-order .o-view:hover {
        background: var(--blue, #2563FF);
        color: #fff;
    }

    /* Mobile: stack the order row */
    @media (max-width: 640px) {
        .ov-order {
            grid-template-columns: 44px 1fr;
            grid-template-areas:
                "icon info"
                "actions actions";
            gap: 12px;
            padding: 16px 0;
        }
        .ov-order .o-icon { grid-area: icon; }
        .ov-order .o-info { grid-area: info; }
        .ov-order .o-actions {
            grid-area: actions;
            justify-content: space-between;
            width: 100%;
        }
        .ov-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .ov-actions {
            grid-template-columns: 1fr;
        }
        .ov-alert {
            flex-direction: column;
            text-align: center;
        }
        .ov-alert .alert-icon { align-self: center; }
        .ov-alert .alert-btn { width: 100%; text-align: center; }
    }
    @media (max-width: 400px) {
        .ov-stats { grid-template-columns: 1fr; }
        .ov-order .o-actions { flex-wrap: wrap; gap: 8px; }
    }
</style>

<div class="acct-page-header">
    <h1>Welcome back, <?php echo htmlspecialchars(explode(' ', $accountUser['full_name'])[0]); ?> 👋</h1>
    <p>Here's what's happening with your orders.</p>
</div>

<?php if ($awaitingConfirmation > 0): ?>
<div class="ov-alert">
    <div class="alert-icon">📦</div>
    <div class="alert-body">
        <strong>Delivery confirmation needed</strong>
        <p><?php echo $awaitingConfirmation; ?> order<?php echo $awaitingConfirmation > 1 ? 's' : ''; ?> awaiting your confirmation.</p>
    </div>
    <a href="orders.php?status=shipped" class="alert-btn">View</a>
</div>
<?php endif; ?>

<?php if ($activeReturns > 0): ?>
<div class="ov-alert warn">
    <div class="alert-icon">↩️</div>
    <div class="alert-body">
        <strong>Return in progress</strong>
        <p><?php echo $activeReturns; ?> approved return<?php echo $activeReturns > 1 ? 's' : ''; ?> awaiting shipment.</p>
    </div>
    <a href="returns.php" class="alert-btn">View</a>
</div>
<?php endif; ?>

<!-- STATS -->
<div class="ov-stats">
    <div class="ov-stat">
        <div class="icon">📦</div>
        <div class="value"><?php echo $stats['total_orders']; ?></div>
        <div class="label">Total Orders</div>
    </div>
    <div class="ov-stat">
        <div class="icon">🚚</div>
        <div class="value"><?php echo $stats['active']; ?></div>
        <div class="label">Active Orders</div>
    </div>
    <div class="ov-stat">
        <div class="icon">✅</div>
        <div class="value"><?php echo $stats['delivered']; ?></div>
        <div class="label">Delivered</div>
    </div>
    <div class="ov-stat">
        <div class="icon">💰</div>
        <div class="value" style="font-size:1.15rem;">UGX <?php echo number_format($stats['total_spent']); ?></div>
        <div class="label">Total Spent</div>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="ov-actions">
    <a href="orders.php" class="ov-action">
        <div class="icon">📦</div>
        <div class="info">
            <strong>My Orders</strong>
            <span>View order history</span>
        </div>
    </a>
    <a href="returns.php" class="ov-action">
        <div class="icon">↩️</div>
        <div class="info">
            <strong>Returns</strong>
            <span>Track return requests</span>
        </div>
    </a>
    <a href="profile.php" class="ov-action">
        <div class="icon">👤</div>
        <div class="info">
            <strong>My Profile</strong>
            <span>Update your details</span>
        </div>
    </a>
    <a href="../index.php" class="ov-action">
        <div class="icon">🛍️</div>
        <div class="info">
            <strong>Shop Now</strong>
            <span>Browse products</span>
        </div>
    </a>
</div>

<!-- RECENT ORDERS -->
<div class="acct-card">
    <h2>
        Recent Orders
        <?php if (count($recentOrders) > 0): ?>
            <a href="orders.php">View all →</a>
        <?php endif; ?>
    </h2>

    <?php if (empty($recentOrders)): ?>
        <div style="text-align:center;padding:40px 16px;color:var(--ink-mute);">
            <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">📭</div>
            <p style="margin-bottom:16px;">You haven't placed any orders yet.</p>
            <a href="../index.php" style="display:inline-block;padding:12px 24px;background:linear-gradient(120deg,#2563FF,#7C3AED);color:#fff;border-radius:10px;font-weight:600;text-decoration:none;">
                Start Shopping →
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($recentOrders as $order): ?>
            <div class="ov-order">
                <div class="o-icon">📦</div>
                <div class="o-info">
                    <strong><?php echo htmlspecialchars($order['order_number']); ?></strong>
                    <span><?php echo date('M j, Y', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="o-status <?php echo htmlspecialchars($order['order_status']); ?>">
                    <?php echo htmlspecialchars($order['order_status']); ?>
                </div>
                <div class="o-actions">
                    <span class="o-total">UGX <?php echo number_format($order['total']); ?></span>
                    <a href="order-details.php?id=<?php echo (int)$order['id']; ?>" class="o-view">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>