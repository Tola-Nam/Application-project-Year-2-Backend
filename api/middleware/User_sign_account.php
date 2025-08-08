<?php
session_start();

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}



// Must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// Decode JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['phone_number']) || empty($input['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Phone number and password are required']);
    exit();
}

$phone = trim($input['phone_number']);
$password = $input['password'];

// DB connection
$host = 'localhost';
$dbname = 'applicationprojectbackendruppy2';
$username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user exists
    $stmt = $pdo->prepare("SELECT User_id, First_name, Last_name, Phone_number, password FROM users WHERE Phone_number = ?");
    $stmt->execute([$phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate password
    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid phone number or password']);
        exit();
    }

    // Store in session
    $_SESSION['User_id'] = $user['User_id'];
    $_SESSION['First_name'] = $user['First_name'];
    $_SESSION['Last_name'] = $user['Last_name'];
    $_SESSION['Phone_number'] = $user['Phone_number'];

    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'User_id' => $user['User_id'],
            'First_name' => $user['First_name'],
            'Last_name' => $user['Last_name'],
            'Phone_number' => $user['Phone_number'],
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
