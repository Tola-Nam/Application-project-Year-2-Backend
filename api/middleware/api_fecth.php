<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");

include('connection.php');
$connection = connection();

$sql = "SELECT * FROM supercar_inventory";
$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
    $cars = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cars[] = $row;
    }

    echo json_encode([
        'status' => true,
        'data' => $cars
    ]);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'No records found'
    ]);
}
?>