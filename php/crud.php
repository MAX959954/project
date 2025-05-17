<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Menu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e9ecef;
        }
        .menu-container {
            max-width: 700px;
            margin: 60px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .menu-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .menu-header h1 {
            margin: 0;
            font-size: 28px;
            color: #495057;
        }
        .menu-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }
        .menu-buttons a {
            text-decoration: none;
            text-align: center;
            background: #28a745;
            color: #ffffff;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .menu-buttons a:hover {
            background: #218838;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <div class="menu-header">
            <h1>CRUD Operations</h1>
        </div>
        <div class="menu-buttons">
            <a href="create.php">Create</a>
            <a href="read.php">Read</a>
            <a href="update.php">Update</a>
            <a href="delete.php">Delete</a>
        </div>
    </div>
</body>
</html>

<?php
class Database {
    private $host = "localhost";
    private $db_name = "your_database_name";
    private $username = "your_username";
    private $password = "your_password";
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}

class CRUD {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO your_table_name (column1, column2) VALUES (:value1, :value2)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':value1', $data['value1']);
        $stmt->bindParam(':value2', $data['value2']);
        if ($stmt->execute()) {
            echo "Record created successfully.";
        } else {
            echo "Failed to create record.";
        }
    }

    public function read() {
        $sql = "SELECT * FROM your_table_name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE your_table_name SET column1 = :value1, column2 = :value2 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':value1', $data['value1']);
        $stmt->bindParam(':value2', $data['value2']);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "Record updated successfully.";
        } else {
            echo "Failed to update record.";
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM your_table_name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "Record deleted successfully.";
        } else {
            echo "Failed to delete record.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();
    $crud = new CRUD($db);

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $crud->create(['value1' => 'Sample1', 'value2' => 'Sample2']);
                break;
            case 'read':
                $records = $crud->read();
                print_r($records);
                break;
            case 'update':
                $crud->update(1, ['value1' => 'Updated1', 'value2' => 'Updated2']);
                break;
            case 'delete':
                $crud->delete(1);
                break;
        }
    }
}
?>
<form method="POST" style="display: none;">
    <input type="hidden" name="action" id="action">
</form>
<script>
    document.querySelectorAll('.menu-buttons a').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.textContent.toLowerCase();
            document.getElementById('action').value = action;
            document.querySelector('form').submit();
        });
    });
</script>