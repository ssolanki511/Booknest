<?php
    ob_start();
    include_once('db_connect.php');
    session_start();

    if (!isset($_SESSION['user'])) {
        setcookie('error', 'Please log in to view your wishlist.', time()+3, '/');
        ?>
        <script>window.location.href = "login.php"</script>
        <?php
    }
    $user_id = $_SESSION['user'];

    $wishlistQuery = "SELECT w.id AS wishlist_id, b.b_id, b.b_name, b.b_category, b.b_price, b.b_cover_tmp FROM wishlist w INNER JOIN books b ON w.book_id = b.b_id WHERE w.user_id = '$user_id'";
    $wishlistResult = $con->query($wishlistQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/add-on/swiper-bundle.min.css">
    <script src="files/add-on/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
</head>

<body class="bg-gray-100">
    
    <?php include_once('cookie_display.php'); ?>
    <?php include 'header.php'; ?>

    <header class="text-center my-8">
        <h1 class="text-2xl md:text-4xl font-bold text-black">Wishlist Item</h1>
    </header>
    <?php
        if($wishlistResult->num_rows > 0) {
    ?>
        <main class="container mx-auto p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-700 text-white text-left">
                        <tr>
                            <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">No</th>
                            <th class="w-3/12 py-3 px-4 uppercase font-semibold text-sm">Book Name</th>
                            <th class="w-3/12 py-3 px-4 uppercase font-semibold text-sm">Category</th>
                            <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm">Price</th>
                            <th class="w-3/12 py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php
                            $count = 1;
                            while ($row = $wishlistResult->fetch_assoc()) {
                        ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="py-3 px-4"><?php echo $count++; ?></td>
                                <td class="py-3 px-4">
                                    <a href="product.php?book_id=<?php echo $row['b_id']; ?>">
                                        <?php echo $row['b_name']; ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4"><?php echo $row['b_category']; ?></td>
                                <td class="py-3 px-4">₹<?php echo $row['b_price']; ?></td>
                                <td class="py-3 px-4 flex justify-between">
                                    <a href="book_add_to_cart.php?book_id=<?php echo $row['b_id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Add to Cart</a>
                                    <a href="remove_wishlist_item.php?wishlist_id=<?php echo $row['wishlist_id']; ?>" class="text-red-500 hover:text-red-700 px-10">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    <?php
        }else{
            ?>
                <p class="text-red-600 font-bold text-center text-lg md:text-2xl">Your wishlist is empty.</p>
            <?php
        }
    ?>

    <?php include 'footer.php'; ?>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>