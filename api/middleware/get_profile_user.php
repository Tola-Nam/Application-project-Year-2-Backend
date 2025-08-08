<?php
global $conn;
session_start();

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

//require_once('./connection.php');
$host = 'localhost';
$dbname = 'applicationprojectbackendruppy2';
$username = 'root';
$db_password = '';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $db_password);
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}

if (!isset($_SESSION['User_id'])) {
    http_response_code(401);
    echo json_encode([
        'logged_in' => false,
        'message' => 'User not logged in'
    ]);
    exit();
}

$userId = $_SESSION['User_id'];

try {
    $stmt = $conn->prepare("
        SELECT 
            u.User_id,
            u.First_name,
            u.Last_name,
            u.Gender,
            u.Phone_number,
            p.profile_id,
            p.thumbnail
        FROM users u
        LEFT JOIN profiles p ON p.user_id = u.User_id
        WHERE u.User_id = ?
        ORDER BY p.create_date DESC
        LIMIT 1
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode([
            'logged_in' => true,
            'user' => $user
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'logged_in' => true,
            'message' => 'User not found'
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
