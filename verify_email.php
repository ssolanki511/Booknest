<?php
session_start();
include_once("db_connect.php");

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];
    $sql = "SELECT * FROM registration WHERE email = '$email' AND token = '$token'";
    $count = $con->query($sql);

    if ($count === false) {
        setcookie('error', 'Database query error', time() + 3, '/');
    } else {
        $r = mysqli_fetch_assoc($count);
        if ($count->num_rows == 1) {
            if ($r['status'] == 'Inactive') {
                $update = "UPDATE registration SET status = 'Active' WHERE email = '$email'";
                if ($con->query($update)) {
                    setcookie('success', 'Account Verification Successful', time() + 3, '/');
                } else {
                    setcookie('error', 'Error in verifying email', time() + 3, '/');
                }
            } else {
                setcookie('success', 'Email already verified', time() + 3, '/');
            }
        } else {
            setcookie('error', 'Invalid verification link', time() + 3, '/');
        }
    }
} else {
    setcookie('error', 'Email not registered', time() + 3, '/');
}
?>
<script>
    window.location.href = 'verify_email.php';
</script>