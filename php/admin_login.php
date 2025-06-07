<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/crud-operation.php';

class AdminLogin {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function login($email, $password, $rememberMe = false) {
        if (empty($email) || empty($password)) {
            $_SESSION['errors'] = ["Email and password are required"];
            $this->redirect("../index.php");
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            $_SESSION['errors'] = ["Database error. Please try again."];
            $this->redirect("../index.php");
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login_success'] = true;
            $_SESSION['login_message'] = "Welcome back! You've logged in successfully.";

            if ($rememberMe) {
                setcookie("user_email", $user["email"], time() + (86400 * 7), "/");
            }
        } else {
            $_SESSION['errors'] = ["Invalid email or password."];
        }

        $this->redirect("../index.php");
    }

    private function redirect($url) {
        header("Location: $url");
        exit();
    }
}

$dbConnection = new mysqli("localhost", "root", "root", "login_register"); // Replace with actual credentials
$adminLogin = new AdminLogin($dbConnection);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["log_in"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $rememberMe = !empty($_POST['remember_me']);
    $adminLogin->login($email, $password, $rememberMe);
}