<?php
include_once('db_connect.php');
session_start();

$wishlist_books = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $category = isset($_GET['category']) ? $con->real_escape_string($_GET['category']) : '';
    $price_range = isset($_GET['price_range']) ? $con->real_escape_string($_GET['price_range']) : '';
    $search_query = isset($_GET['search_query']) ? $con->real_escape_string($_GET['search_query']) : '';
    
    // Fetch books based on category and price range
    $price_condition = '';
    $order_by = 'b.b_publish_date DESC, b.b_id DESC'; // Default ordering

    if (!empty($price_range)) {
        if ($price_range === 'low to high') {
            $order_by = 'b.b_price ASC';
        } elseif ($price_range === 'high to low') {
            $order_by = 'b.b_price DESC';
        } elseif ($price_range === 'relevant') {
            $order_by = 'b.b_publish_date DESC, b.b_id DESC';
        }
    }

    if ($category === '' || $category === 'All') {
        $query = "
            SELECT 
                b.b_id, 
                b.b_name, 
                b.b_author, 
                b.b_price, 
                b.b_discount, 
                b.b_category, 
                b.b_cover_tmp, 
                IFNULL(AVG(r.rating), 0) AS avg_rating
            FROM books b
            LEFT JOIN reviews r ON b.b_id = r.book_id
            WHERE 1=1 
            " . (!empty($price_condition) ? $price_condition : '') . "
            " . (!empty($search_query) ? "AND (b.b_name LIKE '%$search_query%' OR b.b_author LIKE '%$search_query%' OR b.b_category LIKE '%$search_query%')" : '') . "
            GROUP BY b.b_id
            ORDER BY $order_by
        ";
    } else {
        $query = "
            SELECT 
                b.b_id, 
                b.b_name, 
                b.b_author, 
                b.b_price, 
                b.b_discount, 
                b.b_category, 
                b.b_cover_tmp, 
                IFNULL(AVG(r.rating), 0) AS avg_rating
            FROM books b
            LEFT JOIN reviews r ON b.b_id = r.book_id
            WHERE b.b_category = '$category' 
            " . (!empty($price_condition) ? $price_condition : '') . "
            " . (!empty($search_query) ? "AND (b.b_name LIKE '%$search_query%' OR b.b_author LIKE '%$search_query%' OR b.b_category LIKE '%$search_query%')" : '') . "
            GROUP BY b.b_id
            ORDER BY $order_by
        ";
    }
    
    $result = $con->query($query);

    if(isset($_SESSION['user'])){
        $user_id = $_SESSION['user'];
        $wishlist_query = "SELECT `book_id` FROM `wishlist` WHERE `user_id` = $user_id";
        $wishlist_result = $con->query($wishlist_query);

        if ($wishlist_result->num_rows > 0) {
            while ($row = $wishlist_result->fetch_assoc()) {
                $wishlist_books[] = $row['book_id'];
            }
        }
    }

    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            $discounted_price = floor($book['b_price'] - ($book['b_price'] * $book['b_discount'] / 100));
            ?>
            <div class="bg-white w-60 h-fit flex items-center flex-col border-2 shadow-md rounded-lg py-3 px-4 relative justify-center">
                <div class="w-32 h-52">
                    <a href="product.php?book_id=<?php echo $book['b_id']; ?>">
                        <img src="files/book_cover/<?php echo $book['b_cover_tmp']; ?>" alt="book cover">
                    </a>
                    <a href="add_to_wishlist.php?book_id=<?php echo $book['b_id']; ?>" class="like absolute top-2 right-2 cursor-pointer">
                        <i class="fa-heart text-xl p-2 <?php echo isset($_SESSION['user']) && in_array($book['b_id'], $wishlist_books) ? 'fa-solid text-red-600' : 'fa-regular text-black'; ?>"></i>
                    </a>
                </div>
                <div class="mt-2 w-full">
                    <a href="product.php?book_id=<?php echo $book['b_id']; ?>">
                        <h5 class="text-base md:text-lg tracking-tight text-slate-900 capitalize overflow-hidden text-ellipsis whitespace-nowrap font-medium md:w-52"><?php echo $book['b_name']; ?></h5>
                    </a>
                    <div class="mt-1 flex items-center justify-between">
                        <p>
                            <span class="text-base md:text-xl font-bold text-slate-900">₹<?php echo $book['b_price']; ?></span>
                            <?php if ($book['b_discount'] > 0) { ?>
                                <span class="text-xs md:text-sm text-slate-900 line-through">₹<?php echo $discounted_price; ?></span>
                            <?php } ?>
                        </p>
                        <p>
                            <span class="text-xs md:text-sm font-normal"><?php echo $book['b_category']; ?></span>
                        </p>
                    </div>
                    <div class="flex items-center justify-start my-1">
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
                    <a href="book_add_to_cart.php?book_id=<?php echo $book['b_id']; ?>" class="flex items-center justify-center rounded-md bg-slate-900 px-5 py-2.5 text-center text-xs md:text-sm font-medium text-white hover:bg-gray-700">Add to cart</a>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='text-red-600 font-bold text-start ml-4'>No books found matching your criteria.</p>";
    }
} else {
    echo "<p class=`text-red-600 font-bold text-start ml-4`>Invalid category selected.</p>";
}
?>