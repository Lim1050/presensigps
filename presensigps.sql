-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2024 at 04:00 PM
-- Server version: 8.0.33
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensigps`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashbon`
--

CREATE TABLE `cashbon` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_cashbon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','diterima','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cashbon`
--

INSERT INTO `cashbon` (`id`, `kode_cashbon`, `nik`, `tanggal_pengajuan`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(1, 'CB1A2B3C', '123123123', '2024-10-24', 50000.00, 'Test Cashbon', 'ditolak', '2024-10-24 03:53:33', '2024-11-10 10:40:16'),
(2, 'CB2B3C4D', '123123123', '2024-10-31', 100000.00, 'Test lagi Cashbon', 'pending', '2024-10-24 05:01:03', '2024-11-10 10:40:31'),
(3, 'CBXWL175', '123123123', '2024-10-25', 10000.00, 'test code cashbon', 'diterima', '2024-10-24 08:39:00', '2024-10-25 10:02:24'),
(4, 'CBI0E781', '123456789', '2024-10-25', 20000.00, 'naefdoncalksj', 'ditolak', '2024-10-25 06:11:26', '2024-11-10 10:40:58'),
(5, 'CB2CD594', '123123123', '2024-11-09', 10000.00, 'test limit', 'diterima', '2024-10-25 10:01:17', '2024-11-12 08:16:42'),
(6, 'CBJWZ938', '123123123', '2024-10-26', 100000.00, 'test edit cashbon', 'diterima', '2024-10-26 05:37:44', '2024-10-26 05:58:03'),
(9, 'CBQBL599', '123123123', '2024-10-27', 2000.00, 'test 2000', 'pending', '2024-10-26 07:03:33', '2024-10-26 07:03:33'),
(10, 'CBJEH963', '123123123', '2025-11-13', 50000.00, 'test', 'diterima', '2024-11-12 08:20:37', '2024-11-12 08:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `cashbon_karyawan_limit`
--

CREATE TABLE `cashbon_karyawan_limit` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `limit` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cashbon_karyawan_limit`
--

INSERT INTO `cashbon_karyawan_limit` (`id`, `nik`, `limit`, `created_at`, `updated_at`) VALUES
(1, '123123123', 1500000.00, '2024-10-25 09:39:06', '2024-11-12 08:29:44'),
(2, '123456789', 1500000.00, '2024-10-25 09:47:59', '2024-11-12 08:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `cashbon_limit`
--

CREATE TABLE `cashbon_limit` (
  `id` bigint UNSIGNED NOT NULL,
  `global_limit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cashbon_limit`
--

INSERT INTO `cashbon_limit` (`id`, `global_limit`, `created_at`, `updated_at`) VALUES
(1, 1500000.00, '2024-10-25 09:29:38', '2024-11-12 08:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `kode_departemen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_departemen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`kode_departemen`, `nama_departemen`, `created_at`, `updated_at`) VALUES
('SAG', 'Security Area Gudang', '2024-10-14 15:28:21', NULL),
('SAP', 'Security Area Pabrik', '2024-10-14 15:29:33', NULL),
('SEC', 'Security', '2024-10-14 15:25:43', NULL),
('SGP', 'Security Gedung Parkir', '2024-10-14 15:29:13', NULL),
('SKP', 'Security Kantor Pusat', '2024-10-14 15:28:09', NULL),
('SM', 'Security Mall', '2024-10-14 15:29:22', NULL),
('SPMU', 'Security Pintu Masuk Utama', '2024-10-14 15:29:54', NULL),
('TKM', 'Tim Keamanan Malam', '2024-10-14 15:30:05', NULL),
('TRC', 'Tim Respon Cepat', '2024-10-14 15:29:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` (
  `kode_gaji` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_lokasi_penugasan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jenis_gaji` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_gaji` enum('Gaji tetap','Tunjangan jabatan','Transportasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_gaji` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_gaji` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gaji`
--

INSERT INTO `gaji` (`kode_gaji`, `kode_jabatan`, `kode_lokasi_penugasan`, `kode_cabang`, `kode_jenis_gaji`, `jenis_gaji`, `nama_gaji`, `jumlah_gaji`, `created_at`, `updated_at`) VALUES
('GTKK', 'KEPKAM', 'MN', 'JKTP', 'GT', 'Gaji tetap', 'Gaji Tetap Kepala Keamanan', 5000000.00, '2024-10-15 05:36:13', '2024-10-22 05:59:20'),
('GTPC', 'PCCTV', 'MN', 'JKTP', 'GT', 'Gaji tetap', 'Gaji Tetap Petugas CCTV', 4000000.00, '2024-10-15 05:38:02', '2024-10-22 05:56:21'),
('GTPKG', 'PKG', 'SMB', 'BKS', 'GT', 'Gaji tetap', 'Gaji Tetap Petugas Kontrol Gedung', 4000000.00, '2024-10-15 05:40:30', '2024-10-22 05:52:40'),
('GTPM', 'PENMAL', 'MN', 'JKTP', 'GT', 'Gaji tetap', 'Gaji Tetap Penjaga Malam', 4000000.00, '2024-10-15 05:39:09', '2024-10-22 05:55:22'),
('GTPPM', 'PPM', 'SMB', 'BKS', 'GT', 'Gaji tetap', 'Gaji Tetap Petugas Pintu Masuk', 3500000.00, '2024-10-15 05:41:57', '2024-10-22 05:58:18'),
('GTSK', 'SUPKAM', 'GR', 'JKTT', 'GT', 'Gaji tetap', 'Gaji Tetap Supervisor Keamanan', 5000000.00, '2024-10-15 05:45:15', '2024-10-22 05:57:32'),
('GTSO', 'SO', 'SMB', 'BKS', 'GT', 'Gaji tetap', 'Gaji Tetap Security Officer', 3750000.00, '2024-10-15 05:43:40', '2024-10-22 05:54:44'),
('GTWKK', 'WAKAM', 'MN', 'JKTP', 'GT', 'Gaji tetap', 'Gaji Tetap Wakil Kepala Keamanan', 4500000.00, '2024-10-15 05:46:22', '2024-10-22 05:55:49'),
('LKK', 'KEPKAM', 'MN', 'JKTP', 'L', NULL, 'Lembur Kepala Keamanan (per jam)', 20000.00, '2024-11-05 13:37:28', '2024-11-05 13:37:28'),
('TJKK', 'KEPKAM', 'MN', 'JKTP', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Kepala Keamanan', 2500000.00, '2024-10-15 05:36:51', '2024-10-22 05:59:26'),
('TJPC', 'PCCTV', 'MN', 'JKTP', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Petugas CCTV', 2000000.00, '2024-10-15 05:38:38', '2024-10-22 05:57:11'),
('TJPKG', 'PKG', 'SMB', 'BKS', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Petugas Kontrol Gedung', 2000000.00, '2024-10-15 05:41:04', '2024-10-22 05:54:16'),
('TJPM', 'PENMAL', 'MN', 'JKTP', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Penjaga Malam', 2000000.00, '2024-10-15 05:39:41', '2024-10-22 05:55:28'),
('TJPPM', 'PPM', 'SMB', 'BKS', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Petugas Pintu Masuk', 1750000.00, '2024-10-15 05:42:50', '2024-10-22 05:58:26'),
('TJSK', 'SUPKAM', 'GR', 'JKTT', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Supervisor Keamanan', 2500000.00, '2024-10-15 05:45:44', '2024-10-22 05:57:47'),
('TJSO', 'SO', 'SMB', 'BKS', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Security Officer', 1875000.00, '2024-10-15 05:44:22', '2024-10-22 05:54:52'),
('TJWKK', 'WAKAM', 'MN', 'JKTP', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Wakil Kepala Keamanan', 2250000.00, '2024-10-15 05:47:11', '2024-10-22 05:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `kode_jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`kode_jabatan`, `nama_jabatan`, `created_at`, `updated_at`) VALUES
('KEPKAM', 'Kepala Keamanan', '2024-10-14 15:30:35', '2024-10-14 15:30:35'),
('PCCTV', 'Petugas CCTV', '2024-10-14 15:31:15', '2024-10-14 15:31:15'),
('PENMAL', 'Penjaga Malam', '2024-10-14 15:31:33', '2024-10-14 15:31:33'),
('PKG', 'Petugas Kontrol Gedung', '2024-10-14 15:31:45', '2024-10-14 15:31:45'),
('PPM', 'Petugas Pintu Masuk', '2024-10-14 15:31:24', '2024-10-14 15:31:24'),
('SO', 'Security Officer', '2024-10-14 15:31:07', '2024-10-14 15:31:07'),
('SUPKAM', 'Supervisor Keamanan', '2024-10-14 15:30:58', '2024-10-14 15:30:58'),
('WAKAM', 'Wakil Kepala Keamanan', '2024-10-14 15:30:48', '2024-10-14 15:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja`
--

CREATE TABLE `jam_kerja` (
  `kode_jam_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_lokasi_penugasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_jam_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `awal_jam_masuk` time NOT NULL,
  `jam_masuk` time NOT NULL,
  `akhir_jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `lintas_hari` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja`
--

INSERT INTO `jam_kerja` (`kode_jam_kerja`, `kode_lokasi_penugasan`, `kode_cabang`, `nama_jam_kerja`, `awal_jam_masuk`, `jam_masuk`, `akhir_jam_masuk`, `jam_pulang`, `lintas_hari`, `created_at`, `updated_at`) VALUES
('JK01', 'GR', 'JKTT', 'Shift Pagi', '00:00:00', '01:00:00', '02:00:00', '08:00:00', '0', NULL, '2024-11-05 06:22:40'),
('JK02', 'GR', 'JKTT', 'Shift Siang', '16:00:00', '16:10:00', '16:20:00', '16:30:00', '0', NULL, '2024-11-05 06:22:49'),
('JK03', 'GR', 'JKTT', 'Shift Malam', '15:40:00', '15:45:00', '15:50:00', '16:00:00', '1', NULL, '2024-11-05 06:22:56'),
('JKHIBKSSS', 'HI', 'BKS', 'Shift Siang', '08:00:00', '09:00:00', '10:00:00', '21:00:00', '0', '2024-11-05 14:53:01', '2024-11-05 14:53:01'),
('JKMNJKTPSM', 'MN', 'JKTP', 'Shift Malam', '20:50:00', '21:10:00', '21:20:00', '21:30:00', '0', '2024-11-04 08:37:57', '2024-11-05 14:05:39'),
('JKMNJKTPSP', 'MN', 'JKTP', 'Shift Pagi', '00:00:00', '00:30:00', '01:00:00', '08:00:00', '0', '2024-11-04 08:34:27', '2024-11-04 08:34:27'),
('JKMNJKTPSS', 'MN', 'JKTP', 'Shift Siang', '08:00:00', '08:30:00', '09:00:00', '16:00:00', '0', '2024-11-04 08:36:55', '2024-11-04 08:36:55'),
('JKPIKJKTPSM', 'PIK', 'JKTP', 'Shift Malam', '18:00:00', '18:30:00', '19:00:00', '02:30:00', '1', '2024-11-05 15:08:47', '2024-11-05 15:08:47'),
('JKPIKJKTPSP', 'PIK', 'JKTP', 'Shift Pagi', '00:00:00', '01:00:00', '02:00:00', '09:00:00', '0', '2024-11-05 15:19:28', '2024-11-05 15:37:54'),
('JKPIKJKTPSS', 'PIK', 'JKTP', 'Shift Siang', '08:00:00', '09:00:00', '10:00:00', '17:00:00', '0', '2024-11-05 15:15:39', '2024-11-05 15:15:39'),
('JKSMBBKSSM', 'SMB', 'BKS', 'Shift Malam', '17:00:00', '18:00:00', '19:00:00', '00:00:00', '1', '2024-11-05 06:30:50', '2024-11-05 06:30:50'),
('JKSMBBKSSP', 'SMB', 'BKS', 'Shift Pagi', '00:00:00', '01:00:00', '02:00:00', '08:00:00', '0', '2024-11-05 06:29:47', '2024-11-05 06:29:47'),
('JKSMBBKSSS', 'SMB', 'BKS', 'Shift Siang', '08:00:00', '09:00:00', '10:00:00', '17:00:00', '0', '2024-11-05 06:30:25', '2024-11-05 06:30:25');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_dept`
--

CREATE TABLE `jam_kerja_dept` (
  `kode_jk_dept` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_cabang` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_departemen` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja_dept`
--

INSERT INTO `jam_kerja_dept` (`kode_jk_dept`, `kode_cabang`, `kode_departemen`, `created_at`, `updated_at`) VALUES
('JBKSSEC', 'BKS', 'SEC', '2024-11-14 08:29:03', NULL),
('JBKSSM', 'BKS', 'SM', '2024-07-19 07:07:49', NULL),
('JJKTPSEC', 'JKTP', 'SEC', '2024-11-14 08:00:14', NULL),
('JJKTPSKP', 'JKTP', 'SKP', '2024-10-14 15:57:27', NULL),
('JJKTPTKM', 'JKTP', 'TKM', '2024-10-14 16:03:29', NULL),
('JJKTTSM', 'JKTT', 'SM', '2024-07-18 08:29:04', NULL),
('JJKTTTRC', 'JKTT', 'TRC', '2024-11-14 07:57:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_dept_detail`
--

CREATE TABLE `jam_kerja_dept_detail` (
  `kode_jk_dept` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hari` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jam_kerja` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja_dept_detail`
--

INSERT INTO `jam_kerja_dept_detail` (`kode_jk_dept`, `hari`, `kode_jam_kerja`, `created_at`, `updated_at`) VALUES
('JJKTTSM', 'Senin', 'JK03', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Selasa', 'JK02', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Rabu', 'JK03', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Kamis', 'JK02', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Jumat', 'JK03', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Sabtu', 'JK02', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Minggu', 'JK03', NULL, '2024-07-18 08:29:34'),
('JJKTTTRC', 'Senin', 'JK01', '2024-11-14 07:57:40', NULL),
('JJKTTTRC', 'Selasa', 'JK02', '2024-11-14 07:57:40', NULL),
('JJKTTTRC', 'Rabu', 'JK03', '2024-11-14 07:57:40', NULL),
('JJKTTTRC', 'Kamis', 'JK01', '2024-11-14 07:57:40', NULL),
('JJKTTTRC', 'Jumat', 'JK02', '2024-11-14 07:57:40', NULL),
('JJKTTTRC', 'Sabtu', 'JK03', '2024-11-14 07:57:40', NULL),
('JJKTTTRC', 'Minggu', 'JK01', '2024-11-14 07:57:40', NULL),
('JJKTPSEC', 'Senin', 'JKMNJKTPSM', '2024-11-14 08:00:14', NULL),
('JJKTPSEC', 'Selasa', 'JKMNJKTPSP', '2024-11-14 08:00:14', NULL),
('JJKTPSEC', 'Rabu', 'JKMNJKTPSS', '2024-11-14 08:00:14', NULL),
('JJKTPSEC', 'Kamis', 'JKPIKJKTPSM', '2024-11-14 08:00:14', NULL),
('JJKTPSEC', 'Jumat', 'JKPIKJKTPSP', '2024-11-14 08:00:14', NULL),
('JJKTPSEC', 'Sabtu', 'JKPIKJKTPSS', '2024-11-14 08:00:14', NULL),
('JJKTPSEC', 'Minggu', 'JKMNJKTPSM', '2024-11-14 08:00:14', NULL),
('JBKSSEC', 'Senin', 'JKHIBKSSS', '2024-11-14 08:46:03', '2024-11-14 08:46:03'),
('JBKSSEC', 'Selasa', 'JKSMBBKSSM', '2024-11-14 08:46:03', '2024-11-14 08:46:03'),
('JBKSSEC', 'Rabu', 'JKSMBBKSSP', '2024-11-14 08:46:03', '2024-11-14 08:46:03'),
('JBKSSEC', 'Kamis', 'JKSMBBKSSS', '2024-11-14 08:46:03', '2024-11-14 08:46:03'),
('JBKSSEC', 'Jumat', 'JKHIBKSSS', '2024-11-14 08:46:03', '2024-11-14 08:46:03'),
('JBKSSEC', 'Sabtu', 'JKSMBBKSSM', '2024-11-14 08:46:03', '2024-11-14 08:46:03'),
('JBKSSEC', 'Minggu', 'JKSMBBKSSP', '2024-11-14 08:46:03', '2024-11-14 08:46:03'),
('JBKSSM', 'Senin', 'JKHIBKSSS', '2024-11-14 08:50:32', '2024-11-14 08:50:32'),
('JBKSSM', 'Selasa', 'JKSMBBKSSM', '2024-11-14 08:50:32', '2024-11-14 08:50:32'),
('JBKSSM', 'Rabu', 'JKSMBBKSSP', '2024-11-14 08:50:32', '2024-11-14 08:50:32'),
('JBKSSM', 'Kamis', 'JKHIBKSSS', '2024-11-14 08:50:32', '2024-11-14 08:50:32'),
('JBKSSM', 'Jumat', 'JKSMBBKSSM', '2024-11-14 08:50:32', '2024-11-14 08:50:32'),
('JBKSSM', 'Sabtu', 'JKSMBBKSSP', '2024-11-14 08:50:32', '2024-11-14 08:50:32'),
('JBKSSM', 'Minggu', 'JKSMBBKSSS', '2024-11-14 08:50:32', '2024-11-14 08:50:32'),
('JJKTPSKP', 'Senin', 'JKMNJKTPSM', '2024-11-14 08:51:00', '2024-11-14 08:51:00'),
('JJKTPSKP', 'Selasa', 'JKMNJKTPSP', '2024-11-14 08:51:00', '2024-11-14 08:51:00'),
('JJKTPSKP', 'Rabu', 'JKMNJKTPSS', '2024-11-14 08:51:00', '2024-11-14 08:51:00'),
('JJKTPSKP', 'Kamis', 'JKMNJKTPSM', '2024-11-14 08:51:00', '2024-11-14 08:51:00'),
('JJKTPSKP', 'Jumat', 'JKMNJKTPSP', '2024-11-14 08:51:00', '2024-11-14 08:51:00'),
('JJKTPSKP', 'Sabtu', 'JKPIKJKTPSM', '2024-11-14 08:51:00', '2024-11-14 08:51:00'),
('JJKTPSKP', 'Minggu', 'JKPIKJKTPSS', '2024-11-14 08:51:00', '2024-11-14 08:51:00'),
('JJKTPTKM', 'Senin', 'JKMNJKTPSM', '2024-11-14 08:51:19', '2024-11-14 08:51:19'),
('JJKTPTKM', 'Selasa', 'JKPIKJKTPSM', '2024-11-14 08:51:19', '2024-11-14 08:51:19'),
('JJKTPTKM', 'Rabu', 'JKMNJKTPSM', '2024-11-14 08:51:19', '2024-11-14 08:51:19'),
('JJKTPTKM', 'Kamis', 'JKMNJKTPSM', '2024-11-14 08:51:19', '2024-11-14 08:51:19'),
('JJKTPTKM', 'Jumat', 'JKPIKJKTPSM', '2024-11-14 08:51:19', '2024-11-14 08:51:19'),
('JJKTPTKM', 'Sabtu', 'JKPIKJKTPSM', '2024-11-14 08:51:19', '2024-11-14 08:51:19'),
('JJKTPTKM', 'Minggu', 'JKMNJKTPSM', '2024-11-14 08:51:19', '2024-11-14 08:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_karyawan`
--

CREATE TABLE `jam_kerja_karyawan` (
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hari` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jam_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja_karyawan`
--

INSERT INTO `jam_kerja_karyawan` (`nik`, `hari`, `kode_jam_kerja`, `created_at`, `updated_at`) VALUES
('123456789', 'Senin', 'JK02', '2024-07-28 03:49:09', '2024-07-28 03:49:09'),
('123456789', 'Selasa', 'JK03', '2024-07-28 03:49:09', '2024-07-28 03:49:09'),
('123456789', 'Rabu', 'JK01', '2024-07-28 03:49:09', '2024-07-28 03:49:09'),
('123456789', 'Kamis', 'JK02', '2024-07-28 03:49:09', '2024-07-28 03:49:09'),
('123456789', 'Jumat', 'JK03', '2024-07-28 03:49:09', '2024-07-28 03:49:09'),
('123456789', 'Sabtu', 'JK01', '2024-07-28 03:49:09', '2024-07-28 03:49:09'),
('123456789', 'Minggu', 'JK02', '2024-07-28 03:49:09', '2024-07-28 03:49:09'),
('321321321', 'Senin', 'JK01', '2024-07-28 03:49:15', '2024-07-28 03:49:15'),
('321321321', 'Selasa', 'JK03', '2024-07-28 03:49:15', '2024-07-28 03:49:15'),
('321321321', 'Rabu', 'JK03', '2024-07-28 03:49:15', '2024-07-28 03:49:15'),
('321321321', 'Kamis', 'JK03', '2024-07-28 03:49:15', '2024-07-28 03:49:15'),
('321321321', 'Jumat', 'JK01', '2024-07-28 03:49:15', '2024-07-28 03:49:15'),
('321321321', 'Sabtu', 'JK02', '2024-07-28 03:49:15', '2024-07-28 03:49:15'),
('321321321', 'Minggu', 'JK02', '2024-07-28 03:49:15', '2024-07-28 03:49:15'),
('213213213', 'Senin', 'JK03', '2024-07-28 03:49:27', '2024-07-28 03:49:27'),
('213213213', 'Selasa', 'JK02', '2024-07-28 03:49:27', '2024-07-28 03:49:27'),
('213213213', 'Rabu', 'JK01', '2024-07-28 03:49:27', '2024-07-28 03:49:27'),
('213213213', 'Kamis', 'JK01', '2024-07-28 03:49:27', '2024-07-28 03:49:27'),
('213213213', 'Jumat', 'JK02', '2024-07-28 03:49:27', '2024-07-28 03:49:27'),
('213213213', 'Sabtu', 'JK03', '2024-07-28 03:49:27', '2024-07-28 03:49:27'),
('213213213', 'Minggu', 'JK02', '2024-07-28 03:49:27', '2024-07-28 03:49:27'),
('222-222-222', 'Senin', NULL, '2024-07-28 03:49:44', NULL),
('222-222-222', 'Selasa', NULL, '2024-07-28 03:49:44', NULL),
('222-222-222', 'Rabu', NULL, '2024-07-28 03:49:44', NULL),
('222-222-222', 'Kamis', NULL, '2024-07-28 03:49:44', NULL),
('222-222-222', 'Jumat', NULL, '2024-07-28 03:49:44', NULL),
('222-222-222', 'Sabtu', NULL, '2024-07-28 03:49:44', NULL),
('222-222-222', 'Minggu', 'JK02', '2024-07-28 03:49:44', NULL),
('444444444', 'Senin', 'JK01', '2024-08-07 07:55:51', '2024-08-07 07:55:51'),
('444444444', 'Selasa', 'JK02', '2024-08-07 07:55:51', '2024-08-07 07:55:51'),
('444444444', 'Rabu', 'JK03', '2024-08-07 07:55:51', '2024-08-07 07:55:51'),
('444444444', 'Kamis', 'JK01', '2024-08-07 07:55:51', '2024-08-07 07:55:51'),
('444444444', 'Jumat', 'JK02', '2024-08-07 07:55:51', '2024-08-07 07:55:51'),
('444444444', 'Sabtu', 'JK03', '2024-08-07 07:55:51', '2024-08-07 07:55:51'),
('444444444', 'Minggu', 'JK01', '2024-08-07 07:55:51', '2024-08-07 07:55:51'),
('369258147', 'Senin', 'JK01', '2024-08-07 07:56:10', '2024-08-07 07:56:10'),
('369258147', 'Selasa', 'JK02', '2024-08-07 07:56:10', '2024-08-07 07:56:10'),
('369258147', 'Rabu', 'JK03', '2024-08-07 07:56:10', '2024-08-07 07:56:10'),
('369258147', 'Kamis', 'JK01', '2024-08-07 07:56:10', '2024-08-07 07:56:10'),
('369258147', 'Jumat', 'JK02', '2024-08-07 07:56:10', '2024-08-07 07:56:10'),
('369258147', 'Sabtu', 'JK03', '2024-08-07 07:56:10', '2024-08-07 07:56:10'),
('369258147', 'Minggu', 'JK01', '2024-08-07 07:56:10', '2024-08-07 07:56:10'),
('555555555', 'Senin', 'JKT01', '2024-08-07 07:57:09', '2024-08-07 07:57:09'),
('555555555', 'Selasa', 'JKT', '2024-08-07 07:57:09', '2024-08-07 07:57:09'),
('555555555', 'Rabu', 'JKT01', '2024-08-07 07:57:09', '2024-08-07 07:57:09'),
('555555555', 'Kamis', 'JKT', '2024-08-07 07:57:09', '2024-08-07 07:57:09'),
('555555555', 'Jumat', 'JKT01', '2024-08-07 07:57:09', '2024-08-07 07:57:09'),
('555555555', 'Sabtu', 'JKT', '2024-08-07 07:57:09', '2024-08-07 07:57:09'),
('555555555', 'Minggu', 'JKT01', '2024-08-07 07:57:09', '2024-08-07 07:57:09'),
('222222222', 'Senin', 'JKT01', '2024-08-07 07:57:25', '2024-08-07 07:57:25'),
('222222222', 'Selasa', 'JKT', '2024-08-07 07:57:25', '2024-08-07 07:57:25'),
('222222222', 'Rabu', 'JKT01', '2024-08-07 07:57:25', '2024-08-07 07:57:25'),
('222222222', 'Kamis', 'JKT', '2024-08-07 07:57:25', '2024-08-07 07:57:25'),
('222222222', 'Jumat', 'JKT01', '2024-08-07 07:57:25', '2024-08-07 07:57:25'),
('222222222', 'Sabtu', 'JKT', '2024-08-07 07:57:25', '2024-08-07 07:57:25'),
('222222222', 'Minggu', 'JKT01', '2024-08-07 07:57:25', '2024-08-07 07:57:25'),
('333333333', 'Senin', 'JK01', '2024-08-07 08:04:53', '2024-08-07 08:04:53'),
('333333333', 'Selasa', 'JKT01', '2024-08-07 08:04:53', '2024-08-07 08:04:53'),
('333333333', 'Rabu', 'JKT01', '2024-08-07 08:04:53', '2024-08-07 08:04:53'),
('333333333', 'Kamis', NULL, '2024-08-07 08:04:53', '2024-08-07 08:04:53'),
('333333333', 'Jumat', 'JK03', '2024-08-07 08:04:53', '2024-08-07 08:04:53'),
('333333333', 'Sabtu', NULL, '2024-08-07 08:04:53', '2024-08-07 08:04:53'),
('333333333', 'Minggu', 'JK02', '2024-08-07 08:04:53', '2024-08-07 08:04:53'),
('112233445', 'Senin', 'JK02', '2024-10-14 15:58:04', '2024-10-14 15:58:04'),
('112233445', 'Selasa', 'JK02', '2024-10-14 15:58:04', '2024-10-14 15:58:04'),
('112233445', 'Rabu', 'JK02', '2024-10-14 15:58:04', '2024-10-14 15:58:04'),
('112233445', 'Kamis', 'JK02', '2024-10-14 15:58:04', '2024-10-14 15:58:04'),
('112233445', 'Jumat', 'JK02', '2024-10-14 15:58:04', '2024-10-14 15:58:04'),
('112233445', 'Sabtu', 'JK02', '2024-10-14 15:58:04', '2024-10-14 15:58:04'),
('112233445', 'Minggu', 'JK02', '2024-10-14 15:58:04', '2024-10-14 15:58:04'),
('123123123', 'Senin', 'JKMNJKTPSM', '2024-11-05 06:21:30', '2024-11-05 06:21:30'),
('123123123', 'Selasa', 'JKMNJKTPSM', '2024-11-05 06:21:30', '2024-11-05 06:21:30'),
('123123123', 'Rabu', 'JKMNJKTPSM', '2024-11-05 06:21:30', '2024-11-05 06:21:30'),
('123123123', 'Kamis', 'JKMNJKTPSM', '2024-11-05 06:21:30', '2024-11-05 06:21:30'),
('123123123', 'Jumat', 'JKMNJKTPSM', '2024-11-05 06:21:30', '2024-11-05 06:21:30'),
('123123123', 'Sabtu', 'JKMNJKTPSM', '2024-11-05 06:21:30', '2024-11-05 06:21:30'),
('123123123', 'Minggu', 'JKMNJKTPSM', '2024-11-05 06:21:30', '2024-11-05 06:21:30'),
('11111111', 'Senin', NULL, '2024-11-11 05:58:46', '2024-11-11 05:58:46'),
('11111111', 'Selasa', NULL, '2024-11-11 05:58:46', '2024-11-11 05:58:46'),
('11111111', 'Rabu', NULL, '2024-11-11 05:58:46', '2024-11-11 05:58:46'),
('11111111', 'Kamis', NULL, '2024-11-11 05:58:46', '2024-11-11 05:58:46'),
('11111111', 'Jumat', NULL, '2024-11-11 05:58:46', '2024-11-11 05:58:46'),
('11111111', 'Sabtu', NULL, '2024-11-11 05:58:46', '2024-11-11 05:58:46'),
('11111111', 'Minggu', NULL, '2024-11-11 05:58:46', '2024-11-11 05:58:46');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_lokasi_penugasan`
--

CREATE TABLE `jam_kerja_lokasi_penugasan` (
  `kode_jk_lp_c` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_lokasi_penugasan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_cabang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_jam_kerja` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kantor_cabang`
--

CREATE TABLE `kantor_cabang` (
  `kode_cabang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_cabang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_kantor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `radius` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kantor_cabang`
--

INSERT INTO `kantor_cabang` (`kode_cabang`, `nama_cabang`, `lokasi_kantor`, `radius`, `created_at`, `updated_at`) VALUES
('BKS', 'Kota Bekasi', '-6.239541455611084, 107.00999661637697', 50000, '2024-07-10 08:04:31', '2024-07-19 07:27:48'),
('JKTP', 'Jakarta Pusat', '-6.201860426667731, 106.84214013495168', 60000, '2024-07-10 08:32:23', '2024-07-19 07:27:52'),
('JKTT', 'Jakarta Timur', '-6.2667231599717725, 106.89117553131064', 50000, '2024-07-10 08:15:07', '2024-07-19 07:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_departemen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_lokasi_penugasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_wa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama_lengkap`, `foto`, `kode_jabatan`, `kode_departemen`, `kode_cabang`, `kode_lokasi_penugasan`, `no_wa`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
('123123123', 'Salim Purnama Ramadhan', '123123123_1729498839.jpg', 'KEPKAM', 'SAG', 'JKTP', 'MN', '08123456789', '$2y$10$NBvJhmZ0TWDts4FcZ4oBduCgFafZex/8WfCakwD4KjTqafnemB7fa', NULL, '2024-06-21 01:22:30', '2024-10-21 08:37:01'),
('123456789', 'Mei Ling', '123456789_1719807160.png', 'WAKAM', 'SGP', 'JKTP', 'MN', '08147258369', '$2y$10$NiSacYyb3uDeR2eWQ/j.MeFEE/g8Eh5SAhp.ErErDgsg46ST8Q1Lq', NULL, '2024-06-28 07:19:07', '2024-10-21 08:36:30'),
('213213213', 'Ramadhan S Purnama', '213213213_1719807132.png', 'SUPKAM', 'SM', 'JKTT', 'GR', '08123456789', '$2y$10$iyjXNfqYeuZTj3iE/U9WKeWHlTZ7DoiWQIgaaDp53GxZ4B2DDvE1a', NULL, '2024-06-21 01:22:30', '2024-10-21 08:36:43'),
('222222222', 'Ujang', '222-222-222_1721201871.png', 'SO', 'SAP', 'JKTP', 'MN', '0822222222222', '$2y$10$ZVRKYmoOqeB3zY3iRZ7Fe.UQIOXku0slcNTCuXxZcAflWmYiqt8y.', NULL, '2024-07-17 07:37:52', '2024-10-21 08:39:18'),
('321321321', 'Purnama R Salim', '321321321_1719807141.png', 'PCCTV', 'TRC', 'JKTP', 'MN', '08123456789', '$2y$10$iyjXNfqYeuZTj3iE/U9WKeWHlTZ7DoiWQIgaaDp53GxZ4B2DDvE1a', NULL, '2024-06-21 01:22:30', '2024-10-21 08:36:35'),
('333333333', 'Ridho', '333333333_1721293448.png', 'PPM', 'SPMU', 'BKS', 'SMB', '0833333333333', '$2y$10$n2wY2wxFJyHwufrCqBJyFeT0yfxsRcsAlyRneOOyuwx0bYqMnGZ/y', NULL, '2024-07-17 07:43:12', '2024-10-21 08:36:52'),
('369258147', 'Ling Mei', '369258147_1719807170.png', 'PENMAL', 'TKM', 'JKTP', 'MN', '123456789', '$2y$10$IXxUEvNVZY1S/QuQ2qQBie.m0LgTz4Pp8qy7LEC5NYuWR1GLP7V22', NULL, '2024-06-28 07:26:47', '2024-10-21 08:36:22'),
('444444444', 'Abrar', '444444444_1721372945.jpg', 'PKG', 'TKM', 'BKS', 'SMB', '0834444444444', '$2y$10$za9w9xJv98V9YPB3f8U84.OPRJb5DbOY8T1z65qIwdylGXoA6acqy', NULL, '2024-07-19 07:09:05', '2024-10-21 08:36:09'),
('555555555', 'Sidqy Anwar', '555555555_1722323769.jpg', 'SO', 'TRC', 'BKS', 'SMB', '0855555555555', '$2y$10$z2BPEScdYN8KSWsB0nOcau2mCM5Cvk6zenK.mPz6PHNck/lJqEV6W', NULL, '2024-07-30 07:16:09', '2024-10-21 08:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_gaji`
--

CREATE TABLE `konfigurasi_gaji` (
  `kode_jenis_gaji` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_gaji` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konfigurasi_gaji`
--

INSERT INTO `konfigurasi_gaji` (`kode_jenis_gaji`, `jenis_gaji`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
('GT', 'Gaji Tetap', 'Gaji Tetap', 1, '2024-10-16 07:16:12', '2024-10-16 07:16:12'),
('L', 'Lembur', 'Lembur', 1, '2024-10-22 06:05:31', '2024-10-22 06:05:31'),
('TJ', 'Tunjangan Jabatan', 'Tunjangan Jabatan', 1, '2024-10-16 07:16:27', '2024-10-16 07:16:27');

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_potongan`
--

CREATE TABLE `konfigurasi_potongan` (
  `kode_jenis_potongan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_potongan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konfigurasi_potongan`
--

INSERT INTO `konfigurasi_potongan` (`kode_jenis_potongan`, `jenis_potongan`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
('BPJSK', 'BPJS Kesehatan', 'BPJS Kesehatan', 1, '2024-10-27 08:44:41', '2024-10-27 08:44:41'),
('BPJSTK', 'BPJS Tenaga Kerja', 'Potongan BPJS Tenaga Kerja', 1, '2024-10-27 08:44:11', '2024-10-27 08:44:11'),
('DD', 'Dana Darurat', 'Potongan Dana Darurat', 1, '2024-10-27 08:45:50', '2024-10-27 08:45:50'),
('JHT', 'Jaminan Hari Tua', 'Potongan Jaminan Hari Tua', 1, '2024-10-27 08:43:21', '2024-10-27 08:43:21'),
('PS', 'Potongan Seragam', 'Potongan Seragam', 1, '2024-10-27 08:45:25', '2024-10-27 08:45:25'),
('THR', 'Tunjangan Hari Raya', 'Potongan Tunjangan Hari Raya', 1, '2024-10-27 08:45:04', '2024-10-27 08:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `lembur`
--

CREATE TABLE `lembur` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_presensi` date DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `durasi_menit` int DEFAULT NULL,
  `lintas_hari` tinyint(1) DEFAULT NULL,
  `lembur_libur` tinyint(1) DEFAULT '0',
  `catatan_lembur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alasan_penolakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lembur`
--

INSERT INTO `lembur` (`id`, `nik`, `tanggal_presensi`, `waktu_mulai`, `waktu_selesai`, `durasi_menit`, `lintas_hari`, `lembur_libur`, `catatan_lembur`, `status`, `alasan_penolakan`, `created_at`, `updated_at`) VALUES
(2, '123123123', '2024-10-30', '17:00:00', '00:00:00', 420, 1, 1, 'Test Catatan Lembur', 'disetujui', NULL, '2024-10-30 08:07:28', '2024-10-31 10:14:15'),
(5, '444444444', '2024-10-31', '14:44:00', '23:44:00', 540, 0, 1, 'Test Catatan Lembur', 'pending', NULL, '2024-10-31 06:44:53', '2024-10-31 07:09:58'),
(6, '123456789', '2024-11-01', '00:00:00', '14:00:00', 840, 0, 1, 'Test Catatan Lembur', 'pending', NULL, '2024-10-31 07:07:24', '2024-10-31 07:07:24'),
(7, '123123123', '2024-11-02', '16:00:00', '16:30:00', 120, 0, 1, 'asdwqeasff', 'disetujui', NULL, '2024-10-31 09:20:27', '2024-11-02 06:53:02'),
(8, '123123123', '2024-11-01', '16:10:00', '16:18:00', 60, 0, 1, 'Test Lemburan', 'disetujui', NULL, '2024-11-01 07:47:12', '2024-11-01 08:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_kantor`
--

CREATE TABLE `lokasi_kantor` (
  `id` bigint UNSIGNED NOT NULL,
  `lokasi_kantor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `radius` smallint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_kantor`
--

INSERT INTO `lokasi_kantor` (`id`, `lokasi_kantor`, `radius`, `created_at`, `updated_at`) VALUES
(1, '-6.201837348522722, 106.84220394173208', 60, '2024-07-04 06:15:57', '2024-07-04 06:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_penugasan`
--

CREATE TABLE `lokasi_penugasan` (
  `kode_lokasi_penugasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lokasi_penugasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_penugasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `radius` double(8,2) DEFAULT NULL,
  `jumlah_jam_kerja` int DEFAULT NULL,
  `jumlah_hari_kerja` int DEFAULT NULL,
  `kode_cabang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_penugasan`
--

INSERT INTO `lokasi_penugasan` (`kode_lokasi_penugasan`, `nama_lokasi_penugasan`, `lokasi_penugasan`, `radius`, `jumlah_jam_kerja`, `jumlah_hari_kerja`, `kode_cabang`, `created_at`, `updated_at`) VALUES
('GR', 'GOR Rawamangun', '-6.190608764410666, 106.88994525893163', 5000.00, 8, 26, 'JKTT', '2024-10-21 08:35:59', '2024-11-05 14:37:15'),
('HI', 'Harapan Indah', '-6.180941252660481, 106.97394111219177', 5000.00, 12, 26, 'BKS', '2024-11-05 13:52:35', '2024-11-05 14:37:22'),
('MN', 'Monumen Nasional', '-6.174314150988573, 106.82691709944937', 5000.00, 8, 26, 'JKTP', '2024-10-21 08:34:59', '2024-11-05 14:37:29'),
('PIK', 'Pantai Indah Kapuk', '-6.101752434927198, 106.74070898023851', 5000.00, 8, 26, 'JKTP', '2024-11-05 14:36:15', '2024-11-05 14:36:15'),
('SMB', 'Summarecon Mall Bekasi', '-6.225244050554438, 107.00119587589245', 5000.00, 8, 26, 'BKS', '2024-10-21 08:34:28', '2024-11-05 14:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `master_cuti`
--

CREATE TABLE `master_cuti` (
  `kode_cuti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_cuti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_hari` smallint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_cuti`
--

INSERT INTO `master_cuti` (`kode_cuti`, `nama_cuti`, `jumlah_hari`, `created_at`, `updated_at`) VALUES
('C001', 'Cuti Tahunan', 12, '2024-07-23 07:20:41', NULL),
('C002', 'Cuti Sakit', 30, '2024-07-23 07:20:41', '2024-07-23 08:45:54'),
('C003', 'Cuti Melahirkan', 90, '2024-07-23 07:20:41', NULL),
('C004', 'Cuti Pendidikan', 300, '2024-07-23 08:06:06', '2024-07-23 08:39:54'),
('C005', 'Cuti Menikah', 7, '2024-07-23 08:46:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(7, '2014_10_12_000000_create_users_table', 1),
(8, '2014_10_12_100000_create_password_resets_table', 1),
(9, '2019_08_19_000000_create_failed_jobs_table', 1),
(10, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(11, '2024_06_20_045454_create_karyawans_table', 1),
(12, '2024_06_20_050903_create_presensis_table', 1),
(13, '2024_06_24_110056_remove_unique_from_presensi_nik', 2),
(14, '2024_06_25_144752_create_izin_table', 3),
(15, '2024_06_25_145123_rename_izin_to_pengajuan_sakit_izin', 4),
(16, '2024_06_27_142520_add_kode_departemen_to_karyawan_table', 5),
(18, '2024_06_27_142747_create_departemen_table', 6),
(19, '2024_07_04_131023_create_lokasi_kantors_table', 7),
(20, '2024_07_08_110709_create_gajis_table', 8),
(21, '2024_07_10_142545_create_kantor_cabang_table', 9),
(22, '2024_07_10_154246_add_kode_cabang_to_karyawan_table', 10),
(24, '2024_07_11_121845_create_jam_kerja_table', 11),
(25, '2024_07_12_144029_create_jam_kerja_karyawan_table', 12),
(26, '2024_07_16_120014_add_kode_jam_kerja_to_presensi_table', 13),
(27, '2024_07_18_113217_create_jam_kerja_dept_table', 14),
(28, '2024_07_18_113515_create_jam_kerja_dept_detail_table', 15),
(29, '2024_07_18_124201_modify_jam_kerja_dept_and_detail_tables', 16),
(33, '2024_07_22_151928_update_persetujuan_sakit_izin_table', 17),
(34, '2024_07_23_113243_add_surat_sakit_to_pengajuan_sakit_izin_table', 18),
(35, '2024_07_23_132656_update_id_to_kode_izin_in_pengajuan_izin_table', 19),
(36, '2024_07_23_133500_update_kode_izin_in_pengajuan_izin_table', 20),
(38, '2024_07_23_141508_create_master_cuti_table', 21),
(42, '2024_07_23_161528_add_kode_cuti_to_pengajuan_izin_table', 22),
(43, '2024_07_28_104311_add_status_to_presensi_table', 22),
(44, '2024_07_28_113700_add_kode_izin_to_presensi_table', 23),
(45, '2024_08_06_132618_add_lintas_hari_to_jam_kerja_table', 24),
(46, '2024_08_08_134748_create_permission_tables', 25),
(47, '2024_08_08_152031_add_kode_departemen_to_users_table', 26),
(48, '2024_08_08_203420_create_jabatans_table', 27),
(52, '2024_08_08_203612_create_gajis_table', 28),
(54, '2024_08_08_204401_update_karyawan_table', 28),
(55, '2024_08_09_142314_add_username_to_users_table', 28),
(56, '2024_08_13_131219_add_group_name_to_permissions_table', 29),
(57, '2024_10_15_131400_add_jenis_gaji_to_gaji_table', 30),
(60, '2024_10_16_124430_create_konfigurasi_gaji_table', 32),
(61, '2024_10_16_145441_add_kode_jenis_gaji_to_gaji_table', 33),
(67, '2024_10_18_124338_add_change_notes_and_changed_by_to_penggajian_table', 35),
(68, '2024_10_21_134512_create_lokasi_penugasan_table', 36),
(69, '2024_10_21_150922_update_karyawan_table_remove_jabatan_add_lokasi_penugasan', 37),
(70, '2024_10_22_121028_add_kode_lokasi_and_cabang_to_gaji_table', 38),
(71, '2024_10_23_152443_create_cashbon_table', 39),
(72, '2024_10_23_160445_add_keterangan_to_cashbon_table', 40),
(74, '2024_10_25_152721_create_cashbon_limit_table', 41),
(75, '2024_10_25_152826_create_cashbon_karyawan_limit_table', 41),
(76, '2024_10_27_145336_create_konfigurasi_potongan', 42),
(77, '2024_10_27_145533_create_potongan', 43),
(79, '2024_10_28_141613_create_thr_table', 44),
(80, '2024_10_29_142048_add_catatan_perubahan_dan_diubah_oleh_pada_table_thr', 45),
(81, '2024_10_30_130909_create_lembur_table', 46),
(82, '2024_10_30_131532_add_lembur_to_presensi_table', 47),
(83, '2024_10_30_150231_add_coloumn_durasi_lembur_to_lembur_table', 48),
(84, '2024_10_30_160245_add_coloumn_alasan_penolakan_to_lembur_table', 49),
(85, '2024_10_31_135924_add_catatan_lembur_to_lembur_table', 50),
(86, '2024_11_03_143741_create_jam_kerja_lokasi_penugasan_cabang_table', 51),
(87, '2024_11_03_150450_add_awal_akhir_nama_to_jam_kerja_lokasi_penugasan_cabang_table', 52),
(88, '2024_11_03_152039_create_jam_kerja_lokasi_penugasan', 53),
(89, '2024_11_04_134832_add_lokasi_penugasan_and_cabang_to_jam_kerja_table', 54),
(90, '2024_11_05_204604_add_jumlah_jam_kerja_to_lokasi_penugasan_table', 55),
(91, '2024_11_05_213211_add_jumlah_hari_kerja_to_lokasi_penugasan_table', 56),
(92, '2024_11_06_152034_create_penggajian_table', 57),
(93, '2024_11_07_150924_add_komponen_gaji_kotor_to_penggajian_table', 58);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(7, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 2),
(6, 'App\\Models\\User', 17),
(12, 'App\\Models\\User', 18),
(9, 'App\\Models\\User', 19),
(11, 'App\\Models\\User', 21);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `kode_izin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_izin_dari` date DEFAULT NULL,
  `tanggal_izin_sampai` date DEFAULT NULL,
  `jumlah_hari` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cuti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('sakit','izin','cuti') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_approved` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `surat_sakit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`kode_izin`, `nik`, `tanggal_izin_dari`, `tanggal_izin_sampai`, `jumlah_hari`, `kode_cuti`, `status`, `status_approved`, `keterangan`, `surat_sakit`, `created_at`, `updated_at`) VALUES
('IA0724001', '123123123', '2024-07-25', '2024-07-27', '3 Hari', NULL, 'izin', 2, 'Acara Healing', NULL, '2024-07-23 07:02:40', '2024-07-24 04:59:06'),
('IA0724005', '123123123', '2024-07-27', '2024-07-27', '1 Hari', NULL, 'izin', 1, 'Tugas Luar Kota', NULL, '2024-07-24 07:26:57', '2024-07-30 05:43:43'),
('IA0724007', '555555555', '2024-07-30', '2024-07-30', '1 Hari', NULL, 'izin', 1, 'libur bentar', NULL, '2024-07-30 07:38:42', NULL),
('IA0824002', '123123123', '2024-08-30', '2024-08-31', '2 Hari', NULL, 'izin', 0, 'Libur Bentar', NULL, '2024-08-02 08:54:43', NULL),
('IC0724004', '123123123', '2024-07-25', '2024-07-26', '2 Hari', 'C002', 'cuti', 1, 'Demam', NULL, '2024-07-23 09:21:34', '2024-07-30 05:44:30'),
('IC0724008', '333333333', '2024-07-30', '2024-07-30', '1 Hari', 'C001', 'cuti', 1, 'capek', NULL, '2024-07-30 07:39:58', NULL),
('IC0824001', '123123123', '2024-08-04', '2024-08-10', '7 Hari', 'C005', 'cuti', 1, 'Honeymoon di Bali', NULL, '2024-07-24 07:18:34', '2024-07-28 03:45:28'),
('IS0624001', '123123123', '2024-06-01', '2024-06-30', '30 Hari', NULL, 'sakit', 1, 'Sakit dikit', 'IS0624001.png', '2024-07-26 08:45:52', NULL),
('IS0724005', '123123123', '2024-07-25', '2024-07-27', '3 Hari', NULL, 'sakit', 2, 'Demam', 'IS0724005.png', '2024-07-24 07:55:10', '2024-07-24 07:57:49'),
('IS0724006', '123123123', '2024-07-29', '2024-07-29', '1 Hari', NULL, 'sakit', 1, 'sakit bentar', 'IS0724006.png', '2024-07-28 04:56:18', NULL),
('IS0724007', '444444444', '2024-07-30', '2024-07-30', '1 Hari', NULL, 'sakit', 2, 'pileg', NULL, '2024-07-30 07:39:16', NULL),
('IS1124001', '123123123', '2024-11-10', '2024-11-16', '7 Hari', NULL, 'sakit', 0, 'hahaha', 'IS1124001.png', '2024-11-09 12:02:54', '2024-11-09 13:27:19'),
('IS1124002', '123123123', '2024-11-24', '2024-11-30', '7 Hari', NULL, 'sakit', 0, 'sakit bentar', 'IS1124002.png', '2024-11-09 13:11:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penggajian`
--

CREATE TABLE `penggajian` (
  `kode_penggajian` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_lokasi_penugasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_gaji` date DEFAULT NULL,
  `bulan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_hari_kerja` int DEFAULT NULL,
  `jumlah_hari_masuk` int DEFAULT NULL,
  `jumlah_hari_tidak_masuk` int DEFAULT NULL,
  `total_jam_lembur` decimal(10,2) NOT NULL DEFAULT '0.00',
  `komponen_gaji_kotor` json DEFAULT NULL,
  `komponen_gaji` json DEFAULT NULL,
  `total_gaji_kotor` decimal(10,2) NOT NULL DEFAULT '0.00',
  `komponen_potongan` json DEFAULT NULL,
  `total_potongan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gaji_bersih` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','disetujui','ditolak','dibayar') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `diproses_oleh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alasan_perubahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `diubah_oleh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_perubahan` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penggajian`
--

INSERT INTO `penggajian` (`kode_penggajian`, `nik`, `kode_cabang`, `kode_lokasi_penugasan`, `tanggal_gaji`, `bulan`, `jumlah_hari_kerja`, `jumlah_hari_masuk`, `jumlah_hari_tidak_masuk`, `total_jam_lembur`, `komponen_gaji_kotor`, `komponen_gaji`, `total_gaji_kotor`, `komponen_potongan`, `total_potongan`, `gaji_bersih`, `status`, `catatan`, `diproses_oleh`, `alasan_perubahan`, `diubah_oleh`, `waktu_perubahan`, `created_at`, `updated_at`) VALUES
('PG2024110003', '123123123', 'JKTP', 'MN', '2024-11-30', '2024-11', 26, 26, 0, 30.00, '{\"L\": {\"jenis_gaji\": \"Lembur\", \"jumlah_gaji\": \"20000.00\"}, \"GT\": {\"jenis_gaji\": \"Gaji Tetap\", \"jumlah_gaji\": \"5000000.00\"}, \"TJ\": {\"jenis_gaji\": \"Tunjangan Jabatan\", \"jumlah_gaji\": \"2500000.00\"}}', '{\"L\": {\"jenis_gaji\": \"Lembur\", \"jumlah_gaji\": 600000}, \"GT\": {\"jenis_gaji\": \"Gaji Tetap\", \"jumlah_gaji\": \"5000000.00\"}, \"TJ\": {\"jenis_gaji\": \"Tunjangan Jabatan\", \"jumlah_gaji\": \"2500000.00\"}}', 7520000.00, '{\"Cashbon\": \"10000.00\", \"Dana Darurat\": \"50000.00\", \"BPJS Kesehatan\": \"50000.00\", \"Jaminan Hari Tua\": \"50000.00\", \"Potongan Seragam\": \"50000.00\", \"BPJS Tenaga Kerja\": \"50000.00\", \"Tunjangan Hari Raya\": \"416000.00\"}', 676000.00, 7424000.00, 'dibayar', 'Catatan', 'Super Admin Presensi', 'Catatan Perubahan', 'Super Admin Presensi', '2024-11-09 11:43:44', '2024-11-09 07:27:39', '2024-11-09 11:43:44'),
('PG2024110004', '123123123', 'JKTP', 'MN', '2024-09-30', '2024-09', 26, 0, 26, 0.00, '{\"L\": {\"jenis_gaji\": \"Lembur\", \"jumlah_gaji\": \"20000.00\"}, \"GT\": {\"jenis_gaji\": \"Gaji Tetap\", \"jumlah_gaji\": \"5000000.00\"}, \"TJ\": {\"jenis_gaji\": \"Tunjangan Jabatan\", \"jumlah_gaji\": \"2500000.00\"}}', '{\"L\": {\"jenis_gaji\": \"Lembur\", \"jumlah_gaji\": 0}, \"GT\": {\"jenis_gaji\": \"Gaji Tetap\", \"jumlah_gaji\": 0}, \"TJ\": {\"jenis_gaji\": \"Tunjangan Jabatan\", \"jumlah_gaji\": \"2500000.00\"}}', 7520000.00, '{\"Dana Darurat\": \"50000.00\", \"BPJS Kesehatan\": \"50000.00\", \"Jaminan Hari Tua\": \"50000.00\", \"Potongan Seragam\": \"50000.00\", \"BPJS Tenaga Kerja\": \"50000.00\", \"Tunjangan Hari Raya\": \"416000.00\", \"Potongan Ketidakhadiran\": 5000000}', 5666000.00, 1834000.00, 'dibayar', 'Catatan', 'Super Admin Presensi', NULL, NULL, NULL, '2024-11-09 07:36:44', '2024-11-09 07:36:44'),
('PG2024110005', '123123123', 'JKTP', 'MN', '2024-10-09', '2024-10', 26, 20, 6, 10.00, '{\"L\": {\"jenis_gaji\": \"Lembur\", \"jumlah_gaji\": \"20000.00\"}, \"GT\": {\"jenis_gaji\": \"Gaji Tetap\", \"jumlah_gaji\": \"5000000.00\"}, \"TJ\": {\"jenis_gaji\": \"Tunjangan Jabatan\", \"jumlah_gaji\": \"2500000.00\"}}', '{\"L\": {\"jenis_gaji\": \"Lembur\", \"jumlah_gaji\": 200000}, \"GT\": {\"jenis_gaji\": \"Gaji Tetap\", \"jumlah_gaji\": 3846153.846153846}, \"TJ\": {\"jenis_gaji\": \"Tunjangan Jabatan\", \"jumlah_gaji\": \"2500000.00\"}}', 7520000.00, '{\"Cashbon\": \"210000.00\", \"Dana Darurat\": \"50000.00\", \"BPJS Kesehatan\": \"50000.00\", \"Jaminan Hari Tua\": \"50000.00\", \"Potongan Seragam\": \"50000.00\", \"BPJS Tenaga Kerja\": \"50000.00\", \"Tunjangan Hari Raya\": \"416000.00\", \"Potongan Ketidakhadiran\": 1153846.153846154}', 2029846.15, 5670153.85, 'draft', 'Catatan', 'Super Admin Presensi', 'Catatan Perubahan', 'Super Admin Presensi', '2024-11-09 10:58:11', '2024-11-09 10:50:48', '2024-11-09 10:58:11');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(3, 'dashboard.dashboard', 'user', 'Dashboard', '2024-08-13 07:01:39', '2024-08-13 07:01:39'),
(4, 'dashboard.monitoring-presensi', 'user', 'Dashboard', '2024-08-13 07:02:12', '2024-08-13 07:02:12'),
(5, 'dashboard.persetujuan-sakit-izin', 'user', 'Dashboard', '2024-08-13 07:02:34', '2024-08-13 07:02:34'),
(6, 'laporan.laporan-presensi', 'user', 'Laporan', '2024-08-13 07:02:51', '2024-08-13 07:02:51'),
(7, 'laporan.rekap-presensi', 'user', 'Laporan', '2024-08-13 07:03:09', '2024-08-13 07:03:09'),
(8, 'master.karyawan', 'user', 'Master', '2024-08-13 07:03:38', '2024-08-13 07:03:38'),
(9, 'master.departemen', 'user', 'Master', '2024-08-13 07:03:55', '2024-08-13 07:03:55'),
(10, 'master.kantor-cabang', 'user', 'Master', '2024-08-13 07:04:10', '2024-08-13 07:04:10'),
(11, 'master.cuti', 'user', 'Master', '2024-08-13 07:04:27', '2024-08-13 07:04:27'),
(12, 'konfigurasi.jam-kerja', 'user', 'Konfigurasi', '2024-08-13 07:04:43', '2024-08-13 07:04:43'),
(13, 'konfigurasi.jam-kerja-departemen', 'user', 'Konfigurasi', '2024-08-13 07:04:54', '2024-08-13 07:04:54'),
(14, 'konfigurasi.user', 'user', 'Konfigurasi', '2024-08-13 07:05:08', '2024-08-13 07:05:08'),
(15, 'konfigurasi.role', 'user', 'Konfigurasi', '2024-08-13 07:05:16', '2024-08-13 07:05:16'),
(16, 'konfigurasi.permission', 'user', 'Konfigurasi', '2024-08-13 07:05:29', '2024-08-13 07:05:29'),
(18, 'dashboard.test', 'user', 'Dashboard', '2024-08-13 08:59:31', '2024-08-13 08:59:31'),
(19, 'laporan.test', 'user', 'Laporan', '2024-08-13 08:59:31', '2024-08-13 08:59:31'),
(20, 'master.test', 'user', 'Master', '2024-08-13 08:59:31', '2024-08-13 08:59:31'),
(21, 'konfigurasi.test', 'user', 'Konfigurasi', '2024-08-13 08:59:31', '2024-08-13 08:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `potongan`
--

CREATE TABLE `potongan` (
  `kode_potongan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_lokasi_penugasan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jenis_potongan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_potongan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_potongan` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `potongan`
--

INSERT INTO `potongan` (`kode_potongan`, `kode_jabatan`, `kode_lokasi_penugasan`, `kode_cabang`, `kode_jenis_potongan`, `nama_potongan`, `jumlah_potongan`, `created_at`, `updated_at`) VALUES
('BPJSKKK', 'KEPKAM', 'MN', 'JKTP', 'BPJSK', 'BPJS Kesehatan Kepala Keamanan', 50000.00, '2024-10-30 04:18:46', '2024-10-30 04:18:46'),
('BPJSTKKK', 'KEPKAM', 'MN', 'JKTP', 'BPJSTK', 'BPJS Tenaga Kerja Kepala Keamanan', 50000.00, '2024-10-30 04:20:18', '2024-10-30 04:20:18'),
('DDKK', 'KEPKAM', 'MN', 'JKTP', 'DD', 'Dana Darurat Kepala Keamanan', 50000.00, '2024-10-30 04:20:48', '2024-10-30 04:20:48'),
('JHTKK', 'KEPKAM', 'MN', 'JKTP', 'JHT', 'Jaminan Hari Tua Kepala Keamanan', 50000.00, '2024-10-30 04:18:14', '2024-10-30 04:18:14'),
('PSKK', 'KEPKAM', 'MN', 'JKTP', 'PS', 'Potongan Seragam Kepala Keamanan', 50000.00, '2024-10-30 04:21:29', '2024-10-30 04:21:29'),
('THRKK', 'KEPKAM', 'MN', 'JKTP', 'THR', 'Tunjangan Hari Raya Kepala Keamanan', 416000.00, '2024-10-30 04:22:43', '2024-10-30 04:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_presensi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `foto_masuk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_keluar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_masuk` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lokasi_keluar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kode_jam_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_izin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lembur` tinyint(1) DEFAULT NULL,
  `mulai_lembur` time DEFAULT NULL,
  `selesai_lembur` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tanggal_presensi`, `jam_masuk`, `jam_keluar`, `foto_masuk`, `foto_keluar`, `lokasi_masuk`, `lokasi_keluar`, `kode_jam_kerja`, `status`, `kode_izin`, `lembur`, `mulai_lembur`, `selesai_lembur`, `created_at`, `updated_at`) VALUES
(107, '123123123', '2024-07-28', '10:53:37', '15:00:53', 'public/uploads/absensi/123123123-2024-07-28-105337-masuk.png', 'public/uploads/absensi/123123123-2024-07-28-150053-keluar.png', '-6.22592,106.8302336', '-6.22592,106.8302336', 'JK02', 'hadir', NULL, NULL, NULL, NULL, '2024-07-28 03:53:37', '2024-07-28 08:00:53'),
(240, '123123123', '2024-07-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0724006', NULL, NULL, NULL, '2024-07-29 09:10:51', NULL),
(247, '123123123', '2024-06-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(248, '123123123', '2024-06-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(249, '123123123', '2024-06-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(250, '123123123', '2024-06-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(251, '123123123', '2024-06-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(252, '123123123', '2024-06-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(253, '123123123', '2024-06-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(254, '123123123', '2024-06-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(255, '123123123', '2024-06-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(256, '123123123', '2024-06-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(257, '123123123', '2024-06-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(258, '123123123', '2024-06-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(259, '123123123', '2024-06-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(260, '123123123', '2024-06-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(261, '123123123', '2024-06-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(262, '123123123', '2024-06-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(263, '123123123', '2024-06-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(264, '123123123', '2024-06-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(265, '123123123', '2024-06-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(266, '123123123', '2024-06-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(267, '123123123', '2024-06-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(268, '123123123', '2024-06-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(269, '123123123', '2024-06-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(270, '123123123', '2024-06-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(271, '123123123', '2024-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(272, '123123123', '2024-06-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(273, '123123123', '2024-06-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(274, '123123123', '2024-06-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(275, '123123123', '2024-06-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(276, '123123123', '2024-06-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', NULL, NULL, NULL, '2024-07-29 09:11:15', NULL),
(277, '123123123', '2024-07-30', '11:13:14', '15:13:14', 'public/uploads/absensi/123123123-2024-07-30-111314-masuk.png', 'public/uploads/absensi/123123123-2024-07-30-111314-masuk.png', '-6.201695,106.8421801', '-6.201695,106.8421801', 'JK02', 'hadir', NULL, NULL, NULL, NULL, '2024-07-30 04:13:14', NULL),
(278, '123123123', '2024-07-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'izin', 'IA0724005', NULL, NULL, NULL, '2024-07-30 05:44:59', NULL),
(279, '123123123', '2024-07-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0724004', NULL, NULL, NULL, '2024-07-30 05:45:06', NULL),
(280, '123123123', '2024-07-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0724004', NULL, NULL, NULL, '2024-07-30 05:45:06', NULL),
(281, '555555555', '2024-07-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'izin', 'IA0724007', NULL, NULL, NULL, '2024-07-30 07:40:09', NULL),
(282, '333333333', '2024-07-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0724008', NULL, NULL, NULL, '2024-07-30 07:40:11', NULL),
(284, '123123123', '2024-08-01', '14:19:54', '16:20:56', 'public/uploads/absensi/123123123-2024-08-01-141954-masuk.png', 'public/uploads/absensi/123123123-2024-08-01-162056-keluar.png', '-6.2017052,106.8421785', '-6.2017082,106.8421777', 'JK03', 'hadir', NULL, NULL, NULL, NULL, '2024-08-01 07:19:54', '2024-08-01 09:20:56'),
(285, '123123123', '2024-08-02', '11:03:15', '16:14:17', 'public/uploads/absensi/123123123-2024-08-02-110315-masuk.png', 'public/uploads/absensi/123123123-2024-08-02-161417-keluar.png', '-6.1964288,106.8433408', '-6.2017027,106.842168', 'JK02', 'hadir', NULL, NULL, NULL, NULL, '2024-08-02 04:03:15', '2024-08-02 09:14:17'),
(286, '444444444', '2024-08-05', '15:58:13', '16:00:43', 'public/uploads/absensi/444444444-2024-08-05-155813-masuk.png', 'public/uploads/absensi/444444444-2024-08-05-160043-keluar.png', '-6.2016715,106.8421765', '-6.2016715,106.8421765', 'JK03', 'hadir', NULL, NULL, NULL, NULL, '2024-08-05 08:58:13', '2024-08-05 09:00:43'),
(290, '555555555', '2024-08-06', '15:01:01', '13:31:25', 'public/uploads/absensi/555555555-2024-08-06-150101-masuk.png', 'public/uploads/absensi/555555555-2024-08-06-133125-keluar.png', '-6.2062592,106.8302336', '-6.2016831,106.8421874', 'JKT', 'hadir', NULL, NULL, NULL, NULL, '2024-08-06 08:01:01', '2024-08-07 06:31:25'),
(291, '222222222', '2024-08-06', '15:01:43', '14:57:36', 'public/uploads/absensi/222222222-2024-08-06-150143-masuk.png', 'public/uploads/absensi/222222222-2024-08-06-145736-keluar.png', '-6.2062592,106.8302336', '-6.1341696,106.82368', 'JKT', 'hadir', NULL, NULL, NULL, NULL, '2024-08-06 08:01:43', '2024-08-07 07:57:36'),
(292, '333333333', '2024-08-06', '15:02:38', '15:04:32', 'public/uploads/absensi/333333333-2024-08-06-150238-masuk.png', 'public/uploads/absensi/333333333-2024-08-06-150432-keluar.png', '-6.2062592,106.8302336', '-6.2062592,106.8302336', 'JKT01', 'hadir', NULL, NULL, NULL, NULL, '2024-08-06 08:02:38', '2024-08-06 08:04:32'),
(293, '222222222', '2024-08-07', '15:03:09', '16:14:55', 'public/uploads/absensi/222222222-2024-08-07-150309-masuk.png', 'public/uploads/absensi/222222222-2024-08-07-161455-keluar.png', '-6.1341696,106.82368', '-6.2016825,106.8421861', 'JKT01', 'hadir', NULL, NULL, NULL, NULL, '2024-08-07 08:03:09', '2024-08-07 09:14:55'),
(294, '555555555', '2024-08-07', '15:03:56', '16:14:31', 'public/uploads/absensi/555555555-2024-08-07-150356-masuk.png', 'public/uploads/absensi/555555555-2024-08-07-161431-keluar.png', '-6.1341696,106.82368', '-6.2016842,106.842187', 'JKT01', 'hadir', NULL, NULL, NULL, NULL, '2024-08-07 08:03:56', '2024-08-07 09:14:31'),
(295, '333333333', '2024-08-07', '15:05:19', '16:14:05', 'public/uploads/absensi/333333333-2024-08-07-150519-masuk.png', 'public/uploads/absensi/333333333-2024-08-07-161405-keluar.png', '-6.1341696,106.82368', '-6.2016842,106.842187', 'JKT01', 'hadir', NULL, NULL, NULL, NULL, '2024-08-07 08:05:19', '2024-08-07 09:14:05'),
(297, '123123123', '2024-10-18', '13:09:24', '13:54:44', 'public/uploads/absensi/123123123-2024-10-18-130924-masuk.png', 'public/uploads/absensi/123123123-2024-10-18-135444-keluar.png', '-6.2017428,106.8420686', NULL, 'JK02', 'hadir', NULL, NULL, NULL, NULL, '2024-10-18 06:09:24', '2024-10-18 06:54:44'),
(299, '123123123', '2024-11-01', '16:16:31', '16:19:18', 'public/uploads/absensi/123123123-2024-11-01-161631-masuk.png', 'public/uploads/absensi/123123123-2024-11-01-161918-keluar.png', '-6.2017276,106.8420937', '-6.2062592,106.856448', 'JK02', 'hadir', NULL, NULL, NULL, NULL, '2024-11-01 09:16:31', '2024-11-01 09:19:18'),
(304, '123123123', '2024-11-02', '16:07:13', '16:11:37', 'public/uploads/absensi/123123123-2024-11-02-160713-masuk.png', 'public/uploads/absensi/123123123-2024-11-02-161137-keluar.png', '-6.2357504,106.790912', '-6.2291968,106.807296', 'LEMBUR', 'hadir', NULL, 1, '16:00:00', '16:30:00', '2024-11-02 09:07:13', '2024-11-02 09:11:37'),
(305, '123123123', '2024-11-05', '21:11:28', '21:30:09', 'public/uploads/absensi/123123123-2024-11-05-211128-masuk.png', 'public/uploads/absensi/123123123-2024-11-05-213009-keluar.png', '-6.2029824,106.8040192', '-6.2029824,106.8040192', 'JKMNJKTPSM', 'hadir', NULL, 0, NULL, NULL, '2024-11-05 14:11:28', '2024-11-05 14:30:09'),
(306, '123123123', '2024-08-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', NULL, NULL, NULL, '2024-11-09 13:37:32', NULL),
(307, '123123123', '2024-08-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', NULL, NULL, NULL, '2024-11-09 13:37:32', NULL),
(308, '123123123', '2024-08-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', NULL, NULL, NULL, '2024-11-09 13:37:32', NULL),
(309, '123123123', '2024-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', NULL, NULL, NULL, '2024-11-09 13:37:32', NULL),
(310, '123123123', '2024-08-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', NULL, NULL, NULL, '2024-11-09 13:37:32', NULL),
(311, '123123123', '2024-08-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', NULL, NULL, NULL, '2024-11-09 13:37:32', NULL),
(312, '123123123', '2024-08-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', NULL, NULL, NULL, '2024-11-09 13:37:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(6, 'admin', 'user', '2024-08-14 06:43:55', '2024-08-14 06:46:24'),
(7, 'super-admin', 'user', '2024-08-14 06:44:04', '2024-08-14 06:46:30'),
(8, 'development', 'user', '2024-08-14 06:44:12', '2024-08-14 06:46:36'),
(9, 'admin-presensi', 'user', '2024-08-14 06:44:23', '2024-08-14 06:46:42'),
(11, 'test-role', 'user', '2024-08-15 07:26:37', '2024-08-15 07:26:37'),
(12, 'admin-gaji', 'user', '2024-08-15 07:29:16', '2024-08-15 07:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(7, 6),
(8, 6),
(9, 6),
(10, 6),
(11, 6),
(12, 6),
(13, 6),
(3, 7),
(4, 7),
(5, 7),
(6, 7),
(7, 7),
(8, 7),
(9, 7),
(10, 7),
(11, 7),
(12, 7),
(13, 7),
(14, 7),
(15, 7),
(16, 7),
(3, 8),
(4, 8),
(5, 8),
(6, 8),
(7, 8),
(8, 8),
(9, 8),
(10, 8),
(11, 8),
(12, 8),
(13, 8),
(14, 8),
(15, 8),
(16, 8),
(3, 9),
(4, 9),
(5, 9),
(6, 9),
(7, 9),
(11, 9),
(12, 9),
(13, 9),
(3, 12),
(4, 12),
(5, 12),
(6, 12),
(7, 12),
(8, 12),
(9, 12),
(10, 12),
(11, 12);

-- --------------------------------------------------------

--
-- Table structure for table `thr`
--

CREATE TABLE `thr` (
  `kode_thr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_lokasi_penugasan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_thr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` int NOT NULL,
  `jumlah_thr` decimal(65,2) DEFAULT NULL,
  `tanggal_penyerahan` date NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `catatan_perubahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `diubah_oleh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thr`
--

INSERT INTO `thr` (`kode_thr`, `nik`, `kode_jabatan`, `kode_lokasi_penugasan`, `kode_cabang`, `nama_thr`, `tahun`, `jumlah_thr`, `tanggal_penyerahan`, `status`, `notes`, `catatan_perubahan`, `diubah_oleh`, `created_at`, `updated_at`) VALUES
('THR4GQ438', '123123123', 'KEPKAM', 'MN', 'JKTP', 'Test Edit THR', 2024, 5000000.00, '2024-10-31', 'Disetujui', 'Test Edit THR', 'Ubah nominal', 'Super Admin Presensi', '2024-10-29 06:41:31', '2024-11-09 11:05:21'),
('THRBT4832', '123456789', 'WAKAM', 'MN', 'JKTP', 'Nama THR', 2024, 4500000.00, '2024-11-21', 'Pending', 'Catatan', NULL, NULL, '2024-11-10 11:49:38', '2024-11-10 11:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_departemen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `email_verified_at`, `password`, `foto`, `role`, `kode_departemen`, `kode_cabang`, `no_hp`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Super Admin Presensi', 'super_admin@presensi.com', NULL, '$2y$10$xfLKKBPmVlQ2mhUcrnb0VuTW4CZIsl0tH85ACvMLKYCKq4lMBhICm', 'superadmin_1729495847.jpg', 'super-admin', 'SKP', 'JKTP', '0811223344', NULL, '2024-06-27 03:51:34', '2024-10-21 07:30:47'),
(2, 'admindev', 'Admin Dev', 'admin_dev@presensi.com', NULL, '$2y$10$e6mAMjamNr7Oy/mYkdJK0OHL7l55BEubGkJYgTvcsrIXzjzT2nayK', 'admindev_1723709310.jpeg', 'development', 'SKP', 'JKTP', '0897654321', NULL, '2024-06-27 03:51:34', '2024-08-15 09:03:51'),
(17, 'admin', 'Admin', 'admin@presensi.com', NULL, '$2y$10$LlpTAdvrMV7/4/WFXGPjp.TrUU5cPfHJgP/DFgwnNxwyZ7cy/qHo2', 'admin_1723709176.jpeg', 'admin', 'SKP', 'JKTT', '08654321987', NULL, '2024-08-15 08:06:16', '2024-08-15 09:03:49'),
(18, 'admingaji', 'Admin Gaji', 'admin_gaji@presensi.com', NULL, '$2y$10$2is9hbaVsOj.fnuZt/kIJus5TKrlM9iOJpEvW3RrGMq5jXWzYS2AK', 'admingaji_1723710387.jpeg', 'admin-gaji', 'SKP', 'BKS', '0808080808', NULL, '2024-08-15 08:23:26', '2024-08-15 09:03:52'),
(19, 'adminpresensi', 'Admin Presensi', 'admin_presensi@presensi.com', NULL, '$2y$10$G2YDsXfkbG6Oq6Yvxj54QOrlssaX33Zoupp2eN/eewAGIqhdF8Mmu', 'adminpresensi_1723710353.png', 'admin-presensi', 'SKP', 'JKTT', '090909090909', NULL, '2024-08-15 08:25:53', '2024-08-15 09:03:53'),
(21, 'testadmin', 'Test Admin', 'test_admin@presensi.com', NULL, '$2y$10$5fy5DRZg8241lEKejcpKnuHfqFCZb6tCp0ebWExA3FXduYd395KQy', 'testadmin_1723711979.webp', 'test-role', 'SKP', 'BKS', '081122334455', NULL, '2024-08-15 08:52:59', '2024-08-15 09:03:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashbon`
--
ALTER TABLE `cashbon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cashbon_nik_index` (`nik`);

--
-- Indexes for table `cashbon_karyawan_limit`
--
ALTER TABLE `cashbon_karyawan_limit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cashbon_karyawan_limit_nik_foreign` (`nik`);

--
-- Indexes for table `cashbon_limit`
--
ALTER TABLE `cashbon_limit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`kode_departemen`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`kode_gaji`),
  ADD KEY `gaji_kode_jenis_gaji_foreign` (`kode_jenis_gaji`),
  ADD KEY `gaji_kode_lokasi_penugasan_foreign` (`kode_lokasi_penugasan`),
  ADD KEY `gaji_kode_cabang_foreign` (`kode_cabang`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`kode_jabatan`);

--
-- Indexes for table `jam_kerja`
--
ALTER TABLE `jam_kerja`
  ADD PRIMARY KEY (`kode_jam_kerja`);

--
-- Indexes for table `jam_kerja_dept`
--
ALTER TABLE `jam_kerja_dept`
  ADD PRIMARY KEY (`kode_jk_dept`);

--
-- Indexes for table `jam_kerja_lokasi_penugasan`
--
ALTER TABLE `jam_kerja_lokasi_penugasan`
  ADD PRIMARY KEY (`kode_jk_lp_c`),
  ADD KEY `jam_kerja_lokasi_penugasan_kode_jam_kerja_foreign` (`kode_jam_kerja`),
  ADD KEY `jam_kerja_lokasi_penugasan_kode_lokasi_penugasan_foreign` (`kode_lokasi_penugasan`),
  ADD KEY `jam_kerja_lokasi_penugasan_kode_cabang_foreign` (`kode_cabang`);

--
-- Indexes for table `kantor_cabang`
--
ALTER TABLE `kantor_cabang`
  ADD PRIMARY KEY (`kode_cabang`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik`),
  ADD KEY `karyawan_kode_lokasi_penugasan_foreign` (`kode_lokasi_penugasan`);

--
-- Indexes for table `konfigurasi_gaji`
--
ALTER TABLE `konfigurasi_gaji`
  ADD PRIMARY KEY (`kode_jenis_gaji`);

--
-- Indexes for table `konfigurasi_potongan`
--
ALTER TABLE `konfigurasi_potongan`
  ADD PRIMARY KEY (`kode_jenis_potongan`);

--
-- Indexes for table `lembur`
--
ALTER TABLE `lembur`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_kantor`
--
ALTER TABLE `lokasi_kantor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_penugasan`
--
ALTER TABLE `lokasi_penugasan`
  ADD PRIMARY KEY (`kode_lokasi_penugasan`),
  ADD KEY `lokasi_penugasan_kode_cabang_foreign` (`kode_cabang`);

--
-- Indexes for table `master_cuti`
--
ALTER TABLE `master_cuti`
  ADD PRIMARY KEY (`kode_cuti`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`kode_izin`);

--
-- Indexes for table `penggajian`
--
ALTER TABLE `penggajian`
  ADD PRIMARY KEY (`kode_penggajian`),
  ADD KEY `penggajian_nik_index` (`nik`),
  ADD KEY `penggajian_tanggal_gaji_index` (`tanggal_gaji`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `potongan`
--
ALTER TABLE `potongan`
  ADD PRIMARY KEY (`kode_potongan`),
  ADD KEY `potongan_kode_jabatan_foreign` (`kode_jabatan`),
  ADD KEY `potongan_kode_lokasi_penugasan_foreign` (`kode_lokasi_penugasan`),
  ADD KEY `potongan_kode_cabang_foreign` (`kode_cabang`),
  ADD KEY `potongan_kode_jenis_potongan_foreign` (`kode_jenis_potongan`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `thr`
--
ALTER TABLE `thr`
  ADD PRIMARY KEY (`kode_thr`),
  ADD KEY `thr_nik_foreign` (`nik`),
  ADD KEY `thr_kode_jabatan_foreign` (`kode_jabatan`),
  ADD KEY `thr_kode_lokasi_penugasan_foreign` (`kode_lokasi_penugasan`),
  ADD KEY `thr_kode_cabang_foreign` (`kode_cabang`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashbon`
--
ALTER TABLE `cashbon`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cashbon_karyawan_limit`
--
ALTER TABLE `cashbon_karyawan_limit`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cashbon_limit`
--
ALTER TABLE `cashbon_limit`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lembur`
--
ALTER TABLE `lembur`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lokasi_kantor`
--
ALTER TABLE `lokasi_kantor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=327;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cashbon_karyawan_limit`
--
ALTER TABLE `cashbon_karyawan_limit`
  ADD CONSTRAINT `cashbon_karyawan_limit_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `karyawan` (`nik`) ON DELETE CASCADE;

--
-- Constraints for table `gaji`
--
ALTER TABLE `gaji`
  ADD CONSTRAINT `gaji_kode_cabang_foreign` FOREIGN KEY (`kode_cabang`) REFERENCES `kantor_cabang` (`kode_cabang`) ON DELETE SET NULL,
  ADD CONSTRAINT `gaji_kode_jenis_gaji_foreign` FOREIGN KEY (`kode_jenis_gaji`) REFERENCES `konfigurasi_gaji` (`kode_jenis_gaji`) ON DELETE SET NULL,
  ADD CONSTRAINT `gaji_kode_lokasi_penugasan_foreign` FOREIGN KEY (`kode_lokasi_penugasan`) REFERENCES `lokasi_penugasan` (`kode_lokasi_penugasan`) ON DELETE SET NULL;

--
-- Constraints for table `jam_kerja_lokasi_penugasan`
--
ALTER TABLE `jam_kerja_lokasi_penugasan`
  ADD CONSTRAINT `jam_kerja_lokasi_penugasan_kode_cabang_foreign` FOREIGN KEY (`kode_cabang`) REFERENCES `kantor_cabang` (`kode_cabang`) ON DELETE CASCADE,
  ADD CONSTRAINT `jam_kerja_lokasi_penugasan_kode_jam_kerja_foreign` FOREIGN KEY (`kode_jam_kerja`) REFERENCES `jam_kerja` (`kode_jam_kerja`) ON DELETE CASCADE,
  ADD CONSTRAINT `jam_kerja_lokasi_penugasan_kode_lokasi_penugasan_foreign` FOREIGN KEY (`kode_lokasi_penugasan`) REFERENCES `lokasi_penugasan` (`kode_lokasi_penugasan`) ON DELETE CASCADE;

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `karyawan_kode_lokasi_penugasan_foreign` FOREIGN KEY (`kode_lokasi_penugasan`) REFERENCES `lokasi_penugasan` (`kode_lokasi_penugasan`) ON DELETE SET NULL;

--
-- Constraints for table `lokasi_penugasan`
--
ALTER TABLE `lokasi_penugasan`
  ADD CONSTRAINT `lokasi_penugasan_kode_cabang_foreign` FOREIGN KEY (`kode_cabang`) REFERENCES `kantor_cabang` (`kode_cabang`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `potongan`
--
ALTER TABLE `potongan`
  ADD CONSTRAINT `potongan_kode_cabang_foreign` FOREIGN KEY (`kode_cabang`) REFERENCES `kantor_cabang` (`kode_cabang`) ON DELETE SET NULL,
  ADD CONSTRAINT `potongan_kode_jabatan_foreign` FOREIGN KEY (`kode_jabatan`) REFERENCES `jabatan` (`kode_jabatan`) ON DELETE SET NULL,
  ADD CONSTRAINT `potongan_kode_jenis_potongan_foreign` FOREIGN KEY (`kode_jenis_potongan`) REFERENCES `konfigurasi_potongan` (`kode_jenis_potongan`) ON DELETE SET NULL,
  ADD CONSTRAINT `potongan_kode_lokasi_penugasan_foreign` FOREIGN KEY (`kode_lokasi_penugasan`) REFERENCES `lokasi_penugasan` (`kode_lokasi_penugasan`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `thr`
--
ALTER TABLE `thr`
  ADD CONSTRAINT `thr_kode_cabang_foreign` FOREIGN KEY (`kode_cabang`) REFERENCES `kantor_cabang` (`kode_cabang`) ON DELETE SET NULL,
  ADD CONSTRAINT `thr_kode_jabatan_foreign` FOREIGN KEY (`kode_jabatan`) REFERENCES `jabatan` (`kode_jabatan`) ON DELETE SET NULL,
  ADD CONSTRAINT `thr_kode_lokasi_penugasan_foreign` FOREIGN KEY (`kode_lokasi_penugasan`) REFERENCES `lokasi_penugasan` (`kode_lokasi_penugasan`) ON DELETE SET NULL,
  ADD CONSTRAINT `thr_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `karyawan` (`nik`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
