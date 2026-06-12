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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Alat - Unsika Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        
        .card-alat {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .img-container {
            height: 180px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-radius: 20px 20px 0 0;
        }

        .alat-info {
            font-size: 0.8rem;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .btn-pinjam {
            border-radius: 12px;
            padding: 10px;
            font-weight: 600;
        }

      
        .modal-content { border-radius: 25px; border: none; }
        .form-control { border-radius: 10px; padding: 12px; }
    </style>
</head>
<body>

<div class="container py-5">
    <h3 class="fw-bold mb-4">Alat Tersedia</h3>
    
    <div class="row g-4">
        <?php while ($data = mysqli_fetch_assoc($query)) : 
            $id_alat = $data['id'];
            $cek_pinjam = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_alat = '$id_alat' AND status IN ('Pending', 'Disetujui')");
            $pinjam_aktif = mysqli_fetch_assoc($cek_pinjam);
            
            $btn_text = "Pinjam Sekarang";
            $btn_class = "btn-outline-primary";
            $is_disabled = "";
            $target_modal = 'data-bs-toggle="modal" data-bs-target="#modalPinjam"';
            $is_return = false;
            $id_peminjaman = "";

            if ($pinjam_aktif) {
                if ($pinjam_aktif['nama_mahasiswa'] == $nama_user) {
                    if ($pinjam_aktif['status'] == 'Pending') {
                        $btn_text = "Waiting for Approval ⏳";
                        $btn_class = "btn-warning text-dark";
                        $is_disabled = "disabled";
                        $target_modal = "";
                    } else if ($pinjam_aktif['status'] == 'Disetujui') {
                        $btn_text = "Kembalikan";
                        $btn_class = "btn-danger";
                        $is_disabled = "";
                        $target_modal = "";
                        $is_return = true;
                        $id_peminjaman = $pinjam_aktif['id_peminjaman'];
                    }
                } else {
                    $btn_text = "Tidak Tersedia";
                    $btn_class = "btn-secondary";
                    $is_disabled = "disabled";
                    $target_modal = "";
                }
            } else if ($data['status'] == 'Dipinjam') {
                $btn_text = "Dipinjam";
                $btn_class = "btn-secondary";
                $is_disabled = "disabled";
                $target_modal = "";
            }
        ?>
        <div class="col-md-4 col-lg-3">
            <div class="card card-alat h-100 shadow-sm">
                <div class="img-container">
                    <span class="position-absolute top-0 end-0 m-3 badge rounded-pill bg-dark">
                        Sisa <?php echo $data['jumlah_unit']; ?> Unit
                    </span>
                    <i class="bi bi-camera-video" style="font-size: 4rem; color: #adb5bd;"></i>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-success rounded-pill"><?php echo $data['status']; ?></span>
                    </div>
                    <h5 class="card-title fw-bold mb-1"><?php echo $data['nama_alat']; ?></h5>
                    <p class="small text-muted"><i class="bi bi-geo-alt text-danger"></i> <?php echo $data['lokasi']; ?></p>
                    
                    <div class="alat-info">
                        <div class="d-flex justify-content-between">
                            <span>Spek:</span>
                            <span class="text-dark fw-bold"><?php echo $data['spesifikasi_1']; ?></span>
                        </div>
                    </div>
                    
                    <?php if ($is_return): ?>
                        <a href="kembalikan_alat.php?id_peminjaman=<?php echo $id_peminjaman; ?>" class="btn w-100 btn-pinjam mt-auto <?php echo $btn_class; ?>">
                            <?php echo $btn_text; ?>
                        </a>
                    <?php else: ?>
                        <button class="btn w-100 btn-pinjam mt-auto <?php echo $btn_class; ?>" 
                                <?php echo $is_disabled; ?>
                                <?php echo $target_modal; ?>
                                data-nama="<?php echo $data['nama_alat']; ?>" 
                                data-id="<?php echo $data['id']; ?>">
                            <?php echo $btn_text; ?>
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
        <div class="modal-content shadow-lg">
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
                        <label class="form-label small fw-bold">Jumlah Pinjam</label>
                        <input type="number" name="jumlah_pinjam" class="form-control" min="1" value="1" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Tgl Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Tgl Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keperluan</label>
                        <textarea name="tujuan" class="form-control" rows="2" placeholder="Contoh: Tugas Akhir / Praktikum" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Upload Foto KTM</label>
                        <input type="file" name="foto_ktm" class="form-control" accept="image/*" required>
                        <small class="text-muted d-block mt-1">Wajib dilampirkan sebagai syarat peminjaman.</small>
                    </div>

                    <div class="form-check small text-muted">
                        <input class="form-check-input" type="checkbox" required id="agree">
                        <label class="form-check-label" for="agree">
                            Saya bertanggung jawab atas keutuhan alat yang dipinjam.
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="pinjam" class="btn btn-primary rounded-3 px-4" style="background-color: #ff0000; border:none;">Kirim Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    var modalPinjam = document.getElementById('modalPinjam')
    modalPinjam.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget 
        var nama = button.getAttribute('data-nama')
        var id = button.getAttribute('data-id')

        var displayNama = modalPinjam.querySelector('#displayNamaAlat')
        var inputId = modalPinjam.querySelector('#inputAlatId')

        displayNama.textContent = nama
        inputId.value = id
    })
</script>

</body>
</html>