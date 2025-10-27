<?php
    ob_start();
    include_once('db_connect.php');
    session_start();
    if(isset($_SESSION['guest'])){
        setcookie('error', 'Please login to view your cart', time() + 3, '/');
        ?>
        <script>window.location.href = "login.php"; </script>
        <?php
    }
    $user_id = $_SESSION['user'];

    $cartQuery = "SELECT c.id AS cart_id, b.b_id, b.b_name, b.b_author, b.b_price, b.b_cover_tmp FROM add_to_cart c INNER JOIN books b ON c.book_id = b.b_id WHERE c.user_id = '$user_id'";

    $cartResult = $con->query($cartQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <script src="files/add-on/jquery.min.js"></script>
</head>

<body class="bg-gray-100">
    <?php include_once('cookie_display.php'); ?>
    <?php include_once('header.php'); ?>

    <header class="text-center my-8">
        <h1 class="text-2xl md:text-4xl font-bold text-black">Cart Item</h1>
    </header>

    <?php
        $totalPrice = 0;
        if ($cartResult->num_rows > 0) {
    ?>
    <div class="container mx-auto px-4 overflow-x-auto">
        <div class="bg-white shadow-md rounded-lg p-6  ">
            <h2 class="text-2xl font-semibold mb-4">Your Cart Items</h2>
            <div class="space-y-4 text-nowrap">
                <?php
                    while ($row = $cartResult->fetch_assoc()) {
                        $totalPrice += $row['b_price'];
                ?>
                <div class="flex items-center justify-between p-4 border rounded-lg ">
                    <div class="flex items-center">
                        <a href="product.php?book_id=<?php echo $row['b_id']; ?>">
                            <img src="files/book_cover/<?php echo $row['b_cover_tmp']; ?>" alt="Book Cover" class="w-16 h-24 object-cover rounded-lg">
                        </a>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold"><?php echo $row['b_name']; ?></h3>
                            <p class="text-gray-600"><?php echo $row['b_author']; ?></p>
                            <p class="text-gray-800 font-bold">₹<?php echo $row['b_price']; ?></p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 pt-2">
                        <!-- <a href="payment.php?book_id=<?php echo $row['b_id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Buy Now</a> -->
                        
                        <a href="remove_cart.php?cart_id=<?php echo $row['cart_id']; ?>" class="text-red-500 hover:text-red-700 mr-4">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="mt-6 text-right">
                <p class="text-xl font-semibold">Total: ₹<?php echo $totalPrice; ?></p>
                <div class="flex justify-end">
                    <a href="payment.php" class="block w-fit mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <?php
        }else{
            ?>
            <p class='text-red-600 font-bold text-center text-lg md:text-2xl'>Your cart is empty.</p>
            <?php
        }
    ?>
    <?php include 'footer.php'; ?>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>