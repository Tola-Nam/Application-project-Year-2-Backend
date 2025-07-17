<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once('./connection.php');
$conn = connection();

// Only allow DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Method not allowed']);
    exit;
}

// Accept pro_id from query string
$pro_id = isset($_GET['pro_id']) ? (int) $_GET['pro_id'] : 0;

if ($pro_id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Invalid ID']);
    exit;
}

// Delete from DB
$stmt = $conn->prepare("DELETE FROM product_fishing WHERE pro_id = ?");
$stmt->bind_param("i", $pro_id);

if ($stmt->execute()) {
    echo json_encode(['status' => true, 'message' => 'Product deleted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Delete failed']);
}
