-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 04:44 AM
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
-- Database: `sms_lp`
--

-- --------------------------------------------------------

--
-- Table structure for table `lesson_plan`
--

CREATE TABLE `lesson_plan` (
  `LessonPlan_ID` int(11) NOT NULL,
  `CLUSTER` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `THEME` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `SUB_THEME` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `TOPIC` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `YEAR` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DURATION (minutes)` int(11) DEFAULT NULL,
  `INSTRUCTIONAL DESIGN` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `TECHNOLOGY INTEGRATION` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `APPROACH` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `METHOD` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PARENTAL INVOLVEMENT` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_plan`
--

INSERT INTO `lesson_plan` (`LessonPlan_ID`, `CLUSTER`, `THEME`, `SUB_THEME`, `TOPIC`, `YEAR`, `DURATION (minutes)`, `INSTRUCTIONAL DESIGN`, `TECHNOLOGY INTEGRATION`, `APPROACH`, `METHOD`, `PARENTAL INVOLVEMENT`) VALUES
(1, 'Kerohanian dan Nilai', 'Negara', 'Negara Aman', 'Qudwatun Hasanah', 'SATU', 120, 'Active Learning, Collaborative Learning', 'Adoption Level', '', 'Simulasi, Main Peranan', 'TIDAK'),
(2, 'Kerohanian dan Nilai', 'Negara', 'Negara Aman', 'Qudwatun Hasanah', 'SATU', 120, 'Active Learning, Collaborative Learning', 'Adoption Level', 'Kontekstual', 'Simulasi, Main Peranan', 'TIDAK'),
(3, 'Kerohanian dan Nilai', 'Negara', 'Negara Aman', 'Qudwatun Hasanah', 'SATU', 120, 'Active Learning, Collaborative Learning', 'Adoption Level', '', 'Simulasi, Main Peranan', 'TIDAK'),
(4, '', '', '', '', '', 0, '', '', '', '', ''),
(5, 's', 'd', 'd', 'w', 'ed', 123, 'Active Learning, Goal-Directed Learning', 'Adoption Level, Infusion Level', 'Inquiry, Project-Based', 'Demonstration, Experimentation', 'No'),
(6, 'ASA', 'asdasd', 'asdasd', 'adasda', 'TIGA', 0, 'Collaborative Learning', 'Adoption Level', 'Berasaskan Masalah, Kontekstual', 'Simulasi, Main Peranan, Sumbangsaran', 'TIDAK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lesson_plan`
--
ALTER TABLE `lesson_plan`
  ADD PRIMARY KEY (`LessonPlan_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lesson_plan`
--
ALTER TABLE `lesson_plan`
  MODIFY `LessonPlan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
