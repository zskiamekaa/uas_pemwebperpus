<?php
session_start();
include "koneksi.php";

// Cek apakah user anggota
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'anggota') {
    header("Location: login.php");
    exit();
}

$id_buku = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT * FROM t_buku WHERE id_buku = $id_buku";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Buku tidak ditemukan.";
    exit();
}

$buku = mysqli_fetch_assoc($result);
$tanggal_pinjam = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku - KIA SmartLib</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fde4ec;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .book-detail {
            display: flex;
            gap: 30px;
        }
        .book-detail img {
            width: 250px;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
        }
        .info {
            flex: 1;
        }
        .info h2 {
            margin-top: 0;
            color: #880e4f;
        }
        .info p {
            font-size: 14px;
            color: #555;
        }
        .pinjam-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f06292;
            color: #fff;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .pinjam-btn:hover {
            background-color: #e91e63;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="book-detail">
            <img src="uploads/<?= htmlspecialchars($buku['gambar']) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
            <div class="info">
                <h2><?= htmlspecialchars($buku['judul']) ?></h2>
                <p><strong>Penulis:</strong> <?= htmlspecialchars($buku['penulis']) ?></p>
                <p><strong>Penerbit:</strong> <?= htmlspecialchars($buku['penerbit']) ?></p>
                <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($buku['tahun_terbit']) ?></p>
                <p><strong>Stok:</strong> <?= htmlspecialchars($buku['stok']) ?></p>
                
                <?php if ($buku['stok'] > 0): ?>
                    <form method="post" action="pinjam_buku.php" style="margin-top: 20px;">
                        <input type="hidden" name="id_buku" value="<?= $buku['id_buku'] ?>">
                        <input type="hidden" name="tanggal_pinjam" value="<?= $tanggal_pinjam ?>">
                        <button type="submit" class="pinjam-btn">Pinjam Buku</button>
                        <a class="pinjam-btn" href="dashboard_anggota.php" style="background-color: #ccc; color: #333; text-align:center; line-height: 38px;">Kembali</a>
                    </form>
                <?php else: ?>
                    <p style="color: red; font-weight: bold;">Stok buku habis.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
