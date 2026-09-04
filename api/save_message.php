<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$company = trim($data['company'] ?? '');
$service = trim($data['service'] ?? '');
$service_description = trim($data['service_description'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($service) || empty($service_description)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Database connection
require_once 'db-connection.php';

try {
    $pdo = getConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    // Prepare SQL statement
    $stmt = $pdo->prepare("
        INSERT INTO messages (name, email, phone, company, service, service_description)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    // Execute with parameters
    $stmt->execute([
        $name,
        $email,
        $phone ?: null,
        $company ?: null,
        $service,
        $service_description
    ]);
    
    // Get the inserted ID
    $messageId = $pdo->lastInsertId();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Message saved successfully',
        'id' => $messageId
    ]);
    
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save message. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>