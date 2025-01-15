<?php
// Start a session
session_start();

// Include database configuration
require_once 'config/database.php';

// Check if the user is already logged in
if (isset($_SESSION['role'])) {
    // Redirect based on user role
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['role'] == 'petugas') {
        header("Location: petugas/dashboard.php");
    } elseif ($_SESSION['role'] == 'masyarakat') {
        header("Location: masyarakat/dashboard.php");
    }
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Password hashed using MD5 (matches your DB structure)

    // Connect to the database
    $hostname = "localhost";
    $username1 = "root";
    $password1 = "";
    $database = "contoh";
    $conn = new mysqli($hostname, $username1, $password1, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is a masyarakat
    $query_masyarakat = $conn->prepare("SELECT * FROM masyarakat WHERE username = ? AND password = ?");
    $query_masyarakat->bind_param("ss", $username, $password);
    $query_masyarakat->execute();
    $result_masyarakat = $query_masyarakat->get_result();

    if ($result_masyarakat->num_rows > 0) {
        $user = $result_masyarakat->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'masyarakat';
        header("Location: masyarakat/dashboard.php");
        exit;
    }

    // Check if the user is a petugas or admin
    $query_petugas = $conn->prepare("SELECT * FROM petugas WHERE username = ? AND password = ?");
    $query_petugas->bind_param("ss", $username, $password);
    $query_petugas->execute();
    $result_petugas = $query_petugas->get_result();

    if ($result_petugas->num_rows > 0) {
        $user = $result_petugas->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['level']; // Either 'admin' or 'petugas'
        $redirect_page = $user['level'] == 'admin' ? "admin/dashboard.php" : "petugas/dashboard.php";
        header("Location: $redirect_page");
        exit;
    }

    // If no match, show error
    $error = "Invalid username or password!";
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Login</h1>
            
            <?php if (isset($error)): ?>
                <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST" action="" class="space-y-4">
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
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Login
                </button>
            </form>

            <p class="text-center text-gray-600 mt-4">
                Don't have an account? 
                <a href="masyarakat/registration.php" class="text-blue-500 hover:underline">Register here</a>
            </p>
        </div>
    </div>
</body>
</html>
