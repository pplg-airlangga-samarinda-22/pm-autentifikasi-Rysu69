<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $_POST['nik'];
    $sql = "SELECT * FROM masyarakat WHERE nik = ?";
    $cek = $conn->execute_query($sql, [$nik]);

    if ($cek->num_rows == 1) {
        echo "<script>alert('NIK sudah terdaftar!')</script>";
    } else {
        $nik = $_POST['nik'];

        $nama = $_POST['nama'];
        $telp = $_POST['telp'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $sql = "INSERT INTO masyarakat (nik, nama, telp, username, password) VALUES (?, ?, ?, ?, ?)";
        $row = $conn->execute_query($sql, [$nik, $nama, $telp, $username, $password]);
        echo "<script>alert('Data berhasil disimpan!')</script>";
        header("Location: masyarakat.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Masyarakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Tambah Masyarakat</h1>
        <form method="post" action="" class="space-y-4">
            <!-- NIK -->
            <div>
                <label for="nik" class="block text-sm font-medium text-gray-700">NIK:</label>
                <input type="text" id="nik" name="nik" maxlength="16" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Nama -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama:</label>
                <input type="text" id="nama" name="nama" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Telepon -->
            <div>
                <label for="telp" class="block text-sm font-medium text-gray-700">Telepon:</label>
                <input type="tel" id="telp" name="telp" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                <input type="text" id="username" name="username" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Submit Button -->
            <div>
                <button type="submit" 
                    class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Simpan
                </button>
            </div>
        </form>
        <!-- Back Button -->
        <div class="mt-4">
            <a href="masyarakat.php" 
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                Kembali
            </a>
        </div>
    </div>
</body>
</html>
