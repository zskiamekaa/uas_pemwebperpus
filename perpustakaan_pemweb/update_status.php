<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_account = $_POST['id_account'];
    $status_akun = $_POST['status_akun'];

    $query = "UPDATE t_account SET status_akun = '$status_akun' WHERE id_account = '$id_account'";
    mysqli_query($conn, $query);
}

header("Location: manajemen_akun.php");
exit();
?>
