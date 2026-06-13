<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");

    if ($query) {
        echo "<script>
                alert('Pengguna berhasil dinonaktifkan (dihapus)!');
                window.location.href = 'users_admin.php'; 
              </script>";
    } else {
        echo "<script>
                alert('Gagal menonaktifkan pengguna!');
                window.location.href = 'users_admin.php';
              </script>";
    }
} else {
    header("Location: users_admin.php");
    exit();
}
?>
