<?php 
    ob_start();
    include_once('db_connect.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg?v=<?php echo time(); ?>" type="image/icon type">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <script src="files/add-on/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="files/add-on/jquery.validate.min.js?v=<?php echo time(); ?>"></script>
    <script src="files/add-on/additional-methods.min.js?v=<?php echo time(); ?>"></script>
</head>
<body class="bg-pattern flex items-center justify-center h-screen">
    
    <?php include_once('cookie_display.php'); ?>

    <div class="hidden lg:block">
        <div class="absolute top-4 left-4 flex items-center space-x-3">
            <img src="files/Logo/logo.svg" alt="Booknest Logo" class="h-12">
            <span class="text-3xl font-serif color">Booknest</span>
        </div>
    </div>
    <div class="bg-white py-6 px-6 md:px-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Login</h1>
        <form action="login.php" method="post" class="space-y-3 md:space-y-5" id="loginForm">
            <div>
                <label for="email" class="block text-xs md:text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm outline-none text-xs md:text-sm">
            </div>
            <div>
                <label for="password" class="block text-xs md:text-sm font-medium text-gray-700">Password</label>
                <div class="border border-gray-300 rounded-md shadow-sm flex items-center justify-between group px-4">
                    <input type="password" name="password" id="password" placeholder="Password" class="block w-full py-3 text-xs md:text-sm outline-none">
                    <i class="fa-solid fa-eye-slash password-eye cursor-pointer text-sm md:text-base"></i>
                </div>
                <div class="error-password"></div>
            </div>
            <div class="flex justify-end items-center">
                <div class="text-right">
                    <a href="forgot-password.php" class="text-xs md:text-sm text-indigo-600 hover:text-indigo-700">Forgot Password?</a>
                </div>
            </div>
            <div>
                <input type="submit" value="Login" name="login_btn" class="w-full bg-indigo-600 text-white py-2 md:py-3 px-4 text-sm md:text-base rounded-md hover:bg-indigo-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"></input>
            </div>
        </form>
        <p class="mt-2 text-center text-xs md:text-sm text-gray-600">Don't have an account? <a href="register.php" class="text-indigo-600 hover:text-indigo-700 font-medium">Register</a></p>
    </div>
    <script src="files/js_files/validation.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php
    if(isset($_POST['login_btn'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user_exist = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'";
        $result = $con->query($user_exist);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if($row['status'] == 'Active'){
                if($row['usertype'] == 'User'){
                    $_SESSION['user'] = $row['user_id'];
                    ?>
                    <script>window.location.href = "index.php"; </script>
                    <?php
                }
                if($row['usertype'] == 'Admin'){
                    $_SESSION['admin'] = $row['user_id'];
                    ?>
                    <script>window.location.href = "admin/admin_dashboard.php"; </script>
                    <?php
                }
            }else{
                setcookie('error', 'Account not activated.', time() + 3, "/");
            }
        }else{
            setcookie('error', 'Invalid email or password.', time() + 3, "/");
        }
        ?>
        <script>window.location.href = "login.php"; </script>
        <?php
    }
?>