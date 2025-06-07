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

  function attachDeleteHandler(card) {
    const deleteBtn = card.querySelector('.delete-btn');
    const id = card.getAttribute('data-id');
    const type = card.getAttribute('data-type');
    console.log("Deleting ID:", id, "Type:", type);

    deleteBtn.addEventListener('click', async () => {
      const confirmDelete = confirm("Are you sure you want to delete this " + type + "?");
      if (!confirmDelete) return;

      const formData = new FormData();
      formData.append('id', id);
      formData.append('action', type === 'client' ? 'delete_client' : 'delete_vacation');

      const response = await fetch('crud.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.text();
      console.log("Server response:", result.replace(/<[^>]*>?/gm, ''));

      if (result.includes("✅")) {
        card.remove();
      } else {
        console.error("Delete failed:", result);
      }
    });
  }

  function addClientCard(name, email, id) {
    const container = document.getElementById('cardContainer');
    const card = document.createElement('div');
    card.className = "bg-white p-5 rounded-xl shadow-md space-y-3";
    card.dataset.id = id;
    card.dataset.type = 'client';

    card.innerHTML = `
      <h2 class="text-xl font-semibold text-gray-700">${name}</h2>
      <p class="text-sm text-gray-500">📧 ${email}</p>
      <p class="text-sm text-gray-400">🕒 Created: ${new Date().toLocaleString()}</p>
      <div class="flex space-x-2 pt-3">
        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition edit-btn">✏️ Edit</button>
        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition delete-btn">🗑️ Delete</button>
      </div>
    `;
    container.appendChild(card);

    card.querySelector('.edit-btn').addEventListener('click', () => {
      showEditForm(id, name, email);
    });

    attachDeleteHandler(card);
  }

  function showEditForm(id, currentName, currentEmail) {
    document.getElementById('newClientForm').classList.add('hidden');
    document.getElementById('newVacationForm').classList.add('hidden');

    let editForm = document.getElementById('editClientForm');
    if (!editForm) {
      editForm = document.createElement('div');
      editForm.id = 'editClientForm';
      editForm.className = 'bg-white p-6 rounded-xl shadow-md mb-6';
      editForm.innerHTML = `
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Client</h2>
        <form id="editClientFormData" method="POST">
          <input type="hidden" name="action" value="edit_client">
          <input type="hidden" name="id" value="${id}">
          <div class="grid grid-cols-1 gap-4">
            <label>
              <span class="block text-sm font-medium text-gray-700">Name</span>
              <input type="text" name="full_name" class="w-full p-2 mt-1 border rounded" required />
            </label>
            <label>
              <span class="block text-sm font-medium text-gray-700">Email</span>
              <input type="email" name="email" class="w-full p-2 mt-1 border rounded" required />
            </label>
          </div>
          <div class="flex space-x-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update</button>
            <button type="button" onclick="document.getElementById('editClientForm').remove()" class="border px-4 py-2 rounded hover:bg-gray-100 transition">Cancel</button>
          </div>
        </form>
      `;
      document.querySelector('.max-w-6xl').appendChild(editForm);

      document.getElementById('editClientFormData').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
          const response = await fetch('crud.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.text();
          console.log(result.replace(/<[^>]*>?/gm, ''));

          if (result.includes("✅")) {
            const id = formData.get('id');
            const updatedName = formData.get('full_name');
            const updatedEmail = formData.get('email');

            const card = document.querySelector(`[data-id="${id}"]`);
            if (card) {
              card.querySelector('h2').textContent = updatedName;
              card.querySelector('p.text-gray-500').textContent = `📧 ${updatedEmail}`;
            }

            document.getElementById('editClientForm').remove();
          }
        } catch (error) {
          console.error("Error updating client: " + error.message);
        }
      });
    }

    editForm.querySelector('input[name="full_name"]').value = currentName;
    editForm.querySelector('input[name="email"]').value = currentEmail;
    editForm.classList.remove('hidden');
  }

  function addVacationCard(title, start, end, id) {
    const container = document.getElementById('cardContainer');
    const card = document.createElement('div');
    card.className = "bg-white p-5 rounded-xl shadow-md space-y-3";
    card.setAttribute('data-id', id);
    card.setAttribute('data-type', 'vacation');

    card.innerHTML = `
      <h2 class="text-xl font-semibold text-gray-700">${title}</h2>
      <p class="text-sm text-gray-500">📅 ${start} to ${end}</p>
      <p class="text-sm text-gray-400">🕒 Created: ${new Date().toLocaleString()}</p>
      <div class="flex space-x-2 pt-3">
        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition edit-vacation-btn">✏️ Edit</button>
        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition delete-btn">🗑️ Delete</button>
      </div>
    `;
    container.appendChild(card);

    card.querySelector('.edit-vacation-btn').addEventListener('click', () => {
      showEditVacationForm(id, title, start, end);
    });

    attachDeleteHandler(card);
  }

  function showEditVacationForm(id, currentTitle, currentStart, currentEnd) {
    document.getElementById('newClientForm').classList.add('hidden');
    document.getElementById('newVacationForm').classList.add('hidden');

    let editForm = document.getElementById('editVacationForm');
    if (!editForm) {
      editForm = document.createElement('div');
      editForm.id = 'editVacationForm';
      editForm.className = 'bg-white p-6 rounded-xl shadow-md mb-6';
      editForm.innerHTML = `
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Vacation</h2>
        <form id="editVacationFormData" method="POST">
          <input type="hidden" name="action" value="edit_vacation">
          <input type="hidden" name="id" value="${id}">
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
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update</button>
            <button type="button" onclick="document.getElementById('editVacationForm').remove()" class="border px-4 py-2 rounded hover:bg-gray-100 transition">Cancel</button>
          </div>
        </form>
      `;
      document.querySelector('.max-w-6xl').appendChild(editForm);

      document.getElementById('editVacationFormData').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
          const response = await fetch('crud.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.text();
          console.log(result.replace(/<[^>]*>?/gm, ''));

          if (result.includes("✅")) {
            const id = formData.get('id');
            const updatedTitle = formData.get('title');
            const updatedStart = formData.get('start_date');
            const updatedEnd = formData.get('end_date');

            const card = document.querySelector(`[data-id="${id}"][data-type="vacation"]`);
            if (card) {
              card.querySelector('h2').textContent = updatedTitle;
              card.querySelector('p.text-gray-500').textContent = `📅 ${updatedStart} to ${updatedEnd}`;
            }

            document.getElementById('editVacationForm').remove();
          }
        } catch (error) {
          console.error("Error updating vacation: " + error.message);
        }
      });
    }

    editForm.querySelector('input[name="title"]').value = currentTitle;
    editForm.querySelector('input[name="start_date"]').value = currentStart;
    editForm.querySelector('input[name="end_date"]').value = currentEnd;
    editForm.classList.remove('hidden');
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const action = formData.get('action');
        if (!action) {
          console.error("Action parameter is missing in the form submission.");
          return; // Prevent submission if action is missing
        }

        const response = await fetch('crud.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.text();
        console.log(result.replace(/<[^>]*>?/gm, ''));

        if (result.includes("✅")) {
          const idMatch = result.match(/ID:(\d+)/);
          const newId = idMatch ? idMatch[1] : null;

          if (action === 'add_client') {
            const name = form.querySelector('input[name="full_name"]').value;
            const email = form.querySelector('input[name="email"]').value;
            addClientCard(name, email, newId);
            toggleForm();
            form.reset();
          } else if (action === 'add_vacation') {
            const title = form.querySelector('input[name="title"]').value;
            const start = form.querySelector('input[name="start_date"]').value;
            const end = form.querySelector('input[name="end_date"]').value;
            addVacationCard(title, start, end, newId);
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
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Client List</h1>
      <div class="flex space-x-4">
        <button onclick="vacationForm()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">+ New Vacation</button>
        <button onclick="toggleForm()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">+ New Client</button>
      </div>
    </div>

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

require_once 'crud-operation.php'; // Include your database connection
require_once 'functions.php'; // Assume this contains a `Database` class with getConnection()

class CRUD {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add Client
    public function addClient($full_name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt && $stmt->bind_param("sss", $full_name, $email, $hashedPassword) && $stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function addVacation($title, $start_date, $end_date) {
        $sql = "INSERT INTO vacation (title, start_date, end_date) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt && $stmt->bind_param("sss", $title, $start_date, $end_date) && $stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function editClient($id, $full_name, $email) {
        if ($id <= 0) return false;
        $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt && $stmt->bind_param("ssi", $full_name, $email, $id)) {
            return $stmt->execute();
        }
        return false;
    }

    // Delete Vacation
    public function deleteVacation($id) {
        if ($id <= 0) {
            error_log("Invalid ID provided to deleteVacation: $id");
            return false;
        }
        $sql = "DELETE FROM vacation WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed for deleteVacation: " . $this->conn->error);
            return false;
        }
        if (!$stmt->bind_param("i", $id)) {
            error_log("Bind failed for deleteVacation (ID: $id): " . $stmt->error);
            $stmt->close();
            return false;
        }
        if (!$stmt->execute()) {
            error_log("Execute failed for deleteVacation (ID: $id): " . $stmt->error);
            $stmt->close();
            return false;
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    // Delete Client
    public function deleteClient($id) {
        if ($id <= 0) {
            error_log("Invalid ID provided to deleteClient: $id");
            return false;
        }
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed for deleteClient: " . $this->conn->error);
            return false;
        }
        if (!$stmt->bind_param("i", $id)) {
            error_log("Bind failed for deleteClient (ID: $id): " . $stmt->error);
            $stmt->close();
            return false;
        }
        if (!$stmt->execute()) {
            error_log("Execute failed for deleteClient (ID: $id): " . $stmt->error);
            $stmt->close();
            return false;
        }
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    public function editVacation($id, $title, $start_date, $end_date) {
        if ($id <= 0) return false;
        $sql = "UPDATE vacation SET title = ?, start_date = ?, end_date = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt && $stmt->bind_param("sssi", $title, $start_date, $end_date, $id)) {
            return $stmt->execute();
        }
        return false;
    }
}

class CRUDHandler {
    private $crud;

    public function __construct($crud) {
        $this->crud = $crud;
    }

    public function handleRequest() {
        $action = $_POST['action'] ?? '';
        $response = '';

        switch ($action) {
            case 'add_client':
                $response = $this->handleAddClient();
                break;

            case 'add_vacation':
                $response = $this->handleAddVacation();
                break;

            case 'edit_client':
                $response = $this->handleEditClient();
                break;

            case 'edit_vacation':
                $response = $this->handleEditVacation();
                break;

            case 'delete_client':
                $response = $this->handleDeleteClient();
                break;

            case 'delete_vacation':
                $response = $this->handleDeleteVacation();
                break;
        }

        // Log the response for debugging
        error_log("Response for action '$action': $response");
        echo $response; // Still echo to allow client-side handling
    }

    private function handleAddClient() {
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $id = $this->crud->addClient($full_name, $email, $password);
        return $id ? "✅ Client added successfully. ID:$id" : "❌ Failed to add client. Error: " . $this->crud->conn->error;
    }

    private function handleAddVacation() {
        $title = $_POST['title'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        if (empty($title) || empty($start_date) || empty($end_date)) {
            return "❌ All vacation fields are required.";
        }
        $id = $this->crud->addVacation($title, $start_date, $end_date);
        return $id ? "✅ Vacation added successfully. ID:$id" : "❌ Failed to add vacation. Error: " . $this->crud->conn->error;
    }

    private function handleEditClient() {
        $id = (int)($_POST['id'] ?? 0);
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        if ($id > 0 && $full_name && $email) {
            return $this->crud->editClient($id, $full_name, $email)
                ? "✅ Client updated successfully. ID:$id"
                : "❌ Failed to update client. ID:$id. Error: " . $this->crud->conn->error;
        }
        return "❌ Invalid data for editing client.";
    }

    private function handleEditVacation() {
        $id = (int)($_POST['id'] ?? 0);
        $title = $_POST['title'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        if ($id > 0 && $title && $start_date && $end_date) {
            return $this->crud->editVacation($id, $title, $start_date, $end_date)
                ? "✅ Vacation updated successfully. ID:$id"
                : "❌ Failed to update vacation. ID:$id. Error: " . $this->crud->conn->error;
        }
        return "❌ Invalid data for editing vacation.";
    }

    private function handleDeleteClient() {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            return "❌ Invalid ID.";
        }
        return $this->crud->deleteClient($id)
            ? "✅ Client deleted.||$id"
            : "❌ Failed to delete client. ID: $id. Error: " . $this->crud->conn->error;
    }

    private function handleDeleteVacation() {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            return "❌ Invalid ID.";
        }
        return $this->crud->deleteVacation($id)
            ? "✅ Vacation deleted.||$id"
            : "❌ Failed to delete vacation. ID: $id. Error: " . $this->crud->conn->error;
    }
}

// Initialize and handle the request
$database = new Database();
$db = $database->getConnection();
if (!$db) {
    error_log("Database connection failed: " . $database->getLastError());
    echo "❌ Database connection failed.";
    exit;
}

$crud = new CRUD($db);
$handler = new CRUDHandler($crud);
$handler->handleRequest();
?>