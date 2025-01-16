    <?php
    // Start a session
    session_start();

    // Check if the user is logged in and has the correct role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'masyarakat') {
        header("Location: ../index.php");
        exit;
    }

    // Include database configuration
    require_once '../config/database.php';

    // Fetch the username of the logged-in user
    $username = $_SESSION['username'];

    // Get the user's NIK from the database
    $query_user = $conn->prepare("SELECT nik FROM masyarakat WHERE username = ?");
    $query_user->bind_param("s", $username);
    $query_user->execute();
    $result_user = $query_user->get_result();

    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $nik = $user['nik'];
    } else {
        echo "User not found!";
        exit;
    }

    // Handle form submission for laporan pengaduan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $isi_laporan = $_POST['isi_laporan'];
        $tgl_pengaduan = date("Y-m-d");
        $status = '0'; // Default status: pending
        $foto = null; // Default no photo uploaded

      // Handle file upload if provided
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];

    // Set the target directory and file path
    $target_dir = '../gambar/'; // Adjust for the 'gambar' folder being outside 'masyarakat'
    $target_file = $target_dir . basename($foto_name);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($foto_tmp, $target_file)) {
        $foto = $target_file; // Save the file path to the database
    } else {
        $error = "Gagal mengunggah foto. Silakan coba lagi.";
    }
} else {
    $foto = null; // No file uploaded
}



        // Insert the laporan into the database
        $query_insert = $conn->prepare("INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, foto, status) VALUES (?, ?, ?, ?, ?)");
        $query_insert->bind_param("sssss", $tgl_pengaduan, $nik, $isi_laporan, $foto, $status);

        if ($query_insert->execute()) {
            // After successful insert, redirect to avoid resubmission on refresh
            header("Location: dashboard.php");
            exit; // Make sure to exit after the redirect
        } else {
            $error = "Gagal mengirim laporan. Silakan coba lagi.";
        }
    }

    // Fetch user's previous reports
    $query_laporan = $conn->prepare("SELECT * FROM pengaduan WHERE nik = ? ORDER BY tgl_pengaduan DESC");
    $query_laporan->bind_param("s", $nik);
    $query_laporan->execute();
    $result_laporan = $query_laporan->get_result();
    ?>

   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Masyarakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 relative px-4">

    <!-- Logout Button at the Top Right -->
    <a href="logout.php" class="absolute top-4 right-4 bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600">
        Logout
    </a>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6">Dashboard - Masyarakat</h1>

        <!-- Success or Error Message -->
        <?php if (isset($success)): ?>
            <p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Form for Pengaduan -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-bold mb-4">Tulis Laporan Pengaduan</h2>
            <form method="POST" enctype="multipart/form-data">
                <textarea name="isi_laporan" rows="4" placeholder="Tulis laporan Anda di sini..."
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                <div class="mt-4">
                    <label class="block text-gray-700">Upload Foto (Opsional):</label>
                    <input type="file" name="foto" class="block w-full mt-2">
                </div>
                <button type="submit"
                    class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Kirim Laporan
                </button>
            </form>
        </div>

        <!-- Table of Previous Reports -->
        <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Laporan Anda</h2>
    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Tanggal</th>
                <th class="border border-gray-300 px-4 py-2">Isi Laporan</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Foto</th>
                <th class="border border-gray-300 px-4 py-2">Aksi</th> <!-- Added Aksi Column -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_laporan->fetch_assoc()): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['tgl_pengaduan']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['isi_laporan']; ?></td>
                    <td class="border border-gray-300 px-4 py-2">
                        <?php 
                            if ($row['status'] === '0') {
                                echo "Belum Ditanggapi";
                            } else {
                                echo ucfirst($row['status']);
                            }
                        ?>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <?php if ($row['foto']): ?>
                            <a href="<?php echo $row['foto']; ?>" target="_blank" class="text-blue-500 hover:underline">Lihat Foto</a>
                        <?php else: ?>
                            Tidak Ada
                        <?php endif; ?>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <div class="rounded-md bg-yellow-300 text-center border-yellow-400 border-2">
                        <a href="edit-aduan.php?id=<?php echo $row['id_pengaduan']; ?>" 
                            class="text-grey-300 hover:underline text-center">
                            Edit
                        </a>
                        </div>
                    </td> <!-- Added Edit Link -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

    </div>

</body>
</html>

                                        