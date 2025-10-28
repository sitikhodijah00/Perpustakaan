<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "db_library"; // ganti sesuai nama database kamu

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
