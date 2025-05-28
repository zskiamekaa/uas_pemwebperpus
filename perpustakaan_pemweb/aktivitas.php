<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM t_aktivitas ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Aktivitas Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0; padding: 0;
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
    <h2>Admin Panel</h2>
    <a href="dashboard_admin.php">📊 Dashboard</a>
    <a href="manajemen_akun.php">👤 Kelola Akun</a>
    <a href="manajemen_buku.php">📚 Kelola Buku</a>
    <a href="laporan_peminjaman.php">📁 Riwayat Peminjaman</a>
    <a href="aktivitas.php">📌 Aktivitas</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="topbar">
        <h2>Aktivitas Admin</h2>
        <div>
            Halo, <strong><?= $_SESSION['username'] ?></strong> |
            <a class="logout" href="logout.php">Logout</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Aktivitas</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['username']}</td>
                        <td>{$row['aktivitas']}</td>
                        <td>{$row['tanggal']}</td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
