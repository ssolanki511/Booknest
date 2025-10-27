<?php
    ob_start();
    include_once('../db_connect.php');
    session_start();
    if(isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
        $user_array = $con->query("SELECT * FROM `users` WHERE `user_id` = $user_id");
        $user_detail = $user_array->fetch_assoc();  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User <?php echo isset($_GET['user_id'])?"Update":"Insert" ?> - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
    <script src="../files/add-on/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="../files/add-on/jquery.validate.min.js?v=<?php echo time(); ?>"></script>
    <script src="../files/add-on/additional-methods.min.js?v=<?php echo time(); ?>"></script>
    <link rel="stylesheet" href="../fontawesome-free-6.5.1-web/css/all.css?v=<?php echo time(); ?>">
</head>
<body class="bg-main relative">
    
    <?php include_once('../cookie_display.php'); ?>
    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        
        <?php require_once('admin_slidebar.php'); ?>

        <div class="w-full flex justify-center items-start px-4 py-6 md:px-16 md:py-8 flex-col">
            <h1 class="text-base md:text-lg font-medium mb-4"><a href="admin_user.php" class="hover:underline hover:decoration-temp hover:text-temp">User</a>/<?php
            if(isset($_GET['user_id'])){
                ?>
                <a href="admin_user_detail.php?user_id=<?php echo $user_id; ?>" class="hover:underline hover:decoration-temp hover:text-temp"><?php echo $user_detail['name']; ?></a>
                <?php
            } 
            ?></h1>
            <div class="bg-white w-full px-5 py-6 rounded-md">
                <form action="admin_user_form.php<?php echo isset($_GET['user_id'])?'?user_id'.'='.$user_id:''; ?>" method="post" class="space-y-5" id="user_form" enctype="multipart/form-data" data-user-edit-mode="<?php echo isset($_GET['user_id'])?'false':'true'; ?>">

                    <h5 class="text-base md:text-xl text-center font-medium text-temp">User <?php echo isset($_GET['user_id'])?"Update":"Insert" ?> Form</h5>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Username</label>
                        <input type="text" name="user_name" placeholder="Enter Username" class="mt-2 w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" value="<?php echo isset($_GET['user_id'])? $user_detail['name']:""; ?>">
                    </div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Email</label>
                        <input type="email" name="user_email" placeholder="Enter Email" class="mt-2 w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" value="<?php echo isset($_GET['user_id'])? $user_detail['email']:""; ?>">
                    </div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Password</label>
                        <div class="border border-gray-500 rounded-lg mt-2 py-2 px-2 focus:outline-temp flex justify-between items-center">
                            <input type="password" name="user_password" id="password" placeholder="Enter Password" class="outline-none w-full h-full text-sm md:text-base" value="<?php echo isset($_GET['user_id'])? $user_detail['password']:""; ?>">
                            <i class="fa-solid fa-eye-slash password-eye cursor-pointer text-sm md:text-base"></i>
                        </div>
                    </div>
                    <div class="error-user-password"></div>
                    <div class="input-field flex items-start md:items-center flex-col md:flex-row gap-y-3">
                        <label for="" class="text-sm md:text-base font-medium mr-3">Status</label>
                        <select name="user_status" class="border border-gray-500 rounded-md px-2 py-1 text-sm md:text-base">
                            <option value="Select" selected disabled hidden>Select Status</option>
                            <option value="Active" <?php echo isset($user_detail['status']) && $user_detail['status'] == "Active" ? "selected" : ""; ?>>Active</option>
                            <option value="Inactive" <?php echo isset($user_detail['status']) && $user_detail['status'] == "Inactive" ? "selected" : ""; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="error-user-status"></div>
                    <div class="input-field flex flex-wrap items-center justify-start gap-y-3">
                        <?php if (isset($_GET['user_id']) && !empty($user_detail['user_img'])){ ?>
                            <div class="mt-3 mr-8">
                                <p class="text-sm text-gray-500 mb-2">Current Profile Picture:</p>
                                <img src="<?php echo '../files/user_images/' . $user_detail['user_img']; ?>" alt="user image" class="w-32 md:w-40 h-32 md:h-40 object-cover border border-gray-300 rounded-full">
                            </div>
                        <?php }?>
                        <label for="profile_img" class="text-sm md:text-base font-medium mr-3">Upload Your Profile Picture</label>
                        <input type="file" name="profile_pic" id="profile_img" class="cursor-pointer text-sm md:text-base w-fit">
                    </div>
                    <div class="error-user-pic"></div>
                    <div class="submit-box text-center pt-4">
                        <input type="submit" value="Submit" name="user-sub" class="bg-temp text-white py-1 px-3 rounded cursor-pointer">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../files/js_files/admin_valid.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php
if (isset($_POST['user-sub'])) {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_status = $_POST['user_status'];
    $current_date = date("Y-m-d");

 
    $profile_pic = uniqid() . $_FILES['profile_pic']['name'];
    $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];
    $profile_pic_folder = "../files/user_images/" . $profile_pic;

    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        if (!empty($_FILES['profile_pic']['name'])) {
            if (move_uploaded_file($profile_pic_tmp, $profile_pic_folder)) {
                if (!empty($user_detail['user_img']) && file_exists("../files/user_images/" . $user_detail['user_img'])) {
                    unlink("../files/user_images/" . $user_detail['user_img']);
                }
            } else {
                setcookie('error', 'Failed to upload new profile picture.', time() + 3, "/");
                ?>
                <script>window.location.href = "admin_user_form.php?user_id=<?php echo $user_id; ?>";</script>
                <?php
            }
        } else {
            $profile_pic = $user_detail['user_img'];
        }

        $update_query = "UPDATE `users` SET `name` = '$user_name', `email` = '$user_email', `status` = '$user_status', `password` = '$user_password', `user_img` = '$profile_pic' WHERE `user_id` = $user_id";

        if ($con->query($update_query)) {
            setcookie('success', 'User updated successfully.', time() + 3, "/");
            ?>
            <script>window.location.href = "admin_user.php";</script>
            <?php 
        } else {
            setcookie('error', 'Failed to update user detail', time() + 3, "/");
        }
    } else {
        $check_user_query = "SELECT * FROM `users` WHERE `email` = '$user_email'";
        $check_user_result = $con->query($check_user_query);

        if ($check_user_result->num_rows > 0) {
            setcookie('error', 'User with this email already exists.', time() + 3, "/");
        } else {
            if (move_uploaded_file($profile_pic_tmp, $profile_pic_folder)) {
                $insert_query = "INSERT INTO `users`(`name`, `email`, `register_date`, `status`, `password`, `user_img`) VALUES ('$user_name', '$user_email', '$current_date', '$user_status', '$user_password', '$profile_pic')";

                if ($con->query($insert_query)) {
                    setcookie('success', 'User added successfully.', time() + 3, "/");
                    ?>
                    <script>window.location.href = "admin_user.php";</script>
                    <?php      
                } else {
                    setcookie('error', 'Failed to add user: ' . $con->error, time() + 3, "/");
                }
            } else {
                setcookie('error', 'Failed to upload profile picture.', time() + 3, "/");
            }
        }
    }
    ?>
    <script>window.location.href = "admin_user_form.php?user_id=<?php echo $user_id; ?>";</script>
    <?php
}
?>