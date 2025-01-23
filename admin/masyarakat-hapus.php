<?php 
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $nik = $_GET['nik'];
    
    $sql = "DELETE FROM masyarakat WHERE nik=?";
    $row = $conn->execute_query($sql, [$nik]);

    if ($row) {
        header("Location: masyarakat.php");
    }
}
?>