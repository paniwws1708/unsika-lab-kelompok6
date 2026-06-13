<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include '../koneksi.php';

$id_pinjam = $_GET['id'] ?? '';
$status_baru = $_GET['status'] ?? ''; 

if(empty($id_pinjam) || empty($status_baru)){
    die("Error: ID atau Status kosong");
}

// JALUR AMAN: Jika tombol mengirim kata "Kembali", kita paksa ubah jadi "Dikembalikan" agar seragam
if ($status_baru == "Kembali") {
    $status_baru = "Dikembalikan";
}

$sql = "SELECT id_alat, jumlah_pinjam, status FROM peminjaman WHERE id_peminjaman='$id_pinjam'";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) == 0){
    die("Data peminjaman tidak ditemukan");
}

$data = mysqli_fetch_assoc($query);
$id_alat     = $data['id_alat'];
$jumlah      = $data['jumlah_pinjam'];
$status_lama = $data['status']; 

if ($status_lama == $status_baru) {
    die("<script>alert('Status transaksi ini sudah $status_baru!'); window.history.back();</script>");
}

if ($status_baru == "Disetujui") {
    if ($status_lama == "Pending" || $status_lama == "Ditolak") {
        mysqli_query($conn, "UPDATE daftar_alat SET jumlah_unit = jumlah_unit - $jumlah WHERE id = '$id_alat'");
    }

} else if ($status_baru == "Ditolak") {
    if ($status_lama == "Disetujui") {
        mysqli_query($conn, "UPDATE daftar_alat SET jumlah_unit = jumlah_unit + $jumlah WHERE id = '$id_alat'");
    }
} else if ($status_baru == "Dikembalikan") {
    // Logika pengembalian barang (Menerima status lama 'Disetujui' atau 'Kembali' buat jaga-jaga)
    if ($status_lama == "Disetujui" || $status_lama == "Kembali") {
        mysqli_query($conn, "UPDATE daftar_alat SET jumlah_unit = jumlah_unit + $jumlah WHERE id = '$id_alat'");
    } else {
        die("<script>alert('Error: Transaksi belum disetujui, tidak bisa langsung dikembalikan!'); window.history.back();</script>");
    }
}

// Eksekusi update ke database peminjaman menggunakan kata "Dikembalikan"
mysqli_query(
    $conn,
    "UPDATE peminjaman 
     SET status = '$status_baru' 
     WHERE id_peminjaman = '$id_pinjam'"
);

echo "
<script>
alert('Status berhasil diperbarui dari $status_lama menjadi $status_baru');
window.location='data_pemijaman.php';
</script>
";
?>