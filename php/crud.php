<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CRUD Menu Cards</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleForm() {
      document.getElementById('newClientForm').classList.toggle('hidden');
    }

    function vacationForm() {
      document.getElementById('newVacationForm').classList.toggle('hidden');
    }

    function addClientCard(name, email) {
      const container = document.getElementById('cardContainer');
      const card = document.createElement('div');
      card.className = "bg-white p-5 rounded-xl shadow-md space-y-3";

      card.innerHTML = `
        <h2 class="text-xl font-semibold text-gray-700">${name}</h2>
        <p class="text-sm text-gray-500">📧 ${email}</p>
        <p class="text-sm text-gray-400">🕒 Created: ${new Date().toLocaleString()}</p>
        <div class="flex space-x-2 pt-3">
          <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">✏️ Edit</button>
          <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">🗑️ Delete</button>
        </div>
      `;
      container.appendChild(card);
    }

    function addVacationCard(title, start, end) {
      const container = document.getElementById('cardContainer');
      const card = document.createElement('div');
      card.className = "bg-white p-5 rounded-xl shadow-md space-y-3";

      card.innerHTML = `
        <h2 class="text-xl font-semibold text-gray-700">${title}</h2>
        <p class="text-sm text-gray-500">📅 ${start} to ${end}</p>
        <p class="text-sm text-gray-400">🕒 Created: ${new Date().toLocaleString()}</p>
        <div class="flex space-x-2 pt-3">
          <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">✏️ Edit</button>
          <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">🗑️ Delete</button>
        </div>
      `;
      container.appendChild(card);
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          const formData = new FormData(form);
          const action = formData.get('action');

          const response = await fetch('crud.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.text();
          alert(result.replace(/<[^>]*>?/gm, ''));

          if (result.includes("✅")) {
            if (action === 'add_client') {
              const name = form.querySelector('input[name="full_name"]').value;
              const email = form.querySelector('input[name="email"]').value;
              addClientCard(name, email);
              toggleForm();
              form.reset();
            }

            if (action === 'add_vacation') {
              const title = form.querySelector('input[name="title"]').value;
              const start = form.querySelector('input[name="start_date"]').value;
              const end = form.querySelector('input[name="end_date"]').value;
              addVacationCard(title, start, end);
              vacationForm();
              form.reset();
            }
          }
        });
      });
    });
  </script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Client List</h1>
      <div class="flex space-x-4">
        <button onclick="vacationForm()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">+ New Vacation</button>
        <button onclick="toggleForm()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">+ New Client</button>
      </div>
    </div>

    <!-- Cards Container -->
    <div id="cardContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10"></div>

    <!-- New Client Form -->
    <div id="newClientForm" class="hidden bg-white p-6 rounded-xl shadow-md mb-6">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">New Client</h2>
      <form method="POST" action="crud.php">
        <input type="hidden" name="action" value="add_client">
        <div class="grid grid-cols-1 gap-4">
          <label>
            <span class="block text-sm font-medium text-gray-700">Name</span>
            <input type="text" name="full_name" class="w-full p-2 mt-1 border rounded" required />
          </label>
          <label>
            <span class="block text-sm font-medium text-gray-700">Email</span>
            <input type="email" name="email" class="w-full p-2 mt-1 border rounded" required />
          </label>
          <label>
            <span class="block text-sm font-medium text-gray-700">Password</span>
            <input type="password" name="password" class="w-full p-2 mt-1 border rounded" required />
          </label>
        </div>
        <div class="flex space-x-4 mt-6">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Submit</button>
          <button type="button" onclick="toggleForm()" class="border px-4 py-2 rounded hover:bg-gray-100 transition">Cancel</button>
        </div>
      </form>
    </div>

    <!-- New Vacation Form -->
    <div id="newVacationForm" class="hidden bg-white p-6 rounded-xl shadow-md mb-6">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">New Vacation</h2>
      <form method="POST" action="crud.php">
        <input type="hidden" name="action" value="add_vacation">
        <div class="grid grid-cols-1 gap-4">
          <label>
            <span class="block text-sm font-medium text-gray-700">Title</span>
            <input type="text" name="title" class="w-full p-2 mt-1 border rounded" required />
          </label>
          <label>
            <span class="block text-sm font-medium text-gray-700">Start date</span>
            <input type="date" name="start_date" class="w-full p-2 mt-1 border rounded" required />
          </label>
          <label>
            <span class="block text-sm font-medium text-gray-700">End date</span>
            <input type="date" name="end_date" class="w-full p-2 mt-1 border rounded" required />
          </label>
        </div>
        <div class="flex space-x-4 mt-6">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Submit</button>
          <button type="button" onclick="vacationForm()" class="border px-4 py-2 rounded hover:bg-gray-100 transition">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'crud-operation.php';
require_once 'functions.php';

class CRUD {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

   public function addClient($full_name, $email, $password) {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([$full_name, $email, $hashedPassword]);
  }

    public function addVacation($title, $start_date, $end_date) {
        $sql = "INSERT INTO vacation (title, start_date, end_date) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$title, $start_date, $end_date]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $database = new Database();
   $db = $database->getConnection(); // ✅ use getConnection() instead of connect()
   $crud = new CRUD($db);

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add_client':
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            if ($crud->addClient($full_name, $email, $password)) {
                echo '<script>alert("✅ Client added successfully.");</script>';
            } else {
                echo '<script>alert("❌ Failed to add client.");</script>';
            }
            break;

        case 'add_vacation':
            $title = $_POST['title'] ?? '';
            $start = $_POST['start_date'] ?? '';
            $end = $_POST['end_date'] ?? '';
            if ($crud->addVacation($title, $start, $end)) {
                add_message();
            } else {
                add_message();
            }
            break;

        default:
            echo "⚠️ Unknown action.";
    }
}

?>
