<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prosedur Peminjaman - Unsika Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --unsika-red: #800020; }
        .active-link { 
            color: var(--unsika-red) !important; 
            font-weight: bold; 
            border-bottom: 3px solid var(--unsika-red); 
        }
        .accordion-button:not(.collapsed) { 
            background-color: #fff5f5; 
            color: var(--unsika-red); 
            shadow: none; 
        }
        .accordion-button:focus { 
            border-color: var(--unsika-red); 
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25); 
        }
        .step-icon { 
            color: var(--unsika-red); 
            font-size: 1.5rem; 
            margin-right: 15px; 
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

        @keyframes soft-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        #loading-video {
            width: 1200px; /* Sesuaikan ukuran */
            height: auto;
            display: block;
            outline: none;
            border: none;
            animation: soft-pulse 2s infinite ease-in-out;
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

        /* Navbar Styling */
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        .navbar-brand img {
            filter: drop-shadow(0 2px 4px rgb(247, 175, 175));
            margin-right: 12px;
        }

        .nav-link {
            position: relative;
            color: #ff0000 !important;
            padding: 5px 0;
            margin: 0 15px;
        }

        .nav-link.active::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px; 
            width: 100%;  
            height: 3px;  
            background-color: #ff0000; 
            border-radius: 2px;
        }

        .nav-link:hover::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 3px;
            background-color: rgba(255, 0, 0, 0.5); 
        }

        .navbar-dark.bg-dark {
            background-color: #1a1a1a !important; 
        }

        .navbar-nav .nav-link {
            color: #000000 !important; 
            font-weight: 500;
            position: relative;
            padding: 10px 15px;
            background-color: transparent !important; 
        }

        .navbar-nav .nav-link.active, 
        .navbar-nav .nav-link:hover {
            color: #000000 !important;
        }

        .navbar-nav .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 15%; 
            width: 70%;  
            height: 3px; 
            background-color: #ff0000; 
            border-radius: 2px;
        }

        .navbar-nav .nav-link:hover {
            color: #ff0000 !important; 
        }

        /* Hero Section */
        .hero-section {
            background-color: #fae1e1;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            border-radius: 0 0 50px 50px;
        }
    </style>
</head>
<body class="bg-light">

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
                    <a class="nav-link" href="alat_tersedia.php">Alat Tersedia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active-link" href="prosedur.php">Prosedur</a>
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

<div class="container mb-5 mt-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Prosedur & Aturan Lab</h2>
        <p class="text-muted">Harap baca dengan seksama sebelum melakukan peminjaman alat.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="accordion shadow-sm" id="accordionProsedur">
                
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            1. Syarat & Ketentuan Umum
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionProsedur">
                        <div class="accordion-body bg-white">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-0"><i class="bi bi-check2-circle text-success me-2"></i><strong>Keanggotaan:</strong> Peminjam wajib mahasiswa aktif Unsika yang terdaftar di sistem.</li>
                                <li class="list-group-item border-0"><i class="bi bi-check2-circle text-success me-2"></i><strong>Jaminan:</strong> Menyerahkan KTM (Kartu Tanda Mahasiswa) asli saat pengambilan alat.</li>
                                <li class="list-group-item border-0"><i class="bi bi-check2-circle text-success me-2"></i><strong>Batas Waktu:</strong> Maksimal durasi peminjaman 3 hari kerja.</li>
                                <li class="list-group-item border-0"><i class="bi bi-check2-circle text-success me-2"></i><strong>Jam Operasional:</strong> Pengambilan & pengembalian dilakukan pukul 08.00 - 16.00 WIB.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            2. Alur Peminjaman Detail (SOP)
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionProsedur">
                        <div class="accordion-body bg-white">
                            <div class="d-flex mb-3">
                                <div class="step-icon">01</div>
                                <div><strong>Pengajuan:</strong> Melakukan booking melalui website minimal H-1 sebelum pemakaian.</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="step-icon">02</div>
                                <div><strong>Persetujuan:</strong> Admin/Kepala Lab akan memverifikasi permohonan maksimal dalam 1x24 jam.</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="step-icon">03</div>
                                <div><strong>Pengambilan:</strong> Datang ke Lab, cek fisik alat bersama teknisi, dan tanda tangani berita acara.</div>
                            </div>
                            <div class="d-flex">
                                <div class="step-icon">04</div>
                                <div><strong>Penggunaan:</strong> Alat hanya boleh digunakan untuk kepentingan akademik (tugas/riset).</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            3. Prosedur Pengembalian
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionProsedur">
                        <div class="accordion-body bg-white">
                            <p><i class="bi bi-info-circle-fill text-primary me-2"></i> Alat harus dikembalikan dalam kondisi bersih dan berfungsi seperti semula.</p>
                            <p><i class="bi bi-info-circle-fill text-primary me-2"></i> Teknisi akan mengecek kelengkapan (kabel, tas, baterai) sesuai data katalog.</p>
                            <p><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Toleransi keterlambatan maksimal 30 menit dari jam kesepakatan.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            4. Sanksi & Ganti Rugi
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionProsedur">
                        <div class="accordion-body bg-white border-start border-danger border-4">
                            <div class="alert alert-danger border-0 mb-0">
                                <ul>
                                    <li><strong>Keterlambatan:</strong> Denda berupa poin atau larangan meminjam selama 1 month.</li>
                                    <li><strong>Kerusakan Ringan:</strong> Peminjam wajib menanggung biaya perbaikan resmi.</li>
                                    <li><strong>Kerusakan Total/Hilang:</strong> Peminjam wajib mengganti dengan alat berspesifikasi sama atau setara.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="container row justify-content-center mx-auto mt-5 mb-4">
    <div class="col-md-8 text-center">
        <div class="p-4 rounded-4 shadow-sm border-0" style="background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%); border: 1px solid #ffe5e5 !important;">
            <h5 class="fw-bold mb-2">Masih punya pertanyaan?</h5>
            <p class="text-muted small mb-4">Jika ada prosedur yang kurang jelas atau butuh bantuan mendesak, silakan hubungi tim teknis kami.</p>
            
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="https://wa.me/628123456789" target="_blank" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-whatsapp me-2"></i>WhatsApp Admin
                </a>
                
                <a href="https://instagram.com/unsika_lab" target="_blank" class="btn btn-outline-danger px-4 py-2 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-instagram me-2"></i>Instagram Lab
                </a>
            </div>
            
            <p class="mt-3 mb-0" style="font-size: 0.75rem; color: #999;">
                <i class="bi bi-clock me-1"></i> Respon cepat pada jam kerja (08.00 - 16.00 WIB)
            </p>
        </div>
    </div>
</div>

<footer class="pt-5 pb-3" style="background-color: #212529; color: #adb5bd;">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <h4 class="text-white fw-bold mb-3">Unsika-Lab</h4>
                <p class="small">Sistem Manajemen Peminjaman Alat & Fasilitas Laboratorium Universitas Singaperbangsa Karawang. Mendukung inovasi dan riset mahasiswa teknik.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-lg-3">
                <h6 class="text-white fw-bold mb-3">Hubungi Kami</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-geo-alt text-danger me-2"></i> Gedung Lab Terpadu Lt. 2, Kampus UNSIKA</li>
                    <li class="mb-2"><i class="bi bi-envelope text-danger me-2"></i> lab.teknik@unsika.ac.id</li>
                    <li class="mb-2"><i class="bi bi-whatsapp text-danger me-2"></i> +62 812-3456-7890</li>
                </ul>
            </div>

            <div class="col-lg-5">
                <h6 class="text-white fw-bold mb-3">Lokasi Fakultas</h6>
                <div class="rounded-3 overflow-hidden shadow-sm" style="height: 150px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.733526131435!2d107.30456107475132!3d-6.30006399368903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6977936603a95d%3A0x6968037c87c094f!2sUniversitas%20Singaperbangsa%20Karawang!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center mt-4">
            <p class="small mb-0">© 2026 Unsika-Lab. Created by <span class="text-white">Kelompok 2</span>. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    window.addEventListener('load', function() {
        document.getElementById('progress-bar').style.width = '100%';
        
        setTimeout(function() {
            const loadingScreen = document.getElementById('loading-screen');
            loadingScreen.style.opacity = '0';
            setTimeout(() => { loadingScreen.style.display = 'none'; }, 500);
        }, 3000);
    });
</script>
</body>
</html>