<?php
session_start();
require "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'];

    // Get pengaduan details
    $sql = "SELECT * FROM pengaduan WHERE id_pengaduan=?";
    $row = $conn->execute_query($sql, [$id])->fetch_assoc();
    $nik = $row['nik'];
    $laporan = $row['isi_laporan'];
    $foto = $row['foto'];
    $status = $row['status'];

    // Get masyarakat details using NIK
    $sql = "SELECT nama FROM masyarakat WHERE nik=?";
    $masyarakat = $conn->execute_query($sql, [$nik])->fetch_assoc();
    $nama_pelapor = $masyarakat['nama'];

    // Get tanggapan details
    $sql = "SELECT * FROM tanggapan WHERE id_pengaduan=?";
    $row = $conn->execute_query($sql, [$id])->fetch_assoc();
    $tanggal_tanggapan = $row['tgl_tanggapan'];
    $tanggapan = $row['tanggapan'];
    $id_petugas = $row['id_petugas'];

    // Get petugas details using id_petugas
    $sql = "SELECT nama_petugas FROM petugas WHERE id_petugas=?";
    $petugas = $conn->execute_query($sql, [$id_petugas])->fetch_assoc();
    $nama_petugas = $petugas['nama_petugas'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lihat Pengaduan</title>
</head>

<body>
    <h1>Lihat Pengaduan</h1>
    <a href="pengaduan.php">Kembali</a><br><br>

    <table border="1">
        <tr>
            <th>Nama Pelapor</th>
            <td><?= $nama_pelapor ?></td>
        </tr>
        <tr>
            <th>NIK Pelapor</th>
            <td><?= $nik ?></td>
        </tr>
        <tr>
            <th>Isi Laporan</th>
            <td><textarea readonly><?= $laporan ?></textarea></td>
        </tr>
        <tr>
            <th>Foto Pendukung</th>
            <td><img src="../gambar/<?= $foto ?>" alt="" width="250px"></td>
        </tr>
        <tr>
            <th>Tanggal Ditanggapi</th>
            <td><input type="date" value="<?= $tanggal_tanggapan ?>" disabled></td>
        </tr>
        <tr>
            <th>Nama Petugas</th>
            <td><?= $nama_petugas ?></td>
        </tr>
        <tr>
            <th>Tanggapan</th>
            <td><textarea readonly><?= $tanggapan ?></textarea></td>
        </tr>
    </table>

    <br>
    <a href="pengaduan.php">Kembali</a>
</body>

</html>