-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 31, 2024 at 01:37 AM
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
-- Database: `bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`) VALUES
(1, 'admin', 'admin', '123'),
(2, 'cok', 'cok', '$2y$10$QkGW3FauN0UKIm.wDKe98uokm0MZuC/XqZHTOegojX.z4tv1joQZ2');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `saldo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nama`, `username`, `password`, `nis`, `jurusan`, `saldo`) VALUES
(1, 'farel ', 'farel', '123', '111111', 'rpl', 750.00),
(3, 'adit', 'adit', '$2y$10$PBdWacSogtN3ecjWWpewdukZtXmqf5CTQZqVviHO8wfC0b.3S7Xde', '222', 'rpl', 0.00),
(4, 'afdan', 'afdan', '$2y$10$wtiBDv5db4c/s6guUvL2KudK5fhatBW/39oQ5porFOB8IA73k8OWa', '66262', 'tjk', 0.00),
(5, 'rey', 'reypan', '$2y$10$EYUBP3.9hm82Xou2Prl05.r2841hBNHlq1nq9JHFI1Ya9p36AQF12', '9765432', 'akl', 0.00),
(6, 'asu', 'asu', '$2y$10$C1IdfeoKXcUKLCqaCE8r2OHcvQ4hoTaqbTctAoPTpRmMy1TYZO/qq', '9876545678', 'akl', 0.00),
(7, 'opet', 'udin', '$2y$10$TMfb.LbejddqkGsoN4ktD.QYWwrIT0mesdhXnAO8d62U3GOrmRqky', '9876', 'pplg', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_riwayat`
--

CREATE TABLE `transaksi_riwayat` (
  `id` int NOT NULL,
  `nama_id` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `teler` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_riwayat`
--

INSERT INTO `transaksi_riwayat` (`id`, `nama_id`, `type`, `amount`, `timestamp`, `teler`) VALUES
(1, '1', 'Tambah Saldo', 100.00, '2024-08-30 14:50:32', 'pace'),
(2, '1', 'Tambah Saldo', 10.00, '2024-08-30 14:51:35', 'ss'),
(3, '1', 'Tambah Saldo', 100.00, '2024-08-30 16:41:51', 'opet'),
(4, '1', 'Tambah Saldo', 40.00, '2024-08-30 16:44:29', 'opet'),
(5, '7', 'Tambah Saldo', 100.00, '2024-08-30 16:47:40', 'pokemin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_riwayat`
--
ALTER TABLE `transaksi_riwayat`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaksi_riwayat`
--
ALTER TABLE `transaksi_riwayat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
