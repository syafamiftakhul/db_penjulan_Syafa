<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 1) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_barang'];
    $beli = $_POST['harga_beli'];
    $jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO barang (nama_barang, harga_beli, harga_jual, stok) VALUES ('$nama', '$beli', '$jual', '$stok')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: barang_index.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang - Aplikasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
        <div class="nav-container">
            <a href="index.php" class="brand"><i class="fas fa-store"></i> Admin Panel</a>
            <div>
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="user_index.php"><i class="fas fa-users"></i> User</a>
                <a href="barang_index.php"><i class="fas fa-box"></i> Barang</a>
                <a href="logout.php" style="background: rgba(231, 76, 60, 0.2); padding: 5px 15px; border-radius: 15px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Tambah Barang</h2>
        <form action="" method="POST">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" required>

            <label>Harga Beli</label>
            <input type="number" name="harga_beli" required>

            <label>Harga Jual</label>
            <input type="number" name="harga_jual" required>

            <label>Stok</label>
            <input type="number" name="stok" required>

            <button type="submit" name="simpan">Simpan</button>
            <br><br>
            <a href="barang_index.php">Kembali</a>
        </form>
    </div>
</body>
</html>
