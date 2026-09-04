<?php
session_start();
require_once 'config/auth.php';

// Check if user is logged in
include_once ' db-connection.php';
requireLogin();
$pdo = getConnection();
$stmtUnread = $pdo->prepare("SELECT COUNT(id) as total FROM messages WHERE is_read = 0");
$stmtUnread->execute();
$unreadCount = $stmtUnread->fetch()['total'];
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages — WayronX Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
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
        min-height: 70px;
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
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .top-bar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
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
    
    /* Messages Header */
    .messages-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .messages-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        font-family: 'Manrope', sans-serif;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-danger {
        background: var(--danger);
        color: #fff;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: var(--bg-card);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }
    
    .btn-secondary:hover {
        background: var(--bg-main);
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    /* Messages Filter */
    .messages-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 8px 16px;
        border-radius: 20px;
        border: 2px solid var(--border-color);
        background: var(--bg-card);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        font-family: 'Manrope', sans-serif;
        transition: all 0.3s ease;
        color: var(--text-secondary);
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: var(--grad);
        border-color: transparent;
        color: #fff;
    }
    
    /* Messages List */
    .messages-list {
        background: var(--bg-card);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .message-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    
    .message-item:hover {
        background: var(--bg-main);
    }
    
    .message-item.unread {
        background: rgba(37,99,255,0.03);
    }
    
    .message-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: var(--blue);
    }
    
    .message-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        flex-shrink: 0;
    }
    
    .message-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #fff;
        flex-shrink: 0;
    }
    
    .message-content {
        flex: 1;
        min-width: 0;
    }
    
    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
        gap: 10px;
    }
    
    .message-sender {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-primary);
    }
    
    .message-time {
        font-size: 0.8rem;
        color: var(--text-secondary);
        white-space: nowrap;
    }
    
    .message-subject {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
        margin-bottom: 3px;
    }
    
    .message-preview {
        font-size: 0.85rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .message-actions {
        display: flex;
        gap: 5px;
        flex-shrink: 0;
    }
    
    .action-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 5px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        background: var(--border-color);
    }
    
    .action-btn.delete:hover {
        background: rgba(239,68,68,0.1);
    }
    
    .action-btn.mark-read:hover {
        background: rgba(16,185,129,0.1);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: var(--text-secondary);
    }
    
    /* Select All Checkbox */
    .select-all-container {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-color);
        background: var(--bg-main);
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
        
        .user-info {
            display: none;
        }
        
        .message-item {
            flex-wrap: wrap;
        }
        
        .message-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }
    
    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal {
        background: var(--bg-card);
        border-radius: 16px;
        max-width: 600px;
        width: 100%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-secondary);
        padding: 5px;
        transition: color 0.3s ease;
    }
    
    .modal-close:hover {
        color: var(--danger);
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .message-detail-header {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .message-detail-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        color: #fff;
        flex-shrink: 0;
    }
    
    .message-detail-sender h4 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 5px;
    }
    
    .message-detail-sender p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin: 0;
    }
    
    .message-detail-info {
        background: var(--bg-main);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }
    
    .info-row:last-child {
        margin-bottom: 0;
    }
    
    .info-label {
        font-weight: 600;
        width: 100px;
        color: var(--text-secondary);
        flex-shrink: 0;
    }
    
    .info-value {
        color: var(--text-primary);
    }
    
    .message-detail-body {
        margin-bottom: 20px;
    }
    
    .message-detail-body h5 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .message-detail-body p {
        color: var(--text-primary);
        line-height: 1.7;
        font-size: 0.95rem;
    }
    
    .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    /* Toast */
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--success);
        color: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
</style>
</head>
<body>

<div class="dashboard-container">
    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="logo">
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
                <li><a href="messages.php" class="active"><span class="icon">✉️</span>Messages <span class="badge"><?php echo $unreadCount;?></span></a></li>
                <li><a href="#"><span class="icon">📝</span>Blog Posts</a></li>
                <li><a href="#"><span class="icon">🛠️</span>Services</a></li>
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
                <h1 class="page-title">Messages</h1>
            </div>
            
            <div class="top-bar-right">
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
                            <div class="user-avatar-small">A</div>
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
        
        <!-- Messages Header -->
        <div class="messages-header">
            <div class="messages-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="unread">Unread</button>
                <button class="filter-btn" data-filter="read">Read</button>
                <button class="filter-btn" data-filter="important">Important</button>
            </div>
            
            <div class="messages-actions">
                <button class="btn btn-secondary btn-sm" id="markAllRead">✓ Mark All Read</button>
                <button class="btn btn-danger btn-sm" id="deleteSelected">🗑️ Delete Selected</button>
            </div>
        </div>
        
        <!-- Messages List -->
        <div class="messages-list" id="messagesList">
            <!-- Messages will be loaded here -->
        </div>
    </main>
</div>

<!-- Message Detail Modal -->
<div class="modal-overlay" id="messageModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Message Details</h3>
            <button class="modal-close" id="modalClose">×</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Message content will be loaded here -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" id="modalImportant">☆ Toggle Important</button>
            <button class="btn btn-danger btn-sm" id="modalDelete">🗑️ Delete</button>
        </div>
    </div>
</div>
<script>
    // ============ MOBILE MENU TOGGLE ============
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (menuToggle && sidebar && sidebarOverlay) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
        
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }
    
    // ============ USER DROPDOWN ============
    const userProfileWrapper = document.getElementById('userProfileWrapper');
    const userProfile = document.getElementById('userProfile');
    
    if (userProfile && userProfileWrapper) {
        userProfile.addEventListener('click', (e) => {
            e.stopPropagation();
            userProfileWrapper.classList.toggle('active');
        });
        
        document.addEventListener('click', (e) => {
            if (!userProfileWrapper.contains(e.target)) {
                userProfileWrapper.classList.remove('active');
            }
        });
        
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
    }
    
    // ============ API CONFIGURATION ============
    const API_BASE = 'api/messages.php';
    
    // ============ STATE ============
    let messages = [];
    let selectedMessages = new Set();
    let currentFilter = 'all';
    let currentMessageId = null;
    
    // ============ LOAD MESSAGES ============
    async function loadMessages() {
        try {
            const response = await fetch(`${API_BASE}?filter=${currentFilter}`);
            const result = await response.json();
            
            if (result.success) {
                messages = result.data;
                renderMessages();
            } else {
                showToast(result.message || 'Error loading messages', 'error');
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            showToast('Error loading messages. Check API connection.', 'error');
        }
    }
    
    // ============ RENDER MESSAGES ============
    function renderMessages() {
        const container = document.getElementById('messagesList');
        
        if (!container) return;
        
        let filteredMessages = messages;
        
        if (currentFilter === 'unread') {
            filteredMessages = messages.filter(m => m.is_read == 0);
        } else if (currentFilter === 'read') {
            filteredMessages = messages.filter(m => m.is_read == 1);
        } else if (currentFilter === 'important') {
            filteredMessages = messages.filter(m => m.is_important == 1);
        }
        
        if (filteredMessages.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>No messages found</h3>
                    <p>There are no ${currentFilter} messages at the moment.</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <div class="select-all-container">
                <input type="checkbox" id="selectAll" class="message-checkbox" onchange="toggleSelectAll(this)">
                <label for="selectAll">Select All</label>
            </div>
            ${filteredMessages.map(msg => `
                <div class="message-item ${msg.is_read == 1 ? '' : 'unread'}" data-id="${msg.id}">
                    <input type="checkbox" class="message-checkbox" onchange="toggleSelect(${msg.id}, this)" ${selectedMessages.has(parseInt(msg.id)) ? 'checked' : ''}>
                    <div class="message-avatar" style="background: ${getAvatarColor(msg.name)}">
                        ${msg.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="message-content" onclick="openMessage(${msg.id})">
                        <div class="message-header">
                            <span class="message-sender">${msg.name}</span>
                            <span class="message-time">${formatDate(msg.created_at)}</span>
                        </div>
                        <div class="message-subject">${msg.is_important == 1 ? '⭐ ' : ''}${msg.service || 'General Inquiry'}</div>
                        <div class="message-preview">${msg.service_description || ''}</div>
                    </div>
                    <div class="message-actions">
                        <button class="action-btn mark-read" onclick="toggleRead(${msg.id})" title="Mark as ${msg.is_read == 1 ? 'unread' : 'read'}">
                            ${msg.is_read == 1 ? '📖' : '✅'}
                        </button>
                        <button class="action-btn" onclick="toggleImportant(${msg.id})" title="Toggle important">
                            ${msg.is_important == 1 ? '⭐' : '☆'}
                        </button>
                        <button class="action-btn delete" onclick="deleteMessage(${msg.id})" title="Delete">
                            🗑️
                        </button>
                    </div>
                </div>
            `).join('')}
        `;
    }
    
    // ============ OPEN MESSAGE DETAIL ============
    async function openMessage(id) {
        try {
            const response = await fetch(`${API_BASE}?id=${id}`);
            const result = await response.json();
            
            if (result.success) {
                const msg = result.data;
                currentMessageId = parseInt(msg.id);
                
                // Mark as read if unread
                if (msg.is_read == 0) {
                    await toggleRead(id);
                }
                
                const modalBody = document.getElementById('modalBody');
                
                modalBody.innerHTML = `
                    <div class="message-detail-header">
                        <div class="message-detail-avatar" style="background: ${getAvatarColor(msg.name)}">
                            ${msg.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="message-detail-sender">
                            <h4>${msg.name}</h4>
                            <p>${msg.email}</p>
                        </div>
                    </div>
                    
                    <div class="message-detail-info">
                        ${msg.phone ? `
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">${msg.phone}</span>
                        </div>
                        ` : ''}
                        ${msg.company ? `
                        <div class="info-row">
                            <span class="info-label">Company:</span>
                            <span class="info-value">${msg.company}</span>
                        </div>
                        ` : ''}
                        <div class="info-row">
                            <span class="info-label">Service:</span>
                            <span class="info-value">${msg.service || 'General Inquiry'}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Received:</span>
                            <span class="info-value">${formatDate(msg.created_at)}</span>
                        </div>
                    </div>
                    
                    <div class="message-detail-body">
                        <h5>Message</h5>
                        <p>${msg.service_description || 'No additional details provided.'}</p>
                    </div>
                `;
                
                document.getElementById('messageModal').classList.add('active');
            }
        } catch (error) {
            console.error('Error opening message:', error);
            showToast('Error loading message', 'error');
        }
    }
    
    // ============ CLOSE MODAL ============
    function closeModal() {
        const modal = document.getElementById('messageModal');
        if (modal) {
            modal.classList.remove('active');
        }
        currentMessageId = null;
    }
    
    // ============ DELETE SINGLE MESSAGE ============
    async function deleteMessage(id) {
        if (!confirm('Are you sure you want to delete this message?')) {
            return;
        }
        
        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    action: 'delete',
                    id: parseInt(id) 
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                messages = messages.filter(m => parseInt(m.id) !== parseInt(id));
                selectedMessages.delete(parseInt(id));
                renderMessages();
                showToast('Message deleted successfully', 'success');
            } else {
                showToast(result.message || 'Failed to delete message', 'error');
            }
        } catch (error) {
            console.error('Error deleting message:', error);
            showToast('Network error. Could not delete message.', 'error');
        }
    }
    
    // ============ DELETE SELECTED MESSAGES ============
    async function deleteSelectedMessages() {
        if (selectedMessages.size === 0) {
            alert('Please select at least one message to delete');
            return;
        }
        
        const idsToDelete = Array.from(selectedMessages);
        
        if (!confirm(`Are you sure you want to delete ${idsToDelete.length} selected message(s)?`)) {
            return;
        }
        
        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    action: 'delete-multiple',
                    ids: idsToDelete 
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                messages = messages.filter(m => !selectedMessages.has(parseInt(m.id)));
                selectedMessages.clear();
                renderMessages();
                showToast(`${result.deleted || idsToDelete.length} message(s) deleted successfully`, 'success');
            } else {
                showToast(result.message || 'Failed to delete messages', 'error');
            }
        } catch (error) {
            console.error('Error deleting messages:', error);
            showToast('Network error. Could not delete messages.', 'error');
        }
    }
    
    // ============ TOGGLE READ STATUS ============
    async function toggleRead(id) {
        try {
            const msg = messages.find(m => parseInt(m.id) === parseInt(id));
            if (!msg) return;
            
            const newReadStatus = msg.is_read == 1 ? 0 : 1;
            
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    action: 'update',
                    id: parseInt(id), 
                    is_read: newReadStatus 
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                msg.is_read = newReadStatus;
                renderMessages();
            }
        } catch (error) {
            console.error('Error updating message:', error);
        }
    }
    
    // ============ TOGGLE IMPORTANT STATUS ============
    async function toggleImportant(id) {
        try {
            const msg = messages.find(m => parseInt(m.id) === parseInt(id));
            if (!msg) return;
            
            const newImportantStatus = msg.is_important == 1 ? 0 : 1;
            
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    action: 'update',
                    id: parseInt(id), 
                    is_important: newImportantStatus 
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                msg.is_important = newImportantStatus;
                renderMessages();
                showToast(newImportantStatus == 1 ? 'Marked as important' : 'Removed from important', 'success');
            }
        } catch (error) {
            console.error('Error updating message:', error);
        }
    }
    
    // ============ MARK ALL AS READ ============
    async function markAllRead() {
        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    action: 'mark-all-read'
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                messages.forEach(m => m.is_read = 1);
                renderMessages();
                showToast(`${result.updated || messages.length} message(s) marked as read`, 'success');
            }
        } catch (error) {
            console.error('Error marking all read:', error);
            showToast('Network error. Could not update messages.', 'error');
        }
    }
    
    // ============ HELPER FUNCTIONS ============
    function getAvatarColor(name) {
        const colors = ['#2563FF', '#7C3AED', '#22D3EE', '#10B981', '#F59E0B', '#EF4444', '#EC4899', '#8B5CF6'];
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    }
    
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return `${Math.floor(diff / 60000)} minutes ago`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} hours ago`;
        if (diff < 604800000) return `${Math.floor(diff / 86400000)} days ago`;
        
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        if (type === 'error') {
            toast.style.background = 'var(--danger)';
        }
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    function toggleSelectAll(checkbox) {
        selectedMessages.clear();
        if (checkbox.checked) {
            messages.forEach(m => selectedMessages.add(parseInt(m.id)));
        }
        renderMessages();
    }
    
    function toggleSelect(id, checkbox) {
        const numericId = parseInt(id);
        if (checkbox.checked) {
            selectedMessages.add(numericId);
        } else {
            selectedMessages.delete(numericId);
        }
    }
    
    // ============ EVENT LISTENERS ============
    document.addEventListener('DOMContentLoaded', () => {
        loadMessages();
        
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.getAttribute('data-filter');
                loadMessages();
            });
        });
        
        const modalClose = document.getElementById('modalClose');
        const messageModal = document.getElementById('messageModal');
        
        if (modalClose && messageModal) {
            modalClose.addEventListener('click', closeModal);
            messageModal.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    closeModal();
                }
            });
        }
        
        const modalImportant = document.getElementById('modalImportant');
        const modalDelete = document.getElementById('modalDelete');
        
        if (modalImportant) {
            modalImportant.addEventListener('click', async () => {
                if (currentMessageId) {
                    await toggleImportant(currentMessageId);
                    closeModal();
                }
            });
        }
        
        if (modalDelete) {
            modalDelete.addEventListener('click', async () => {
                if (currentMessageId) {
                    await deleteMessage(currentMessageId);
                    closeModal();
                }
            });
        }
        
        const markAllReadBtn = document.getElementById('markAllRead');
        const deleteSelectedBtn = document.getElementById('deleteSelected');
        
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', markAllRead);
        }
        
        if (deleteSelectedBtn) {
            deleteSelectedBtn.addEventListener('click', deleteSelectedMessages);
        }
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    });
</script>
</body>
</html>