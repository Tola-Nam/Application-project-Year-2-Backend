<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once './connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'views' => 0
];

try {
    // Validate input
    if (!isset($data['pro_id'])) {
        throw new Exception('Product ID is required');
    }

    $pro_id = (int)$data['pro_id'];
    
    if ($pro_id <= 0) {
        throw new Exception('Invalid product ID');
    }

    // Connect to database
    $db = connection();

    // Get current view count
    $query = "SELECT product_viewers FROM product_fishing WHERE pro_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$pro_id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$current) {
        throw new Exception('Product not found');
    }

    // Increment view count
    $new_count = $current['product_viewers'] + 1;
    $update = "UPDATE product_fishing SET product_viewers = ? WHERE pro_id = ?";
    $stmt = $db->prepare($update);
    $success = $stmt->execute([$new_count, $pro_id]);

    if ($success) {
        $response = [
            'success' => true,
            'views' => $new_count,
            'message' => 'View count updated'
        ];
    } else {
        throw new Exception('Failed to update view count');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Return response
echo json_encode($response);
?>