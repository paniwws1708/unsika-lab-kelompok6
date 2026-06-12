<?php
session_start();
if (!isset($_SESSION['nama_user'])) {
    header("Location: index.php");
    exit();
}
$nama_user = $_SESSION['nama_user'];
include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM daftar_alat");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alat Tersedia - Unsika Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        .img-container {
            height: 180px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .card-alat {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: #ffffff;
        }
        .alat-info {
            font-size: 0.8rem;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 8px;
            margin-bottom: 15px;
        }
        .card-alat:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        }
        .btn-pinjam,
        .btn-disabled-custom{
            width:100%;
            height:52px; /* samakan tinggi */
            border-radius:16px;
            font-weight:600;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:0;
        }

        .btn-disabled-custom:hover{
            border: 2px solid #d6d6d6;
            background: #f1f1f1;
            color: #8a8a8a;
            cursor:not-allowed;
        }

        :root { --unsika-red: #800020; --unsika-dark: #5C0011; --soft-pink: #F7F2F3; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        
        /* Navbar Custom */
        .active-link { color: var(--unsika-red) !important; font-weight: bold; }

        /* Navbar Styling */
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
            color: var(--unsika-red) !important;
        }
        .nav-link.active-link::after {
            content: "";
            position: absolute;
            left: 15%;
            bottom: 0;
            width: 70%;
            height: 3px;
            background-color: var(--unsika-red);
            border-radius: 3px;
        }
        
        .text-unsika { color: var(--unsika-red) !important; }
        .bg-unsika { background-color: var(--unsika-red) !important; color: white; }
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
        /* Hero Section */
        .hero-section {
            background-color: #fae1e1;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 0 0 50px 50px;
        }

        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        @keyframes soft-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        #loading-video {
            width: 1200px;
            height: auto;
            display: block;
            outline: none;
            border: none;
            animation: soft-pulse 2s infinite ease-in-out;
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
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active-link" href="alat_tersedia.php">Alat Tersedia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prosedur.php">Prosedur</a>
                </li>
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle btn btn-light btn-sm mt-1 rounded-pill px-4 py-2 text-dark shadow-sm" href="#" id="akunDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #ffffff; border: 1px solid #dee2e6; color: #000000 !important; font-weight: 600;">
                        <i class="bi bi-person-circle me-1"></i> Akun
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" aria-labelledby="akunDropdown" style="min-width: 240px; animation: fadeIn 0.3s ease;">
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

<div class="container py-5">
    <div class="row mb-5 align-items-center bg-light p-4 rounded-4 shadow-sm">
    <div class="col-md-7">
        <h2 class="fw-bold text-unsika">Selamat Datang di Unsika-Lab 👋</h2>
        <p class="text-muted">Pinjam peralatan laboratorium dengan mudah, cepat, dan terdata secara otomatis.</p>
        
        <div class="input-group mb-3 shadow-sm" style="max-width: 500px;">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari kamera, laptop, atau proyektor..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button class="btn btn-unsika px-4" type="button">Cari Alat</button>
        </div>
    </div>
    <div class="col-md-5 d-none d-md-block text-center">
        <i class="bi bi-box-seam-fill text-unsika opacity-25" style="font-size: 8rem;"></i>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-filter"></i> Filter Kategori
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Semua Alat</a></li>
            <li><a class="dropdown-item" href="#">Multimedia</a></li>
            <li><a class="dropdown-item" href="#">Komputer</a></li>
            <li><a class="dropdown-item" href="#">Fasilitas Umum</a></li>
        </ul>
    </div>
</div>

<div class="row mb-5 align-items-center bg-light p-4 rounded-4 shadow-sm">
    <div class="col-md-7">
        <h2 class="fw-bold text-unsika">
            Daftar Alat Laboratorium 👋
        </h2>
        <p class="text-muted">
            Cek ketersediaan dan pinjam alat secara real-time.
        </p>
    </div>
</div>

    <div class="row g-4"> 
        <?php while ($data = mysqli_fetch_assoc($query)) : 
            $id_alat = $data['id'];
            
            $cek_pinjam = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_alat = '$id_alat' AND status IN ('Pending', 'Disetujui') ORDER BY id_peminjaman DESC LIMIT 1");
            $pinjam_aktif = mysqli_fetch_assoc($cek_pinjam);
            $status_display = "Available"; 
            $badgeColor = "bg-success"; 
            $isBtnDisabled = "";
            $btnText = "Pinjam Sekarang";
            $btnClass = "btn-outline-unsika";
            $target_modal = 'data-bs-toggle="modal" data-bs-target="#modalPinjam"';
            $is_return = false;
            $id_peminjaman = "";

            if ($pinjam_aktif) {
                if ($pinjam_aktif['nama_mahasiswa'] == $nama_user) {
                    if ($pinjam_aktif['status'] == 'Pending') {
                        $status_display = "Available"; 
                        $badgeColor = "bg-success"; 
                        $btnText = "Waiting for Approval ⏳";
                        $btnClass = "btn-warning text-dark";
                        $isBtnDisabled = "disabled";
                        $target_modal = "";
                    } else if ($pinjam_aktif['status'] == 'Disetujui') {
                        $status_display = "Dipinjam";
                        $badgeColor = "bg-warning text-dark";
                        $btnText = "Kembalikan";
                        $btnClass = "btn-danger";
                        $isBtnDisabled = "";
                        $target_modal = "";
                        $is_return = true;
                        $id_peminjaman = $pinjam_aktif['id_peminjaman'];
                    }
                } else {
                    if ($pinjam_aktif['status'] == 'Disetujui') {
                        $status_display = "Dipinjam";
                        $badgeColor = "bg-warning text-dark";
                        $btnText = "Not Available";
                        $btnClass = "btn-disabled-custom";
                        $isBtnDisabled = "disabled";
                        $target_modal = "";
                    }
                }
            }
            if ($data['jumlah_unit'] <= 0) {
                $status_display = "Dipinjam";
                $badgeColor = "bg-warning text-dark";
                if (!$is_return) { 
                    $isBtnDisabled = "disabled";
                    $btnText = "Tidak Tersedia";
                    $btnClass = "btn-disabled-custom";
                    $target_modal = "";
                }
            }
        ?>
        <div class="col-md-4 col-lg-3">
            <div class="card card-alat h-100 shadow-sm border-0">
                <div class="img-container">
                    <span class="position-absolute top-0 end-0 m-3 badge rounded-pill bg-dark">
                        Sisa <?php echo $data['jumlah_unit']; ?> Unit
                    </span>
                    
                    <?php
                    $gambar = !empty($data['gambar']) ? "uploads/".$data['gambar'] : "assets/no-image.png";
                    ?>
                    <img src="<?= $gambar ?>" style="width:100%;height:100%;object-fit:cover;" alt="Foto Alat">
                </div> 
                
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge rounded-pill <?php echo $badgeColor; ?>">
                            <?php echo $status_display; ?>
                        </span>
                    </div>
                    <h5 class="card-title fw-bold mb-1"><?php echo $data['nama_alat']; ?></h5>
                    <p class="small text-muted mb-3"><i class="bi bi-geo-alt text-danger"></i> <?php echo $data['lokasi']; ?></p>
                    
                    <div class="alat-info mb-4" style="background: #f8f9fa; padding: 10px; border-radius: 12px;">
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Spesifikasi:</span>
                            <span class="fw-bold"><?php echo $data['spesifikasi_1']; ?></span>
                        </div>
                    </div>

                    <?php if ($is_return): ?>
                        <a href="kembalikan_alat.php?id_peminjaman=<?php echo $id_peminjaman; ?>" class="btn <?php echo $btnClass; ?> w-100 btn-pinjam mt-auto">
                            <?php echo $btnText; ?>
                        </a>
                    <?php else: ?>
                        <button class="btn <?php echo $btnClass; ?> w-100 btn-pinjam mt-auto" 
                                <?php echo $isBtnDisabled; ?>
                                <?php echo $target_modal; ?>
                                data-nama="<?php echo $data['nama_alat']; ?>" 
                                data-stok="<?php echo $data['jumlah_unit']; ?>"
                                data-id="<?php echo $data['id']; ?>">
                            <?php echo $btnText; ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div> 
</div>

<div class="modal fade" id="modalPinjam" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Form Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="proses_pinjam.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body px-4">
                    <div class="mb-3 p-3 rounded-3" style="background-color: #eef2ff;">
                        <small class="text-muted d-block">Alat yang akan dipinjam:</small>
                        <span id="displayNamaAlat" class="fw-bold text-primary fs-5"></span>
                        <input type="hidden" name="id_alat" id="inputAlatId">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_mahasiswa" class="form-control" placeholder="Nama sesuai KTM" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">NIM / NIDN</label>
                        <input type="text" name="npm" class="form-control" placeholder="Masukkan nomor identitas" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jumlah Pinjam (Pcs)</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_pinjam" id="input_jumlah" class="form-control" min="1" value="1" required>
                            <span class="input-group-text bg-light text-dark fw-bold">
                                Tersedia: <span id="stok_modal">0</span>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Tgl Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Tgl Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Upload Foto KTM</label>
                        <input type="file" name="foto_ktm" class="form-control" accept="image/*" required>
                        <small class="text-muted d-block mt-1">Wajib dilampirkan sebagai syarat peminjaman.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keperluan</label>
                        <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Tugas Akhir"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="pinjam" class="btn btn-unsika rounded-pill px-4">Kirim Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
            <p class="small mb-0">© 2026 Unsika-Lab. Created by <span class="text-white">Kelompok 2</span>. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener('load', function() {
        const progressBar = document.getElementById('progress-bar');
        const loadingScreen = document.getElementById('loading-screen');
        
        if(progressBar) progressBar.style.width = '100%';
        
        setTimeout(function() {
            if(loadingScreen) {
                loadingScreen.style.opacity = '0';
                setTimeout(() => loadingScreen.style.display = 'none', 400);
            }
        }, 800);
    });
    var modalPinjam = document.getElementById('modalPinjam');
    if (modalPinjam) {
        modalPinjam.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var stok = button.getAttribute('data-stok');
            
            document.getElementById('displayNamaAlat').textContent = nama;
            document.getElementById('inputAlatId').value = id;
            document.getElementById('stok_modal').textContent = stok;
            
            var inputJumlah = document.getElementById('input_jumlah');
            inputJumlah.setAttribute('max', stok);
            inputJumlah.value = 1;
        });
    }
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        function triggerSearch() {
            let filter = searchInput.value.toLowerCase();
            let cards = document.querySelectorAll('.card-alat');
            
            cards.forEach(card => {
                let title = card.querySelector('.card-title').innerText.toLowerCase();
                if (title.includes(filter)) {
                    card.closest('.col-md-4, .col-lg-3').style.display = "";
                } else {
                    card.closest('.col-md-4, .col-lg-3').style.display = "none";
                }
            });
        }

        searchInput.addEventListener('keyup', triggerSearch);
        
        if(searchInput.value !== '') {
            triggerSearch();
        }
    }
    const urlParams = new URLSearchParams(window.location.search);
    const idAlatDariUrl = urlParams.get('id');

    if (idAlatDariUrl) {
        const tombolTarget = document.querySelector(`button[data-id="${idAlatDariUrl}"]`);
        if (tombolTarget) {
            var myModal = new bootstrap.Modal(document.getElementById('modalPinjam'));
            var nama = tombolTarget.getAttribute('data-nama');
            document.getElementById('displayNamaAlat').textContent = nama;
            document.getElementById('inputAlatId').value = idAlatDariUrl;
            myModal.show();
        }
    }
</script>
</body>
</html>