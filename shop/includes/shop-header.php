<?php
if (!isset($isLoggedIn)) {
    require_once __DIR__ . '/shop-config.php';
}

// Build redirect target (relative to root)
$scriptPath = $_SERVER['PHP_SELF'] ?? '/shop/index.php';
$baseFile = basename($scriptPath);
$redirectTarget = 'shop/' . $baseFile;
if (!empty($_SERVER['QUERY_STRING'])) {
    $redirectTarget .= '?' . $_SERVER['QUERY_STRING'];
}
?>
<header class="shop-header">
    <div class="shop-header-inner">
        <a href="index.php" class="shop-logo">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 4L20 20M20 4L4 20" stroke="url(#hdrGrad)" stroke-width="2.6" stroke-linecap="round"/>
                <defs>
                    <linearGradient id="hdrGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#2563FF"/>
                        <stop offset="1" stop-color="#7C3AED"/>
                    </linearGradient>
                </defs>
            </svg>
            <span>WayronX Shop</span>
        </a>

        <nav class="shop-nav">
            <a href="index.php" class="<?php echo $shopCurrentPage === 'shop' ? 'active' : ''; ?>">
                <span class="nav-icon">🛍️</span> Shop
            </a>
            <a href="track-order.php" class="<?php echo $shopCurrentPage === 'track' ? 'active' : ''; ?>">
                <span class="nav-icon">🔍</span> Track Order
            </a>
            <?php if ($isLoggedIn): ?>
                <a href="account/orders.php" class="<?php echo $shopCurrentPage === 'orders' ? 'active' : ''; ?>">
                    <span class="nav-icon">📦</span> My Orders
                </a>
            <?php endif; ?>
        </nav>

        <div class="shop-header-actions">
            <a href="cart.php" class="cart-btn" aria-label="Cart">
                <span class="cart-icon">🛒</span>
                <span class="cart-count" id="headerCartCount"><?php echo (int)$shopCartCount; ?></span>
            </a>

            <?php if ($isLoggedIn): ?>
                <div class="user-menu" id="shopUserMenu">
                    <button class="user-btn" onclick="toggleUserMenu(event)">
                        <span class="user-avatar"><?php echo strtoupper(substr($shopUser['full_name'], 0, 1)); ?></span>
                        <span class="user-name"><?php echo htmlspecialchars(explode(' ', $shopUser['full_name'])[0]); ?></span>
                        <span class="dropdown-arrow">▾</span>
                    </button>
                    <div class="user-dropdown" id="shopUserDropdown">
                        <div class="user-dropdown-header">
                            <div class="user-avatar-lg"><?php echo strtoupper(substr($shopUser['full_name'], 0, 1)); ?></div>
                            <div>
                                <strong><?php echo htmlspecialchars($shopUser['full_name']); ?></strong>
                                <span><?php echo htmlspecialchars($shopUser['email']); ?></span>
                            </div>
                        </div>
                        <a href="account/index.php" class="user-menu-item"><span>🏠</span> My Account</a>
                        <a href="account/orders.php" class="user-menu-item"><span>📦</span> My Orders</a>
                        <a href="account/returns.php" class="user-menu-item"><span>↩️</span> Returns</a>
                        <a href="account/profile.php" class="user-menu-item"><span>👤</span> Profile</a>
                        <div class="user-menu-divider"></div>
                        <a href="logout.php" class="user-menu-item logout"><span>🚪</span> Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="login-btn">
                    <span>👤</span> Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
function toggleUserMenu(e) {
    e.stopPropagation();
    const menu = document.getElementById('shopUserMenu');
    if (menu) menu.classList.toggle('open');
}
document.addEventListener('click', (e) => {
    const menu = document.getElementById('shopUserMenu');
    if (menu && !menu.contains(e.target)) menu.classList.remove('open');
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') document.getElementById('shopUserMenu')?.classList.remove('open');
});
</script>