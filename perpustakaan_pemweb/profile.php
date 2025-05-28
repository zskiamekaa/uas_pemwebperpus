<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'anggota') {
    header("Location: login.php");
    exit();
}

$page = 'profile';

$id_account = (int) $_SESSION['id_account'];

// Ambil data anggota
$query = "SELECT * FROM t_anggota WHERE id_account = $id_account";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query profil gagal: " . mysqli_error($conn));
}
$anggota = mysqli_fetch_assoc($result);
if (!$anggota) {
    die("Data anggota tidak ditemukan.");
}

// Proses form submit update data
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['telepon']);

    // Validasi sederhana
    if (empty($nama)) {
        $error = "Nama tidak boleh kosong.";
    } else {
        // Update data ke database
        $sql_update = "UPDATE t_anggota SET 
            nama = '" . mysqli_real_escape_string($conn, $nama) . "',
            alamat = '" . mysqli_real_escape_string($conn, $alamat) . "',
            telepon = '" . mysqli_real_escape_string($conn, $telepon) . "'
            WHERE id_account = $id_account";

        if (mysqli_query($conn, $sql_update)) {
            $success = "Profil berhasil diperbarui.";
            // Refresh data anggota dari database setelah update
            $result = mysqli_query($conn, $query);
            $anggota = mysqli_fetch_assoc($result);
        } else {
            $error = "Gagal memperbarui profil: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - KIA SmartLib</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fce4ec;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 60px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
            color: #4a2b3c;
        }

        .container h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #c2185b;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #880e4f;
        }

        form input[type="text"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            resize: vertical;
        }

        form textarea {
            min-height: 80px;
        }

        .btn-submit {
            margin-top: 25px;
            padding: 12px 30px;
            background-color: #c2185b;
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #ad1457;
        }

        .message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
        }

        .success {
            background-color: #c8e6c9;
            color: #256029;
        }

        .error {
            background-color: #ffcdd2;
            color: #c62828;
        }

        .logout-button {
            display: block;
            margin: 30px auto 0 auto;
            padding: 10px 22px;
            border: none;
            background-color: #c2185b;
            color: white;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .logout-button:hover {
            background-color: #ad1457;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Profil Anggota</h2>

    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="nama">Nama</label>
        <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($anggota['nama']) ?>" required>

        <label for="email">Email (tidak bisa diubah)</label>
        <input type="text" id="email" value="<?= htmlspecialchars($anggota['email']) ?>" disabled>

        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat"><?= htmlspecialchars($anggota['alamat']) ?></textarea>

        <label for="telepon">Telepon</label>
        <input type="text" name="telepon" id="telepon" value="<?= htmlspecialchars($anggota['telepon']) ?>">

        <button type="submit" name="update_profile" class="btn-submit">Simpan Perubahan</button>
    </form>

    <form action="dashboard_anggota.php" method="post">
        <button class="logout-button" type="submit">Kembali ke Dashboard</button>
    </form>
</div>

</body>
</html>
