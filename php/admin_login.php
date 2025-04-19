<?php 
   session_start();

   $admin_username = "admin";
   $admin_password = "admin123";
   $admin_hashed_password = password_hash($admin_password , PASSWORD_DEFAULT);

   $username = $_POST['admin_username'] ?? '';
   $password = $_POST['admin_password'] ?? '';
   $remember = isset($_POST['remember_me']);

   $stored_hash = $admin_hashed_password;

    if ($username == $admin_username && password_verify($password ,$stored_hash)){
        $_SESSION['admin_logged_in'] = true;

        if ($remember) {
            setcookie("admin_usename" , $username , time() + (86400 * 30), "/");
            setcookie("admin_password" , "true" , time() + (86400 * 30) , "/" );
        }

        header("Location: php/admin.php");
        exit();
    }else {
        header("Location: ../index.php?login=failed");
        exit();
    }

?>