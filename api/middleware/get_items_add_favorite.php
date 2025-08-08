<?php
global $pdo;
session_start();

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'applicationprojectbackendruppy2';
$username = 'root';
$db_password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $db_password);
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// Optional: check if user is logged in (if you want to filter by user)
if (!isset($_SESSION['User_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

try {
    $userId = $_SESSION['User_id'];

    // Prepare your SQL query — optionally filter by user_id
    $sql = "
        SELECT 
            a.favorite_id,
            a.user_id,
            a.pro_id,         
            p.productName,
            p.category,
            p.brand,
            p.price,
            p.stock,
            p.thumbnail,
            p.length,
            p.color,
            p.product_viewers,         
            u.First_name,
            u.Last_name,
            u.Phone_number
        FROM add_favorite a
        LEFT JOIN product_fishing p ON a.pro_id = p.pro_id
        LEFT JOIN users u ON a.user_id = u.User_id
        WHERE a.user_id = :user_id
        ORDER BY a.favorite_id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $favorites,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
    ]);
}
