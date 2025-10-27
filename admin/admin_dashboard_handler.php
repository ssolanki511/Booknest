<?php
    ob_start();
    include('../db_connect.php');
    session_start();
    $admin_id = $_SESSION['admin'];
    $admin_array = $con->query("SELECT * FROM `users` WHERE `user_id` = $admin_id");
    $admin = $admin_array->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Edit Form - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
    <script src="../files/add-on/jquery.min.js"></script>
    <script src="../files/add-on/jquery.validate.min.js"></script>
    <script src="../files/add-on/additional-methods.min.js"></script>
</head>
<body class="bg-main relative">

    <?php include_once('../cookie_display.php'); ?>
    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        
        <?php require_once('admin_slidebar.php'); ?>

        <div class="px-4 py-3 w-full">
            <h3 class="font-medium text-base md:text-lg mb-2"><a href="admin_dashboard.php" class="hover:underline hover:decoration-temp hover:text-temp">Dashboard</a>/</h3>
            <div class="flex justify-center items-center">
                <div class="bg-white w-full md:w-2/4 shadow-md py-8 px-6 rounded-md">
                    <h3 class="text-center text-temp text-base md:text-xl mb-4 font-medium">Admin Edit Form</h3>
                    <form action="admin_dashboard_handler.php" method="post" class="flex justify-center items-center flex-col text-sm md:text-base" id="admin_edit">
                        <div class="mb-2 w-full md:w-72">
                            <label for="" class="block mb-1">Username</label>
                            <input type="text" name="uname" class="border border-gray-600 rounded w-full outline-none py-1 px-2" value="<?php echo $admin['name']; ?>">
                        </div>
                        <div class="mb-2 w-full md:w-72">
                            <label for="" class="block mb-1">Email</label>
                            <input type="email" name="email" class="border border-gray-600 rounded w-full outline-none py-1 px-2" value="<?php echo $admin['email']; ?>">
                        </div>
                        <div class="w-full md:w-72 text-xs">
                            <input type="submit" value="Submit" name="admin-update" class="bg-temp text-sm md:text-base text-white py-2 px-3 rounded-md hover:bg-blue-800 cursor-pointer w-full mt-4">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../files/js_files/admin_valid.js?v<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php 
    if(isset($_POST['admin-update'])){
        $username = $_POST['uname'];
        $email = $_POST['email'];

        if($con->query("UPDATE `users` SET `name`='$username',`email`='$email' WHERE `user_id` = $admin_id")){
            setcookie('success','Profile update successfully.', time()+3, '/');
            ?>
            <script>window.location.href = 'admin_dashboard.php';</script>
            <?php
        }else{
            setcookie('error', 'Profile is not update', time()+3, '/');
            ?>
            <script>window.location.href = 'admin_dashboard_handler.php.php';</script>
            <?php
        }
    }
?>  