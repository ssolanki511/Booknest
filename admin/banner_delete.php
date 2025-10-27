<?php
    include_once('../db_connect.php');
    session_start();

    if(isset($_GET['banner_id'])){
        $banner_id = $_GET['banner_id'];
        $result = $con->query("SELECT `banner_img` FROM `banner` WHERE `banner_id` = $banner_id");
        $row = $result->fetch_assoc();
        $delete = "DELETE FROM `banner` WHERE `banner_id` = $banner_id";
        unlink('../files/banners/'.$row['banner_img']);
        if($con->query($delete)){
            setcookie('success', "Banner successfully delete.",time()+3,'/');
        }else{
            setcookie('error', "Banner not delete.",time()+3,'/');
        }
    }
?>
<script>window.location.href="admin_advertisement.php"</script>