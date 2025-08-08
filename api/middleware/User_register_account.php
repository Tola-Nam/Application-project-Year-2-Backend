<?php
session_start();
// Allow CORS from a specific frontend (adjust if needed)
header("Access-Control-Allow-Origin: http://localhost:5173"); // use your actual frontend origin
header("Access-Control-Allow-Credentials: true");

// Allow headers and methods
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');


// Database configuration
//$pdo = databaseConfiguration();
$host = 'localhost';
$dbname = 'applicationprojectbackendruppy2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}
// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['first_name']) || !isset($input['last_name']) ||
    !isset($input['gender']) || !isset($input['phone_number']) ||
    !isset($input['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Sanitize and validate data
$firstName = trim($input['first_name']);
$lastName = trim($input['last_name']);
$gender = trim($input['gender']);
$phoneNumber = trim($input['phone_number']);
$userPassword = $input['password'];

// Validate gender
$allowedGenders = ['Male', 'Female'];
if (!in_array($gender, $allowedGenders)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid gender value']);
    exit;
}

// Validate phone number (basic validation)
if (!preg_match('/^[0-9]{10,15}$/', $phoneNumber)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'mes  sage' => 'Invalid phone number format']);
    exit;
}

// Validate password strength
if (strlen($userPassword) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit;
}

// Check if phone number already exists
try {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE Phone_number = :phone_number");
    $stmt->execute(['phone_number' => $phoneNumber]);
    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(
            ['success' => false,
             'message' => 'Phone number already registered'
            ]);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

// Hash password
$hashedPassword = password_hash($userPassword, PASSWORD_BCRYPT);

// Insert new user
try {
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, gender, Phone_number, password) 
                          VALUES (:first_name, :last_name, :gender, :phone_number, :password)");
    $stmt->execute([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'gender' => $gender,
        'phone_number' => $phoneNumber,
        'password' => $hashedPassword
    ]);

    // Get the newly created user ID
    $userId = $pdo->lastInsertId();

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful',
        'user_id' => $userId
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Registration failed']);
}
