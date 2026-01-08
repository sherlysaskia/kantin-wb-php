<?php
include "koneksi.php";
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['penjual'] = $username;
        header("Location: kasir.php");
        exit;
    } else {
        echo "<script>alert('Username atau Password salah!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Penjual</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(180deg, #f9e7cf 0%, #f7dcb9 40%, #fffaf2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card {
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        border: none;
        width: 400px;
    }
    .card-header {
        background-color: #e7caa4;
        color: #3b2d1f;
        font-weight: bold;
        text-align: center;
    }
    .btn-login {
        background: linear-gradient(90deg, #cea77fff, #cea77fff);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 0;
    }
</style>
</head>
<body>

<div class="card">
    <div class="card-header py-3">
        <h4>👨‍🍳 Login Penjual</h4>
    </div>
    <div class="card-body p-4">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-login w-100">Masuk</button>
        </form>
        <div class="text-center mt-3">
            <a href="index.php" class="text-secondary text-decoration-none">⬅️ Kembali ke Beranda</a>
        </div>
    </div>
</div>

</body>
</html>
