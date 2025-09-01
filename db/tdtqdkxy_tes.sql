-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 04, 2025 at 01:28 PM
-- Server version: 10.11.13-MariaDB-cll-lve-log
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tdtqdkxy_tes`
--

DELIMITER $$
--
-- Procedures
--
$$

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
(1, 1, 'LOGIN', 'Admin berhasil login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '2025-07-01 04:22:23'),
(2, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: 103.162.221.250', '103.162.221.250', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 20:51:15'),
(3, 1, 'AUTO_LOGOUT', 'Admin auto logout - menghapus semua remember me tokens', '103.162.221.250', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 20:52:14'),
(4, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: 103.162.221.250', '103.162.221.250', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-03 20:52:25'),
(5, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: 103.162.221.250', '103.162.221.250', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-07-03 21:10:38'),
(6, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: 103.162.221.250', '103.162.221.250', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', '2025-07-03 21:14:36'),
(7, 1, 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: 103.162.221.250', '103.162.221.250', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-04 01:01:45');

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

--
-- Dumping data for table `hasil_tes`
--

INSERT INTO `hasil_tes` (`id`, `peserta_id`, `total_jawaban_ya`, `total_skor`, `persentase`, `kategori`, `deskripsi_kategori`, `completed_at`) VALUES
(2, 1, 6, 6, 40.00, 'Sedang', 'Anda mulai mengalami gejala burnout. Perlu perhatian dan perbaikan dalam manajemen stres.', '2025-07-03 20:52:06');

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
-- Dumping data for table `jawaban_peserta`
--

INSERT INTO `jawaban_peserta` (`id`, `peserta_id`, `soal_id`, `jawaban`, `created_at`) VALUES
(1, 1, 1, 'YA', '2025-07-03 20:52:06'),
(2, 1, 2, 'TIDAK', '2025-07-03 20:52:06'),
(3, 1, 3, 'YA', '2025-07-03 20:52:06'),
(4, 1, 4, 'YA', '2025-07-03 20:52:06'),
(5, 1, 5, 'TIDAK', '2025-07-03 20:52:06'),
(6, 1, 6, 'YA', '2025-07-03 20:52:06'),
(7, 1, 7, 'TIDAK', '2025-07-03 20:52:06'),
(8, 1, 8, 'YA', '2025-07-03 20:52:06'),
(9, 1, 9, 'TIDAK', '2025-07-03 20:52:06'),
(10, 1, 10, 'TIDAK', '2025-07-03 20:52:06'),
(11, 1, 11, 'TIDAK', '2025-07-03 20:52:06'),
(12, 1, 12, 'YA', '2025-07-03 20:52:06'),
(13, 1, 13, 'TIDAK', '2025-07-03 20:52:06'),
(14, 1, 14, 'TIDAK', '2025-07-03 20:52:06'),
(15, 1, 15, 'TIDAK', '2025-07-03 20:52:06');

--
-- Triggers `jawaban_peserta`
--
DELIMITER $$
CREATE TRIGGER `after_jawaban_complete` AFTER INSERT ON `jawaban_peserta` FOR EACH ROW BEGIN
    DECLARE jawaban_count INT;
    
    -- Hitung jumlah jawaban untuk peserta ini
    SELECT COUNT(*) INTO jawaban_count 
    FROM jawaban_peserta 
    WHERE peserta_id = NEW.peserta_id;
    
    -- Jika sudah menjawab 15 soal, hitung hasil
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

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id`, `nama`, `jenis_kelamin`, `usia`, `pekerjaan`, `pendidikan`, `created_at`, `updated_at`) VALUES
(1, 'tes', 'Laki-laki', 15, 'tes', 'SMA', '2025-07-03 20:51:45', '2025-07-03 20:51:45');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_remember_tokens`
--
ALTER TABLE `admin_remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_tes`
--
ALTER TABLE `hasil_tes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jawaban_peserta`
--
ALTER TABLE `jawaban_peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `soal_psikotes`
--
ALTER TABLE `soal_psikotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

-- --------------------------------------------------------

--
-- Structure for view `view_hasil_lengkap`
--
DROP TABLE IF EXISTS `view_hasil_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cpses_tdyeet99qw`@`localhost` SQL SECURITY DEFINER VIEW `view_hasil_lengkap`  AS SELECT `p`.`id` AS `id`, `p`.`nama` AS `nama`, `p`.`jenis_kelamin` AS `jenis_kelamin`, `p`.`usia` AS `usia`, `p`.`pekerjaan` AS `pekerjaan`, `p`.`pendidikan` AS `pendidikan`, `h`.`total_jawaban_ya` AS `total_jawaban_ya`, `h`.`total_skor` AS `total_skor`, `h`.`persentase` AS `persentase`, `h`.`kategori` AS `kategori`, `h`.`deskripsi_kategori` AS `deskripsi_kategori`, `h`.`completed_at` AS `completed_at`, date_format(`h`.`completed_at`,'%d-%m-%Y') AS `tanggal_tes`, date_format(`h`.`completed_at`,'%d-%m-%Y %H:%i') AS `waktu_lengkap` FROM (`peserta` `p` join `hasil_tes` `h` on(`p`.`id` = `h`.`peserta_id`)) ORDER BY `h`.`completed_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_statistik_burnout`
--
DROP TABLE IF EXISTS `view_statistik_burnout`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cpses_tdyeet99qw`@`localhost` SQL SECURITY DEFINER VIEW `view_statistik_burnout`  AS SELECT count(0) AS `total_peserta`, sum(case when `h`.`kategori` = 'Rendah' then 1 else 0 end) AS `burnout_rendah`, sum(case when `h`.`kategori` = 'Sedang' then 1 else 0 end) AS `burnout_sedang`, sum(case when `h`.`kategori` = 'Tinggi' then 1 else 0 end) AS `burnout_tinggi`, sum(case when `h`.`kategori` = 'Sangat Tinggi' then 1 else 0 end) AS `burnout_sangat_tinggi`, round(avg(`h`.`persentase`),2) AS `rata_rata_persentase` FROM `hasil_tes` AS `h` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_remember_tokens`
--
ALTER TABLE `admin_remember_tokens`
  ADD CONSTRAINT `admin_remember_tokens_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

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
