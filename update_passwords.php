<?php
include 'koneksi.php';

$pass_admin = md5('admin1');
$pass_kasir = md5('kasir1');

$query1 = "UPDATE user SET password = '$pass_admin' WHERE username = 'admin'";
$query2 = "UPDATE user SET password = '$pass_kasir' WHERE username = 'kasir'";

if (mysqli_query($koneksi, $query1) && mysqli_query($koneksi, $query2)) {
    echo "Passwords updated successfully.";
} else {
    echo "Error updating passwords: " . mysqli_error($koneksi);
}
?>
