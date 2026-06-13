<?php include '../koneksi.php'; 
$query_user = mysqli_query($conn, "SELECT * FROM users ORDER BY role DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Admin Control Panel</title>
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
            color: #64748b; 
            background-color: #f8fafc;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        .table tbody td { 
            vertical-align: middle; 
            padding: 1.25rem 1.5rem; 
            border-color: #f1f5f9;
        }
        .role-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            text-transform: uppercase;
        }
        .btn-action-user {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #f1f5f9;
            color: #64748b;
            transition: all 0.2s ease;
        }
        .btn-action-user:hover {
            background: #fee2e2;
            color: #ef4444;
            transform: scale(1.1);
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
                <p class="text-[10px] text-blue-300 font-medium mt-2">ADMIN CONTROL PANEL</p>
            </div>
            
            <nav class="space-y-2 flex-grow">
                <a href="dashboard_admin.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl hover:bg-blue-700/30 transition">
                    <i class="fas fa-th-large w-5 text-blue-200"></i> <span>Dashboard</span>
                </a>
                <a href="data_pemijaman.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl hover:bg-blue-700/30 transition">
                    <i class="fas fa-exchange-alt w-5 text-blue-200"></i> <span>Peminjaman</span>
                </a>
                <a href="barang_admin.php" class="sidebar-link flex items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
                    <i class="fas fa-box w-5 text-blue-200"></i> <span>Barang</span>
                </a>
                <a href="users_admin.php" class="sidebar-link active flex items-center space-x-3 p-3 rounded-xl transition">
                    <i class="fas fa-users w-5 text-white"></i> <span>Users</span>
                </a>
                <a href="laporan_admin.php" class="sidebar-link flex items-center space-x-3 hover:bg-blue-700/30 p-3 rounded-xl transition">
                    <i class="fas fa-file-alt w-5 text-blue-200"></i> <span>Laporan</span>
                </a>
            </nav>

            <div class="pt-6 border-t border-blue-800">
                <a href="../logout.php" class="flex items-center space-x-3 text-red-300 hover:text-red-100 p-3 transition">
                    <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
                </a>
            </div>
        </aside>

        <main class="flex-1 p-8">
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Daftar Pengguna</h1>
                    <p class="text-slate-500 text-sm mt-1">Kelola hak akses mahasiswa dan staf laboratorium.</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-white border px-3 py-1.5 rounded-lg shadow-sm">
                        Total: <?php echo mysqli_num_rows($query_user); ?> Pengguna
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <div class="flex gap-3">
                        <div class="relative flex-grow">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" placeholder="Cari berdasarkan NPM, NIDN, atau Nama..." 
                                   class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm text-sm">
                        </div>
                        <button class="bg-slate-800 hover:bg-slate-900 text-white px-8 py-3 rounded-2xl font-bold text-sm transition shadow-lg shadow-slate-200">
                            Cari
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-hover m-0">
                        <thead>
                            <tr>
                                <th>Identitas Pengguna</th>
                                <th>Nama Lengkap</th>
                                <th class="text-center">Hak Akses</th>
                                <th class="text-center">Status Akun</th>
                                <th class="text-end pr-8">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php while ($row = mysqli_fetch_array($query_user)) { ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs border border-blue-100 uppercase">
                                            <?php echo substr($row['nama_lengkap'], 0, 2); ?>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter"><?php echo ($row['role'] == 'admin') ? 'NIDN' : 'NPM'; ?></div>
                                            <div class="text-sm font-semibold text-slate-700"><?php echo $row['username']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800"><?php echo $row['nama_lengkap']; ?></div>
                                    <div class="text-[10px] text-slate-400 italic">Terdaftar pada sistem</div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $role = $row['role'];
                                        $roleStyle = ($role == 'admin') ? 'bg-indigo-100 text-indigo-700' : 'bg-blue-100 text-blue-700';
                                    ?>
                                    <span class="role-badge <?php echo $roleStyle; ?>">
                                        <?php echo $role; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                </td>
                                <td class="text-end pr-8">
                                    <a href="nonaktifkan_user.php?id=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menonaktifkan pengguna ini?')" 
                                       class="btn-action-user" title="Nonaktifkan User">
                                        <i class="fas fa-user-slash text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-widest mb-0">Laboratorium Terpadu UNSIKA &copy; 2026</p>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>