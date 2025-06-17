<?php
require_once('./connection.php');

// Allow all origins (or specify only your frontend's origin)
header("Access-Control-Allow-Origin: http://localhost:5173");

// Allow credentials if needed (optional)
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
// Allow necessary methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Allow headers you want to send
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}


$method = $_SERVER['REQUEST_METHOD'];
$conn = connection();

switch ($method) {
    case 'POST':
        // Decode JSON input
        $inputItem = json_decode(file_get_contents("php://input"), true);

        if (
            empty($inputItem['firstName']) || empty($inputItem['lastName']) ||
            empty($inputItem['email']) || empty($inputItem['PhoneNumber']) ||
            empty($inputItem['password_hast']) || empty($inputItem['confirmPassword'])
        ) {
            echo json_encode(["error" => "All fields are required"], JSON_PRETTY_PRINT);
            exit;
        } else {


            if ($inputItem['password_hast'] !== $inputItem['confirmPassword']) {
                echo json_encode(["error" => "Passwords do not match"], JSON_PRETTY_PRINT);
                exit;
            }

            // Assign variables safely
            $firstName = $inputItem['firstName'];
            $lastName = $inputItem['lastName'];
            $email = $inputItem['email'];
            $phoneNumber = $inputItem['PhoneNumber'];
            $passwordHash = $inputItem['password_hast'];

            // Use prepared statement
            $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, PhoneNumber, password_hast) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                echo json_encode(["error" => "Prepare failed: " . $conn->error]);
                exit;
            }

            $stmt->bind_param("sssss", $firstName, $lastName, $email, $phoneNumber, $passwordHash);

            if ($stmt->execute()) {
                echo json_encode([
                    "message" => "User created successfully",
                    "data" => [
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'email' => $email,
                        'PhoneNumber' => $phoneNumber
                    ]
                ], JSON_PRETTY_PRINT);
            } else {
                echo json_encode(["error" => "Execute failed: " . $stmt->error], JSON_PRETTY_PRINT);
            }

            $stmt->close();
            $conn->close();
        }

        // api for login account.
        if ($_POST['login']) {

            $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ? AND PhoneNumber = ? AND password_hast =?");
            $stmt->bind_param("sss", $email, $phoneNumber, $passwordHash);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                if ($user['password_hast']) {
                    echo json_encode(["success" => true, "user" => $user]);
                } else {
                    echo json_encode(["success" => false, "error" => "Invalid password"]);
                }
            } else {
                echo json_encode(["success" => false, "error" => "User not found"]);
            }

            $stmt->close();
            $conn->close();

        }

        break;

    case 'GET':
        // echo json_encode(["message" => "GET method is not supported for this endpoint"], JSON_PRETTY_PRINT);
        $result = $conn->query("SELECT * FROM `Users`;");
        $user = [];
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($user, $row);
        }

        echo json_encode($user);
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Method not allowed"], JSON_PRETTY_PRINT);
        break;
}
?>