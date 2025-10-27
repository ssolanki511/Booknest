<?php
include_once('../db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $status = $_POST['status'];

    if($type == 'banner'){
        if (!empty($id) && in_array($status, ['active', 'inactive'])) {
            if ($con->query("UPDATE `banner` SET `status` = '$status' WHERE `banner_id` = $id")) {
                setcookie('success','Status updated successfully!', time()+3,'/');
                echo 'success';
            } else {
                setcookie('error','Failed to update status.',time()+3,'/');
                echo 'error';
            }
        }
    }

    if($type == 'link'){
        if (!empty($id) && in_array($status, ['active', 'inactive'])) {
            if ($con->query("UPDATE `links` SET `status` = '$status' WHERE `id` = $id")) {
                setcookie('success','Status updated successfully!', time()+3,'/');
                echo 'success';
            } else {
                setcookie('error','Failed to update status.',time()+3,'/');
                echo 'error';
            }
        }
    }
}
?>