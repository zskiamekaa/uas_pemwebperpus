<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // simpan plain text
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $id_role = 3; // role anggota
    $status_akun = 'aktif';

    mysqli_begin_transaction($conn);

    try {
        $sql_account = "INSERT INTO t_account (username, password, id_role, status_akun) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_account);
        $stmt->bind_param("ssis", $username, $password, $id_role, $status_akun);
        if (!$stmt->execute()) {
            throw new Exception("Error insert t_account: " . $stmt->error);
        }

        $id_account = $conn->insert_id;

        $sql_anggota = "INSERT INTO t_anggota (id_account, nama, email, telepon, alamat) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql_anggota);
        $stmt2->bind_param("issss", $id_account, $nama, $email, $telepon, $alamat);
        if (!$stmt2->execute()) {
            throw new Exception("Error insert t_anggota: " . $stmt2->error);
        }

        mysqli_commit($conn);
        $success_msg = "Registrasi Anggota berhasil!";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error_msg = "Registrasi gagal: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Registrasi Anggota</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #FCE8F6; /* pink soft cerah */
    color: #5C385F; /* ungu gelap untuk teks */
    padding: 30px;
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  form {
    background-color: #F9D7E0; /* pink pastel lembut */
    padding: 30px 35px;
    border-radius: 12px;
    width: 400px;
    box-shadow: 0 0 20px rgba(236, 72, 153, 0.4); /* glow pink */
  }

  h2 {
    text-align: center;
    margin-bottom: 24px;
    font-weight: 700;
    color: #D6336C; /* pink agak tua */
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #8B496D; /* ungu lembut */
  }

  input[type="text"],
  input[type="email"],
  input[type="password"],
  textarea {
    width: 100%;
    padding: 10px 14px;
    margin-bottom: 20px;
    border: none;
    border-radius: 8px;
    background-color: #FEE9F1; /* pink sangat lembut */
    color: #6B4C63; /* ungu gelap */
    font-size: 15px;
    box-sizing: border-box;
    resize: vertical;
    outline-offset: 2px;
    outline-color: #D6336C;
    transition: background-color 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="email"]:focus,
  input[type="password"]:focus,
  textarea:focus {
    background-color: #F7C6D3; /* pink lebih kuat saat fokus */
    outline: 2px solid #E11D74; /* pink cerah */
  }

  button {
    width: 100%;
    padding: 12px 0;
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.25s ease;
  }

  button[type="submit"] {
    background-color: #E11D74; /* pink cerah */
  }

  button[type="submit"]:hover {
    background-color: #B3155A; /* pink tua saat hover */
  }

  button.back-btn {
    background-color: #8B496D; /* ungu lembut */
    margin-top: 12px;
    font-size: 16px;
    font-weight: 600;
  }

  button.back-btn:hover {
    background-color: #6B3754; /* ungu gelap hover */
  }

  .message {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
  }

  .success {
    color: #22c55e; /* hijau */
  }

  .error {
    color: #ef4444; /* merah */
  }

  @media (max-width: 450px) {
    form {
      width: 90%;
      padding: 25px 20px;
    }
  }
</style>
</head>
<body>

<form method="post" action="">
  <h2>Registrasi Anggota</h2>

  <?php if (!empty($success_msg)): ?>
    <div class="message success"><?= htmlspecialchars($success_msg) ?></div>
  <?php elseif (!empty($error_msg)): ?>
    <div class="message error"><?= htmlspecialchars($error_msg) ?></div>
  <?php endif; ?>

  <label for="username">Username:</label>
  <input type="text" name="username" id="username" required>

  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>

  <label for="nama">Nama Lengkap:</label>
  <input type="text" name="nama" id="nama" required>

  <label for="email">Email:</label>
  <input type="email" name="email" id="email" required>

  <label for="telepon">Telepon:</label>
  <input type="text" name="telepon" id="telepon" required>

  <label for="alamat">Alamat:</label>
  <textarea name="alamat" id="alamat" required></textarea>

  <button type="submit">Daftar</button>
  <button type="button" class="back-btn" onclick="window.location.href='login.php'">Kembali ke Login</button>
</form>

</body>
</html>
