<?php
session_start();
require "../config/database.php";

// Handle GET and POST requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    // Fetch petugas data based on the provided ID
    $sql = "SELECT * FROM petugas WHERE id_petugas=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $nama = htmlspecialchars($row['nama_petugas']);
        $username = htmlspecialchars($row['username']);
        $telepon = htmlspecialchars($row['telp']);
        $level = $row['level'];
    } else {
        die('Petugas not found.');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $telepon = $_POST['telepon'];
    $level = $_POST['level'];

    // Update query includes username
    $sql = "UPDATE petugas SET nama_petugas=?, username=?, telp=?, level=? WHERE id_petugas=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $nama, $username, $telepon, $level, $id);
    $success = $stmt->execute();

    if ($success) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui petugas');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Petugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-5 bg-white shadow-md rounded">
        <h1 class="text-2xl font-bold mb-5 text-gray-700">Edit Petugas</h1>
        <form action="" method="post" class="space-y-4">
            <!-- Level -->
            <div>
                <label for="level" class="block text-gray-700 font-medium">Level Akses</label>
                <select name="level" id="level" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="admin" <?= ($level === 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="petugas" <?= ($level === 'petugas') ? 'selected' : '' ?>>Petugas</option>
                </select>
            </div>
            
            <!-- Nama -->
            <div>
                <label for="nama" class="block text-gray-700 font-medium">Nama Petugas</label>
                <input type="text" name="nama" id="nama" value="<?= $nama ?>" 
                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            </div>
            
            <!-- Telepon -->
            <div>
                <label for="telepon" class="block text-gray-700 font-medium">Telepon</label>
                <input type="tel" name="telepon" id="telepon" value="<?= $telepon ?>" 
                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            </div>
            
            <!-- Username -->
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username</label>
                <input type="text" name="username" id="username" value="<?= $username ?>" 
                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
            </div>
            
            <!-- Submit and Cancel -->
            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Simpan</button>
                <a href="dashboard.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
