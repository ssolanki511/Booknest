<?php
include_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'];
    $email = $_POST['email'];

    $result = $con->query("SELECT * FROM `users` WHERE `email` = '$email' AND `otp` = '$otp' AND `otp_expiry` > NOW()");

    if($result->num_rows > 0){
        setcookie('success', 'Correct OTP, Please set the new password.',time()+3,'/');
        echo 'Success | '.$email;
    }else{
        setcookie('error', 'Invalid or expired OTP.', time() + 3, '/');
        echo 'Error | Invalid or expired OTP';
    }
}
?>