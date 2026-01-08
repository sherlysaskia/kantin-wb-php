<?php
include "koneksi.php";
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['penjual'])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['proses_id'])) {
    if (isset($_GET['proses_id'])) {
    $id = intval($_GET['proses_id']);

    // ===============================
    // AMBIL DATA PESANAN
    // ===============================
   $q = mysqli_query($conn, "
    SELECT 
        u.nama_lengkap,
        u.nomor_wa,
        o.tipe_pesanan,
        o.nomor_meja
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = $id
");

if (!$q) {
    die("QUERY ERROR: " . mysqli_error($conn));
}


    if ($d = mysqli_fetch_assoc($q)) {

        // Ubah nomor ke format WA
        $target = preg_replace('/^0/', '62', $d['nomor_wa']);


        // Pesan sesuai tipe
        if ($d['tipe_pesanan'] === 'Dine In') {
            $info = "🍽️ *DINE IN*\n"
                  . "Meja: *{$d['nomor_meja']}*\n\n"
                  . "📌 Harap tetap di tempat\n"
                  . "Agar pesanan dapat diantar dengan mudah 🙏";
        } else {
            $info = "🥡 *TAKE AWAY*\n\n"
                  . "📌 Silakan ambil pesanan di kasir 🙏";
        }

        $pesan = "Halo *{$d['nama_lengkap']}* 👋\n\n"
               . "✅ Pesanan kamu *SUDAH SELESAI*\n\n"
               . $info . "\n\n"
               . "Terima kasih ❤️";

        // ===============================
        // KIRIM WA VIA FONNTE
        // ===============================
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: adFw2L345YSW2CxXL43J"
            ],
            CURLOPT_POSTFIELDS => [
                "target" => $target,
                "message" => $pesan
            ],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }

    // ===============================
    // UPDATE STATUS (SETELAH WA)
    // ===============================
    mysqli_query($conn, "UPDATE orders SET status='Selesai' WHERE id=$id");

    header("Location: kasir.php?page=pesanan");
    exit;
}

}

$page = $_GET['page'] ?? 'pesanan';

/* =============================
   DATA MENU UNTUK EDIT
============================= */
$editData = null;
if ($page === 'menu' && isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editData = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT * FROM menu WHERE id=$id")
    );
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Kasir</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
    background: linear-gradient(180deg,#f9e7cf,#f7dcb9,#fffaf2);
    min-height:100vh;
}
.card { background:#fff7ef; border-radius:15px; box-shadow:0 8px 25px rgba(0,0,0,.08); }
.card-header { background:#e7caa4; border-radius:15px 15px 0 0; }
.btn-proses { background:#f4b183; border:none; border-radius:8px; }
.btn-proses:hover { background:#ec9a5f; color:white; }
.status-selesai { background:#b7e4c7; color:#085f2c; padding:4px 8px; border-radius:6px; }
</style>
</head>

<body>
<div class="container py-4">

<!-- HEADER -->
<div class="card mb-4">
<div class="card-header text-center">
<h3>👨‍🍳 Dashboard Kasir</h3>
<div class="mt-2">
<a href="kasir.php?page=pesanan" class="btn btn-light">Pesanan</a>
<a href="kasir.php?page=menu" class="btn btn-light">Menu</a>
<a href="kasir.php?page=report" class="btn btn-warning">📊 Report</a>
<a href="logout.php" class="btn btn-danger float-end">Logout</a>
</div>
</div>
</div>

<!-- ================= PESANAN ================= -->
<?php if ($page === 'pesanan') { ?>

<?php
if (isset($_GET['proses_id'])) {
    $id = intval($_GET['proses_id']);

    mysqli_query($conn, "UPDATE orders SET status='Selesai' WHERE id=$id");
    header("Location: kasir.php?page=pesanan");
    exit;
}

$pesanan = mysqli_query($conn, "
    SELECT 
        o.id AS order_id,
        o.tipe_pesanan,
        o.nomor_meja,
        o.total,
        o.status,
        u.nama_lengkap,
        m.nama AS nama_menu,
        oi.jumlah,
        oi.subtotal
    FROM orders o
    JOIN users u ON o.user_id=u.id
    JOIN order_items oi ON o.id=oi.order_id
    JOIN menu m ON oi.menu_id=m.id
    ORDER BY o.id DESC
");
?>

<div class="card">
<div class="card-body">
<h4>📥 Pesanan Masuk</h4>

<table class="table text-center">
<thead>
<tr>
<th>No</th>
<th>Pembeli</th>
<th>Menu</th>
<th>Jumlah</th>
<th>Subtotal</th>
<th>Total</th>
<th>Metode</th>
<th>No Meja</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php $no=1; while($row=mysqli_fetch_assoc($pesanan)){ ?>
<tr>
<td><?= $no++; ?></td>
<td><?= $row['nama_lengkap']; ?></td>
<td><?= $row['nama_menu']; ?></td>
<td><?= $row['jumlah']; ?></td>
<td>Rp <?= number_format($row['subtotal'],0,',','.'); ?></td>
<td><b>Rp <?= number_format($row['total'],0,',','.'); ?></b></td>
<td><?= $row['tipe_pesanan']; ?></td>
<td><?= ($row['tipe_pesanan']==='Dine In') ? $row['nomor_meja'] : '-'; ?></td>
<td>
<?= ($row['status']==='Selesai')
    ? '<span class="status-selesai">Selesai</span>'
    : '<span class="badge bg-warning">Pending</span>'; ?>
</td>
<td>
<?php if ($row['status']!=='Selesai'){ ?>
<a href="kasir.php?page=pesanan&proses_id=<?= $row['order_id']; ?>" class="btn btn-proses btn-sm">Proses</a>
<?php } else { echo '✔️'; } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

<?php } ?>

<!-- ================= REPORT ================= -->
<?php if ($page === 'report') {

$filter = $_GET['filter'] ?? 'harian';
$detail = $_GET['detail'] ?? '';

// ================== QUERY SUMMARY ==================
if ($filter == 'harian') {
    $group = "DATE(o.created_at)";
    $label = "DATE(o.created_at)";
} elseif ($filter == 'bulanan') {
    $group = "DATE_FORMAT(o.created_at,'%Y-%m')";
    $label = "DATE_FORMAT(o.created_at,'%Y-%m')";
} else {
    $group = "YEAR(o.created_at)";
    $label = "YEAR(o.created_at)";
}

$sqlSummary = "
    SELECT 
        $label AS label,
        COUNT(o.id) AS jumlah,
        SUM(o.total) AS pendapatan
    FROM orders o
    WHERE o.status='Selesai'
    GROUP BY $group
";

$qSummary = mysqli_query($conn, $sqlSummary);

$labels = [];
$data = [];
$totalPesanan = 0;
$totalUang = 0;

while ($r = mysqli_fetch_assoc($qSummary)) {
    $labels[] = $r['label'];
    $data[] = (int)$r['pendapatan'];
    $totalPesanan += (int)$r['jumlah'];
    $totalUang += (int)$r['pendapatan'];
}
?>

<div class="card">
<div class="card-body">

<h4>📊 Report Penjualan</h4>

<form class="mb-3">
<input type="hidden" name="page" value="report">
<select name="filter" class="form-select w-25 d-inline">
<option value="harian" <?= $filter=='harian'?'selected':'' ?>>Harian</option>
<option value="bulanan" <?= $filter=='bulanan'?'selected':'' ?>>Bulanan</option>
<option value="tahunan" <?= $filter=='tahunan'?'selected':'' ?>>Tahunan</option>
</select>
<button class="btn btn-primary">Tampilkan</button>
</form>

<div class="row mb-3">
<div class="col-md-6">
<a href="?page=report&filter=<?= $filter ?>&detail=ranking" style="text-decoration:none">
<div class="card p-3 shadow-sm">
<h6>Total Pesanan</h6>
<h4><?= $totalPesanan ?></h4>
<small class="text-muted">Klik untuk lihat ranking</small>
</div>
</a>
</div>

<div class="col-md-6">
<div class="card p-3 shadow-sm">
<h6>Total Pendapatan</h6>
<h4>Rp <?= number_format($totalUang,0,',','.') ?></h4>
</div>
</div>
</div>

<canvas id="chart"></canvas>

<?php
// ================== RANKING MENU ==================
if ($detail == 'ranking') {

$sqlRank = "
    SELECT 
        m.nama AS menu,
        SUM(oi.jumlah) AS total_terjual
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN menu m ON oi.menu_id = m.id
    WHERE o.status='Selesai'
    GROUP BY oi.menu_id
    ORDER BY total_terjual DESC
";

$qRank = mysqli_query($conn, $sqlRank);
?>

<hr>
<h5>🏆 Ranking Menu Terlaris</h5>

<table class="table table-bordered">
<thead class="table-light">
<tr>
<th>Peringkat</th>
<th>Nama Menu</th>
<th>Jumlah Terjual</th>
</tr>
</thead>
<tbody>
<?php 
$no = 1; 
while ($r = mysqli_fetch_assoc($qRank)) { ?>
<tr>
<td><?= $no++ ?></td>
<td><?= $r['menu'] ?></td>
<td><?= $r['total_terjual'] ?></td>
</tr>
<?php } ?>
</tbody>
</table>

<a href="?page=report&filter=<?= $filter ?>" class="btn btn-secondary btn-sm">
⬅ Kembali
</a>

<?php } ?>

</div>
</div>

<script>
new Chart(document.getElementById('chart'),{
type:'bar',
data:{
labels:<?= json_encode($labels) ?>,
datasets:[{
label:'Pendapatan',
data:<?= json_encode($data) ?>,
backgroundColor:'#e7caa4'
}]
}
});
</script>

<?php } ?>


<!-- ================= KELOLA MENU ================= -->
<?php if ($page == 'menu') {

    // ===============================
    // UPDATE STATUS MENU
    // ===============================
    if (isset($_GET['menu_habis'])) {
        $id = intval($_GET['menu_habis']);
        mysqli_query($conn, "UPDATE menu SET status='habis' WHERE id=$id");
        echo "<script>location.href='kasir.php?page=menu';</script>";
        exit;
    }

    if (isset($_GET['menu_tersedia'])) {
        $id = intval($_GET['menu_tersedia']);
        mysqli_query($conn, "UPDATE menu SET status='tersedia' WHERE id=$id");
        echo "<script>location.href='kasir.php?page=menu';</script>";
        exit;
    }

    // ===============================
    // TAMBAH MENU
    // ===============================
    if (isset($_POST['tambah_menu'])) {
        $nama = $_POST['nama'];
        $harga = intval($_POST['harga']);
        $gambar = "";

        if (!empty($_FILES['gambar']['name'])) {
            $file = "menu_" . time() . "_" . basename($_FILES['gambar']['name']);
            move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/menu/" . $file);
            $gambar = $file;
        }

        mysqli_query($conn, "
            INSERT INTO menu (nama, harga, gambar, status)
            VALUES ('$nama','$harga','$gambar','tersedia')
        ");

        echo "<script>location.href='kasir.php?page=menu';</script>";
        exit;
    }

    // ===============================
    // UPDATE MENU
    // ===============================
    if (isset($_POST['update_menu'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $harga = intval($_POST['harga']);
        $gambarUpdate = $_POST['gambar_lama'];

        if (!empty($_FILES['gambar']['name'])) {
            $file = "menu_" . time() . "_" . basename($_FILES['gambar']['name']);
            move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/menu/" . $file);
            $gambarUpdate = $file;
        }

        mysqli_query($conn, "
            UPDATE menu 
            SET nama='$nama', harga='$harga', gambar='$gambarUpdate'
            WHERE id=$id
        ");

        echo "<script>location.href='kasir.php?page=menu';</script>";
        exit;
    }

    // ===============================
    // HAPUS MENU
    // ===============================
    if (isset($_GET['hapus'])) {
        $id = intval($_GET['hapus']);
        mysqli_query($conn, "DELETE FROM menu WHERE id=$id");
        echo "<script>location.href='kasir.php?page=menu';</script>";
        exit;
    }

    $menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY id DESC");
?>

<div class="card">
<div class="card-body">

<h4 class="mb-3">📋 Kelola Menu</h4>

<!-- FORM TAMBAH / EDIT -->
<?php if ($editData) { ?>
<div class="mb-4">
<h5>✏️ Edit Menu</h5>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $editData['id']; ?>">
<input type="hidden" name="gambar_lama" value="<?= $editData['gambar']; ?>">

<div class="row g-2">
<div class="col-md-4">
<input type="text" name="nama" class="form-control" value="<?= $editData['nama']; ?>" required>
</div>
<div class="col-md-3">
<input type="number" name="harga" class="form-control" value="<?= $editData['harga']; ?>" required>
</div>
<div class="col-md-3">
<input type="file" name="gambar" class="form-control">
<?php if ($editData['gambar']) { ?>
<img src="uploads/menu/<?= $editData['gambar']; ?>" width="60" class="mt-1 rounded">
<?php } ?>
</div>
<div class="col-md-2">
<button type="submit" name="update_menu" class="btn btn-warning w-100">Simpan</button>
</div>
</div>
</form>
</div>

<?php } else { ?>

<form method="post" enctype="multipart/form-data" class="mb-4">
<div class="row g-2">
<div class="col-md-4">
<input type="text" name="nama" class="form-control" placeholder="Nama Menu" required>
</div>
<div class="col-md-3">
<input type="number" name="harga" class="form-control" placeholder="Harga" required>
</div>
<div class="col-md-3">
<input type="file" name="gambar" class="form-control">
</div>
<div class="col-md-2">
<button type="submit" name="tambah_menu" class="btn btn-success w-100">Tambah</button>
</div>
</div>
</form>

<?php } ?>

<!-- TABEL MENU -->
<table class="table text-center">
<thead>
<tr>
<th>Gambar</th>
<th>Nama</th>
<th>Harga</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php while ($m = mysqli_fetch_assoc($menu)) { ?>
<tr>
<td>
<?php if ($m['gambar']) { ?>
<img src="uploads/menu/<?= $m['gambar']; ?>" width="60" style="border-radius:8px;">
<?php } else { echo "<i>-</i>"; } ?>
</td>

<td><?= $m['nama']; ?></td>
<td>Rp <?= number_format($m['harga'],0,',','.'); ?></td>

<td>
<?php if ($m['status']=='tersedia') { ?>
<span class="badge bg-success">Tersedia</span>
<?php } else { ?>
<span class="badge bg-danger">Habis</span>
<?php } ?>
</td>

<td>
<a href="kasir.php?page=menu&edit=<?= $m['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

<?php if ($m['status']=='tersedia') { ?>
<a href="kasir.php?page=menu&menu_habis=<?= $m['id']; ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Tandai menu HABIS?')">Habis</a>
<?php } else { ?>
<a href="kasir.php?page=menu&menu_tersedia=<?= $m['id']; ?>" class="btn btn-success btn-sm"
onclick="return confirm('Tandai menu TERSEDIA?')">Tersedia</a>
<?php } ?>

<a href="kasir.php?page=menu&hapus=<?= $m['id']; ?>" 
onclick="return confirm('Hapus menu ini?')" 
class="btn btn-outline-danger btn-sm">Hapus</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>

</div>
</div>
<?php } ?>

</div>
</body>
</html>
