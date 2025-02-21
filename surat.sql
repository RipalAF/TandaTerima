-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2025 at 09:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `surat`
--

-- --------------------------------------------------------

--
-- Table structure for table `ditujukan`
--

CREATE TABLE `ditujukan` (
  `id` int NOT NULL,
  `sebutan` enum('Bapak','Ibu') NOT NULL,
  `nama_penerima` varchar(255) NOT NULL,
  `divisi` varchar(255) NOT NULL,
  `nama_perusahaan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ditujukan`
--

INSERT INTO `ditujukan` (`id`, `sebutan`, `nama_penerima`, `divisi`, `nama_perusahaan`) VALUES
(1, 'Bapak', 'Apri', 'Nautika', 'PT PELNI Persero'),
(2, 'Ibu', 'Aulia', 'Nautika Perintis', 'PT PELNI Persero');

-- --------------------------------------------------------

--
-- Table structure for table `penerima`
--

CREATE TABLE `penerima` (
  `id` int NOT NULL,
  `berupa` text NOT NULL,
  `ditunjukan` varchar(255) NOT NULL,
  `hari_tanggal` date NOT NULL,
  `penerima` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penerima`
--

INSERT INTO `penerima` (`id`, `berupa`, `ditunjukan`, `hari_tanggal`, `penerima`, `file_path`) VALUES
(1, '- Pemohonan Surat Invoice dan Pajak', '1', '2025-02-21', '  ', 'uploads/1740125854_Sertifikat Ripal.pdf'),
(2, '- Keinginan punya rumah di Bandung yang halaman nya banyak pohon-pohon dan tanaman\r\n- Keinginan ingin punya mobil classic amerika', '2', '2025-02-21', 'Bapak Apriansyah', 'uploads/1740126123_PSIKOTES - Ripal.docx'),
(9, '- ajsdaksdkdads\r<br>- jasdsdkdkj', 'Ridwan Aprianto', '2025-12-10', ' ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '12345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ditujukan`
--
ALTER TABLE `ditujukan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerima`
--
ALTER TABLE `penerima`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ditujukan`
--
ALTER TABLE `ditujukan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penerima`
--
ALTER TABLE `penerima`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
