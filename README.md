#  Unsika-Lab - Sistem Manajemen Peminjaman Alat Laboratorium

Projek aplikasi ini dibuat untuk memenuhi tugas Ujian Akhir Semester (UAS) Praktikum Pemrograman Berbasis Web Teknik Informatika UNSIKA.

##  Anggota Kelompok 6
1. FACHRY ADRUL MUSLIM (2410631170017)
2. HAFIDZ RASYID       (2410631170140)
3. STEVANI EKA PUTRI G (2410631170049)
   

##  Deskripsi & Tujuan Website
Unsika-Lab adalah platform berbasis web yang dirancang khusus untuk mendigitalisasi proses sirkulasi dan peminjaman alat di Laboratorium Teknik Universitas Singaperbangsa Karawang. Tujuan utama website ini adalah menggantikan sistem pencatatan fisik/manual, mempermudah validasi berkas jaminan bagi laboran, serta memberikan transparansi ketersediaan alat secara real-time kepada mahasiswa.

##  Fitur Utama Website
* **Autentikasi & Validasi Multi-User:** Memisahkan hak akses antara Mahasiswa dan Admin.
* **Live Search Bar & Tags Populer:** Mempercepat pencarian alat praktikum secara instan di halaman utama.
* **Personalized Dashboard & Session:** Menampilkan data statistik peminjaman aktif dan sapaan dinamis berdasarkan data user yang sedang login.
* **Manajemen Inventaris & Transaksi Real-Time:** Admin dapat menambah, mengedit, menghapus data alat, serta memperbarui status peminjaman mahasiswa (stok otomatis terpotong/bertambah).
* **Helpdesk & Integrasi Google Maps API:** Menyediakan tombol interaktif menuju WhatsApp/Instagram tim teknis lab beserta peta lokasi fisik gedung.

##  Struktur Project & Penjelasan File Penting
Berikut adalah susunan arsitektur direktori serta fungsi dari file-file krusial yang ada di dalam projek PEBEWE:

* **admin/** — Folder khusus yang berisi fungsionalitas dan hak akses untuk level Admin/Laboran:
  * `dashboard_admin.php` — Halaman panel utama admin untuk memantau ringkasan statistik seperti total barang, user, dan transaksi.
  * `barang_admin.php` & `data_peminjaman.php` — Mengelola manajemen data inventaris alat dan daftar riwayat transaksi mahasiswa.
  * `tambah_alat.php`, `edit_alat.php`, `hapus_alat.php` — Fitur CRUD (Create, Read, Update, Delete) data aset laboratorium.
  * `update_status.php` — Memproses logika persetujuan atau pengembalian alat yang memengaruhi jumlah stok di database.
  * `users_admin.php` & `nonaktifkan_user.php` — Manajemen data akun pengguna yang terdaftar di dalam sistem.
  * `laporan_admin.php` — Menyusun rekapitulasi data peminjaman alat lab.
* **bootstrap-5.0.2-dist/**, **css/**, **js/** — Direktori penyimpanan framework Bootstrap dan aset styling tampilan antarmuka web.
* **image/**, **upload/**, **uploads/** — Folder penyimpanan aset gambar website serta berkas unggahan jaminan (seperti foto KTM mahasiswa).
* `index.php` — Halaman autentikasi utama (Form Login & Register) sekaligus pintu gerbang awal aplikasi.
* `dashboard.php` — Halaman beranda utama mahasiswa setelah berhasil login, memuat statistik dan sapaan session.
* `alat_tersedia.php` — Menampilkan katalog daftar alat laboratorium yang siap untuk dipinjam.
* `pinjam_alat.php` & `proses_pinjam.php` — Menangani form pengajuan booking alat serta proses validasi input data mahasiswa.
* `kembalikan_alat.php` — Memproses alur pengembalian alat yang dipinjam.
* `koneksi.php` — File inti untuk mengonfigurasi dan menghubungkan script PHP dengan server database MySQL.
* `logout.php` — Menghapus data session user dan mengarahkan kembali ke halaman login secara aman.
* `prosedur.php` — Halaman informasi panduan tata cara peminjaman fasilitas laboratorium.
* `lupa_password.php` & `profile.php` — Fitur pemulihan kata sandi dan pengaturan detail data profil user.
* `database_update.sql` & `schema.php` — Berkas skema struktur tabel database database MySQL untuk projek Unsika-Lab.

##  Cara Menjalankan Aplikasi
1. Unduh atau Clone seluruh berkas dari repository GitHub ini.
2. Letakkan folder projek ini ke dalam direktori server lokal komputer Anda, yaitu di folder `C:/xampp/htdocs/`.
3. Aktifkan modul Apache dan MySQL melalui aplikasi XAMPP Control Panel.
4. Buka browser, akses `localhost/phpmyadmin`, buat database baru bernama `pebewe`, kemudian Import file `database_update.sql`.
5. Buka tab baru di browser Anda dan jalankan aplikasi dengan mengakses URL: `localhost/pebewe/index.php`.

##  Link Video Presentasi Project
* [Klik di sini untuk menonton Video Demo Aplikasi Unsika-Lab](https://drive.google.com/file/d/1q7rUn-NPF-3RDu19Tu3thnuB7BgwKow6/view?usp=sharing)
