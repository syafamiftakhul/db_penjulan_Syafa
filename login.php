<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_nama'] = $row['user_nama'];
        $_SESSION['user_status'] = $row['user_status'];

        if ($row['user_status'] == 1) {
            header("Location: admin_index.php");
        } else if ($row['user_status'] == 2) {
            header("Location: kasir_index.php");
        }
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h2>Welcome Back!</h2>
        <p style="color: #666; margin-bottom: 20px;">Silakan login ke akun Anda</p>
        
        <?php if ($error): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="" method="POST" style="padding: 0; box-shadow: none;">
            <div style="text-align: left;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username..." required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password..." required>
            </div>
            
            <button type="submit" name="login">LOGIN</button>
        </form>
    </div>
</body>
</html>
