<?php
    ob_start();
    include_once('db_connect.php');
    session_start();

    $contact_result = $con->query("SELECT * FROM `contact_us`");
    $contact_us = $contact_result->fetch_assoc();

    $result_link = $con->query("SELECT * FROM `links`");
    $result_link->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <script src="files/add-on/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="files/add-on/jquery.validate.min.js?v=<?php echo time(); ?>"></script>
    <script src="files/add-on/additional-methods.min.js?v=<?php echo time(); ?>"></script>
</head>
<body class="bg-gray-100">

    <?php include_once('cookie_display.php'); ?>
    <?php  require_once('header.php'); ?>

    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center text-black mb-8">Contact Us</h2>
            <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-1/2 px-4 mb-8 lg:mb-0">
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-semibold mb-6">Get in Touch</h3>
                        <form action="contact.php" method="post" id="contactForm">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700">Name</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700">Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label for="message" class="block text-gray-700">Message</label>
                                <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <input type="submit" name="contact_sub" id="con_sub" value="Send Message" class="w-full bg-temp text-white py-2 rounded-lg hover:bg-blue-600">
                        </form>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                    <div class="bg-white p-8 rounded-lg shadow-lg h-full flex flex-col">
                        <h3 class="text-2xl font-semibold mb-6">Contact Information</h3>
                        <ul class="space-y-11">
                            <li class="flex items-center">
                                <i class="fas fa-map-marker-alt text-temp text-2xl mr-4"></i>
                                <span><?php echo $contact_us['address']; ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone-alt text-temp text-2xl mr-4"></i>
                                <span><?php echo $contact_us['p_number']; ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope text-temp text-2xl mr-4"></i>
                                <span><?php echo $contact_us['email']; ?></span>
                            </li>
                        </ul>
                        <?php
                            if($result_link->num_rows > 0){
                        ?>
                        <h3 class="text-2xl font-semibold mt-12 mb-6">Follow Us</h3>
                        <ul class="flex space-x-4">
                            <?php
                                foreach($result_link as $link){
                                    if($link['title'] == "Instagram"){
                                        if($link['status'] == 'active'){
                                            ?>
                                            <li>
                                                <a href="<?php echo $link['link_url']; ?>" target="_blank" class="text-temp text-xl">
                                                    <i class="fab fa-instagram text-2xl"></i>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    }
                                    if($link['title'] == "Facebook"){
                                        if($link['status'] == 'active'){
                                            ?>
                                            <li>
                                                <a href="<?php echo $link['link_url']; ?>" target="_blank" class="text-temp text-xl">
                                                    <i class="fab fa-facebook text-2xl"></i>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    }
                                    if($link['title'] == "Twitter"){
                                        if($link['status'] == 'active'){
                                            ?>
                                            <li>
                                                <a href="<?php echo $link['link_url']; ?>" target="_blank" class="text-temp text-xl">
                                                    <i class="fab fa-twitter text-2xl"></i>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    }
                                }
                            ?>
                        </ul>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once('footer.php'); ?>

    <script src="files/js_files/validation.js?v=<?php echo time(); ?>"></script>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php
    if (isset($_POST['contact_sub'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        // $submitted_at = date("Y-m-d");

        $user_id = isset($_SESSION['user']) ? $_SESSION['user'] : "NULL";
    
        $insert_query = "INSERT INTO `message`(`user_id`, `name`, `email`, `message`) VALUES ($user_id, '$name', '$email', '$message')";

        if ($con->query($insert_query)) {
            setcookie('success', 'Message successfully sent.', time() + 3, '/');
        } else {
            setcookie('error', 'Error: Message could not be sent.', time() + 3, '/');
        }
        ?>
        <script>window.location.href = "contact.php"; </script>
        <?php
    }
?>