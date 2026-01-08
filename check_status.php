<?php
require 'koneksi.php';

// ambil pesanan terakhir yang status Selesai
$sql = "
SELECT p.id, COALESCE(m.nama_menu, m.nama, m.menu) AS menu_name
FROM pesanan p
JOIN menu m ON (p.menu_id = m.id OR p.menu_id = m.id_menu)
WHERE LOWER(p.status) = 'selesai'
ORDER BY p.id DESC
LIMIT 1
";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(null);
}
