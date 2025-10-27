<?php
    include_once('../db_connect.php');
    session_start();

    if(isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
        $user_array = $con->query("SELECT * FROM `users` WHERE `user_id` = $user_id");
        if ($user_array->num_rows > 0) {
            $user = $user_array->fetch_assoc();
        } else {
            ?>
            <script>window.location.href = "admin_user.php";</script>
            <?php
            exit;
        }
    }else{
        ?>
        <script>window.location.href = "admin_user.php";</script>
        <?php
    }

    $order_query = "
        SELECT p.purchase_id, b.b_name, b.b_category, p.purchase_date, p.price_at_purchase
        FROM `purchases` p
        LEFT JOIN `books` b ON p.book_id = b.b_id
        WHERE p.user_id = $user_id
        ORDER BY p.purchase_date DESC
    ";
    $order_result = $con->query($order_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Detail - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
</head>
<body class="bg-main relative">

<?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">

        <?php require_once('admin_slidebar.php'); ?>

        <div class="px-6 py-5 w-full">
            <h3 class="font-medium text-base md:text-lg"><a href="admin_user.php" class="hover:underline hover:decoration-temp hover:text-temp">User</a>/</h3>
            <div class="flex w-full justify-center items-start">
                <div class="bg-gray-50 w-full rounded-lg mt-3 px-5 py-4 relative">
                    <div class="flex justify-center items-center gap-x-24 mt-2 flex-col md:flex-row gap-y-6">
                        <div class="md:w-52 md:h-52 w-32 h-32 group">
                            <img src="../files/user_images/<?php echo $user['user_img']; ?>" class="w-full h-full object-cover group-hover:cursor-pointer group-hover:opacity-85 rounded-full" alt="">
                        </div>
                        <div class="">
                            <table>
                                <tr>
                                    <th><p class="text-sm md:text-lg font-medium text-left">Username:</p></th>
                                    <td><p class="text-sm md:text-base text-gray-500 capitalize ml-3"><?php echo $user['name']; ?></p></td>
                                </tr>
                                <tr>
                                    <th><p class="text-sm md:text-lg font-medium text-left">Email:</p></th>
                                    <td><p class="text-sm md:text-base text-gray-500 capitalize ml-3"><?php echo $user['email']; ?></p></td>
                                </tr>
                                <tr>
                                    <th><p class="text-sm md:text-lg font-medium text-left">Joining date:</p></th>
                                    <td><p class="text-sm md:text-base text-gray-500 ml-3"><?php echo date("d F Y", strtotime($user['register_date'])); ?></p></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="absolute top-4 right-6">
                        <a href="admin_user_form.php?user_id=<?php echo $user['user_id']; ?>" class="py-1 px-2 md:px-2 md:text-base bg-green-600 text-white rounded-md hover:bg-green-700 text-xs">Edit</a>
                        <a href="admin_user_delete.php?user_email=<?php echo $user['email']; ?>" class="py-1 px-2 md:px-2 md:text-base bg-red-600 text-white rounded-md hover:bg-red-700 text-xs">Delete</a>
                    </div>

                    <div class="mt-6">
                        <h1 class="mb-2 font-medium text-base md:text-lg text-temp">Order Summary</h1>
                        <div class="relative flex flex-col w-full h-full text-gray-700 bg-white shadow-md overflow-scroll md:overflow-auto">
                            <table class="w-full text-left border border-gray-500">
                                <thead class="text-nowrap">
                                    <tr class="text-slate-500 border-b border-slate-300 bg-slate-50 text-sm md:text-base">
                                        <th class="p-2 md:p-4">
                                            <p class="block font-normal leading-none">#</p>
                                        </th>
                                        <th class="p-2 md:p-4">
                                            <p class="block font-normal leading-none">Title</p>
                                        </th>
                                        <th class="p-2 md:p-4">
                                            <p class="block font-normal leading-none">category</p>
                                        </th>
                                        <th class="p-2 md:p-4">
                                            <p class="block font-normal leading-none">Order Date</p>
                                        </th>
                                        <th class="p-2 md:p-4">
                                            <p class="block font-normal leading-none">Price</p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-nowrap">
                                <?php
                                    if ($order_result->num_rows > 0) {
                                        $i = 1;
                                        $total_price = 0;
                                        while ($order = $order_result->fetch_assoc()) {
                                            $total_price += $order['price_at_purchase'];
                                            ?>
                                            <tr class="hover:bg-slate-50 border-t border-gray-200 text-sm md:text-base text-slate-800">
                                                <td class="p-2 md:p-4">
                                                    <p class="block"><?php echo $i++; ?></p>
                                                </td>
                                                <td class="p-2 md:p-4">
                                                    <p class="block">
                                                        <?php echo $order['b_name'] ? $order['b_name'] : '<span class="text-gray-500">Book no longer available</span>'; ?>
                                                    </p>
                                                </td>
                                                <td class="p-2 md:p-4">
                                                    <p class="block"><?php echo $order['b_category']; ?></p>
                                                </td>
                                                <td class="p-2 md:p-4">
                                                    <p class="block"><?php echo date("d F Y", strtotime($order['purchase_date'])); ?></p>
                                                </td>
                                                <td class="p-2 md:p-4">
                                                    <p class="block">₹<?php echo $order['price_at_purchase']; ?></p>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5" class="p-4 text-center text-gray-500">No orders found for this user.</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <?php if ($order_result->num_rows > 0) { ?>
                                    <tfoot class="text-nowrap border-t border-gray-900">
                                        <tr class="text-sm md:text-base text-slate-800">
                                            <td colspan="4" class="p-2 md:p-4 text-left font-bold">Total</td>
                                            <td colspan="1" class="p-2 md:p-4 font-semibold">₹<?php echo number_format($total_price, 2); ?></td>
                                        </tr>
                                    </tfoot>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>