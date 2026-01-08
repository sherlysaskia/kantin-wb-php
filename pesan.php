<?php
include "koneksi.php";
session_start();

/* =============================
   CEK LOGIN
============================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: login_buyer.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

/* =============================
   AMBIL DATA MENU (+ STATUS)
============================= */
$menu_q = mysqli_query(
    $conn,
    "SELECT id, nama, harga, gambar, status FROM menu ORDER BY id ASC"
);

/* =============================
   PROSES PESANAN
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan'])) {

    mysqli_begin_transaction($conn);

    try {

        $catatan = trim($_POST['catatan'] ?? '');

        /* ===== TIPE PESANAN ===== */
        $tipe_pesanan = $_POST['tipe_pesanan'] ?? 'Take Away';
        $nomor_meja   = ($tipe_pesanan === 'Dine In') ? ($_POST['nomor_meja'] ?? null) : null;

        /* ===== INSERT ORDERS ===== */
        $stmtOrder = mysqli_prepare($conn,
            "INSERT INTO orders 
            (user_id, catatan, tipe_pesanan, nomor_meja, status, total)
            VALUES (?, ?, ?, ?, 'Pending', 0)"
        );

        mysqli_stmt_bind_param(
            $stmtOrder,
            "isss",
            $user_id,
            $catatan,
            $tipe_pesanan,
            $nomor_meja
        );
        mysqli_stmt_execute($stmtOrder);

        $order_id = mysqli_insert_id($conn);

        /* ===== INSERT ORDER ITEMS ===== */
        $stmtItem = mysqli_prepare($conn,
            "INSERT INTO order_items
            (order_id, menu_id, jumlah, subtotal)
            VALUES (?, ?, ?, ?)"
        );

        $menu_ids = $_POST['menu_id'] ?? [];
        $jumlah   = $_POST['jumlah'] ?? [];

        $total = 0;

        foreach ($menu_ids as $i => $menu_id) {

            $qty = intval($jumlah[$i] ?? 0);
            if ($qty <= 0) continue;

            $menu_id = intval($menu_id);

            /* 🔒 VALIDASI STATUS MENU (ANTI CURANG) */
            $q = mysqli_query(
                $conn,
                "SELECT harga, status FROM menu WHERE id=$menu_id"
            );
            $m = mysqli_fetch_assoc($q);

            if (!$m || $m['status'] !== 'tersedia') {
                continue; // menu habis → abaikan
            }

            $subtotal = $m['harga'] * $qty;
            $total   += $subtotal;

            mysqli_stmt_bind_param(
                $stmtItem,
                "iiii",
                $order_id,
                $menu_id,
                $qty,
                $subtotal
            );
            mysqli_stmt_execute($stmtItem);
        }

        /* ===== UPDATE TOTAL ===== */
        $stmtTotal = mysqli_prepare($conn,
            "UPDATE orders SET total=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmtTotal, "ii", $total, $order_id);
        mysqli_stmt_execute($stmtTotal);

        mysqli_commit($conn);

        header("Location: detail.php?id=".$order_id);
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal membuat pesanan');location.href='pesan.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Pemesanan Kantin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(180deg,#fff1e6,#ffe8d8,#fff5ee);
}
.menu-card {
    border-radius: 12px;
    background: #fff7ef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: 1px solid #e8c8a4;
}
.menu-img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
}
.menu-habis {
    filter: grayscale(100%);
    opacity: .6;
}
.btn-order {
    background: #e7caa4;
    border: none;
    font-weight: 600;
    border-radius: 10px;
}
</style>
</head>

<body>
    

<div class="container py-4">
<div class="card shadow" style="background:#fff7ef;">
<div class="card-header text-center" style="background:#e7caa4;">
    <h3>🍽️ Form Pemesanan Kantin</h3>
     <p class="mt-2">
        Hai <b><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?></b> |
        <a href="history.php" class="btn btn-sm btn-secondary">📜 Riwayat</a>
        <a href="logout.php" class="btn btn-sm btn-danger">🚪 Logout</a>
    </p>

</div>

<div class="card-body">

<form method="post">

<!-- CATATAN -->
<div class="mb-3">
<label class="fw-bold">Catatan</label>
<textarea name="catatan" class="form-control"></textarea>
</div>

<!-- TIPE PESANAN -->
<div class="mb-3">
<label class="fw-bold">Tipe Pesanan</label>
<select name="tipe_pesanan" id="tipe_pesanan" class="form-control" onchange="toggleMeja()" required>
<option value="">-- Pilih --</option>
<option value="Take Away">Take Away</option>
<option value="Dine In">Dine In</option>
</select>
</div>

<div class="mb-3" id="meja_box" style="display:none;">
<label class="fw-bold">Nomor Meja</label>
<select name="nomor_meja" class="form-control">
<?php for($i=1;$i<=11;$i++) echo "<option>$i</option>"; ?>
</select>
</div>

<!-- MENU -->
<div class="row">
<?php while ($m = mysqli_fetch_assoc($menu_q)) { ?>
<div class="col-md-3 mb-3">
<div class="menu-card p-2 text-center <?= $m['status']=='habis'?'menu-habis':'' ?>">

<?php if ($m['gambar']) { ?>
<img src="uploads/menu/<?= $m['gambar']; ?>" class="menu-img">
<?php } ?>

<h6><?= htmlspecialchars($m['nama']); ?></h6>
<p>Rp <?= number_format($m['harga'],0,',','.'); ?></p>

<?php if ($m['status']=='tersedia') { ?>
<input type="hidden" name="menu_id[]" value="<?= $m['id']; ?>">
<input type="number" name="jumlah[]" class="form-control text-center" min="0" placeholder="Jumlah">
<?php } else { ?>
<span class="badge bg-danger">HABIS</span>
<?php } ?>

</div>
</div>
<?php } ?>
</div>

<div class="text-center">
<button type="submit" name="pesan" class="btn btn-order btn-lg">
Pesan Sekarang
</button>
</div>

</form>
</div>
</div>
</div>

<script>
function toggleMeja(){
document.getElementById('meja_box').style.display =
document.getElementById('tipe_pesanan').value === 'Dine In'
? 'block' : 'none';
}
</script>

</body>
</html>
