<?php
include '../koneksi.php'; 

$sql = "SELECT peminjaman.*, daftar_alat.nama_alat 
        FROM peminjaman 
        JOIN daftar_alat ON peminjaman.id_alat = daftar_alat.id 
        ORDER BY id_peminjaman DESC";

$query = mysqli_query($conn, $sql);

if (!$query) {
    die("Fatal Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background: rgba(59, 130, 246, 0.5); border-left: 4px solid #fff; }
        .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border: none; }
        .table tbody td { vertical-align: middle; padding: 1rem 0.75rem; border-color: #f3f4f6; }
        .status-badge { font-size: 0.7rem; font-weight: 600; padding: 0.4rem 0.8rem; border-radius: 9999px; }
    </style>
</head>
<body class="bg-slate-50">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#002B6B] text-white p-6 hidden lg:flex flex-col sticky top-0 h-screen">
            <div class="mb-10 text-center flex flex-col items-center">
                <div class="flex items-center justify-center space-x-3">
                    <img src="../image/logo.png" alt="UNSIKA Logo" class="w-10 h-10 drop-shadow-md">
                    <h1 class="text-xl font-bold uppercase tracking-widest mt-1">Lab UNSIKA</h1>
                </div>
                <p class="text-[10px] text-blue-300 mt-2">ADMIN CONTROL PANEL</p>
            </div>
            
            <nav class="space-y-2 flex-grow">
                <a href="dashboard_admin.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl hover:bg-blue-700/30 transition">
                    <i class="fas fa-th-large w-5"></i> <span>Dashboard</span>
                </a>
                <a href="data_pemijaman.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-xl transition">
                    <i class="fas fa-exchange-alt w-5"></i> <span>Peminjaman</span>
                </a>
                <a href="barang_admin.php" class="sidebar-link flex items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
                    <i class="fas fa-box w-5"></i> <span>Barang</span>
                </a>
                <a href="users_admin.php" class="sidebar-link flex items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
                    <i class="fas fa-users w-5"></i> <span>Users</span>
                </a>
                <a href="laporan_admin.php" class="sidebar-link flex items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
                    <i class="fas fa-file-alt w-5"></i> <span>Laporan</span>
                </a>
            </nav>

            <div class="pt-6 border-t border-blue-800">
                <a href="../logout.php" class="flex items-center space-x-3 text-red-300 hover:text-red-100 p-3 transition">
                    <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
                </a>
            </div>
        </aside>

        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Manajemen Peminjaman</h2>
                    <p class="text-slate-500 text-sm">Kelola semua permintaan peminjaman alat mahasiswa di sini.</p>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-white border p-2 rounded-lg text-slate-600 hover:bg-slate-50 shadow-sm transition">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <a href="dashboard_admin.php" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700 shadow-md transition">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-700 m-0">Permintaan Terbaru</h3>
                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-md font-medium">Real-time Data</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-hover m-0">
                        <thead>
                            <tr>
                                <th class="px-6">Tgl Pinjam</th>
                                <th>Mahasiswa</th>
                                <th>Informasi Alat</th>
                                <th class="text-center">Jumlah</th> 
                                <th>Status</th>
                                <th class="text-right px-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
    <tr class="hover:bg-slate-50/80 transition">
        <td class="px-6">
            <div class="text-xs font-medium text-slate-400"><?= date('D, d M Y', strtotime($row['tgl_pinjam'])); ?></div>
        </td>
        <td>
            <div class="font-bold text-slate-800"><?= $row['nama_mahasiswa']; ?></div>
            <div class="text-[10px] text-slate-400 uppercase tracking-tighter mb-1">NPM: <?= $row['npm']; ?></div>
            <?php 
                $ktmPath = "../" . $row['foto_ktm'];
                if (!empty($row['foto_ktm']) && file_exists($ktmPath)): 
            ?>
                <a href="<?= $ktmPath; ?>" target="_blank" class="text-[10px] bg-blue-100 text-blue-600 px-2 py-1 rounded inline-block hover:bg-blue-200 transition">
                    <i class="fas fa-id-card mr-1"></i> Lihat KTM
                </a>
            <?php else: ?>
                <span class="text-[10px] bg-slate-100 text-slate-400 px-2 py-1 rounded inline-block italic">KTM Tidak Ada / Hilang</span>
            <?php endif; ?>
        </td>
        <td>
            <div class="text-sm font-semibold text-blue-600"><?= $row['nama_alat']; ?></div>
            <div class="text-[10px] text-slate-400">ID Unit: <?= $row['id_alat']; ?></div>
        </td>
        <td class="text-center">
            <span class="font-bold text-slate-700"><?= $row['jumlah_pinjam']; ?></span> <small class="text-slate-400">Pcs</small>
        </td>
        <td>
            <?php 
                $status = $row['status'] ?: 'Pending';
                
                // Menentukan warna badge status di tabel
                if ($status == 'Disetujui') {
                    $colorClass = 'bg-emerald-100 text-emerald-600';
                } else if ($status == 'Ditolak') {
                    $colorClass = 'bg-rose-100 text-rose-600';
                } else if ($status == 'Kembali' || $status == 'Dikembalikan') {
                    $colorClass = 'bg-blue-100 text-blue-600';
                } else {
                    $colorClass = 'bg-amber-100 text-amber-600';
                }
            ?>
            <span class="status-badge <?= $colorClass; ?>">
                <i class="fas fa-circle text-[6px] mr-1 align-middle"></i> <?= $status; ?>
            </span>
        </td>
        <td class="text-right px-6">
            <?php if ($row['status'] == 'Pending' || empty($row['status'])) : ?>
                <div class="flex justify-end space-x-2">
                    <a href="update_status.php?id=<?= $row['id_peminjaman']; ?>&status=Disetujui&id_alat=<?= $row['id_alat']; ?>" class="bg-emerald-600 text-white text-xs px-3 py-1.5 rounded hover:bg-emerald-700 transition font-medium shadow-sm">
                        Setuju
                    </a>
                    <a href="update_status.php?id=<?= $row['id_peminjaman']; ?>&status=Ditolak" class="bg-rose-600 text-white text-xs px-3 py-1.5 rounded hover:bg-rose-700 transition font-medium shadow-sm">
                        Tolak
                    </a>
                </div>

            <?php elseif ($row['status'] == 'Disetujui') : ?>
                <div class="flex justify-end">
                    <a href="update_status.php?id=<?= $row['id_peminjaman']; ?>&status=Dikembalikan&id_alat=<?= $row['id_alat']; ?>" 
                       class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 transition font-medium shadow-sm"
                       onclick="return confirm('Konfirmasi bahwa alat ini sudah dikembalikan secara fisik?');">
                       <i class="fas fa-undo mr-1 text-[10px]"></i> Kembalikan
                    </a>
                </div>

            <?php else : ?>
                <span class="text-[10px] bg-slate-100 text-slate-400 px-2 py-1 rounded italic">
                    Sudah Diproses
                </span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>
                    </table>
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 mb-0">Menampilkan seluruh riwayat transaksi laboratorium.</p>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></