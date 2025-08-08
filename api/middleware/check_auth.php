<?php
session_start();

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
require_once ('./connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}

if (isset($_SESSION['User_id'])) {
    echo json_encode([
        'logged_in' => true,
        'user_id' => $_SESSION['User_id']
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'logged_in' => false,
        'message' => 'User not logged in'
    ]);
}
