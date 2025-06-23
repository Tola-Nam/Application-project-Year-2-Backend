<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once('../middleware/connection.php');

if (!isset($_GET['category'])) {
    echo json_encode(["error" => "Invalid product category"]);
    exit;
}

$category = intval(value: $_GET['category']);

$conn = connection();
if ($conn->connect_error) {
    echo json_encode(['error' => "Database connection failed"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM product_fishing WHERE category = ?");
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param("i", $category);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

if (count($products) > 0) {
    echo json_encode([
        "status" => true,
        "data" => $products
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "No products found in this category"
    ]);
}

$stmt->close();
$conn->close();
?>