<?php
    include_once('db_connect.php');
    session_start();
    $wishlist_books = [];
    if(!isset($_SESSION['user'])){
        unset($_SESSION['user']);
        $_SESSION['guest'] = 'guest';
    }else{
        unset($_SESSION['guest']);

        $user_id = $_SESSION['user'];
        $wishlist_query = "SELECT `book_id` FROM `wishlist` WHERE `user_id` = $user_id";
        $wishlist_result = $con->query($wishlist_query);
    
        if ($wishlist_result->num_rows > 0) {
            while ($row = $wishlist_result->fetch_assoc()) {
                $wishlist_books[] = $row['book_id'];
            }
        }
    }
    
    $all_banner = $con->query("SELECT `banner_img` FROM `banner` WHERE `status` = 'active'");
    $all_banner->fetch_assoc();

    // $trendingBooksQuery = "
    // SELECT 
    //     b.b_id, b.b_name, b.b_price, b.b_discount, b.b_category, b.b_cover_tmp, COUNT(p.purchase_id) AS purchase_count
    //     FROM books b
    //     LEFT JOIN purchases p 
    //     ON b.b_id = p.book_id
    //     GROUP BY b.b_id
    //     ORDER BY purchase_count DESC, b.b_id DESC
    //     LIMIT 5
    // ";
    // $trendingBooksResult = $con->query($trendingBooksQuery);
    $trendingBooksQuery = "
        SELECT 
            b.b_id, 
            b.b_name, 
            b.b_price, 
            b.b_discount, 
            b.b_category, 
            b.b_cover_tmp, 
            COUNT(p.purchase_id) AS purchase_count,
            IFNULL(AVG(r.rating), 0) AS avg_rating
        FROM books b
        LEFT JOIN purchases p ON b.b_id = p.book_id
        LEFT JOIN reviews r ON b.b_id = r.book_id
        GROUP BY b.b_id
        ORDER BY purchase_count DESC, b.b_id DESC
        LIMIT 5
    ";
    $trendingBooksResult = $con->query($trendingBooksQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time() ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time() ?>">
    <link rel="stylesheet" href="files/add-on/swiper-bundle.min.css">
    <script src="files/add-on/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <title>Home - Booknest</title>
    <script src="files/add-on/jquery.min.js"></script>
</head>

<body class="bg-gray-100">

    <?php include_once('cookie_display.php'); ?>
    <!-- navbar -->
    <?php require_once('header.php');?>

    <!-- hero section -->
    <?php
        if($all_banner->num_rows > 0){
            ?>
            <section id="home" class="overflow-hidden">
                <div class="swiper mySwiper w-screen hero-swiper">
                    <div class="swiper-wrapper w-full">
                        <?php
                            foreach($all_banner as $banner){
                        ?>
                        <div class="swiper-slide w-full h-full border">
                            <img src="files/banners/<?php echo $banner['banner_img']; ?>" class="w-full h-full" alt="">
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </section>
            <?php
        }
    ?>

    <!-- new Trending section -->
    <?php
        if ($trendingBooksResult->num_rows > 0) {
    ?>
    <section class="flex justify-center items-center mt-10 w-full flex-col px-12 pb-10 " id="new-release">
        <h1 class="text-center mb-5 text-xl md:text-2xl font-semibold w-full text-white bg-black rounded-t-3xl py-2">Trending Now</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-12 gap-y-8">
            <?php
                while ($book = $trendingBooksResult->fetch_assoc()) {
                    // Calculate discounted price
                    $discountedPrice = floor($book['b_price'] - ($book['b_price'] * $book['b_discount'] / 100));
            ?>
                <div class="bg-white w-52 h-fit flex items-center flex-col border-2 shadow-md rounded-lg py-3 px-4 relative justify-center">
                    <div class="w-32 h-52">
                        <a href="product.php?book_id=<?php echo $book['b_id']; ?>">
                            <img src="files/book_cover/<?php echo $book['b_cover_tmp']; ?>" alt="Book Cover" class="w-full h-full object-cover">
                        </a>
                        <a href="add_to_wishlist.php?book_id=<?php echo $book['b_id']; ?>" class="like absolute top-2 right-2 cursor-pointer">
                            <i class="fa-heart text-xl p-2 <?php echo isset($_SESSION['user']) && in_array($book['b_id'], $wishlist_books) ? 'fa-solid text-red-600' : 'fa-regular text-black'; ?>"></i>
                        </a>
                    </div>
                    <div class="mt-2 w-full">
                        <a href="product.php?book_id=<?php echo $book['b_id']; ?>">
                            <h5 class="text-base md:text-lg tracking-tight text-slate-900 capitalize overflow-hidden text-ellipsis whitespace-nowrap font-medium "><?php echo $book['b_name']; ?></h5>
                            <div class="mt-1 flex items-center justify-between w-full">
                                <p>
                                    <span class="text-base md:text-xl font-bold text-slate-900">₹<?php echo $discountedPrice; ?></span>
                                    <?php if ($book['b_discount'] > 0) { ?>
                                        <span class="text-xs md:text-sm text-slate-900 line-through">₹<?php echo $book['b_price']; ?></span>
                                    <?php } ?>
                                </p>
                                <p>
                                    <span class="text-xs md:text-sm font-normal"><?php echo $book['b_category']; ?></span>
                                </p>
                            </div>
                            <div class="flex items-center justify-start my-1 w-full">
                                <div class="rating2 float-right text-lg md:text-xl">
                                <?php
                                    $avg_rating = round($book['avg_rating']); 
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $avg_rating) {
                                            echo '<i class="fa-solid fa-star text-temp text-sm"></i>'; 
                                        } else {
                                            echo '<i class="fa-regular fa-star text-gray-400 text-sm"></i>'; 
                                        }
                                    }
                                ?>
                                </div>
                                <span class="rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-semibold ml-2"><?php echo number_format($book['avg_rating'], 1); ?></span>
                            </div>
                            <a href="book_add_to_cart.php?book_id=<?php echo $book['b_id']; ?>" class="flex items-center justify-center rounded-md bg-slate-900 px-5 py-2.5 text-center text-xs md:text-sm font-medium text-white hover:bg-gray-700 w-full">Add to cart</a>
                        </a>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
    </section>
    <?php
        }
    ?>

    <!-- new release section -->
    <?php
        $new_release_books = $con->query("
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
            GROUP BY b.b_id
            ORDER BY b.b_publish_date DESC, b.b_id DESC
            LIMIT 5
        ");
        if($new_release_books->num_rows > 0){
            $new_release_books->fetch_assoc();
    ?>
    <section class="flex justify-center items-center mt-10 w-full flex-col px-16 pb-10" id="new-release">
        <h1 class="text-center mb-5 text-xl md:text-2xl font-semibold w-full text-white bg-black rounded-t-3xl py-2 ">New Release</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-12 gap-y-8">
            <?php
                foreach($new_release_books as $new_book){
                    $discounted_price = floor($new_book['b_price'] - ($new_book['b_price'] * $new_book['b_discount'] / 100));
                    ?>
                    <div class="bg-white w-52 h-fit flex items-center flex-col border-2 shadow-md rounded-lg py-3 px-4 relative justify-center">
                        <div class="w-32 h-52">
                            <a href="product.php?book_id=<?php echo $new_book['b_id']; ?>">
                                <img src="files/book_cover/<?php echo $new_book['b_cover_tmp']; ?>" alt="book cover">
                            </a>
                            <a href="add_to_wishlist.php?book_id=<?php echo $new_book['b_id']; ?>" class="like absolute top-2 right-2 cursor-pointer">
                                <i class="fa-heart text-xl p-2 <?php echo isset($_SESSION['user']) && in_array($new_book['b_id'], $wishlist_books) ? 'fa-solid text-red-600' : 'fa-regular text-black'; ?>"></i>
                            </a>
                        </div>
                        <div class="mt-2 w-full">
                            <a href="product.php?book_id=<?php echo $new_book['b_id']; ?>">
                                <h5 class="text-base md:text-lg tracking-tight text-slate-900 capitalize overflow-hidden text-ellipsis whitespace-nowrap font-medium"><?php echo $new_book['b_name']; ?></h5>
                                <div class="mt-1 flex items-center justify-between w-full">
                                    <p>
                                        <span class="text-base md:text-xl font-bold text-slate-900">₹<?php echo $new_book['b_price']; ?></span>
                                        <?php if ($new_book['b_discount'] > 0) { ?>
                                            <span class="text-xs md:text-sm text-slate-900 line-through">₹<?php echo $discounted_price; ?></span>
                                        <?php } ?>
                                    </p>
                                    <p>
                                        <span class="text-xs md:text-sm font-normal"><?php echo $new_book['b_category']; ?></span>
                                    </p>
                                </div>
                                <div class="flex items-center justify-start my-1 w-full">
                                    <div class="rating2 float-right text-lg md:text-xl">
                                    <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $avg_rating) {
                                                echo '<i class="fa-solid fa-star text-temp text-sm"></i>';
                                            } else {
                                                echo '<i class="fa-regular fa-star text-gray-400 text-sm"></i>';
                                            }
                                        }
                                    ?>
                                    </div>
                                    <span class="rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-semibold ml-2"><?php echo number_format($new_book['avg_rating'], 1); ?></span>
                                </div>
                                <a href="book_add_to_cart.php?book_id=<?php echo $new_book['b_id']; ?>" class="flex items-center justify-center rounded-md bg-slate-900 px-5 py-2.5 text-center text-xs md:text-sm font-medium text-white hover:bg-gray-700 w-full">Add to cart</a>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </section>
    <?php
        }
    ?>
    
    <?php include_once('footer.php'); ?>
    <script src="files/js_files/swiper_slider.js?v=<?php echo time() ?>"></script>
    <script src="files/js_files/home.js?v=<?php echo time() ?>"></script>
</body>

</html>