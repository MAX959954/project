<?php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "login_register";
    private $charset = "utf8";
    private $conn;

    public function __construct() {
        $this->connect();
    }

   public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("❌ Database connection failed: " . $this->conn->connect_error);
        }

        if (!$this->conn->set_charset($this->charset)) {
            die("❌ Error loading character set {$this->charset}: " . $this->conn->error);
        }

        return $this->conn; // ✅ return the connection
   }


    public function getConnection() {
        return $this->conn;
    }
}

$db = new Database();
$conn = $db->getConnection();
