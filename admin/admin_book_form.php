<?php
    ob_start();
    include_once('../db_connect.php');
    session_start();
    if(isset($_GET['book_id'])){
        $book_id = $_GET['book_id'];
        $book_array = $con->query("SELECT * FROM `books` WHERE `b_id` = $book_id");
        $book_detail = $book_array->fetch_assoc();  
    }
    $b_cover_directory = '../files/book_cover/';
    $b_file_directory = '../files/book_file/';
    $categorys = $con->query("SELECT * FROM `category`");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo isset($_GET['book_id'])?"Update":"Insert" ?> Form - Booknest</title>
    <link rel="icon" href="../files/Logo/logo.svg" type="image/icon type">
    <script src="../files/add-on/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="../files/add-on/jquery.validate.min.js?v=<?php echo time(); ?>"></script>
    <script src="../files/add-on/additional-methods.min.js?v=<?php echo time(); ?>"></script>
</head>
<body class="bg-main relative">

    <?php require_once('admin_header.php'); ?>

    <div class="main-admin flex">
        
        <?php require_once('admin_slidebar.php'); ?>

        <div class="w-full flex justify-center items-start px-4 py-6 md:px-16 md:py-8 flex-col">
            <h1 class="text-base md:text-lg font-medium mb-4"><a href="admin_book.php" class="hover:underline hover:decoration-temp hover:text-temp">Book</a>/
            <?php 
            if(isset($_GET['book_id'])){
                ?>
                <a href="admin_book_detail.php?book_id=<?php echo $book_id; ?>" class="hover:underline hover:decoration-temp hover:text-temp"><?php echo $book_detail['b_name']; ?></a>
                <?php
            } 
            ?></h1>
            <div class="bg-white w-full px-5 py-6 rounded-md">
                <form action="admin_book_form.php<?php echo isset($_GET['book_id'])?'?book_id'.'='.$book_id:''; ?>" method="post" class="space-y-3" id="book-from" enctype="multipart/form-data" data-edit-mode="<?php echo isset($_GET['book_id'])?'false':'true'; ?>">
                    <h5 class="text-base md:text-xl text-center font-medium text-temp">Book <?php echo isset($_GET['book_id'])?"Update":"Insert" ?> Form</h5>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Title</label>
                        <input type="text" name="book_name" placeholder="Enter Title" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" value="<?php echo isset($_GET['book_id'])? $book_detail['b_name']:""; ?>">
                    </div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Author Name</label>
                        <input type="text" name="book_author" placeholder="Enter Author Name" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" value="<?php echo isset($_GET['book_id'])? $book_detail['b_author']:""; ?>">
                    </div>
                    <?php
                        if($categorys->num_rows > 0){
                            ?>
                            <div class="input-field flex items-start md:items-center flex-col md:flex-row gap-y-3">
                                <label for="" class="text-sm md:text-base font-medium mr-3">Category</label>
                                <select name="book_cgry" class="border capitalize border-gray-500 rounded-md px-2 py-1 text-sm md:text-base">
                                    <option value="default" <?php echo isset($_GET['book_id']) ? "" : "selected"; ?> disabled>Select Category</option>
                                    <?php
                                        foreach ($categorys as $category) {
                                            ?>
                                            <option value="<?php echo strtolower($category['category_name']); ?>" 
                                                <?php echo isset($book_detail['b_category']) && $book_detail['b_category'] == strtolower($category['category_name']) ? "selected" : ""; ?>>
                                                <?php echo $category['category_name']; ?>
                                            </option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="error-cgry-box"></div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Enter Price</label>
                        <input type="number" name="price" placeholder="Enter Price" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" value="<?php echo isset($_GET['book_id'])? $book_detail['b_price']:""; ?>">
                    </div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Enter Discount (In percentage)</label>
                        <input type="number" name="discount" placeholder="Enter Discount" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp text-sm md:text-base" value="<?php echo isset($_GET['book_id'])? $book_detail['b_discount']:""; ?>">
                    </div>
                    <div class="input-field">
                        <label for="" class="text-sm md:text-base font-medium">Description</label>
                        <textarea name="book_desc" rows="3" placeholder="Enter Description" class="mt-2 capitalize w-full border border-gray-500 rounded-lg py-2 px-2 focus:outline-temp resize-none text-sm md:text-base"><?php echo isset($_GET['book_id'])?$book_detail['b_desc']:""; ?></textarea>
                    </div>
                    <div class="input-field flex flex-wrap justify-start items-center gap-y-3">
                        <?php if (isset($_GET['book_id']) && !empty($book_detail['b_cover_tmp'])){ ?>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500">Current Book Cover:</p>
                                <img src="<?php echo $b_cover_directory . $book_detail['b_cover_tmp']; ?>" alt="Book Cover" class="w-32 h-40 object-cover border border-gray-300 rounded-md">
                            </div>
                        <?php } ?>
                        <label for="bookCover" class="text-sm md:text-base font-medium mr-3">Upload Book Cover</label>
                        <input type="file" name="book_cover" id="bookCover" class="cursor-pointer text-sm md:text-base">
                    </div>
                    <div class="error-book-cover"></div>
                    <div class="input-field flex flex-wrap items-center justify-start gap-y-3">
                        <?php if (isset($_GET['book_id']) && !empty($book_detail['b_file'])){ ?>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500">Current Book File:</p>
                                <a href="<?php echo $b_file_directory . $book_detail['b_file']; ?>" target="_blank" class="text-blue-500 underline">View/Download Current Book File</a>
                            </div>
                        <?php }?>
                        <label for="bookFile" class="text-sm md:text-base font-medium mr-3">Upload Book</label>
                        <input type="file" name="book_file" id="bookFile" class="cursor-pointer text-sm md:text-base w-fit">
                    </div>
                    <div class="error-book-pdf"></div>
                    <div class="submit-box text-center pt-4">
                        <input type="submit" value="Submit" name="book-sub" class="bg-temp text-white py-1 px-3 rounded cursor-pointer">
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
if (isset($_POST['book-sub'])) {
    $b_name = $_POST['book_name'];
    $b_author = $_POST['book_author'];
    $b_category = $_POST['book_cgry'];
    $b_price = $_POST['price'];
    $b_discount = isset($_POST['discount']) && $_POST['discount'] !== '' ? $_POST['discount'] : 0;
    $b_description = $con->real_escape_string($_POST['book_desc']);
    
    // Ensure directories exist
    if (!is_dir($b_cover_directory)) {
        mkdir($b_cover_directory);
    }
    if (!is_dir($b_file_directory)) {
        mkdir($b_file_directory);
    }

    // Handle book cover
    $b_cover_name = $_FILES['book_cover']['name'];
    $b_cover_tmp_name = $_FILES['book_cover']['tmp_name'];
    $b_cover_unique_name = uniqid() . $b_cover_name;

    // Handle book file
    $b_file_name = $_FILES['book_file']['name'];
    $b_file_tmp_name = $_FILES['book_file']['tmp_name'];
    $b_file_unique_name = uniqid() . $b_file_name;

    // Check if new files are uploaded
    $is_new_cover_uploaded = !empty($b_cover_name) && move_uploaded_file($b_cover_tmp_name, $b_cover_directory . $b_cover_unique_name);
    $is_new_file_uploaded = !empty($b_file_name) && move_uploaded_file($b_file_tmp_name, $b_file_directory . $b_file_unique_name);

    if (isset($_GET['book_id'])) {
        // Update existing book
        if ($is_new_cover_uploaded) {
            // Delete old cover if a new one is uploaded
            if (!empty($book_detail['b_cover_tmp']) && file_exists($b_cover_directory . $book_detail['b_cover_tmp'])) {
                unlink($b_cover_directory . $book_detail['b_cover_tmp']);
            }
        } else {
            // Keep the old cover if no new one is uploaded
            $b_cover_unique_name = $book_detail['b_cover_tmp'];
            // $b_cover_name = $book_detail['b_cover'];
        }

        if ($is_new_file_uploaded) {
            // Delete old file if a new one is uploaded
            if (!empty($book_detail['b_file']) && file_exists($b_file_directory . $book_detail['b_file'])) {
                unlink($b_file_directory . $book_detail['b_file']);
            }
        } else {
            // Keep the old file if no new one is uploaded
            $b_file_unique_name = $book_detail['b_file'];
        }

        $update = "UPDATE `books` SET 
            `b_name`='$b_name',
            `b_author`='$b_author',
            `b_price`='$b_price',
            `b_discount`='$b_discount',
            `b_desc`='$b_description',
            `b_category`='$b_category',
            `b_cover_tmp`='$b_cover_unique_name',
            `b_file`='$b_file_unique_name'
            WHERE `b_id` = $book_id";

        if ($con->query($update)) {
            setcookie('success', 'Book is updated.', time() + 3, '/');
        } else {
            setcookie('error', 'Book is not updated.', time() + 3, '/');
        }
    } else {
        // Insert new book
        $b_publish_date = date('Y-m-d');
        $insert = "INSERT INTO `books`(`b_name`, `b_author`, `b_price`, `b_discount`, `b_desc`, `b_category`, `b_publish_date`, `b_cover_tmp`, `b_file`) 
            VALUES ('$b_name','$b_author', '$b_price', '$b_discount', '$b_description','$b_category','$b_publish_date', '$b_cover_unique_name' ,'$b_file_unique_name')";

        if ($con->query($insert)) {
            setcookie('success', 'Book is inserted.', time() + 3, '/');
        } else {
            // Rollback if insertion fails
            if (file_exists($b_cover_directory . $b_cover_unique_name)) {
                unlink($b_cover_directory . $b_cover_unique_name);
            }
            if (file_exists($b_file_directory . $b_file_unique_name)) {
                unlink($b_file_directory . $b_file_unique_name);
            }
            setcookie('error', 'Book is not inserted.', time() + 3, '/');
        }
    }

    ?>
    <script>window.location.href = "admin_book.php";</script>
    <?php
}
?>