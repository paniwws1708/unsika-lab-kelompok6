<?php include '../koneksi.php'; 
$sql = "SELECT peminjaman.*, daftar_alat.nama_alat FROM peminjaman JOIN daftar_alat ON peminjaman.id_alat = daftar_alat.id WHERE 1=1";

if (isset($_GET['dari']) && !empty($_GET['dari'])) {
    $dari = mysqli_real_escape_string($conn, $_GET['dari']);
    $sql .= " AND tgl_pinjam >= '$dari'";
}
if (isset($_GET['sampai']) && !empty($_GET['sampai'])) {
    $sampai = mysqli_real_escape_string($conn, $_GET['sampai']);
    $sql .= " AND tgl_pinjam <= '$sampai'";
}
$sql .= " ORDER BY tgl_pinjam DESC";

$query_laporan = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background: rgba(59, 130, 246, 0.5); border-left: 4px solid #fff; }
        
        /* Table Styling */
        .table thead th { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            color: #f8fafc; 
            background-color: #1e293b;
            border: none;
            padding: 1.25rem 1rem;
        }
        .table tbody td { 
            vertical-align: middle; 
            padding: 1rem; 
            border-color: #f1f5f9;
            color: #334155;
        }
        
        /* Print Optimization */
        @media print {
            aside, .filter-box, .btn-print { display: none !important; }
            main { padding: 0 !important; }
            .bg-slate-50 { background-color: white !important; }
            .shadow-sm { shadow: none !important; }
        }
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
                <a href="data_pemijaman.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl transition">
                    <i class="fas fa-exchange-alt w-5"></i> <span>Peminjaman</span>
                </a>
                <a href="barang_admin.php" class="sidebar-link flex items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
                    <i class="fas fa-box w-5"></i> <span>Barang</span>
                </a>
                <a href="users_admin.php" class="sidebar-link flex items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
                    <i class="fas fa-users w-5"></i> <span>Users</span>
                </a>
                <a href="laporan_admin.php" class="sidebar-link flex active items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
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
                    <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Laporan Aktivitas</h1>
                    <p class="text-slate-500 text-sm">Rekapitulasi riwayat peminjaman alat laboratorium.</p>
                </div>
                <button onclick="window.print()" class="btn-print bg-slate-800 text-white px-6 py-2.5 rounded-xl hover:bg-slate-900 shadow-md shadow-slate-200 transition flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
            </div>

            <div class="filter-box bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8">
                <form action="" method="GET" class="flex flex-wrap gap-6 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Dari Tanggal</label>
                        <input type="date" name="dari" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="table table-hover m-0 w-full text-left">
                    <thead>
                        <tr>
                            <th class="p-4">Tanggal (Pinjam - Kembali)</th>
                            <th class="p-4">Mahasiswa</th>
                            <th class="p-4">Alat</th>
                            <th class="p-4 text-center">Jumlah</th>
                            <th class="p-4 text-end px-6">Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php while($row = mysqli_fetch_array($query_laporan)) { ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded mb-1 inline-block">
                                    <i class="far fa-calendar-alt mr-1"></i> P: <?php echo $row['tgl_pinjam']; ?>
                                </div>
                                <div class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded inline-block mt-1">
                                    <i class="far fa-calendar-check mr-1"></i> K: <?php echo $row['tgl_kembali']; ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-slate-700"><?php echo $row['nama_mahasiswa']; ?></div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-tighter">NPM: <?php echo $row['npm']; ?></div>
                            </td>
                            <td class="p-4">
                                <div class="text-sm font-medium text-slate-800"><?php echo $row['nama_alat']; ?></div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-tighter">ID: <?php echo $row['id_alat']; ?></div>
                            </td>
                            <td class="p-4 text-center font-bold text-slate-600"><?php echo $row['jumlah_pinjam']; ?></td>
                            <td class="p-4 text-end px-6">
                                <?php 
                                    $status = $row['status'];
                                    if($status == 'Disetujui'): ?>
                                    <span class="inline-flex items-center text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle mr-1.5"></i> Disetujui
                                    </span>
                                <?php elseif($status == 'Ditolak'): ?>
                                    <span class="inline-flex items-center text-xs font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-times-circle mr-1.5"></i> Ditolak
                                    </span>
                                <?php elseif($status == 'Selesai'): ?>
                                    <span class="inline-flex items-center text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-check-double mr-1.5"></i> Selesai
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1.5"></i> Menunggu
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <div class="hidden print:block p-8 text-center border-t mt-8">
                    <p class="text-sm">Dicetak secara otomatis oleh Sistem Manajemen Lab UNSIKA</p>
                    <p class="text-xs text-gray-400">Tanggal cetak: <?php echo date('d-m-Y H:i'); ?></p>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>