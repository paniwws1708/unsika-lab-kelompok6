<?php
include 'c:\xampp\htdocs\pebewe\koneksi.php';
$res1 = mysqli_query($conn, "DESCRIBE peminjaman");
echo "--- peminjaman ---\n";
while ($row = mysqli_fetch_assoc($res1)) { print_r($row); }
$res2 = mysqli_query($conn, "DESCRIBE daftar_alat");
echo "--- daftar_alat ---\n";
while ($row = mysqli_fetch_assoc($res2)) { print_r($row); }
?>
