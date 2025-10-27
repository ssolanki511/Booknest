<?php
    $result_link = $con->query("SELECT * FROM `links`");
    $result_link->fetch_assoc();

    $contact_result = $con->query("SELECT * FROM `contact_us`");
    $contact_us = $contact_result->fetch_assoc();

    $categorys = $con->query("SELECT * FROM `category`");
?>
<footer class="w-full mt-12">
    <div class="footer-container bg-zinc-900 text-white">
        <div class="w-full footer-box flex justify-around items-start py-8 px-8 flex-wrap">
            <div class="footer-box-1 w-full md:w-72">
                <h1 class="">
                    <div class="flex justify-left items-center">
                        <div class="logo w-10 md:w-10">
                            <a href="index.php"><img src="files/Logo/logo.svg" alt="logo"></a>
                        </div>
                        <a href="index.php" class="">
                            <p class="color text-2xl font-serif">&nbsp;Booknest</p>
                        </a>
                    </div>
                </h1>
                <p class="text-xs md:text-sm mt-3 text-justify">Browse our vast collection of E-Books across various genres and discover your next great read.</p>
                <?php
                    if($result_link->num_rows > 0){
                        ?>
                        <div class="follow-links flex items-center space-x-3 mt-5">
                            <?php
                                foreach($result_link as $link){
                                    if($link['title'] == "Instagram"){
                                        if($link['status'] == 'active'){
                                            ?>
                                            <div class="follow-box rounded-full border-2 flex justify-center items-center w-8 h-8 md:w-10 md:h-10 group">
                                                <a href="<?php echo $link['link_url']; ?>" target="_blank" class="text-base md:text-xl w-full h-full flex justify-center items-center rounded-full group-hover:bg-pink-600 duration-100">
                                                    <i class="fa-brands fa-instagram"></i>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    }
                                    if($link['title'] == "Facebook"){
                                        if($link['status'] == 'active'){
                                            ?>
                                            <div class="follow-box rounded-full border-2 flex justify-center items-center w-8 h-8 md:w-10 md:h-10 group">
                                                <a href="<?php echo $link['link_url']; ?>" target="_blank" class="text-base md:text-xl w-full h-full flex justify-center items-center rounded-full group-hover:bg-blue-600 duration-100">
                                                    <i class="fa-brands fa-facebook"></i>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    }
                                    if($link['title'] == "Twitter"){
                                        if($link['status'] == 'active'){
                                            ?>
                                            <div class="follow-box rounded-full border-2 flex justify-center items-center w-8 h-8 md:w-10 md:h-10 group">
                                                <a href="<?php echo $link['link_url']; ?>" target="_blank" class="text-base md:text-xl w-full h-full flex justify-center items-center rounded-full group-hover:bg-slate-500 duration-100">
                                                    <i class="fa-brands fa-x-twitter"></i>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            ?>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <div class="footer-box-2 w-full md:w-fit flex justify-center items-start flex-col mt-4">
                <h1 class="text-base md:text-lg footer-heading relative w-fit mb-2">Quick Links</h1>
                <ul class="text-xs space-y-1 md:text-sm text-gray-400">
                    <li><a href="index.php" class="hover:text-gray-50">Home</a></li>
                    <li><a href="contact.php" class="hover:text-gray-50">Contact</a></li>
                    <?php
                        if(isset($_SESSION['user'])){
                            ?>
                            <li><a href="wishlist.php" class="hover:text-gray-50">Wishlist Item</a></li>
                            <li><a href="cart.php" class="hover:text-gray-50">Cart Item</a></li>
                            <li><a href="purchsed.php" class=" hover:text-gray-50">Purchased Item</a></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
            <?php
                if($categorys->num_rows > 0){
                    ?>
                    <div class="footer-box-3 w-full md:w-fit flex justify-center items-start flex-col mt-4">
                        <h1 class="text-base md:text-lg footer-heading relative w-fit mb-2">Explore</h1>
                        <ul class="text-xs space-y-1 md:text-sm text-gray-400">
                            <?php
                                foreach($categorys as $category){
                                    ?>
                                        <li><a href="search.php?category=<?php echo strtolower($category['category_name']); ?>" class="hover:text-gray-50 capitalize"><?php echo $category['category_name']; ?></a></li>
                                    <?php
                                }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
            ?>
            <div class=" footer-box-4 w-full md:w-64 mt-4">
                <h1 class="text-base md:text-lg footer-heading relative w-fit mb-2">About Us</h1>
                <p class="text-xs md:text-sm mt-3 text-justify"><?php echo $contact_us['about_us']; ?></p>
            </div>
        </div>
    </div>
</footer>