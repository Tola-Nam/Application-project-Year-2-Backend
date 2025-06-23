<?php
// CORS and JSON headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
header("Cross-Origin-Resource-Policy: cross-origin");

// DB connection
$conn = new mysqli("localhost", "root", "", "applicationprojectbackendruppy2");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB Connection failed"]);
    exit;
}

// Get POST form data safely
$productName = $_POST['productName'] ?? '';
$category = $_POST['category'] ?? '';
$brand = $_POST['brand'] ?? '';
$price = $_POST['price'] ?? '';
$stock = $_POST['quantity'] ?? '';
$description = $_POST['description'] ?? '';
$length = $_POST['length'] ?? '';
$color = $_POST['color'] ?? '';

/**
 * Upload product thumbnail with random+datetime file name
 */
function upload_product_thumbnail($source_file): string
{
    $random = rand(0, 9999999);
    $datetime = date('Ymd_His');
    $extension = pathinfo($source_file['name'], PATHINFO_EXTENSION);
    $newFileName = $random . '_' . $datetime . '.' . $extension;

    $uploadDir = __DIR__ . "/../services/image/upload/";
    $destination = $uploadDir . $newFileName;

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($source_file['tmp_name'], $destination)) {
        return "services/image/upload/" . $newFileName; // relative path for DB
    } else {
        throw new Exception("Image upload failed!");
    }
}

// Process file upload and insert to DB
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    try {
        $relativePath = upload_product_thumbnail($_FILES['photo']);

        // Escape values before inserting to DB
        $productName = $conn->real_escape_string($productName);
        $category = $conn->real_escape_string($category);
        $brand = $conn->real_escape_string($brand);
        $price = $conn->real_escape_string($price);
        $stock = $conn->real_escape_string($stock);
        $description = $conn->real_escape_string($description);
        $length = $conn->real_escape_string($length);
        $color = $conn->real_escape_string($color);
        $filepath = $conn->real_escape_string($relativePath);

        // SQL Insert
        $sql = "INSERT INTO product_fishing 
                (productName, category, brand, price, stock, description, thumbnail, length, color) 
                VALUES 
                ('$productName', '$category', '$brand', '$price', '$stock', '$description', '$filepath', '$length', '$color')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Upload and insert successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "DB Insert Error: " . $conn->error]);
        }

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No file uploaded"]);
}

$conn->close();
?>