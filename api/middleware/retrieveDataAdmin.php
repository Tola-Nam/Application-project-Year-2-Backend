<?php
session_start();

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

function respond(array $data, int $status = 200): void
{
    http_response_code($status);
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

    // Query user by email and phone_number only
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email AND phone_number = :phone_number");
    $stmt->execute([
        ':email' => $email,
        ':phone_number' => $phone,
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no user found
    if (!$admin) {
        respond(['error' => 'Invalid email or phone number'], 401);
    }

    // Verify password with stored hashed password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    unset($admin['password']); // remove password from response

    $_SESSION['admin'] = [
        'admin_id' => $admin['admin_id'],
        'email' => $admin['email'],
        'phone_number' => $admin['phone_number']
    ];

    respond([
        'message' => 'Login successful',
        'email' => $email,
        'phone_number' => $phone,
        'password' => $hashedPassword,
        'admin' => $admin
    ]);
} catch (PDOException $e) {
    respond(['error' => 'Database error', 'details' => $e->getMessage()], 500);
}
