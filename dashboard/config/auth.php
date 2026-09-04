<?php
session_start();

require_once 'db-connection.php';

// Authentication functions (no password hashing)
function loginUser($username, $password) {
    $pdo = getConnection();
    
    if (!$pdo) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Direct comparison - no hashing
    $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND password = ? AND is_active = 1");
    $stmt->execute([$username, $username, $password]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Update last login
        $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->execute([$user['id']]);
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        
        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ];
    }
    
    return ['success' => false, 'message' => 'Invalid username or password'];
}

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function logoutUser() {
    session_unset();
    session_destroy();
    return ['success' => true, 'message' => 'Logged out successfully'];
}

function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

function createUser($username, $email, $password, $full_name, $role = 'admin') {
    $pdo = getConnection();
    
    if (!$pdo) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Check if user already exists
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkStmt->execute([$username, $email]);
    
    if ($checkStmt->fetch()) {
        return ['success' => false, 'message' => 'Username or email already exists'];
    }
    
    // Store password as plain text (no hashing)
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, full_name, role)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([$username, $email, $password, $full_name, $role]);
    
    return ['success' => true, 'message' => 'User created successfully'];
}
?>