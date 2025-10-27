<?php
    ob_start();
    include_once('db_connect.php');
    session_start();
    if(isset($_GET['book_id'])){
        $book_id = $_GET['book_id'];
        $query = "SELECT * FROM `books` WHERE `b_id` = '$book_id'";
        $result = $con->query($query);

        $averageRatingQuery = "
            SELECT IFNULL(AVG(rating), 0) AS avg_rating
            FROM reviews
            WHERE book_id = '$book_id'
        ";
        $averageRatingResult = $con->query($averageRatingQuery);
        $averageRating = 0;

        if ($averageRatingResult->num_rows > 0) {
            $row = $averageRatingResult->fetch_assoc();
            $averageRating = number_format($row['avg_rating'], 1); // Round to 1 decimal place
        }

        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();

            // $is_in_wishlist = false;
            // $user_id = $_SESSION['user'];
            // $wishlist_query = "SELECT * FROM `wishlist` WHERE `book_id` = '$book_id' AND `user_id` = '$user_id'";
            // $wishlist_result = $con->query($wishlist_query);
            // $is_in_wishlist = $wishlist_result->num_rows > 0;

            if(isset($_SESSION['user'])){
                $user_id = $_SESSION['user'];
                $wishlist_books = [];
                $wishlist_query = "SELECT `book_id` FROM `wishlist` WHERE `user_id` = $user_id";
                $wishlist_result = $con->query($wishlist_query);
            
                if ($wishlist_result->num_rows > 0) {
                    while ($row = $wishlist_result->fetch_assoc()) {
                        $wishlist_books[] = $row['book_id'];
                    }
                }
            }

            $category = $book['b_category'];
            $relatedBooksQuery = "
                SELECT 
                    b.b_id, 
                    b.b_name, 
                    b.b_price, 
                    b.b_discount, 
                    b.b_category, 
                    b.b_cover_tmp, 
                    IFNULL(AVG(r.rating), 0) AS avg_rating
                FROM books b
                LEFT JOIN reviews r ON b.b_id = r.book_id
                WHERE b.b_category = '$category' AND b.b_id != '$book_id'
                GROUP BY b.b_id
                ORDER BY b.b_publish_date DESC, b.b_id DESC
                LIMIT 10
            ";
            $relatedBooksResult = $con->query($relatedBooksQuery);

            $reviewsQuery = "
                SELECT 
                    r.review_text, 
                    r.rating, 
                    r.review_date, 
                    r.review_title, 
                    u.name AS reviewer_name, 
                    u.user_img, 
                    EXISTS (
                        SELECT 1 
                        FROM purchases 
                        WHERE purchases.user_id = r.user_id 
                        AND purchases.book_id = '$book_id'
                    ) AS has_purchased
                FROM reviews r
                LEFT JOIN users u ON r.user_id = u.user_id
                WHERE r.book_id = '$book_id'
                ORDER BY r.review_date DESC
            ";
            $reviewsResult = $con->query($reviewsQuery);
        } else {
            ?>
            <script>window.location.href='index.php';</script>
            <?php
        }
    }else{
        ?>
        <script>window.location.href='index.php';</script>
        <?php
    }

    $is_purchased = false;

    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user'];
        $purchase_query = "SELECT * FROM `purchases` WHERE `user_id` = '$user_id' AND `book_id` = '$book_id'";
        $purchase_result = $con->query($purchase_query);

        if ($purchase_result->num_rows > 0) {
            $is_purchased = true; // The user has purchased the product
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $book['b_name']; ?> - Booknest</title>
    <link rel="stylesheet" href="files/add-on/swiper-bundle.min.css">
    <script src="files/add-on/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
</head>
<body class="bg-gray-100">
    <?php include_once('cookie_display.php'); ?>
    <?php require_once('header.php'); ?>

    <div class="product-container md:px-4 mb-4">
        <div class="product-box flex justify-center items-start relative flex-wrap md:flex-nowrap">
            <div class="product-img w-52 md:w-96 md:sticky top-32 left-0">
                <div class="image flex justify-center items-center">
                    <div class="w-64 h-96 relative border-2">
                        <img src="files/book_cover/<?php echo $book['b_cover_tmp']; ?>" class="object-cover w-full h-full" alt="book cover">
                        <a href="add_to_wishlist.php?book_id=<?php echo $book['b_id']; ?>" class="like absolute top-2 right-2 cursor-pointer">
                            <i class="fa-heart text-xl p-2 <?php echo isset($_SESSION['user']) && in_array($book['b_id'], $wishlist_books) ? 'fa-solid text-red-600' : 'fa-regular text-black'; ?>"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="product-detail-box w-full mt-6 md:w-2/3 px-4 md:px-6">
                <div class="main-details flex justify-between w-full">
                    <h1 class="product-heading text-base md:text-lg font-medium"><?php echo $book['b_name']; ?></h1>
                    <div class="share-link relative h-fit">
                        <button class="share-icon text-temp hover:bg-gray-500 rounded text-base md:text-lg hover:bg-opacity-40">
                            <i class="fa-solid fa-share-from-square p-2"></i>
                        </button>
                        <div class="share-box hidden">
                            <button id="copy-link-btn" class="flex shadow-xl bg-container absolute top-full text-temp w-28 justify-center items-center right-0 h-12 hover:bg-neutral-400 rounded-md hover:bg-opacity-30 text-sm md:text-base">
                                <i class="fa-solid fa-copy"></i>
                                <p class="ml-2">Copy link</p>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="product-main-rating bg-green-600 w-fit rounded-md py-1 px-2">
                    <p class="text-xs md:text-sm text-white"><?php echo $averageRating; ?> <i class="fa-solid fa-star ml-1"></i></p>
                </div>
                <div class="product-price-detail mt-2 flex items-center space-x-2">
                    <div class="price-offer flex items-end whitespace-nowrap">
                        <p class="base-price text-base md:text-lg font-semibold flex">₹<?php echo $book['b_price']; ?></p>
                    </div>
                    <?php if ($book['b_discount'] > 0) { ?>
                        <p class="original-price line-through text-xs md:text-sm text-gray-500">₹<?php echo floor($book['b_price'] - ($book['b_price'] * $book['b_discount'] / 100)); ?></p>
                    <?php } ?>
                </div>
                
                <div class="space-x-4 flex">
                    <?php if ($is_purchased) { ?>
                        <!-- Display Download Button -->
                        <a href="download.php?book_id=<?php echo $book_id; ?>" class="bg-green-600 block w-fit text-white text-xs md:text-base mt-4 px-2 py-2 rounded border border-green-600 hover:bg-transparent hover:text-green-600 duration-100 ease-linear md:px-4">
                            Download <i class="fa-solid fa-download ml-1"></i>
                        </a>
                    <?php } else { ?>
                        <!-- Display Add to Cart and Buy Now Buttons -->
                        <a href="book_add_to_cart.php?book_id=<?php echo $book_id; ?>" class="bg-temp block w-fit text-white text-xs md:text-base mt-4 px-2 py-2 rounded border border-temp hover:bg-transparent hover:text-temp duration-100 ease-linear md:px-4">
                            Add to Cart <i class="fa-solid fa-cart-shopping ml-1"></i>
                        </a>
                        <a href="payment.php?book_id=<?php echo $book_id; ?>" class="bg-transparent block w-fit text-temp border border-temp text-xs md:text-base mt-4 px-2 py-2 rounded hover:bg-temp hover:text-white duration-100 ease-linear md:px-4">
                            Buy Now <i class="fa-solid fa-bag-shopping ml-1"></i>
                        </a>
                    <?php } ?>
                    
                </div>
                
                <div class="mt-5 capitalize space-y-2">
                    <h1 class="font-medium text-base md:text-lg mb-3">Book Detials</h1>
                    <table class="">
                        <tr class="align-text-top">
                            <td class="text-gray-500 text-sm px-6 py-3">Title</td>
                            <td class="capitalize text-sm"><?php echo $book['b_name']; ?></td>
                        </tr>
                        <tr class="align-text-top">
                            <td class="text-gray-500 text-sm px-6 py-3">Author</td>
                            <td class="capitalize text-sm"><?php echo $book['b_author']; ?></td>
                        </tr>
                        <tr class="align-text-top">
                            <td class="text-gray-500 text-sm px-6 py-3">Genre</td>
                            <td class="capitalize text-sm"><?php echo $book['b_category']; ?></td>
                        </tr>
                        <tr class="align-text-top">
                            <td class="text-gray-500 text-sm px-6 py-3">Description</td>
                            <td class="line-clamp-3 text-sm read-container"><?php echo $book['b_desc']; ?></td>
                        </tr>
                    </table>
                    <div class="w-full text-center">
                        <button class="read-more text-sm py-1 px-2 border border-temp rounded-full bg-temp text-white">Read More</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="review-contianer mt-4 mx-6 md:mx-14">
            <div class="review-box flex justify-between items-end mb-3">
                <h1 class="text-sm md:text-lg font-medium">Rating & Review</h1>
                <a href="review.php?book_id=<?php echo $book_id; ?>" class="border border-black px-2 py-1 rounded text-black font-medium hover:text-temp hover:border-temp text-xs md:text-sm md:py-2 md:px-4">Rate Book</a>
            </div>
            <div class="user-review-container max-h-96 border rounded-md border-gray-400 px-5 py-4 overflow-y-scroll bg-white">
                <?php
                    if ($reviewsResult->num_rows > 0) {
                        while ($review = $reviewsResult->fetch_assoc()) {
                ?>
                <div class="user-review">
                    <div class="user-review-heading flex w-full relative flex-wrap justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center">
                                <div class="user-img-box rounded-full shadow shadow-temp h-8 w-8 md:w-10 md:h-10">
                                    <img src="files/user_images/<?php echo $review['user_img']; ?>" alt="user image" class="w-full h-full object-cover rounded-full">
                                </div>
                                <div class="reviewer-name ml-3">
                                    <p class="text-sm md:text-base"><?php echo $review['reviewer_name']; ?></p>
                                    <p class="text-xs md:text-sm text-gray-600"><?php echo date('F d, Y', strtotime($review['review_date'])); ?></p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <?php if ($review['has_purchased']) { ?>
                                    <i class="fa-solid fa-circle-check text-temp text-lg"></i>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="flex items-center justify-center my-1 float-right">
                            <div class="rating2 float-right text-lg md:text-xl">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $review['rating']) {
                                        echo '<i class="fa-solid fa-star text-temp text-sm"></i>';
                                    } else {
                                        echo '<i class="fa-regular fa-star text-gray-400 text-sm"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <span class="rounded text-sm font-semibold ml-2"><?php echo $review['rating']; ?></span>
                        </div>
                    </div>
                    <div class="review-details mt-2 md:mt-8">
                        <p class="text-xs md:text-sm font-medium"><?php echo $review['review_title']; ?></p>
                        <p class="text-xs md:text-sm text-neutral-600 mt-4"><?php echo $review['review_text']; ?></p>
                    </div>
                </div>
    
                <hr class="border my-4 border-gray-400">
                <?php
                }
            } else {
                echo "<p class='text-center text-gray-500'>No reviews yet. Be the first to review this book!</p>";
            }
            ?>
            </div>
        </div>
    </div>

    <?php
        if($relatedBooksResult->num_rows > 0){
    ?>
    <section class="relative mt-8 md:mx-6 mx-3 mb-6">
        <h1 class="text-base md:text-2xl ml-8 mb-4 font-medium text-blue-700">You might also like</h1>
            <div class="master-container w-full">
                <div class="swiper you-like-swiper">
                    <div class="swiper-wrapper">
                        <?php
                            while ($relatedBook = $relatedBooksResult->fetch_assoc()){
                        ?>
                        <div class="swiper-slide">
                            <div class="bg-white w-60 h-fit flex items-center flex-col border-2 shadow-md rounded-lg py-3 px-4 relative justify-center">
                                <div class="w-32 h-52">
                                    <a href="product.php?book_id=<?php echo $relatedBook['b_id']; ?>">
                                        <img src="files/book_cover/<?php echo $relatedBook['b_cover_tmp']; ?>" alt="">
                                    </a>
                                    <a href="add_to_wishlist.php?book_id=<?php echo $relatedBook['b_id']; ?>" class="like absolute top-2 right-2 cursor-pointer">
                                        <i class="fa-heart text-xl p-2 <?php echo isset($_SESSION['user']) && in_array($relatedBook['b_id'], $wishlist_books) ? 'fa-solid text-red-600' : 'fa-regular text-black'; ?>"></i>
                                    </a>
                                </div>
                                <div class="mt-2 w-full">
                                    <a href="product.php?book_id=<?php echo $relatedBook['b_id']; ?>">
                                        <h5 class="text-base md:text-lg tracking-tight text-slate-900 capitalize overflow-hidden text-ellipsis whitespace-nowrap font-medium md:w-52"><?php echo $relatedBook['b_name']; ?></h5>
                                    </a>
                                    <div class="mt-1 flex items-center justify-between">
                                        <p>
                                            <span class="text-base md:text-xl font-bold text-slate-900">₹<?php echo $relatedBook['b_price']; ?></span>
                                            <?php if ($relatedBook['b_discount'] > 0) { ?>
                                                <span class="text-xs md:text-sm text-slate-900 line-through">₹<?php echo floor($relatedBook['b_price'] - ($relatedBook['b_price'] * $relatedBook['b_discount'] / 100)); ?></span>
                                            <?php } ?>
                                        </p>
                                        <p>
                                            <span class="text-xs md:text-sm font-normal"><?php echo $relatedBook['b_category']; ?></span>
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-start my-1">
                                        <div class="rating2 float-right text-lg md:text-xl">
                                        <?php
                                            $avg_rating = round($relatedBook['avg_rating'], 1);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= round($avg_rating)) {
                                                    echo '<i class="fa-solid fa-star text-temp text-sm"></i>'; // Filled star
                                                } else {
                                                    echo '<i class="fa-regular fa-star text-gray-400 text-sm"></i>'; // Empty star
                                                }
                                            }
                                        ?>
                                        </div>
                                        <span class="rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-semibold ml-2"><?php echo number_format($avg_rating, 1); ?></span>
                                    </div>
                                    <a href="login.php" class="flex items-center justify-center rounded-md bg-slate-900 px-5 py-2.5 text-center text-xs md:text-sm font-medium text-white hover:bg-gray-700">Add to cart</a>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?> 
                    </div>
                </div>
                <button class="swiper-button-next you-like-btn-next"></button>
                <button class="swiper-button-prev you-like-btn-prev"></button>
            </div>
    </section>
    <?php
        }
    ?>

    <?php require_once('footer.php'); ?>
    
    <script src="files/js_files/swiper_slider.js?v=<?php echo time(); ?>"></script>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>