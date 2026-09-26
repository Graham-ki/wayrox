<?php
//session_start();
require_once 'config/auth.php';

// Check if user is logged in
requireLogin();

// Get current user
$currentUser = getCurrentUser();

require_once 'db-connection.php';
$pdo = getConnection();

// Fetch recent messages (latest 5)
$stmt = $pdo->prepare("
    SELECT id, name, email, service, is_read, is_important, created_at 
    FROM messages 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute();
$recentMessages = $stmt->fetchAll();

// Get message counts
$stmtUnread = $pdo->prepare("SELECT COUNT(id) as total FROM messages WHERE is_read = 0");
$stmtUnread->execute();
$unreadCount = $stmtUnread->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — WayronX Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* ============ DASHBOARD STYLES ============ */
    :root {
        --midnight: #050816;
        --navy: #0B1026;
        --blue: #2563FF;
        --purple: #7C3AED;
        --cyan: #22D3EE;
        --white: #FFFFFF;
        --light: #F7F8FC;
        --ink: #0B1026;
        --ink-mute: #5A6180;
        --line-dark: rgba(255,255,255,0.10);
        --line-light: rgba(11,16,38,0.10);
        --grad: linear-gradient(120deg, #2563FF, #7C3AED);
        --sidebar-width: 260px;
        --header-height: 70px;
        --bg-main: #f1f5f9;
        --bg-card: #ffffff;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --border-color: #e2e8f0;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Manrope', sans-serif;
        background: var(--bg-main);
        color: var(--text-primary);
        overflow-x: hidden;
    }
    
    a {
        text-decoration: none;
        color: inherit;
    }
    
    ul {
        list-style: none;
    }
    
    /* Loading Screen */
    .loading-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--midnight);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s, visibility 0.5s;
    }
    
    .loading-screen.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
    
    .loader {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(255,255,255,0.1);
        border-top: 4px solid var(--blue);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Dashboard Layout */
    .dashboard-container {
        display: flex;
        min-height: 100vh;
    }
    
    /* Sidebar */
    .sidebar {
        width: var(--sidebar-width);
        background: var(--midnight);
        color: #fff;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1000;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid var(--line-dark);
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: var(--header-height);
    }
    
    .sidebar-header .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        font-weight: 800;
        color: #fff;
    }
    
    .sidebar-header .logo svg {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
    }
    
    .sidebar-nav {
        flex: 1;
        padding: 20px 0;
        overflow-y: auto;
    }
    
    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sidebar-nav li {
        margin-bottom: 5px;
    }
    
    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: rgba(255,255,255,0.7);
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
    }
    
    .sidebar-nav a:hover,
    .sidebar-nav a.active {
        background: rgba(255,255,255,0.1);
        color: #fff;
    }
    
    .sidebar-nav a.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: var(--grad);
    }
    
    .sidebar-nav .icon {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
        flex-shrink: 0;
    }
    
    .sidebar-nav .badge {
        margin-left: auto;
        background: var(--danger);
        color: #fff;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid var(--line-dark);
    }
    
    .sidebar-footer a {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255,255,255,0.7);
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .sidebar-footer a:hover {
        color: #fff;
    }
    
    /* Main Content */
    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        padding: 20px;
        min-height: 100vh;
        width: calc(100% - var(--sidebar-width));
    }
    
    /* Top Bar */
    .top-bar {
        background: var(--bg-card);
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .top-bar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .menu-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.5rem;
        color: var(--text-primary);
        padding: 5px;
    }
    
    .search-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-main);
        padding: 10px 15px;
        border-radius: 8px;
        width: 300px;
    }
    
    .search-bar input {
        border: none;
        background: none;
        outline: none;
        font-family: 'Manrope', sans-serif;
        font-size: 0.95rem;
        width: 100%;
    }
    
    .top-bar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .notification-btn {
        position: relative;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.5rem;
        padding: 5px;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--danger);
        color: #fff;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    /* User Dropdown */
    .user-profile-wrapper {
        position: relative;
    }
    
    .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .user-profile:hover {
        background: var(--bg-main);
    }
    
    .user-profile .dropdown-arrow {
        font-size: 0.7rem;
        color: var(--text-secondary);
        transition: transform 0.3s ease;
    }
    
    .user-profile-wrapper.active .dropdown-arrow {
        transform: rotate(180deg);
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .user-info h4 {
        font-size: 0.9rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
    }
    
    .user-info p {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin: 0;
    }
    
    .user-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: var(--bg-card);
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        min-width: 220px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 1000;
        overflow: hidden;
        margin-top: 10px;
    }
    
    .user-profile-wrapper.active .user-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .dropdown-header {
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--bg-main);
    }
    
    .user-avatar-small {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .dropdown-header h4 {
        font-size: 0.9rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
    }
    
    .dropdown-header p {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin: 0;
    }
    
    .dropdown-divider {
        height: 1px;
        background: var(--border-color);
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        color: var(--text-primary);
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .dropdown-item:hover {
        background: var(--bg-main);
        padding-left: 25px;
    }
    
    .dropdown-item.logout {
        color: var(--danger);
    }
    
    .dropdown-item.logout:hover {
        background: rgba(239,68,68,0.05);
    }
    
    .dropdown-item span {
        font-size: 1.1rem;
    }
    
    /* Stats Cards */
    .stats-grid-dash {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card-dash {
        background: var(--bg-card);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-dash:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .stat-card-dash::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--grad);
    }
    
    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .stat-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-card-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 5px;
    }
    
    .stat-card-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 500;
    }
    
    .stat-card-change {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 5px;
    }
    
    .stat-card-change.positive {
        color: var(--success);
        background: rgba(16,185,129,0.1);
    }
    
    .stat-card-change.negative {
        color: var(--danger);
        background: rgba(239,68,68,0.1);
    }
    
    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .dashboard-card {
        background: var(--bg-card);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
    }
    
    .dashboard-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text-primary);
    }
    
    /* Table */
    .table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        text-align: left;
        padding: 12px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-secondary);
        border-bottom: 2px solid var(--border-color);
    }
    
    .data-table td {
        padding: 12px;
        font-size: 0.9rem;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-color);
    }
    
    .data-table tbody tr:hover {
        background: var(--bg-main);
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-badge.active {
        background: rgba(16,185,129,0.1);
        color: var(--success);
    }
    
    .status-badge.pending {
        background: rgba(245,158,11,0.1);
        color: var(--warning);
    }
    
    .status-badge.inactive {
        background: rgba(239,68,68,0.1);
        color: var(--danger);
    }
    
    /* Activity List */
    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .activity-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .activity-content h4 {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0 0 5px;
        color: var(--text-primary);
    }
    
    .activity-content p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin: 0;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
    
    /* Overlay for mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 999;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 15px;
        }
        
        .menu-toggle {
            display: block;
        }
        
        .search-bar {
            width: 150px;
        }
        
        .user-info {
            display: none;
        }
        
        .stats-grid-dash {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>
<body>

<div class="loading-screen"><div class="loader"></div></div>

<div class="dashboard-container">
    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="index.html" class="logo">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4 4L20 20M20 4L4 20" stroke="url(#sideX)" stroke-width="2.6" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="sideX" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2563FF"/>
                            <stop offset="1" stop-color="#7C3AED"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span>WayronX Admin</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
        <ul>
            <li><a href="index.php"><span class="icon">📊</span>Overview</a></li>
            <li><a href="#"><span class="icon">👥</span>Users</a></li>
            <li><a href="messages.php"><span class="icon">✉️</span>Messages <?php if ($unreadCount > 0): ?><span class="badge"><?php echo $unreadCount; ?></span><?php endif; ?></a></li>
            <li><a href="#"><span class="icon">📝</span>Blog Posts</a></li>
            <li><a href="#"><span class="icon">🛠️</span>Services</a></li>
            <li><a href="shop-products.php"><span class="icon">🛍️</span>Shop Products</a></li>
            <li><a href="shop-orders.php" class="active"><span class="icon">📦</span>Shop Orders</a></li>
            <li><a href="shop-categories.php"><span class="icon">📁</span>Categories</a></li>
            <li><a href="shop-delivery.php"><span class="icon">🚚</span>Delivery Regions</a></li>
            <li><a href="shop-settings.php"><span class="icon">⚙️</span>Shop Settings</a></li>
            <li><a href="#"><span class="icon">💡</span>Solutions</a></li>
            <li><a href="#"><span class="icon">💼</span>Careers</a></li>
            <li><a href="#"><span class="icon">👨‍💼</span>Team (HRM)</a></li>
            <li><a href="#"><span class="icon">💰</span>Finances</a></li>
            <li><a href="#"><span class="icon">📈</span>Analytics</a></li>
            <li><a href="#"><span class="icon">⚙️</span>Settings</a></li>
        </ul>
    </nav>
        
        <div class="sidebar-footer">
            <a href="https://wayronx.com/"><span>🏠</span> Back to Website</a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <button class="menu-toggle" id="menuToggle">☰</button>
                <div class="search-bar">
                    <span>🔍</span>
                    <input type="text" placeholder="Search...">
                </div>
            </div>
            
            <div class="top-bar-right">
                <button class="notification-btn">
                    🔔
                    <span class="notification-badge"><?php
                      		require_once 'db-connection.php';

							$pdo = getConnection();

						// Count all messages
							$stmt = $pdo->prepare("SELECT COUNT(id) as total FROM messages");
							$stmt->execute();
							$result = $stmt->fetch();
							$totalMessages = $result['total'];

							echo $totalMessages;
                      ?></span>
                </button>
                
                <div class="user-profile-wrapper" id="userProfileWrapper">
                    <div class="user-profile" id="userProfile">
                        <div class="user-avatar"> <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?></div>
                        <div class="user-info">
                            <h4><?php echo htmlspecialchars($currentUser['full_name']); ?></h4>
                <p><?php echo htmlspecialchars(ucfirst($currentUser['role'])); ?></p>
                        </div>
                        <span class="dropdown-arrow">▼</span>
                    </div>
                    
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="user-avatar-small"><?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?></div>
                            <div>
                                <h4><?php echo htmlspecialchars($currentUser['full_name']); ?></h4>
                                <p><?php echo htmlspecialchars(ucfirst($currentUser['role'])); ?></p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" data-action="profile">
                            <span>👤</span> Profile
                        </a>
                        <a href="#" class="dropdown-item" data-action="settings">
                            <span>⚙️</span> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="api/logout.php" class="dropdown-item logout" data-action="logout">
                            <span>🚪</span> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div id="dashboardContent">
            <!-- Stats Cards -->
            <div class="stats-grid-dash">
                <div class="stat-card-dash">
                    <div class="stat-card-header">
                        <div class="stat-card-icon" style="background: rgba(37,99,255,0.1);">👥</div>
                        <span class="stat-card-change positive">+12%</span>
                    </div>
                    <div class="stat-card-value">0</div>
                    <div class="stat-card-label">Total Users</div>
                </div>
                
                <div class="stat-card-dash">
                    <div class="stat-card-header">
                        <div class="stat-card-icon" style="background: rgba(124,58,237,0.1);">📝</div>
                        <span class="stat-card-change positive">+0%</span>
                    </div>
                    <div class="stat-card-value">0</div>
                    <div class="stat-card-label">Blog Posts</div>
                </div>
                
                <div class="stat-card-dash">
                    <div class="stat-card-header">
                        <div class="stat-card-icon" style="background: rgba(34,211,238,0.1);">💼</div>
                        <span class="stat-card-change positive">+0%</span>
                    </div>
                    <div class="stat-card-value">0</div>
                    <div class="stat-card-label">Active Projects</div>
                </div>
                
                <div class="stat-card-dash">
                    <div class="stat-card-header">
                        <div class="stat-card-icon" style="background: rgba(16,185,129,0.1);">💰</div>
                        <span class="stat-card-change positive">+0%</span>
                    </div>
                    <div class="stat-card-value">UGX 0</div>
                    <div class="stat-card-label">Revenue (Monthly)</div>
                </div>
            </div>
            
            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Recent Messages -->
                <div class="dashboard-card">
                    <h3>Recent Messages</h3>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                           		 <tbody>
                <?php if (empty($recentMessages)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px;">
                            <p>No messages found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentMessages as $message): ?>
                        <tr onclick="window.location.href='messages.php?id=<?php echo $message['id']; ?>'" style="cursor: pointer;">
                            <td>
                                <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                <?php if ($message['is_important'] == 1): ?>
                                    <span title="Important">⭐</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                            <td><?php echo htmlspecialchars($message['service'] ?? 'General Inquiry'); ?></td>
                            <td>
                                <?php if ($message['is_read'] == 0): ?>
                                    <span class="status-badge active">New</span>
                                <?php else: ?>
                                    <span class="status-badge inactive">Read</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <h3>Recent Activity</h3>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <!--<div class="activity-icon" style="background: rgba(37,99,255,0.1);">📝</div>-->
                            <div class="activity-content">
                                <h4>No activity yet!</h4>
                               <!-- <p>10 Web Development Trends</p>
                                <span class="activity-time">2 hours ago</span>-->
                            </div>
                        </li>
                        <!--<li class="activity-item">
                            <div class="activity-icon" style="background: rgba(124,58,237,0.1);">👥</div>
                            <div class="activity-content">
                                <h4>New user registered</h4>
                                <p>Emily Davis joined the platform</p>
                                <span class="activity-time">5 hours ago</span>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon" style="background: rgba(16,185,129,0.1);">💰</div>
                            <div class="activity-content">
                                <h4>Payment received</h4>
                                <p>$5,000 from TechCorp</p>
                                <span class="activity-time">1 day ago</span>
                            </div>
                        </li>-->
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Loading Screen
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.querySelector('.loading-screen').classList.add('hidden');
        }, 500);
    });
    
    // Mobile Menu Toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
    });
    
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });
    
    // Notification button
    document.querySelector('.notification-btn').addEventListener('click', function() {
        alert('You have 3 new notifications');
    });
    
    // User Dropdown
    const userProfileWrapper = document.getElementById('userProfileWrapper');
    const userProfile = document.getElementById('userProfile');
    
    if (userProfile && userProfileWrapper) {
        // Toggle dropdown on click
        userProfile.addEventListener('click', (e) => {
            e.stopPropagation();
            userProfileWrapper.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userProfileWrapper.contains(e.target)) {
                userProfileWrapper.classList.remove('active');
            }
        });
        
        // Handle dropdown item clicks
        userProfileWrapper.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const action = item.getAttribute('data-action');
                
                if (action === 'logout') {
                    if (confirm('Are you sure you want to logout?')) {
                        window.location.href = 'api/logout.php';
                    }
                } else if (action === 'settings') {
                    alert('Settings page coming soon');
                } else if (action === 'profile') {
                    alert('Profile page coming soon');
                }
                
                userProfileWrapper.classList.remove('active');
            });
        });
        
        // Close dropdown on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                userProfileWrapper.classList.remove('active');
            }
        });
    }
</script>
</body>
</html>