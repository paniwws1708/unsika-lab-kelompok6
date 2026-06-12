<?php
// Aktifkan error reporting agar tidak halaman putih
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php'; 

if (isset($_POST['pinjam'])) {
    // Tangkap data dengan pengaman
    $id_alat       = mysqli_real_escape_string($conn, $_POST['id_alat']);
    $nama_mhs      = mysqli_real_escape_string($conn, $_POST['nama_mahasiswa']);
    $npm           = mysqli_real_escape_string($conn, $_POST['npm']);
    $tgl_pinjam    = mysqli_real_escape_string($conn, $_POST['tgl_pinjam']);
    $tgl_kembali   = mysqli_real_escape_string($conn, $_POST['tgl_kembali']);
    
    // Ambil jumlah pinjam
    $jumlah_pinjam = mysqli_real_escape_string($conn, $_POST['jumlah_pinjam']); 

    // Cek apakah data kosong sebelum simpan
    if(empty($jumlah_pinjam)) {
        die("ERROR: Jumlah pinjam kosong! Periksa name='jumlah_pinjam' di file pinjam_alat.php");
    }

    // Proses Upload KTM
    $foto_ktm = "";
    if (isset($_FILES['foto_ktm']) && $_FILES['foto_ktm']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto_ktm']['error'] !== 0) {
            echo "<script>alert('Terjadi kesalahan saat upload (Error Code: " . $_FILES['foto_ktm']['error'] . "). File mungkin terlalu besar!'); window.history.back();</script>";
            exit();
        }

        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['foto_ktm']['name'];
        $file_size = $_FILES['foto_ktm']['size'];
        $file_tmp = $_FILES['foto_ktm']['tmp_name'];
        
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Cek ukuran file (Max 5MB)
        if ($file_size > 5 * 1024 * 1024) {
            echo "<script>alert('Ukuran file foto KTM maksimal 5MB!'); window.history.back();</script>";
            exit();
        }
        
        if (in_array($ext, $allowed_ext)) {
            $new_filename = uniqid('ktm_') . '.' . $ext;
            $upload_dir = 'uploads/ktm/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $foto_ktm = $upload_path;
            } else {
                echo "<script>alert('Gagal memindahkan file yang diupload!'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Format foto KTM tidak valid! Hanya JPG, PNG, JPEG yang diperbolehkan.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Foto KTM wajib diupload!'); window.history.back();</script>";
        exit();
    }

    // Cek apakah stok di gudang saat ini mencukupi
    $cek_stok = mysqli_query($conn, "SELECT jumlah_unit FROM daftar_alat WHERE id = '$id_alat'");
    $data_stok = mysqli_fetch_assoc($cek_stok);
    
    if ($data_stok['jumlah_unit'] < $jumlah_pinjam) {
        echo "<script>alert('Stok tidak mencukupi untuk diajukan!'); window.history.back();</script>";
        exit();
    }

    $query = "INSERT INTO peminjaman (id_alat, nama_mahasiswa, npm, jumlah_pinjam, tgl_pinjam, tgl_kembali, status, foto_ktm) 
              VALUES ('$id_alat', '$nama_mhs', '$npm', '$jumlah_pinjam', '$tgl_pinjam', '$tgl_kembali', 'Pending', '$foto_ktm')";
    
    if (mysqli_query($conn, $query)) {
       
        echo "<script>alert('Pengajuan peminjaman berhasil dikirim! Status saat ini: Pending (Menunggu Persetujuan Admin).'); window.location.href='alat_tersedia.php';</script>";
    } else {
        die("Gagal Simpan Database: " . mysqli_error($conn));
    }
} else {
    echo "Tombol pinjam belum diklik / data tidak terdeteksi.";
}
?>