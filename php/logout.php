<?php 
    session_start();
    session_destroy();

    setcookie("admin_username" , "" , time() - 3600 , "/");
    setcookie("admin_password" , "" , time() - 3600 , "/");
    setcookie("admin_remember" , "" , time() - 3600 , "/");

    header("Location: ../index.php");
    exit();
?>