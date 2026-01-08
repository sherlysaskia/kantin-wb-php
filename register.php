<?php
include "koneksi.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $nomor_wa = trim($_POST['nomor_wa']); // diganti dari no_hp
    $password = $_POST['password'];

    if ($username === '' || $nama === '' || $password === '' || $nomor_wa === '') {
        $error = "Lengkapi semua form.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn,
            "INSERT INTO users (username, password, nama_lengkap, nomor_wa) VALUES (?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssss", $username, $hash, $nama, $nomor_wa);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['username'] = $username;
            $_SESSION['nama_lengkap'] = $nama;
            header("Location: pesan.php");
            exit;
        } else {
            $error = "Gagal registrasi. Username mungkin sudah dipakai.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Akun</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(to bottom right, #ffe5d0, #fff5ea);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-register {
            background: #ffffff;
            border-radius: 22px;
            padding: 45px 55px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        }

        h3 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 28px;
            color: #9b5e2b;
        }

        .form-label {
            font-weight: 600;
            color: #9b5e2b;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e6c7a8;
            background: #fffdf9;
        }

        .form-control:focus {
            border-color: #e3b98a;
            box-shadow: 0 0 0 3px rgba(227,185,138,0.25);
        }

        .btn-register {
            background: #E9A06C;
            color: white;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px;
            font-size: 17px;
            transition: 0.2s;
            width: 100%;
        }

        .btn-register:hover {
            background: #e3c49a;
        }

        .error-text {
            background: #ffe0dd;
            padding: 12px;
            border-left: 4px solid #ff4d4d;
            border-radius: 8px;
            color: #b10000;
            margin-bottom: 18px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            text-decoration: none;
            color: #c26d2d;
            font-weight: 600;
        }
        .login-link a:hover {
            opacity: 0.7;
        }
    </style>
</head>

<body>

<div class="card-register">

    <h3>Daftar Akun Pembeli</h3>

    <?php if (isset($error)) { ?>
        <div class="error-text"><?= htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
                   placeholder="Masukkan nama Anda" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" name="nomor_wa" class="form-control"
                   placeholder="Masukkan nomor WA aktif (contoh: 6281234567890)" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   placeholder="Buat username" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control"
                   placeholder="Buat password" required>
        </div>

        <button type="submit" class="btn-register">Daftar & Masuk</button>

    </form>

    <div class="login-link">
        <small>Sudah punya akun?
            <a href="login_buyer.php">Login</a>
        </small>
    </div>

</div>

</body>
</html>
