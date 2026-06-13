<?php 
include '../koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM daftar_alat");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background: rgba(59, 130, 246, 0.5); border-left: 4px solid #fff; }
        .table thead th { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            color: #6b7280; 
            background-color: #f9fafb;
            border: none;
            padding: 1rem 1.5rem;
        }
        .table tbody td { 
            vertical-align: middle; 
            padding: 1.25rem 1.5rem; 
            border-color: #f3f4f6;
            color: #374151;
        }
        .action-btn {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-edit { background-color: #eff6ff; color: #2563eb; }
        .btn-edit:hover { background-color: #2563eb; color: white; }
        .btn-delete { background-color: #fef2f2; color: #dc2626; }
        .btn-delete:hover { background-color: #dc2626; color: white; }
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
                <a href="barang_admin.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-xl transition">
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
                    <h1 class="text-3xl font-bold text-slate-800">Manajemen Alat</h1>
                    <p class="text-slate-500 text-sm">Daftar inventaris alat laboratorium yang tersedia.</p>
                </div>
                <a href="tambah_alat.php" class="flex items-center space-x-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-200 transition transform active:scale-95">
                    <i class="fas fa-plus text-sm"></i> <span class="font-semibold">Tambah Alat</span>
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-hover m-0 w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="text-center w-20">ID</th>
                                <th>Informasi Barang</th>
                                <th class="text-center">Stok Unit</th>
                                <th class="text-center">Lokasi</th>
                                <th class="text-end px-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php while ($row = mysqli_fetch_array($query)) { ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="text-center">
                                    <span class="text-xs font-bold bg-slate-100 text-slate-500 py-1 px-2 rounded">
                                        #<?php echo $row['id']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800 text-base"><?php echo $row['nama_alat']; ?></div>
                                    <div class="text-[11px] text-slate-400 font-medium italic">Laboratorium Teknik</div>
                                </td>
                                <td class="text-center">
                                    <span class="font-semibold text-slate-700"><?php echo $row['jumlah_unit'] ?? '0'; ?></span>
                                    <small class="text-slate-400 ml-1 font-medium">Unit</small>
                                </td>
                                <td class="text-center">
                                    <div class="text-xs text-slate-600 bg-emerald-50 text-emerald-700 px-2 py-1 rounded-md inline-block">
                                        <i class="fas fa-map-marker-alt mr-1"></i> Ruang Lab 01
                                    </div>
                                </td>
                                <td class="text-end px-6">
                                    <div class="flex justify-end space-x-2">
                                        <a href="edit_alat.php?id=<?php echo $row['id']; ?>" 
                                           class="action-btn btn-edit shadow-sm" title="Edit Alat">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>
                                        
                                        <a href="hapus_alat.php?id=<?php echo $row['id']; ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus alat ini?')" 
                                           class="action-btn btn-delete shadow-sm" title="Hapus Alat">
                                            <i class="fas fa-trash text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-slate-50 p-4 border-t border-slate-100 flex justify-between items-center text-[11px] text-slate-400 font-medium">
                    <div>Menampilkan semua alat yang terdaftar dalam sistem.</div>
                    <div class="flex items-center">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
                        Database Terhubung
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>