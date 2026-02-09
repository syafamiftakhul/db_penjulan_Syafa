<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_status'] != 1 && $_SESSION['user_status'] != 2)) {
    echo "Akses ditolak";
    exit;
}

$tgl_awal = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

$query = "SELECT p.*, b.nama_barang, b.harga_jual, u.user_nama 
          FROM penjualan p 
          JOIN barang b ON p.id_barang = b.id_barang 
          JOIN user u ON p.user_id = u.user_id 
          WHERE p.tgl_jual BETWEEN '$tgl_awal' AND '$tgl_akhir'
          ORDER BY p.tgl_jual ASC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Per Tanggal</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #eee; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>LAPORAN PENJUALAN</h2>
        <p>Periode: <?= date('d-m-Y', strtotime($tgl_awal)) ?> s/d <?= date('d-m-Y', strtotime($tgl_akhir)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $grand_total = 0;
            while($row = mysqli_fetch_assoc($result)): 
                $grand_total += $row['total_harga'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl_jual'])) ?></td>
                <td><?= $row['nama_barang'] ?></td>
                <td>Rp <?= number_format($row['harga_jual']) ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp <?= number_format($row['total_harga']) ?></td>
                <td><?= $row['user_nama'] ?></td>
            </tr>
            <?php endwhile; ?>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL PENDAPATAN</td>
                <td colspan="2" style="font-weight: bold;">Rp <?= number_format($grand_total) ?></td>
            </tr>
        </tbody>
    </table>

    <div style="float: right; margin-right: 50px; text-align: center;">
        <p>Mengetahui,<br>Pimpinan</p>
        <br><br><br>
        <p>(.........................)</p>
    </div>
</body>
</html>
