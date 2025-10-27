<?php
    include_once('db_connect.php');

    if(isset($_GET['email']) && isset($_GET['token'])){
        $email = $_GET['email'];
        $token = $_GET['token'];

        $user_exist = "SELECT * FROM `users` WHERE `email` = '$email' AND `token` = '$token'";
        $result = $con->query($user_exist);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if($row['status'] == 'Inactive'){
                $update_status = "UPDATE `users` SET `status` = 'Active' WHERE `email` = '$email'";
                if($con->query($update_status)){
                    setcookie('success', 'Account activated successfully.', time() + 3, "/");
                }else{
                    setcookie('error', 'Failed to activate account.', time() + 3, "/");
                }
            }else{
                setcookie('error', 'Account already activated.', time() + 3, "/");
            }
        }else{
            setcookie('error', 'Account not found.', time() + 3, "/");
        }
    }
?>
<script>
    window.location.href = 'login.php';
</script>