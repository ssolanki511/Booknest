<?php
    include_once('../db_connect.php');

    if(isset($_GET['category_id'])){
        $category_id = $_GET['category_id'];
        $delete_query = "DELETE FROM `category` WHERE `id` = $category_id";
        if($con->query($delete_query)){
            setcookie('success', 'Category deleted successfully.', time()+3, '/');
        }else{
            setcookie('error', "Failed to delete category. Please try again.", time() + 3, '/');
        }
    }
    ?>
    <script>window.location.href = "admin_book.php";</script>
    <?php
?>