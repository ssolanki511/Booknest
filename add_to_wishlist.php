<?php
    include_once('db_connect.php');
    session_start();
    
    $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    if(isset($_SESSION['guest'])){
        setcookie('error', 'Please login to add book to wishlist.', time()+3, '/');
        ?>
        <script>window.location.href = "login.php"; </script>
        <?php
        exit();
    }

    if (isset($_GET['book_id'])) {
        $book_id = $_GET['book_id']; // Sanitize book_id
        $user_id = $_SESSION['user']; // Get the logged-in user's ID

        // Check if the book is already in the wishlist
        $check_query = "SELECT * FROM `wishlist` WHERE `book_id` = $book_id AND `user_id` = $user_id";
        $check_result = $con->query($check_query);

        if ($check_result->num_rows > 0) {
            // Remove the book from the wishlist
            $delete_query = "DELETE FROM `wishlist` WHERE `book_id` = $book_id AND `user_id` = $user_id";
            if ($con->query($delete_query)) {
                setcookie('success', 'Book removed from wishlist.', time()+3, '/');
            } else {
                setcookie('error', 'Failed to add book to wishlist.', time()+3, '/');
            }
        } else {
            // Add the book to the wishlist
            $insert_query = "INSERT INTO `wishlist` (`book_id`, `user_id`) VALUES ($book_id, $user_id)";
            if ($con->query($insert_query)) {
                setcookie('success', 'Book added from wishlist.', time()+3, '/');
            } else {
                setcookie('error', 'Failed to add book to wishlist.', time()+3, '/');
            }
        }
        ?>
        <script>window.location.href = "<?php echo $previous_page; ?>"; </script>
        <?php
    } else {
        ?>
        <script>window.location.href = "index.php"; </script>
        <?php
    }
?>