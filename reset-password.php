<?php
    include_once('db_connect.php');
    if(isset($_GET['email'])){
        $email = $_GET['email'];
    }else{
        ?>
        <script>window.location.href = "forgot-password.php";</script>
        <?php
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time() ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time() ?>">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <script src="files/add-on/jquery.min.js"></script>
    <script src="files/add-on/jquery.validate.min.js"></script>
    <script src="files/add-on/additional-methods.min.js"></script>
</head>

<body class="bg-pattern flex items-center justify-center h-screen">
    <div class="hidden lg:block">
        <div class="absolute top-4 left-4 flex items-center space-x-3">
            <img src="files/Logo/logo.svg" alt="Booknest Logo" class="h-12">
            <span class="text-3xl font-serif color">Booknest</span>
        </div>
    </div>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Reset Password</h1>
        <form id="resetPasswordForm" action="reset-password.php" method="post" class="space-y-6">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" name="password" id="password" placeholder="New Password" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <input type="submit" name="reset-password-btn" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" value="Reset Password">
            </div>
        </form>
    </div>
    <script src="files/js_files/validation.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
    if(isset($_POST['reset-password-btn'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        if($con->query("UPDATE `users` SET `password` = '$password' WHERE `email` = '$email'")){
            setcookie('success', 'Successfully change password.',time()+3,'/');
        }else{
            setcookie('error', 'Password is not change',time()+3,'/');
        }
        ?>
        <script>window.location.href = "login.php"; </script>
        <?php
    }
?>