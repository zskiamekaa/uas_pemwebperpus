<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'anggota') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = (int)$_SESSION['id_account'];
    $create_by = $id_anggota;

    $id_buku = isset($_POST['id_buku']) ? (int)$_POST['id_buku'] : 0;
    $tanggal_pinjam = date('Y-m-d');
    $tanggal_batas = date('Y-m-d', strtotime('+7 days'));
    $status_peminjaman = 'dipinjam';
    $create_date = date('Y-m-d H:i:s');

    // Cek stok buku
    $q_stok = mysqli_query($conn, "SELECT stok FROM t_buku WHERE id_buku = $id_buku");
    if (!$q_stok || mysqli_num_rows($q_stok) === 0) {
        echo "<script>alert('Buku tidak ditemukan.'); window.history.back();</script>";
        exit();
    }

    $buku = mysqli_fetch_assoc($q_stok);
    if ($buku['stok'] < 1) {
        echo "<script>alert('Stok buku habis. Tidak bisa dipinjam.'); window.history.back();</script>";
        exit();
    }

    // Simpan data peminjaman
    $query_pinjam = "INSERT INTO t_peminjaman 
        (id_anggota, id_buku, tanggal_pinjam, tanggal_batas, status_peminjaman, create_by, create_date)
        VALUES
        ($id_anggota, $id_buku, '$tanggal_pinjam', '$tanggal_batas', '$status_peminjaman', $create_by, '$create_date')";

    if (mysqli_query($conn, $query_pinjam)) {
        // Kurangi stok buku
        mysqli_query($conn, "UPDATE t_buku SET stok = stok - 1 WHERE id_buku = $id_buku");

        echo "<script>alert('Buku berhasil dipinjam!'); window.location.href='dashboard_anggota.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal meminjam buku: " . mysqli_error($conn) . "'); window.history.back();</script>";
        exit();
    }
} else {
    header("Location: dashboard_anggota.php");
    exit();
}
?>
