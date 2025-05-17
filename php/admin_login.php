<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/crud-operation.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["log_in"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $_SESSION['errors'] = ["Email and password are required"];
        header("Location: ../index.php");
        exit();
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['errors'] = ["Database error. Please try again."];
        header("Location: ../index.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION['user'] = $user;
        $_SESSION['login_success'] = true;
        $_SESSION['login_message'] = "Welcome back! You've logged in successfully."; // Added custom message

        if (!empty($_POST['remember_me'])) {
            setcookie("user_email", $user["email"], time() + (86400 * 7), "/");
        }
    } else {
        $_SESSION['errors'] = ["Invalid email or password."];
    }
}

header("Location: ../index.php");
exit;
?>