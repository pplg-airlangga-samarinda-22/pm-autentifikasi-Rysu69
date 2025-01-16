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

// Get the ID of the report to edit
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID pengaduan tidak ditemukan!";
    exit;
}

$id_pengaduan = $_GET['id'];

// Fetch the specific report from the database
$query_laporan = $conn->prepare("SELECT * FROM pengaduan WHERE id_pengaduan = ?");
$query_laporan->bind_param("s", $id_pengaduan);
$query_laporan->execute();
$result_laporan = $query_laporan->get_result();

if ($result_laporan->num_rows === 0) {
    echo "Laporan tidak ditemukan!";
    exit;
}

$laporan = $result_laporan->fetch_assoc();

// Handle form submission to update the report
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isi_laporan = $_POST['isi_laporan'];
    $foto = $laporan['foto']; // Keep the existing photo by default

    // Handle file upload if a new photo is provided
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);

        // Ensure the uploads directory exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto = $target_file; // Update the photo path
        } else {
            $error = "Gagal mengunggah foto baru. Silakan coba lagi.";
        }
    }

    // Update the report in the database
    $query_update = $conn->prepare("UPDATE pengaduan SET isi_laporan = ?, foto = ? WHERE id_pengaduan = ?");
    $query_update->bind_param("sss", $isi_laporan, $foto, $id_pengaduan);

    if ($query_update->execute()) {
        // Redirect to the dashboard after updating
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Gagal memperbarui laporan. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan - Masyarakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 relative px-4">

    <!-- Back to Dashboard Button -->
    <a href="dashboard.php" class="absolute top-4 left-4 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600">
        Kembali
    </a>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6">Edit Laporan Pengaduan</h1>

        <!-- Success or Error Message -->
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Form for Editing Report -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Isi Laporan:</label>
                    <textarea name="isi_laporan" rows="4"
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required><?php echo htmlspecialchars($laporan['isi_laporan']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Foto Saat Ini:</label>
                    <?php if ($laporan['foto']): ?>
                        <a href="<?php echo $laporan['foto']; ?>" target="_blank" class="text-blue-500 hover:underline">Lihat Foto</a>
                    <?php else: ?>
                        Tidak Ada Foto
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Ganti Foto (Opsional):</label>
                    <input type="file" name="foto" class="block w-full mt-2">
                </div>
                <button type="submit"
                    class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

</body>
</html>
