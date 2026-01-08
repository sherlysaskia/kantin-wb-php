<?php
include "koneksi.php";

$nama_pembeli = $_GET['nama_pembeli'] ?? '';

$result = mysqli_query($conn, "
    SELECT p.*, m.nama AS nama_menu, m.harga 
    FROM pesanan p 
    JOIN menu m ON p.menu_id = m.id 
    WHERE p.nama_pembeli = '$nama_pembeli'
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(180deg, #f9e7cf 0%, #f7dcb9 40%, #fffaf2 100%);
            min-height: 100vh;
            padding: 50px 0;
        }
        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .card-header {
            background-color: #e7caa4;
            color: #3b2d1f;
            border-bottom: none;
        }
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        .btn-back {
            background: linear-gradient(90deg, #6cc070, #57ad59);
            color: white;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            padding: 10px 22px;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(87,173,89,0.2);
        }
        .status-pending {
            background-color: #ffe8a1;
            color: #856404;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
        }
        .status-selesai {
            background-color: #c3e6cb;
            color: #155724;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-center py-4">
            <h3>📋 Status Pesanan Anda</h3>
            <p class="mb-0">Atas nama <strong><?= htmlspecialchars($nama_pembeli); ?></strong></p>
        </div>
        <div class="card-body">
            <?php if (mysqli_num_rows($result) > 0) { ?>
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($result)) { 
                            $grand_total += $row['total'];
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_menu']); ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= $row['jumlah']; ?></td>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="<?= $row['status'] == 'Pending' ? 'status-pending' : 'status-selesai'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end">Total Keseluruhan:</td>
                            <td colspan="2">Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php } else { ?>
                <div class="text-center py-4">
                    <p class="fw-bold text-secondary">Belum ada pesanan ditemukan 😢</p>
                </div>
            <?php } ?>
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-back">⬅️ Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

