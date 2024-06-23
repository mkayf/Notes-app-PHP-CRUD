<?php
    $server_name = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'notes';

    $connection = mysqli_connect($server_name,$username,$password,$database);
    if(!$connection){
        echo 'Connection failed!';
    }
?>