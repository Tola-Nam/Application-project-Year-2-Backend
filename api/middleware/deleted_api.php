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

// Step 1: Copy to store_destroy
$stmtInsert = $conn->prepare("
    INSERT INTO store_destroy (pro_id, pro_name, category,branch, price, stock, description, thumbnail, length, color, pro_viewers)
    SELECT pro_id, productName, category,brand, price, stock, description, thumbnail, length, color, product_viewers
    FROM product_fishing WHERE pro_id = ?;");

$stmtInsert->bind_param("i", $pro_id);

if (!$stmtInsert->execute()) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Failed to copy data to warehouse']);
    exit;
}

// Step 2: Delete the product from product_fishing
$stmtDelete = $conn->prepare("DELETE FROM product_fishing WHERE pro_id = ?");
$stmtDelete->bind_param("i", $pro_id);

if ($stmtDelete->execute()) {
    echo json_encode(['status' => true, 'message' => 'Product moved to warehouse and deleted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Delete failed']);
}
