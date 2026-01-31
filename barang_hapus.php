<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 1) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query = "DELETE FROM barang WHERE id_barang = $id";

if (mysqli_query($koneksi, $query)) {
    header("Location: barang_index.php");
} else {
    echo "Error deleting record: " . mysqli_error($koneksi);
}
?>
