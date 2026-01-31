<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 1) {
    header("Location: login.php");
    exit;
}

$user_nama = $_SESSION['user_nama'];

// Stats Logic (Removed Sales Stats)
$count_user = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM user"));
$count_barang = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barang"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Aplikasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="index.php" class="brand"><i class="fas fa-store"></i> Admin Panel</a>
            <div>
                <a href="index.php" class="active"><i class="fas fa-home"></i> Home</a>
                <a href="user_index.php"><i class="fas fa-users"></i> User</a>
                <a href="barang_index.php"><i class="fas fa-box"></i> Barang</a>
                <a href="logout.php" style="background: rgba(231, 76, 60, 0.2); padding: 5px 15px; border-radius: 15px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1>Dashboard Admin</h1>
                <p style="color: #666; margin: 0;">Selamat Datang, <b><?php echo $user_nama; ?></b>!</p>
            </div>
            <div style="text-align: right;">
                <span style="background: #e8f0fe; color: #4e73df; padding: 5px 15px; border-radius: 20px; font-size: 0.9em; font-weight: 500;">
                    <?php echo date('l, d F Y'); ?>
                </span>
            </div>
        </div>
        
        <div class="card-grid">
            <a href="user_index.php" class="card" style="text-decoration: none; border-left: 5px solid #4e73df; padding: 25px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="margin: 0 0 5px 0; color: #4e73df; font-size: 1.1em; text-transform: uppercase; letter-spacing: 1px;">Data User</h3>
                    <p style="font-size: 2.5em; margin: 0; color: #333; font-weight: bold;"><?php echo $count_user; ?></p>
                    <span style="color: #858796; font-size: 0.9em;">Pengguna Sistem</span>
                </div>
                <div style="font-size: 3em; color: #dddfeb;">
                    <i class="fas fa-users"></i>
                </div>
            </a>
            
            <a href="barang_index.php" class="card" style="text-decoration: none; border-left: 5px solid #1cc88a; padding: 25px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="margin: 0 0 5px 0; color: #1cc88a; font-size: 1.1em; text-transform: uppercase; letter-spacing: 1px;">Data Barang</h3>
                    <p style="font-size: 2.5em; margin: 0; color: #333; font-weight: bold;"><?php echo $count_barang; ?></p>
                    <span style="color: #858796; font-size: 0.9em;">Stok Barang</span>
                </div>
                <div style="font-size: 3em; color: #dddfeb;">
                    <i class="fas fa-boxes"></i>
                </div>
            </a>
        </div>

        <div style="margin-top: 40px;">
             <h3><i class="fas fa-info-circle"></i> Informasi Sistem</h3>
             <div style="background: #f8f9fc; padding: 20px; border-radius: 10px; border: 1px solid #e3e6f0;">
                <p style="margin: 0;">Anda login sebagai <strong>Administrator</strong>. Anda memiliki akses penuh untuk mengelola data master (User dan Barang).</p>
             </div>
        </div>
    </div>
</body>
</html>
