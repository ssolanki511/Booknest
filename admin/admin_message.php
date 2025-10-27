<?php
    ob_start();
    include_once('../db_connect.php');
    session_start();

    $banner_result = $con->query("SELECT * FROM `banner`");
    $banner_result->fetch_assoc();

    $link_result = $con->query("SELECT * FROM `links`");
    $link_result->fetch_assoc();

    $contact_result = $con->query("SELECT * FROM `contact_us`");
    $contact_us = $contact_result->fetch_assoc();

    $message_query = "SELECT m.*, u.user_img FROM `message` m LEFT JOIN `users` u ON m.user_id = u.user_id ORDER BY m.date DESC";
    $message_result = $con->query($message_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Booknest</title>
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

        <div class="px-4 py-8 w-full flex justify-start items-start flex-col">
            <div class="bg-white w-full rounded-md px-5 py-4">
                <span class="text-base md:text-lg font-medium text-temp mb-4 block">Messages</span>
                <div>
                    <?php
                        if ($message_result->num_rows > 0) {
                            while ($message = $message_result->fetch_assoc()) {
                    ?>
                    <div class="flex justify-center py-2 items-center gap-x-4 flex-wrap md:flex-nowrap md:justify-between">
                        <div class="w-20 h-20 md:w-24 md:h-24 md:ml-8 mb-4 md:mb-0">
                            <img src="<?php echo !empty($message['user_img']) ? '../files/user_images/' . $message['user_img'] : '../files/user_images/default_image.svg'; ?>" alt="" class="w-full h-full object-cover rounded-full">
                        </div>
                        <div class="w-10/12">
                            <div class="flex justify-between items-center">
                                <div class="flex gap-x-1 items-start flex-wrap">
                                    <p class="text-sm md:text-base font-medium"><?php echo $message['name']; ?></p>
                                    <?php if ($message['reply'] === 'Yes') { ?>
                                        <i class="fa-solid fa-check-double text-temp text-base md:text-lg"></i>
                                    <?php } ?>
                                </div>
                                <p class="text-sm md:text-base font-medium"><?php echo date('d M Y', strtotime($message['date'])); ?></p>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 mt-2 leading-relaxed"><?php echo $message['message']; ?></p>
                            <div class="mt-4 text-right">
                                <a href="admin_message_form.php?message_id=<?php echo $message['id']; ?>" class="py-1 px-2 md:px-2 md:text-base bg-temp text-white rounded-md hover:bg-blue-800 text-xs">Response</a>
                            </div>
                        </div>
                    </div>
                    <hr class="border border-gray-300 my-3">
                    <?php
                            }
                        }else{
                            ?>
                            <p class='text-center text-gray-500'>No messages available.</p>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../files/js_files/admin_valid.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>