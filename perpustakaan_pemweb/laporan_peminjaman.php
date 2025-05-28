<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f9f9fb;
        }

        .sidebar {
            width: 220px;
            background-color: #e91e63;
            color: white;
            padding: 20px;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 15px 0;
            padding: 8px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .main {
            flex: 1;
            padding: 30px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
        }

        th {
            background-color: #e91e63;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .logout {
            color: #e91e63;
            font-weight: bold;
            text-decoration: none;
        }

        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Apa yang mau kamu Kelola?</h2>
    <a href="dashboard_admin.php">📊 Dashboard</a>
    <a href="manajemen_akun.php">👤 Kelola Akun</a>
    <a href="laporan_peminjaman.php">📁 Riwayat Peminjaman</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="topbar">
        <h2>Riwayat Peminjaman Buku</h2>
        <div>
            Halo, <strong><?= $_SESSION['username'] ?></strong> |
            <a class="logout" href="logout.php">Logout</a>
        </div>
    </div>

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
            <?php
            $query = "SELECT p.*, a.nama, b.judul
                      FROM t_peminjaman p
                      JOIN t_anggota a ON p.id_anggota = a.id_anggota
                      JOIN t_buku b ON p.id_buku = b.id_buku
                      ORDER BY p.tanggal_pinjam DESC";

            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query error: " . mysqli_error($conn));
            }

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['nama']}</td>
                        <td>{$row['judul']}</td>
                        <td>{$row['tanggal_pinjam']}</td>
                        <td>{$row['tanggal_kembali']}</td>
                        <td>{$row['status_peminjaman']}</td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
