<?php
session_start();
require "../config/database.php";

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $id = $_GET['id'];
    $sql = "SELECT * FROM pengaduan WHERE id_pengaduan=?";
    $row =  $conn->execute_query($sql, [$id])->fetch_assoc();
    $nik = $row['nik'];
    $laporan = $row['isi_laporan'];
    $foto = $row['foto'];
    $status = $row['status'];
} elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_petugas = $_SESSION['petugas'];
    $id_pengaduan = $_GET['id'];
    $tanggal = date('y-m-d');
    $tanggapan = $_POST['tanggapan'];
    $status = 'selesai';

    // update pengaduan
    $sql = "UPDATE pengaduan SET status=? WHERE id_pengaduan=?";
    $row = $conn->execute_query($sql, [$status, $id_pengaduan]);

    // kirim tangggapan
    $sql = "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, id_petugas) values (?, ?, ?, ?)";
    $row = $conn->execute_query($sql, [$id_pengaduan, $tanggal, $tanggapan, $id_petugas]);

    header("location:pengaduan.php");

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanggal pengaduan</title>
</head>
<body>
    <h1>Tanggal Pengaduan</h1>
    <a href="pengaduan.php">Kembali</a>
    <form action="" method="post">
        <div class="form-item">
            <label for="laporan">Isi laporan</label>
            <textarea name="laporan" id="laporan" readonly><?= $laporan ?></textarea>
        </div>
        <div class="form-item">
            <label for="foto">Foto pendukung</label>
            <img src="../gambar/<?= $foto ?>" alt="" width="250px">
        </div>
        <div class="form-item">
            <label for="tanggapan">Tanggapan</label>
            <textarea name="tanggapan" id="tanggapan"></textarea>
        </div>
        <button type="submit" name="selesai">Kirim tanggapan</button>
    </form>
    
</body>
</html>