<?php
    ob_start();
    include_once('db_connect.php');
    session_start();

    $user_id = $_SESSION['user'];
    $select = "SELECT * FROM `users` WHERE user_id = '$user_id'";
    $result = $con->query($select);
    $row = $result->fetch_assoc();

    $cartCountQuery = "SELECT COUNT(*) AS cart_count FROM `add_to_cart` WHERE `user_id` = '$user_id'";
    $cartCountResult = $con->query($cartCountQuery);
    $cartCount = $cartCountResult->fetch_assoc()['cart_count'];

    $wishlistCountQuery = "SELECT COUNT(*) AS wishlist_count FROM `wishlist` WHERE `user_id` = '$user_id'";
    $wishlistCountResult = $con->query($wishlistCountQuery);
    $wishlistCount = $wishlistCountResult->fetch_assoc()['wishlist_count'];

    $purchasedCountQuery = "SELECT COUNT(*) AS purchased_count FROM `purchases` WHERE `user_id` = '$user_id'";
    $purchasedCountResult = $con->query($purchasedCountQuery);
    $purchasedCount = $purchasedCountResult->fetch_assoc()['purchased_count'];

    $recentPurchasesQuery = "
        SELECT p.purchase_date, b.b_id, b.b_name, p.price_at_purchase, b.b_price, p.purchase_id 
        FROM `purchases` p
        LEFT JOIN `books` b ON p.book_id = b.b_id
        WHERE p.user_id = '$user_id'
        ORDER BY p.purchase_date DESC
        LIMIT 4
    ";
    $recentPurchasesResult = $con->query($recentPurchasesQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Booknest</title>
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="files/tailwindcss/output.css">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <script src="files/add-on/jquery.min.js"></script>
    <script src="files/add-on/jquery.validate.min.js"></script>
    <script src="files/add-on/additional-methods.min.js"></script>
    </script>
</head>

<body class="bg-gray-100">
    <?php include_once('cookie_display.php'); ?>
    <?php  include_once('header.php'); ?>

    <div class="container mx-auto px-5 py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="relative">
                    <img src="files/user_images/<?php echo $row['user_img']; ?>" alt="Profile Photo"
                        class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                    <label for="profile-picture"
                        class="absolute bottom-0 right-0 bg-gray-700 text-white p-2 rounded-full cursor-pointer hover:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                            <circle cx="12" cy="13" r="4" />
                        </svg>
                        <form action="profile.php" method="post" enctype="multipart/form-data" id="profile_image">
                            <input type="file" id="profile-picture" name="profile_picture" class="hidden">
                            <input type="submit" value="Upload" name="image_upld" class="hidden" onchange="this.form.submit()">
                        </form>
                    </label>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-bold text-gray-800"><?php echo $row['name']; ?></h1>
                    <p class="text-gray-600"><?php echo $row['email']; ?></p>
                    <div class="mt-4">
                        <button class="bg-temp text-white px-4 py-2 mt-2 rounded-lg hover:bg-blue-700"
                            onclick="toggleEditProfile()">Edit Profile</button>
                        <button class="bg-red-600 text-white px-4 py-2 mt-2 rounded-lg hover:bg-red-700 mx-2"
                            onclick="logout()">Log Out</button>
                        <button class="bg-yellow-600 text-white px-4 py-2 mt-2 rounded-lg hover:bg-yellow-700 "
                            onclick="toggleChangePassword()">Change Password</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Section -->
        <div id="edit-profile-section" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
            <h2 class="text-xl font-bold mb-4">Edit Profile</h2>
            <form action="profile.php" method="post" id="editUserForm">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?php echo $row['name']; ?>">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="<?php echo $row['email']; ?>">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2" onclick="toggleEditProfile()">Cancel</button>
                    <input type="submit" name="update_profile" class="bg-temp text-white px-4 py-2 rounded-lg hover:bg-blue-700" value="Save Changes">
                </div>
            </form>
        </div>

        <!-- Change Password Section -->
        <div id="change-password-section" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
            <h2 class="text-xl font-bold mb-4">Change Password</h2>
            <form id="changePassword" method="post" action="profile.php">
                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="n_password" class="block text-gray-700">New Password</label>
                    <input type="password" id="n_password" name="n_password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="mb-4">
                    <label for="c_password" class="block text-gray-700">Confirm Password</label>
                    <input type="password" id="c_password" name="c_password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>
                <div class="flex justify-end">
                    <input type="submit" class="bg-blue-500 text-white px-4 py-2 rounded" name="change_password" value="Change Password">
                </div>
            </form>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-1">
                <div class="justify-between mb-6">
                    <a href="cart.php">
                        <div class="bg-white rounded-lg shadow p-6 text-center  mb-2 hover:bg-gray-300">
                            <div class="text-3xl font-bold text-blue-600"><?php echo $cartCount; ?></div>
                            <p class="text-gray-600">Cart Items</p>
                        </div>
                    </a>
                    <a href="wishlist.php">
                        <div class="bg-white rounded-lg shadow p-6 text-center  mb-2 hover:bg-gray-300">
                            <div class="text-3xl font-bold text-red-600"><?php echo $wishlistCount; ?></div>
                            <p class="text-gray-600">Wishlist Items</p>
                        </div>
                    </a>
                    <a href="purchsed.php">
                        <div class="bg-white rounded-lg shadow p-6 text-center  mb-2 hover:bg-gray-300">
                            <div class="text-3xl font-bold text-yellow-600"><?php echo $purchasedCount; ?></div>
                            <p class="text-gray-600">Purchased Items</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="space-y-6 md:col-span-3">

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Recent Purchase</h3>
                        <a href="purchsed.php" class="text-temp hover:text-blue-800">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-nowrap">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Book name</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Download</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                        if ($recentPurchasesResult->num_rows > 0) {
                            $count = 1;
                            while ($purchase = $recentPurchasesResult->fetch_assoc()) {
                                ?>
                                <tr class="border-b">
                                <td class="py-3 px-4"><?php echo $count++; ?></td>
                                    <td class="px-4">
                                        <?php echo $purchase['b_name'] ? $purchase['b_name']: '<span class="text-gray-500">Book no longer available</span>';?></td>
                                    <td class="px-4"><?php echo date('d-m-Y', strtotime($purchase['purchase_date'])); ?></td>
                                    <td class="px-4">₹<?php echo $purchase['price_at_purchase']; ?></td>
                                    <td class="px-4">
                                    <?php if ($purchase['b_name']) { ?>
                                        <a href="download.php?book_id=<?php echo $purchase['b_id']; ?>" class="bg-temp text-white px-3 py-1 rounded hover:bg-blue-900">Download</a>
                                    <?php } else { ?>
                                        <span class="text-gray-500">Not Available</span>
                                    <?php } ?>    
                                    </td>
                                </tr>
                            </tbody>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4'>No recent purchases found.</td></tr>";
                        }
                        ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <?php include 'footer.php'; ?>

    <script>
        document.getElementById('profile-picture').addEventListener('change', function(e) {
            document.getElementById('profile_image').submit();
        });
    </script>
    <script src="files/js_files/validation.js?v=<?php echo time(); ?>"></script>
    <script src="files/js_files/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php
    if (isset($_POST['update_profile'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
    
        $update = "UPDATE `users` SET `name` = '$username', `email`='$email' WHERE `user_id` = $user_id";
    
        if ($con->query($update)) {
            setcookie('success', 'Profile updated successfully', time() + 3, "/");
        } else {
            setcookie('error', 'Error in updating profile', time() + 3, "/");
        }
        ?>
        <script>
            window.location.href = "profile.php";
        </script>
        <?php
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileTmpName = $_FILES['profile_picture']['tmp_name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileError = $_FILES['profile_picture']['error'];
        $fileType = $_FILES['profile_picture']['type'];
    
        if (!file_exists('files/user_images')) {
            mkdir('files/user_images');
        }
    
        $fileExt = strtolower(end(explode('.', $fileName)));
        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) {
                    $fileNewName = uniqid('', true) . "." . $fileExt;
                    $fileDestination = 'files/user_images/' . $fileNewName;
                    move_uploaded_file($fileTmpName, $fileDestination);
    
                    $select = "SELECT `user_img` FROM `users` WHERE `user_id`='$user_id'";
                    $result = $con->query($select);
                    $row = $result->fetch_assoc();
                    $oldProfilePicture = $row['user_img'];
                    if ($oldProfilePicture && file_exists('files/user_images/' . $oldProfilePicture)) {
                        if($oldProfilePicture != 'default_image.svg'){
                            unlink('files/user_images/' . $oldProfilePicture);
                        }
                    }
                    $update = "UPDATE `users` SET `user_img`='$fileNewName' WHERE `user_id`='$user_id'";
                    if ($con->query($update)) {
                        setcookie('success', 'Profile picture updated successfully', time() + 3, "/");
                    } else {
                        setcookie('error', 'Error in updating profile picture', time() + 3, "/");
                    }
                } else {
                    setcookie('error', 'Your file is too large', time() + 3, "/");
                }
            } else {
                setcookie('error', 'There was an error uploading your file', time() + 3, "/");
            }
        } else {
            setcookie('error', 'You cannot upload files of this type', time() + 3, "/");
        }
        ?>
        <script>window.location.href = "profile.php";</script>
        <?php
    }

    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['n_password'];
        $confirmPassword = $_POST['c_password'];
    
        $select = "SELECT `password` FROM `users` WHERE `user_id`='$user_id'";
        $result = $con->query($select);
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];
    
        if ($currentPassword == $hashedPassword) {
            $update = "UPDATE `users` SET `password`='$confirmPassword' WHERE `user_id`='$user_id'";
            if ($con->query($update)) {
                setcookie('success', 'Password changed successfully', time() + 3, "/");
                ?>
                <script>
                    window.location.href = "logout.php";
                </script>
                <?php
            } else {
                setcookie('error', 'Error in changing password', time() + 3, "/");
            }
        } else {
            setcookie('error', 'Current password is incorrect', time() + 3, "/");
        }
        ?>
        <script>
            window.location.href = "profile.php";
        </script>
        <?php
    }
?>