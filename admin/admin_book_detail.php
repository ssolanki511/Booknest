<?php
    include_once('../db_connect.php');
    session_start();
    $book_id = $_GET['book_id'];
    $book_array = $con->query("SELECT * FROM `books` WHERE `b_id` = '$book_id'");
    $book = $book_array->fetch_assoc();
    $b_cover_directory = '../files/book_cover/';
    $b_file_directory = '../files/book_file/';
    $b_cover = $b_cover_directory.$book['b_cover_tmp'];
    $b_file = $b_file_directory.$book['b_file'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rich Dad Poor Dad - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
</head>
<body class="bg-main relative">

    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        
        <?php require_once('admin_slidebar.php'); ?>

        <div class="px-6 py-5 w-full">
            <h3 class="font-medium text-base md:text-lg"><a href="admin_book.php" class="hover:underline hover:decoration-temp hover:text-temp">Book</a>/</h3>
            <div class="flex w-full justify-center items-start">
                <div class="bg-gray-50 w-full rounded-lg mt-3">
                    <h1 class="text-lg md:text-xl font-semibold text-temp text-center mt-4">Book Detail</h1>
                    <div class="flex justify-start items-s py-6 px-8 md:py-8 flex-col items-center md:flex-row md:justify-center md:items-start">
                        <div class="w-24 h-36 sm:w-40 sm:h-56 relative group flex justify-center items-center">
                            <img src="<?php echo $b_cover; ?>" class="w-full h-full object-cover group-hover:opacity-30" alt="">
                            <a href="<?php echo $b_file; ?>" target="_blank" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 hidden group-hover:block bg-temp py-1 px-2 rounded text-white">Preview</a>
                        </div>
                        <div class="space-y-1 w-full mt-4 md:mt-0 md:ml-8 md:w-9/12">
                            <span class="flex items-center">
                                <p class="text-sm md:text-lg font-medium">Title:</p>
                                <p class="text-sm md:text-base text-gray-500 ml-2"><?php echo $book['b_name']; ?></p>
                            </span>
                            <span class="flex items-center">
                                <p class="text-sm md:text-lg font-medium">Author:</p>
                                <p class="text-sm md:text-base text-gray-500 ml-2"><?php echo $book['b_author']; ?></p>
                            </span>
                            <span class="flex items-center">
                                <p class="text-sm md:text-lg font-medium">Cetagory:</p>
                                <p class="text-sm md:text-base text-gray-500 ml-2 capitalize"><?php echo $book['b_category']; ?></p>
                            </span>
                            <span class="flex items-center">
                                <p class="text-sm md:text-lg font-medium">publish date:</p>
                                <p class="text-sm md:text-base text-gray-500 ml-2"><?php echo date("d F Y", strtotime($book['b_publish_date'])); ?></p>
                            </span>
                            <span class="flex items-center">
                                <p class="text-sm md:text-lg font-medium">Price:</p>
                                <p class="text-sm md:text-base text-gray-500 ml-2">₹<?php echo $book['b_price']; ?></p>
                            </span>
                            <span class="flex items-center">
                                <p class="text-sm md:text-lg font-medium">Discount:</p>
                                <p class="text-sm md:text-base text-gray-500 ml-2"><?php echo $book['b_discount']; ?>%</p>
                            </span>
                            <span class="flex items-start w-full flex-wrap md:flex-nowrap">
                                <p class="text-sm md:text-lg font-medium text-nowrap">description:</p>
                                <span class="flex items-end w-full flex-wrap md:flex-nowrap">
                                    <p class="admin-book-description text-sm md:text-base text-gray-500 ml-2 line-clamp-3 w-full"><?php echo $book['b_desc']; ?></p>
                                    <button class="admin-book-more text-sm md:text-base hover:text-temp hover:underline hover:decoration-temp">More</button>
                                </span>
                            </span>
                            <div class="flex justify-center items-center pt-6 space-x-3">
                                <a href="admin_book_form.php?book_id=<?php echo $book['b_id']; ?>" class="bg-blue-600 hover:bg-blue-700 py-1 px-3 rounded-md text-white text-sm md:text-base">Edit Book</a>
                                <a href="admin_book_delete.php?book_id=<?php echo $book['b_id']; ?>" class="admin-user-delete-btn bg-red-600 hover:bg-red-700 py-1 px-3 rounded-md text-white text-sm md:text-base">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-delete-dialog z-30 hidden">
        <div class="fixed left-0 top-0 w-screen h-screen flex justify-center items-center">
            <div class="admin-delete-box bg-white rounded-lg px-2 py-6 sm:px-6 sm:py-8 z-50 flex justify-center items-center flex-col space-y-3 shadow-lg w-52 md:w-fit">
                <span class="text-2xl md:text-3xl">
                    <i class="fa-solid fa-trash"></i>
                </span>
                <span class="text-sm md:text-base text-center">Are you sure you want to delete this book?</span>
                <div class="flex space-x-3">
                    <button class="admin-delete-dialog-close border border-gray-200 py-1 md:py-2 px-3 text-gray-500 text-sm md:text-base rounded-lg hover:bg-blue-50 hover:text-black">No, cancel</button>
                    <a href="admin_book.php" class="py-1 md:py-2 px-3 bg-red-600 text-white text-sm md:text-base rounded-lg hover:bg-red-700">Yes, I'm sure</a>
                </div>
            </div>
        </div>
    </div>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>