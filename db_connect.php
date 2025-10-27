<?php
    try{
        $con = mysqli_connect('localhost', 'root', '', 'booknest');
    }catch(Exception $e){
        die('Error: Database is not connected');
    }
?>