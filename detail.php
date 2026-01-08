<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_buyer.php");
    exit;
}

$user_id  = intval($_SESSION['user_id']);
$order_id = intval($_GET['id'] ?? 0);

/* ===============================
   CEK ORDER MILIK USER
================================ */
$stmt = mysqli_prepare($conn, "
    SELECT 
        id,
        user_id,
        catatan,
        status,
        total,
        created_at,
        tipe_pesanan,
        nomor_meja
    FROM orders 
    WHERE id = ? AND user_id = ?
");
mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt);
$res   = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($res);

if (!$order) {
    echo "Order tidak ditemukan atau bukan milik Anda.";
    exit;
}

/* ===============================
   AMBIL ITEM PESANAN
================================ */
$stmt2 = mysqli_prepare($conn, "
    SELECT 
        m.nama AS nama_menu,
        m.harga,
        oi.jumlah,
        (m.harga * oi.jumlah) AS subtotal
    FROM order_items oi
    JOIN menu m ON oi.menu_id = m.id
    WHERE oi.order_id = ?
");
mysqli_stmt_bind_param($stmt2, "i", $order_id);
mysqli_stmt_execute($stmt2);
$items = mysqli_stmt_get_result($stmt2);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Transaksi</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body {
    background: linear-gradient(to bottom right, #ffe5d0, #fff5ea);
    font-family: 'Segoe UI', sans-serif;
}
.card-custom {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
h3 {
    font-weight: bold;
    color: #9b5e2b;
}
.table thead {
    background: #ffddb8;
}
.table tbody tr:hover {
    background: #fac593ff;
}
.btn-back {
    background: #E9A06C;
    border: none;
    color: white;
    font-weight: 600;
}
.badge-status {
    padding: 8px 15px;
    font-size: 14px;
    border-radius: 12px;
}
.badge-pending { background: #f7b485ff; }
.badge-selesai { background: #b9e6a2; }
</style>
</head>

<body>
<div class="container mt-5 mb-5">
<div class="card-custom mx-auto" style="max-width: 750px;">

<h3 class="text-center mb-4">Detail Transaksi</h3>

<p><strong>Nama:</strong>
<?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?>
</p>

<p><strong>Tanggal:</strong> <?= $order['created_at']; ?></p>

<p><strong>Status:</strong>
<span class="badge-status <?= $order['status']==='Selesai'?'badge-selesai':'badge-pending'; ?>">
<?= htmlspecialchars($order['status']); ?>
</span>
</p>

<p><strong>Tipe Pesanan:</strong>
<?= htmlspecialchars($order['tipe_pesanan']); ?>
</p>

<?php if ($order['tipe_pesanan'] === 'Dine In') { ?>
<p><strong>Nomor Meja:</strong> <?= htmlspecialchars($order['nomor_meja']); ?></p>
<?php } ?>

<?php if (!empty($order['catatan'])) { ?>
<p><strong>Catatan:</strong><br>
<?= nl2br(htmlspecialchars($order['catatan'])); ?>
</p>
<?php } ?>

<table class="table table-bordered mt-4">
<thead>
<tr>
<th>No</th>
<th>Menu</th>
<th>Harga</th>
<th>Jumlah</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>
<?php $no=1; while ($it = mysqli_fetch_assoc($items)) { ?>
<tr>
<td><?= $no++; ?></td>
<td><?= htmlspecialchars($it['nama_menu']); ?></td>
<td>Rp <?= number_format($it['harga'],0,',','.'); ?></td>
<td><?= $it['jumlah']; ?></td>
<td>Rp <?= number_format($it['subtotal'],0,',','.'); ?></td>
</tr>
<?php } ?>
</tbody>
<tfoot>
<tr>
<td colspan="4" class="text-end"><strong>Total:</strong></td>
<td><strong>Rp <?= number_format($order['total'],0,',','.'); ?></strong></td>
</tr>
</tfoot>
</table>

<div class="text-center mt-4">
<a href="history.php" class="btn btn-back px-4">Kembali</a>
</div>

</div>
</div>
</body>
</html>
