<?php
session_start();
require "../config/database.php";

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_pengaduan = $_GET['id'];
    $sql = "DELETE FROM pengaduan WHERE id_pengaduan=?";
    $row = $conn->execute_query($sql, [$id_pengaduan]);

    header("location:pengaduan.php");

}
?>