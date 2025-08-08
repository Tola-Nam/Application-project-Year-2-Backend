<?php
session_start();
require_once './connection.php';

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = ['success' => false];

if (!isset($_SESSION['User_id'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$pro_id = $input['pro_id'] ?? null;
$user_id = $_SESSION['User_id'];

if (!$pro_id) {
    $response['message'] = 'Product ID is required';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM add_favorite WHERE user_id = ? AND pro_id = ?");
    $stmt->execute([$user_id, $pro_id]);
    $response['success'] = true;
} catch (PDOException $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
