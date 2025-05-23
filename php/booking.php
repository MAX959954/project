<?php 

class Database_vacation {
    private mysqli $conn;

    public function __construct(string $host, string $user, string $password, string $dbname) {
        $this->conn = new mysqli($host, $user, $password, $dbname);

        if ($this->conn->connect_error) {  // FIX: $this->conn not $this-conn
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection(): mysqli {
        return $this->conn;
    }

    public function close(): void {
        $this->conn->close();
    }
}

class BookTrip {
    private string $title;
    private string $start_date;
    private string $end_date;

    public function __construct(array $data) {
        $this->title = htmlspecialchars($data['title'] ?? '');
        $this->start_date = $data['start_date'] ?? '';
        $this->end_date = $data['end_date'] ?? '';
    }

    public function isValid(): bool {
        return !empty($this->title) && !empty($this->start_date) && !empty($this->end_date);
    }

    public function save(Database_vacation $db): bool {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("INSERT INTO vacation (title, start_date, end_date) VALUES (?, ?, ?)");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sss", $this->title, $this->start_date, $this->end_date);
        return $stmt->execute();
    }

    public function displayConfirmation(): void {
        echo "<script>
            alert('Thank you for booking!\\nTrip: {$this->title}\\nStart Date: {$this->start_date}\\nReturn Date: {$this->end_date}');
        </script>";
    }
}

class BookingHandler {
    private Database_vacation $db;

    public function __construct() {  // FIX: __construct was misspelled
        $this->db = new Database_vacation('localhost', 'root', '', 'vacation');
    }

    public function handleBooking(array $postData): void {
        $booking = new BookTrip($postData);

        if ($booking->isValid()) {
            if ($booking->save($this->db)) {
                $_SESSION['booking_success'] = true;
                $this->db->close();
                header("Location: /project/index.php");
                exit();
            } else {
                $_SESSION['booking_error'] = "Error saving booking. Please try again.";
                $this->db->close();
                header("Location: /project/index.php");
                exit();
            }
        } else {
            $_SESSION['booking_error'] = "Please fill in all fields.";
            $this->db->close();
            header("Location: /project/index.php");
            exit();
        }
    }
}

// Entry point
try {
    $handler = new BookingHandler();
    $handler->handleBooking($_POST);
} catch (Exception $e) {
    $_SESSION['booking_error'] = "An error occurred: {$e->getMessage()}";
    header("Location: /project/index.php");
    exit();
}
?>
