<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 1) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['user_nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $status = $_POST['user_status'];

    $query = "INSERT INTO user (user_nama, username, password, user_status) VALUES ('$nama', '$username', '$password', '$status')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: user_index.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User - Aplikasi Penjualan</title>
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
        <h2>Tambah User</h2>
        <form action="" method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="user_nama" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Status</label>
            <select name="user_status">
                <option value="1">Admin</option>
                <option value="2">Kasir</option>
            </select>

            <button type="submit" name="simpan">Simpan</button>
            <br><br>
            <a href="user_index.php">Kembali</a>
        </form>
    </div>
</body>
</html>
