-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 04:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan_pemweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `p_role`
--

CREATE TABLE `p_role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_role`
--

INSERT INTO `p_role` (`id_role`, `nama_role`) VALUES
(1, 'admin'),
(2, 'staff'),
(3, 'anggota');

-- --------------------------------------------------------

--
-- Table structure for table `t_account`
--

CREATE TABLE `t_account` (
  `id_account` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) DEFAULT NULL,
  `status_akun` enum('aktif','nonaktif') DEFAULT 'nonaktif',
  `create_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_account`
--

INSERT INTO `t_account` (`id_account`, `username`, `password`, `id_role`, `status_akun`, `create_date`) VALUES
(4, 'admin Mekka', 'admin123', 1, 'aktif', '2025-05-26 19:39:06'),
(5, 'staff1', 'staff123', 2, 'aktif', '2025-05-26 19:39:22'),
(6, 'anggota1', 'anggota123', 3, 'aktif', '2025-05-26 19:39:34'),
(7, 'anggota2', 'keizory123', 3, 'aktif', '2025-05-28 07:56:51'),
(8, 'dewiadmin', 'password123', 1, 'aktif', '2025-05-28 14:11:36'),
(9, 'staff2', 'staff123', 2, 'aktif', '2025-05-28 14:20:29'),
(12, 'anggota3', 'nira123', 3, 'aktif', '2025-05-28 14:26:06');

-- --------------------------------------------------------

--
-- Table structure for table `t_anggota`
--

CREATE TABLE `t_anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(15) DEFAULT NULL,
  `id_account` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_anggota`
--

INSERT INTO `t_anggota` (`id_anggota`, `nama`, `alamat`, `telepon`, `id_account`, `email`) VALUES
(6, 'Ciska', 'Jl. Pendidikan No. 10', '081234567890', 6, 'anggapratama@gmail.com\r\n'),
(7, 'Keizory davinka', 'Jl. inpress 15', '0812345678', 7, 'keizorydavinka@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_buku`
--

CREATE TABLE `t_buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `update_by` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_buku`
--

INSERT INTO `t_buku` (`id_buku`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `stok`, `create_by`, `create_date`, `update_by`, `update_date`, `gambar`) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 10, 4, '2025-05-27 15:23:25', 4, '2025-05-28 16:21:04', 'laskar_pelangi.jpg'),
(2, 'Bumi', 'Tere Liye', 'Gramedia Pustaka Utama', '2014', 1, 4, '2025-05-27 15:23:25', 4, '2025-05-28 16:06:29', 'bumi.jpg'),
(3, 'Hujan', 'Tere Liye', 'Gramedia Pustaka Utama', '2016', 10, 4, '2025-05-27 15:23:25', 4, '2025-07-27 16:22:38', 'hujan.jpg'),
(4, 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia Pustaka Utama', '2009', 10, 4, '2025-05-27 15:23:25', 4, '2025-06-27 16:21:52', 'negeri_5_menara.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `t_peminjaman`
--

CREATE TABLE `t_peminjaman` (
  `id_pinjam` int(11) NOT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `id_anggota` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_batas` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('pinjam','kembali') DEFAULT 'pinjam',
  `create_by` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `status_peminjaman` varchar(50) NOT NULL DEFAULT 'meminjam',
  `denda` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_peminjaman`
--

INSERT INTO `t_peminjaman` (`id_pinjam`, `id_buku`, `id_anggota`, `tanggal_pinjam`, `tanggal_batas`, `tanggal_kembali`, `status`, `create_by`, `create_date`, `status_peminjaman`, `denda`) VALUES
(3, 1, 6, '2025-05-27', NULL, '2025-05-27', 'pinjam', 6, '2025-05-27 16:01:31', 'dikembalikan', 0),
(4, 3, 6, '2025-05-27', NULL, '2025-05-27', 'pinjam', 6, '2025-05-27 16:03:52', 'dikembalikan', 0),
(5, 5, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 02:51:00', 'dikembalikan', 0),
(6, 1, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 07:55:30', 'dikembalikan', 0),
(7, 2, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 07:55:30', 'dikembalikan', 0),
(9, 1, 7, '2025-05-28', NULL, '2025-05-28', 'pinjam', 7, '2025-05-28 03:03:37', 'dikembalikan', 0),
(10, 4, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 07:40:30', 'dikembalikan', 0),
(18, 2, 7, '2025-05-28', NULL, '2025-06-04', 'pinjam', 7, '2025-05-28 09:33:07', 'dipinjam', 0),
(19, 2, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 10:23:28', 'dikembalikan', 0),
(20, 1, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 11:04:03', 'dikembalikan', 0),
(21, 4, 6, '2025-05-28', NULL, '2025-05-29', 'pinjam', 6, '2025-05-28 11:08:51', 'dikembalikan', 0),
(22, 3, 6, '2025-05-29', NULL, '2025-06-14', 'pinjam', 6, '2025-05-29 11:13:42', 'dikembalikan', 20252917),
(23, 3, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 11:16:09', 'dikembalikan', 20235917),
(24, 4, 6, '2025-05-28', NULL, '2025-05-28', 'pinjam', 6, '2025-05-28 11:16:20', 'dikembalikan', 20235917),
(25, 1, 6, '2025-06-26', NULL, '2025-05-28', 'pinjam', 6, '2025-06-26 11:17:15', 'dikembalikan', 20235917),
(26, 3, 6, '2025-05-28', '2025-06-04', '2025-05-28', 'pinjam', 6, '2025-05-28 11:20:35', 'dikembalikan', 0),
(27, 4, 6, '2025-06-27', '2025-07-04', '2025-06-27', 'pinjam', 6, '2025-06-27 11:21:40', 'dikembalikan', 0),
(28, 3, 6, '2025-06-27', '2025-07-04', '2025-07-27', 'pinjam', 6, '2025-06-27 11:22:07', 'dikembalikan', 23000);

-- --------------------------------------------------------

--
-- Table structure for table `t_staff`
--

CREATE TABLE `t_staff` (
  `id_staff` int(11) NOT NULL,
  `id_account` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_staff`
--

INSERT INTO `t_staff` (`id_staff`, `id_account`, `nama`, `email`, `telepon`, `alamat`) VALUES
(3, 8, 'Dewi Anggraeni', 'dewi.anggraeni@example.com', '081234567890', 'Jl. Merpati No. 45, Surabaya'),
(4, 9, 'vira', 'viraamelia@gmail.com', '0856789876', 'menjangan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `p_role`
--
ALTER TABLE `p_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `t_account`
--
ALTER TABLE `t_account`
  ADD PRIMARY KEY (`id_account`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_role` (`id_role`);

--
-- Indexes for table `t_anggota`
--
ALTER TABLE `t_anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD KEY `t_anggota_ibfk_1` (`id_account`);

--
-- Indexes for table `t_buku`
--
ALTER TABLE `t_buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `create_by` (`create_by`),
  ADD KEY `update_by` (`update_by`);

--
-- Indexes for table `t_peminjaman`
--
ALTER TABLE `t_peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `create_by` (`create_by`);

--
-- Indexes for table `t_staff`
--
ALTER TABLE `t_staff`
  ADD PRIMARY KEY (`id_staff`),
  ADD KEY `id_account` (`id_account`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `p_role`
--
ALTER TABLE `p_role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_account`
--
ALTER TABLE `t_account`
  MODIFY `id_account` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `t_anggota`
--
ALTER TABLE `t_anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `t_buku`
--
ALTER TABLE `t_buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `t_peminjaman`
--
ALTER TABLE `t_peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `t_staff`
--
ALTER TABLE `t_staff`
  MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_account`
--
ALTER TABLE `t_account`
  ADD CONSTRAINT `t_account_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `p_role` (`id_role`);

--
-- Constraints for table `t_anggota`
--
ALTER TABLE `t_anggota`
  ADD CONSTRAINT `t_anggota_ibfk_1` FOREIGN KEY (`id_account`) REFERENCES `t_account` (`id_account`) ON DELETE CASCADE;

--
-- Constraints for table `t_buku`
--
ALTER TABLE `t_buku`
  ADD CONSTRAINT `t_buku_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `t_account` (`id_account`),
  ADD CONSTRAINT `t_buku_ibfk_2` FOREIGN KEY (`update_by`) REFERENCES `t_account` (`id_account`);

--
-- Constraints for table `t_peminjaman`
--
ALTER TABLE `t_peminjaman`
  ADD CONSTRAINT `t_peminjaman_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `t_anggota` (`id_anggota`),
  ADD CONSTRAINT `t_peminjaman_ibfk_2` FOREIGN KEY (`create_by`) REFERENCES `t_account` (`id_account`);

--
-- Constraints for table `t_staff`
--
ALTER TABLE `t_staff`
  ADD CONSTRAINT `t_staff_ibfk_1` FOREIGN KEY (`id_account`) REFERENCES `t_account` (`id_account`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
