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
    <title>Forgot Password - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time() ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time() ?>">
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
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Forgot Password</h1>
        <form id="forgotForm" action="forgot-password.php" method="post" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <input type="submit" name="reset_password" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" value="Submit">
            </div>
        </form>
        <p class="mt-6 text-center text-gray-600">Remembered your password? <a href="login.php" class="text-indigo-600 hover:text-indigo-700 font-medium">Login</a></p>
    </div>
    <script src="files/js_files/validation.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
    if(isset($_POST['reset_password'])){
        $email = $_POST['email'];
        $result = $con->query("SELECT * FROM `users` WHERE `email` = '$email'");
        if($result->num_rows > 0){
            $otp = rand(1000,9999);
            $con->query("UPDATE `users` SET `otp` = '$otp', `otp_expiry` = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE `email` = '$email'");
            $row = $result->fetch_assoc();
            sendmail($email, $row['name'], $otp);
            setcookie('success', 'OTP sent to your email.', time() + 3, '/');
            ?>
            <script>window.location.href = "otp.php?email=<?php echo $email; ?>";</script>
            <?php
            
        }else{
            setcookie('error', 'Account is not registered.', time()+3,'/');
        }
    }

    function sendmail($email, $username, $otp){
        $mail = new PHPMailer();
        $headers = 'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        
        $to = $email;
        $subject = "Account Verification Link";
        $link = 'http://localhost/New_booknest/booknest/active_account.php?email='.$email;
        $body = "<div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px;'>
                    <h2 style='color: #dc3545; text-align: center;'>Your OTP</h2>
                    <p style='text-align: start;font-size:18px;'>Hi, $username</p>
                    <p style='text-align: start;font-size:18px;'>Please enter the below mentioned OTP for reset the password into booknest.</p>
                    <h2 style='text-align: center;letter-spacing:6px; font-size:40px;'>$otp</h2>
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
        $mail->Subject    = "OTP For Reset Password";
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