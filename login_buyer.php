<?php
include "koneksi.php";
session_start();

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, password, nama_lengkap FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $hash, $nama);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['nama_lengkap'] = $nama;

            header("Location: pesan.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Login Pembeli</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #ffe5d0, #FFE7D2);
    }

    .card {
        width: 90%;
        max-width: 430px; /* ❗ Lebar lebih proporsional — tidak panjang berlebihan */
        padding: 35px 100px; /* ❗ Ruang kiri-kanan lebih pas */
        background: white;
        border-radius: 25px;
        box-shadow: 0 10px 28px rgba(0,0,0,0.15);
        text-align: left;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    h2 {
        color: #9C6B3C;
        margin-bottom: 25px;
        font-weight: bold;
        text-align: center;
        font-size: 26px;
    }

    label {
        color: #9C6B3C;
        font-weight: 600;
        margin-bottom: 5px;
        display: block;
        padding-left: 3px;
    }

    input {
        width: 95%;
        max-width: 100%;
        padding: 10px 14px;
        margin-bottom: 18px;
        font-size: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        background: #fafafa;
        transition: 0.2s;
    }

    input:focus {
        outline: none;
        border-color:#e3c49a;
        background: #fff;
    }

    button {
        width: 102%;
        padding: 16px;
        background: #E9A06C;
        border: none;
        color: white;
        font-size: 17px;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 8px;
    }

    button:hover {
        background: #e3c49a;
    }

    .link {
        margin-top: 18px;
        display: block;
        color: #9C6B3C;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
    }

    .link:hover {
        opacity: 0.7;
    }

    .error {
        color: red;
        margin-bottom: 10px;
        font-size: 14px;
        text-align: center;
    }
</style>

</head>
<body>

<div class="card">

    <h2>Login Pembeli</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">

        <label>Username</label>
        <input name="username" placeholder="Masukkan username Anda" required>

        <label>Password</label>
        <input name="password" type="password" placeholder="Masukkan password" required>

        <button name="login" type="submit">Masuk</button>
    </form>

    <a class="link" href="register.php">Belum punya akun? Daftar</a>

</div>

</body>
</html>

