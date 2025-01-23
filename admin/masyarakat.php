<?php 
session_start();
require "../config/database.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Masyarakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-5 bg-white shadow-md rounded">
        <h1 class="text-2xl font-bold text-gray-700 mb-5">Data Masyarakat</h1>
        
        <!-- Navigation Buttons -->
        <div class="mb-5 flex space-x-4">
            <a href="dashboard.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Kembali</a>
            <a href="masyarakat-form.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Tambah Masyarakat</a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 border-b">No</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 border-b">NIK</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 border-b">Nama</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 border-b">Username</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 border-b">Telepon</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 0;
                    $sql = "SELECT * FROM masyarakat";
                    $rows = $conn->execute_query($sql)->fetch_all(MYSQLI_ASSOC);
                    foreach ($rows as $row) {
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-700 border-b"><?= ++$no ?></td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b"><?= htmlspecialchars($row['nik']) ?></td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b"><?= htmlspecialchars($row['username']) ?></td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b"><?= htmlspecialchars($row['telp']) ?></td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700 border-b space-x-2">
                            <a href="masyarakat-edit.php?nik=<?= urlencode($row['nik']) ?>" 
                               class="text-blue-500 hover:text-blue-700">Edit</a>
                            <a href="masyarakat-hapus.php?nik=<?= urlencode($row['nik']) ?>" 
                               class="text-red-500 hover:text-red-700"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
       
    </div>
</body>
</html>
