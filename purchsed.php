<?php
    include_once('db_connect.php');
    session_start();

    if (!isset($_SESSION['user'])) {
        setcookie('error', 'Please log in to view your purchases.', time()+3, '/');
        ?>
        <script>window.location.href = "login.php";</script>
        <?php
    }
    
    $user_id = $_SESSION['user'];

    $purchasesQuery = "
        SELECT p.purchase_id, p.purchase_date, p.price_at_purchase, b.b_id, b.b_name, b.b_author, b.b_cover_tmp 
        FROM `purchases` p
        LEFT JOIN `books` b ON p.book_id = b.b_id
        WHERE p.user_id = '$user_id'
        ORDER BY p.purchase_date DESC
    ";
    $purchasesResult = $con->query($purchasesQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchased - Booknest</title>
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
</head>

<body class="bg-gray-100">
    <?php include 'header.php'; ?>

    <header class="text-center my-8">
        <h1 class="text-4xl font-bold text-black">Purchsed Item</h1>
    </header>
    <?php
        if($purchasesResult->num_rows > 0){
    ?>
    <div class="container mx-auto px-4">
        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full bg-white text-nowrap">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="py-2 px-4 border-b">No</th>
                        <th class="py-2 px-4 border-b text-left">Image</th>
                        <th class="py-2 px-4 border-b text-left">Book Detail</th>
                        <th class="py-2 px-4 border-b">Date</th>
                        <th class="py-2 px-4 border-b">Amount</th>
                        <th class="py-2 px-4 border-b">Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $count = 1;
                        while ($purchase = $purchasesResult->fetch_assoc()) {
                    ?>
                    <tr>
                        <td class="py-2 px-4 border-b text-center"><?php echo $count++; ?></td>
                        <td class="py-2 px-4 border-b text-center">
                            <?php if ($purchase['b_cover_tmp']) { ?>
                                <a href="product.php?book_id=<?php echo $purchase['b_id']; ?>">
                                    <img src="files/book_cover/<?php echo $purchase['b_cover_tmp']; ?>" alt="Book Image" class="w-16 h-24 object-cover">
                                </a>
                            <?php } else { ?>
                                <span class="text-gray-500">Not Available</span>
                            <?php } ?>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <?php if ($purchase['b_name']) { ?>
                                <div class="text-lg font-semibold"><?php echo $purchase['b_name']; ?></div>
                                <div class="text-sm text-gray-600"><?php echo $purchase['b_author']; ?></div>
                            <?php } else { ?>
                                <span class="text-gray-500">Book no longer available</span>
                            <?php } ?>
                        </td>
                        <td class="py-2 px-4 border-b text-center"><?php echo date('d-m-Y', strtotime($purchase['purchase_date'])); ?></td>
                        <td class="py-2 px-4 border-b text-center">₹<?php echo $purchase['price_at_purchase']; ?></td>
                        <td class="py-2 px-4 border-b text-center">
                            <?php if ($purchase['b_name']) { ?>
                                    <a href="download.php?book_id=<?php echo $purchase['b_id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Download</a>
                            <?php } else { ?>
                                    <span class="text-gray-500">Not Available</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        }else{
            ?>
            <p class='text-red-600 font-bold text-center text-lg md:text-2xl'>No purchases found.</p>
            <?php
        }
    ?>

    <?php include 'footer.php'; ?>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>

</body>

</html>