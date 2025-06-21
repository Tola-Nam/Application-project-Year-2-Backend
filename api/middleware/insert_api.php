<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "applicationprojectbackendruppy2");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB Connection failed"]);
    exit;
}

// Get form data safely
$productName = $_POST['productName'] ?? '';
$category = $_POST['category'] ?? '';
$brand = $_POST['brand'] ?? '';
$price = $_POST['price'] ?? '';
$stock = $_POST['quantity'] ?? '';
$description = $_POST['description'] ?? '';
$length = $_POST['length'] ?? '';
$color = $_POST['color'] ?? '';

// File upload
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    // Physical server path, not URL
    $uploadDir = __DIR__ . "/../services/image/upload/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $originalName = basename($_FILES["photo"]["name"]);
    $newFileName = uniqid() . "_" . $originalName;
    $targetPath = $uploadDir . $newFileName;
    $relativePath = "services/image/upload/" . $newFileName; // This can be stored and used as image src later

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath)) {
        // Escape strings
        $productName = $conn->real_escape_string($productName);
        $category = $conn->real_escape_string($category);
        $brand = $conn->real_escape_string($brand);
        $price = $conn->real_escape_string($price);
        $stock = $conn->real_escape_string($stock);
        $description = $conn->real_escape_string($description);
        $length = $conn->real_escape_string($length);
        $filepath = $conn->real_escape_string($relativePath);
        $color = $conn->real_escape_string($color);

        $sql = "INSERT INTO product_fishing 
                (productName, category, brand, price, stock, description, thumbnail, length, color) 
                VALUES 
                ('$productName', '$category', '$brand', '$price', '$stock', '$description', '$filepath', '$length', '$color')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Upload and insert successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "DB Insert Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No file uploaded"]);
}

$conn->close();
?>