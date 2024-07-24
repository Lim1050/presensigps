-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2024 at 04:06 PM
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
  `kode_departemen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_departemen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`kode_departemen`, `nama_departemen`, `created_at`, `updated_at`) VALUES
('FIN', 'Finance', NULL, '2024-07-01 07:59:20'),
('HR', 'Human Resources', NULL, NULL),
('IT', 'Information Technology', NULL, NULL),
('MKT', 'Marketing', NULL, NULL),
('OPS', 'Operations', NULL, NULL),
('SM', 'Social Media', '2024-07-01 07:34:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bulan` int DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `jumlah_masuk` int DEFAULT NULL,
  `gaji_per_hari` decimal(8,2) DEFAULT NULL,
  `total_gaji` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja`
--

CREATE TABLE `jam_kerja` (
  `kode_jam_kerja` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jam_kerja` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `awal_jam_masuk` time NOT NULL,
  `jam_masuk` time NOT NULL,
  `akhir_jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja`
--

INSERT INTO `jam_kerja` (`kode_jam_kerja`, `nama_jam_kerja`, `awal_jam_masuk`, `jam_masuk`, `akhir_jam_masuk`, `jam_pulang`, `created_at`, `updated_at`) VALUES
('JK01', 'Shift Pagi', '00:00:00', '01:00:00', '02:00:00', '08:00:00', NULL, '2024-07-12 06:50:38'),
('JK02', 'Shift Siang', '08:00:00', '09:00:00', '10:00:00', '16:00:00', NULL, '2024-07-16 08:54:50'),
('JK03', 'Shift Malam', '16:00:00', '17:00:00', '18:00:00', '23:59:00', NULL, '2024-07-16 09:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_dept`
--

CREATE TABLE `jam_kerja_dept` (
  `kode_jk_dept` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_cabang` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_departemen` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja_dept`
--

INSERT INTO `jam_kerja_dept` (`kode_jk_dept`, `kode_cabang`, `kode_departemen`, `created_at`, `updated_at`) VALUES
('JBKSFIN', 'BKS', 'FIN', '2024-07-18 05:29:45', NULL),
('JBKSHR', 'BKS', 'HR', '2024-07-19 07:06:10', NULL),
('JBKSIT', 'BKS', 'IT', '2024-07-18 05:38:46', NULL),
('JBKSMKT', 'BKS', 'MKT', '2024-07-19 07:06:32', NULL),
('JBKSOPS', 'BKS', 'OPS', '2024-07-19 07:07:19', NULL),
('JBKSSM', 'BKS', 'SM', '2024-07-19 07:07:49', NULL),
('JJKTPFIN', 'JKTP', 'FIN', '2024-07-18 05:37:26', NULL),
('JJKTPIT', 'JKTP', 'IT', '2024-07-18 05:28:42', NULL),
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
('JJKTPIT', 'Senin', 'JK03', '2024-07-18 05:28:42', NULL),
('JJKTPIT', 'Selasa', 'JK03', '2024-07-18 05:28:42', NULL),
('JJKTPIT', 'Rabu', 'JK02', '2024-07-18 05:28:42', NULL),
('JJKTPIT', 'Kamis', 'JK02', '2024-07-18 05:28:42', NULL),
('JJKTPIT', 'Jumat', 'JK02', '2024-07-18 05:28:42', NULL),
('JJKTPIT', 'Sabtu', 'JK01', '2024-07-18 05:28:42', NULL),
('JJKTPIT', 'Minggu', 'JK01', '2024-07-18 05:28:42', NULL),
('JJKTPFIN', 'Senin', 'JK02', '2024-07-18 05:37:26', NULL),
('JJKTPFIN', 'Selasa', 'JK02', '2024-07-18 05:37:26', NULL),
('JJKTPFIN', 'Rabu', 'JK02', '2024-07-18 05:37:26', NULL),
('JJKTPFIN', 'Kamis', 'JK02', '2024-07-18 05:37:26', NULL),
('JJKTPFIN', 'Jumat', 'JK02', '2024-07-18 05:37:26', NULL),
('JJKTPFIN', 'Sabtu', 'JK02', '2024-07-18 05:37:26', NULL),
('JJKTPFIN', 'Minggu', 'JK02', '2024-07-18 05:37:26', NULL),
('JBKSIT', 'Senin', 'JK03', '2024-07-18 05:38:46', NULL),
('JBKSIT', 'Selasa', 'JK03', '2024-07-18 05:38:46', NULL),
('JBKSIT', 'Rabu', 'JK03', '2024-07-18 05:38:46', NULL),
('JBKSIT', 'Kamis', 'JK03', '2024-07-18 05:38:46', NULL),
('JBKSIT', 'Jumat', 'JK03', '2024-07-18 05:38:46', NULL),
('JBKSIT', 'Sabtu', 'JK03', '2024-07-18 05:38:46', NULL),
('JBKSIT', 'Minggu', 'JK03', '2024-07-18 05:38:46', NULL),
('JBKSFIN', 'Senin', 'JK03', NULL, '2024-07-18 07:41:16'),
('JBKSFIN', 'Selasa', NULL, NULL, '2024-07-18 07:41:16'),
('JBKSFIN', 'Rabu', 'JK03', NULL, '2024-07-18 07:41:16'),
('JBKSFIN', 'Kamis', NULL, NULL, '2024-07-18 07:41:16'),
('JBKSFIN', 'Jumat', 'JK03', NULL, '2024-07-18 07:41:16'),
('JBKSFIN', 'Sabtu', NULL, NULL, '2024-07-18 07:41:16'),
('JBKSFIN', 'Minggu', 'JK03', NULL, '2024-07-18 07:41:16'),
('JJKTTSM', 'Senin', 'JK03', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Selasa', 'JK02', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Rabu', 'JK03', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Kamis', 'JK02', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Jumat', 'JK03', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Sabtu', 'JK02', NULL, '2024-07-18 08:29:34'),
('JJKTTSM', 'Minggu', 'JK03', NULL, '2024-07-18 08:29:34'),
('JBKSHR', 'Senin', 'JK02', '2024-07-19 07:06:10', NULL),
('JBKSHR', 'Selasa', 'JK02', '2024-07-19 07:06:10', NULL),
('JBKSHR', 'Rabu', 'JK02', '2024-07-19 07:06:10', NULL),
('JBKSHR', 'Kamis', 'JK02', '2024-07-19 07:06:10', NULL),
('JBKSHR', 'Jumat', 'JK02', '2024-07-19 07:06:10', NULL),
('JBKSHR', 'Sabtu', NULL, '2024-07-19 07:06:10', NULL),
('JBKSHR', 'Minggu', NULL, '2024-07-19 07:06:10', NULL),
('JBKSMKT', 'Senin', 'JK02', '2024-07-19 07:06:32', NULL),
('JBKSMKT', 'Selasa', 'JK02', '2024-07-19 07:06:32', NULL),
('JBKSMKT', 'Rabu', 'JK02', '2024-07-19 07:06:32', NULL),
('JBKSMKT', 'Kamis', 'JK02', '2024-07-19 07:06:32', NULL),
('JBKSMKT', 'Jumat', 'JK02', '2024-07-19 07:06:32', NULL),
('JBKSMKT', 'Sabtu', NULL, '2024-07-19 07:06:32', NULL),
('JBKSMKT', 'Minggu', NULL, '2024-07-19 07:06:32', NULL),
('JBKSOPS', 'Senin', 'JK01', '2024-07-19 07:07:19', NULL),
('JBKSOPS', 'Selasa', 'JK02', '2024-07-19 07:07:19', NULL),
('JBKSOPS', 'Rabu', 'JK03', '2024-07-19 07:07:19', NULL),
('JBKSOPS', 'Kamis', 'JK01', '2024-07-19 07:07:19', NULL),
('JBKSOPS', 'Jumat', 'JK02', '2024-07-19 07:07:19', NULL),
('JBKSOPS', 'Sabtu', 'JK03', '2024-07-19 07:07:19', NULL),
('JBKSOPS', 'Minggu', 'JK01', '2024-07-19 07:07:19', NULL),
('JBKSSM', 'Senin', 'JK01', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Selasa', 'JK02', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Rabu', 'JK03', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Kamis', 'JK03', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Jumat', 'JK01', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Sabtu', 'JK02', '2024-07-19 07:07:49', NULL),
('JBKSSM', 'Minggu', 'JK02', '2024-07-19 07:07:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_karyawan`
--

CREATE TABLE `jam_kerja_karyawan` (
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_jam_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_kerja_karyawan`
--

INSERT INTO `jam_kerja_karyawan` (`nik`, `hari`, `kode_jam_kerja`, `created_at`, `updated_at`) VALUES
('369258147', 'Senin', 'JK01', '2024-07-12 08:42:15', '2024-07-12 08:42:15'),
('369258147', 'Selasa', 'JK02', '2024-07-12 08:42:15', '2024-07-12 08:42:15'),
('369258147', 'Rabu', 'JK03', '2024-07-12 08:42:15', '2024-07-12 08:42:15'),
('369258147', 'Kamis', NULL, '2024-07-12 08:42:15', '2024-07-12 08:42:15'),
('369258147', 'Jumat', 'JK01', '2024-07-12 08:42:15', '2024-07-12 08:42:15'),
('369258147', 'Sabtu', 'JK02', '2024-07-12 08:42:15', '2024-07-12 08:42:15'),
('369258147', 'Minggu', 'JK03', '2024-07-12 08:42:15', '2024-07-12 08:42:15'),
('123456789', 'Senin', 'JK02', '2024-07-12 08:48:22', '2024-07-12 08:48:22'),
('123456789', 'Selasa', 'JK03', '2024-07-12 08:48:22', '2024-07-12 08:48:22'),
('123456789', 'Rabu', 'JK01', '2024-07-12 08:48:22', '2024-07-12 08:48:22'),
('123456789', 'Kamis', 'JK02', '2024-07-12 08:48:22', '2024-07-12 08:48:22'),
('123456789', 'Jumat', 'JK03', '2024-07-12 08:48:22', '2024-07-12 08:48:22'),
('123456789', 'Sabtu', 'JK01', '2024-07-12 08:48:22', '2024-07-12 08:48:22'),
('123456789', 'Minggu', NULL, '2024-07-12 08:48:22', '2024-07-12 08:48:22'),
('213213213', 'Senin', 'JK03', '2024-07-12 08:49:03', NULL),
('213213213', 'Selasa', 'JK02', '2024-07-12 08:49:03', NULL),
('213213213', 'Rabu', 'JK01', '2024-07-12 08:49:03', NULL),
('213213213', 'Kamis', 'JK01', '2024-07-12 08:49:03', NULL),
('213213213', 'Jumat', 'JK02', '2024-07-12 08:49:03', NULL),
('213213213', 'Sabtu', 'JK03', '2024-07-12 08:49:03', NULL),
('213213213', 'Minggu', 'JK03', '2024-07-12 08:49:03', NULL),
('123123123', 'Senin', 'JK01', '2024-07-12 08:49:21', NULL),
('123123123', 'Selasa', 'JK02', '2024-07-12 08:49:21', NULL),
('123123123', 'Rabu', 'JK03', '2024-07-12 08:49:21', NULL),
('123123123', 'Kamis', 'JK01', '2024-07-12 08:49:21', NULL),
('123123123', 'Jumat', 'JK02', '2024-07-12 08:49:21', NULL),
('123123123', 'Sabtu', 'JK03', '2024-07-12 08:49:21', NULL),
('123123123', 'Minggu', 'JK01', '2024-07-12 08:49:21', NULL),
('321321321', 'Senin', 'JK01', '2024-07-16 09:05:52', '2024-07-16 09:05:52'),
('321321321', 'Selasa', 'JK03', '2024-07-16 09:05:52', '2024-07-16 09:05:52'),
('321321321', 'Rabu', 'JK03', '2024-07-16 09:05:52', '2024-07-16 09:05:52'),
('321321321', 'Kamis', 'JK03', '2024-07-16 09:05:52', '2024-07-16 09:05:52'),
('321321321', 'Jumat', 'JK01', '2024-07-16 09:05:52', '2024-07-16 09:05:52'),
('321321321', 'Sabtu', 'JK02', '2024-07-16 09:05:52', '2024-07-16 09:05:52'),
('321321321', 'Minggu', 'JK03', '2024-07-16 09:05:52', '2024-07-16 09:05:52'),
('333333333', 'Senin', 'JK01', '2024-07-18 08:36:07', '2024-07-18 08:36:07'),
('333333333', 'Selasa', NULL, '2024-07-18 08:36:07', '2024-07-18 08:36:07'),
('333333333', 'Rabu', 'JK02', '2024-07-18 08:36:07', '2024-07-18 08:36:07'),
('333333333', 'Kamis', NULL, '2024-07-18 08:36:07', '2024-07-18 08:36:07'),
('333333333', 'Jumat', 'JK03', '2024-07-18 08:36:07', '2024-07-18 08:36:07'),
('333333333', 'Sabtu', NULL, '2024-07-18 08:36:07', '2024-07-18 08:36:07'),
('333333333', 'Minggu', 'JK01', '2024-07-18 08:36:07', '2024-07-18 08:36:07');

-- --------------------------------------------------------

--
-- Table structure for table `kantor_cabang`
--

CREATE TABLE `kantor_cabang` (
  `kode_cabang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_cabang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_kantor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_departemen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_wa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama_lengkap`, `foto`, `jabatan`, `kode_departemen`, `kode_cabang`, `no_wa`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
('123123123', 'Salim Purnama Ramadhan', '123123123_1720603406.png', 'Head IT', 'IT', 'JKTP', '08123456789', '$2y$10$NBvJhmZ0TWDts4FcZ4oBduCgFafZex/8WfCakwD4KjTqafnemB7fa', NULL, '2024-06-21 01:22:30', '2024-07-10 09:23:26'),
('123456789', 'Mei Ling', '123456789_1719807160.png', 'Head Accountant', 'FIN', 'JKTP', '08147258369', '$2y$10$NiSacYyb3uDeR2eWQ/j.MeFEE/g8Eh5SAhp.ErErDgsg46ST8Q1Lq', NULL, '2024-06-28 07:19:07', '2024-07-10 08:51:11'),
('213213213', 'Ramadhan S Purnama', '213213213_1719807132.png', 'Head HRD', 'HR', 'JKTT', '08123456789', '$2y$10$iyjXNfqYeuZTj3iE/U9WKeWHlTZ7DoiWQIgaaDp53GxZ4B2DDvE1a', NULL, '2024-06-21 01:22:30', '2024-07-10 08:50:57'),
('222-222-222', 'Ujang', '222-222-222_1721201871.png', 'Head Operations', 'OPS', 'BKS', '0822222222222', '$2y$10$7AK7gdSf0cx8yhVpTRi/luMC9AKXN6M7fikDLL1UGjmVHnRxovP32', NULL, '2024-07-17 07:37:52', '2024-07-19 07:09:29'),
('321321321', 'Purnama R Salim', '321321321_1719807141.png', 'Head Finance', 'FIN', 'JKTP', '08123456789', '$2y$10$iyjXNfqYeuZTj3iE/U9WKeWHlTZ7DoiWQIgaaDp53GxZ4B2DDvE1a', NULL, '2024-06-21 01:22:30', '2024-07-16 09:05:35'),
('333333333', 'Ridho', '333333333_1721293448.png', 'Recepcionist', 'HR', 'BKS', '0833333333333', '$2y$10$n2wY2wxFJyHwufrCqBJyFeT0yfxsRcsAlyRneOOyuwx0bYqMnGZ/y', NULL, '2024-07-17 07:43:12', '2024-07-18 09:04:08'),
('369258147', 'Ling Mei', '369258147_1719807170.png', 'Assistance Accountant', 'FIN', 'JKTP', '123456789', '$2y$10$IXxUEvNVZY1S/QuQ2qQBie.m0LgTz4Pp8qy7LEC5NYuWR1GLP7V22', NULL, '2024-06-28 07:26:47', '2024-07-16 05:47:25'),
('444444444', 'Abrar', '444444444_1721372945.jpg', 'Asistance IT', 'IT', 'BKS', '0834444444444', '$2y$10$/UW.ORjK5zgS/Rq6wtIU3eyA0Z.FVvlVSXjVv0uqy.g6dYyFfTw7.', NULL, '2024-07-19 07:09:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_kantor`
--

CREATE TABLE `lokasi_kantor` (
  `id` bigint UNSIGNED NOT NULL,
  `lokasi_kantor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `master_cuti`
--

CREATE TABLE `master_cuti` (
  `kode_cuti` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_cuti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(40, '2024_07_23_161528_add_kode_cuti_to_pengajuan_izin_table', 22);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `kode_izin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_izin_dari` date DEFAULT NULL,
  `tanggal_izin_sampai` date DEFAULT NULL,
  `jumlah_hari` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cuti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('sakit','izin','cuti') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_approved` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `surat_sakit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`kode_izin`, `nik`, `tanggal_izin_dari`, `tanggal_izin_sampai`, `jumlah_hari`, `kode_cuti`, `status`, `status_approved`, `keterangan`, `surat_sakit`, `created_at`, `updated_at`) VALUES
('IA0724001', '123123123', '2024-07-25', '2024-07-27', '3 Hari', NULL, 'izin', 2, 'Acara Healing', NULL, '2024-07-23 07:02:40', '2024-07-24 04:59:06'),
('IA0724005', '123123123', '2024-07-26', '2024-08-02', '8 Hari', NULL, 'izin', 0, 'Tugas Luar Kota', NULL, '2024-07-24 07:26:57', NULL),
('IC0724004', '123123123', '2024-07-24', '2024-07-27', '4 Hari', 'C002', 'cuti', 1, 'Demam', NULL, '2024-07-23 09:21:34', NULL),
('IC0824001', '123123123', '2024-08-04', '2024-08-10', '7 Hari', 'C005', 'cuti', 0, 'Honeymoon di Bali', NULL, '2024-07-24 07:18:34', '2024-07-24 07:23:58'),
('IS0724005', '123123123', '2024-07-25', '2024-07-27', '3 Hari', NULL, 'sakit', 0, 'Demam', 'IS0724005.png', '2024-07-24 07:55:10', '2024-07-24 07:57:49');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
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
  `kode_jam_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tanggal_presensi`, `jam_masuk`, `jam_keluar`, `foto_masuk`, `foto_keluar`, `lokasi_masuk`, `lokasi_keluar`, `kode_jam_kerja`, `created_at`, `updated_at`) VALUES
(59, '123123123', '2024-06-26', '08:41:06', '15:47:43', 'public/uploads/absensi/123123123-2024-06-26-154106-masuk.png', 'public/uploads/absensi/123123123-2024-06-27-103908-keluar.png', '-6.1965,106.8413', '-6.2017103,106.8421285', 'JK02', '2024-06-26 08:41:06', '2024-06-27 03:39:08'),
(60, '321321321', '2024-06-26', '15:47:43', '15:48:10', 'public/uploads/absensi/321321321-2024-06-26-154743-masuk.png', 'public/uploads/absensi/321321321-2024-06-26-154810-keluar.png', '-6.2017112,106.8421335', '-6.2017112,106.8421335', 'JK02', '2024-06-26 08:47:43', '2024-06-26 08:48:10'),
(63, '123123123', '2024-06-27', '10:42:07', '10:42:20', 'public/uploads/absensi/123123123-2024-06-27-104207-masuk.png', 'public/uploads/absensi/123123123-2024-06-27-104220-keluar.png', '-6.2488576,106.6500096', '-6.2488576,106.6500096', 'JK02', '2024-06-27 03:42:07', '2024-06-27 03:42:20'),
(64, '321321321', '2024-06-27', '10:43:04', '10:43:14', 'public/uploads/absensi/321321321-2024-06-27-104304-masuk.png', 'public/uploads/absensi/321321321-2024-06-27-104314-keluar.png', '-6.2488576,106.6500096', '-6.2488576,106.6500096', 'JK02', '2024-06-27 03:43:04', '2024-06-27 03:43:14'),
(65, '213213213', '2024-06-27', '10:44:46', '10:44:58', 'public/uploads/absensi/213213213-2024-06-27-104446-masuk.png', 'public/uploads/absensi/213213213-2024-06-27-104458-keluar.png', '-6.2017015,106.8421304', '-6.2017015,106.8421304', 'JK02', '2024-06-27 03:44:46', '2024-06-27 03:44:58'),
(66, '123123123', '2024-06-28', '10:06:16', NULL, 'public/uploads/absensi/123123123-2024-06-28-100616-masuk.png', NULL, '-6.2017097,106.8421288', NULL, 'JK02', '2024-06-28 03:06:16', NULL),
(67, '321321321', '2024-06-28', '10:07:39', NULL, 'public/uploads/absensi/321321321-2024-06-28-100739-masuk.png', NULL, '-6.2017097,106.8421288', NULL, 'JK02', '2024-06-28 03:07:39', NULL),
(68, '213213213', '2024-06-28', '10:08:23', NULL, 'public/uploads/absensi/213213213-2024-06-28-100823-masuk.png', NULL, '-6.2017092,106.8421308', NULL, 'JK02', '2024-06-28 03:08:23', NULL),
(69, '123123123', '2024-07-01', '11:15:34', '11:15:47', 'public/uploads/absensi/123123123-2024-07-01-111534-masuk.png', 'public/uploads/absensi/123123123-2024-07-01-111547-keluar.png', '-6.2017126,106.8421311', '-6.2017092,106.8421305', 'JK02', '2024-07-01 04:15:34', '2024-07-01 04:15:47'),
(70, '213213213', '2024-07-01', '11:16:20', '11:16:29', 'public/uploads/absensi/213213213-2024-07-01-111620-masuk.png', 'public/uploads/absensi/213213213-2024-07-01-111629-keluar.png', '-6.2017092,106.8421305', '-6.2017092,106.8421305', 'JK02', '2024-07-01 04:16:20', '2024-07-01 04:16:29'),
(71, '321321321', '2024-07-01', '11:17:04', '11:17:12', 'public/uploads/absensi/321321321-2024-07-01-111704-masuk.png', 'public/uploads/absensi/321321321-2024-07-01-111712-keluar.png', '-6.2017089,106.8421297', '-6.2017089,106.8421297', 'JK02', '2024-07-01 04:17:04', '2024-07-01 04:17:12'),
(72, '123456789', '2024-07-01', '11:17:38', '11:17:47', 'public/uploads/absensi/123456789-2024-07-01-111738-masuk.png', 'public/uploads/absensi/123456789-2024-07-01-111747-keluar.png', '-6.2017095,106.8421289', '-6.2017095,106.8421289', 'JK02', '2024-07-01 04:17:38', '2024-07-01 04:17:47'),
(73, '369258147', '2024-07-01', '11:18:23', '11:18:31', 'public/uploads/absensi/369258147-2024-07-01-111823-masuk.png', 'public/uploads/absensi/369258147-2024-07-01-111831-keluar.png', '-6.2017089,106.8421334', '-6.2017089,106.8421334', 'JK02', '2024-07-01 04:18:23', '2024-07-01 04:18:31'),
(74, '123123123', '2024-07-02', '08:01:59', '10:02:26', 'public/uploads/absensi/123123123-2024-07-02-100159-masuk.png', 'public/uploads/absensi/123123123-2024-07-02-100226-keluar.png', '-6.2017102,106.8421316', '-6.2017122,106.8421298', 'JK02', '2024-07-02 03:01:59', '2024-07-02 03:02:26'),
(75, '213213213', '2024-07-02', '09:03:00', '10:03:11', 'public/uploads/absensi/213213213-2024-07-02-100300-masuk.png', 'public/uploads/absensi/213213213-2024-07-02-100311-keluar.png', '-6.2017122,106.8421298', '-6.2017109,106.8421309', 'JK02', '2024-07-02 03:03:00', '2024-07-02 03:03:11'),
(76, '321321321', '2024-07-02', '09:03:51', '10:04:02', 'public/uploads/absensi/321321321-2024-07-02-100351-masuk.png', 'public/uploads/absensi/321321321-2024-07-02-100402-keluar.png', '-6.2017109,106.8421309', '-6.2017109,106.8421309', 'JK02', '2024-07-02 03:03:51', '2024-07-02 03:04:02'),
(77, '123456789', '2024-07-02', '10:04:35', '10:04:51', 'public/uploads/absensi/123456789-2024-07-02-100435-masuk.png', 'public/uploads/absensi/123456789-2024-07-02-100451-keluar.png', '-6.2017085,106.8421292', '-6.2017085,106.8421292', 'JK02', '2024-07-02 03:04:35', '2024-07-02 03:04:51'),
(78, '369258147', '2024-07-02', '10:05:22', '10:05:43', 'public/uploads/absensi/369258147-2024-07-02-100522-masuk.png', 'public/uploads/absensi/369258147-2024-07-02-100543-keluar.png', '-6.201712,106.8421314', '-6.201712,106.8421314', 'JK02', '2024-07-02 03:05:22', '2024-07-02 03:05:43'),
(79, '369258147', '2024-07-03', '14:02:03', '14:02:14', 'public/uploads/absensi/369258147-2024-07-03-140203-masuk.png', 'public/uploads/absensi/369258147-2024-07-03-140214-keluar.png', '-6.2017064,106.8421417', '-6.2017085,106.84214', 'JK02', '2024-07-03 07:02:03', '2024-07-03 07:02:14'),
(80, '123456789', '2024-07-03', '14:02:44', '14:02:53', 'public/uploads/absensi/123456789-2024-07-03-140244-masuk.png', 'public/uploads/absensi/123456789-2024-07-03-140253-keluar.png', '-6.2017085,106.84214', '-6.2017085,106.84214', 'JK02', '2024-07-03 07:02:44', '2024-07-03 07:02:53'),
(81, '321321321', '2024-07-03', '14:03:29', '14:03:53', 'public/uploads/absensi/321321321-2024-07-03-140329-masuk.png', 'public/uploads/absensi/321321321-2024-07-03-140353-keluar.png', '-6.2017085,106.84214', '-6.2017085,106.84214', 'JK02', '2024-07-03 07:03:29', '2024-07-03 07:03:53'),
(82, '213213213', '2024-07-03', '14:04:21', '14:04:32', 'public/uploads/absensi/213213213-2024-07-03-140421-masuk.png', 'public/uploads/absensi/213213213-2024-07-03-140432-keluar.png', '-6.2017069,106.8421366', '-6.2017069,106.8421366', 'JK02', '2024-07-03 07:04:21', '2024-07-03 07:04:32'),
(83, '123123123', '2024-07-03', '14:04:59', '14:05:14', 'public/uploads/absensi/123123123-2024-07-03-140459-masuk.png', 'public/uploads/absensi/123123123-2024-07-03-140514-keluar.png', '-6.2017069,106.8421366', '-6.2017069,106.8421366', 'JK02', '2024-07-03 07:04:59', '2024-07-03 07:05:14'),
(84, '369258147', '2024-07-04', '12:51:15', '12:51:28', 'public/uploads/absensi/369258147-2024-07-04-125115-masuk.png', 'public/uploads/absensi/369258147-2024-07-04-125128-keluar.png', '-6.2016967,106.8421446', '-6.2016967,106.8421446', 'JK02', '2024-07-04 05:51:15', '2024-07-04 05:51:28'),
(85, '123456789', '2024-07-04', '12:54:56', '12:55:11', 'public/uploads/absensi/123456789-2024-07-04-125456-masuk.png', 'public/uploads/absensi/123456789-2024-07-04-125511-keluar.png', '-6.201708,106.8421385', '-6.1341696,106.82368', 'JK02', '2024-07-04 05:54:56', '2024-07-04 05:55:11'),
(86, '321321321', '2024-07-04', '12:55:55', '12:56:09', 'public/uploads/absensi/321321321-2024-07-04-125555-masuk.png', 'public/uploads/absensi/321321321-2024-07-04-125609-keluar.png', '-6.1341696,106.82368', '-6.1341696,106.82368', 'JK02', '2024-07-04 05:55:55', '2024-07-04 05:56:09'),
(87, '213213213', '2024-07-04', '12:56:55', '12:57:24', 'public/uploads/absensi/213213213-2024-07-04-125655-masuk.png', 'public/uploads/absensi/213213213-2024-07-04-125724-keluar.png', '-6.1341696,106.82368', '-6.1341696,106.82368', 'JK02', '2024-07-04 05:56:55', '2024-07-04 05:57:24'),
(90, '123123123', '2024-07-04', '14:41:50', '14:42:02', 'public/uploads/absensi/123123123-2024-07-04-144150-masuk.png', 'public/uploads/absensi/123123123-2024-07-04-144202-keluar.png', '-6.2017041,106.8421404', '-6.2017041,106.8421404', 'JK02', '2024-07-04 07:41:50', '2024-07-04 07:42:02'),
(91, '123123123', '2024-07-05', '11:33:57', '11:34:06', 'public/uploads/absensi/123123123-2024-07-05-113357-masuk.png', 'public/uploads/absensi/123123123-2024-07-05-113406-keluar.png', '-6.2017005,106.8421405', '-6.2017005,106.8421405', 'JK02', '2024-07-05 04:33:57', '2024-07-05 04:34:06'),
(92, '213213213', '2024-07-05', '11:34:32', '11:34:42', 'public/uploads/absensi/213213213-2024-07-05-113432-masuk.png', 'public/uploads/absensi/213213213-2024-07-05-113442-keluar.png', '-6.2017005,106.8421405', '-6.2017005,106.8421405', 'JK02', '2024-07-05 04:34:32', '2024-07-05 04:34:42'),
(93, '321321321', '2024-07-05', '11:36:42', '11:36:51', 'public/uploads/absensi/321321321-2024-07-05-113642-masuk.png', 'public/uploads/absensi/321321321-2024-07-05-113651-keluar.png', '-6.2017066,106.8421392', '-6.2017066,106.8421392', 'JK02', '2024-07-05 04:36:42', '2024-07-05 04:36:51'),
(94, '123456789', '2024-07-05', '11:49:07', '11:49:15', 'public/uploads/absensi/123456789-2024-07-05-114907-masuk.png', 'public/uploads/absensi/123456789-2024-07-05-114915-keluar.png', '-6.2017099,106.8421426', '-6.2017099,106.8421426', 'JK02', '2024-07-05 04:49:07', '2024-07-05 04:49:15'),
(95, '369258147', '2024-07-05', '11:49:53', '11:50:04', 'public/uploads/absensi/369258147-2024-07-05-114953-masuk.png', 'public/uploads/absensi/369258147-2024-07-05-115004-keluar.png', '-6.2017005,106.8421405', '-6.2017005,106.8421405', 'JK02', '2024-07-05 04:49:53', '2024-07-05 04:50:04'),
(96, '321321321', '2024-07-10', '16:15:05', '16:15:19', 'public/uploads/absensi/321321321-2024-07-10-161505-masuk.png', 'public/uploads/absensi/321321321-2024-07-10-161519-keluar.png', '-6.2017049,106.84214', '-6.2017061,106.8421378', 'JK02', '2024-07-10 09:15:05', '2024-07-10 09:15:19'),
(97, '123123123', '2024-07-10', '16:21:26', '16:21:36', 'public/uploads/absensi/123123123-2024-07-10-162126-masuk.png', 'public/uploads/absensi/123123123-2024-07-10-162136-keluar.png', '-6.2016955,106.8421458', '-6.2016955,106.8421458', 'JK02', '2024-07-10 09:21:26', '2024-07-10 09:21:36'),
(101, '123123123', '2024-07-16', '12:09:32', '16:00:03', 'public/uploads/absensi/123123123-2024-07-16-120932-masuk.png', 'public/uploads/absensi/123123123-2024-07-16-160003-keluar.png', '-6.2016958,106.8421862', '-6.2016952,106.8421862', 'JK02', '2024-07-16 05:09:32', '2024-07-16 09:00:03'),
(102, '369258147', '2024-07-16', '12:48:32', '16:01:10', 'public/uploads/absensi/369258147-2024-07-16-124832-masuk.png', 'public/uploads/absensi/369258147-2024-07-16-160110-keluar.png', '-6.2016963,106.8421898', '-6.2016994,106.8421952', 'JK02', '2024-07-16 05:48:32', '2024-07-16 09:01:10'),
(103, '123456789', '2024-07-16', '16:02:25', '16:02:40', 'public/uploads/absensi/123456789-2024-07-16-160225-masuk.png', 'public/uploads/absensi/123456789-2024-07-16-160240-keluar.png', '-6.2016962,106.8421918', '-6.2016983,106.8421903', 'JK03', '2024-07-16 09:02:25', '2024-07-16 09:02:40'),
(104, '321321321', '2024-07-16', '16:06:17', '16:07:37', 'public/uploads/absensi/321321321-2024-07-16-160617-masuk.png', 'public/uploads/absensi/321321321-2024-07-16-160737-keluar.png', '-6.2016917,106.8421936', '-6.2016952,106.8421881', 'JK03', '2024-07-16 09:06:17', '2024-07-16 09:07:37'),
(105, '123123123', '2024-07-19', '08:53:39', '16:04:36', 'public/uploads/absensi/123123123-2024-07-19-085339-masuk.png', 'public/uploads/absensi/123123123-2024-07-19-160436-keluar.png', '-6.2016965,106.8421866', '-6.2016976,106.8421972', 'JK02', '2024-07-19 01:53:39', '2024-07-19 09:04:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super_admin@presensi.com', NULL, '$2y$10$t5ZzX/KcfTGcwBq2DP9Qxu4F1kpw3x03V.9tF0NR0HOKxGmR7Wt3.', NULL, '2024-06-27 03:51:34', '2024-06-27 03:51:34'),
(2, 'Admin Dev', 'admin_dev@presensi.com', NULL, '$2y$10$PtlgeqTpFD.acYy1AVw8COgx0otYyR3GJZZkTuNwaG94GxowhjHYe', NULL, '2024-06-27 03:51:34', '2024-06-27 03:51:34');

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `lokasi_kantor`
--
ALTER TABLE `lokasi_kantor`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gaji`
--
ALTER TABLE `gaji`
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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
