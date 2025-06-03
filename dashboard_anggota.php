<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'anggota') {
    header("Location: login.php");
    exit();
}

$id_anggota = (int)$_SESSION['id_account'];

// Cek apakah ada keyword pencarian
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

// Query daftar buku berdasarkan keyword
if ($keyword) {
    $query = "SELECT * FROM t_buku WHERE judul LIKE '%$keyword%' ORDER BY judul ASC";
} else {
    $query = "SELECT * FROM t_buku ORDER BY judul ASC";
}

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query buku gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>KIA SmartLib - Daftar Buku</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fde4ec;
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 30px;
        }

        header {
            padding: 0;
            background: none;
            box-shadow: none;
        }

        .top-bar {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 30px;
        }

        .branding {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .branding-logo {
            width: 20px;
            height: 40px;
            background: #00bcd4;
            clip-path: polygon(0 0, 100% 0, 100% 50%, 50% 100%, 0 50%);
        }

        .branding-text {
            font-weight: 600;
            font-size: 14px;
            color: #111;
        }

        nav.top-nav {
            display: flex;
            gap: 30px;
            font-size: 14px;
            font-weight: 500;
        }

        nav.top-nav a {
            color: #555;
            text-decoration: none;
            padding: 6px 18px;
            border-radius: 20px;
            transition: 0.3s ease;
        }

        nav.top-nav a.active,
        nav.top-nav a:hover {
            background-color: #ffb399;
            color: white;
        }

        section.hero-landing {
            max-width: 1200px;
            margin: 40px auto 30px;
            display: flex;
            align-items: center;
            gap: 40px;
            padding: 0 30px;
        }

        .hero-text-landing {
            max-width: 450px;
        }

        .hero-text-landing h1 {
            font-size: 48px;
            font-weight: 700;
            color: #2a2a72;
            margin: 0;
        }

        .hero-text-landing h1 span {
            color: #f8a91b;
        }

        .hero-text-landing p {
            color: #555;
            font-size: 14px;
            margin: 20px 0 30px;
            line-height: 1.5;
        }

        .hero-img-landing {
            flex: 1;
        }

        .hero-img-landing img {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
            max-height: 300px;
        }

        h2 {
            text-align: center;
            color: #880e4f;
            margin-bottom: 20px;
        }

        /* Search form */
        form.search-form {
            max-width: 600px;
            margin: 0 auto 30px;
            display: flex;
        }

        form.search-form input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 6px 0 0 6px;
        }

        form.search-form button {
            padding: 10px 20px;
            background-color: #e91e63;
            color: white;
            border: none;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
        }

        .book-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .book-card:hover {
            transform: translateY(-5px);
        }

        .book-cover {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .book-info {
            padding: 15px;
        }

        .book-info h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #880e4f;
        }

        .book-info p {
            margin: 0;
            font-size: 14px;
            color: #6a1b4d;
        }

        .book-info a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #f06292;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.3s ease;
        }

        .book-info a:hover {
            background-color: #e91e63;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <header>
        <div class="top-bar">
            <div class="branding">
                <div class="branding-logo"></div>
                <span class="branding-text">KIA SmartLib</span>
            </div>
            <nav class="top-nav">
                <a href="dashboard_anggota.php" class="active">Beranda</a>
                <a href="pengembalian.php">Pengembalian buku</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php" style="color:#e91e63; font-weight:bold;">Logout</a>
            </nav>
        </div>
    </header>

    <section class="hero-landing">
        <div class="hero-text-landing">
            <h1>Selamat <span>Datang</span></h1>
            <p>Jelajahi koleksi buku digital kami, temukan bacaan favoritmu, dan pinjam buku favoritmu dengan mudah melalui KIA SmartLib</p>
        </div>
        <div class="hero-img-landing">
            <img src="img/dashboard.png" alt="Hero Image" />
        </div>
    </section>

    <!-- Form pencarian -->
    <form class="search-form" method="GET">
        <input type="text" name="cari" placeholder="Cari judul buku..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">🔍 Cari</button>
    </form>

    <h2>Daftar Buku</h2>

    <div class="book-container">
        <?php while($buku = mysqli_fetch_assoc($result)): ?>
        <div class="book-card">
            <img class="book-cover" src="uploads/<?= htmlspecialchars($buku['gambar']) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
            <div class="book-info">
                <h3><?= htmlspecialchars($buku['judul']) ?></h3>
                <p><strong>Penulis:</strong> <?= htmlspecialchars($buku['penulis']) ?></p>
                <p><strong>Tahun:</strong> <?= htmlspecialchars($buku['tahun_terbit']) ?></p>
                <a href="detail_buku.php?id=<?= $buku['id_buku'] ?>">Lihat Detail</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
