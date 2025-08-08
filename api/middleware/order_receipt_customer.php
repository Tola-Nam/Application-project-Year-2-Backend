<?php
session_start();

// Allow CORS from a specific frontend (adjust if needed)
header("Access-Control-Allow-Origin: http://localhost:5173"); // use your actual frontend origin
header("Access-Control-Allow-Credentials: true");

// Allow headers and methods
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Include database connection
require_once('./connection.php');
$pdo = connection();
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input exists
if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// Validate required fields
$errors = [];
$required = ['firstName', 'lastName', 'email', 'password'];

foreach ($required as $field) {
    if (empty($data[$field])) {
        $errors[$field] = 'This field is required';
    }
}

// Email validation
if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email format';
}

// Password validation
if (strlen($data['password'] ?? '') < 8) {
    $errors['password'] = 'Password must be at least 8 characters';
}

// Return errors if any
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $data['email']]);

    if ($stmt->fetch()) {
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'errors' => ['email' => 'Email already exists']]);
        exit;
    }

    // Create user
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)");
    $stmt->execute([
        ':firstName' => $data['firstName'],
        ':lastName' => $data['lastName'],
        ':email' => $data['email'],
        ':password' => $hashedPassword
    ]);

    // Set session
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_email'] = $data['email'];

    // Successful response
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'email' => $data['email'],
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName']
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Registration failed']);
}