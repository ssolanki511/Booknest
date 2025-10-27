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
    <title>OTP - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <script src="files/add-on/jquery.min.js"></script>
    <script src="files/add-on/jquery.validate.min.js"></script>
    <script src="files/add-on/additional-methods.min.js"></script>
</head>
<body class="bg-pattern flex items-center justify-center h-screen">
    <?php include_once('cookie_display.php'); ?>
    <div class="hidden lg:block">
        <div class="absolute top-4 left-4 flex items-center space-x-3">
            <img src="files/Logo/logo.svg" alt="Booknest Logo" class="h-12">
            <span class="text-3xl font-serif color">Booknest</span>
        </div>
    </div>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md ">
        <h1 class="text-2xl md:text-3xl font-bold mb-2 text-center text-gray-800">OTP Verification</h1>
        <p class="text-gray-500 text-xs md:text-sm text-center mb-4">Plese Enter The Verification Code We Sent To <?php echo $email; ?></p>
        <form id="otpForm" class="space-y-6" method="POST" action="javascript:void(0);">
            <input type="email" id="reset_password_email" value="<?php echo $email; ?>" hidden>
            <div class="flex justify-center items-center gap-x-2 md:gap-x-3 my-6 md:my-8">
                <input type="number" name="opt1" min="0" max="9" placeholder="0" class="otp_field rounded text-base md:text-2xl border-2 border-gray-300 text-center font-bold outline-none otp_field py-3 md:py-4">
                <input type="number" name="opt2" min="0" max="9" placeholder="0" class="otp_field rounded text-base md:text-2xl border-2 border-gray-300 text-center font-bold outline-none otp_field py-3 md:py-4">
                <input type="number" name="opt3" min="0" max="9" placeholder="0" class="otp_field rounded text-base md:text-2xl border-2 border-gray-300 text-center font-bold outline-none otp_field py-3 md:py-4">
                <input type="number" name="opt4" min="0" max="9" placeholder="0" class="otp_field rounded text-base md:text-2xl border-2 border-gray-300 text-center font-bold outline-none otp_field py-3 md:py-4">
            </div>
            <div class="otp-error-box"></div>
            <div>
                <input type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" value="Confirm">
            </div>
        </form>
    </div>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>