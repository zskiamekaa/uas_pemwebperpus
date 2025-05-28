<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'anggota') {
    header("Location: login.php");
    exit();
}

$page = 'pengembalian';
$id_anggota = (int) $_SESSION['id_account'];

$pesan = '';
$pesan_tipe = '';

// Proses pengembalian
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pinjam'])) {
    $id_pinjam = (int) $_POST['id_pinjam'];
    $tgl_kembali = date('Y-m-d');

    $query_buku = "SELECT id_buku, tanggal_batas FROM t_peminjaman 
                   WHERE id_pinjam = $id_pinjam AND id_anggota = $id_anggota AND status_peminjaman = 'dipinjam'";
    $result_buku = mysqli_query($conn, $query_buku);

    if ($result_buku && mysqli_num_rows($result_buku) > 0) {
        $row_buku = mysqli_fetch_assoc($result_buku);
        $id_buku = $row_buku['id_buku'];
        $tanggal_batas = $row_buku['tanggal_batas'];

        $denda = 0;
        if ($tgl_kembali > $tanggal_batas) {
            $selisih = (strtotime($tgl_kembali) - strtotime($tanggal_batas)) / (60 * 60 * 24);
            $denda_per_hari = 1000;
            $denda = $selisih * $denda_per_hari;
        }

        $update = "UPDATE t_peminjaman SET 
            tanggal_kembali = '$tgl_kembali',
            status_peminjaman = 'dikembalikan',
            denda = $denda
            WHERE id_pinjam = $id_pinjam AND id_anggota = $id_anggota AND status_peminjaman = 'dipinjam'";

        if (mysqli_query($conn, $update)) {
            if (mysqli_affected_rows($conn) > 0) {
                mysqli_query($conn, "UPDATE t_buku SET stok = stok + 1 WHERE id_buku = $id_buku");
                $pesan = $denda > 0 
                    ? "Buku berhasil dikembalikan. Anda dikenakan denda sebesar Rp " . number_format($denda, 0, ',', '.') 
                    : "Buku berhasil dikembalikan.";
                $pesan_tipe = 'success';
            } else {
                $pesan = "Data pengembalian tidak ditemukan atau sudah dikembalikan.";
                $pesan_tipe = 'error';
            }
        } else {
            $pesan = "Gagal mengembalikan buku: " . mysqli_error($conn);
            $pesan_tipe = 'error';
        }
    } else {
        $pesan = "Data peminjaman tidak ditemukan.";
        $pesan_tipe = 'error';
    }
}

$query = "SELECT p.id_pinjam, b.judul, p.tanggal_pinjam, p.tanggal_batas, p.tanggal_kembali, p.status_peminjaman, p.denda
          FROM t_peminjaman p
          JOIN t_buku b ON p.id_buku = b.id_buku
          WHERE p.id_anggota = $id_anggota
          ORDER BY p.tanggal_pinjam DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Pengembalian Buku - KIA SmartLib</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fce4ec;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 60px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
            color: #4a2b3c;
        }

        h2 {
            color: #c2185b;
            margin-bottom: 30px;
            font-size: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f8bbd0;
            color: #4a2b3c;
        }

        tr:hover {
            background-color: #fce4ec;
        }

        button.return-btn {
            background-color: #c2185b;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s ease;
        }

        button.return-btn:hover {
            background-color: #ad1457;
        }

        form.back-dashboard {
            text-align: center;
            margin-top: 30px;
        }

        form.back-dashboard button {
            padding: 10px 22px;
            border: none;
            background-color: #c2185b;
            color: white;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        form.back-dashboard button:hover {
            background-color: #ad1457;
        }

        .message {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }
        .success {
            background-color: #c8e6c9;
            color: #256029;
        }
        .error {
            background-color: #ffcdd2;
            color: #c62828;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Pengembalian Buku</h2>

    <?php if ($pesan): ?>
        <div class="message <?= $pesan_tipe ?>"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Batas</th>
                <th>Tanggal Kembali</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal_batas'])) ?></td>
                <td><?= $row['tanggal_kembali'] ? date('d/m/Y', strtotime($row['tanggal_kembali'])) : '-' ?></td>
                <td>
                    <?= $row['status_peminjaman'] == 'dikembalikan' 
                        ? 'Rp ' . number_format($row['denda'], 0, ',', '.') 
                        : '-' ?>
                </td>
                <td>
                    <?php if ($row['status_peminjaman'] == 'dipinjam'): ?>
                        <form method="post" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?');">
                            <input type="hidden" name="id_pinjam" value="<?= $row['id_pinjam'] ?>">
                            <button type="submit" class="return-btn">Kembalikan</button>
                        </form>
                    <?php else: ?>
                        <span style="color: green; font-weight: bold;">Selesai</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Tidak ada riwayat peminjaman.</p>
    <?php endif; ?>

    <form action="dashboard_anggota.php" method="post" class="back-dashboard">
        <button type="submit">Kembali ke Dashboard</button>
    </form>
</div>

</body>
</html>
