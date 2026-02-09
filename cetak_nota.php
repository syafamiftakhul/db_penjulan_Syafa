<?php
include 'koneksi.php';

if(!isset($_GET['id'])){
    echo "ID tidak ditemukan";
    exit;
}

$id_jual = $_GET['id'];
$query = "SELECT p.*, b.nama_barang, b.harga_jual, u.user_nama 
          FROM penjualan p 
          JOIN barang b ON p.id_barang = b.id_barang 
          JOIN user u ON p.user_id = u.user_id 
          WHERE p.id_jual = '$id_jual'";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

if(!$row){
    echo "Data tidak ditemukan";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan #<?= $id_jual ?></title>
    <style>
        body { font-family: monospace; }
        .nota { width: 300px; margin: 20px auto; border: 1px solid #ccc; padding: 10px; }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .item { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .total { border-top: 1px dashed #000; padding-top: 5px; margin-top: 10px; font-weight: bold; }
        @media print {
            .no-print { display: none; }
            .nota { border: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="no-print" onclick="window.print()" style="position: fixed; top: 10px; right: 10px;">Cetak</button>
    <div class="nota">
        <div class="header">
            <h3>Toko RPL Skanega</h3>
            <p>Jl. Contoh No. 123</p>
            <p>Nota: #<?= $id_jual ?><br>
               <?= date('d/m/Y H:i', strtotime($row['tgl_jual'])) ?></p>
            <p>Kasir: <?= $row['user_nama'] ?></p>
        </div>
        
        <div class="item">
            <span><?= $row['nama_barang'] ?></span>
        </div>
        <div class="item">
            <span><?= $row['jumlah'] ?> x <?= number_format($row['harga_jual']) ?></span>
            <span><?= number_format($row['total_harga']) ?></span>
        </div>

        <div class="item total">
            <span>TOTAL</span>
            <span>Rp <?= number_format($row['total_harga']) ?></span>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <p>Terima Kasih</p>
        </div>
    </div>
</body>
</html>
