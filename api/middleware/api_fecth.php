<?php
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");

include('connection.php');
$connection = connection();

$sql = "SELECT * FROM product_fishing";
$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
    $cars = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    echo json_encode([
        'status' => true,
        'data' => $items
    ]);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'No records found'
    ]);
}
?>