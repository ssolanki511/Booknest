<?php
include_once('db_connect.php');
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['payment_id'], $data['book_id'], $data['coupon_id'], $data['user_id'])) {
    $payment_id = $data['payment_id'];
    $book_id = $data['book_id'];
    $coupon_id = $data['coupon_id'];
    $user_id = $data['user_id'];

    // Insert into coupon_used table
    $query = "INSERT INTO coupon_used (user_id, coupon_id) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $user_id, $coupon_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request data."]);
}
?>