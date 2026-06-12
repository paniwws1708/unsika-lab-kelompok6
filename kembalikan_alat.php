<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nama_user'])) {
    header("Location:index.php");
    exit();
}

$nama_user=$_SESSION['nama_user'];

$id_peminjaman=$_GET['id_peminjaman'] ?? '';

if(empty($id_peminjaman)){
die("ID tidak ditemukan");
}

$sql="SELECT *
      FROM peminjaman
      WHERE id_peminjaman='$id_peminjaman'
      AND nama_mahasiswa='$nama_user'
      AND status='Disetujui'";

$query=mysqli_query($conn,$sql);

if(mysqli_num_rows($query)>0){

mysqli_query(
$conn,
"UPDATE peminjaman
SET status='Menunggu Pengembalian'
WHERE id_peminjaman='$id_peminjaman'"
);

echo "
<script>
alert('Menunggu konfirmasi admin');
window.location='dashboard.php';
</script>
";

}else{

echo "
<script>
alert('Data tidak valid');
window.location='dashboard.php';
</script>
";

}
?>