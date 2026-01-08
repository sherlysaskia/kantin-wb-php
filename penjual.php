<?php
session_start();
include "koneksi.php";

// cek apakah penjual sudah login
if (!isset($_SESSION['penjual'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Penjual</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(180deg, #f9e7cf 0%, #f7dcb9 40%, #fffaf2 100%);
        min-height: 100vh;
        padding: 70px 0;
    }
    .card {
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border: none;
    }
    .menu-btn {
        background: #6ab46e;
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 20px;
    }
    .menu-btn:hover {
        background: #589c5c;
        color: white;
    }
    .kasir-btn {
        background: #f0a45c;
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 20px;
    }
    .kasir-btn:hover {
        background: #d98b45;
        color: white;
    }
</style>
</head>
<body>

<div class="container">
    <div class="card p-5 text-center">

        <h2 class="mb-4">👨‍🍳 Dashboard Penjual</h2>

        <div class="d-flex justify-content-center gap-4">

            <!-- tombol menuju ke menu.php -->
            <a href="menu.php" class="menu-btn">📋 Kelola Menu</a>

            <!-- tombol menuju ke kasir.php -->
            <a href="kasir.php" class="kasir-btn">🛒 Pesanan Masuk</a>

        </div>

        <div class="mt-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

    </div>
</div>

</body>
</html>
