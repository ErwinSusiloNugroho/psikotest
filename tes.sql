-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 04:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tes`
--

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `HitungHasilTes` (IN `p_peserta_id` INT)   BEGIN
    DECLARE v_total_ya INT DEFAULT 0;
    DECLARE v_persentase DECIMAL(5,2) DEFAULT 0.00;
    DECLARE v_kategori VARCHAR(20);
    DECLARE v_deskripsi TEXT;
    
    
    SELECT COUNT(*) INTO v_total_ya 
    FROM jawaban_peserta 
    WHERE peserta_id = p_peserta_id AND jawaban = 'YA';
    
    
    SET v_persentase = (v_total_ya / 15.0) * 100;
    
    
    IF v_persentase >= 0 AND v_persentase <= 26 THEN
        SET v_kategori = 'Rendah';
        SET v_deskripsi = 'Tidak ada burnout serius. Anda dalam kondisi yang relatif baik.';
    ELSEIF v_persentase >= 27 AND v_persentase <= 53 THEN
        SET v_kategori = 'Sedang';
        SET v_deskripsi = 'Perlu mulai menjaga diri & istirahat. Mulai perhatikan work-life balance.';
    ELSEIF v_persentase >= 54 AND v_persentase <= 79 THEN
        SET v_kategori = 'Tinggi';
        SET v_deskripsi = 'Perlu perhatian lebih & evaluasi aktivitas. Pertimbangkan untuk mengurangi beban.';
    ELSE
        SET v_kategori = 'Sangat Tinggi';
        SET v_deskripsi = 'Segera cari bantuan & lakukan pemulihan diri. Konsultasi dengan profesional dianjurkan.';
    END IF;
    
    
    INSERT INTO hasil_tes (peserta_id, total_jawaban_ya, total_skor, persentase, kategori, deskripsi_kategori)
    VALUES (p_peserta_id, v_total_ya, v_total_ya, v_persentase, v_kategori, v_deskripsi)
    ON DUPLICATE KEY UPDATE
        total_jawaban_ya = v_total_ya,
        total_skor = v_total_ya,
        persentase = v_persentase,
        kategori = v_kategori,
        deskripsi_kategori = v_deskripsi,
        completed_at = CURRENT_TIMESTAMP;
        
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `is_active`) VALUES
(1, 'konseling', 'prodiunggul2025', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'LOGIN', 'Admin berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 04:22:23'),
(2, 1, 'LOGIN', 'Admin berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:14:35'),
(3, 1, 'FORCE_LOGOUT', 'Session tidak valid dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:18:01'),
(4, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:18:13'),
(5, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:26:29'),
(6, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:26:58'),
(7, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:27:01'),
(8, 1, 'LOGIN_FAILED', 'Percobaan login dengan password salah dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:27:20'),
(9, 1, 'LOGIN', 'Admin berhasil login dengan remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:27:40'),
(10, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:27:43'),
(11, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:30:43'),
(12, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:30:47'),
(13, 1, 'FORCE_LOGOUT', 'Session tidak valid dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:32:29'),
(14, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:32:47'),
(15, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:32:58'),
(16, 1, 'LOGIN', 'Admin berhasil login dengan remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:33:15'),
(17, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:33:17'),
(18, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:35:30'),
(19, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 05:35:33'),
(20, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:01:02'),
(21, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:09:56'),
(22, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:10:25'),
(23, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:10:27'),
(24, 1, 'LOGIN', 'Admin berhasil login dengan remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:10:48'),
(25, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:10:50'),
(26, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:13:03'),
(27, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:13:05'),
(28, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-01 09:14:20'),
(29, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-02 10:55:31'),
(30, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-02 11:11:58'),
(31, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-02 11:12:19'),
(32, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-02 11:12:21'),
(33, 1, 'LOGIN_FAILED', 'Percobaan login dengan password salah dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-02 11:26:16'),
(34, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-02 11:26:28'),
(35, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-02 12:19:10'),
(36, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 02:45:07'),
(37, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 04:10:38'),
(38, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:11:46'),
(39, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:29:02'),
(40, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:29:24'),
(41, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:29:24'),
(42, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:29:46'),
(43, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:29:46'),
(44, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:30:08'),
(45, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:30:08'),
(46, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:30:23'),
(47, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:30:24'),
(48, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:31:15'),
(49, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:31:15'),
(50, 1, 'LOGIN', 'Admin berhasil login dengan remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:34:08'),
(51, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:34:08'),
(52, 1, 'LOGIN_FAILED', 'Percobaan login dengan password salah dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:36:18'),
(53, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:36:34'),
(54, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:36:34'),
(55, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:45:13'),
(56, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:47:57'),
(57, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:48:17'),
(58, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:48:17'),
(59, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:51:48'),
(60, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:51:48'),
(61, 1, 'LOGIN', 'Admin berhasil login normal dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:53:10'),
(62, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 10:53:10'),
(63, 1, 'LOGIN', 'Admin berhasil login dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:39:47'),
(64, 1, 'LOGOUT', 'Admin logout dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:39:47'),
(65, 1, 'LOGIN', 'Admin berhasil login dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:40:37'),
(66, 1, 'AUTO_LOGOUT', 'Admin auto logout - menghapus semua remember me tokens', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:54:21'),
(67, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:54:38'),
(68, 1, 'AUTO_LOGOUT', 'Admin auto logout - menghapus semua remember me tokens', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:54:53'),
(69, 1, 'LOGIN_FAILED', 'Percobaan login dengan password salah dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:56:39'),
(70, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 11:57:11'),
(71, 1, 'AUTO_LOGOUT', 'Admin auto logout - menghapus semua remember me tokens', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 12:41:17'),
(72, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 12:41:31'),
(73, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 13:31:38'),
(74, 1, 'AUTO_LOGOUT', 'Admin auto logout - menghapus semua remember me tokens', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 13:31:56'),
(75, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: ::1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 13:34:17');

-- --------------------------------------------------------

--
-- Table structure for table `admin_remember_tokens`
--

CREATE TABLE `admin_remember_tokens` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_tes`
--

CREATE TABLE `hasil_tes` (
  `id` int(11) NOT NULL,
  `peserta_id` int(11) NOT NULL,
  `total_jawaban_ya` int(11) NOT NULL DEFAULT 0,
  `total_skor` int(11) NOT NULL DEFAULT 0,
  `persentase` decimal(5,2) NOT NULL DEFAULT 0.00,
  `kategori` enum('Rendah','Sedang','Tinggi','Sangat Tinggi') NOT NULL,
  `deskripsi_kategori` text DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_peserta`
--

CREATE TABLE `jawaban_peserta` (
  `id` int(11) NOT NULL,
  `peserta_id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL,
  `jawaban` enum('YA','TIDAK') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `jawaban_peserta`
--
DELIMITER $$
CREATE TRIGGER `after_jawaban_complete` AFTER INSERT ON `jawaban_peserta` FOR EACH ROW BEGIN
    DECLARE jawaban_count INT;
    
    
    SELECT COUNT(*) INTO jawaban_count 
    FROM jawaban_peserta 
    WHERE peserta_id = NEW.peserta_id;
    
    
    IF jawaban_count = 15 THEN
        CALL HitungHasilTes(NEW.peserta_id);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `usia` int(11) NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `pendidikan` enum('SD','SMP','SMA','D3','S1','S2','S3') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soal_psikotes`
--

CREATE TABLE `soal_psikotes` (
  `id` int(11) NOT NULL,
  `nomor_soal` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soal_psikotes`
--

INSERT INTO `soal_psikotes` (`id`, `nomor_soal`, `pertanyaan`, `created_at`) VALUES
(1, 1, 'Saya merasa kelelahan secara fisik setelah menjalani aktivitas harian.', '2025-06-29 16:12:23'),
(2, 2, 'Saya merasa tidak bersemangat untuk melakukan pekerjaan atau tugas saya.', '2025-06-29 16:12:23'),
(3, 3, 'Saya merasa tertekan ketika memikirkan pekerjaan atau tanggung jawab saya.', '2025-06-29 16:12:23'),
(4, 4, 'Saya merasa kehilangan minat terhadap aktivitas yang dulu saya sukai.', '2025-06-29 16:12:23'),
(5, 5, 'Saya mengalami kesulitan untuk fokus dan berkonsentrasi.', '2025-06-29 16:12:23'),
(6, 6, 'Saya merasa cemas ketika hendak memulai hari.', '2025-06-29 16:12:23'),
(7, 7, 'Saya merasa emosi saya mudah meledak akibat tekanan pekerjaan.', '2025-06-29 16:12:23'),
(8, 8, 'Saya sering merasa ingin menyendiri dan menjauh dari orang lain.', '2025-06-29 16:12:23'),
(9, 9, 'Saya merasa tidak memiliki cukup waktu untuk diri sendiri.', '2025-06-29 16:12:23'),
(10, 10, 'Saya merasa tidak mampu mengontrol beban kerja atau tanggung jawab saya.', '2025-06-29 16:12:23'),
(11, 11, 'Saya merasa lelah walaupun sudah cukup tidur.', '2025-06-29 16:12:23'),
(12, 12, 'Saya merasa terus-menerus dikejar waktu atau deadline.', '2025-06-29 16:12:23'),
(13, 13, 'Saya merasa tidak puas dengan hasil kerja saya sendiri.', '2025-06-29 16:12:23'),
(14, 14, 'Saya merasa bosan dan jenuh dengan rutinitas sehari-hari.', '2025-06-29 16:12:23'),
(15, 15, 'Saya merasa semakin sulit untuk memulai aktivitas baru.', '2025-06-29 16:12:23');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_hasil_lengkap`
-- (See below for the actual view)
--
CREATE TABLE `view_hasil_lengkap` (
`id` int(11)
,`nama` varchar(100)
,`jenis_kelamin` enum('Laki-laki','Perempuan')
,`usia` int(11)
,`pekerjaan` varchar(100)
,`pendidikan` enum('SD','SMP','SMA','D3','S1','S2','S3')
,`total_jawaban_ya` int(11)
,`total_skor` int(11)
,`persentase` decimal(5,2)
,`kategori` enum('Rendah','Sedang','Tinggi','Sangat Tinggi')
,`deskripsi_kategori` text
,`completed_at` timestamp
,`tanggal_tes` varchar(10)
,`waktu_lengkap` varchar(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_statistik_burnout`
-- (See below for the actual view)
--
CREATE TABLE `view_statistik_burnout` (
`total_peserta` bigint(21)
,`burnout_rendah` decimal(22,0)
,`burnout_sedang` decimal(22,0)
,`burnout_tinggi` decimal(22,0)
,`burnout_sangat_tinggi` decimal(22,0)
,`rata_rata_persentase` decimal(6,2)
);

-- --------------------------------------------------------

--
-- Structure for view `view_hasil_lengkap`
--
DROP TABLE IF EXISTS `view_hasil_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_hasil_lengkap`  AS SELECT `p`.`id` AS `id`, `p`.`nama` AS `nama`, `p`.`jenis_kelamin` AS `jenis_kelamin`, `p`.`usia` AS `usia`, `p`.`pekerjaan` AS `pekerjaan`, `p`.`pendidikan` AS `pendidikan`, `h`.`total_jawaban_ya` AS `total_jawaban_ya`, `h`.`total_skor` AS `total_skor`, `h`.`persentase` AS `persentase`, `h`.`kategori` AS `kategori`, `h`.`deskripsi_kategori` AS `deskripsi_kategori`, `h`.`completed_at` AS `completed_at`, date_format(`h`.`completed_at`,'%d-%m-%Y') AS `tanggal_tes`, date_format(`h`.`completed_at`,'%d-%m-%Y %H:%i') AS `waktu_lengkap` FROM (`peserta` `p` join `hasil_tes` `h` on(`p`.`id` = `h`.`peserta_id`)) ORDER BY `h`.`completed_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_statistik_burnout`
--
DROP TABLE IF EXISTS `view_statistik_burnout`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_statistik_burnout`  AS SELECT count(0) AS `total_peserta`, sum(case when `h`.`kategori` = 'Rendah' then 1 else 0 end) AS `burnout_rendah`, sum(case when `h`.`kategori` = 'Sedang' then 1 else 0 end) AS `burnout_sedang`, sum(case when `h`.`kategori` = 'Tinggi' then 1 else 0 end) AS `burnout_tinggi`, sum(case when `h`.`kategori` = 'Sangat Tinggi' then 1 else 0 end) AS `burnout_sangat_tinggi`, round(avg(`h`.`persentase`),2) AS `rata_rata_persentase` FROM `hasil_tes` AS `h` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_admin_username` (`username`),
  ADD KEY `idx_admin_active` (`is_active`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_logs_admin_id` (`admin_id`),
  ADD KEY `idx_admin_logs_action` (`action`);

--
-- Indexes for table `admin_remember_tokens`
--
ALTER TABLE `admin_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `hasil_tes`
--
ALTER TABLE `hasil_tes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_peserta_hasil` (`peserta_id`),
  ADD KEY `idx_hasil_kategori` (`kategori`),
  ADD KEY `idx_hasil_completed` (`completed_at`);

--
-- Indexes for table `jawaban_peserta`
--
ALTER TABLE `jawaban_peserta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_peserta_soal` (`peserta_id`,`soal_id`),
  ADD KEY `soal_id` (`soal_id`),
  ADD KEY `idx_jawaban_peserta_id` (`peserta_id`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_peserta_nama` (`nama`),
  ADD KEY `idx_peserta_jenis_kelamin` (`jenis_kelamin`);

--
-- Indexes for table `soal_psikotes`
--
ALTER TABLE `soal_psikotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_soal` (`nomor_soal`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `admin_remember_tokens`
--
ALTER TABLE `admin_remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_tes`
--
ALTER TABLE `hasil_tes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawaban_peserta`
--
ALTER TABLE `jawaban_peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `soal_psikotes`
--
ALTER TABLE `soal_psikotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hasil_tes`
--
ALTER TABLE `hasil_tes`
  ADD CONSTRAINT `hasil_tes_ibfk_1` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jawaban_peserta`
--
ALTER TABLE `jawaban_peserta`
  ADD CONSTRAINT `jawaban_peserta_ibfk_1` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_peserta_ibfk_2` FOREIGN KEY (`soal_id`) REFERENCES `soal_psikotes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
