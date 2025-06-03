<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fungsi helper untuk eksekusi query dan cek error
function runQuery($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }
    return $result;
}

// Jumlah data metrik
$jumlah_akun = mysqli_fetch_assoc(runQuery($conn, "SELECT COUNT(*) AS total FROM t_anggota"))['total'];
$jumlah_buku = mysqli_fetch_assoc(runQuery($conn, "SELECT COUNT(*) AS total FROM t_buku"))['total'];
$jumlah_peminjaman = mysqli_fetch_assoc(runQuery($conn, "SELECT COUNT(*) AS total FROM t_peminjaman"))['total'];

// Data buku paling sering dipinjam (top 5)
$query_peminjaman = "
    SELECT b.judul, COUNT(p.id_pinjam) AS total_pinjam
    FROM t_peminjaman p
    JOIN t_buku b ON p.id_buku = b.id_buku
    GROUP BY b.id_buku
    ORDER BY total_pinjam DESC
    LIMIT 5
";
$result_peminjaman = mysqli_query($conn, $query_peminjaman);

$buku_pinjam_labels = [];
$buku_pinjam_data = [];
while ($row = mysqli_fetch_assoc($result_peminjaman)) {
    $buku_pinjam_labels[] = $row['judul'];
    $buku_pinjam_data[] = (int)$row['total_pinjam'];
}

// Data buku dengan stok paling banyak (top 5)
$query_stok_banyak = "
    SELECT judul, stok
    FROM t_buku
    ORDER BY stok DESC
    LIMIT 5
";
$result_stok_banyak = runQuery($conn, $query_stok_banyak);

$buku_stok_banyak_labels = [];
$buku_stok_banyak_data = [];
while ($row = mysqli_fetch_assoc($result_stok_banyak)) {
    $buku_stok_banyak_labels[] = $row['judul'];
    $buku_stok_banyak_data[] = (int)$row['stok'];
}

// Data buku dengan stok paling sedikit (top 5)
$query_stok_sedikit = "
    SELECT judul, stok
    FROM t_buku
    ORDER BY stok ASC
    LIMIT 5
";
$result_stok_sedikit = runQuery($conn, $query_stok_sedikit);

$buku_stok_sedikit_labels = [];
$buku_stok_sedikit_data = [];
while ($row = mysqli_fetch_assoc($result_stok_sedikit)) {
    $buku_stok_sedikit_labels[] = $row['judul'];
    $buku_stok_sedikit_data[] = (int)$row['stok'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        * {
            margin: 0; padding: 0;
            font-family: 'Segoe UI', sans-serif;
            box-sizing: border-box;
        }
        body {
            display: flex;
            height: 100vh;
            background-color: #f9f9fb;
        }
        .sidebar {
            width: 220px;
            background-color: #e91e63;
            color: white;
            padding: 20px;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            text-align: center;
            font-size: 22px;
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
            overflow-y: auto;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar h1 {
            font-size: 24px;
            color: #333;
        }
        .card-container {
            display: flex;
            margin-top: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }
        .card {
            flex: 1;
            min-width: 200px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card h2 {
            font-size: 36px;
            color: #e91e63;
        }
        .card p {
            margin-top: 10px;
            color: #666;
            font-weight: bold;
        }
        .welcome-box {
            margin-top: 40px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .welcome-box ul {
            margin-top: 10px;
            padding-left: 20px;
            color: #444;
        }
        .user-box {
            text-align: right;
            font-size: 14px;
            color: #444;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logout {
            color: #e91e63;
            font-weight: bold;
            text-decoration: none;
        }
        .logout:hover {
            text-decoration: underline;
        }
        .charts-container {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(300px,1fr));
            gap: 30px;
        }
        .chart-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .chart-box h3 {
            margin-bottom: 20px;
            color: #e91e63;
            text-align: center;
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
        <h1>Dashboard Admin</h1>
        <div class="user-box">
            Halo, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
            <a class="logout" href="logout.php">Log out</a>
        </div>
    </div>

    <div class="card-container">
        <div class="card">
            <h2><?= $jumlah_akun ?></h2>
            <p>Akun Terdaftar</p>
        </div>
        <div class="card">
            <h2><?= $jumlah_buku ?></h2>
            <p>Total Buku</p>
        </div>
        <div class="card">
            <h2><?= $jumlah_peminjaman ?></h2>
            <p>Peminjaman</p>
        </div>
    </div>

    <div class="welcome-box">
        <h2>Selamat Datang Admin 👋</h2>
        <p>Anda dapat:</p>
        <ul>
            <li>Melihat dan mengelola seluruh aktivitas peminjaman</li>
            <li>Mengelola akun pengguna: aktif/nonaktifkan akun anggota</li>
            <li>Melihat laporan dan status buku yang sedang dipinjam</li>
        </ul>
    </div>

    <div class="charts-container">
        <div class="chart-box">
            <h3>Buku Paling Sering Dipinjam</h3>
            <canvas id="chartPeminjaman"></canvas>
        </div>
        <div class="chart-box">
            <h3>Buku dengan Stok Terbanyak</h3>
            <canvas id="chartStokBanyak"></canvas>
        </div>
        <div class="chart-box">
            <h3>Buku dengan Stok Tersedikit</h3>
            <canvas id="chartStokSedikit"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Data dari PHP
const bukuPinjamLabels = <?= json_encode($buku_pinjam_labels) ?>;
const bukuPinjamData = <?= json_encode($buku_pinjam_data) ?>;

const bukuStokBanyakLabels = <?= json_encode($buku_stok_banyak_labels) ?>;
const bukuStokBanyakData = <?= json_encode($buku_stok_banyak_data) ?>;

const bukuStokSedikitLabels = <?= json_encode($buku_stok_sedikit_labels) ?>;
const bukuStokSedikitData = <?= json_encode($buku_stok_sedikit_data) ?>;

// Fungsi buat chart bar
function createBarChart(ctx, labels, data, label, color) {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: color,
                borderRadius: 5,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

// Inisialisasi chart
createBarChart(document.getElementById('chartPeminjaman').getContext('2d'), bukuPinjamLabels, bukuPinjamData, 'Jumlah Pinjam', '#e91e63');
createBarChart(document.getElementById('chartStokBanyak').getContext('2d'), bukuStokBanyakLabels, bukuStokBanyakData, 'Stok', '#28a745');
createBarChart(document.getElementById('chartStokSedikit').getContext('2d'), bukuStokSedikitLabels, bukuStokSedikitData, 'Stok', '#dc3545');
</script>

</body>
</html>
