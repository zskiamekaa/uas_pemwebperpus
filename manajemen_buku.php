<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: dashboard_admin.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Buku</title>
    <style>
        * {
            font-family: 'Segoe UI', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
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
            margin-bottom: 30px;
            font-size: 22px;
            text-align: center;
        }

        .sidebar a {
            display: block;
            margin: 15px 0;
            color: white;
            text-decoration: none;
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
            color: #333;
        }

        .btn-tambah {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-tambah:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
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

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 5px;
        }

        .btn-edit {
            background-color: #28a745;
        }

        .btn-hapus {
            background-color: #dc3545;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
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
    <a href="manajemen_buku.php">📚 Kelola Buku</a>
    <a href="laporan_peminjaman.php">📁 Riwayat Peminjaman</a>
    <a href="aktivitas.php">📌 Aktivitas</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="topbar">
        <h2>Manajemen Buku</h2>
        <div>
            Halo, <strong><?= $_SESSION['username'] ?></strong> |
            <a class="logout" href="logout.php">Logout</a>
        </div>
    </div>

    <a href="tambah_buku.php" class="btn btn-tambah">+ Tambah Buku</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM t_buku");
        while ($buku = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$buku['id_buku']}</td>
                    <td>{$buku['judul']}</td>
                    <td>{$buku['penulis']}</td>
                    <td>{$buku['penerbit']}</td>
                    <td>{$buku['tahun_terbit']}</td>
                    <td>{$buku['stok']}</td>
                    <td>
                        <a href='edit_buku.php?id={$buku['id_buku']}' class='btn btn-edit'>Edit</a>
                        <a href='hapus_buku.php?id={$buku['id_buku']}' class='btn btn-hapus' onclick='return confirm(\"Hapus buku ini?\")'>Hapus</a>
                    </td>
                </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
