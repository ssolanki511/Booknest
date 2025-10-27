<div class="error-box">     
    <?php
        if(isset($_COOKIE['error'])){
            ?>
            <div class="fixed top-2 left-1/2 -translate-x-1/2 w-full md:w-fit bg-red-500 border border-red-600 text-red-900 px-4 py-3 rounded z-50">
                <span class="text-xs md:text-sm"><?php echo $_COOKIE['error']; ?></span>   
            </div>
            <?php
            setcookie('error', '', time() - 3600, "/");
        }
        if(isset($_COOKIE['success'])){
            ?>
            <div class="fixed top-2 left-1/2 -translate-x-1/2 w-full md:w-fit bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
                <span class="text-xs md:text-sm"><?php echo $_COOKIE['success']; ?></span>   
            </div>
            <?php
            setcookie('success', '', time() - 3600, "/");
        }
    ?>
    <script>
        setInterval(()=>{
            document.querySelector('.error-box').innerHTML = '';
        },3000);
    </script>
</div>