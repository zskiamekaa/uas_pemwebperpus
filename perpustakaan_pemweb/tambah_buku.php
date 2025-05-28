<?php
session_start();
include "koneksi.php";

// Hanya staff yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO t_buku (judul, penulis, penerbit, tahun_terbit, stok)
              VALUES ('$judul', '$penulis', '$penerbit', '$tahun', '$stok')";
    mysqli_query($conn, $query);
    header("Location: dashboard_staff.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
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

        .btn-kembali {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #6c757d;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-kembali:hover {
            background-color: #5a6268;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            color: #444;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>KIA SmartLib</h2>
    <a href="dashboard_staff.php">📚 Kelola Buku</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <h2>Tambah Buku Baru</h2>

    <a href="dashboard_staff.php" class="btn-kembali">🔙 Kembali</a>

    <form method="POST">
        <label>Judul Buku</label>
        <input type="text" name="judul" required>

        <label>Penulis</label>
        <input type="text" name="penulis" required>

        <label>Penerbit</label>
        <input type="text" name="penerbit" required>

        <label>Tahun Terbit</label>
        <input type="number" name="tahun" required>

        <label>Stok Buku</label>
        <input type="number" name="stok" required>

        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>
