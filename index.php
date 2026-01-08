<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pemesanan Kantin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        body {
            margin: 0;
            background: #fffaf2;
        }

        /* 🌄 Header */
        header {
            position: relative;
            height: 100vh;
            overflow: hidden;
            text-align: center;
            color: white;
        }

        header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(65%);
        }

        header .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        header .content {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }

        header h1 {
            font-size: 3rem;
            font-weight: 700;
        }

        header p {
            font-size: 1.2rem;
            margin-bottom: 25px;
        }

        header a {
            background:#ffddb8;
            color: #000;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        header a:hover {
            background: #cea77fff;
            transform: scale(1.05);
        }

        /* ✨ Section Pilih Peran */
        section {
            padding: 120px 20px;
            background: linear-gradient(180deg, #f9e7cf 0%, #f7dcb9 40%, #fffaf2 100%);
            color: #4b3b2b;
        }

        section h2 {
            font-weight: 700;
            margin-bottom: 40px;
            color: #3b2d1f;
        }

        .role-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 40px 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .btn-role {
            width: 100%;
            padding: 14px;
            font-size: 18px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: 0.3s ease;
        }

        .btn-pembeli {
            background: linear-gradient(90deg, #cea77fff, #cea77fff);
            color: white;
        }

        .btn-pembeli:hover {
            background: linear-gradient(90deg, #ffddb8, #ffddb8);
            transform: scale(1.05);
        }

        .btn-penjual {
            background: linear-gradient(90deg, #cea77fff, #cea77fff);
            color: white;
        }

        .btn-penjual:hover {
            background: linear-gradient(90deg, #ffddb8, #ffddb8);
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            padding: 30px;
            background: #2b2118;
            color: #f8f3e9;
            font-size: 15px;
        }

        /* Animasi lembut saat muncul */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tombol tambahan */
        .extra-buttons a {
            border-radius: 30px;
            padding: 10px 22px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .extra-buttons a:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>

<!-- 🌄 Header -->
<header>
    <img src="/kantin/bg4.jpg" alt="Kantin Background">
    <div class="overlay"></div>
    <div class="content">
        <h1>🍴 Selamat Datang di Kantin Freshsweety</h1>
        <p>Pesan makanan dan minuman favoritmu dengan mudah, cepat, dan praktis!</p>
        <a href="#pilihan">Mulai Sekarang ⬇️</a>
    </div>
</header>

<!-- 👥 Pilih Peran -->
<section id="pilihan" class="text-center">
    <div class="container fade-in">
        <h2>Pilih Peran Anda</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="role-card">
                    <h4 class="mb-3">Sebagai Pembeli</h4>
                    <p>Pesan makanan & minuman favoritmu tanpa antre, cukup lewat sistem online!</p>
                    <a href="pesan.php" class="btn btn-role btn-pembeli mt-3">🛒 Masuk Sebagai Pembeli</a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="role-card">
                    <h4 class="mb-3">Sebagai Penjual</h4>
                    <p>Kelola pesanan masuk, ubah status, dan pantau transaksi dari satu tempat.</p>
                    <a href="kasir.php" class="btn btn-role btn-penjual mt-3">👨‍🍳 Masuk Sebagai Penjual</a>
                </div>
            </div>
        </div>

        <!-- 📋 Tombol Tambahan -->
        <div class="mt-5 extra-buttons d-flex justify-content-center gap-3">
           
        </div>
    </div>
</section>

<footer>
    &copy; <?= date('Y'); ?> Kantin Cerdas | Dibuat oleh Kelompok 3
</footer>

</body>
</html>
