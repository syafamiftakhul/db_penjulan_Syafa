<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 1) {
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM user";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User - Aplikasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="index.php" class="brand"><i class="fas fa-store"></i> Admin Panel</a>
            <div>
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="user_index.php" class="active"><i class="fas fa-users"></i> User</a>
                <a href="barang_index.php"><i class="fas fa-box"></i> Barang</a>
                <a href="logout.php" style="background: rgba(231, 76, 60, 0.2); padding: 5px 15px; border-radius: 15px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Data User</h2>
        <a href="user_tambah.php" class="button" style="background: #2ecc71; color: white; padding: 10px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px;"><i class="fas fa-plus"></i> Tambah User</a>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['user_nama']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo ($row['user_status'] == 1) ? 'Admin' : 'Kasir'; ?></td>
                    <td>
                        <a href="user_edit.php?id=<?php echo $row['user_id']; ?>" style="color: orange;"><i class="fas fa-edit"></i> Edit</a> | 
                        <a href="user_hapus.php?id=<?php echo $row['user_id']; ?>" style="color: red;" onclick="return confirm('Yakin ingin menghapus user ini?')"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
