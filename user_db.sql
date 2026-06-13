-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 27, 2026 at 02:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(255) NOT NULL,
  `jumlah_unit` int(11) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `status` enum('Tersedia','Dipinjam','Habis') DEFAULT 'Tersedia',
  `spesifikasi_1` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `status_barang` enum('Tersedia','Habis') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daftar_alat`
--

CREATE TABLE `daftar_alat` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `jumlah_unit` int(11) DEFAULT 0,
  `status` enum('Tersedia','Dipinjam','Perbaikan') DEFAULT 'Tersedia',
  `spesifikasi_1` varchar(100) DEFAULT NULL,
  `spesifikasi_2` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_alat`
--

INSERT INTO `daftar_alat` (`id`, `nama_alat`, `lokasi`, `jumlah_unit`, `status`, `spesifikasi_1`, `spesifikasi_2`, `gambar`) VALUES
(5, 'Kabel Lan', '', 2, 'Tersedia', 'Kabel CAT5e:', '', '1779713194_kabelLan.jpg'),
(6, 'Kamera DSLR Canon', '', 1, 'Tersedia', 'Canon EOS 5DS', '', '1779713234_canon.jpg'),
(7, 'Modem Router TP-Link Archer AX12', '', 10, 'Tersedia', 'Teknologi Wi-Fi 6, Kecepatan hingga 1.5 Gbps (1201 Mbps pada 5 GHz & 300 Mbps pada 2.4 GHz).', '', '1779800678_routerboard.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_alat` int(11) DEFAULT NULL,
  `nama_mahasiswa` varchar(100) DEFAULT NULL,
  `npm` varchar(20) DEFAULT NULL,
  `jumlah_pinjam` int(11) DEFAULT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `status` enum('Pending','Disetujui','Ditolak','Selesai') DEFAULT 'Pending',
  `foto_ktm` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_alat`, `nama_mahasiswa`, `npm`, `jumlah_pinjam`, `tgl_pinjam`, `tgl_kembali`, `status`, `foto_ktm`) VALUES
(20, 5, 'niki', '2410631170067', 1, '2026-05-25', '2026-05-26', 'Disetujui', 'uploads/ktm/ktm_6a1444fd2e980.jpg'),
(21, 6, 'niki', '2410631170067', 1, '2026-05-25', '2026-05-26', 'Ditolak', 'uploads/ktm/ktm_6a1568ffbe498.jpg'),
(22, 6, 'niki', '2410631170067', 1, '2026-05-26', '2026-05-26', 'Ditolak', 'uploads/ktm/ktm_6a1597d028fff.jpg'),
(23, 6, 'lalala', '2410631170067', 1, '2026-05-26', '2026-05-26', 'Ditolak', 'uploads/ktm/ktm_6a1599c1c2d1d.jpg'),
(24, 6, 'stevani eka putri', '2410631170067', 1, '2026-05-27', '2026-05-28', 'Ditolak', 'uploads/ktm/ktm_6a164fe617f11.jpg'),
(25, 6, 'stevani eka putri', '2410631170067', 1, '2026-05-27', '2026-05-28', 'Ditolak', 'uploads/ktm/ktm_6a165198de675.png'),
(26, 6, 'stevani eka putri', '2410631170067', 1, '2026-05-27', '2026-05-28', 'Ditolak', 'uploads/ktm/ktm_6a165efb50d97.png'),
(27, 5, 'kiko', '2410631170090', 1, '2026-05-27', '2026-05-28', 'Disetujui', 'uploads/ktm/ktm_6a166aa885631.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `npm` varchar(20) DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `role` enum('mahasiswa','admin','dosen') DEFAULT 'mahasiswa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `npm`, `jurusan`, `role`, `created_at`) VALUES
(2, 'fachry', '2410631170017@student.unsika.ac.id', '$2y$10$xCdXqdf284cHe9lPH4um.ulf6JurXn8m2x3/aTxqftX8sNF4bMXc.', '2410631170138', 'Informatika', 'mahasiswa', '2026-05-08 09:25:37'),
(3, 'Stevani Eka Putri ', 'stevaniputri1708@gmail.com', '$2y$10$NDEAw5HkhN3zn/d/mJvtleeJB8jJ5brB2pHmiY3uiUGESoeGNVpAi', '2410631170049', 'Informatika', 'mahasiswa', '2026-05-17 23:46:52'),
(9, 'Admin Utama Lab', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', '1', NULL, 'admin', '2026-05-25 13:53:18'),
(10, 'kiko mulyadi', 'kiko@gmail.com', '$2y$10$iW9mGGcfWXXnWApdBlG1fOZMJ2vaVOZDfWIlXWyGdIJXDV6c/ae06', '2410631170090', 'Informatika', 'mahasiswa', '2026-05-27 03:35:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `daftar_alat`
--
ALTER TABLE `daftar_alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nim_nip` (`npm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daftar_alat`
--
ALTER TABLE `daftar_alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_alat`) REFERENCES `daftar_alat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
