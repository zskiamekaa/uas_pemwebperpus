<?php
$host = "blgcrn9oxyd4ww0tczdg-mysql.services.clever-cloud.com";
$user = "uk9l58u5gl9ipgv3"; // ganti jika bukan root
$pass = "LfRSZ3UGUbfpFWvWLcLO";     // ganti jika ada password
$db   = "blgcrn9oxyd4ww0tczdg";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
