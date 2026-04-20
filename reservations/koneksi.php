<?php
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'laravel';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>

