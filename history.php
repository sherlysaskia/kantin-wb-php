<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login_buyer.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

$q = mysqli_prepare($conn, "SELECT id, total, status, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
mysqli_stmt_bind_param($q, "i", $user_id);
mysqli_stmt_execute($q);
$res = mysqli_stmt_get_result($q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Riwayat Pesanan</title>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #FFD1A8, #FFE7D2);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    .container {
        width: 95%;
        max-width: 900px;
        background: white;
        margin-top: 40px;
        padding: 30px 35px;
        border-radius: 25px;
        box-shadow: 0 10px 28px rgba(0,0,0,0.12);
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(12px);}
        to {opacity: 1; transform: translateY(0);}
    }

    h3 {
        color: #9C6B3C;
        font-size: 26px;
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
    }

    thead {
        background: #E9A06C;
        color: white;
    }

    thead th {
        padding: 12px;
        font-size: 15px;
        text-align: left;
    }

    tbody tr {
        background: #fff;
        transition: 0.2s;
    }

    tbody tr:hover {
        background: #FFF2E7;
    }

    tbody td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .btn {
        padding: 7px 12px;
        border-radius: 10px;
        font-size: 13px;
        text-decoration: none;
        display: inline-block;
        transition: 0.2s;
    }

    .btn-primary {
        background: #E9A06C;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: #E9A06C;
    }

    .btn-success {
        background:  #E9A06C;
        color: white;
        padding: 10px 16px;
        margin-top: 20px;
        display: inline-block;
        font-size: 14px;
        border-radius: 12px;
    }

    .btn-success:hover {
        background: #da9f5dff;
    }

    .footer-btn {
        text-align: center;
        margin-top: 20px;
    }

    .menu-list {
        color:  #E9A06C;
        font-size: 13px;
        font-style: italic;
    }
</style>

</head>
<body>

<div class="container">
    <h3>Riwayat Pesanan</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Menu Dipesan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php 
        $no=1; 
        while ($row = mysqli_fetch_assoc($res)) { 

            // Ambil menu dari order_items
            $oid = $row['id'];
           $items = mysqli_query($conn, 
    "SELECT m.nama, oi.jumlah
     FROM order_items oi
     JOIN menu m ON oi.menu_id = m.id
     WHERE oi.order_id = $oid");


            $menu_text = [];
            while ($m = mysqli_fetch_assoc($items)) {
               $menu_text[] = $m['nama'] . " (" . $m['jumlah'] . ")";

            }
            $menu_final = implode(" • ", $menu_text);
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['created_at']; ?></td>

                <td class="menu-list">
                    <?= $menu_final; ?>
                </td>

                <td>Rp <?= number_format($row['total'],0,',','.'); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td><a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-primary">Detail</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="footer-btn">
        <a href="pesan.php" class="btn-success">Kembali</a>
    </div>
</div>

</body>
</html>
