<?php
    ob_start();
    include_once('../db_connect.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Form - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
    <script src="../files/add-on/jquery.min.js"></script>
    <script src="../files/add-on/jquery.validate.min.js"></script>
    <script src="../files/add-on/additional-methods.min.js"></script>
</head>
<body class="bg-main relative">

    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        <?php require_once('admin_slidebar.php'); ?>

        <div class="px-4 py-3 w-full" >
        <h3 class="font-medium text-base md:text-lg"><a href="admin_dashboard.php" class="hover:underline hover:decoration-temp hover:text-temp">Dashboard</a>/</h3>
            <div class="coupon-form w-full flex justify-center items-center">
                <div class="bg-white w-full md:w-3/4 py-5 rounded-md px-6 relative">
                    <form action="dashboard_coupon.php" class="flex flex-col gap-y-3" id="coupon-form" method="post">
                        <h5 class="text-temp text-center font-medium text-base md:text-lg mb-2">Coupon Code Form</h5>
                        <div class="flex gap-x-4 gap-y-2 flex-col md:flex-row">
                            <div class="w-full md:w-1/2">
                                <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Enter Title</label>
                                <input type="text" name="coupon_title" class="border border-gray-300 rounded-lg px-2 py-1 text-sm md:text-base focus:outline-1 focus:outline-temp w-full">
                            </div>
                            <div class="w-full md:w-1/2">
                                <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Enter Code</label>
                                <input type="text" name="coupon_code" class="border border-gray-300 rounded-lg px-2 py-1 text-sm md:text-base focus:outline-1 focus:outline-temp w-full">
                            </div>
                        </div>
                        <div class="flex gap-x-6 flex-col gap-y-2 md:flex-row">
                            <div class="w-full md:w-1/2">
                                <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Start Date</label>
                                <input type="date" name="coupon_start" class="border border-gray-300 px-3 py-1 rounded-md  text-sm md:text-base focus:outline-1 focus:outline-temp">
                            </div>
                            <div class="w-full md:w-1/2">
                                <label for="" class="block font-medium text-gray-700 mb-1 md:mb-2 text-sm md:text-base">End Date</label>
                                <input type="date" name="coupon_end" class="border border-gray-300 px-3 py-1 rounded-md text-sm md:text-base focus:outline-1 focus:outline-temp">
                            </div>
                        </div>
                        <div>
                            <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Type</label>
                            <div class="flex flex-col">
                                <span>
                                    <input type="radio" value="percentage" class="coupon-type-radio text-sm md:text-base" id="percent" name="type">
                                    <label for="percent" class="text-sm md:text-base">Percentage</label>
                                </span>
                                <span>
                                    <input type="radio" value="value" id="val" name="type" class="coupon-type-radio text-sm md:text-base">
                                    <label for="val" class="text-sm md:text-base">Price</label>
                                </span>
                                <div class="error-coupon-type"></div>
                            </div>
                        </div>
                        <div>
                            <div class="parcentage-field hidden">
                                <div class="flex flex-col w-fit gap-y-2">
                                    <label for="" class="text-sm text-gray-800 font-medium md:text-base">Enter Parcentage</label>
                                    <span class="border border-gray-600 w-fit">
                                        <i class="fa-solid fa-percent py-1.5 px-2 md:px-3 md:py-2 text-white text-base bg-temp"></i>
                                        <input type="number" name="coupon_parecent" class="outline-none px-2 w-40 h-full">
                                    </span>
                                    <div class="error-parecent"></div>
                                </div>
                            </div>
                            <div class="value-field hidden">
                                <div class="flex flex-col w-fit gap-y-2">
                                    <label for="" class="text-sm text-gray-800 font-medium md:text-base">Enter Price</label>
                                    <span class="border border-gray-600 w-fit">
                                        <i class="fa-solid fa-indian-rupee-sign py-1.5 px-2 md:px-3 md:py-2 text-white text-base bg-temp"></i>
                                        <input type="number" name="coupon_price" class="outline-none px-2 w-40 h-full">
                                    </span>
                                    <div class="error-price"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center md:pt-2">
                            <input type="submit" value="Submit" name="coupon_sub" class="py-1 px-3 bg-temp text-white rounded-md hover:bg-blue-800 cursor-pointer text-sm md:text-base">
                        </div>
                    </form>
                </div>
            </div>            
        </div>

    </div>
    <script src="../files/js_files/admin_valid.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_coupon_form.js?v<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_coupon.js?v<?php echo time(); ?>"></script>
</body>
</html>
<?php
if (isset($_POST['coupon_sub'])) {
    $coupon_title = $_POST['coupon_title'];
    $coupon_code = $_POST['coupon_code'];
    $coupon_type = $_POST['type'];
    $coupon_value = ($coupon_type === 'percentage') ? $_POST['coupon_parecent'] : $_POST['coupon_price'];
    $start_date = $_POST['coupon_start'];
    $end_date = $_POST['coupon_end'];

    $query = "INSERT INTO `coupons` (`coupon_title`, `coupon_code`, `coupon_type`, `coupon_value`, `start_date`, `end_date`) VALUES ('$coupon_title', '$coupon_code', '$coupon_type', '$coupon_value', '$start_date', '$end_date')";

    if ($con->query($query)) {
        setcookie('success', 'Coupon added successfully.', time() + 3, '/');
    } else {
        setcookie('error', 'Failed to add coupon.', time() + 3, '/');
    }
    ?>
    <script>window.location.href = "admin_dashboard.php";</script>
    <?php
}
?>