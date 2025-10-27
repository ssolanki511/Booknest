<?php
    include_once('db_connect.php');
    session_start();

    if(isset($_SESSION['guest'])){
        setcookie('error', 'Please login to add book to cart.', time()+3, '/');
        ?>
        <script>window.location.href = "login.php"; </script>
        <?php
        exit();
    }

    if(isset($_GET['book_id'])){
        $book_id = $_GET['book_id'];
        $user_id = $_SESSION['user'];
        $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

        $check_query = "SELECT * FROM `add_to_cart` WHERE `book_id` = $book_id AND `user_id` = $user_id";
        $check_result = $con->query($check_query);

        if ($check_result->num_rows > 0) {
            setcookie('error', 'This book is already in your cart.', time() + 3, '/');
            ?>
            <script>window.location.href = "<?php echo $previous_page; ?>"</script>
            <?php
        } else {
            $insert_query = "INSERT INTO `add_to_cart` (`book_id`, `user_id`) VALUES ($book_id, $user_id)";
            if ($con->query($insert_query)) {
                setcookie('success', 'Book added to cart successfully.', time() + 3, '/');
                ?>
                <script>window.location.href = "cart.php"</script>
                <?php
            } else {
                setcookie('error', 'Failed to add book to cart, Please try again.', time() + 3, '/');
                ?>
                <script>window.location.href = "<?php echo $previous_page; ?>"</script>
                <?php
            }
        }
        // header('Location: ' . $previous_page);
        ?>
        <script> window.location.href = "cart.php"; </script>
        <?php
    }
?>