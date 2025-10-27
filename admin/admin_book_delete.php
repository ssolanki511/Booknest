<?php
include_once('../db_connect.php');
session_start();
if($_GET['book_id']){
    $book_id = $_GET['book_id'];
    $delete = "DELETE FROM `books` WHERE `b_id` = $book_id";
    $book_array = $con->query("SELECT * FROM `books` WHERE `b_id` = '$book_id'");
    $book= $book_array->fetch_assoc();
    $cover_path = '../files/book_cover/'.$book['b_cover_tmp'];
    $file_path = '../files/book_file/'.$book['b_file'];
    if($con->query($delete)){
        if (file_exists($cover_path)) {
            unlink($cover_path);
        }
        
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        setcookie('success', 'Book is deleted.', time() + 3, '/');
        
    }else{
        setcookie('error', 'Book is not deleted.', time() + 3, '/');
    }
    ?>
    <script>window.location.href = "admin_book.php"; </script>
    <?php
}

?>