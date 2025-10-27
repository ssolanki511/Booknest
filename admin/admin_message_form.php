<?php
    ob_start();
    include_once('../db_connect.php');
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require('..\files\add-on\PHPMailer\PHPMailer.php');
    require('..\files\add-on\PHPMailer\SMTP.php');
    require('..\files\add-on\PHPMailer\Exception.php');

    if(isset($_GET['message_id'])){
        $message_id = $_GET['message_id'];
    }else{
        ?>
        <script>window.location.href="admin_message.php";</script>
        <?php
    }

    $message_query = "SELECT * FROM `message` WHERE `id` = $message_id";
    $message_result = $con->query($message_query);
    if (isset($_GET['message_id'])) {
        $message_id = $_GET['message_id']; 
    
        $message_query = "SELECT * FROM `message` WHERE `id` = $message_id";
        $message_result = $con->query($message_query);
    
        if ($message_result->num_rows > 0) {
            $message = $message_result->fetch_assoc();
        } else {
            setcookie('error', 'Message not found.', time() + 3, '/');
            ?>
            <script>window.location.href="admin_message.php";</script>
            <?php
        }
    } else {
        setcookie('error', 'Invalid request.', time() + 3, '/');
        ?>
        <script>window.location.href="admin_message.php";</script>
        <?php
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response Form - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
    <script src="../files/add-on/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="../files/add-on/jquery.validate.min.js?v=<?php echo time(); ?>"></script>
    <script src="../files/add-on/additional-methods.min.js?v=<?php echo time(); ?>"></script>
</head>
<body class="bg-main relative">
    <?php include_once('../cookie_display.php'); ?>
    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        <?php require_once('admin_slidebar.php'); ?>

        <div class="w-full flex justify-start items-start px-4 py-6 md:px-16 md:py-8 flex-col">
            <h1 class="text-base md:text-lg font-medium mb-4"><a href="admin_message.php" class="hover:underline hover:decoration-temp hover:text-temp">Message</a>/</h1>
            <div class="bg-white w-full px-5 py-6 rounded-md">
                <form action="admin_message_form.php?message_id=<?php echo $message_id; ?>" method="post" class="space-y-3" id="message-form">
                    <h5 class="text-base md:text-xl text-center font-medium text-temp">Response Form</h5>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Name</label>
                        <input type="text" name="name" placeholder="Enter Title" class="mt-2 w-full border border-gray-500 outline-none rounded-lg py-2 bg-gray-200 px-2 text-sm md:text-base text-gray-500" value="<?php echo $message['name']; ?>" readonly>
                    </div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Email</label>
                        <input type="text" name="email" placeholder="Enter Author Name" class="mt-2 w-full border outline-none border-gray-500 rounded-lg py-2 px-2 bg-gray-200 text-sm md:text-base text-gray-500" value="<?php echo $message['email']; ?>" readonly>
                    </div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Message</label>
                        <textarea name="message" rows="4" placeholder="Enter Description" class="mt-2 w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp resize-none text-sm md:text-base"></textarea>
                    </div>
                    <div class="submit-box text-center pt-4">
                        <input type="submit" value="Submit" name="response_sub" class="bg-temp text-white py-1 px-3 rounded cursor-pointer">
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
    if (isset($_POST['response_sub'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        if(sendmail($email, $name, $message)){
            $con->query("UPDATE `message` SET `reply`='Yes' WHERE `id` = $message_id");
            setcookie('success', 'Response send successfully.', time()+3, '/');
        }else{
            setcookie('error', 'Response is not send.', time()+3, '/');
        }
        ?>
        <script>window.location.href = "admin_message.php"; </script>
        <?php
        
    }

    function sendmail($email, $username, $message){
        $mail = new PHPMailer();
        $headers = 'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        
        $to = $email;
        $body = "<p style='font-size:19px;font-weight:500;font-family:sans-serif;'>$message</p>";
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
        $mail->Subject    = "Responce to message";
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