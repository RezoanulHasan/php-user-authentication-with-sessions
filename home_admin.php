<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit(); 
}

function readUserData() {
    $userData = file("./data/users.txt", FILE_IGNORE_NEW_LINES);
    return $userData;
}

function writeUserData($userData) {
    file_put_contents("./data/users.txt", implode("\n", $userData));
}

function createUser($role, $email, $password, $firstname, $lastname, $age) {
    $newUser = "$role, $email, $password, $firstname, $lastname, $age";
    $userData = readUserData();
    $userData[] = $newUser;
    writeUserData($userData);
}

$userData = readUserData();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete"])) {
        $emailToDelete = $_POST["delete"];
        foreach ($userData as $key => $user) {
            list($role, $email, $password, $firstname, $lastname, $age) = explode(", ", $user);
            if ($email == $emailToDelete) {
                unset($userData[$key]);
                break;
            }
        }
        writeUserData($userData);
    } elseif (isset($_POST["update"])) {
        $emailToUpdate = $_POST["email"];
        $role = $_POST["role"];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $age = $_POST["age"];

        foreach ($userData as $key => $user) {
            list($oldRole, $oldEmail, $oldPassword, $oldFirstname, $oldLastname, $oldAge) = explode(", ", $user);
            if ($oldEmail == $emailToUpdate) {
                $userData[$key] = "$role, $emailToUpdate, $oldPassword, $firstname, $lastname, $age";
                break;
            }
        }

        writeUserData($userData);
    } elseif (isset($_POST["create"])) {
        $role = $_POST["role"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $age = $_POST["age"];
        
        createUser($role, $email, $password, $firstname, $lastname, $age);
        $userData = readUserData(); // Refresh user data after creating a new user
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Admin Panel</h1>
        <h2 class="text-lg mb-2">Welcome, <?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?></h2>
        <p class="text-gray-600 mb-4">Role: <?php echo $_SESSION["role"]; ?></p>
        <a href="logout.php" class="bg-red-500 text-white py-2 px-4 rounded">Logout</a>

        <h2 class="text-2xl text-center text-red-500 font-bold mt-8 mb-5"> ALL User Data</h2>

        <div class="text-center mt-5 mb-5">
            <button class="btn btn-xl bg-green-500 text-white px-3 py-4 mr-2 btn-create">Create user</button>
        </div>

        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">Role</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">First Name</th>
                    <th class="border px-4 py-2">Last Name</th>
                    <th class="border px-4 py-2">Age</th>
                    <th class="border px-4 py-2">Delete</th>
                    <th class="border px-4 py-2">Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($userData as $user) {
                        list($role, $email, $password, $firstname, $lastname, $age) = explode(", ", $user);
                        echo "<tr>
                                <td class='border px-4 py-2'>$role</td>
                                <td class='border px-4 py-2'>$email</td>
                                <td class='border px-4 py-2'>$firstname</td>
                                <td class='border px-4 py-2'>$lastname</td>
                                <td class='border px-4 py-2'>$age</td>
                                <td class='border px-4 py-2'>
                                    <form method='post'>
                                        <button type='submit' name='delete' value='$email' class='bg-red-500 text-white px-3 py-1 rounded-full mr-2'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </form>
                                </td>
                                <td class='border px-4 py-2'>
                                    <button type='button' class='edit-button bg-blue-500 text-white px-5 py-1 rounded-full mr-2'
                                        data-email='$email'
                                        data-role='$role'
                                        data-firstname='$firstname'
                                        data-lastname='$lastname'
                                        data-age='$age'>
                                        <i class='fas fa-edit'></i> Edit
                                    </button>
                                </td>
                              </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <div id="createModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
        <div class="modal-content bg-white p-4 rounded shadow-lg">
            <span class="modal-close-create cursor-pointer absolute top-0 right-0 p-4">&times;</span>
            <h2 class="text-2xl text-green-500 font-bold mb-4">Create New User</h2>
            <form id="createForm" method="post">
                <input  class="form-input w-full px-4 py-2 rounded border border-gray-300"  type="text" name="email" id="createEmail" placeholder="Email " required><br><br>
                <input  class="form-input w-full px-4 py-2 rounded border border-gray-300"  type="text" name="password" id="createPassword"placeholder="Password " required><br><br>

                <h1>Set Role Here</h1>
                <select name="role" id="createRole" class="p-2 rounded border text-black border-gray-300" required>
                    <option value="" disabled selected>Select a role</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="user">User</option>
                </select>

                <br><br>

                <input type="text" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="firstname" id="createFirstname" placeholder="First Name" required><br><br>
                <input type="text" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="lastname" id="createLastname" placeholder="Last Name" required><br><br>
                <input type="number" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="age" id="createAge" placeholder="Age" required><br><br>
                <button type="submit" name="create" class="bg-green-500 text-white px-2 py-1 rounded-full mr-2">
                    Create User
                </button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
      <div class="modal-content bg-white p-4 rounded shadow-lg">
        <span class="modal-close cursor-pointer absolute top-0 right-0 p-4">&times;</span>
        <h2 class="text-2xl text-blue-500 font-bold mb-4">Edit User Info</h2>
        <form   id="editForm" method="post">
          <input type="hidden" name="email" id="editEmail">

          <h1>Set Role Here</h1>
          <select  name="role" id="editRole" class="p-2 rounded border text-black border-gray-300"  required>
            <option value="" disabled selected>Select a role</option>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="user">User</option>
          </select>

          <br><br>

          <input type="text"   class="form-input w-full px-4 py-2 rounded border border-gray-300"  name="firstname" id="editFirstname" placeholder="First Name" required><br><br>
          <input type="text" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="lastname" id="editLastname" placeholder="Last Name" required><br><br>
          <input type="number" class="form-input w-full px-4 py-2 rounded border border-gray-300" name="age" id="editAge" placeholder="Age" required><br><br>
          <button type="submit" name="update" class="bg-blue-500 text-white px-2 py-1 rounded-full mr-2">
            Update
          </button>
        </form>
      </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const editButtons = document.querySelectorAll('.edit-button');
        const createButton = document.querySelector('.btn-create');
        const editModal = document.getElementById('editModal');
        const createModal = document.getElementById('createModal');
        const closeModalEdit = document.querySelector('.modal-close');
        const closeModalCreate = document.querySelector('.modal-close-create');
        const editForm = document.getElementById('editForm');
        const createForm = document.getElementById('createForm');

        editButtons.forEach(button => {
          button.addEventListener('click', function() {
            const email = button.dataset.email;
            const role = button.dataset.role;
            const firstname = button.dataset.firstname;
            const lastname = button.dataset.lastname;
            const age = button.dataset.age;

            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editFirstname').value = firstname;
            document.getElementById('editLastname').value = lastname;
            document.getElementById('editAge').value = age;

            editModal.classList.remove('hidden');
          });
        });

        createButton.addEventListener('click', function() {
            createModal.classList.remove('hidden');
        });

        closeModalEdit.addEventListener('click', function() {
          editModal.classList.add('hidden');
        });

        closeModalCreate.addEventListener('click', function() {
            createModal.classList.add('hidden');
        });

        editForm.addEventListener('submit', function() {
          editModal.classList.add('hidden');
        });

        createForm.addEventListener('submit', function() {
            createModal.classList.add('hidden');
        });
      });
    </script>
</body>
</html>
