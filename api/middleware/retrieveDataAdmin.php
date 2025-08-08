<?php

use JetBrains\PhpStorm\NoReturn;

session_start();

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

#[NoReturn] function respond(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['error' => 'Invalid request method'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'], $data['phone_number'], $data['password'])) {
    respond(['error' => 'Missing required fields'], 400);
}

$email = $data['email'];
$phone = $data['phone_number'];
$password = $data['password'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=applicationprojectbackendruppy2", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query user by email and phone number
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email AND phone_number = :phone_number");
    $stmt->execute([
        ':email' => $email,
        ':phone_number' => $phone,
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        respond(['error' => 'Invalid email or phone number'], 401);
    }

    // if (!password_verify($password, $admin['password'])) {
    //     respond(['error' => 'Incorrect password'], 401);
    // }
    if (!password_verify($password, $admin['password'])) {
        respond([
            'error' => 'Incorrect password',
            'input_password' => $password,
            'stored_hash' => $admin['password'],
            'password_verified' => password_verify($password, $admin['password'])
        ], 401);
    }

    // Remove password before sending data back or storing in session
    unset($admin['password']);

    // Store user info in session
    $_SESSION['admin_id'] = [
        'admin_id' => $admin['admin_id'],
        'first_name' => $admin['first_name'],
        'last_name' => $admin['last_name'],
        'email' => $admin['email'],
        'phone_number' => $admin['phone_number'],
    ];

    respond([
        'message' => 'Login successful',
        'admin' => $_SESSION['admin_id'],
    ]);


} catch (PDOException $e) {
    respond(['error' => 'Database error', 'details' => $e->getMessage()], 500);
}
