<?php
include_once('../db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!empty($id) && is_numeric($id)) {
        $result = $con->query("SELECT `title`, `link_url` FROM `links` WHERE `id` = $id");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Return the title and URL as a plain string separated by "|"
            echo $row['title'] . '|' . $row['link_url'];
        } else {
            echo 'Error|No record found';
        }

    } else {
        echo 'Error|Invalid ID';
    }
} else {
    echo 'Error|Invalid request method';
}
?>