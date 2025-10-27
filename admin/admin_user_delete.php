<?php
    include_once('../db_connect.php');
    session_start();

    if(isset($_GET['user_email'])) {
        $user_email = $_GET['user_email'];
        $delete_query = "DELETE FROM `users` WHERE `email` = '$user_email'";
        if($con->query($delete_query)){
            setcookie('success', 'User deleted successfully', time() + 3, '/');
        }else{
            setcookie('error', 'Failed to delete user', time() + 3, '/');
        }
    }else{
        ?>
        <script>window.location.href = "admin_user.php";</script>
        <?php
    }
    ?>
    <script>window.location.href = "admin_user.php";</script>
    <?php
?>
