<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kantinn_db"; // <-- pastikan ini persis sama dengan nama DB di phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
