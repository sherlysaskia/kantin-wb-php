<?php
include "koneksi.php";
session_start();

if (isset($_GET['id']) && isset($_GET['s'])) {
    $id = intval($_GET['id']);
    $status = $_GET['s'];

    $status_valid = ['Pending', 'Diproses', 'Selesai'];

If (in_array($status, $status_valid)) {
        $query = mysqli_query($conn, "UPDATE pesanan SET status='$status' WHERE id='$id'");

        if ($query) {
            header("Location: kasir.php");
            exit;
        } else {
            echo "<script>alert('Gagal update status!');history.back();</script>";
        }
    } else {
        echo "<script>alert('Status tidak valid!');history.back();</script>";
    }
} else {
    echo "<script>alert('Parameter tidak lengkap!');history.back();</script>";
}
?>

