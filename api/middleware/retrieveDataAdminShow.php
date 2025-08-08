<?php

use JetBrains\PhpStorm\NoReturn;

session_start();
require_once('./api_for_admin.php');
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

#[NoReturn] function respond($data, $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Accept GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    respond(['error' => 'Invalid request method'], 405);
}

// Return session data
if (isset($_SESSION['admin_id'])) {
    respond([
        'status' => true,
        'admin' => $_SESSION['admin_id']
    ]);
} else {
    respond(['error' => 'No session'], 401);
}
