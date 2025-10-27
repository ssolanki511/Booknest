<?php
    include_once('db_connect.php');
    session_start();

    if (isset($_GET['cart_id']) && isset($_SESSION['user'])) {
        $cart_id = $con->real_escape_string($_GET['cart_id']);
        $user_id = $_SESSION['user'];

        $deleteQuery = "DELETE FROM `add_to_cart` WHERE `id` = '$cart_id' AND `user_id` = '$user_id'";
        if ($con->query($deleteQuery)) {
            setcookie('success', 'Item removed from cart.', time()+3,'/');
        } else {
            setcookie('error', 'Failed to remove item.', time()+3,'/');
        }
    } else {
        setcookie('error', 'Invalid request.', time()+3,'/');
    }
    ?>
    <script>window.location.href = 'cart.php';</script>
    <?php
?>