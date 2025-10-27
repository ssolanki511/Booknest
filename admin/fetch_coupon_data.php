<?php
    include_once('../db_connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];

        if (!empty($id) && is_numeric($id)) {
            $result = $con->query("SELECT * FROM `coupons` WHERE `coupon_id` = $id");

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Return the coupon data as a JSON response
                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        'coupon_title' => $row['coupon_title'],
                        'coupon_code' => $row['coupon_code'],
                        'start_date' => $row['start_date'],
                        'end_date' => $row['end_date'],
                        'coupon_type' => $row['coupon_type'],
                        'coupon_value' => $row['coupon_value']
                    ]
                ]);
            } else {
                // No record found
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No record found'
                ]);
            }
        } else {
            // Invalid ID
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid ID'
            ]);
        }
    } else {
        // Invalid request method
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request method'
        ]);
    }
?>