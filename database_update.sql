UPDATE daftar_alat SET status = 'Available' WHERE status = 'Tersedia';
UPDATE daftar_alat SET status = 'Borrowed' WHERE status = 'Dipinjam';
UPDATE daftar_alat SET status = 'Broken' WHERE status = 'Rusak';

UPDATE peminjaman SET status = 'Pending' WHERE status = 'Menunggu';
UPDATE peminjaman SET status = 'Approved' WHERE status = 'Disetujui';
UPDATE peminjaman SET status = 'Returned' WHERE status = 'Selesai';
UPDATE peminjaman SET status = 'Rejected' WHERE status = 'Ditolak';

ALTER TABLE `peminjaman` MODIFY `status` ENUM('Pending', 'Approved', 'Returned', 'Rejected') NOT NULL DEFAULT 'Pending';
ALTER TABLE `daftar_alat` MODIFY `status` ENUM('Available', 'Borrowed', 'Broken') NOT NULL DEFAULT 'Available';
