<?php 
    include_once('db_connect.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require('files\add-on\PHPMailer\PHPMailer.php');
    require('files\add-on\PHPMailer\SMTP.php');
    require('files\add-on\PHPMailer\Exception.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Booknest</title>
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
            <span class="text-3xl  font-serif text-black color">Booknest</span>
        </div>
    </div>

    <div class="bg-white py-6 px-6 md:px-8 rounded-lg shadow-lg w-full md:h-fit md:max-w-md">
        <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center text-gray-800">Register</h1>
        <form action="register.php" method="post" class="space-y-3 md:space-y-5" id="registerForm">
            <div>
                <label for="uname" class="block text-xs md:text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="uname" placeholder="Username" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm outline-none text-xs md:text-sm">
            </div>
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
            <div>
                <input type="submit" class="w-full bg-indigo-600 text-white py-2 md:py-3 px-4 rounded-md hover:bg-indigo-700 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" name="signup_btn">
            </div>
        </form>
        <p class="mt-2 text-center text-xs md:text-sm text-gray-600">Already have an account? <a href="login.php" class="text-indigo-600 hover:text-indigo-700 font-medium">Login</a></p>
    </div>
    <script src="files/js_files/validation.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
    if(isset($_POST['signup_btn'])){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $current_date = date("Y-m-d");
        $token = uniqid();

        $user_exist = "SELECT * FROM `users` WHERE `email` = '$email'";

        if($con->query($user_exist)->num_rows > 0){
            setcookie('error', 'Email already exists.', time() + 3, "/");
        }else{
            $user_insert = "INSERT INTO `users`(`name`, `email`, `register_date`, `password`, `token`, `user_img`) VALUES ('$username','$email','$current_date','$password','$token','default_image.svg')";

            if($con->query($user_insert)){
                if(!sendmail($email, $username, $token)){
                    setcookie('error', 'Failed to send the registration link.', time() + 3, "/");
                    $con->query("DELETE FROM `users` WHERE `email` = '$email'");
                }else{
                    setcookie('success', 'Registration Successfull, An account verification link has been sent to your email.', time() + 3, "/");
                }
            }else{
                setcookie('error', 'Failed to register.', time() + 3, "/");
            }
        }
        ?>
        <script>
            window.location.href = 'register.php';
        </script>
        <?php        
    }

    function sendmail($email, $username, $token){
        $mail = new PHPMailer();
        $headers = 'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        
        $to = $email;
        $subject = "Account Verification Link";
        $link = 'http://localhost/New_booknest/booknest/active_account.php?email='.$email.'&token='.$token;
        $body = "<div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px;'>
                    <h2 style='color: #dc3545; text-align: center;'>Account Verification</h2>
                    <p style='text-align: center;'>Click the below button to verify your account.</p>
                    <a href='$link' style='display: block; width: 200px; margin: 0px auto; text-align: center; background-color: #dc3545; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verify Account</a>
                </div>";
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug  = 0;                // enables SMTP debug information (for testing)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = 'smtp.gmail.com';      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $mail->Username   = "ssolanki511@rku.ac.in";  // GMAIL username(from)
        $mail->Password   = "bczp zgan ojkm dwkl";            // GMAIL password(from)
        $mail->SetFrom('ssolanki511@rku.ac.in', 'Booknest'); //from
        // $mail->AddReplyTo($email); //to
        $mail->Subject    = "Account Verification Link";
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
        $mail->MsgHTML($body);
        $mail->AddAddress($to, $username);

        if (!$mail->Send()) {
            return false;
        } else {
            return true;
        }
    }
?>