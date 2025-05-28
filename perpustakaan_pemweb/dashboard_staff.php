<?php
session_start();
include "koneksi.php";

// Hanya staff yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: login.php");
    exit();
}

// Ambil semua data buku
$resultBuku = mysqli_query($conn, "SELECT * FROM t_buku");

// Ambil riwayat peminjaman
$resultPeminjaman = mysqli_query($conn, "
    SELECT p.*, a.nama, b.judul
    FROM t_peminjaman p
    JOIN t_anggota a ON p.id_anggota = a.id_anggota
    JOIN t_buku b ON p.id_buku = b.id_buku
    ORDER BY p.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff - KIA SmartLib</title>
    <style>
        * {
            font-family: 'Segoe UI', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f9f9fb;
        }

        .navbar {
            background-color: #e91e63;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 22px;
        }

        .navbar .user-info {
            font-size: 14px;
        }

        .container {
            padding: 30px;
        }

        .top-action {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a.action-link {
            margin-right: 10px;
            color: #e91e63;
            text-decoration: none;
            font-weight: bold;
        }

        a.action-link:hover {
            text-decoration: underline;
        }

        h2 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>KIA SmartLib</h1>
    <div class="user-info">
        Hai, <strong><?= $_SESSION['username'] ?></strong> |
        <a href="logout.php" style="color: white; text-decoration: underline;">Logout</a>
    </div>
</div>

<div class="container">
    <div class="top-action">
        <h2>Data Buku</h2>
        <a class="btn" href="tambah_buku.php">➕ Tambah Buku</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($resultBuku)): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['penulis']) ?></td>
                <td><?= htmlspecialchars($row['penerbit']) ?></td>
                <td><?= htmlspecialchars($row['tahun_terbit']) ?></td>
                <td><?= htmlspecialchars($row['stok']) ?></td>
                <td>
                    <a class="action-link" href="edit_buku.php?id=<?= $row['id_buku'] ?>">✏️ Edit</a>
                    <a class="action-link" href="hapus_buku.php?id=<?= $row['id_buku'] ?>" onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️ Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Riwayat Peminjaman Buku</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($resultPeminjaman)): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_pinjam']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_kembali']) ?></td>
                <td><?= htmlspecialchars($row['status_peminjaman']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
