-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 04:13 PM
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

INSERT INTO `gaji` (`kode_gaji`, `kode_jabatan`, `kode_jenis_gaji`, `jenis_gaji`, `nama_gaji`, `jumlah_gaji`, `created_at`, `updated_at`) VALUES
('GTKK', 'KEPKAM', 'GT', 'Gaji tetap', 'Gaji Tetap Kepala Keamanan', 5000000.00, '2024-10-15 05:36:13', '2024-10-16 08:04:33'),
('GTPC', 'PCCTV', 'GT', 'Gaji tetap', 'Gaji Tetap Petugas CCTV', 4000000.00, '2024-10-15 05:38:02', '2024-10-16 08:06:29'),
('GTPKG', 'PKG', 'GT', 'Gaji tetap', 'Gaji Tetap Petugas Kontrol Gedung', 4000000.00, '2024-10-15 05:40:30', '2024-10-16 08:07:08'),
('GTPM', 'PENMAL', 'GT', 'Gaji tetap', 'Gaji Tetap Penjaga Malam', 4000000.00, '2024-10-15 05:39:09', '2024-10-16 08:06:44'),
('GTPPM', 'PPM', 'GT', 'Gaji tetap', 'Gaji Tetap Petugas Pintu Masuk', 3500000.00, '2024-10-15 05:41:57', '2024-10-16 08:07:18'),
('GTSK', 'SUPKAM', 'GT', 'Gaji tetap', 'Gaji Tetap Supervisor Keamanan', 5000000.00, '2024-10-15 05:45:15', '2024-10-16 08:07:43'),
('GTSO', 'SO', 'GT', 'Gaji tetap', 'Gaji Tetap Security Officer', 3750000.00, '2024-10-15 05:43:40', '2024-10-16 08:07:31'),
('GTWKK', 'WAKAM', 'GT', 'Gaji tetap', 'Gaji Tetap Wakil Kepala Keamanan', 4500000.00, '2024-10-15 05:46:22', '2024-10-16 08:07:53'),
('TJKK', 'KEPKAM', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Kepala Keamanan', 2500000.00, '2024-10-15 05:36:51', '2024-10-16 08:06:22'),
('TJPC', 'PCCTV', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Petugas CCTV', 2000000.00, '2024-10-15 05:38:38', '2024-10-16 08:06:37'),
('TJPKG', 'PKG', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Petugas Kontrol Gedung', 2000000.00, '2024-10-15 05:41:04', '2024-10-16 08:07:13'),
('TJPM', 'PENMAL', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Penjaga Malam', 2000000.00, '2024-10-15 05:39:41', '2024-10-16 08:06:54'),
('TJPPM', 'PPM', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Petugas Pintu Masuk', 1750000.00, '2024-10-15 05:42:50', '2024-10-16 08:07:24'),
('TJSK', 'SUPKAM', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Supervisor Keamanan', 2500000.00, '2024-10-15 05:45:44', '2024-10-16 08:07:47'),
('TJSO', 'SO', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Security Officer', 1875000.00, '2024-10-15 05:44:22', '2024-10-16 08:07:37'),
('TJWKK', 'WAKAM', 'TJ', 'Tunjangan jabatan', 'Tunjangan Jabatan Wakil Kepala Keamanan', 2250000.00, '2024-10-15 05:47:11', '2024-10-16 08:07:59'),
('TKK', 'KEPKAM', 'TR', NULL, 'Transportasi Kepala Keamanan', 100000.00, '2024-10-17 05:52:56', '2024-10-17 05:52:56'),
('TST', 'PPM', 'MKN', NULL, 'TEST GAJI SO', 2222222.00, '2024-10-16 08:11:17', '2024-10-16 08:20:14'),
('UMKK', 'KEPKAM', 'MKN', NULL, 'Uang Makan Kepala Keamanan', 50000.00, '2024-10-17 05:52:26', '2024-10-17 05:52:26');

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

INSERT INTO `jam_kerja` (`kode_jam_kerja`, `nama_jam_kerja`, `awal_jam_masuk`, `jam_masuk`, `akhir_jam_masuk`, `jam_pulang`, `lintas_hari`, `created_at`, `updated_at`) VALUES
('JK01', 'Shift Pagi', '00:00:00', '01:00:00', '02:00:00', '08:00:00', '0', NULL, '2024-08-06 07:01:36'),
('JK02', 'Shift Siang', '13:00:00', '13:15:00', '13:30:00', '13:45:00', '0', NULL, '2024-10-18 06:53:32'),
('JK03', 'Shift Malam', '16:00:00', '17:00:00', '18:00:00', '00:00:00', '1', NULL, '2024-10-14 16:35:25'),
('JKT', 'Test Lintas Hari', '14:00:00', '15:00:00', '16:00:00', '10:00:00', '1', '2024-08-06 06:54:12', '2024-08-06 07:57:38'),
('JKT01', 'Jam Kerja Test', '14:00:00', '15:00:00', '15:30:00', '16:00:00', '0', '2024-08-06 07:33:06', '2024-08-07 08:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_dept`
--

CREATE TABLE `jam_kerja_dept` (
  `kode_jk_dept` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_cabang` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_departemen` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja_dept`
--

INSERT INTO `jam_kerja_dept` (`kode_jk_dept`, `kode_cabang`, `kode_departemen`, `created_at`, `updated_at`) VALUES
('JBKSSM', 'BKS', 'SM', '2024-07-19 07:07:49', NULL),
('JJKTPSKP', 'JKTP', 'SKP', '2024-10-14 15:57:27', NULL),
('JJKTPTKM', 'JKTP', 'TKM', '2024-10-14 16:03:29', NULL),
('JJKTTSM', 'JKTT', 'SM', '2024-07-18 08:29:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_dept_detail`
--

CREATE TABLE `jam_kerja_dept_detail` (
  `kode_jk_dept` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hari` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jam_kerja` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
('JBKSSM', 'Senin', 'JK01', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Selasa', 'JK02', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Rabu', 'JK03', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Kamis', 'JK03', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Jumat', 'JK01', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Sabtu', 'JK02', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Minggu', 'JK02', '2024-07-19 07:07:49', NULL),
('JJKTPSKP', 'Senin', 'JK02', '2024-10-14 15:57:27', NULL),
('JJKTPSKP', 'Selasa', 'JK02', '2024-10-14 15:57:27', NULL),
('JJKTPSKP', 'Rabu', 'JK02', '2024-10-14 15:57:27', NULL),
('JJKTPSKP', 'Kamis', 'JK02', '2024-10-14 15:57:27', NULL),
('JJKTPSKP', 'Jumat', 'JK02', '2024-10-14 15:57:27', NULL),
('JJKTPSKP', 'Sabtu', 'JK02', '2024-10-14 15:57:27', NULL),
('JJKTPSKP', 'Minggu', 'JK02', '2024-10-14 15:57:27', NULL),
('JJKTPTKM', 'Senin', 'JK03', '2024-10-14 16:03:29', NULL),
('JJKTPTKM', 'Selasa', 'JK03', '2024-10-14 16:03:29', NULL),
('JJKTPTKM', 'Rabu', 'JK03', '2024-10-14 16:03:29', NULL),
('JJKTPTKM', 'Kamis', 'JK03', '2024-10-14 16:03:29', NULL),
('JJKTPTKM', 'Jumat', 'JK03', '2024-10-14 16:03:29', NULL),
('JJKTPTKM', 'Sabtu', 'JK03', '2024-10-14 16:03:29', NULL),
('JJKTPTKM', 'Minggu', 'JK03', '2024-10-14 16:03:29', NULL);

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
('123123123', 'Senin', 'JK03', '2024-10-14 16:28:24', '2024-10-14 16:28:24'),
('123123123', 'Selasa', 'JK02', '2024-10-14 16:28:24', '2024-10-14 16:28:24'),
('123123123', 'Rabu', 'JK03', '2024-10-14 16:28:24', '2024-10-14 16:28:24'),
('123123123', 'Kamis', 'JK03', '2024-10-14 16:28:24', '2024-10-14 16:28:24'),
('123123123', 'Jumat', 'JK02', '2024-10-14 16:28:24', '2024-10-14 16:28:24'),
('123123123', 'Sabtu', 'JK03', '2024-10-14 16:28:24', '2024-10-14 16:28:24'),
('123123123', 'Minggu', 'JK02', '2024-10-14 16:28:24', '2024-10-14 16:28:24');

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
  `kode_lokasi_penugasan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
('111222333', 'Anggota Test', '111222333_1729499601.webp', 'SO', 'SEC', 'BKS', 'SMB', '009988776655', '$2y$10$3Nxe33WWxex8K7/Ykv3cJe2Y1ODTtME4XOpCffxwsZ7Hji4YexrD6', NULL, '2024-10-21 08:33:21', '2024-10-21 08:36:15'),
('112233445', 'Test Karyawan', '112233445_1728920637.jpeg', 'KEPKAM', 'SKP', 'JKTP', 'MN', '1122334455667', '$2y$10$0XL2yinuxM2giFvK9/rKWOtU6RdGFYhVN4aPkFFwychHOTLJRXTBG', NULL, '2024-10-14 15:43:57', '2024-10-21 08:37:19'),
('123123123', 'Salim Purnama Ramadhan', '123123123_1729498839.jpg', 'KEPKAM', 'SAG', 'JKTP', 'MN', '08123456789', '$2y$10$NBvJhmZ0TWDts4FcZ4oBduCgFafZex/8WfCakwD4KjTqafnemB7fa', NULL, '2024-06-21 01:22:30', '2024-10-21 08:37:01'),
('123456789', 'Mei Ling', '123456789_1719807160.png', 'WAKAM', 'SGP', 'JKTP', 'MN', '08147258369', '$2y$10$NiSacYyb3uDeR2eWQ/j.MeFEE/g8Eh5SAhp.ErErDgsg46ST8Q1Lq', NULL, '2024-06-28 07:19:07', '2024-10-21 08:36:30'),
('213213213', 'Ramadhan S Purnama', '213213213_1719807132.png', 'SUPKAM', 'SM', 'JKTT', 'GR', '08123456789', '$2y$10$iyjXNfqYeuZTj3iE/U9WKeWHlTZ7DoiWQIgaaDp53GxZ4B2DDvE1a', NULL, '2024-06-21 01:22:30', '2024-10-21 08:36:43'),
('222222222', 'Ujang', '222-222-222_1721201871.png', 'SO', 'SAP', 'JKTP', 'MN', '0822222222222', '$2y$10$ZVRKYmoOqeB3zY3iRZ7Fe.UQIOXku0slcNTCuXxZcAflWmYiqt8y.', NULL, '2024-07-17 07:37:52', '2024-10-21 08:39:18'),
('321321321', 'Purnama R Salim', '321321321_1719807141.png', 'PCCTV', 'TRC', 'JKTP', 'MN', '08123456789', '$2y$10$iyjXNfqYeuZTj3iE/U9WKeWHlTZ7DoiWQIgaaDp53GxZ4B2DDvE1a', NULL, '2024-06-21 01:22:30', '2024-10-21 08:36:35'),
('333333333', 'Ridho', '333333333_1721293448.png', 'PPM', 'SPMU', 'BKS', 'SMB', '0833333333333', '$2y$10$n2wY2wxFJyHwufrCqBJyFeT0yfxsRcsAlyRneOOyuwx0bYqMnGZ/y', NULL, '2024-07-17 07:43:12', '2024-10-21 08:36:52'),
('369258147', 'Ling Mei', '369258147_1719807170.png', 'PENMAL', 'TKM', 'JKTP', 'MN', '123456789', '$2y$10$IXxUEvNVZY1S/QuQ2qQBie.m0LgTz4Pp8qy7LEC5NYuWR1GLP7V22', NULL, '2024-06-28 07:26:47', '2024-10-21 08:36:22'),
('444444444', 'Abrar', '444444444_1721372945.jpg', 'PKG', 'TKM', 'BKS', 'SMB', '0834444444444', '$2y$10$za9w9xJv98V9YPB3f8U84.OPRJb5DbOY8T1z65qIwdylGXoA6acqy', NULL, '2024-07-19 07:09:05', '2024-10-21 08:36:09'),
('555555555', 'Sidqy Anwar', '555555555_1722323769.jpg', 'SO', 'TRC', 'BKS', 'SMB', '0855555555555', '$2y$10$z2BPEScdYN8KSWsB0nOcau2mCM5Cvk6zenK.mPz6PHNck/lJqEV6W', NULL, '2024-07-30 07:16:09', '2024-10-21 08:37:12'),
('7777777', 'test', '7777777_1729500407.jpg', 'KEPKAM', 'SAG', 'BKS', 'GR', '77777777', '$2y$10$.V7W/mqH.Jo2N2OhJde7MOBaYutBvcN.LkriUUXlY4K6OwHN4nMMe', NULL, '2024-10-21 08:46:47', '2024-10-21 08:46:47');

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
('MKN', 'Uang Makan', 'Uang Makan', 1, '2024-10-16 07:17:15', '2024-10-16 07:17:15'),
('TJ', 'Tunjangan Jabatan', 'Tunjangan Jabatan', 1, '2024-10-16 07:16:27', '2024-10-16 07:16:27'),
('TR', 'Transportasi', 'Transportasi', 1, '2024-10-16 07:16:52', '2024-10-16 07:16:52');

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
  `kode_lokasi_penugasan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lokasi_penugasan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_penugasan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `radius` double(8,2) DEFAULT NULL,
  `kode_cabang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_penugasan`
--

INSERT INTO `lokasi_penugasan` (`kode_lokasi_penugasan`, `nama_lokasi_penugasan`, `lokasi_penugasan`, `radius`, `kode_cabang`, `created_at`, `updated_at`) VALUES
('GR', 'GOR Rawamangun', '-6.190608764410666, 106.88994525893163', 5000.00, 'JKTT', '2024-10-21 08:35:59', '2024-10-21 08:35:59'),
('MN', 'Monumen Nasional', '-6.174314150988573, 106.82691709944937', 5000.00, 'JKTP', '2024-10-21 08:34:59', '2024-10-21 08:34:59'),
('SMB', 'Summarecon Mall Bekasi', '-6.225244050554438, 107.00119587589245', 5000.00, 'BKS', '2024-10-21 08:34:28', '2024-10-21 08:34:28');

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
(53, '2024_08_08_204034_create_penggajians_table', 28),
(54, '2024_08_08_204401_update_karyawan_table', 28),
(55, '2024_08_09_142314_add_username_to_users_table', 28),
(56, '2024_08_13_131219_add_group_name_to_permissions_table', 29),
(57, '2024_10_15_131400_add_jenis_gaji_to_gaji_table', 30),
(58, '2024_10_15_131631_add_fields_to_penggajian_table', 31),
(60, '2024_10_16_124430_create_konfigurasi_gaji_table', 32),
(61, '2024_10_16_145441_add_kode_jenis_gaji_to_gaji_table', 33),
(66, '2024_10_17_135500_add_salary_details_to_penggajian_table', 34),
(67, '2024_10_18_124338_add_change_notes_and_changed_by_to_penggajian_table', 35),
(68, '2024_10_21_134512_create_lokasi_penugasan_table', 36),
(69, '2024_10_21_150922_update_karyawan_table_remove_jabatan_add_lokasi_penugasan', 37);

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
('IS0724007', '444444444', '2024-07-30', '2024-07-30', '1 Hari', NULL, 'sakit', 1, 'pileg', NULL, '2024-07-30 07:39:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penggajian`
--

CREATE TABLE `penggajian` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gaji_tetap` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tunjangan_jabatan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uang_makan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transportasi` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gaji` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bulan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_hari_dalam_bulan` int DEFAULT NULL,
  `jumlah_hari_masuk` int DEFAULT NULL,
  `jumlah_hari_tidak_masuk` int DEFAULT NULL,
  `potongan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_gaji` decimal(10,2) NOT NULL DEFAULT '0.00',
  `catatan_perubahan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `diubah_oleh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_gaji` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penggajian`
--

INSERT INTO `penggajian` (`id`, `nik`, `gaji_tetap`, `tunjangan_jabatan`, `uang_makan`, `transportasi`, `gaji`, `bulan`, `jumlah_hari_dalam_bulan`, `jumlah_hari_masuk`, `jumlah_hari_tidak_masuk`, `potongan`, `total_gaji`, `catatan_perubahan`, `diubah_oleh`, `tanggal_gaji`, `created_at`, `updated_at`) VALUES
(16, '123123123', 5000000.00, 2500000.00, 1500000.00, 3000000.00, 12000000.00, 'Juni', 30, 29, 1, 150000.00, 11850000.00, NULL, NULL, '2024-06-30', '2024-10-17 07:06:06', '2024-10-17 07:06:06'),
(17, '123123123', 5000000.00, 2500000.00, 1550000.00, 3100000.00, 12150000.00, 'Juli', 31, 6, 25, 3750000.00, 8400000.00, NULL, NULL, '2024-07-31', '2024-10-17 07:11:19', '2024-10-17 07:11:19'),
(18, '123123123', 5000000.00, 2500000.00, 1550000.00, 3100000.00, 12150000.00, 'Agustus', 31, 8, 23, 3450000.00, 8700000.00, NULL, NULL, '2024-08-31', '2024-10-17 07:11:51', '2024-10-17 07:11:51'),
(19, '123123123', 5000000.00, 2500000.00, 1500000.00, 3000000.00, 12000000.00, 'September', 30, 0, 30, 4500000.00, 7500000.00, NULL, NULL, '2024-09-30', '2024-10-17 07:12:15', '2024-10-17 07:12:15'),
(20, '123123123', 5000000.00, 2500000.00, 1550000.00, 3100000.00, 12150000.00, 'Oktober', 31, 0, 31, 4650000.00, 7500000.00, NULL, NULL, '2024-10-31', '2024-10-17 07:13:01', '2024-10-17 07:56:37'),
(25, '123456789', 4500000.00, 2250000.00, 0.00, 0.00, 6750000.00, 'Oktober', 31, 0, 31, 0.00, 6750000.00, NULL, NULL, '2024-10-31', '2024-10-18 04:17:21', '2024-10-18 04:17:21'),
(26, '112233445', 5000000.00, 2500000.00, 1550000.00, 3100000.00, 12150000.00, 'Oktober', 31, 25, 6, 900000.00, 11250000.00, 'Test Catatan Perubahan', 'Super Admin', '2024-10-31', '2024-10-18 04:18:17', '2024-10-18 05:50:31'),
(27, '222222222', 3750000.00, 1875000.00, 0.00, 0.00, 5625000.00, 'Oktober', 31, 20, 11, 0.00, 5625000.00, NULL, NULL, '2024-10-31', '2024-10-18 04:19:20', '2024-10-18 04:27:01');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tanggal_presensi`, `jam_masuk`, `jam_keluar`, `foto_masuk`, `foto_keluar`, `lokasi_masuk`, `lokasi_keluar`, `kode_jam_kerja`, `status`, `kode_izin`, `created_at`, `updated_at`) VALUES
(107, '123123123', '2024-07-28', '10:53:37', '15:00:53', 'public/uploads/absensi/123123123-2024-07-28-105337-masuk.png', 'public/uploads/absensi/123123123-2024-07-28-150053-keluar.png', '-6.22592,106.8302336', '-6.22592,106.8302336', 'JK02', 'hadir', NULL, '2024-07-28 03:53:37', '2024-07-28 08:00:53'),
(233, '123123123', '2024-08-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', '2024-07-29 09:10:43', NULL),
(234, '123123123', '2024-08-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', '2024-07-29 09:10:43', NULL),
(235, '123123123', '2024-08-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', '2024-07-29 09:10:43', NULL),
(236, '123123123', '2024-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', '2024-07-29 09:10:43', NULL),
(237, '123123123', '2024-08-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', '2024-07-29 09:10:43', NULL),
(238, '123123123', '2024-08-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', '2024-07-29 09:10:43', NULL),
(239, '123123123', '2024-08-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0824001', '2024-07-29 09:10:43', NULL),
(240, '123123123', '2024-07-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0724006', '2024-07-29 09:10:51', NULL),
(247, '123123123', '2024-06-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(248, '123123123', '2024-06-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(249, '123123123', '2024-06-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(250, '123123123', '2024-06-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(251, '123123123', '2024-06-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(252, '123123123', '2024-06-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(253, '123123123', '2024-06-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(254, '123123123', '2024-06-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(255, '123123123', '2024-06-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(256, '123123123', '2024-06-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(257, '123123123', '2024-06-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(258, '123123123', '2024-06-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(259, '123123123', '2024-06-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(260, '123123123', '2024-06-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(261, '123123123', '2024-06-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(262, '123123123', '2024-06-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(263, '123123123', '2024-06-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(264, '123123123', '2024-06-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(265, '123123123', '2024-06-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(266, '123123123', '2024-06-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(267, '123123123', '2024-06-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(268, '123123123', '2024-06-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(269, '123123123', '2024-06-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(270, '123123123', '2024-06-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(271, '123123123', '2024-06-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(272, '123123123', '2024-06-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(273, '123123123', '2024-06-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(274, '123123123', '2024-06-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(275, '123123123', '2024-06-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(276, '123123123', '2024-06-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0624001', '2024-07-29 09:11:15', NULL),
(277, '123123123', '2024-07-30', '11:13:14', '15:13:14', 'public/uploads/absensi/123123123-2024-07-30-111314-masuk.png', 'public/uploads/absensi/123123123-2024-07-30-111314-masuk.png', '-6.201695,106.8421801', '-6.201695,106.8421801', 'JK02', 'hadir', NULL, '2024-07-30 04:13:14', NULL),
(278, '123123123', '2024-07-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'izin', 'IA0724005', '2024-07-30 05:44:59', NULL),
(279, '123123123', '2024-07-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0724004', '2024-07-30 05:45:06', NULL),
(280, '123123123', '2024-07-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0724004', '2024-07-30 05:45:06', NULL),
(281, '555555555', '2024-07-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'izin', 'IA0724007', '2024-07-30 07:40:09', NULL),
(282, '333333333', '2024-07-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cuti', 'IC0724008', '2024-07-30 07:40:11', NULL),
(283, '444444444', '2024-07-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sakit', 'IS0724007', '2024-07-30 07:40:13', NULL),
(284, '123123123', '2024-08-01', '14:19:54', '16:20:56', 'public/uploads/absensi/123123123-2024-08-01-141954-masuk.png', 'public/uploads/absensi/123123123-2024-08-01-162056-keluar.png', '-6.2017052,106.8421785', '-6.2017082,106.8421777', 'JK03', 'hadir', NULL, '2024-08-01 07:19:54', '2024-08-01 09:20:56'),
(285, '123123123', '2024-08-02', '11:03:15', '16:14:17', 'public/uploads/absensi/123123123-2024-08-02-110315-masuk.png', 'public/uploads/absensi/123123123-2024-08-02-161417-keluar.png', '-6.1964288,106.8433408', '-6.2017027,106.842168', 'JK02', 'hadir', NULL, '2024-08-02 04:03:15', '2024-08-02 09:14:17'),
(286, '444444444', '2024-08-05', '15:58:13', '16:00:43', 'public/uploads/absensi/444444444-2024-08-05-155813-masuk.png', 'public/uploads/absensi/444444444-2024-08-05-160043-keluar.png', '-6.2016715,106.8421765', '-6.2016715,106.8421765', 'JK03', 'hadir', NULL, '2024-08-05 08:58:13', '2024-08-05 09:00:43'),
(290, '555555555', '2024-08-06', '15:01:01', '13:31:25', 'public/uploads/absensi/555555555-2024-08-06-150101-masuk.png', 'public/uploads/absensi/555555555-2024-08-06-133125-keluar.png', '-6.2062592,106.8302336', '-6.2016831,106.8421874', 'JKT', 'hadir', NULL, '2024-08-06 08:01:01', '2024-08-07 06:31:25'),
(291, '222222222', '2024-08-06', '15:01:43', '14:57:36', 'public/uploads/absensi/222222222-2024-08-06-150143-masuk.png', 'public/uploads/absensi/222222222-2024-08-06-145736-keluar.png', '-6.2062592,106.8302336', '-6.1341696,106.82368', 'JKT', 'hadir', NULL, '2024-08-06 08:01:43', '2024-08-07 07:57:36'),
(292, '333333333', '2024-08-06', '15:02:38', '15:04:32', 'public/uploads/absensi/333333333-2024-08-06-150238-masuk.png', 'public/uploads/absensi/333333333-2024-08-06-150432-keluar.png', '-6.2062592,106.8302336', '-6.2062592,106.8302336', 'JKT01', 'hadir', NULL, '2024-08-06 08:02:38', '2024-08-06 08:04:32'),
(293, '222222222', '2024-08-07', '15:03:09', '16:14:55', 'public/uploads/absensi/222222222-2024-08-07-150309-masuk.png', 'public/uploads/absensi/222222222-2024-08-07-161455-keluar.png', '-6.1341696,106.82368', '-6.2016825,106.8421861', 'JKT01', 'hadir', NULL, '2024-08-07 08:03:09', '2024-08-07 09:14:55'),
(294, '555555555', '2024-08-07', '15:03:56', '16:14:31', 'public/uploads/absensi/555555555-2024-08-07-150356-masuk.png', 'public/uploads/absensi/555555555-2024-08-07-161431-keluar.png', '-6.1341696,106.82368', '-6.2016842,106.842187', 'JKT01', 'hadir', NULL, '2024-08-07 08:03:56', '2024-08-07 09:14:31'),
(295, '333333333', '2024-08-07', '15:05:19', '16:14:05', 'public/uploads/absensi/333333333-2024-08-07-150519-masuk.png', 'public/uploads/absensi/333333333-2024-08-07-161405-keluar.png', '-6.1341696,106.82368', '-6.2016842,106.842187', 'JKT01', 'hadir', NULL, '2024-08-07 08:05:19', '2024-08-07 09:14:05'),
(297, '123123123', '2024-10-18', '13:09:24', '13:54:44', 'public/uploads/absensi/123123123-2024-10-18-130924-masuk.png', 'public/uploads/absensi/123123123-2024-10-18-135444-keluar.png', '-6.2017428,106.8420686', NULL, 'JK02', 'hadir', NULL, '2024-10-18 06:09:24', '2024-10-18 06:54:44');

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
  ADD KEY `gaji_kode_jenis_gaji_foreign` (`kode_jenis_gaji`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `penggajian_nik_index` (`nik`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lokasi_kantor`
--
ALTER TABLE `lokasi_kantor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `penggajian`
--
ALTER TABLE `penggajian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

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
-- Constraints for table `gaji`
--
ALTER TABLE `gaji`
  ADD CONSTRAINT `gaji_kode_jenis_gaji_foreign` FOREIGN KEY (`kode_jenis_gaji`) REFERENCES `konfigurasi_gaji` (`kode_jenis_gaji`) ON DELETE SET NULL;

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
