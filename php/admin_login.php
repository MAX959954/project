<?php 
    session_start();

    require_once 'database.php';

    if ($_SERVER['REQUEST_METHOD'] ==='POST'){
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn , $sql);

        
    }


?>