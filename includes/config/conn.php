<?php
    $server = "localhost";

    $username = "dbadmin";
    $password = "CmD5j@9n62UR";
    $database = "ArvieDS";

    if(!$conn = mysqli_connect($server, $username, $password, $database)){
        die("Failed to Connect to Database!");
    }
?>
