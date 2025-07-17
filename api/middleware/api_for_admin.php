<?php
class Api_for_admin
{
    private PDO $db;
    private array $inputData;

    public function __construct()
    {
        $this->setHeaders();
        $this->HandleOptionRequest();
        $this->inputData = $this->retrieveData();
        $this->connectDB();
    }

    private function setHeaders(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Content-Type: application/json");
        header("Cross-Origin-Resource-Policy: cross-origin");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    private function HandleOptionRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
            http_response_code(204);
            exit();
        }
    }

    private function retrieveData(): array
    {
        $retrieve = file_get_contents("php://input");
        $decode = json_decode($retrieve, true);
        return is_array($decode) ? $decode : [];
    }

    public function input($key, $value = null)
    {
        return $this->inputData[$key] ?? $value;
    }

    public function require_field(array $fields)
    {
        $missing = [];
        foreach ($fields as $field) {
            if (!isset($this->inputData[$field]) || trim($this->inputData[$field]) === '') {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            $this->respond([
                'error' => 'Missing fields',
                'fields' => $missing
            ], 400);
        }
    }

    public function respond(array $data, int $code = 200)
    {
        http_response_code($code);
        echo json_encode($data);
        exit();
    }

    private function connectDB(): void
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=applicationprojectbackendruppy2",  // replace with your DB name
                "root",   // default user in XAMPP
                ""        // leave password empty in XAMPP by default
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->respond(['error' => 'Database connection failed', 'details' => $e->getMessage()], 500);
        }
    }


    // public function InsertAdmin(string $first_name, string $last_name, string $email, string $gender, string $phone_number, string $password)
    // {
    //     try {
    //         $stmt = $this->db->prepare("INSERT INTO admins (`first_name`, `last_name`, `email`, `gender`, `phone_number`, `password`) 
    //                                     VALUES (:first_name, :last_name, :email, :gender, :phone_number, :password)");
    //         $stmt->execute([
    //             ':first_name' => $first_name,
    //             ':last_name' => $last_name,
    //             ':email' => $email,
    //             ':gender' => $gender,
    //             ':phone_number' => $phone_number,
    //             ':password' => $password
    //         ]);
    //         $this->respond([
    //             'message' => 'User access to dashboard successfully'
    //         ]);
    //     } catch (PDOException $e) {
    //         $this->respond([
    //             'error' => 'Insert fail',
    //             'detail' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function InsertAdmin(string $first_name, string $last_name, string $email, string $gender, string $phone_number, string $password)
    {
        try {
            // Hash the password securely
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->db->prepare("INSERT INTO admins 
            (`first_name`, `last_name`, `email`, `gender`, `phone_number`, `password`) 
            VALUES (:first_name, :last_name, :email, :gender, :phone_number, :password)");

            $stmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':gender' => $gender,
                ':phone_number' => $phone_number,
                ':password' => $hashedPassword
            ]);

            $this->respond([
                'message' => 'Admin registered successfully'
            ]);
        } catch (PDOException $e) {
            $this->respond([
                'error' => 'Insert failed',
                'detail' => $e->getMessage()
            ], 500);
        }
    }

}
