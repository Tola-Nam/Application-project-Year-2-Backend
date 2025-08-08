<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require './connection.php';
$conn = connection();
$pro_id = intval($_GET['pro_id']);
$stmt = $conn->prepare("SELECT * FROM product_fishing WHERE pro_id = ?");
$stmt->bind_param("i", $pro_id);
$stmt->execute();
echo json_encode($stmt->get_result()->fetch_assoc());
