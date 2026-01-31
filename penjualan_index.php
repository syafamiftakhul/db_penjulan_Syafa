<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$query = "SELECT p.*, b.nama_barang, b.harga_jual, u.user_nama 
          FROM penjualan p 
          JOIN barang b ON p.id_barang = b.id_barang 
          JOIN user u ON p.user_id = u.user_id 
          ORDER BY p.tgl_jual DESC, p.id_jual DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Penjualan - Aplikasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav>
        <div class="nav-container">
            <a href="index.php" class="brand"><i class="fas fa-cash-register"></i> Kasir Panel</a>
            <div>
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="penjualan_form.php"><i class="fas fa-calculator"></i> Transaksi</a>
                <a href="penjualan_index.php" class="active"><i class="fas fa-history"></i> Riwayat</a>
                <a href="logout.php" style="background: rgba(231, 76, 60, 0.2); padding: 5px 15px; border-radius: 15px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Riwayat Penjualan</h2>
        <a href="penjualan_form.php" class="button" style="background: #3498db; color: white; padding: 10px; text-decoration: none; border-radius: 4px;">+ Transaksi Baru</a>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Harga Barang</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['tgl_jual']; ?></td>
                    <td><?php echo $row['nama_barang']; ?></td>
                    <td>Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['jumlah']; ?></td>
                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['user_nama']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
