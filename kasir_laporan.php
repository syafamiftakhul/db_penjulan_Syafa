<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 2) {
    header("Location: login.php");
    exit;
}

$user_nama = $_SESSION['user_nama'];

// Default dates
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');

// Query with Date Filter
$query = "SELECT p.*, b.nama_barang, b.harga_jual, u.user_nama 
          FROM penjualan p 
          JOIN barang b ON p.id_barang = b.id_barang 
          JOIN user u ON p.user_id = u.user_id 
          WHERE p.tgl_jual BETWEEN '$tgl_awal' AND '$tgl_akhir'
          ORDER BY p.tgl_jual DESC, p.id_jual DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Kasir</title>
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
                <a href="penjualan_index.php"><i class="fas fa-history"></i> Riwayat</a>
                <a href="kasir_laporan.php" class="active"><i class="fas fa-file-alt"></i> Laporan</a>
                <a href="logout.php" style="background: rgba(231, 76, 60, 0.2); padding: 5px 15px; border-radius: 15px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2><i class="fas fa-file-alt"></i> Laporan Penjualan</h2>
            <a href="index.php" class="button" style="background: #95a5a6;">Kembali</a>
        </div>

        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <form method="GET" action="" style="display: flex; gap: 15px; align-items: flex-end;">
                <div>
                    <label>Dari Tanggal</label>
                    <input type="date" name="tgl_awal" value="<?= $tgl_awal ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <label>Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <button type="submit" class="button" style="background: #3498db; border: none; cursor: pointer;"><i class="fas fa-filter"></i> Filter</button>
                <a href="cetak_laporan_gabug.php?tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>" target="_blank" class="button" style="background: #2ecc71; text-decoration: none;"><i class="fas fa-print"></i> Cetak Laporan</a>
            </form>
        </div>
        
        <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f8f9fc; color: #4e73df; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">No</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">Tanggal</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">Barang</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">Harga</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">Qty</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">Total</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">Kasir</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e3e6f0;">Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                $grand_total = 0;
                while ($row = mysqli_fetch_assoc($result)): 
                    $grand_total += $row['total_harga'];
                ?>
                <tr style="border-bottom: 1px solid #e3e6f0;">
                    <td style="padding: 12px;"><?php echo $no++; ?></td>
                    <td style="padding: 12px;"><?php echo date('d-m-Y', strtotime($row['tgl_jual'])); ?></td>
                    <td style="padding: 12px; font-weight: bold;"><?php echo $row['nama_barang']; ?></td>
                    <td style="padding: 12px;">Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                    <td style="padding: 12px;"><?php echo $row['jumlah']; ?></td>
                    <td style="padding: 12px;">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td style="padding: 12px;"><?php echo $row['user_nama']; ?></td>
                    <td style="padding: 12px;">
                        <a href="cetak_nota.php?id=<?= $row['id_jual'] ?>" target="_blank" style="color: #4e73df; text-decoration: none; border: 1px solid #4e73df; padding: 4px 8px; border-radius: 4px; font-size: 0.8em;"><i class="fas fa-print"></i> Nota</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if(mysqli_num_rows($result) == 0): ?>
                    <tr><td colspan="8" style="padding: 20px; text-align: center;">Tidak ada data pada periode ini</td></tr>
                <?php else: ?>
                    <tr style="background: #f8f9fc; font-weight: bold;">
                        <td colspan="5" style="padding: 12px; text-align: right;">TOTAL PENDAPATAN</td>
                        <td colspan="3" style="padding: 12px; color: #2ecc71;">Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
