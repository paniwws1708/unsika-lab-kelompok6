<?php
$conn = mysqli_connect("localhost", "root", "", "user_db", 3307);
$id = $_GET['id'];
$query = mysqli_query($conn, "DELETE FROM daftar_alat WHERE id = '$id'");
if ($query) {
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'barang_admin.php'; 
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus data!');
            window.location.href = 'barang_admin.php';
          </script>";
}
?>