<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 2) {
    header("Location: login.php");
    exit;
}

$user_nama = $_SESSION['user_nama'];
$user_id = $_SESSION['user_id'];

// Stats Logic Kasir
$count_transaksi_saya = mysqli_num_rows(mysqli_query($koneksi, "SELECT DISTINCT tgl_jual, user_id FROM penjualan WHERE user_id = '$user_id' AND tgl_jual = CURDATE()"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir - Aplikasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="index.php" class="brand"><i class="fas fa-cash-register"></i> Kasir Panel</a>
            <div>
                <a href="index.php" class="active"><i class="fas fa-home"></i> Home</a>
                <a href="penjualan_form.php"><i class="fas fa-calculator"></i> Transaksi</a>
                <a href="penjualan_index.php"><i class="fas fa-history"></i> Riwayat</a>
                <a href="logout.php" style="background: rgba(231, 76, 60, 0.2); padding: 5px 15px; border-radius: 15px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Dashboard Kasir</h1>
        <p>Selamat Datang, <b><?php echo $user_nama; ?></b>!</p>
        
        <div class="card-grid">
            <a href="penjualan_form.php" class="card" style="text-decoration: none; border-left-color: #36b9cc; padding: 25px; display: block;">
                <div style="font-size: 3em; color: #36b9cc; margin-bottom: 10px;">
                     <i class="fas fa-cart-plus"></i>
                </div>
                <h3>Transaksi Baru</h3>
                <p style="color: #666; margin-bottom: 15px;">Mulai transaksi penjualan baru</p>
                <button class="button" style="width: auto;">Buka Kasir</button>
            </a>
            
            <a href="penjualan_index.php" class="card" style="text-decoration: none; border-left-color: #f6c23e; padding: 25px; display: block;">
                <div style="font-size: 3em; color: #f6c23e; margin-bottom: 10px;">
                     <i class="fas fa-clock"></i>
                </div>
                <h3>Riwayat Penjualan</h3>
                <p style="color: #666;">Transaksi Anda hari ini: <b><?php echo $count_transaksi_saya; ?></b></p>
                <span style="color: #858796;">Lihat semua riwayat</span>
            </a>
        </div>
    </div>
</body>
</html>
