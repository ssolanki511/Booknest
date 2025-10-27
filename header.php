<?php
    if(isset($_SESSION['guest'])){
        $image_tmp = "defualt_image.png";
    }else{
        $user_id = $_SESSION['user'];
        $user_array = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
        $user_data = $con->query($user_array)->fetch_assoc();
        $image_tmp = $user_data['user_img'];
    }
?>
<header class="navbar sticky top-0 left-0 block w-full z-20">
    <nav class="top bg-zinc-950 text-white relative flex justify-between z-20 items-center px-4 lg:h-16 shadow-none lg:shadow-md">
        <div class="flex justify-center items-center">
            <div class="slide-bar text-xl flex justify-center items-center mr-3 lg:hidden cursor-pointer w-5">
                <i class="mobile-scroll fa-solid fa-bars"></i>
            </div>
            <div class="flex justify-center items-center">
                <div class="logo w-10 lg:w-12 p-2">
                    <a href="index.php"><img src="files/Logo/logo.svg" alt="logo"></a>
                </div>
                <a href="index.php" class="">
                    <p class="color hidden sm:block text-2xl font-serif">Booknest</p>
                </a>
            </div>
        </div>
        <ul class="slider absolute top-full left-0 bg-zinc-950 text-white w-full sm:w-1/2 space-y-2 px-4 py-6 h-screen -translate-x-full border-t-2 border-gray-300 duration-300 lg:-translate-x-0 md:bg-transparent md:flex lg:static lg:h-fit lg:p-0 lg:space-y-0 lg:justify-center lg:items-center lg:space-x-5 lg:border-none text-sm lg:text-base font-medium text-nowrap">
            <li>
                <a href="index.php" class="hover:text-temp">Home</a>
            </li>
            <li>
                <a href="search.php" class="hover:text-temp">Books</a>
            </li>
            <li>
                <a href="contact.php" class="hover:text-temp">Contact us</a>
            </li>
            <?php
            if(isset($_SESSION['guest'])){
                ?>
                <li class="lg:hidden">
                    <a href="login.php" class="hover:text-temp">Login</a>
                </li>
                <li class="lg:hidden">
                    <a href="login.php" class="hover:text-temp">Register</a>
                </li>
                <?php
            }
            ?>
            <li>
                <div class="ms-search w-52 mr-2 hidden md:block h-10">
                    <div class="search-box">
                        <form id="search-form"  class="flex w-full border px-3 py-1.5 rounded-lg h-full focus-within:shadow" method="get">
                            <input type="search" id="search-input" name="search-input" placeholder="Search" class="outline-none text-base w-full bg-zinc-950">
                            <button class="fa-solid fa-magnifying-glass text-white text-base hover:text-temp" id="search-btn" name="search-btn"></button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
        <div class="profile-box flex items-center space-x-5 translate-x-0 duration-300 relative">
            <?php 
                if(isset($_SESSION['user'])){
                    ?>
                    <div class="flex space-x-5 items-center">
                        <a href="wishlist.php" class="text-white hover:text-temp text-xl"><i class="fa-solid fa-heart"></i></a>
                        <a href="cart.php" class=" text-white hover:text-temp text-xl"><i class="fa-solid fa-shopping-cart"></i></a>
                    </div>
                    <div class="profile-icon-box rounded-full">
                        <div class="user-icon-container cursor-pointer rounded-full border-2 p-0 hover:border-temp ">
                            <img src="files/user_images/<?php echo $image_tmp; ?>" class="h-7 w-7 object-cover rounded-full" alt="">
                        </div>
                    </div>
                    <?php
                }
                if(isset($_SESSION['guest'])){
                    ?>
                    <a href="login.php" class="bg-temp px-4 py-1 rounded text-base hidden md:block">Login</a>
                    <a href="register.php" class="bg-green-600 px-4 py-1 rounded text-base hidden md:block">Register</a>
                    <?php
                }
            ?>
        </div>

        <div class="user-setup-box absolute right-0 top-full mt-1 duration-200">
            <div class="user-semi-box hidden">
                <div class="user-setup bg-zinc-900  rounded-md">
                    <ul class="text-sm text-white">
                        <a href="profile.php">
                            <li class="hover:bg-gray-800 px-4 py-2 rounded-tr-md rounded-tl-md space-x-2">
                                <i class="fa-solid fa-user px-1"></i>
                                Profile
                            </li>
                        </a>
                        <a href="logout.php">
                            <li class="hover:bg-gray-800 px-4 py-2 rounded-br-md rounded-bl-md space-x-2">
                                <i class="fa-solid fa-arrow-right-from-bracket px-1"></i>
                                Log Out
                            </li>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
        
    </nav>
    <nav class="bottom max-w-full px-4 bg-zinc-950 pb-2 -translate-y-0 duration-200 lg:pb-0 lg:bg-temp border-b-2 border-gray-400 lg:border-none z-20">
        <div class="search-box border border-gray-400 flex justify-between items-center px-4 py-1 rounded-lg w-full bg-white lg:bg-main lg:hidden shadow shadow-gray-300">
            <input type="search" name="" id="" placeholder="Search" class="search-input outline-none text-base lg:text-lg w-full bg-white">
            <button class="fa-solid fa-magnifying-glass text-lg"></button>
        </div>
    </nav>
</header>

<script>
    $(document).ready(function () {
        $('#search-btn').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission
            const query = $('#search-input').val().trim();
            if (query === '') {
                alert('Please enter a search query.');
                return;
            }

            const currentPage = window.location.pathname.split('/').pop();

            if (currentPage !== 'search.php') {
                // Redirect to [search.php](http://_vscodecontentref_/3) with the query
                window.location.href = `search.php?search_query=${encodeURIComponent(query)}`;
            } else {
                // Perform AJAX search if already on [search.php](http://_vscodecontentref_/4)
                fetchBooks(query);
            }
        });
        
        function fetchBooks(query) {
            $.ajax({
                url: 'fetch_book.php',
                type: 'GET',
                data: {search_query: query },
                success: function (response) {
                    $('#book-container').html(response); // Update the book container with the response
                },
                error: function () {
                    alert('Failed to fetch books. Please try again.');
                }
            });
        }
    });
</script>