<?php
session_start();
//require_once './connection.php';
global $pdo;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = 'localhost';
$dbname = 'applicationprojectbackendruppy2';
$username = 'root';
$db_password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $db_password);

$response = ['success' => false];

if (!isset($_SESSION['User_id'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['User_id'];
$input = json_decode(file_get_contents('php://input'), true);
$pro_id = $input['pro_id'] ?? null;

if (!$pro_id) {
    $response['message'] = 'Product ID is required';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT favorite_id FROM add_favorite WHERE User_id = ? AND pro_id = ?");
    $stmt->execute([$user_id, $pro_id]);

    if ($stmt->rowCount() === 0) {
        $stmt = $pdo->prepare("INSERT INTO add_favorite (user_id, pro_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $pro_id]);
    }

    $response['success'] = true;
} catch (PDOException $e) {
    $response['message'] = 'DB Error: ' . $e->getMessage();
}

// ✅ Make sure this is the ONLY output
echo json_encode($response);
