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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisements - Booknest</title>
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

        <div class="px-4 py-8 w-full flex justify-center items-start flex-col">

            <div class="bg-white w-full rounded-md px-5 py-4 mb-6">
                <span class="text-base md:text-lg font-medium text-temp">All Link</span>
                <div class="relative overflow-x-auto mt-2">
                    <?php
                        if($link_result->num_rows > 0){
                            ?>
                            <table class="w-full text-left text-gray-500">
                                <thead class="text-xs md:text-sm text-gray-700 uppercase bg-gray-100 text-center text-nowrap">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">#</th>
                                        <th scope="col" class="px-6 py-3">Link Titile</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3"></th>
                                        <th scope="col" class="px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php
                                        $i = 1;
                                        foreach($link_result as $link){

                                    ?>
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 text-sm md:text-base text-nowrap">
                                        <td class="px-6 py-3"><?php echo $i++; ?></td>
                                        <td class="px-6 py-3 capitalize"><?php echo $link['title']; ?></td>
                                        <td class="px-6 py-3">
                                            <select name="" class="status text-green-500 border border-green-500 rounded-md px-1 focus:outline-none" data-id="<?php echo $link['id']; ?>" data-type="link">
                                                <option value="active" class="state" <?php echo $link['status'] === 'active' ? 'selected' : ''; ?>>active</option>
                                                <option value="inactive" class="state" <?php echo $link['status'] === 'inactive' ? 'selected' : ''; ?>>inactive</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-3"><a target="_blank" href="<?php echo $link['link_url']; ?>" class="text-temp underline hover:text-black hover:no-underline">Preview</a></td>
                                        <td class="px-6 py-3"><button class="link-form-open text-sm sm:text-lg bg-green-600 px-3 text-white rounded py-1 hover:bg-green-700" data-id="<?php echo $link['id']; ?>">Edit</button></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <div class="bg-white w-full rounded-md px-5 py-4 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-base md:text-lg font-medium text-temp">All Banners</span>
                    <button class="banner-form-open py-1 px-2 text-sm md:text-base border rounded-lg ml-3 border-temp text-temp hover:border-gray-800 hover:text-black">Add Banner</button>
                </div>
                <div class="relative overflow-x-auto mt-2">
                    <?php
                        if($banner_result->num_rows > 0){
                            ?>
                            <table class="w-full text-left text-gray-500">
                                <thead class="text-xs md:text-sm text-gray-700 uppercase bg-gray-100 text-center text-nowrap">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">#</th>
                                        <th scope="col" class="px-6 py-3">Title</th>
                                        <th scope="col" class="px-6 py-3">Publish Date</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3"></th>
                                        <th scope="col" class="px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php
                                        $i = 1;
                                        foreach($banner_result as $banner){
                                            
                                    ?>
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 text-sm md:text-base text-nowrap">
                                        <td class="px-6 py-3"><?php echo $i++; ?></td>
                                        <td class="px-6 py-3 capitalize"><?php echo $banner['banner_title']; ?></td>
                                        <td class="px-6 py-3 capitalize"><?php echo $banner['publish_date']; ?></td>
                                        <td class="px-6 py-3">
                                            <select name="" class="status text-green-500 border border-green-500 rounded-md px-1 focus:outline-none" data-id="<?php echo $banner['banner_id']; ?>" data-type="banner">
                                                <option value="active" class="state" <?php echo $banner['status'] === 'active' ? 'selected' : ''; ?>>active</option>
                                                <option value="inactive" class="state" <?php echo $banner['status'] === 'inactive' ? 'selected' : '' ?>>inactive</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-3"><a href="../files/banners/<?php echo $banner['banner_img']; ?>" target="_blank" class="text-temp underline hover:text-black hover:no-underline">Preview</a></td>
                                        <td><a href="banner_delete.php?banner_id=<?php echo $banner['banner_id']; ?>" class="admin-user-delete-btn hover:text-temp p-2 h-8 w-8 grid place-items-center text-sm sm:text-base"><i class="fa-solid fa-x"></i></a></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }else{
                            ?>
                            <p class="text-center my-4 text-red-600 font-semibold text-xs md:text-lg">No banner are added at the moment.</p>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <div class="bg-white w-full rounded-md px-5 py-4">
                <span class="text-base md:text-lg font-medium text-temp">Contact Information</span>
                <div class="relative overflow-x-auto mt-2">
                    <form action="admin_advertisement.php" id="contact_us" class="space-y-3" method="post">
                        <div>
                            <label for="address" class="text-sm md:text-base font-medium mr-3">About us</label>
                            <textarea name="about_us" rows="3" placeholder="Enter About Us Info" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp resize-none text-sm md:text-base"><?php echo $contact_us['about_us']; ?></textarea>
                        </div>
                        <div>
                            <label for="address" class="text-sm md:text-base font-medium mr-3">Address</label>
                            <input type="text" name="address" id="address" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" placeholder="Enter Address" value="<?php echo $contact_us['address']; ?>">
                        </div>
                        <div>
                            <label for="p_number" class="text-sm md:text-base font-medium mr-3">Phone Number</label>
                            <input type="tel" name="p_number" id="p_number" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" placeholder="Enter Phone Number" value="<?php echo $contact_us['p_number']; ?>">
                        </div>
                        <div>
                            <label for="email" class="text-sm md:text-base font-medium mr-3">Email</label>
                            <input type="email" name="email" id="email" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" placeholder="Enter Email" value="<?php echo $contact_us['email']; ?>">
                        </div>
                        <div class="submit-box text-center pt-4">
                            <input type="submit" value="Submit" name="contact_sub" class="bg-temp text-white py-2 px-4 md:px-6 text-sm md:text-base rounded cursor-pointer">
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="banner-form fixed -top-full left-0 bg-gray-600 z-50 h-screen w-screen bg-opacity-15 flex justify-center items-center duration-100 ease-linear px-4">
        <div class="bg-white w-full md:w-1/4 py-5 rounded-md px-6 relative">
            <form action="admin_advertisement.php" method="post" enctype="multipart/form-data" class="flex flex-col gap-y-3" id="banner-form">
                <h5 class="text-temp text-center font-medium text-base md:text-lg">Banner Form</h5>
                <div>
                    <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Title</label>
                    <input type="text" name="banner_name" class="border border-gray-300 rounded-lg px-2 py-1 text-sm md:text-base focus:outline-1 focus:outline-temp w-full">
                </div>
                <div>
                    <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Upload Banner</label>
                    <input type="file" name="banner_image" class="w-full text-sm md:text-base">
                </div>
                <div class="text-center pt-2 md:pt-4">
                    <input type="submit" name="banner_submit" value="Submit" class="py-1 px-3 bg-temp text-white rounded-md hover:bg-blue-800 cursor-pointer text-sm md:text-base">
                </div>
            </form>
            <button class="banner-form-close absolute top-2 right-3 text-lg md:text-xl"><i class="fa-solid fa-xmark text-temp"></i></button>
        </div>
    </div>

    <div class="link-form fixed -top-full left-0 bg-gray-600 z-50 h-screen w-screen bg-opacity-15 flex justify-center items-center duration-100 ease-linear px-4">
        <div class="bg-white w-full md:w-1/4 py-5 rounded-md px-6 relative">
            <form action="admin_advertisement.php" method="post" class="flex flex-col gap-y-3" id="link-form">
                <h5 class="text-temp text-center font-medium text-base md:text-lg">Link Form</h5>
                <div>
                    <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Link Title</label>
                    <input type="text" name="link_title" id="link_title" class="border border-gray-300 rounded-lg px-2 py-1 text-sm md:text-base focus:outline-1 focus:outline-temp w-full text-gray-400" readonly>
                </div>
                <div>
                    <label for="" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Upload Link</label>
                    <input type="text" name="link_url" id="link_url" class="border border-gray-300 rounded-lg px-2 py-1 text-sm md:text-base focus:outline-1 focus:outline-temp w-full">
                </div>
                <div class="text-center pt-2 md:pt-4">
                    <input type="submit" name="link_update" value="Submit" class="py-1 px-3 bg-temp text-white rounded-md hover:bg-blue-800 cursor-pointer text-sm md:text-base">
                </div>
            </form>
            <button class="link-form-close absolute top-2 right-3 text-lg md:text-xl"><i class="fa-solid fa-xmark text-temp"></i></button>
        </div>
    </div>
    
    <script src="../files/js_files/admin_valid.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_banner.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
    if(isset($_POST['contact_sub'])){
        $about_us = $_POST['about_us'];
        $address = $_POST['address'];
        $p_number = $_POST['p_number'];
        $email = $_POST['email'];

        $contact_update = "UPDATE `contact_us` SET `about_us`='$about_us',`address`='$address',`p_number`='$p_number',`email`='$email'";

        if($con->query($contact_update)){
            setcookie('success', 'Contact info updated successfully.',time()+3, '/');
        }else{
            setcookie('error', 'Contact info is not updated',time()+3, '/');
        }
        ?>
        <script>window.location.href="admin_advertisement.php";</script>
        <?php
    }

    if(isset($_POST['link_update'])){
        $title = $_POST['link_title'];
        $link = $_POST['link_url'];
        $update_link = "UPDATE `links` SET `link_url`='$link' WHERE `title` = '$title'";
        if($con->query($update_link)){
            setcookie('success', 'Link updated successfully.',time()+3, '/');
        }else{
            setcookie('error', 'Link not updated.',time()+3, '/');
        }
        ?>
        <script>window.location.href="admin_advertisement.php";</script>
        <?php
    }

    if(isset($_POST['banner_submit'])){
        $banner_name = $_POST['banner_name'];
        $banner_image = $_FILES['banner_image']['name'];

        $banner_image = uniqid().'_'.$banner_image;
        $date = date('Y-m-d');
        if(move_uploaded_file($_FILES['banner_image']['tmp_name'], '../files/banners/'.$banner_image)){
            $sql = "INSERT INTO `banner`(`banner_title`, `publish_date`, `banner_img`) VALUES ('$banner_name','$date','$banner_image')";
            if($con->query($sql)){
                setcookie('success', 'Banner Added', time() + 3, '/');
            }else{
                setcookie('error', 'Banner Not Added', time() + 3, '/');
            }
        }else{
            setcookie('error', 'Banner Not Uploaded', time() + 3, '/');
        }
        ?>
        <script>window.location.href="admin_advertisement.php";</script>
        <?php
    }
?>