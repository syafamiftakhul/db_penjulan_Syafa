<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize Cart
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Handle Add to Cart
if (isset($_POST['tambah'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];

    // Get Item Details
    $query = "SELECT * FROM barang WHERE id_barang = '$id_barang'";
    $result = mysqli_query($koneksi, $query);
    $barang = mysqli_fetch_assoc($result);

    if ($barang) {
        // Check Stock
        $stok_tersedia = $barang['stok'];
        
        // Check if item already in cart, check cumulative quantity
        $current_qty_in_cart = 0;
        foreach ($_SESSION['keranjang'] as $item) {
            if ($item['id_barang'] == $id_barang) {
                $current_qty_in_cart += $item['jumlah'];
            }
        }

        if (($current_qty_in_cart + $jumlah) > $stok_tersedia) {
            $error = "Stok tidak mencukupi! Sisa stok: " . $stok_tersedia . ". Di keranjang: " . $current_qty_in_cart;
        } else {
            $subtotal = $barang['harga_jual'] * $jumlah;
            
            $_SESSION['keranjang'][] = [
                'id_barang' => $id_barang,
                'nama_barang' => $barang['nama_barang'],
                'harga_jual' => $barang['harga_jual'],
                'jumlah' => $jumlah,
                'subtotal' => $subtotal
            ];
        }
    }
}

// Handle Remove Item
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    unset($_SESSION['keranjang'][$index]);
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Re-index
    header("Location: penjualan_form.php");
    exit;
}

// Get Data Barang for Dropdown
$query_barang = "SELECT * FROM barang WHERE stok > 0";
$result_barang = mysqli_query($koneksi, $query_barang);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Penjualan - Aplikasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        function hitungTotal() {
            var select = document.getElementById("id_barang");
            var harga = select.options[select.selectedIndex].getAttribute('data-harga');
            var stok = select.options[select.selectedIndex].getAttribute('data-stok');
            var jumlah = document.getElementById("jumlah").value;
            
            if (stok && parseInt(jumlah) > parseInt(stok)) {
                alert("Stok tidak mencukupi! Stok tersedia: " + stok);
                document.getElementById("jumlah").value = stok;
                jumlah = stok;
            }

            if (harga && jumlah) {
                var total = harga * jumlah;
                document.getElementById("estimasi_total").value = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total);
            }
        }
    </script>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="index.php" class="brand"><i class="fas fa-cash-register"></i> Kasir Panel</a>
            <div>
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="penjualan_form.php" class="active"><i class="fas fa-calculator"></i> Transaksi</a>
                <a href="penjualan_index.php"><i class="fas fa-history"></i> Riwayat</a>
                <a href="logout.php" style="background: rgba(231, 76, 60, 0.2); padding: 5px 15px; border-radius: 15px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Transaksi Penjualan</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Form Add to Cart -->
        <form action="" method="POST" style="margin-bottom: 30px;">
            <h3 style="margin-top: 0;">Tambah Item</h3>
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px;">
                <div>
                    <label>Pilih Barang</label>
                    <select name="id_barang" id="id_barang" onchange="hitungTotal()" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php while ($row = mysqli_fetch_assoc($result_barang)): ?>
                            <option value="<?php echo $row['id_barang']; ?>" 
                                    data-harga="<?php echo $row['harga_jual']; ?>"
                                    data-stok="<?php echo $row['stok']; ?>">
                                <?php echo $row['nama_barang']; ?> (Stok: <?php echo $row['stok']; ?>) - Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" min="1" value="1" onkeyup="hitungTotal()" onchange="hitungTotal()" required>
                </div>
                <div>
                    <label>Estimasi Subtotal</label>
                    <input type="text" id="estimasi_total" readonly placeholder="Rp 0">
                </div>
            </div>
            
            <button type="submit" name="tambah" class="button" style="background: #f6c23e; color: #fff;">+ Masukkan Keranjang</button>
        </form>

        <!-- Cart Table -->
        <h3>Keranjang Belanja</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $grand_total = 0;
                if (empty($_SESSION['keranjang'])) {
                    echo "<tr><td colspan='6' style='text-align:center;'>Keranjang kosong</td></tr>";
                } else {
                    foreach ($_SESSION['keranjang'] as $key => $item): 
                        $grand_total += $item['subtotal'];
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $item['nama_barang']; ?></td>
                        <td>Rp <?php echo number_format($item['harga_jual'], 0, ',', '.'); ?></td>
                        <td><?php echo $item['jumlah']; ?></td>
                        <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        <td><a href="?hapus=<?php echo $key; ?>" style="color: red;">Hapus</a></td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Grand Total</td>
                    <td colspan="2" style="font-weight: bold; font-size: 1.1em; color: var(--primary-color);">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>
        
        <!-- Checkout Form -->
        <?php if (!empty($_SESSION['keranjang'])): ?>
            <form action="penjualan_simpan.php" method="POST" style="margin-top: 20px; box-shadow: none; padding: 0;">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tgl_jual" value="<?php echo date('Y-m-d'); ?>" required style="max-width: 200px;">
                
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                
                <div style="text-align: right; margin-top: 15px;">
                    <a href="penjualan_index.php" style="color: #666; margin-right: 15px; font-weight: 500;">Batal</a>
                    <button type="submit" name="checkout" class="button" style="width: auto; padding: 12px 30px;">PROSES PEMBAYARAN</button>
                </div>
            </form>
        <?php endif; ?>

    </div>
</body>
</html>
