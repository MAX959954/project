<?php 
   session_start();

   $admin_username = "admin";
   $amdim_hashed_password = password_hash('admin123' , PASSWORD_DEFAULT);

   $username = $_POST['admin_username'] ?? '';
   $password = $_POST['amdim_hashed_password'] ?? '';

    if ($username == $admin_username && password_verify($password ,$admin_hashed_password)){
        $_SESSION['admin_logged_in'] = true;
        header("Location : php/admin.php");
        exit();
    }else {
        header("Location : ../index.php?login=failed");
        exit();
    }

?>