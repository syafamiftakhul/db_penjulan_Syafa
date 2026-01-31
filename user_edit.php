<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] != 1) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM user WHERE user_id = $id";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $nama = $_POST['user_nama'];
    $username = $_POST['username'];
    $status = $_POST['user_status'];
    
    // Only update password if filled
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $query_update = "UPDATE user SET user_nama='$nama', username='$username', password='$password', user_status='$status' WHERE user_id=$id";
    } else {
        $query_update = "UPDATE user SET user_nama='$nama', username='$username', user_status='$status' WHERE user_id=$id";
    }

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: user_index.php");
    } else {
        echo "Error: " . $query_update . "<br>" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User - Aplikasi Penjualan</title>
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
        <h2>Edit User</h2>
        <form action="" method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="user_nama" value="<?php echo $row['user_nama']; ?>" required>

            <label>Username</label>
            <input type="text" name="username" value="<?php echo $row['username']; ?>" required>

            <label>Password (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password">

            <label>Status</label>
            <select name="user_status">
                <option value="1" <?php if($row['user_status'] == 1) echo 'selected'; ?>>Admin</option>
                <option value="2" <?php if($row['user_status'] == 2) echo 'selected'; ?>>Kasir</option>
            </select>

            <button type="submit" name="update">Update</button>
            <br><br>
            <a href="user_index.php">Kembali</a>
        </form>
    </div>
</body>
</html>
