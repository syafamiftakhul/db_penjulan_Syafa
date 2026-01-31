<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['checkout']) && !empty($_SESSION['keranjang'])) {
    $tgl_jual = $_POST['tgl_jual'];
    $user_id = $_POST['user_id'];
    
    $success_count = 0;
    $errors = [];

    foreach ($_SESSION['keranjang'] as $item) {
        $id_barang = $item['id_barang'];
        $jumlah = $item['jumlah'];
        $total_harga = $item['subtotal'];

        // Insert to penjualan
        $query_jual = "INSERT INTO penjualan (id_barang, tgl_jual, jumlah, total_harga, user_id) VALUES ('$id_barang', '$tgl_jual', '$jumlah', '$total_harga', '$user_id')";
        
        if (mysqli_query($koneksi, $query_jual)) {
            // Update stock
            $query_stok = "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = $id_barang";
            if(mysqli_query($koneksi, $query_stok)){
                $success_count++;
            } else {
                $errors[] = "Gagal potong stok untuk barang ID $id_barang";
            }
        } else {
            $errors[] = "Gagal simpan penjualan barang ID $id_barang: " . mysqli_error($koneksi);
        }
    }

    if ($success_count > 0) {
        // Clear Cart
        unset($_SESSION['keranjang']);
        $msg = "Transaksi Berhasil Disimpan! ($success_count Item)";
        echo "<script>alert('$msg'); window.location='penjualan_index.php';</script>";
    } else {
        $msg = "Transaksi Gagal: " . implode(", ", $errors);
        echo "<script>alert('$msg'); window.history.back();</script>";
    }

} else {
    // Direct access or empty cart
    header("Location: penjualan_form.php");
}
?>
