<?php
session_start();
require "../config/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto mt-10 p-5 bg-white shadow-md rounded">
        <h1 class="text-2xl font-bold mb-5 text-gray-700">Data Petugas</h1>
        
        <div class="mb-5 flex justify-between items-center">
            <a href="../admin/dashboard.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Kembali</a>
            <a href="petugas-form.php" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Tambah Petugas Baru</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-r">No</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-r">Nama</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-r">Telepon</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-r">Username</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-r">Level</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    $sql = "SELECT * FROM petugas";
                    $rows = $conn->execute_query($sql)->fetch_all(MYSQLI_ASSOC);
                    foreach ($rows as $row) {
                    ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 border-r"><?= ++$no ?></td>
                            <td class="px-4 py-2 border-r"><?= htmlspecialchars($row['nama_petugas']) ?></td>
                            <td class="px-4 py-2 border-r"><?= htmlspecialchars($row['telp']) ?></td>
                            <td class="px-4 py-2 border-r"><?= htmlspecialchars($row['username']) ?></td>
                            <td class="px-4 py-2 border-r"><?= htmlspecialchars($row['level']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <a href="petugas-edit.php?id=<?= $row['id_petugas'] ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</a>
                                <a href="petugas-hapus.php?id=<?= $row['id_petugas'] ?>" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Hapus</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
