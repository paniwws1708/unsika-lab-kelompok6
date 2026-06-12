<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['nama_user'])) {
    header("Location: index.php");
    exit();
}

$query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM daftar_alat");
$data_total = mysqli_fetch_assoc($query_total);
$total_alat = $data_total['total'];

$query_lab = mysqli_query($conn, "SELECT COUNT(DISTINCT lokasi) as total_lab FROM daftar_alat");
$data_lab = mysqli_fetch_assoc($query_lab);
$jumlah_lab = $data_lab['total_lab'];

$query_aktif = mysqli_query($conn, "SELECT COUNT(*) as total_aktif FROM peminjaman WHERE status = 'Disetujui'");
$data_aktif = mysqli_fetch_assoc($query_aktif);
$peminjaman_aktif = $data_aktif['total_aktif'];

$nama = $_SESSION['nama_user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Alat Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .img-container {
            height: 180px;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-alat {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .card-alat:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        }

         .btn-pinjam{
            width:100%;
            height:45px;
            border-radius:30px;
            font-weight:500;
            border:2px solid #800020;
            background:transparent;
            color:#800020;
            transition:0.3s;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            cursor:pointer;
            font-size:16px;
        }

        .btn-pinjam:hover{
            background:#800020;
            color:white;
            text-decoration:none;
        }

        .btn-pinjam-home{
            width:100%;
            height:45px;
            border-radius:999px;
            font-size:16px;
            font-weight:500;
            padding:0;
        }

        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        .navbar-brand img {
            margin-right: 12px;
        }
        .nav-link {
            position: relative;
            color: #333 !important;
            font-weight: 500;
            padding: 8px 15px !important;
            margin: 0 5px;
            transition: color 0.3s;
        }
        .nav-link:hover, .nav-link.active-link {
            color: #C8102E !important;
        }
        .nav-link.active-link::after {
            content: "";
            position: absolute;
            left: 15%;
            bottom: 0;
            width: 70%;
            height: 3px;
            background-color: #C8102E;
            border-radius: 3px;
        }

        .hero-section {
            background-color: var(--soft-pink);
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 0 0 50px 50px;
        }

        .btn-login {
            border-radius: 8px;
            font-weight: 250;
            height: 40px;
            width: 125px;
        }

        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.8s ease-in-out;
        }

        .main-content {
            opacity: 0;
            transition: opacity 1s ease-in;
        }

        #progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            width: 0%;
            background: linear-gradient(to right, #1a237e, #007bff);
            box-shadow: 0 0 10px rgba(26, 35, 126, 0.5);
            transition: width 3s cubic-bezier(0.1, 0.5, 0.5, 1);
        }

        .stats-section {
            display: flex;
            justify-content: space-around;
            padding: 50px 10%;
            background: #f8f9fa;
            text-align: center;
        }
        .stat-card h3 { color: #1a237e; font-size: 2.5rem; margin-bottom: 5px; }
        .stat-card p { color: #666; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; }

        .steps-container {
            position: relative;
            display: flex;
            align-items: flex-start;
        }

        .steps-timeline {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 40px;
        }

        .steps-timeline::before {
            content: "";
            position: absolute;
            top: 105px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .step-item {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 40%;
        }

        .step-icon-wrap {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.8rem;
            color: white;
            border: 5px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .step-number {
            display: inline-block;
            background: #fff;
            border: 1px solid #333;
            border-radius: 20px;
            padding: 2px 15px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        :root { --unsika-red: #800020; --unsika-dark: #5C0011; --soft-pink: #F7F2F3; }
        
        .active-link { color: var(--unsika-red) !important; font-weight: bold; }

        .search-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .stats-card {
            border: none;
            border-radius: 20px;
            transition: transform 0.3s;
            margin-top: -50px;
        }
        .stats-card:hover { transform: translateY(-5px); }

        .lab-card {
            transition: all 0.3s ease;
            border-radius: 20px;
        }

        .lab-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
            background-color: #ffffff;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            transition: transform 0.3s ease;
        }

        .lab-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .lab-card h6 {
            transition: color 0.3s;
        }
        
        .lab-card:hover h6 {
            color: #dc3545 !important;
        }

        :root {
            --bg-roadmap: #fff5f5;
        }

        .roadmap-wrapper {
            position: relative;
            background-color: var(--bg-roadmap);
            border-radius: 100px;
            padding: 30px 10px;
            margin-top: 40px;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.03);
        }

        .roadmap-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 2;
        }

        .roadmap-item {
            text-align: center;
            position: relative;
            flex: 1;
            padding: 0 10px;
        }

        .roadmap-item .icon-box {
            width: 90px; height: 90px;
            margin: 0 auto 20px;
            display: flex; align-items: center; justify-content: center;
            position: relative;
            border-radius: 50%;
            border: 10px solid #fff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            font-size: 2rem; color: white;
        }

        .number-badge {
            position: absolute;
            top: -10px; right: -10px;
            background-color: #333; color: white;
            font-weight: bold; font-size: 0.75rem;
            width: 30px; height: 30px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .roadmap-item h6 { font-weight: bold; margin-bottom: 5px; color: #333; }
        .roadmap-item p { font-size: 0.8rem; color: #777; line-height: 1.4; }

        .roadmap-item:not(:last-child)::before {
            content: "";
            position: absolute;
            top: 55px;
            right: -25%;
            width: 50%;
            height: 4px;
            background-color: var(--unsika-red);
            z-index: -1;
        }

        @media (max-width: 768px) {
            .roadmap-wrapper { border-radius: 30px; padding: 20px; }
            .roadmap-container { flex-direction: column; }
            .roadmap-item { margin-bottom: 30px; width: 100%; text-align: left; display: flex; align-items: center; }
            .roadmap-item:not(:last-child)::before { display: none; }
            .roadmap-item .icon-box { margin: 0 15px 0 0; width: 70px; height: 70px; font-size: 1.5rem; flex-shrink: 0; }
            .number-badge { top: -5px; right: -5px; width: 25px; height: 25px; font-size: 0.6rem; }
            .roadmap-text { flex-grow: 1; }
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--soft-pink);
            color: var(--unsika-red);
            box-shadow: none;
        }
        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(200, 16, 46, 0.25);
        }
        footer a:hover {
            color: var(--unsika-red) !important;
            transition: 0.3s;
        }
        .btn-unsika {
            background-color: var(--unsika-red);
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-unsika:hover {
            background-color: var(--unsika-dark);
            color: white;
        }
        .btn-outline-unsika {
            color: var(--unsika-red);
            border: 2px solid var(--unsika-red);
            background: transparent;
            transition: all 0.3s ease;
        }
        .btn-outline-unsika:hover {
            background-color: var(--unsika-red);
            color: white;
        }
    </style>
</head>
<body>

<div id="loading-screen">
    <div id="progress-bar"></div>
    <div class="video-container">
        <video autoplay muted loop id="loading-video">
            <source src="image/loading.mp4" type="video/mp4">
        </video>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <img src="image/logo.png" alt="" width="30" class="me-2">Unsika-Lab
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="alat_tersedia.php">Alat Tersedia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prosedur.php">Prosedur</a>
                </li>
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle btn btn-light btn-sm mt-1 rounded-pill px-4 py-2 text-dark shadow-sm" href="#" id="akunDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #ffffff; border: 1px solid #dee2e6; color: #000000 !important; font-weight: 600;">
                        <i class="bi bi-person-circle me-1"></i> Akun
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" aria-labelledby="akunDropdown" style="min-width: 240px;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center rounded-3 px-3 py-2 mb-1" href="profile.php" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
                                <div class="bg-light text-dark rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-lines-fill fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark" style="font-size: 0.95rem;">Informasi Akun</span>
                                    <span class="d-block text-muted" style="font-size: 0.75rem;">Lihat & Edit Profil</span>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-2 opacity-25"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center rounded-3 px-3 py-2 mt-1 text-danger" href="logout.php" onmouseover="this.style.backgroundColor='#fff5f5'" onmouseout="this.style.backgroundColor='transparent'">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                    <i class="bi bi-door-open fs-5 text-danger"></i>
                                </div>
                                <span class="fw-bold" style="font-size: 0.95rem;">Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-start">
                <h1 class="fw-bold display-4 mb-3 text-dark">Pinjam Alat Jadi <span style="color: var(--unsika-red);">Lebih Mudah.</span></h1>
                <p class="lead text-muted mb-4">Dukung riset dan tugas kuliahmu dengan fasilitas laboratorium teknik terbaik kami.</p>
                
                <div class="search-container mb-3">
                    <form action="alat_tersedia.php" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control border-0 bg-transparent text-dark" placeholder="Cari Mikroskop, Kamera, Laptop...">
                        <button class="btn btn-unsika px-4 py-2 rounded-3">Cari</button>
                    </form>
                </div>
                <div class="small text-muted">
                    <span>Populer: </span>
                    <span class="badge bg-white text-dark border fw-normal mx-1">Kamera</span>
                    <span class="badge bg-white text-dark border fw-normal mx-1">Laptop ROG</span>
                    <span class="badge bg-white text-dark border fw-normal mx-1">Osiloskop</span>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-end">
                <img src="image/ilustrasilab.png" alt="Lab Illustration" class="img-fluid rounded-4 shadow-lg" style="max-height: 400px; width: auto; display: block; margin-left: auto; border: 5px solid rgba(255,255,255,0.5);">
            </div>
        </div>
    </div>
</section>

<div class="container" style="margin-top: -100px; position: relative; z-index: 10;">
    <div class="row justify-content-center g-4">
        <div class="col-md-3">
            <div class="p-4 shadow bg-white rounded-4 border-0 text-center">
                <h2 class="fw-bold text-danger mb-1"><?php echo $total_alat; ?>+</h2>
                <p class="text-muted small mb-0">Total Alat Lab</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 shadow bg-white rounded-4 border-0 text-center">
                <h2 class="fw-bold text-danger mb-1"><?php echo $jumlah_lab; ?></h2>
                <p class="text-muted small mb-0">Laboratorium</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 shadow bg-white rounded-4 border-0 text-center">
                <h2 class="fw-bold text-danger mb-1"><?php echo $peminjaman_aktif; ?></h2>
                <p class="text-muted small mb-0">Peminjaman Aktif</p>
            </div>
        </div>
    </div>
</div>

<hr class="container my-5">

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-0">Jumpa lagi, <?= htmlspecialchars($nama) ?>! 👋</h3>
            <p class="text-muted">Mau pinjam alat apa kita hari ini?</p>
        </div>
        <a href="alat_tersedia.php" class="btn btn-outline-unsika rounded-pill px-4">
            Lihat Semua <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>

    <div class="row g-4">
    <?php
    $query_alat = mysqli_query($conn, "SELECT * FROM daftar_alat LIMIT 4");
    while($alat = mysqli_fetch_assoc($query_alat)){
        $id_alat = $alat['id'];
        
        $query_total_terpinjam = mysqli_query($conn, "SELECT SUM(jumlah_pinjam) AS total_terpinjam FROM peminjaman WHERE id_alat = '$id_alat' AND status IN ('Pending', 'Disetujui')");
        $data_terpinjam = mysqli_fetch_assoc($query_total_terpinjam);
        $total_terpinjam = $data_terpinjam['total_terpinjam'] ? $data_terpinjam['total_terpinjam'] : 0;

        $stok_maksimal = isset($alat['stok']) ? $alat['stok'] : 5; 
        $sisa_stok = $stok_maksimal - $total_terpinjam;

        $nama_user_sekarang = $_SESSION['nama_user'];
        $cek_pinjam_saya = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_alat = '$id_alat' AND nama_mahasiswa = '$nama_user_sekarang' AND status IN ('Pending', 'Disetujui') ORDER BY id_peminjaman DESC LIMIT 1");
        $pinjam_saya = mysqli_fetch_assoc($cek_pinjam_saya);

        $display_status = "Tersedia"; 
        $btn_text = "Pinjam Alat";
        $btn_class = "btn-pinjam d-flex justify-content-center align-items-center";
        $is_disabled = "";
        $href = "alat_tersedia.php?id=" . $id_alat;

        if ($pinjam_saya) {
            if ($pinjam_saya['status'] == 'Pending') {
                $display_status = "Menunggu Persetujuan";
                $btn_text = "Waiting for Approval ⏳";
                $btn_class = "btn-warning btn-pinjam-home text-dark d-flex justify-content-center align-items-center";
                $is_disabled = "disabled";
                $href = "#";
            } else if ($pinjam_saya['status'] == 'Disetujui') {
                $display_status = "Sedang Anda Pinjam";
                $btn_text = "Kembalikan";
                $btn_class = "btn-danger btn-pinjam-home d-flex justify-content-center align-items-center";
                $is_disabled = "";
                $href = "kembalikan_alat.php?id_peminjaman=" . $pinjam_saya['id_peminjaman'];
            }
        } else if ($sisa_stok <= 0) {
            $display_status = "Dipinjam Habis";
            $btn_text = "Not Available";
            $btn_class = "btn-outline-secondary btn-pinjam-home d-flex justify-content-center align-items-center";
            $is_disabled = "disabled";
            $href = "#";
        }

        $gambar = "https://placehold.co/600x400?text=No+Image"; 
        if (!empty($alat['gambar'])) {
            $path_gambar = "uploads/" . $alat['gambar'];
            if (file_exists($path_gambar)) {
                $gambar = $path_gambar;
            }
        }
    ?>
        <div class="col-md-3">
            <div class="card card-alat h-100 shadow-sm">
                <div class="img-container">
                    <img src="<?= $gambar ?>" style="width:100%;height:180px;object-fit:cover;" alt="Foto Alat">
                </div>
                <div class="card-body">
                    <?php 
                        if ($sisa_stok <= 0) {
                            $badge_class = "bg-danger";
                            $icon = "🔴";
                        } elseif ($pinjam_saya && $pinjam_saya['status'] == 'Disetujui') {
                            $badge_class = "bg-warning text-dark";
                            $icon = "📦";
                        } elseif ($pinjam_saya && $pinjam_saya['status'] == 'Pending') {
                            $badge_class = "bg-warning text-dark";
                            $icon = "⏳";
                        } else {
                            $badge_class = "bg-success";
                            $icon = "🟢";
                        }
                    ?>
                    <span class="badge <?= $badge_class; ?> mb-2">
                        <?= $icon . " " . $display_status; ?>
                    </span>

                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($alat['nama_alat']) ?></h6>
                    <p class="text-muted small mb-3"><?= htmlspecialchars($alat['lokasi']) ?></p>

                    <?php if ($is_disabled == "") { ?>
                        <a href="<?= $href ?>" class="btn <?= $btn_class ?>">
                            <?= $btn_text ?>
                        </a>
                    <?php } else { ?>
                        <button class="btn <?= $btn_class ?>" disabled>
                            <?= $btn_text ?>
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php 
    } 
    ?>
    </div>
</div>

<section class="container py-5">
    <div class="text-center mb-5">
        <h3 class="fw-bold">Tahapan Peminjaman Alat</h3>
        <hr class="mx-auto" style="width: 50px; height: 3px; background: var(--unsika-red); border:none; opacity:1;">
    </div>
    
    <div class="roadmap-wrapper">
        <div class="roadmap-container">
            <div class="roadmap-item">
                <div class="icon-box bg-primary shadow-sm">
                    <div class="number-badge">01</div>
                    <i class="bi bi-whatsapp"></i>
                </div>
                <div class="roadmap-text">
                    <h6 class="fw-bold">Hubungi Laboran</h6>
                    <p class="text-muted small px-lg-2">Konfirmasi ketersediaan dan biaya sewa.</p>
                </div>
            </div>

            <div class="roadmap-item">
                <div class="icon-box bg-warning shadow-sm">
                    <div class="number-badge">02</div>
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="roadmap-text">
                    <h6 class="fw-bold">Isi Formulir</h6>
                    <p class="text-muted small px-lg-2">Lengkapi data pada G-Form yang tersedia.</p>
                </div>
            </div>

            <div class="roadmap-item">
                <div class="icon-box bg-info shadow-sm text-white">
                    <div class="number-badge">03</div>
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="roadmap-text">
                    <h6 class="fw-bold">Ambil Alat</h6>
                    <p class="text-muted small px-lg-2">Verifikasi fisik alat bersama teknisi lab.</p>
                </div>
            </div>

            <div class="roadmap-item">
                <div class="icon-box bg-success shadow-sm">
                    <div class="number-badge">04</div>
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
                <div class="roadmap-text">
                    <h6 class="fw-bold">Gunakan & Balikkan</h6>
                    <p class="text-muted small px-lg-2">Kembalikan dalam kondisi bersih dan utuh.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light" style="border-radius: 50px 50px 0 0;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Eksplorasi Laboratorium</h3>
                <p class="text-muted small">Temukan alat berdasarkan spesialisasi laboratorium</p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            $labs = [
                ['name' => 'Lab Multimedia', 'icon' => 'bi-camera-video', 'color' => '#6f42c1', 'desc' => 'Kamera, Lighting, Audio'],
                ['name' => 'Lab Komputer', 'icon' => 'bi-laptop', 'color' => '#0d6efd', 'desc' => 'PC High-End, Server, IoT'],
                ['name' => 'Lab Elektro', 'icon' => 'bi-cpu', 'color' => '#fd7e14', 'desc' => 'Osiloskop, Solder, Sensor'],
                ['name' => 'Lab Fisika', 'icon' => 'bi-thermometer-half', 'color' => '#198754', 'desc' => 'Alat Ukur, Optik, Mekanika'],
            ];

            foreach ($labs as $l): ?>
            <div class="col-6 col-md-3">
                <a href="alat_tersedia.php?filter_lab=<?= urlencode($l['name']) ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center lab-card">
                        <div class="icon-box mb-3 mx-auto" style="background-color: <?= $l['color'] ?>15; color: <?= $l['color'] ?>;">
                            <i class="<?= $l['icon'] ?> fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1"><?= $l['name'] ?></h6>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;"><?= $l['desc'] ?></p>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <h4 class="fw-bold mb-4"><i class="bi bi-question-circle text-danger me-2"></i>Pertanyaan Sering Diajukan</h4>
                <div class="accordion accordion-flush shadow-sm rounded-4 overflow-hidden border" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Bagaimana jika alat rusak saat saya pakai?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Peminjam wajib segera melaporkan kerusakan kepada laboran. Jika kerusakan akibat kelalaian, peminjam wajib menanggung biaya perbaikan atau mengganti komponen yang rusak sesuai ketentuan lab.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Apakah bisa pinjam lebih dari 3 hari?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Peminjaman standar adalah 3 hari kerja. Untuk riset jangka panjang atau skripsi, silakan mengajukan surat permohonan khusus yang ditandatangani oleh dosen pembimbing.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Kapan jam operasional pengambilan alat?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Layanan pengambilan dan pengembalian alat dilayani pada hari kerja (Senin - Jumat) pukul 08.00 s.d. 16.00 WIB.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <h4 class="fw-bold mb-0.5"><i class="bi bi-chat-heart text-danger me-2"></i>Kesan Mahasiswa</h4>
                <div class="card border-0 bg-light p-3 rounded-3 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-quote fs-1 text-danger opacity-25"></i>
                        <p class="fs-5 italic mb-4">"Proses bookingnya cepet, alatnya juga terawat banget! Sangat membantu buat ngerjain tugas praktikum tanpa harus ribet antre manual."</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background-color: var(--unsika-red);">
                                <i class="bi bi-person-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Kelompok 2</h6>
                                <small class="text-muted">Mahasiswa Teknik Informatika</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="p-5 rounded-5 shadow-lg text-white d-flex align-items-center justify-content-between flex-wrap" 
             style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);">
            <div class="mb-3 mb-lg-0">
                <h2 class="fw-bold mb-2">Masih bingung dengan prosedurnya?</h2>
                <p class="mb-0 opacity-75">Laboran kami siap membantu menjawab pertanyaanmu secara langsung.</p>
            </div>
            <a href="https://wa.me/6281234567890" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-danger">
                <i class="bi bi-whatsapp me-2"></i>Tanya Laboran
            </a>
        </div>
    </div>
</section>

<footer class="pt-5 pb-3 w-100" style="background-color: #212529; color: #adb5bd;">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-4">
                <h4 class="text-white fw-bold mb-3">Unsika-Lab</h4>
                <p class="small">Sistem Manajemen Peminjaman Alat & Fasilitas Laboratorium Universitas Singaperbangsa Karawang. Mendukung inovasi dan riset mahasiswa teknik.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-12 col-lg-3">
                <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-geo-alt text-danger me-2"></i> Gedung Lab Terpadu Lt. 2, Kampus UNSIKA</li>
                    <li class="mb-2"><i class="bi bi-envelope text-danger me-2"></i> lab.teknik@unsika.ac.id</li>
                    <li class="mb-2"><i class="bi bi-whatsapp text-danger me-2"></i> +62 812-3456-7890</li>
                </ul>
            </div>

            <div class="col-12 col-lg-5">
                <h6 class="text-white fw-bold mb-3">Lokasi Fakultas</h6>
                <div class="rounded-3 overflow-hidden shadow-sm w-100" style="height: 150px;">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.733526131435!2d107.30456107475132!3d-6.30006399368903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6977936603a95d%3A0x6968037c87c094f!2sUniversitas%20Singaperbangsa%20Karawang!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center mt-4">
            <p class="small mb-0">&copy; 2026 Unsika-Lab. Created by <span class="text-white">Kelompok 2</span>. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    window.addEventListener('load', function() {
        const loadingScreen = document.getElementById('loading-screen');
        const progressBar = document.getElementById('progress-bar');
        
        if(progressBar) progressBar.style.width = '100%';
        
        setTimeout(() => {
            if(loadingScreen) {
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 800);
            }
        }, 1000);
    });
</script>

</body>
</html>