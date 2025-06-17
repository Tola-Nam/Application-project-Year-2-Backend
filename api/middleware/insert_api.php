<?php

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,content-type,Access-Control-Allow-Methods,Authorization,X-requested-With');

// Get and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$requiredFields = ['brand', 'Model', 'year', 'price', 'color', 'thumbnail', 'engine', 'HorsePower', 'TopSpeed', 'seconds', 'transmission', 'DriveType', 'description', 'PremiumFeatures'];
foreach ($requiredFields as $field) {
    if (isset($data[$field])) {
        echo json_encode([
            'message' => "Missing required field: $field",
            'status' => false
        ]);
        exit;
    }
}

// Assign values
$brand = $data['brand'] ?? null;
$Model = $data['Model'] ?? null;
$year = $data['year'] ?? null;
$price = $data['price'] ?? null;
$color = $data['color'] ?? null;
$thumbnail = $data['thumbnail'] ?? null;
$engine = $data['engine'] ?? null;
$HorsePower = $data['HorsePower'] ?? null;
$TopSpeed = $data['TopSpeed'] ?? null;
$seconds = $data['seconds'] ?? null;
$Transmission = $data['transmission'] ?? null;
$DriveType = $data['DriveType'] ?? null;
$description = $data['description'] ?? null;
$PremiumFeatures = $data['PremiumFeatures'] ?? null;

include 'connection.php';
$connection = connection();

// FIX: Close the VALUES() parenthesis
$sql = "INSERT INTO supercar_inventory (brand, Model, year, price, color, thumbnail, engine, HorsePower, TopSpeed, seconds,transmission, DriveType, description, PremiumFeatures)
        VALUES ('$brand', '$Model', '$year', '$price', '$color', '$thumbnail', '$engine', '$HorsePower', '$TopSpeed', '$seconds','$Transmission', '$DriveType', '$description', '$PremiumFeatures')";

if (mysqli_query($connection, $sql)) {
    echo json_encode(['message' => 'Supercar report is inserted', 'status' => true]);
} else {
    echo json_encode(['message' => 'Supercar report is not inserted', 'error' => mysqli_error($connection), 'status' => false]);
}

