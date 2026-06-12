<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nama_user'])) {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['id'] ?? null;
$nama_session = $_SESSION['nama_user'];

if ($id_user) {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'");
} else {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE nama_lengkap = '$nama_session' LIMIT 1");
}

$user = mysqli_fetch_assoc($query);

if (isset($_POST['update_profile'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $npm = mysqli_real_escape_string($conn, $_POST['npm']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    
    $update_query = "UPDATE users SET nama_lengkap='$nama_lengkap', username='$username', npm='$npm', jurusan='$jurusan' WHERE id='{$user['id']}'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['nama_user'] = $nama_lengkap;
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='profile.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui profil!');</script>";
    }
}

if (isset($_POST['update_password'])) {
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $update_pass = "UPDATE users SET password='$password_baru' WHERE id='{$user['id']}'";
    if (mysqli_query($conn, $update_pass)) {
        echo "<script>alert('Password berhasil diubah!'); window.location.href='profile.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal mengubah password!');</script>";
    }
}

$is_admin = ($user['role'] == 'admin');
$back_link = $is_admin ? "admin/dashboard_admin.php" : "dashboard.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Unsika Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }
        .bg-unsika { background-color: #800020; }
        .text-unsika { color: #800020; }
        .btn-unsika { background-color: #800020; color: white; border: none; transition: 0.3s; }
        .btn-unsika:hover { background-color: #5C0011; color: white; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

<div class="container max-w-4xl">
    <div class="mb-6 flex items-center">
        <a href="<?= $back_link ?>" class="text-slate-500 hover:text-slate-800 transition flex items-center bg-white px-4 py-2 rounded-full shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden flex flex-col md:flex-row">
        <!-- Sidebar Profile -->
        <div class="md:w-1/3 bg-unsika text-white p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="w-32 h-32 bg-white rounded-full p-1 mb-4 mx-auto shadow-xl">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap']) ?>&background=random&size=128" alt="Profile" class="w-full h-full rounded-full object-cover">
                </div>
                <h2 class="text-2xl font-bold tracking-tight mb-1"><?= $user['nama_lengkap'] ?></h2>
                <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">
                    <?= $user['role'] ?>
                </span>
                <p class="text-sm text-white/80"><i class="fas fa-envelope mr-2"></i><?= $user['username'] ?></p>
            </div>
        </div>

        <!-- Forms -->
        <div class="md:w-2/3 p-8 bg-white">
            <h3 class="text-xl font-bold text-slate-800 border-b pb-3 mb-6">Pengaturan Profil</h3>
            
            <form action="" method="POST" class="mb-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm font-medium text-slate-700" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email / Username</label>
                        <input type="email" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm font-medium text-slate-700" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">NPM / NIDN</label>
                        <input type="text" name="npm" value="<?= htmlspecialchars($user['npm']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm font-medium text-slate-700" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jurusan</label>
                        <input type="text" name="jurusan" value="<?= htmlspecialchars($user['jurusan']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm font-medium text-slate-700" required>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" name="update_profile" class="btn-unsika px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:-translate-y-0.5 transform transition">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

            <h3 class="text-xl font-bold text-slate-800 border-b pb-3 mb-6">Ubah Kata Sandi</h3>
            
            <form action="" method="POST">
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password_baru" placeholder="Masukkan sandi baru..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition text-sm font-medium text-slate-700" required>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" name="update_password" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-slate-200 hover:-translate-y-0.5 transform transition">
                        <i class="fas fa-key mr-2"></i> Perbarui Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
