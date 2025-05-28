<?php
$host = "localhost";
$user = "root"; // ganti jika bukan root
$pass = "";     // ganti jika ada password
$db   = "perpustakaan_pemweb";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
