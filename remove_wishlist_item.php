<?php
    include_once('db_connect.php');
    session_start();

    if (isset($_GET['wishlist_id']) && isset($_SESSION['user'])) {
        $wishlist_id = $con->real_escape_string($_GET['wishlist_id']);
        $user_id = $_SESSION['user'];

        $deleteQuery = "DELETE FROM `wishlist` WHERE `id` = '$wishlist_id' AND `user_id` = '$user_id'";
        if ($con->query($deleteQuery)) {
            setcookie('success', 'Item removed from wishlist.', time()+3,'/');
        } else {
            setcookie('error', 'Failed to remove item.', time()+3,'/');
        }
    } else {
        setcookie('error', 'Invalid request.', time()+3,'/');
    }
    ?>
    <script>window.location.href = 'wishlist.php';</script>
    <?php
?>