<?php
session_start();

// CORS headers
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Check session
if (isset($_SESSION['User_id'])) {
    echo json_encode([
        'success' => true,
        'user' => [
            'User_id' => $_SESSION['User_id'],
            'First_name' => $_SESSION['First_name'] ?? null,
            'Last_name' => $_SESSION['Last_name'] ?? null,
            'Phone_number' => $_SESSION['Phone_number'] ?? null,
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No active session']);
}
