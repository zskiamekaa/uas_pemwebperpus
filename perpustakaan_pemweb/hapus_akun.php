<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $del = mysqli_query($conn, "DELETE FROM t_anggota WHERE id_anggota = $id");

    if ($del) {
        header("Location: manajemen_akun.php?msg=hapus_sukses");
        exit();
    } else {
        // Tampilkan error mysqli
        echo "Error menghapus data: " . mysqli_error($conn);
    }
} else {
    header("Location: manajemen_akun.php");
    exit();
}
