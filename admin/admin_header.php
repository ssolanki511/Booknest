<?php
    include_once('admin_check_session.php');
    $admin_id = $_SESSION['admin'];
    $admin_array = $con->query("SELECT * FROM `users` WHERE `user_id` = $admin_id");
    $admin = $admin_array->fetch_assoc();
?>
<link rel="stylesheet" href="../files/css_files/admin_main.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../files/tailwindcss/output.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../fontawesome-free-6.5.1-web/css/all.css?v=<?php echo time(); ?>">

<header class="bg-white h-14 shadow sticky top-0 left-0 z-20">
    <nav class="flex justify-between items-center h-full px-3 md:px-6">
        <div class="flex items-center justify-center space-x-3">
            <button class="slidebar-icon text-base md:text-xl hover:bg-gray-400 hover:bg-opacity-10 w-5 md:w-8 md:h-8 rounded lg:hidden">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="logo-image w-6 md:w-8 header-logo">
                <img src="../files/Logo/logo.svg" class="object-cover w-full" alt="">
            </div>
            <h1 class="admin-logo font-bold hidden md:block text-xl color">Booknest</h1>
        </div>
        <div class="flex justify-center items-center">
            <div class="mr-3">
                <p class="text-sm font-medium float-right header-admin-detail"><?php echo $admin['name']; ?></p>
                <p class="text-xs text-gray-500 font-medium header-admin-detail"><?php echo $admin['email']; ?></p>
            </div>
            <div class="user-log w-8 md:w-10 h-8 md:h-10 header-admin-image cursor-pointer">
                <img src="../files/user_images/<?php echo $admin['user_img']; ?>" class="rounded-full object-cover w-full h-full" alt="">
            </div>
        </div>
    </nav>
</header>

<div class="admin-detail z-50 flex justify-center items-center h-full w-full bg-gray-400 bg-opacity-20 fixed left-0 -top-full duration-100 ease-linear">
    <div class="bg-white flex justify-center items-center flex-col py-4 px-6 rounded-md relative">
        <h1 class="text-center text-lg md:text-xl font-medium text-temp">Admin Details</h1>
        <div class="relative">
            <img src="../files/user_images/<?php echo $admin['user_img']; ?>" class="rounded-full w-32 h-32 my-4 object-cover" alt="">
            <label for="profile-picture" class="absolute bottom-0 right-0 bg-temp text-white p-2 rounded-full cursor-pointer hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                    <circle cx="12" cy="13" r="4" />
                </svg>
                <form action="admin_dashboard.php" method="post" enctype="multipart/form-data" id="profile_image">
                    <input type="file" id="profile-picture" name="profile_picture" class="hidden">
                    <input type="submit" value="Upload" name="image_upld" class="hidden" onchange="this.form.submit()">
                </form>
            </label>
        </div>
        <table class="text-sm md:text-base text-left mt-4 mb-3">
            <tr>
                <th class="py-0.5 px-3">Username:</th>
                <td class="text-gray-600 py-0.5 px-3"><?php echo $admin['name']; ?></td>
            </tr>
            <tr>
                <th class="py-0.5 px-3">Email ID:</th>
                <td class="text-gray-600 py-0.5 px-3"><?php echo $admin['email']; ?></td>
            </tr>
        </table>
        <div class="flex w-full justify-center items-center mt-2 gap-x-3">
            <a href="admin_dashboard_handler.php" class="bg-temp text-white text-base md:text-lg py-1 px-4 rounded hover:bg-blue-800 h-fit">Edit Profile</a>
            <a href="admin_dashboard_change_password.php" class="text-base md:text-lg py-1 px-4 h-fit rounded bg-green-600 text-white hover:bg-green-700">Change Password</a>
        </div>
        <button class="admin_detail_close text-xl text-black hover:text-temp absolute top-4 right-5"><i class="fa-solid fa-xmark"></i></button>
    </div>
</div>

<script>
    document.getElementById('profile-picture').addEventListener('change', function(e) {
        document.getElementById('profile_image').submit();
    });
</script>