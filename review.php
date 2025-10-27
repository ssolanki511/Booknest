<?php
    include_once('db_connect.php');
    session_start();

    if(!isset($_SESSION['user'])){
        ?>
        <script>window.location.href = "login.php";</script>
        <?php
        setcookie('error', 'Please login to give review.', time()+3, '/');
        exit();
    }

    if(!isset($_GET['book_id'])){
        ?>
        <script>window.location.href = "index.php";</script>
        <?php
        exit();
    }
    $book_id = $_GET['book_id'];
    $book_array = $con->query("SELECT * FROM `books` WHERE `b_id` = $book_id");
    $book = $book_array->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Book - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <script src="files/add-on/jquery.min.js"></script>
    <script src="files/add-on/jquery.validate.min.js"></script>
    <script src="files/add-on/additional-methods.min.js"></script>
</head>

<body class="bg-gray-100">
    <!-- navbar -->
    <?php include_once('cookie_display.php'); ?>
    <?php require_once('header.php'); ?>

    <div class="review-main-container flex justify-center items-center h-screen px-3 ">
        <div class="review-main-box overflow-hidden px-3 py-4 rounded-lg w-full md:border md:px-6 md:border-gray-700 md:w-1/2 bg-white">
            <form action="review.php?book_id=<?php echo $book_id; ?>" id="reviewForm" method="post">
                <h1 class="text-center font-medium text-lg md:text-xl">Rating & Review</h1>
                <div class="rating-product-details flex items-start">
                    <div class="rounded-md h-20 w-20 flex justify-center items-center">
                        <img src="files/book_cover/<?php echo $book['b_cover_tmp']; ?>" alt="" class="object-contain rounded">
                    </div>
                    <h3 class="text-sm md:text-base ml-5 line-clamp-3 w-full capitalize"><?php echo $book['b_name']; ?></h3>
                </div>
                <hr class="border border-neutral-400 my-6">
                <div class="rating-box relative w-full">
                    <h5 class="text-base md:text-lg font-medium">Rating this book</h5>
                    <div class="text-2xl md:text-3xl">
                        <div class="rating2 float-left">
                            <input value="5" name="rating" id="rstar5" type="radio">
                            <label title="text" for="rstar5"></label>
                            <input value="4" name="rating" id="rstar4" type="radio">
                            <label title="text" for="rstar4"></label>
                            <input value="3" name="rating" id="rstar3" type="radio">
                            <label title="text" for="rstar3"></label>
                            <input value="2" name="rating" id="rstar2" type="radio">
                            <label title="text" for="rstar2"></label>
                            <input value="1" name="rating" id="rstar1" type="radio">
                            <label title="text" for="rstar1"></label>
                        </div>
                    </div>
                    <div class="rating-error"></div>
                </div>
                <br>
                <br>
                <div class="review-description block">
                    <h5 class="text-base md:text-lg font-medium">Review this book</h5>
                    <input type="text" name="title" class="border-2 w-full border-neutral-500 rounded-sm py-1 px-2 mt-4 bg-transparent" placeholder="Review title...">
                    <textarea name="description" rows="5" class="bg-transparent w-full border-2 border-neutral-500 rounded-sm resize-none mt-4 py-1 px-2" placeholder="Description..."></textarea>
                </div>
                <div class="review-submit-box flex justify-center items-center mt-5">
                    <input type="submit" value="Submit" name="review_sub" class="bg-temp text-sm text-white py-1 px-4 rounded border border-temp hover:bg-transparent hover:text-temp cursor-pointer duration-100 md:text-base">
                </div>
            </form>
        </div>
    </div>
    
    <?php require_once('footer.php'); ?>

    <script src="files/js_files/validation.js?v=<?php echo time(); ?>"></script>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
    if(isset($_POST['review_sub'])){
        $review_title = $_POST['title'];
        $review_description = $_POST['description'];
        $rating = $_POST['rating'];
        $user_id = $_SESSION['user'];

        $review_query = "INSERT INTO `reviews`(`book_id`, `user_id`, `review_title`, `review_text`, `rating`) VALUES ('$book_id','$user_id','$review_title','$review_description','$rating')";

        if($con->query($review_query)){
            setcookie('success', 'Review is successfully.', time()+3, '/');
            ?>
            <script>window.location.href = "product.php?book_id=<?php echo $book_id; ?>";</script>
            <?php
        }else{
            setcookie('error', 'Review is not successfully.', time()+3, '/');
            ?>
            <script>window.location.href = "review.php?book_id=<?php echo $book_id; ?>";</script>
            <?php
        }
    }
?>