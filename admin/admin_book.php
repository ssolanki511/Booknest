<?php
    include_once('../db_connect.php');
    session_start();
    $all_books = $con->query("SELECT * FROM `books` ORDER BY `b_id` DESC");
    $all_books->fetch_assoc();

    $all_category = $con->query("SELECT * FROM `category`");

    if(isset($_POST['category_sub'])){
        $category_title = trim($_POST['category_title']);
        
        $result = $con->query("SELECT * FROM `category` WHERE `category_name` = '$category_title'");

        if ($result->num_rows > 0) {
            setcookie('error', "Category already exists.", time()+3, '/');
        } else {
            $insert_query = "INSERT INTO `category` (`category_name`) VALUES ('$category_title')";

            if ($con->query($insert_query)) {
                setcookie('success', "Category added successfully.", time()+3, '/');
            } else {
                setcookie('error', "Failed to add category. Please try again.", time()+3, '/');
            }
        }
        ?>
        <script>window.location.href = "admin_book.php"; </script>
        <?php
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - Booknest</title>
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

        <div class="admin-books-container px-4 py-8 w-full flex justify-center items-start relative">
            <div class="admin-books-box bg-white w-11/12 rounded-md px-5 py-4">
                <div class="heading flex justify-center w-full items-center flex-col space-y-2 sm:justify-between sm:flex-row sm:space-y-0 sm:items-start">
                    <div class="flex items-center space-x-3 flex-col space-y-3 md:flex-row md:space-y-0">
                        <span class="text-base md:text-lg font-medium text-temp">All Books</span>
                    </div>
                    <div class="flex items-center justify-center gap-y-3 flex-wrap">
                        <button class="category-form-open py-1 px-2 text-sm md:text-base border rounded-lg ml-3 border-temp text-temp hover:border-gray-800 hover:text-black">Categorys</button>
                        <a href="admin_book_form.php" class="py-1 px-2 text-sm md:text-base border rounded-lg ml-3 border-temp text-temp hover:border-gray-800 hover:text-black">Add Book</a>
                    </div>
                </div>

                <div class="relative overflow-x-auto mt-5">
                    <?php
                        if($all_books->num_rows > 0){
                            ?>
                                <table class="w-full text-left text-gray-500" id="myTable">
                                    <thead class="text-xs md:text-sm text-gray-700 uppercase bg-gray-100 text-center text-nowrap">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">#</th>
                                            <th scope="col" class="px-6 py-3">Title</th>
                                            <th scope="col" class="px-6 py-3">Category</th>
                                            <th scope="col" class="px-6 py-3">Published Date</th>
                                            <th scope="col" class="px-6 py-3">Price</th>
                                            <th scope="col" class="px-6 py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php 
                                            $i = 1;
                                            foreach($all_books as $book) {
                                                ?>
                                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 text-sm md:text-base text-nowrap">
                                                    <td class="px-6 py-3"><?php echo $i++; ?></td>
                                                    <td class="px-6 py-3 capitalize"><a href="admin_book_detail.php?book_id=<?php echo $book['b_id']; ?>"><?php echo $book['b_name']; ?></a></td>
                                                    <td class="px-6 py-3"><?php echo $book['b_category']; ?></td>
                                                    <td class="px-6 py-3"><?php echo date("d F Y", strtotime($book['b_publish_date'])); ?></td>
                                                    <td class="px-6 py-3">₹<?php echo $book['b_price']; ?></td>
                                                    <td>
                                                        <a href="admin_book_delete.php?book_id=<?php echo $book['b_id']; ?>" class="hover:text-temp p-2 h-8 w-8 grid place-items-center text-sm sm:text-base">
                                                            <i class="fa-solid fa-x"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            <?php
                        }else{
                            ?>
                            <p class="text-center my-4 text-red-600 font-semibold text-xs md:text-lg">No products are added at the moment.</p>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="category-form fixed -top-full left-0 bg-gray-600 z-40 h-screen w-screen bg-opacity-15 flex justify-center items-center duration-100 ease-linear px-4">
        <div class="bg-white w-full md:w-2/4 py-5 rounded-md px-6 relative">
            <form action="admin_book.php" method="post" class="flex flex-col gap-y-3 my-3" id="category-form">
                <h5 class="text-temp text-center font-medium text-base md:text-lg">Category Form</h5>
                <div>
                    <label for="category_title" class="block font-medium text-gray-800 mb-1 md:mb-2 text-sm md:text-base">Category Title</label>
                    <input type="text" name="category_title" id="category_title" class="border capitalize border-gray-300 rounded-lg px-2 py-1 text-sm md:text-base focus:outline-1 focus:outline-temp w-full">
                </div>
                <div class="text-center pt-2 md:pt-4">
                    <input type="submit" name="category_sub" value="Submit" class="py-1 px-3 bg-temp text-white rounded-md hover:bg-blue-800 cursor-pointer text-sm md:text-base">
                </div>
            </form>
            <?php
                if($all_category->num_rows > 0){
            ?>
            <span class="text-base md:text-lg font-medium text-temp mb-1 block">All Categorys</span>
            <div class="relative overflow-x-auto">
                <table class="w-full text-left text-gray-500" id="myTable">
                    <thead class="text-xs md:text-sm text-gray-700 uppercase bg-gray-100 text-center text-nowrap">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Category Title</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                            $all_category->fetch_assoc();
                            $i = 1;
                            foreach($all_category as $categorys){
                                ?>
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 text-sm md:text-base text-nowrap">
                                    <td class="px-6 py-3"><?php echo $i++; ?></td>
                                    <td class="px-6 py-3 capitalize"><?php echo $categorys['category_name']; ?></td>
                                    <td>
                                        <a href="delete_category.php?category_id=<?php echo $categorys['id']; ?>" class="hover:text-temp p-2 h-8 w-8 grid place-items-center text-sm sm:text-base">
                                            <i class="fa-solid fa-x"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
                }
            ?>
            <button class="category-form-close absolute top-2 right-3 text-lg md:text-xl"><i class="fa-solid fa-xmark text-temp"></i></button>
        </div>
    </div>
    <script src="../files/js_files/admin_valid.js?v=<?php echo time(); ?>"></script>
    <script src="../files/js_files/admin_main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php
    
?>