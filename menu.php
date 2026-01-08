<?php
session_start();
include "koneksi.php";

// ====== LOGIN CEK ======
if (!isset($_SESSION['login'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username == "admin" && $password == "1234") {
            $_SESSION['login'] = true;
            header("Location: menu.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Login Kelola Menu</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background: linear-gradient(180deg, #f9e7cf 0%, #f7dcb9 40%, #fffaf2 100%); height:100vh; display:flex; justify-content:center; align-items:center; }
            .login-card { background:#fff; padding:35px; border-radius:15px; width:360px; box-shadow:0 6px 20px rgba(0,0,0,0.15); }
            .btn-login { background:#57ad59; color:white; border:none; border-radius:10px; padding:10px; width:100%; }
        </style>
    </head>
    <body>
        <div class="login-card">
            <h3 class="text-center">🔒 Login Kelola Menu</h3>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control mb-3" required>

                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control mb-3" required>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
    </body>
    </html>

    <?php
    exit;
}


// =============================
// ====== TAMBAH MENU =========
// =============================
if (isset($_POST['tambah'])) {

    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    $gambar_name = $_FILES['gambar']['name'];
    $gambar_tmp  = $_FILES['gambar']['tmp_name'];

    if (!is_dir("uploads")) mkdir("uploads");

    $newName = time() . "_" . $gambar_name;
    move_uploaded_file($gambar_tmp, "uploads/" . $newName);

    mysqli_query($conn, "INSERT INTO menu (nama, harga, gambar) VALUES ('$nama', '$harga', '$newName')");
    header("Location: menu.php");
    exit;
}


// =============================
// ====== EDIT MENU ===========
// =============================
if (isset($_POST['edit_menu'])) {

    $id    = $_POST['id'];
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];

    $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM menu WHERE id='$id'"));
    $old_gambar = $old['gambar'];

    // cek apakah user upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {

        $gambar_name = $_FILES['gambar']['name'];
        $gambar_tmp  = $_FILES['gambar']['tmp_name'];
        $newName     = time() . "_" . $gambar_name;

        move_uploaded_file($gambar_tmp, "uploads/" . $newName);

        // hapus gambar lama
        if (!empty($old_gambar) && file_exists("uploads/" . $old_gambar)) {
            unlink("uploads/" . $old_gambar);
        }

        // update dengan gambar baru
        mysqli_query($conn, "UPDATE menu SET nama='$nama', harga='$harga', gambar='$newName' WHERE id='$id'");

    } else {
        // update tanpa gambar baru
        mysqli_query($conn, "UPDATE menu SET nama='$nama', harga='$harga' WHERE id='$id'");
    }

    header("Location: menu.php");
    exit;
}


// =============================
// ====== HAPUS MENU ==========
// =============================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM menu WHERE id='$id'"));
    if (!empty($old['gambar']) && file_exists("uploads/".$old['gambar'])) {
        unlink("uploads/".$old['gambar']);
    }

    mysqli_query($conn, "DELETE FROM menu WHERE id='$id'");
    header("Location: menu.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM menu ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Menu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background:linear-gradient(180deg,#f9e7cf,#f7dcb9,#fffaf2); min-height:100vh; padding:50px 0; }
    .card { border-radius:18px; box-shadow:0 8px 25px rgba(0,0,0,0.1); border:none; }
    .btn-add { background:linear-gradient(90deg,#6cc070,#57ad59); border:none; border-radius:10px; padding:8px 18px; color:white; }
    .menu-img { width:70px; height:70px; object-fit:cover; border-radius:10px; }
    .btn-edit { background:#f4b183; border:none; color:#3b2d1f; padding:6px 12px; border-radius:8px; }
    .btn-edit:hover { background:#e39a59; color:white; }
</style>

</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-center py-4" style="background:#e7caa4;">
            <h3>📋 Kelola Menu Makanan & Minuman</h3>
        </div>

        <div class="card-body">
            
            <!-- FORM TAMBAH MENU -->
            <form method="POST" enctype="multipart/form-data" class="mb-4 d-flex gap-3 justify-content-center">
                <input type="text" name="nama" class="form-control w-25" placeholder="Nama Menu" required>
                <input type="number" name="harga" class="form-control w-25" placeholder="Harga" required>
                <input type="file" name="gambar" class="form-control w-25" required accept="image/*">
                <button type="submit" name="tambah" class="btn btn-add">+ Tambah Menu</button>
            </form>

            <!-- TABEL MENU -->
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                <?php $no=1; while($row=mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td><?= $no++; ?></td>

                        <td>
                            <img src="uploads/<?= $row['gambar']; ?>" class="menu-img">
                        </td>

                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td>Rp <?= number_format($row['harga'],0,',','.'); ?></td>

                        <td>
                            <!-- BTN EDIT -->
                            <button class="btn btn-edit btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id']; ?>">Edit</button>

                            <!-- BTN HAPUS -->
                            <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>


                    <!-- ============ MODAL EDIT ============ -->
                    <div class="modal fade" id="edit<?= $row['id']; ?>">
                      <div class="modal-dialog">
                        <div class="modal-content">

                          <div class="modal-header" style="background:#e7caa4;">
                            <h5 class="modal-title">✏ Edit Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>

                          <form method="POST" enctype="multipart/form-data">

                          <div class="modal-body">

                            <input type="hidden" name="id" value="<?= $row['id']; ?>">

                            <label class="form-label">Nama Menu</label>
                            <input type="text" name="nama" class="form-control mb-2" value="<?= $row['nama']; ?>" required>

                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control mb-2" value="<?= $row['harga']; ?>" required>

                            <label class="form-label">Gambar (biarkan kosong jika tidak ganti)</label>
                            <input type="file" name="gambar" class="form-control mb-2" accept="image/*">

                            <img src="uploads/<?= $row['gambar']; ?>" width="120" class="rounded mt-2">

                          </div>

                          <div class="modal-footer">
                            <button type="submit" name="edit_menu" class="btn btn-success">Simpan</button>
                          </div>

                          </form>

                        </div>
                      </div>
                    </div>
                    <!-- ===================================== -->

                <?php } ?>

                </tbody>
            </table>

            <div class="text-center mt-3">
                <a href="logout.php" class="btn btn-warning">🚪 Logout</a>
                <a href="index.php" class="btn btn-success">⬅️ Kembali ke Halaman Utama</a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
