<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $query = "SELECT a.*, r.nama_role 
              FROM t_account a 
              JOIN p_role r ON a.id_role = r.id_role 
              WHERE a.username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        if ($data['password'] === $password) {
            if ($data['status_akun'] != 'aktif') {
                echo "<script>alert('Akun Anda belum aktif. Silakan hubungi admin.');</script>";
            } else {
                $_SESSION['id_account'] = $data['id_account'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['role'] = $data['nama_role'];

                if ($data['nama_role'] == 'admin') {
                    header("Location: dashboard_admin.php");
                } elseif ($data['nama_role'] == 'staff') {
                    header("Location: dashboard_staff.php");
                } else {
                    header("Location: dashboard_anggota.php");
                }
                exit();
            }
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Login gagal! Username tidak ditemukan.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Berry's Library - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
    <style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #fdeff2; /* soft pink background */
    }
    .container {
        display: flex;
        width: 800px;
        height: 500px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
    }
    .left {
        width: 50%;
        background: url('img/perpus.jpg') no-repeat center center;
        background-size: cover;
    }
    .right {
        width: 50%;
        padding: 40px;
        box-sizing: border-box;
    }
    .right h1 {
        margin: 0;
        font-weight: 600;
        color: #d6336c; /* soft rose */
    }
    .right h2 {
        margin-top: 10px;
        font-weight: 400;
        color: #6d214f; /* dark mauve */
    }
    form {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
    }
    label {
        margin-bottom: 5px;
        font-weight: 500;
        color: #6d214f;
    }
    input[type="text"],
    input[type="password"] {
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #e5b3c9;
        border-radius: 6px;
        font-size: 14px;
    }
    input[type="submit"] {
        background-color: #f7a6c1;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }
    input[type="submit"]:hover {
        background-color: #f17ca0;
    }
    .register-link {
        margin-top: -10px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #d6336c;
        text-decoration: none;
        cursor: pointer;
    }
    .register-link:hover {
        text-decoration: underline;
    }
    #registrationDropdown {
        z-index: 10;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="left"></div>
        <div class="right">
            <h1>KIA SmartLib</h1>
            <h2>Login Form</h2>
            <form method="POST" action="">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username/email" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" maxlength="20" required />

                <a href="#" class="register-link" onclick="showRegistrationDropdown()">Belum punya akun? Daftar di sini</a>
                <div id="registrationDropdown" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; padding:10px; margin-top:5px;">
                    <a href="register_staff.php" style="display:block; margin-bottom:5px;">Registrasi Staff</a>
                    <a href="register_anggota.php" style="display:block;">Registrasi Anggota</a>
                </div>

                <script>
                    function showRegistrationDropdown() {
                        const dropdown = document.getElementById('registrationDropdown');
                        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
                    }
                </script>

                <input type="submit" value="Login" />
            </form>
        </div>
    </div>
</body>
</html>
