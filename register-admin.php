<?php

// Include database configuration
require_once 'config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_petugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Encrypt the password
    $telp = $_POST['telp'];
    $level = $_POST['level']; // Either 'admin' or 'petugas'

    // Check if the username already exists
    $query_check = $conn->prepare("SELECT * FROM petugas WHERE username = ?");
    $query_check->bind_param("s", $username);
    $query_check->execute();
    $result_check = $query_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = "Username already exists. Please choose another.";
    } else {
        // Insert the new admin/petugas into the database
        $query = $conn->prepare("INSERT INTO petugas (nama_petugas, username, password, telp, level) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("sssss", $nama_petugas, $username, $password, $telp, $level);

        if ($query->execute()) {
            $success = "User successfully registered as $level.";
        } else {
            $error = "Failed to register user. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6">Register Admin or Petugas</h1>

        <!-- Display Success or Error Message -->
        <?php if (isset($success)): ?>
            <p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Registration Form -->
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md mx-auto">
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="nama_petugas" class="block text-gray-700">Name:</label>
                    <input type="text" id="nama_petugas" name="nama_petugas" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Username:</label>
                    <input type="text" id="username" name="username" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="telp" class="block text-gray-700">Phone:</label>
                    <input type="text" id="telp" name="telp" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="level" class="block text-gray-700">Level:</label>
                    <select id="level" name="level" class="w-full p-2 border rounded-lg" required>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Register
                </button>
            </form>
            <p class="text-center text-gray-600 mt-4">
                Back to 
                <a href="masyarakat/registration.php" class="text-blue-500 hover:underline">Login</a>
            </p>
        </div>
    </div>
</body>
</html>
