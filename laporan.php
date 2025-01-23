<?php
session_start();
require "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan</title>
</head>

<body>
    <center>
        <h1>Laporan</h1>
    </center>
    <a href="javascript:window.print();">Cetak</a>
    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIK Pelapor</th>
                <th>Nama Pelapor</th>
                <th>Isi Laporan</th>
                <th>Status</th>
                <th>Tanggal Tanggapan</th>
                <th>Nama Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            $sql = "SELECT tgl_pengaduan, nik, isi_laporan, status, id_pengaduan FROM pengaduan";
            $rows = $koneksi->execute_query($sql)->fetch_all(MYSQLI_ASSOC);

            foreach ($rows as $row) {
                $nik = $row['nik'];
                $id_pengaduan = $row['id_pengaduan'];

                // Get masyarakat details
                $sql_masyarakat = "SELECT nama FROM masyarakat WHERE nik=?";
                $masyarakat = $koneksi->execute_query($sql_masyarakat, [$nik])->fetch_assoc();
                $nama_pelapor = $masyarakat['nama'] ?? 'Tidak Ditemukan';

                // Get tanggapan details
                $sql_tanggapan = "SELECT tgl_tanggapan, tanggapan, id_petugas FROM tanggapan WHERE id_pengaduan=?";
                $tanggapan_row = $koneksi->execute_query($sql_tanggapan, [$id_pengaduan])->fetch_assoc();
                $tanggal_tanggapan = $tanggapan_row['tgl_tanggapan'] ?? '-';
                $tanggapan = $tanggapan_row['tanggapan'] ?? '-';
                $id_petugas = $tanggapan_row['id_petugas'] ?? null;

                // Get petugas details
                if ($id_petugas) {
                    $sql_petugas = "SELECT nama_petugas FROM petugas WHERE id_petugas=?";
                    $petugas = $koneksi->execute_query($sql_petugas, [$id_petugas])->fetch_assoc();
                    $nama_petugas = $petugas['nama_petugas'] ?? '-';
                } else {
                    $nama_petugas = '-';
                }
            ?>
                <tr>
                    <td><?= ++$no ?></td>
                    <td><?= $row['tgl_pengaduan'] ?></td>
                    <td><?= $row['nik'] ?></td>
                    <td><?= $nama_pelapor ?></td>
                    <td><?= $row['isi_laporan'] ?></td>
                    <td><?= ($row['status'] == 0) ? 'Menunggu' : (($row['status'] == 'proses') ? 'Diproses' : 'Selesai') ?></td>
                    <td><?= $tanggal_tanggapan ?></td>
                    <td><?= $nama_petugas ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
