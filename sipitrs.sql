-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sistem_pengaduan
CREATE DATABASE IF NOT EXISTS `sistem_pengaduan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sistem_pengaduan`;

-- Dumping structure for table sistem_pengaduan.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_pengaduan.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table sistem_pengaduan.kategori_perangkat
DROP TABLE IF EXISTS `kategori_perangkat`;
CREATE TABLE IF NOT EXISTS `kategori_perangkat` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sistem_pengaduan.kategori_perangkat: ~15 rows (approximately)
DELETE FROM `kategori_perangkat`;
INSERT INTO `kategori_perangkat` (`id_kategori`, `nama_kategori`) VALUES
	(2, 'PC'),
	(3, 'Laptop'),
	(11, 'Printer / Scanner'),
	(12, 'Handphone'),
	(13, 'Mouse'),
	(14, 'Keyboard'),
	(15, 'Monitor'),
	(16, 'USB LAN'),
	(17, 'Switch Port Hub'),
	(18, 'USB Port'),
	(19, 'Finger Print'),
	(20, 'Web Cam'),
	(21, 'CD Room Ex'),
	(22, 'HDD External'),
	(23, 'Rabspery TV');

-- Dumping structure for table sistem_pengaduan.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_pengaduan.migrations: ~8 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(25, '2014_10_12_000000_create_users_table', 1),
	(26, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(27, '2019_08_19_000000_create_failed_jobs_table', 1),
	(28, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- Dumping structure for table sistem_pengaduan.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_pengaduan.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table sistem_pengaduan.pengaduan
DROP TABLE IF EXISTS `pengaduan`;
CREATE TABLE IF NOT EXISTS `pengaduan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_ruangan` int DEFAULT NULL,
  `id_perangkat` int DEFAULT NULL,
  `nama_pengadu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deskripsi_masalah` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_pengaduan.pengaduan: ~3 rows (approximately)
DELETE FROM `pengaduan`;
INSERT INTO `pengaduan` (`id`, `id_ruangan`, `id_perangkat`, `nama_pengadu`, `deskripsi_masalah`, `created_at`, `updated_at`) VALUES
	(1, 15, 114, 'Reyhan', 'pc matot dan mouse matot', '2026-06-23 05:22:50', '2026-06-23 05:22:50'),
	(2, 15, 115, 'Reyhan', 'pc matot dan mouse matot', '2026-06-23 05:22:50', '2026-06-23 05:22:50'),
	(3, 9, 132, 'Hendro', 'printer mancet', '2026-06-23 08:05:13', '2026-06-23 08:05:13');

-- Dumping structure for table sistem_pengaduan.perangkat
DROP TABLE IF EXISTS `perangkat`;
CREATE TABLE IF NOT EXISTS `perangkat` (
  `id_perangkat` int NOT NULL AUTO_INCREMENT,
  `id_ruangan` int DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `kode_inventaris` varchar(50) DEFAULT NULL,
  `alamat_ip` varchar(50) DEFAULT NULL,
  `merek` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_perangkat`)
) ENGINE=InnoDB AUTO_INCREMENT=389 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sistem_pengaduan.perangkat: ~374 rows (approximately)
DELETE FROM `perangkat`;
INSERT INTO `perangkat` (`id_perangkat`, `id_ruangan`, `id_kategori`, `kode_inventaris`, `alamat_ip`, `merek`) VALUES
	(2, 5, 2, 'MDN/MG/01.01/26/0001', '192.168.1.11', 'SIMBADDA'),
	(3, 5, 11, 'MDN/MG/01.02/26/0001', '-', 'EPSON'),
	(4, 5, 11, 'MDN/MG/01.02/26/0002', '-', 'FARGO'),
	(7, 5, 11, 'MDN/MG/01.02/26/0003', '-', 'GPRINTER'),
	(8, 5, 3, 'MDN/MG/01.03/26/0001', '-', 'ASUSVIVOBOOK'),
	(9, 5, 23, 'MDN/MG/01.05/26/0002', '-', 'LG'),
	(10, 5, 13, 'MDN/MG/01.06/26/0001', '-', 'LOGITECH'),
	(11, 5, 14, 'MDN/MG/01.07/26/0001', '-', 'LOGITECH'),
	(12, 5, 15, 'MDN/MG/01.08/26/0001', '-', 'LOGITECH'),
	(13, 5, 18, 'MDN/MG/01.11/26/0001', '-', 'LOGITECH'),
	(14, 34, 2, 'MDN/MG/01.01/26/0036', '192.168.1.22/24', 'SIMBADDA'),
	(15, 5, 19, 'MDN/MG/01.12/26/0001', '-', 'HID 4500'),
	(16, 34, 2, 'MDN/MG/01.01/26/0037', '192.168.1.21/24', 'ASUS'),
	(17, 34, 11, 'MDN/MG/01.02/26/0019', '-', 'EPSON'),
	(18, 33, 2, 'MDN/MG/01.01/26/0033', '192.168.1.15', 'ASUS'),
	(19, 33, 2, 'MDN/MG/01.01/26/0034', '192.168.1.13', 'ASUS'),
	(20, 33, 2, 'MDN/MG/01.01/26/0035', '192.168.1.14', 'ASUS'),
	(21, 34, 13, 'MDN/MG/01.06/26/0037', '-', 'LOGITECH'),
	(22, 34, 13, 'MDN/MG/01.06/26/0038', '-', 'LOGITECH'),
	(23, 33, 11, 'MDN/MG/01.02/26/0017', '-', 'EPSON'),
	(24, 33, 11, 'MDN/MG/01.02/26/0018', '-', 'EPSON'),
	(25, 34, 14, 'MDN/MG/01.07/26/0036', '-', 'LOGITECH'),
	(26, 34, 14, 'MDN/MG/01.07/26/0037', '-', 'LOGITECH'),
	(27, 33, 23, 'MDN/MG/01.05/26/0008', '-', 'LG'),
	(28, 33, 13, 'MDN/MG/01.06/26/0034', '-', 'ASUS'),
	(29, 34, 15, 'MDN/MG/01.08/26/0037', '-', 'LG'),
	(30, 34, 15, 'MDN/MG/01.08/26/0038', '-', 'LG'),
	(31, 33, 13, 'MDN/MG/01.06/26/0035', '-', 'ASUS'),
	(32, 33, 13, 'MDN/MG/01.06/26/0036', '-', 'ASUS'),
	(33, 33, 14, 'MDN/MG/01.07/26/0033', '-', 'ASUS'),
	(34, 33, 14, 'MDN/MG/01.07/26/0034', '-', 'ASUS'),
	(39, 33, 14, 'MDN/MG/01.07/26/0035', '-', 'ASUS'),
	(40, 6, 2, 'MDN/MG/01.01/26/0002', '192.168.1.19/24', 'SIMBADDA'),
	(41, 33, 15, 'MDN/MG/01.08/26/0034', '-', 'ASUS'),
	(42, 33, 15, 'MDN/MG/01.08/26/0035', '-', 'ASUS'),
	(43, 33, 15, 'MDN/MG/01.08/26/0036', '-', 'ASUS'),
	(44, 6, 11, 'MDN/MG/01.02/26/0004', '-', 'EPSON'),
	(45, 6, 13, 'MDN/MG/01.06/26/0002', '-', 'LOGITECH'),
	(46, 10, 2, 'MDN/MG/01.01/26/0008', '192.168.1.25', 'SIMBADDA'),
	(47, 10, 2, 'MDN/MG/01.01/26/0009', '192.168.1.26', 'SIMBADDA'),
	(48, 10, 2, 'MDN/MG/01.01/26/0010', '192.168.1.27', 'SIMBADDA'),
	(49, 10, 11, 'MDN/MG/01.02/26/0010', '-', 'EPSON'),
	(50, 10, 23, 'MDN/MG/01.05/26/0007', '-', 'LG'),
	(51, 10, 13, 'MDN/MG/01.06/26/0008', '-', 'LOGITECH'),
	(52, 10, 13, 'MDN/MG/01.06/26/0009', '-', 'LOGITECH'),
	(53, 10, 13, 'MDN/MG/01.06/26/0010', '-', 'LOGITECH'),
	(54, 10, 14, 'MDN/MG/01.07/26/0008', '-', 'LOGITECH'),
	(55, 10, 14, 'MDN/MG/01.07/26/0009', '-', 'LOGITECH'),
	(56, 10, 14, 'MDN/MG/01.07/26/0010', '-', 'LOGITECH'),
	(57, 10, 15, 'MDN/MG/01.08/26/0008', '-', 'LG'),
	(58, 10, 15, 'MDN/MG/01.08/26/0009', '-', 'LG'),
	(59, 10, 15, 'MDN/MG/01.08/26/0010', '-', 'LG'),
	(60, 10, 17, 'MDN/MG/01.10/26/0001', '-', 'TP-LINK'),
	(61, 17, 2, 'MDN/MG/01.01/26/0017', '192.168.1.75', 'SIMBADDA'),
	(62, 17, 13, 'MDN/MG/01.06/26/0017', '-', 'LOGITECH'),
	(63, 17, 14, 'MDN/MG/01.07/26/0017', '-', 'LOGITECH'),
	(64, 17, 15, 'MDN/MG/01.08/26/0017', '-', 'LG'),
	(65, 8, 2, 'MDN/MG/01.01/26/0004', '192.168.1.176', 'SIMBADDA'),
	(66, 8, 2, 'MDN/MG/01.01/26/0005', '192.168.1.175/24', 'SIMBADDA'),
	(67, 8, 11, 'MDN/MG/01.02/26/0006', '-', 'EPSON'),
	(68, 8, 11, 'MDN/MG/01.02/26/0007', '-', 'EPSON'),
	(69, 10, 12, 'MDN/MG/01.04/26/0010', '-', 'REALME'),
	(70, 8, 12, 'MDN/MG/01.04/26/0007', '-', 'VIVO'),
	(71, 8, 13, 'MDN/MG/01.06/26/0004', '-', 'LOGITECH'),
	(72, 8, 13, 'MDN/MG/01.06/26/0005', '-', 'LOGITECH'),
	(73, 8, 14, 'MDN/MG/01.07/26/0004', '-', 'LOGITECH'),
	(74, 8, 14, 'MDN/MG/01.07/26/0005', '-', 'LOGITECH'),
	(75, 8, 15, 'MDN/MG/01.08/26/0004', '-', 'LG'),
	(76, 8, 15, 'MDN/MG/01.08/26/0005', '-', 'LG'),
	(77, 11, 2, 'MDN/MG/01.01/26/0011', '192.168.1.61', 'SIMBADDA'),
	(78, 11, 11, 'MDN/MG/01.02/26/0011', '-', 'EPSON'),
	(79, 11, 23, 'MDN/MG/01.05/26/0002', '-', 'LG'),
	(80, 11, 23, 'MDN/MG/01.05/26/0003', '-', 'LG'),
	(81, 11, 23, 'MDN/MG/01.05/26/0004', '-', 'LG'),
	(82, 11, 23, 'MDN/MG/01.05/26/0005', '-', 'LG'),
	(83, 11, 13, 'MDN/MG/01.06/26/0011', '-', 'LOGITECH'),
	(84, 11, 14, 'MDN/MG/01.07/26/0011', '-', 'LOGITECH'),
	(85, 11, 15, 'MDN/MG/01.08/26/0011', '-', 'LG'),
	(86, 33, 20, 'MDN/MG/01.13/26/0001', '-', 'LOGITECH'),
	(87, 6, 14, 'MDN/MG/01.07/26/0002', '-', 'LOGITECH'),
	(88, 18, 2, 'MDN/MG/01.01/26/0024', '192.168.1.79', 'SIMBADDA'),
	(90, 6, 15, 'MDN/MG/01.08/26/0002', '-', 'LG'),
	(91, 7, 2, 'MDN/MG/01.01/26/0003', '192.168.1.23', 'SIMBADDA'),
	(92, 7, 11, 'MDN/MG/01.02/26/0005', '-', 'EPSON'),
	(94, 14, 2, 'MDN/MG/01.01/26/0014', '192.168.1.63', 'SIMBADDA'),
	(95, 20, 2, 'MDN/MG/01.01/26/0020', '192.168.1.81', 'ASUS'),
	(96, 20, 13, 'MDN/MG/01.06/26/0020', '-', 'LOGITECH'),
	(97, 20, 14, 'MDN/MG/01.07/26/0020', '-', 'LOGITECH'),
	(98, 20, 15, 'MDN/MG/01.08/26/0020', '-', 'LG'),
	(99, 14, 13, 'MDN/MG/01.06/26/0014', '-', 'LOGITECH'),
	(100, 14, 14, 'MDN/MG/01.07/26/0014', '-', 'LOGITECH'),
	(101, 14, 15, 'MDN/MG/01.08/26/0014', '-', 'LG'),
	(102, 18, 13, 'MDN/MG/01.06/26/0024', '-', 'LOGITECH'),
	(103, 18, 14, 'MDN/MG/01.07/26/0024', '-', 'LOGITECH'),
	(104, 18, 15, 'MDN/MG/01.08/26/0024', '-', 'LG'),
	(105, 27, 2, 'MDN/MG/01.01/26/0027', '192.168.1.95/24', 'ASUS'),
	(106, 27, 13, 'MDN/MG/01.06/26/0027', '-', 'LOGITECH'),
	(107, 27, 14, 'MDN/MG/01.07/26/0027', '-', 'LOGITECH'),
	(108, 27, 15, 'MDN/MG/01.08/26/0027', '-', 'LG'),
	(109, 27, 16, 'MDN/MG/01.09/26/0003', '-', 'LOGITECH'),
	(110, 19, 2, 'MDN/MG/01.01/26/0019', '192.168.1.77', 'SIMBADDA'),
	(111, 19, 13, 'MDN/MG/01.06/26/0019', '-', 'LOGITECH'),
	(112, 19, 14, 'MDN/MG/01.07/26/0019', '-', 'LOGITECH'),
	(113, 19, 15, 'MDN/MG/01.08/26/0019', '-', 'LG'),
	(114, 15, 2, 'MDN/MG/01.01/26/0015', '192.168.1.69', 'ASUS'),
	(115, 15, 13, 'MDN/MG/01.06/26/0015', '-', 'LOGITECH'),
	(116, 7, 3, 'MDN/MG/01.03/26/0002', '-', 'HP'),
	(117, 15, 14, 'MDN/MG/01.07/26/0015', '-', 'LOGITECH'),
	(118, 7, 3, 'MDN/MG/01.03/26/0003', '-', 'ASUS VIVOBOOK'),
	(119, 15, 15, 'MDN/MG/01.08/26/0015', '-', 'LG'),
	(120, 15, 11, 'MDN/MG/01.02/26/0012', '-', 'EPSON'),
	(121, 7, 13, 'MDN/MG/01.06/26/0003', '-', 'LOGITECH'),
	(122, 7, 14, 'MDN/MG/01.07/26/0003', '-', 'LOGITECH'),
	(123, 7, 15, 'MDN/MG/01.08/26/0003', '-', 'LG'),
	(124, 7, 16, 'MDN/MG/01.09/26/0001', '-', 'LOGITECH'),
	(125, 12, 2, 'MDN/MG/01.01/26/0013', '192.168.1.65', 'ASUS'),
	(126, 12, 13, 'MDN/MG/01.06/26/0013', '-', 'LOGITECH'),
	(127, 12, 14, 'MDN/MG/01.07/26/0013', '-', 'LOGITECH'),
	(128, 12, 15, 'MDN/MG/01.08/26/0013', '-', 'LG'),
	(129, 9, 2, 'MDN/MG/01.01/26/0007', '192.168.1.180', 'SIMBADDA'),
	(130, 9, 2, 'MDN/MG/01.01/26/0006', '192.168.1.179', 'DAZUMBA'),
	(131, 9, 11, 'MDN/MG/01.02/26/0008', '-', 'HP'),
	(132, 9, 11, 'MDN/MG/01.02/26/0009', '-', 'EPSON'),
	(133, 9, 13, 'MDN/MG/01.06/26/0007', '-', 'LOGITECH'),
	(134, 9, 13, 'MDN/MG/01.06/26/0006', '-', 'LOGITECH'),
	(135, 9, 14, 'MDN/MG/01.07/26/0007', '-', 'LOGITECH'),
	(136, 9, 14, 'MDN/MG/01.07/26/0006', '-', 'SILENT KB1000'),
	(137, 9, 15, 'MDN/MG/01.08/26/0007', '-', 'LG'),
	(138, 9, 15, 'MDN/MG/01.08/26/0006', '-', 'LG'),
	(139, 9, 12, 'MDN/MG/01.04/26/0011', '-', 'REALME'),
	(140, 9, 21, 'MDN/MG/01.14/26/0001', '-', 'DELL'),
	(141, 9, 22, 'MDN/MG/01.15/26/0001', '-', 'SEAGETA EXPANSION'),
	(142, 36, 3, 'MDN/MG/01.03/26/0012', '-', 'HP'),
	(143, 36, 13, 'MDN/MG/01.06/26/0032', '-', 'LOGITECH'),
	(144, 36, 15, 'MDN/MG/01.08/26/0032', '-', 'VIEWSONIC'),
	(145, 36, 16, 'MDN/MG/01.09/26/0005', '-', 'LOGITECH'),
	(146, 36, 18, 'MDN/MG/01.11/26/0002', '-', 'ROBOT'),
	(147, 36, 19, 'MDN/MG/01.11/26/0002', '-', 'HID 4500'),
	(148, 32, 2, 'MDN/MG/01.01/26/0038', '192.168.1.36', 'SIMBADDA'),
	(149, 32, 2, 'MDN/MG/01.01/26/0039', '192.168.1.37', 'SIMBADDA'),
	(150, 32, 2, 'MDN/MG/01.01/26/0040', '192.168.1.38', 'SIMBADDA'),
	(151, 32, 2, 'MDN/MG/01.01/26/0041', '192.168.1.45', 'SIMBADDA'),
	(152, 32, 2, 'MDN/MG/01.01/26/0042', '192.168.1.46', '3POWER UP'),
	(153, 32, 2, 'MDN/MG/01.01/26/0043', '192.168.1.40', 'SIMBADDA'),
	(154, 32, 2, 'MDN/MG/01.01/26/0044', '192.168.1.41', 'SIMBADDA'),
	(155, 32, 2, 'MDN/MG/01.01/26/0045', '192.168.1.42', '3POWER UP'),
	(156, 32, 2, 'MDN/MG/01.01/26/0046', '192.168.1.43', 'SIMBADDA'),
	(157, 32, 2, 'MDN/MG/01.01/26/0047', '192.168.1.44', 'SIMBADDA'),
	(158, 32, 11, 'MDN/MG/01.02/26/0020', '-', 'EPSON'),
	(159, 32, 11, 'MDN/MG/01.02/26/0021', '-', 'BROTHER'),
	(160, 32, 11, 'MDN/MG/01.02/26/0022', '-', 'BROTHER'),
	(161, 32, 11, 'MDN/MG/01.02/26/0023', '-', 'BROTHER'),
	(162, 32, 13, 'MDN/MG/01.06/26/0039', '-', 'LOGITECH'),
	(163, 32, 13, 'MDN/MG/01.06/26/0040', '-', 'LOGITECH'),
	(164, 32, 13, 'MDN/MG/01.06/26/0041', '-', 'LOGITECH'),
	(165, 32, 13, 'MDN/MG/01.06/26/0042', '-', 'LOGITECH'),
	(166, 32, 13, 'MDN/MG/01.06/26/0043', '-', 'LOGITECH'),
	(167, 32, 13, 'MDN/MG/01.06/26/0044', '-', 'LOGITECH'),
	(168, 32, 13, 'MDN/MG/01.06/26/0045', '-', 'LOGITECH'),
	(169, 32, 13, 'MDN/MG/01.06/26/0046', '-', 'LOGITECH'),
	(170, 32, 13, 'MDN/MG/01.06/26/0047', '-', 'LOGITECH'),
	(171, 41, 2, 'MDN/MG/01.01/26/0068', '192.168.1.137/24', 'SIMBADDA'),
	(172, 32, 13, 'MDN/MG/01.06/26/0048', '-', 'LOGITECH'),
	(173, 41, 2, 'MDN/MG/01.01/26/0069', '192.168.1.138/24', 'SIMBADDA'),
	(174, 41, 11, 'MDN/MG/01.02/26/0029', '-', 'EPSON'),
	(175, 32, 14, 'MDN/MG/01.07/26/0038', '-', 'LOGITECH'),
	(176, 32, 14, 'MDN/MG/01.07/26/0039', '-', 'LOGITECH'),
	(177, 32, 14, 'MDN/MG/01.07/26/0040', '-', 'LOGITECH'),
	(178, 32, 14, 'MDN/MG/01.07/26/0041', '-', 'LOGITECH'),
	(179, 32, 14, 'MDN/MG/01.07/26/0042', '-', 'LOGITECH'),
	(180, 41, 3, 'MDN/MG/01.03/26/0013', '-', 'HP'),
	(181, 41, 12, 'MDN/MG/01.04/26/0012', '-', 'VIVO'),
	(182, 41, 13, 'MDN/MG/01.06/26/0069', '-', 'LOGITECH'),
	(183, 41, 13, 'MDN/MG/01.06/26/0070', '-', 'LOGITECH'),
	(184, 41, 14, 'MDN/MG/01.07/26/0068', '-', 'LOGITECH'),
	(185, 41, 14, 'MDN/MG/01.07/26/0069', '-', 'LOGITECH'),
	(186, 41, 15, 'MDN/MG/01.08/26/0069', '-', 'LG'),
	(187, 41, 15, 'MDN/MG/01.08/26/0070', '-', 'LG'),
	(188, 41, 17, 'MDN/MG/01.10/26/0009', '-', 'TP LINK'),
	(189, 47, 2, 'MDN/MG/01.01/26/0075', '192.168.1.172/24', 'SIMBADDA'),
	(190, 47, 11, 'MDN/MG/01.02/26/0034', '-', 'EPSON'),
	(191, 47, 3, 'MDN/MG/01.03/26/0011', '-', 'ASUS VIVOBOOK'),
	(192, 47, 12, 'MDN/MG/01.04/26/0009', '-', 'VIVO'),
	(193, 47, 13, 'MDN/MG/01.06/26/0076', '-', 'LOGITECH'),
	(194, 47, 14, 'MDN/MG/01.07/26/0075', '-', 'LOGITECH'),
	(195, 47, 15, 'MDN/MG/01.08/26/0076', '-', 'LG'),
	(196, 13, 2, 'MDN/MG/01.01/26/0012', '192.168.1.67', 'ASUS'),
	(197, 13, 11, 'MDN/MG/01.02/26/0013', '-', 'EPSON'),
	(198, 13, 13, 'MDN/MG/01.06/26/0012', '-', 'LOGITECH'),
	(199, 13, 14, 'MDN/MG/01.07/26/0012', '-', 'LOGITECH'),
	(200, 13, 15, 'MDN/MG/01.08/26/0012', '-', 'LG'),
	(201, 39, 2, 'MDN/MG/01.01/26/0054', '192.168.1.154', 'SIMBADDA'),
	(202, 39, 2, 'MDN/MG/01.01/26/0055', '192.168.1.149/24', 'SIMBADDA'),
	(203, 39, 2, 'MDN/MG/01.01/26/0056', '192.168.1.151/24', 'SIMBADDA'),
	(204, 39, 2, 'MDN/MG/01.01/26/0057', '192.168.1.156/24', 'SIMBADDA'),
	(205, 39, 2, 'MDN/MG/01.01/26/0058', '192.168.1.159/24', 'SIMBADDA'),
	(206, 39, 2, 'MDN/MG/01.01/26/0059', '192.168.1.157/24', 'SIMBADDA'),
	(207, 39, 2, 'MDN/MG/01.01/26/0060', '192.168.1.155', 'CUBEGAMING'),
	(208, 39, 2, 'MDN/MG/01.01/26/0061', '192.168.1.148/24', 'SIMBADDA'),
	(209, 39, 2, 'MDN/MG/01.01/26/0062', '169.254.167.87/16', 'SIMBADDA'),
	(210, 39, 2, 'MDN/MG/01.01/26/0063', '192.168.56.1/24', 'SIMBADDA'),
	(211, 39, 2, 'MDN/MG/01.01/26/0064', '192.168.1.143/24', 'SIMBADDA'),
	(212, 39, 2, 'MDN/MG/01.01/26/0065', '192.168.1.145/24', 'SIMBADDA'),
	(213, 39, 2, 'MDN/MG/01.01/26/0066', '192.168.1.146/24', 'SIMBADDA'),
	(214, 39, 2, 'MDN/MG/01.01/26/0067', '192.168.1.144/24', 'SIMBADDA'),
	(215, 39, 11, 'MDN/MG/01.02/26/0027', '-', 'EPSON'),
	(216, 39, 11, 'MDN/MG/01.02/26/0028', '-', 'EPSON'),
	(217, 39, 3, 'MDN/MG/01.03/26/0015', '-', 'HP'),
	(218, 39, 3, 'MDN/MG/01.03/26/0016', '-', 'LENOVO'),
	(219, 39, 3, 'MDN/MG/01.03/26/0017', '-', 'HP'),
	(220, 39, 13, 'MDN/MG/01.06/26/0055', '-', 'LOGITECH'),
	(221, 39, 13, 'MDN/MG/01.06/26/0056', '-', 'ROBOT'),
	(222, 39, 13, 'MDN/MG/01.06/26/0057', '-', 'LOGITECH'),
	(223, 39, 13, 'MDN/MG/01.06/26/0058', '-', 'LOGITECH'),
	(224, 39, 13, 'MDN/MG/01.06/26/0059', '-', 'LOGITECH'),
	(225, 39, 13, 'MDN/MG/01.06/26/0060', '-', 'LOGITECH'),
	(226, 39, 13, 'MDN/MG/01.06/26/0061', '-', 'LOGITECH'),
	(227, 39, 13, 'MDN/MG/01.06/26/0062', '-', 'LOGITECH'),
	(228, 39, 13, 'MDN/MG/01.06/26/0063', '-', 'LOGITECH'),
	(229, 39, 13, 'MDN/MG/01.06/26/0064', '-', 'LOGITECH'),
	(230, 39, 13, 'MDN/MG/01.06/26/0065', '-', 'LOGITECH'),
	(231, 39, 13, 'MDN/MG/01.06/26/0066', '-', 'LOGITECH'),
	(232, 39, 13, 'MDN/MG/01.06/26/0067', '-', 'LOGITECH'),
	(233, 39, 13, 'MDN/MG/01.06/26/0068', '-', 'LOGITECH'),
	(234, 39, 14, 'MDN/MG/01.07/26/0054', '-', 'LOGITECH'),
	(235, 39, 14, 'MDN/MG/01.07/26/0055', '-', 'LOGITECH'),
	(236, 39, 14, 'MDN/MG/01.07/26/0057', '-', 'LOGITECH'),
	(237, 39, 14, 'MDN/MG/01.07/26/0058', '-', 'LOGITECH'),
	(238, 39, 14, 'MDN/MG/01.07/26/0059', '-', 'LOGITECH'),
	(239, 39, 14, 'MDN/MG/01.07/26/0060', '-', 'LOGITECH'),
	(240, 39, 14, 'MDN/MG/01.07/26/0061', '-', 'LOGITECH'),
	(241, 39, 14, 'MDN/MG/01.07/26/0062', '-', 'LOGITECH'),
	(242, 39, 14, 'MDN/MG/01.07/26/0063', '-', 'LOGITECH'),
	(243, 39, 14, 'MDN/MG/01.07/26/0064', '-', 'LOGITECH'),
	(244, 39, 14, 'MDN/MG/01.07/26/0065', '-', 'LOGITECH'),
	(245, 39, 14, 'MDN/MG/01.07/26/0066', '-', 'LOGITECH'),
	(246, 39, 14, 'MDN/MG/01.07/26/0067', '-', 'LOGITECH'),
	(247, 39, 15, 'MDN/MG/01.08/26/0055', '-', 'LG'),
	(248, 39, 15, 'MDN/MG/01.08/26/0056', '-', 'LG'),
	(249, 39, 15, 'MDN/MG/01.08/26/0057', '-', 'LG'),
	(250, 39, 15, 'MDN/MG/01.08/26/0058', '-', 'LG'),
	(251, 39, 15, 'MDN/MG/01.08/26/0059', '-', 'LG'),
	(252, 39, 15, 'MDN/MG/01.08/26/0060', '-', 'LG'),
	(253, 39, 15, 'MDN/MG/01.08/26/0061', '-', 'LG'),
	(254, 39, 15, 'MDN/MG/01.08/26/0062', '-', 'LG'),
	(255, 39, 15, 'MDN/MG/01.08/26/0063', '-', 'LG'),
	(256, 39, 15, 'MDN/MG/01.08/26/0064', '-', 'LG'),
	(257, 39, 15, 'MDN/MG/01.08/26/0065', '-', 'LG'),
	(258, 39, 15, 'MDN/MG/01.08/26/0066', '-', 'LG'),
	(259, 39, 15, 'MDN/MG/01.08/26/0067', '-', 'LG'),
	(260, 39, 15, 'MDN/MG/01.08/26/0068', '-', 'LG'),
	(261, 39, 17, 'MDN/MG/01.10/26/0008', '-', 'TP LINK'),
	(262, 39, 20, 'MDN/MG/01.13/26/0003', '-', 'LOGITECH'),
	(263, 21, 2, 'MDN/MG/01.01/26/0018', '192.168.1.83', 'SIMBADDA'),
	(264, 21, 13, 'MDN/MG/01.06/26/0018', '-', 'LOGITECH'),
	(265, 21, 14, 'MDN/MG/01.07/26/0018', '-', 'LOGITECH'),
	(266, 21, 15, 'MDN/MG/01.08/26/0018', '-', 'LG'),
	(267, 40, 2, 'MDN/MG/01.01/26/0051', '192.168.1.164/24', 'SIMBADDA'),
	(268, 40, 2, 'MDN/MG/01.01/26/0052', '192.168.1.165/24', 'SIMBADDA'),
	(269, 40, 2, 'MDN/MG/01.01/26/0053', '192.168.1.166/24', 'SIMBADDA'),
	(270, 40, 11, 'MDN/MG/01.02/26/0026', '-', 'EPSON'),
	(271, 40, 3, 'MDN/MG/01.03/26/0014', '-', 'LENOVO'),
	(272, 40, 13, 'MDN/MG/01.06/26/0052', '-', 'LOGITECH'),
	(273, 40, 13, 'MDN/MG/01.06/26/0053', '-', 'LOGITECH'),
	(274, 40, 13, 'MDN/MG/01.06/26/0054', '-', 'LOGITECH'),
	(275, 40, 14, 'MDN/MG/01.07/26/0051', '-', 'LOGITECH'),
	(276, 40, 14, 'MDN/MG/01.07/26/0052', '-', 'LOGITECH'),
	(277, 40, 14, 'MDN/MG/01.07/26/0053', '-', 'LOGITECH'),
	(278, 40, 15, 'MDN/MG/01.08/26/0052', '-', 'LG'),
	(279, 40, 15, 'MDN/MG/01.08/26/0053', '-', 'LG'),
	(280, 40, 15, 'MDN/MG/01.08/26/0054', '-', 'LG'),
	(281, 40, 17, 'MDN/MG/01.10/26/0007', '-', 'TP LINK'),
	(282, 38, 2, 'MDN/MG/01.01/26/0050', '192.168.1.59/24', 'SIMBADDA'),
	(283, 32, 14, 'MDN/MG/01.07/26/0043', '-', 'LOGITECH'),
	(284, 32, 14, 'MDN/MG/01.07/26/0044', '-', 'LOGITECH'),
	(285, 38, 11, 'MDN/MG/01.02/26/0025', '-', 'EPSON'),
	(286, 32, 14, 'MDN/MG/01.07/26/0045', '-', 'LOGITECH'),
	(287, 32, 14, 'MDN/MG/01.07/26/0046', '-', 'LOGITECH'),
	(288, 32, 14, 'MDN/MG/01.07/26/0047', '-', 'LOGITECH'),
	(289, 38, 13, 'MDN/MG/01.06/26/0051', '-', 'LOGITECH'),
	(290, 32, 15, 'MDN/MG/01.08/26/0039', '-', 'LG'),
	(291, 38, 14, 'MDN/MG/01.07/26/0050', '-', 'LOGITECH'),
	(292, 38, 15, 'MDN/MG/01.08/26/0051', '-', 'LG'),
	(293, 32, 15, 'MDN/MG/01.08/26/0040', '-', 'LG'),
	(294, 32, 15, 'MDN/MG/01.08/26/0041', '-', 'LG'),
	(295, 32, 15, 'MDN/MG/01.08/26/0043', '-', 'LG'),
	(296, 38, 17, 'MDN/MG/01.10/26/0011', '-', 'TP LINK'),
	(297, 32, 15, 'MDN/MG/01.08/26/0044', '-', 'LG'),
	(298, 32, 15, 'MDN/MG/01.08/26/0045', '-', 'LG'),
	(299, 32, 15, 'MDN/MG/01.08/26/0046', '-', 'LG'),
	(300, 32, 15, 'MDN/MG/01.08/26/0047', '-', 'LG'),
	(301, 32, 15, 'MDN/MG/01.08/26/0048', '-', 'LG'),
	(302, 32, 17, 'MDN/MG/01.10/26/0004', '-', 'TP-LINK'),
	(303, 32, 17, 'MDN/MG/01.10/26/0005', '-', 'TP-LINK'),
	(304, 37, 2, 'MDN/MG/01.01/26/0048', '192.168.1.55/24', '-'),
	(305, 37, 2, 'MDN/MG/01.01/26/0049', '192.168.1.54/24', 'SIMBADA'),
	(306, 37, 11, 'MDN/MG/01.02/26/0024', '-', 'EPSON'),
	(307, 37, 13, 'MDN/MG/01.06/26/0049', '-', 'LOGITECH'),
	(308, 37, 13, 'MDN/MG/01.06/26/0050', '-', 'LOGITECH'),
	(309, 37, 14, 'MDN/MG/01.07/26/0048', '-', 'LOGITECH'),
	(310, 37, 14, 'MDN/MG/01.07/26/0049', '-', 'LOGITECH'),
	(311, 37, 15, 'MDN/MG/01.08/26/0049', '-', 'LG'),
	(312, 37, 15, 'MDN/MG/01.08/26/0050', '-', 'LG'),
	(313, 28, 2, 'MDN/MG/01.01/26/0028', '192.168.1.75/24', 'SIMBADA'),
	(314, 28, 13, 'MDN/MG/01.06/26/0028', '-', '-'),
	(315, 42, 2, 'MDN/MG/01.01/26/0070', '192.168.1.125/24', 'SIMBADDA'),
	(316, 42, 11, 'MDN/MG/01.02/26/0030', '-', 'EPSON'),
	(317, 28, 14, 'MDN/MG/01.07/26/0028', '-', 'LOGITECH'),
	(318, 42, 3, 'MDN/MG/01.03/26/0008', '-', 'HP'),
	(319, 28, 15, 'MDN/MG/01.08/26/0028', '-', 'LG'),
	(320, 42, 13, 'MDN/MG/01.06/26/0071', '-', 'LOGITECH'),
	(321, 28, 16, 'MDN/MG/01.09/26/0004', '-', 'LOGITECH'),
	(322, 42, 15, 'MDN/MG/01.08/26/0071', '-', 'LG'),
	(323, 28, 17, 'MDN/MG/01.10/26/0002', '-', 'TP LINK'),
	(324, 43, 2, 'MDN/MG/01.01/26/0071', '192.168.1.128/24', 'SIMBADDA'),
	(326, 43, 2, 'MDN/MG/01.01/26/0072', '169.254.179.198/16', 'SIMBADDA'),
	(327, 43, 11, 'MDN/MG/01.02/26/0031', '-', 'EPSON'),
	(328, 43, 3, 'MDN/MG/01.03/26/0009', '-', 'HP'),
	(329, 43, 12, 'MDN/MG/01.04/26/0006', '-', 'VIVO Y17S'),
	(330, 43, 13, 'MDN/MG/01.06/26/0072', '-', 'LOGITECH'),
	(331, 43, 13, 'MDN/MG/01.06/26/0073', '-', 'LOGITECH'),
	(332, 43, 14, 'MDN/MG/01.07/26/0071', '-', 'LOGITECH'),
	(333, 43, 14, 'MDN/MG/01.07/26/0072', '-', 'LOGITECH'),
	(334, 43, 15, 'MDN/MG/01.08/26/0072', '-', 'LG'),
	(335, 43, 15, 'MDN/MG/01.08/26/0073', '-', 'LG'),
	(336, 43, 17, 'MDN/MG/01.10/26/0010', '-', 'TP-LINK'),
	(337, 30, 2, 'MDN/MG/01.01/26/0030', '192.168.1.30/24', 'SIMBADA'),
	(338, 30, 3, 'MDN/MG/01.03/26/0005', '-', 'HP'),
	(339, 30, 12, 'MDN/MG/01.04/26/0002', '-', 'OPPO'),
	(340, 30, 13, 'MDN/MG/01.06/26/0030', '-', 'LOGITECH'),
	(341, 30, 14, 'MDN/MG/01.07/26/0030', '-', 'LOGITECH'),
	(342, 30, 15, 'MDN/MG/01.08/26/0030', '-', 'LG'),
	(343, 35, 2, 'MDN/MG/01.01/26/0032', '192.168.1.51/24', 'SIMBADA'),
	(344, 35, 11, 'MDN/MG/01.02/26/0016', '-', 'EPSON'),
	(345, 35, 3, 'MDN/MG/01.03/26/0007', '-', 'HP'),
	(346, 35, 12, 'MDN/MG/01.04/26/0004', '-', 'REALME'),
	(347, 35, 23, 'MDN/MG/01.05/26/0006', '-', 'SAMSUNG 55"'),
	(348, 35, 13, 'MDN/MG/01.06/26/0033', '-', 'LOGITECH'),
	(349, 35, 14, 'MDN/MG/01.07/26/0032', '-', 'LOGITECH'),
	(350, 35, 15, 'MDN/MG/01.08/26/0033', '-', 'LG'),
	(351, 31, 2, 'MDN/MG/01.01/26/0031', '192.168.1.34/24', 'SIMBADA'),
	(352, 31, 3, 'MDN/MG/01.03/26/0006', '-', 'HP'),
	(353, 31, 12, 'MDN/MG/01.04/26/0003', '-', 'VIVO'),
	(354, 31, 13, 'MDN/MG/01.06/26/0031', '-', 'LOGITECH'),
	(355, 31, 14, 'MDN/MG/01.07/26/0031', '-', 'LOGITECH'),
	(356, 31, 15, 'MDN/MG/01.08/26/0031', '-', 'LG'),
	(357, 29, 2, 'MDN/MG/01.01/26/0029', '192.168.1.32/24', 'SIMBADA'),
	(358, 29, 11, 'MDN/MG/01.02/26/0015', '-', 'EPSON'),
	(359, 29, 13, 'MDN/MG/01.06/26/0029', '-', 'LOGITECH'),
	(360, 29, 14, 'MDN/MG/01.07/26/0029', '-', 'LOGITECH'),
	(361, 29, 15, 'MDN/MG/01.08/26/0029', '-', 'LG'),
	(362, 26, 2, 'MDN/MG/01.01/26/0026', '192.168.1.93/24', 'ASUS'),
	(363, 26, 13, 'MDN/MG/01.06/26/0026', '-', 'LOGITECH'),
	(364, 26, 14, 'MDN/MG/01.07/26/0026', '-', 'LOGITECH'),
	(365, 26, 15, 'MDN/MG/01.08/26/0026', '-', 'LG'),
	(366, 26, 16, 'MDN/MG/01.09/26/0002', '-', 'LOGITECH'),
	(367, 25, 2, 'MDN/MG/01.01/26/0025', '192.168.1.91/24', 'SIMBADA'),
	(368, 25, 11, 'MDN/MG/01.02/26/0014', '-', 'EPSON'),
	(369, 25, 13, 'MDN/MG/01.06/26/0025', '-', 'LOGITECH'),
	(370, 25, 14, 'MDN/MG/01.07/26/0025', '-', 'LOGITECH'),
	(371, 25, 15, 'MDN/MG/01.08/26/0025', '-', 'LG'),
	(372, 25, 3, 'MDN/MG/01.03/26/0004', '-', 'ASUSVIVOBOOK'),
	(373, 46, 2, 'MDN/MG/01.01/26/0074', '192.168.1.169/24', 'SIMBADA'),
	(374, 46, 11, 'MDN/MG/01.02/26/0033', '-', 'EPSON'),
	(375, 46, 3, 'MDN/MG/01.03/26/0010', '-', 'HP'),
	(376, 46, 12, 'MDN/MG/01.04/26/0008', '-', 'VIVO'),
	(377, 46, 13, 'MDN/MG/01.06/26/0075', '-', 'LOGITECH'),
	(378, 46, 14, 'MDN/MG/01.07/26/0074', '-', 'LOGITECH'),
	(379, 46, 15, 'MDN/MG/01.08/26/0075', '-', 'LG'),
	(382, 10, 17, '123456111111111111111111', '09809809890', 'toyotaaaa'),
	(383, 16, 2, 'MDN/MG/01.01/26/0016', '192.168.1.71', 'ASUS'),
	(384, 16, 13, 'MDN/MG/01.06/26/0016', '-', 'LOGITECH'),
	(385, 16, 14, 'MDN/MG/01.07/26/0016', '-', 'LOGITECH'),
	(386, 16, 15, 'MDN/MG/01.08/26/0016', '-', 'LG'),
	(387, 5, 14, 'MDN/MG/01.12/26/0002', '-', 'LOGITECH'),
	(388, 5, 3, 'MDN/MG/01.07/26/0067', '-', 'HP');

-- Dumping structure for table sistem_pengaduan.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_pengaduan.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table sistem_pengaduan.ruangan
DROP TABLE IF EXISTS `ruangan`;
CREATE TABLE IF NOT EXISTS `ruangan` (
  `id_ruangan` int NOT NULL AUTO_INCREMENT,
  `nama_ruangan` varchar(100) DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_ruangan`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sistem_pengaduan.ruangan: ~43 rows (approximately)
DELETE FROM `ruangan`;
INSERT INTO `ruangan` (`id_ruangan`, `nama_ruangan`, `lokasi`) VALUES
	(5, 'ADMINISTRASI  IGD', 'Lt. 1'),
	(6, 'KASIR IGD', 'Lt. 1'),
	(7, 'IGD', 'Lt. 1'),
	(8, 'LABORATORIUM', 'Lt. 1'),
	(9, 'RADIOLOGI', 'Lt. 1'),
	(10, 'FARMASI', 'Lt. 1'),
	(11, 'NURSE STATION POLI', 'Lt. 1'),
	(12, 'POLI THT', 'Lt. 1'),
	(13, 'POLI MATA', 'Lt. 1'),
	(14, 'POLI GIGI', 'Lt. 1'),
	(15, 'POLI PARU', 'Lt. 1'),
	(16, 'POLI UROLOGI', 'Lt. 1'),
	(17, 'KLINIK KIA/UMUM', 'Lt. 1'),
	(18, 'POLI BEDAH SYARAF', 'Lt. 1'),
	(19, 'POLI ORTOPEDI', 'Lt. 1'),
	(20, 'POLI JANTUNG', 'Lt. 1'),
	(21, 'POLI SYARAF', 'Lt. 1'),
	(22, 'POLI  PENYAKIT DALAM', 'Lt. 1'),
	(23, 'POLI PENYAKIT DALAM 2', 'Lt. 1'),
	(24, 'POLI BEDAH', 'Lt. 1'),
	(25, 'POLI REHAB MEDIK', 'Lt. 1'),
	(26, 'POLI ANAK', 'Lt. 1'),
	(27, 'POLI OBGYN', 'Lt. 1'),
	(28, 'POLI JIWA', 'Lt. 1'),
	(29, 'UNIT BERSALIN', 'Lt. 1'),
	(30, 'UNIT PERINATAL', 'Lt. 1'),
	(31, 'UNIT MIJIL', 'Lt. 1'),
	(32, 'UNIT KLAIM', 'Lt. 1'),
	(33, 'ADMINISTRASI RS', 'Lt. 1'),
	(34, 'KASIR RS', 'Lt. 1'),
	(35, 'UNIT DIALISIS', 'Lt. 1'),
	(36, 'UNIT APM', 'Lt. 1'),
	(37, 'UNIT LOGISTIK', 'Lt. 1'),
	(38, 'UNIT GIZI', 'Lt. 1'),
	(39, 'UNIT TATA USAHA', 'Lt. 2'),
	(40, 'UNIT KEUANGAN', 'Lt. 2'),
	(41, 'UNIT ICU', 'Lt. 2'),
	(42, 'UNIT KIRANA', 'Lt. 2'),
	(43, 'UNIT LEMBAH MANAH', 'Lt. 2'),
	(44, 'UNIT OK', 'Lt. 2'),
	(45, 'UNIT OPS', 'Lt. 2'),
	(46, 'UNIT GEMAH RIPAH', 'Lt. 3'),
	(47, 'UNIT MAHESWARA', 'Lt. 3');

-- Dumping structure for table sistem_pengaduan.tindakan
DROP TABLE IF EXISTS `tindakan`;
CREATE TABLE IF NOT EXISTS `tindakan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pengaduan` int DEFAULT NULL,
  `id_ruangan` int DEFAULT NULL,
  `id_perangkat` int DEFAULT NULL,
  `kode_inventaris` varchar(50) DEFAULT NULL,
  `kategori_perangkat` varchar(50) DEFAULT NULL,
  `merek_perangkat` varchar(50) DEFAULT NULL,
  `teknisi` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` enum('Menunggu','Pending','Diproses','Diterima','Selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deskripsi_tindakan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table sistem_pengaduan.tindakan: ~3 rows (approximately)
DELETE FROM `tindakan`;
INSERT INTO `tindakan` (`id`, `id_pengaduan`, `id_ruangan`, `id_perangkat`, `kode_inventaris`, `kategori_perangkat`, `merek_perangkat`, `teknisi`, `status`, `deskripsi_tindakan`, `created_at`, `updated_at`) VALUES
	(1, 1, 15, 114, 'MDN/MG/01.01/26/0015', 'PC', 'ASUS', 'AZHIS', 'Diterima', NULL, '2026-06-23 05:23:15', '2026-06-23 05:23:15'),
	(2, 2, 15, 115, 'MDN/MG/01.06/26/0015', 'Mouse', 'LOGITECH', 'AZHIS', 'Diterima', NULL, '2026-06-23 05:23:01', '2026-06-23 05:23:01'),
	(3, 3, 9, 132, 'MDN/MG/01.02/26/0009', 'Printer / Scanner', 'EPSON', 'AZHIS', 'Selesai', 'ganti nozzle warna', '2026-06-23 08:06:23', '2026-06-23 08:06:23');

-- Dumping structure for table sistem_pengaduan.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_ruangan` int DEFAULT NULL,
  `password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_pengaduan.users: ~2 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `role`, `id_ruangan`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Aruljjp', 'admin', NULL, '$2y$12$4ufh8jrn9pMF8mTM4fVJJ.Q5.oO1kHyP1W/62qDThYlOyo.P7/n3u', NULL, '2026-05-02 06:03:05', '2026-05-02 06:03:05'),
	(3, 'Adi', 'admin', NULL, '$2y$12$TwMxIHnxYGbkUusG7wuIuezBmBloyopzAK/qbQzdN9TbVFuhniWWa', NULL, '2026-05-02 21:46:45', '2026-05-02 21:46:45'),
	(6, 'Bagus', 'pengadu', 16, '$2y$12$lWD7C7k5iIVrb3TgfG8VxO9DEmUb2OMOXwHjLc7fQDbSmAxKBda2G', NULL, '2026-05-09 20:22:52', '2026-06-18 19:37:37'),
	(7, 'Sumanto', 'pengadu', 20, '$2y$12$d.rmVyTlcL4RYipc/r8g7ur9VqDr6x6C6ppGmvDW6CLijrn9K4FDa', NULL, '2026-05-10 20:02:43', '2026-05-10 20:03:08'),
	(8, 'Reyhan', 'pengadu', 15, '$2y$12$yyGFgsQROGtKq9L4ioQwJO9UvhzeN/ttiVWxVl80f/xQEtuM9gO5K', NULL, '2026-05-15 01:04:24', '2026-05-15 01:04:24'),
	(9, 'Hendro', 'pengadu', 9, '$2y$12$X6867Lh2XJIXEyXrFKClY.dJ8xmKEr5.dfmYIlDq8vp2MXww1WMOS', NULL, '2026-06-22 22:58:10', '2026-06-22 22:58:10');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
