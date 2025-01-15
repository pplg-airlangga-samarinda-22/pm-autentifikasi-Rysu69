<?php
// Start a session
session_start();

// Include database connection
require_once '../config/database.php';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash the password with MD5
    $telp = $_POST['telp'];

    // Check if the username or NIK already exists
    $check_query = $conn->prepare("SELECT * FROM masyarakat WHERE username = ? OR nik = ?");
    $check_query->bind_param("ss", $username, $nik);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or NIK already exists!";
    } else {
        // Insert the new user into the database
        $insert_query = $conn->prepare("INSERT INTO masyarakat (nik, nama, username, password, telp) VALUES (?, ?, ?, ?, ?)");
        $insert_query->bind_param("sssss", $nik, $nama, $username, $password, $telp);

        if ($insert_query->execute()) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Masyarakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Register as Masyarakat</h1>
            
            <?php if (isset($error)): ?>
                <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
            <?php elseif (isset($success)): ?>
                <p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
            <?php endif; ?>
            
            <form method="POST" action="" class="space-y-4">
                <div>
                    <label class="block text-gray-700">NIK:</label>
                    <input type="text" name="nik" required minlength="16" maxlength="16"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700">Name:</label>
                    <input type="text" name="nama" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700">Username:</label>
                    <input type="text" name="username" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700">Password:</label>
                    <input type="password" name="password" required
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700">Phone Number:</label>
                    <input type="text" name="telp" required maxlength="13"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Register
                </button>
            </form>

            <p class="text-center text-gray-600 mt-4">
                Already have an account? 
                <a href="../index.php" class="text-blue-500 hover:underline">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
