<?php
session_start();

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['User_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$host = 'localhost';
$dbname = 'applicationprojectbackendruppy2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['pro_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Product ID (pro_id) is required']);
    exit();
}

$user_id = $_SESSION['User_id'];
$pro_id = $input['pro_id'];

try {
    // Check if favorite already exists (optional)
    $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND pro_id = ?");
    $stmt->execute([$user_id, $pro_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Already favorited']);
        exit();
    }

    // Insert favorite record
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, pro_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $pro_id]);

    echo json_encode(['success' => true, 'message' => 'Added to favorites']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
