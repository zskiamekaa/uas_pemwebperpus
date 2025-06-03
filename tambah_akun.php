<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    if (!$nama || !$email) {
        $error = "Nama dan Email wajib diisi.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO t_anggota (nama, email, telepon, alamat) VALUES ('$nama', '$email', '$telepon', '$alamat')");
        if ($insert) {
            header("Location: manajemen_akun.php");
            exit();
        } else {
            $error = "Gagal menambah akun baru.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Akun Baru</title>
    <style>
        /* Reset dan global */
        * {
            font-family: 'Segoe UI', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f9f9fb;
        }
        /* Sidebar */
        .sidebar {
            width: 240px;
            background-color: #e91e63;
            color: white;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .sidebar h2 {
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #c2185b;
        }
        /* Konten utama */
        .content {
            flex-grow: 1;
            padding: 30px 40px;
            background: #fff;
            box-shadow: inset 0 0 10px #ddd;
            border-radius: 0 10px 10px 0;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            max-width: 500px;
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            resize: vertical;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            outline: none;
            border-color: #e91e63;
        }
        button {
            margin-top: 20px;
            background-color: #e91e63;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #c2185b;
        }
        .error {
            margin-top: 10px;
            color: red;
            font-weight: bold;
        }
        /* Tombol back */
        a.back {
            display: inline-block;
            margin-top: 30px;
            color: #e91e63;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        a.back:hover {
            color: #c2185b;
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

    <div class="content">
        <h1>Tambah Akun Baru</h1>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" required />

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required />

            <label for="telepon">No Telp</label>
            <input type="text" name="telepon" id="telepon" />

            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" rows="4"></textarea>

            <button type="submit">Simpan</button>
        </form>

        <a href="dashboard_admin.php" class="back">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
