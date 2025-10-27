<?php 
    include_once('../db_connect.php');
    session_start();

    $all_users_query = "
        SELECT u.user_id, u.name, u.email, COUNT(p.purchase_id) AS order_count
        FROM `users` u LEFT JOIN `purchases` p ON u.user_id = p.user_id
        WHERE u.usertype = 'User'
        GROUP BY u.user_id ORDER BY `user_id` DESC
    ";
    $all_users = $con->query($all_users_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg?v=<?php echo time(); ?>" type="image/icon type">
</head>
<body class="bg-main relative">

    <?php include_once('../cookie_display.php'); ?>
    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        <?php require_once('admin_slidebar.php'); ?>

        <div class="admin-books-container px-4 py-8 w-full flex justify-center items-start">
            <div class="admin-books-box bg-white w-11/12 rounded-md px-5 py-4">
                <div class="heading flex justify-center w-full items-center flex-col space-y-2 sm:justify-between sm:flex-row sm:space-y-0 sm:items-start">
                    <div class="flex items-center gap-x-3 flex-col gap-y-1 md:flex-row">
                        <span class="text-base md:text-lg font-medium text-temp">All Users</span>
                    </div>
                    <div class="flex items-center flex-col gap-y-3 md:flex-row">
                        <a href="admin_user_form.php" class="py-1 px-2 text-sm md:text-base border rounded-lg ml-3 border-temp text-temp hover:border-gray-800 hover:text-black">Add User</a>
                    </div>
                </div>

                <div class="relative overflow-x-auto mt-5">
                    <?php 
                        if($all_users->num_rows > 0){
                            ?>
                            <table class="w-full text-left text-gray-500">
                                <thead class="text-xs md:text-sm text-gray-700 uppercase bg-gray-100 text-center text-nowrap">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">#</th>
                                        <th scope="col" class="px-6 py-3">Name</th>
                                        <th scope="col" class="px-6 py-3">Email</th>
                                        <th scope="col" class="px-6 py-3">Orders</th>
                                        <th scope="col" class="px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php
                                        $i = 1;
                                        foreach($all_users as $user) {
                                            ?>
                                            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 text-sm md:text-base text-nowrap">
                                                <td class="px-6 py-3"><?php echo $i++; ?></td>
                                                <td class="px-6 py-3"><a href="admin_user_detail.php?user_id=<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></a></td>
                                                <td class="px-6 py-3"><?php echo $user['email']; ?></td>
                                                <td class="px-6 py-3"><?php echo $user['order_count']; ?></td>
                                                <td><a href="admin_user_delete.php?user_email=<?php echo $user['email']; ?>" class="admin-user-delete-btn hover:text-temp p-2 h-8 w-8 grid place-items-center text-sm sm:text-base"><i class="fa-solid fa-x"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }else{
                            ?>
                            <p class="text-center my-2 text-red-600 font-semibold text-xs md:text-lg">No user found.</p>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>