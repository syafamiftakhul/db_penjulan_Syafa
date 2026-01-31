<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['user_status'] == 1) {
    header("Location: admin_index.php");
} else {
    header("Location: kasir_index.php");
}
exit;
?>
