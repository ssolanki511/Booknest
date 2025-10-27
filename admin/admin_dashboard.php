<?php
    ob_start();
    include('../db_connect.php');
    session_start();
    if(isset($_SESSION['admin'])){
        $user_id = $_SESSION['admin'];
    }else{
        setcookie('error', 'Please login in to access admin panel', time()+3, '/');
        ?>
        <script>window.location.href = "login.php"</script>
        <?php
    }

    $totalBooksQuery = "SELECT COUNT(*) AS total_books FROM `books`";
    $totalBooksResult = $con->query($totalBooksQuery);
    $totalBooks = $totalBooksResult->fetch_assoc()['total_books'];

    // Fetch total revenue
    $totalRevenueQuery = "SELECT SUM(price_at_purchase) AS total_revenue FROM `purchases`";
    $totalRevenueResult = $con->query($totalRevenueQuery);
    $totalRevenue = $totalRevenueResult->fetch_assoc()['total_revenue'] ?? 0;

    // Fetch total book sales
    $totalBookSalesQuery = "SELECT COUNT(*) AS total_sales FROM `purchases`";
    $totalBookSalesResult = $con->query($totalBookSalesQuery);
    $totalBookSales = $totalBookSalesResult->fetch_assoc()['total_sales'];

    // Fetch total users
    $totalUsersQuery = "SELECT COUNT(*) AS total_users FROM `users` WHERE `usertype` = 'User'";
    $totalUsersResult = $con->query($totalUsersQuery);
    $totalUsers = $totalUsersResult->fetch_assoc()['total_users'];

    $bookSalesQuery = "
        SELECT DATE(p.purchase_date) AS sale_date, COUNT(p.purchase_id) AS total_sales FROM purchases p
        WHERE p.purchase_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(p.purchase_date) ORDER BY sale_date ASC
    ";
    $bookSalesResult = $con->query($bookSalesQuery);

    $bookSalesData = [];
    while ($row = $bookSalesResult->fetch_assoc()) {
        $bookSalesData[] = [
            'x' => date('D', strtotime($row['sale_date'])), // Day of the week
            'y' => (int)$row['total_sales'], // Total sales
        ];
    }

    // Fetch revenue data for the last 7 days
    $revenueQuery = "
        SELECT DATE(p.purchase_date) AS revenue_date, SUM(p.price_at_purchase) AS total_revenue FROM purchases p
        WHERE p.purchase_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(p.purchase_date) ORDER BY revenue_date ASC
    ";
    $revenueResult = $con->query($revenueQuery);

    $revenueData = [];
    while ($row = $revenueResult->fetch_assoc()) {
        $revenueData[] = [
            'x' => date('D', strtotime($row['revenue_date'])), // Day of the week
            'y' => (float)$row['total_revenue'], // Total revenue
        ];
    }

    $couponsQuery = "SELECT coupon_id, coupon_title, coupon_code, coupon_type, coupon_value, end_date FROM coupons ORDER BY end_date ASC";
    $couponsResult = $con->query($couponsQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
    <script src="../files/add-on/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="../files/add-on/jquery.validate.min.js"></script> 
    <script src="../files/add-on/additional-methods.min.js"></script>
    <script src="../files/add-on/apexcharts.min.js"></script>
</head>
<body class="bg-main relative">

    <?php include_once('../cookie_display.php'); ?>
    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        <?php require_once('admin_slidebar.php'); ?>

        <div class="admin-dashbord-container px-4 py-3 w-full">
            <h1 class="dashboard-text text-temp font-medium md:ml-1.5 lg:ml-2 mb-2 text-lg md:text-2xl">Dashboard</h1>
            <div class="sell-data-box grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-y-4 gap-x-5 w-full place-items-center ">
                <div class="dashboard-boxs bg-white w-full md:w-11/12 px-4 py-3 flex flex-col justify-start gap-y-2">
                    <div class="text-temp bg-gray-400 bg-opacity-20 w-10 h-10 md:w-12 md:h-12 grid place-content-center rounded-full text-lg">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="ml-2">
                        <h4 class="text-lg md:text-2xl font-medium"><?php echo $totalBooks; ?></h4>
                        <p class="text-gray-500 text-xs md:text-sm">Total Books</p>
                    </div>
                </div>
                <div class="dashboard-boxs bg-white w-full md:w-11/12 px-4 py-3 flex flex-col justify-start gap-y-2">
                    <div class="text-temp bg-gray-400 bg-opacity-20 w-10 h-10 md:w-12 md:h-12 grid place-content-center rounded-full text-lg">
                        <i class="fa-solid fa-cart-shopping fa-flip-horizontal"></i>
                    </div>
                    <div class="ml-2">
                        <h4 class="text-lg md:text-2xl font-medium">₹<?php echo $totalRevenue; ?></h4>
                        <p class="text-gray-500 text-xs md:text-sm">Total Revenue</p>
                    </div>
                </div>
                <div class="dashboard-boxs bg-white w-full md:w-11/12 px-4 py-3 flex flex-col justify-start gap-y-2">
                    <div class="text-temp bg-gray-400 bg-opacity-20 w-10 h-10 md:w-12 md:h-12 grid place-content-center rounded-full text-lg">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div class="ml-2">
                        <h4 class="text-lg md:text-2xl font-medium"><?php echo $totalBookSales; ?></h4>
                        <p class="text-gray-500 text-xs md:text-sm">Total Book Sales</p>
                    </div>
                </div>
                <div class="dashboard-boxs bg-white w-full md:w-11/12 px-4 py-3 flex flex-col justify-start gap-y-2">
                    <div class="text-temp bg-gray-400 bg-opacity-20 w-10 h-10 md:w-12 md:h-12 grid place-content-center rounded-full text-lg">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="ml-2">
                        <h4 class="text-lg md:text-2xl font-medium"><?php echo $totalUsers; ?></h4>
                        <p class="text-gray-500 text-xs md:text-sm">Total Users</p>
                    </div>
                </div>
            </div>

            <div class="flex mt-5 md:mt-6 md:ml-2.5 justify-between items-center relative md:space-x-5 space-y-5 md:space-y-0 md:mr-2.5 flex-wrap md:flex-nowrap">
                
                <div class="chart-box w-full md:w-7/12 md:h-80 bg-white shadow-sm px-8 py-4">
                    <div class="flex space-x-2 md:space-x-3">
                        <div class="flex">
                            <div class="text-temp text-xs md:text-sm">
                                <i class="fa-solid fa-circle border border-temp rounded-full p-0.5"></i>
                            </div>
                            <p class="text-sm md:text-base ml-2 text-temp font-medium">Book Sale</p>
                        </div>
                        <div class="flex">
                            <div class="text-red-300 text-xs md:text-sm">
                                <i class="fa-solid fa-circle border border-red-300 rounded-full p-0.5"></i>
                            </div>
                            <p class="text-sm md:text-base ml-2 text-red-300 font-medium">Revenue</p>
                        </div>
                    </div>
                    <div id="column-chart"></div>
                </div>

                <div class="coupon-board bg-white h-60 md:h-80 w-full md:w-5/12 relative overflow-y-scroll px-3 py-2">
                    <div class="flex justify-between items-center">
                        <h3 class="capitalize text-base md:text-lg font-medium text-temp">coupon code</h3>
                        <a href="dashboard_coupon.php" class=" py-1 px-2 text-sm md:text-base border border-temp text-temp hover:border-gray-700 hover:text-black rounded-lg">Add Coupon</a>
                    </div>
                    <table class="w-full text-left text-gray-500 mt-3">
                        <thead class="text-xs md:text-sm text-gray-700 uppercase bg-gray-100 text-center text-nowrap">
                            <tr>
                                <th scope="col" class="py-1 px-2">#</th>
                                <th scope="col" class="py-1 px-2">Title</th>
                                <th scope="col" class="py-1 px-2">Code</th>
                                <th scope="col" class="py-1 px-2">End Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        <?php
                            if ($couponsResult->num_rows > 0) {
                                $count = 1;
                                while ($coupon = $couponsResult->fetch_assoc()) {
                        ?>
                            <tr class="coupon-detail-open bg-white border-b border-gray-200 hover:bg-gray-50 text-sm md:text-base text-nowrap cursor-pointer" data-id="<?php echo $coupon['coupon_id'];?>">
                                <td class="font-normal py-1 px-2"><?php echo $count++; ?></td>
                                <td class="font-normal py-1 px-2 capitalize"><?php echo $coupon['coupon_title']; ?></td>
                                <td class="font-normal py-1 px-2"><?php echo $coupon['coupon_code']; ?></td>
                                <th class="font-normal py-1 px-2"><?php echo date('d M Y', strtotime($coupon['end_date'])); ?></th>
                            </tr>
                        <?php
                                }
                            }else{
                                echo "<tr><td colspan='7' class='text-center py-4 text-gray-500'>No coupons available.</td></tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="coupon-detail w-screen h-screen bg-gray-600 bg-opacity-15 fixed -top-full left-0 z-50 flex justify-center items-center duration-100 ease-linear">
        <div class="bg-white px-4 py-3 md:px-7 md:py-5 rounded relative">
            <h5 class="text-temp font-medium text-base md:text-lg text-center mb-3">Coupon Details</h5>
            <table class="text-nowrap">
                <tr>
                    <th class="px-3 md:px-6 py-1 text-left text-gray-900 text-sm md:text-base font-medium">Title</th>
                    <td class="px-3 md:px-6 py-1 text-sm md:text-base text-gray-500"></td>
                </tr>
                <tr>
                    <th class="px-3 md:px-6 py-1 text-left text-gray-900 text-sm md:text-base font-medium">Code</th>
                    <td class="px-3 md:px-6 py-1 text-sm md:text-base text-gray-500"></td>
                </tr>
                <tr>
                    <th class="px-3 md:px-6 py-1 text-left text-gray-900 text-sm md:text-base font-medium">Start Date</th>
                    <td class="px-3 md:px-6 py-1 text-sm md:text-base text-gray-500"></td>
                </tr>
                <tr>
                    <th class="px-3 md:px-6 py-1 text-left text-gray-900 text-sm md:text-base font-medium">End Date</th>
                    <td class="px-3 md:px-6 py-1 text-sm md:text-base text-gray-500"></td>
                </tr>
                <tr>
                    <th class="px-3 md:px-6 py-1 text-left text-gray-900 text-sm md:text-base font-medium">Discount</th>
                    <td class="px-3 md:px-6 py-1 text-sm md:text-base text-gray-500"></td>
                </tr>
                <tr>
                    <th class="px-3 md:px-6 py-1 text-left text-gray-900 text-sm md:text-base font-medium">Discount</th>
                    <td class="px-3 md:px-6 py-1 text-sm md:text-base text-gray-500"></td>
                </tr>
            </table>
            <div class="text-center mt-3">
                <a href="admin_dashboard.php" class="bg-red-600 py-1 px-2 text-white rounded-md text-sm md:text-base">Delete</a>
            </div>
            <button class="coupon-detail-close absolute top-2 right-3 text-temp text-lg md:text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>

    <script>
        const bookSalesData = <?php echo json_encode($bookSalesData); ?>;
        const revenueData = <?php echo json_encode($revenueData); ?>;
    </script>
    <script src="../files//js_files/graph.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_valid.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_coupon.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileTmpName = $_FILES['profile_picture']['tmp_name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileError = $_FILES['profile_picture']['error'];
        $fileType = $_FILES['profile_picture']['type'];

        if (!file_exists('../files/user_images')) {
            mkdir('../files/user_images');
        }

        $fileParts = explode('.', $fileName); // Assign the result of explode() to a variable
        $fileExt = strtolower(end($fileParts)); // Pass the variable to end()
        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) {
                    $fileNewName = uniqid('', true) . "." . $fileExt;
                    $fileDestination = '../files/user_images/' . $fileNewName;
                    move_uploaded_file($fileTmpName, $fileDestination);

                    $select = "SELECT `user_img` FROM `users` WHERE `user_id`='$user_id'";
                    $result = $con->query($select);
                    $row = $result->fetch_assoc();
                    $oldProfilePicture = $row['user_img'];
                    if ($oldProfilePicture && file_exists('../files/user_images/' . $oldProfilePicture)) {
                        if($oldProfilePicture != 'default_image.svg'){
                            unlink('../files/user_images/' . $oldProfilePicture);
                        }
                    }
                    $update = "UPDATE `users` SET `user_img`='$fileNewName' WHERE `user_id`='$user_id'";
                    if ($con->query($update)) {
                        setcookie('success', 'Profile picture updated successfully', time() + 3, "/");
                    } else {
                        setcookie('error', 'Error in updating profile picture', time() + 3, "/");
                    }
                } else {
                    setcookie('error', 'Your file is too large', time() + 3, "/");
                }
            } else {
                setcookie('error', 'There was an error uploading your file', time() + 3, "/");
            }
        } else {
            setcookie('error', 'You cannot upload files of this type', time() + 3, "/");
        }
        ?>
        <script>window.location.href = "admin_dashboard.php";</script>
        <?php
    }
?>