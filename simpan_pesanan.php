<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_menu = $_POST['id_menu'];
    $nama_pembeli = $_POST['nama_pembeli'];
    $jumlah = $_POST['jumlah'];

    // Ambil harga menu
    $stmt = $conn->prepare("SELECT harga FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id_menu);
    $stmt->execute();
    $res = $stmt->get_result();
    $menu = $res->fetch_assoc();
    $harga = $menu['harga'];

    $total = $harga * $jumlah;

    // Simpan ke tabel pesanan
    $stmt = $conn->prepare("INSERT INTO pesanan (id_menu, nama_pembeli, jumlah, total, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("isii", $id_menu, $nama_pembeli, $jumlah, $total);

    if ($stmt->execute()) {
        echo "<script>alert('Pesanan berhasil dibuat!'); window.location.href='status.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
