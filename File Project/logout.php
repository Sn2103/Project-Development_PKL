<?php
// Mulai sesi
session_start();

// Menghapus semua session
session_unset();

// Menghancurkan sesi
session_destroy();

// Arahkan pengguna kembali ke halaman login
header("Location: login.php");
exit;
?>
