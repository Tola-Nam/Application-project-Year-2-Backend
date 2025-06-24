<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once('../middleware/connection.php');

// Optional: Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (!isset($_GET['category'])) {
    echo json_encode(value: ["error" => "Invalid product category"]);
    exit;
}

$category = $_GET['category'];

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

$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

if (count(value: $products) > 0) {
    echo json_encode(value: [
        "status" => true,
        "data" => $products
    ]);
} else {
    echo json_encode(value: [
        "status" => false,
        "message" => "No products found in this category"
    ]);
}

$stmt->close();
$conn->close();
?>