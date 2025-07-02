<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('./connection.php');

function upload_product_thumbnail($source_file): string
{
    $upload_dir = __DIR__ . '/services/image/upload/';

    // Create folder if not exists
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            throw new Exception("Failed to create upload directory.");
        }
    }

    $filename = rand(0, 999999999) . date('YmdHis') . '.' . pathinfo($source_file['name'], PATHINFO_EXTENSION);
    $destination = $upload_dir . $filename;

    if (move_uploaded_file($source_file['tmp_name'], $destination)) {
        return $filename;
    } else {
        throw new Exception("Image upload failed!");
    }
}

try {
    $conn = connection();

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Sanitize and fetch POST data
    $pro_id = $_POST['pro_id'] ?? '';
    $productName = $_POST['productName'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $length = $_POST['length'] ?? '';
    $color = $_POST['color'] ?? '';

    // Validate required fields
    if (empty($pro_id) || empty($productName)) {
        throw new Exception("Missing required fields: Product ID and Name are required.");
    }

    // Handle thumbnail upload (if exists)
    $thumbnail = '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $thumbnail = upload_product_thumbnail($_FILES['thumbnail']);
    }

    // Prepare SQL update
    $sql = "UPDATE products SET productName = ?, category = ?, brand = ?, price = ?, stock = ?, description = ?, thumbnail = ?, length = ?, color = ? WHERE id = ?";
    $params = [$productName, $category, $brand, $price, $stock, $description, $thumbnail, $length, $color, $pro_id];
    $types = str_repeat('s', count($params) - 1) . 'i';

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Product updated successfully",
            "thumbnail" => $thumbnail
        ]);
    } else {
        throw new Exception("Database update failed: " . $stmt->error);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
