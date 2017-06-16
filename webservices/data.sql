-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2017 at 11:16 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `data`
--

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `id_subject`, `id_student`, `semester`) VALUES
(1, 1, 1, 8),
(2, 6, 1, 7),
(3, 3, 3, 8),
(4, 4, 2, 6),
(5, 6, 2, 7),
(6, 4, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `nim` varchar(10) NOT NULL,
  `name` varchar(160) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `nim`, `name`) VALUES
(1, 'l200134017', 'fathiyatur rohman'),
(2, 'l200144006', 'veda'),
(3, 'l200144024', 'desy');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `title` varchar(160) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `title`) VALUES
(1, 'TIF50831', '(SIE) Prak. Data Warehousing dan Data Mining'),
(2, 'TIF50433', 'Probabilitas dan Statistik'),
(3, 'TIF50332', 'Organisasi dan Arsitektur Komputer'),
(4, 'TIF50231', 'Praktikum Pemrograman Web Dinamis'),
(5, 'TIF50133', 'Pemrograman Web Dinamis'),
(6, 'TIF51333', '(RPLA) Computer Vision');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `krs_ibfk_1` (`id_student`),
  ADD KEY `krs_ibfk_2` (`id_subject`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
