<?php

$db_server = "localhost";
$db_user   = "root";
$db_pass   = "root";
$db_name   = "login_register";
$char_set  = "utf8";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
