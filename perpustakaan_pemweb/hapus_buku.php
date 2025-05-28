<?php
session_start();
include "koneksi.php";

// Hanya staff yang boleh menghapus buku
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: login.php");
    exit();
}

// Validasi ID buku
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM t_buku WHERE id_buku = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Redirect ke dashboard_staff setelah hapus
header("Location: dashboard_staff.php");
exit();
?>
