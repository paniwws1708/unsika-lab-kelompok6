<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM daftar_alat WHERE id = '$id'");
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='barang_admin.php';</script>";
        exit;
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_alat'];
    $lokasi = $_POST['lokasi'];
    $jumlah = $_POST['jumlah_unit'];
    $status = $_POST['status'];

    $query = "UPDATE daftar_alat SET 
                nama_alat='$nama', 
                lokasi='$lokasi', 
                jumlah_unit='$jumlah', 
                status='$status' 
              WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Alat Berhasil Diperbarui!'); window.location.href='barang_admin.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 p-6 text-white text-center">
            <h2 class="text-2xl font-bold">Edit Data Alat</h2>
            <p class="text-blue-100 text-sm mt-1">Perbarui informasi aset inventaris</p>
        </div>

        <form action="" method="POST" class="p-8 space-y-5">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Alat</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-tools"></i>
                    </span>
                    <input type="text" name="nama_alat" value="<?php echo $data['nama_alat']; ?>" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <input type="text" name="lokasi" value="<?php echo $data['lokasi']; ?>" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Unit</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-layer-group"></i>
                    </span>
                    <input type="number" name="jumlah_unit" value="<?php echo $data['jumlah_unit']; ?>" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status Alat</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <select name="status" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none transition bg-white text-gray-700">
                        <option value="Tersedia" <?php echo ($data['status'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="Dipinjam" <?php echo ($data['status'] == 'Dipinjam') ? 'selected' : ''; ?>>Dipinjam</option>
                        <option value="Rusak" <?php echo ($data['status'] == 'Rusak') ? 'selected' : ''; ?>>Rusak</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 space-y-3">
                <button type="submit" name="update" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition transform active:scale-95">
                    <i class="fas fa-save mr-2"></i> Perbarui Data
                </button>
                
                <a href="barang_admin.php" 
                    class="block text-center text-sm text-gray-500 hover:text-gray-800 transition py-1">
                    <i class="fas fa-arrow-left mr-1"></i> Batal dan Kembali
                </a>
            </div>
        </form>
    </div>

</body>
</html>