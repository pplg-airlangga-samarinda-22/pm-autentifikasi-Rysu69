<?php
session_start();
require "../config/database.php";

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pengaduan WHERE id_pengaduan=?";
    $row = $conn->execute_query($sql, [$id])->fetch_assoc();
    $nik = $row['nik'];
    $laporan = $row['isi_laporan'];
    $foto = $row['foto'];
    $status = $row['status'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_GET['id'];
    $sql = "UPDATE pengaduan SET status='proses' WHERE id_pengaduan=?";
    $row = $conn->execute_query($sql, [$id]);

    if($row){
        header("location:pengaduan.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verval pangaduan</title>
</head>
<body>
    <h1>Verivikasi dan Validasi Pengaduan</h1>
    <a href="pengaduan.php">kembali</a>
    <form action="" method="post">
        <div class="form-item">
            <label for="laporan">Isi laporan</label>
            <textarea name="laporan" id="laporan" readonly<?= $laporan ?>></textarea>
        </div>
        <div class="form-item">
            <label for="foto">Foto pendukung</label>
            <img src="../uploads/<?= $foto ?>" alt="tidak ada foto" width="250px">
        </div>
        <button type="submit" name="selesai">Kirim tanggapan</button>
    </form>
    
</body>
</html>