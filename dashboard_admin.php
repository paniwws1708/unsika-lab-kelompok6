<?php
include '../koneksi.php'; 

$queryTotal = mysqli_query($conn, "SELECT COUNT(*) as total FROM daftar_alat");
$dataTotal = mysqli_fetch_assoc($queryTotal);
$totalBarang = $dataTotal['total'] ?? 0;

$queryTersedia = mysqli_query($conn, "SELECT SUM(jumlah_unit) as tersedia FROM daftar_alat");
$dataTersedia = mysqli_fetch_assoc($queryTersedia);
$barangTersedia = $dataTersedia['tersedia'] ?? 0;

$queryPinjam = mysqli_query($conn, "SELECT COUNT(*) as total_pinjam FROM peminjaman");
$dataPinjam = mysqli_fetch_assoc($queryPinjam);
$totalPinjam = $dataPinjam['total_pinjam'] ?? 0; // Ini yang bikin error tadi karena belum dibuat

$queryMhs = mysqli_query($conn, "SELECT COUNT(*) as total_mhs FROM users WHERE role = 'mahasiswa'");
$dataMhs = mysqli_fetch_assoc($queryMhs);
$totalMhs = $dataMhs['total_mhs'] ?? 0;

$queryTerbaru = mysqli_query($conn, "SELECT peminjaman.*, daftar_alat.nama_alat FROM peminjaman JOIN daftar_alat ON peminjaman.id_alat = daftar_alat.id ORDER BY id_peminjaman DESC LIMIT 5");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UNSIKA</title>
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
                <a href="dashboard_admin.php" class="sidebar-link flex active items-center space-x-3 p-3 rounded-xl hover:bg-blue-700/30 transition">
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
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
                    <p class="text-gray-500 text-sm">Selamat datang kembali, Admin!</p>
                </div>
                <div class="flex items-center space-x-4 relative">
                    <!-- Notification Bell -->
                    <div class="relative">
                        <button id="notificationBtn" class="focus:outline-none flex items-center justify-center p-2 rounded-full hover:bg-gray-100 transition">
                            <i class="far fa-bell text-xl text-gray-400"></i>
                            <?php 
                            $queryCount = mysqli_query($conn, "SELECT COUNT(*) as count FROM peminjaman WHERE status = 'Menunggu'");
                            $notifCount = mysqli_fetch_assoc($queryCount)['count'];
                            if ($notifCount > 0): 
                            ?>
                            <span class="absolute top-1 right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white"><?= $notifCount ?></span>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden transform transition-all">
                            <div class="bg-slate-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-gray-700">Notifikasi</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <?php
                                $queryNotif = mysqli_query($conn, "SELECT peminjaman.*, daftar_alat.nama_alat FROM peminjaman JOIN daftar_alat ON peminjaman.id_alat = daftar_alat.id ORDER BY id_peminjaman DESC LIMIT 5");
                                if (mysqli_num_rows($queryNotif) > 0) {
                                    while ($notif = mysqli_fetch_assoc($queryNotif)) {
                                        $status = $notif['status'];
                                        $icon = 'fa-info-circle';
                                        $colorClass = 'bg-purple-100 text-purple-600';
                                        $title = 'System Update';
                                        $desc = "Pembaruan status sistem.";
                                        
                                        if ($status == 'Menunggu') {
                                            $icon = 'fa-exchange-alt';
                                            $colorClass = 'bg-blue-100 text-blue-600';
                                            $title = 'Permintaan Peminjaman Baru';
                                            $desc = "Ada permohonan peminjaman alat baru: {$notif['nama_alat']}.";
                                        } else if ($status == 'Disetujui') {
                                            $icon = 'fa-check';
                                            $colorClass = 'bg-emerald-100 text-emerald-600';
                                            $title = 'Peminjaman Disetujui';
                                            $desc = "Sistem telah menyetujui peminjaman: {$notif['nama_alat']}.";
                                        } else if ($status == 'Ditolak') {
                                            $icon = 'fa-times';
                                            $colorClass = 'bg-red-100 text-red-600';
                                            $title = 'Peminjaman Ditolak';
                                            $desc = "Peminjaman ditolak: {$notif['nama_alat']}.";
                                        } else if ($status == 'Selesai') {
                                            $icon = 'fa-check-double';
                                            $colorClass = 'bg-gray-100 text-gray-600';
                                            $title = 'Peminjaman Selesai';
                                            $desc = "Peminjaman telah selesai: {$notif['nama_alat']}.";
                                        }
                                ?>
                                <a href="#" class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition">
                                    <div class="flex items-start">
                                        <div class="<?= $colorClass ?> rounded-full p-2 mr-3 flex-shrink-0">
                                            <i class="fas <?= $icon ?> text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-800 font-medium"><?= $title ?></p>
                                            <p class="text-xs text-gray-500 mt-0.5"><?= $desc ?></p>
                                            <p class="text-[10px] text-gray-400 mt-1"><?= date('d M Y', strtotime($notif['tgl_pinjam'])) ?></p>
                                        </div>
                                    </div>
                                </a>
                                <?php
                                    }
                                } else {
                                ?>
                                <div class="px-4 py-6 text-center">
                                    <div class="text-gray-400 mb-2"><i class="far fa-bell-slash text-2xl"></i></div>
                                    <p class="text-gray-500 text-sm">No notifications available</p>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <button id="profileBtn" class="focus:outline-none flex items-center p-1 rounded-full border-2 border-transparent hover:border-blue-500 transition">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_user'] ?? 'Admin') ?>" class="w-10 h-10 rounded-full border border-gray-200">
                        </button>

                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden transform transition-all">
                            <div class="px-4 py-3 border-b border-gray-100 bg-slate-50">
                                <p class="text-sm font-bold text-gray-800"><?= htmlspecialchars($_SESSION['nama_user'] ?? 'Admin') ?></p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <div class="py-1">
                                <a href="../profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition flex items-center">
                                    <i class="far fa-user w-5 mr-2 text-center"></i> Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition flex items-center">
                                    <i class="fas fa-cog w-5 mr-2 text-center"></i> Settings
                                </a>
                            </div>
                            <div class="border-t border-gray-100 py-1">
                                <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center">
                                    <i class="fas fa-sign-out-alt w-5 mr-2 text-center"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl shadow-sm flex items-center justify-between border-l-4 border-blue-500 hover:shadow-md transition">
                    <div class="card-stat">
                        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Barang</span>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $totalBarang; ?></h3>
                    </div>
                    <div class="bg-blue-100 w-14 h-14 rounded-2xl flex items-center justify-center text-blue-600 text-2xl shadow-inner"><i class="fas fa-boxes"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm flex items-center justify-between border-l-4 border-emerald-500 hover:shadow-md transition">
                    <div class="card-stat">
                        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Barang Tersedia</span>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $barangTersedia; ?></h3>
                    </div>
                    <div class="bg-emerald-100 w-14 h-14 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl shadow-inner"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm flex items-center justify-between border-l-4 border-amber-500 hover:shadow-md transition">
                    <div class="card-stat">
                        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Peminjaman</span>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $totalPinjam; ?></h3>
                    </div>
                    <div class="bg-amber-100 w-14 h-14 rounded-2xl flex items-center justify-center text-amber-600 text-2xl shadow-inner"><i class="fas fa-exchange-alt"></i></div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm flex items-center justify-between border-l-4 border-purple-500 hover:shadow-md transition">
                    <div class="card-stat">
                        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Mahasiswa</span>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $totalMhs; ?></h3>
                    </div>
                    <div class="bg-purple-100 w-14 h-14 rounded-2xl flex items-center justify-center text-purple-600 text-2xl shadow-inner"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm">
                    <h4 class="font-bold mb-4">Peminjaman 7 Hari Terakhir</h4>
                    <canvas id="lineChart" height="150"></canvas>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm">
                    <h4 class="font-bold mb-4 text-center">Status Peminjaman</h4>
                    <canvas id="donutChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm overflow-hidden p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h4 class="font-bold">Data Peminjaman Terbaru</h4>
                    <a href="data_pemijaman.php" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm inline-block transition hover:bg-blue-700">Lihat Semua</a>
                </div>
                <table class="w-full text-left block sm:table">
                    <thead class="hidden sm:table-header-group">
                        <tr class="text-gray-400 border-b">
                            <th class="pb-4 font-medium">Peminjam</th>
                            <th class="pb-4 font-medium">Barang</th>
                            <th class="pb-4 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y divide-gray-100 block sm:table-row-group">
                        <?php while($row = mysqli_fetch_assoc($queryTerbaru)): ?>
                        <tr class="hover:bg-gray-50 transition flex flex-col sm:table-row py-4 sm:py-0">
                            <td class="py-2 sm:py-4 block sm:table-cell break-words">
                                <span class="block sm:hidden text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Peminjam</span>
                                <div class="font-semibold text-slate-800"><?= $row['nama_mahasiswa'] ?></div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-tighter">NPM: <?= $row['npm'] ?></div>
                            </td>
                            <td class="py-2 sm:py-4 block sm:table-cell break-words">
                                <span class="block sm:hidden text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Barang</span>
                                <div class="font-semibold text-slate-700"><?= $row['nama_alat'] ?></div>
                                <div class="text-[10px] text-slate-400">Qty: <?= $row['jumlah_pinjam'] ?></div>
                            </td>
                            <td class="py-2 sm:py-4 block sm:table-cell break-words">
                                <span class="block sm:hidden text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status</span>
                                <div>
                                    <?php 
                                        $status = $row['status'];
                                        if($status == 'Disetujui'): ?>
                                        <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Disetujui</span>
                                    <?php elseif($status == 'Ditolak'): ?>
                                        <span class="bg-rose-100 text-rose-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Ditolak</span>
                                    <?php elseif($status == 'Selesai'): ?>
                                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                                    <?php else: ?>
                                        <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Menunggu</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($queryTerbaru) == 0): ?>
                        <tr class="flex flex-col sm:table-row">
                            <td colspan="3" class="text-center py-6 text-gray-400 italic block sm:table-cell">Belum ada data peminjaman terbaru.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const ctxLine = document.getElementById('lineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['18 Mei', '19 Mei', '20 Mei', '21 Mei', '22 Mei', '23 Mei', '24 Mei'],
                datasets: [{
                    label: 'Peminjaman',
                    data: [5, 8, 6, 12, 10, 8, 7],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            }
        });

        const ctxDonut = document.getElementById('donutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Disetujui', 'Ditolak', 'Selesai'],
                datasets: [{
                    data: [8, 15, 3, 4],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#ef4444', '#10b981']
                }]
            },
            options: { cutout: '70%' }
        });

        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
            profileDropdown.classList.add('hidden');
        });

        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
            notificationDropdown.classList.add('hidden');
        });

        window.addEventListener('click', () => {
            notificationDropdown.classList.add('hidden');
            profileDropdown.classList.add('hidden');
        });

        notificationDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        profileDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    </script>
</body>
</html>