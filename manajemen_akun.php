<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Proses Delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $del = mysqli_query($conn, "DELETE FROM t_anggota WHERE id_anggota = $id");
    if ($del) {
        header("Location: manajemen_akun.php?msg=hapus_sukses");
        exit();
    } else {
        echo "Error menghapus data.";
    }
}

// Ambil data anggota
$query = "SELECT * FROM t_anggota ORDER BY id_anggota DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Akun</title>
    <style>
        /* Styling keseluruhan */
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
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 14px;
            background-color: #555;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        th, td {
            padding: 14px 16px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #e91e63;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        a.button {
            padding: 8px 14px;
            background-color: #e91e63;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin-right: 6px;
            display: inline-block;
        }
        a.button:hover {
            background-color: #c2185b;
        }
        a.button.delete {
            background-color: #dc3545;
        }
        a.button.delete:hover {
            background-color: #b71c1c;
        }
        /* Notifikasi */
        .notif-sukses {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
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
        <a href="dashboard_admin.php" class="btn-back">← Kembali ke Dashboard</a>

        <h1>Manajemen Akun Anggota</h1>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'hapus_sukses') : ?>
            <div class="notif-sukses">
                Berhasil menghapus akun.
            </div>
        <?php endif; ?>

        <a href="tambah_akun.php" class="button">+ Tambah Akun Baru</a>
        <table>
            <thead>
                <tr>
                    <th>ID Anggota</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Telp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_anggota']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['telepon']) ?></td>
                    <td>
                        <a href="edit_akun.php?id=<?= $row['id_anggota'] ?>" class="button">Edit</a>
                        <a href="hapus_akun.php?id=<?= $row['id_anggota'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="button delete">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
