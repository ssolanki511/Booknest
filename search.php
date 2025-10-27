<?php
    include_once('db_connect.php');
    session_start();

    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $category = $_GET['category'];
        $temp_category = $category;
        // echo $category;
    } else {
        $category = 'All';
        $temp_category = $category.' Categories';
    } // Default to 'All' if no category is provided

    $categorys = $con->query("SELECT * FROM `category`");
    $filter_pricing = $con->query("SELECT * FROM `filter_price`");
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="files/css_files/home.css?v=<?php echo time(); ?>">
    <link rel="icon" href="files/Logo/logo.svg" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web/css/all.css">
    <title>Book - Booknest</title>
    <script src="files/add-on/jquery.min.js"></script>

</head>

<body class="bg-gray-100 relative">
    
    <?php include_once('cookie_display.php'); ?>
    <!-- navbar -->
    <?php require_once('header.php') ?>

    <div class="search-container">
        <div class="search-box flex">
            <div class="w-full">
                <?php
                    if($categorys->num_rows > 0){
                        ?>
                        <div class="flex items-center mt-4 mx-4 bg-black p-2 rounded-lg relative flex-col md:flex-row md:justify-center">
                            <h3 id="category-title" class="text-white font-semibold text-sm md:text-lg md:absolute md:left-1/2 md:-translate-x-1/2 mb-2 md:mb-0 capitalize"><?php echo $temp_category; ?></h3>
                            <div class="flex items-center space-x-2 justify-center   md:justify-end w-full">
                                <select name="product-pricing" id="price-select" class="border border-gray-500 text-xs md:text-sm font-medium rounded py-1 px-2">
                                    <option value="relevant">Relevant</option>
                                    <?php
                                        foreach($filter_pricing as $filter){
                                            ?>
                                            <option value="<?php echo strtolower($filter['pricing']); ?>">
                                                <?php echo $filter['pricing']; ?>
                                            </option>
                                            <?php
                                        }
                                    ?>
                                </select>
                                <select id="category-select" class="border border-gray-500 text-xs md:text-sm font-medium rounded py-1 px-2">
                                    <option value="All" <?php echo $category == 'All' ? 'selected' : ''; ?>>All</option>
                                    <?php
                                        foreach($categorys as $category){
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
                        </div>
                        <?php
                    }
                ?>
                <div id="book-container" class="product-lists mt-3 w-full grid place-items-center grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-y-16">
                    
                </div>
            </div>
        </div>
    </div>
    <?php require_once('footer.php') ?>
    
    <script>
        $(document).ready(function () {
            // Function to fetch books
            function fetchBooks(category, priceRange, searchQuery = '') {
                $.ajax({
                    url: 'fetch_book.php', // The PHP file to handle the request
                    type: 'GET',
                    data: { category: category, price_range: priceRange, search_query: searchQuery},
                    success: function (response) {
                        $('#book-container').html(response); // Update the book container with the response
                    },
                    error: function () {
                        alert('Failed to load books. Please try again.');
                    }
                });
            }

            // Handle category selection from the dropdown
            $('#category-select').on('change', function () {
                const category = $(this).val(); 
                const priceRange = $('#price-select').val();
                const searchQuery = new URLSearchParams(window.location.search).get('search_query') || '';
                const newUrl = `search.php?category=${category}&price_range=${priceRange}&search_query=${searchQuery}`;
                window.history.pushState(null, '', newUrl);
                $('#category-title').text(category === 'All' ? 'All Categories' : category);
                fetchBooks(category, priceRange, searchQuery);
            });

            $('#price-select').on('change', function () {
                const category = $('#category-select').val();
                const priceRange = $(this).val();
                const searchQuery = new URLSearchParams(window.location.search).get('search_query') || '';
                const newUrl = `search.php?category=${category}&price_range=${priceRange}&search_query=${searchQuery}`;
                window.history.pushState(null, '', newUrl);
                fetchBooks(category, priceRange, searchQuery);
            });

            $('#search-btn').on('click', function (e) {
                e.preventDefault(); // Prevent default form submission
                const searchQuery = $('#search-input').val().trim();
                if (searchQuery === '') {
                    alert('Please enter a search query.');
                    return;
                }
                const category = $('#category-select').val();
                const priceRange = $('#price-select').val();
                const newUrl = `search.php?category=${category}&price_range=${priceRange}&search_query=${encodeURIComponent(searchQuery)}`;
                window.history.pushState(null, '', newUrl);
                fetchBooks(category, priceRange, searchQuery);
            });

            // Automatically load books if a category is passed in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category') || 'All';
            const priceRange = urlParams.get('price_range') || 'relevant';
            const searchQuery = urlParams.get('search_query') || '';
            $('#category-select').val(category);
            $('#price-select').val(priceRange);
            fetchBooks(category, priceRange, searchQuery); 
        });
    </script>
</body>
</html>