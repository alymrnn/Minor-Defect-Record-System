-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 10:57 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minor_defect_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_defect_category`
--

CREATE TABLE `m_defect_category` (
  `id` int(10) NOT NULL,
  `defect_code_dc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_category_dc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_defect_category`
--

INSERT INTO `m_defect_category` (`id`, `defect_code_dc`, `defect_category_dc`, `date_updated`) VALUES
(1, 'AA', 'Exposed wire/junction', '2024-06-07 10:31:20'),
(2, 'BB', 'Missing marking', '2024-06-07 10:31:20'),
(3, 'CC', 'Clamp Defect', '2024-06-07 10:31:20'),
(4, 'A', 'Insufficient taping', '2024-06-07 10:31:20'),
(5, 'B', 'Insufficient taping (with dimension requirement)', '2024-06-07 10:31:20'),
(6, 'C', 'Missing Tape', '2024-06-07 10:31:20'),
(7, 'D', 'Option tape', '2024-06-07 10:31:20'),
(8, 'E', 'Taping defect', '2024-06-07 10:31:20'),
(9, 'F', 'Damage Parts', '2024-06-07 10:31:20'),
(10, 'G', 'La Terminal defect', '2024-06-07 10:31:20'),
(11, 'H', 'Wrong view', '2024-06-07 10:31:20'),
(12, 'I', 'Foreign material', '2024-06-07 10:31:20');

-- --------------------------------------------------------

--
-- Table structure for table `m_defect_details`
--

CREATE TABLE `m_defect_details` (
  `id` int(10) NOT NULL,
  `defect_code_dd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_code_value_dd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_sub_code_dd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_details_dd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_defect_details`
--

INSERT INTO `m_defect_details` (`id`, `defect_code_dd`, `defect_code_value_dd`, `defect_sub_code_dd`, `defect_details_dd`, `date_updated`) VALUES
(1, 'AA', 'Exposed wire/junction', 'AA1', 'Junction', '2024-06-13 07:24:37'),
(2, 'AA', 'Exposed wire/junction', 'AA2', 'Branch', '2024-06-13 07:24:37'),
(3, 'BB', 'Missing marking', 'BB1', 'Aignment jig', '2024-06-13 07:24:37'),
(4, 'BB', 'Missing marking', 'BB2', 'Connector retainer', '2024-06-13 07:24:37'),
(5, 'BB', 'Missing marking', 'BB3', 'Clamp/sponge', '2024-06-13 07:24:37'),
(6, 'BB', 'Missing marking', 'BB4', 'VS', '2024-06-13 07:24:37'),
(7, 'BB', 'Missing marking', 'BB5', 'COH', '2024-06-13 07:24:37'),
(8, 'BB', 'Missing marking', 'BB6', 'PR', '2024-06-13 07:24:37'),
(9, 'BB', 'Missing marking', 'BB7', 'CAP', '2024-06-13 07:24:37'),
(10, 'BB', 'Missing marking', 'BB8', 'Grommet', '2024-06-13 07:24:37'),
(11, 'BB', 'Missing marking', 'BB9', 'JC', '2024-06-13 07:24:37'),
(12, 'CC', 'Clamp Defect', 'CC1', 'Loose clamp', '2024-06-13 07:24:37'),
(13, 'CC', 'Clamp Defect', 'CC2', 'Long clamp tail', '2024-06-13 07:24:37'),
(14, 'CC', 'Clamp Defect', 'CC3', 'Short band tail', '2024-06-13 07:24:37'),
(15, 'CC', 'Clamp Defect', 'CC4', 'No good cut of tail/slant cut of tail', '2024-06-13 07:24:37'),
(16, 'CC', 'Clamp Defect', 'CC5', 'Half locked clamp /open lance of clamp', '2024-06-13 07:24:37'),
(17, 'A', 'Insufficient taping', 'A1', 'Junction', '2024-06-13 07:24:37'),
(18, 'A', 'Insufficient taping', 'A2', 'Combine', '2024-06-13 07:24:37'),
(19, 'A', 'Insufficient taping', 'A3', 'Branch', '2024-06-13 07:24:37'),
(20, 'A', 'Insufficient taping', 'A4', 'End of component', '2024-06-13 07:24:37'),
(21, 'B', 'Insufficient taping (with dimension requirement)', 'B1', 'Junction', '2024-06-13 07:24:37'),
(22, 'B', 'Insufficient taping (with dimension requirement)', 'B2', 'Combine', '2024-06-13 07:24:37'),
(23, 'B', 'Insufficient taping (with dimension requirement)', 'B3', 'Branch', '2024-06-13 07:24:37'),
(24, 'B', 'Insufficient taping (with dimension requirement)', 'B4', 'End of component', '2024-06-13 07:24:37'),
(25, 'C', 'Missing Tape', 'C1', 'Peel off tape', '2024-06-13 07:24:37'),
(26, 'C', 'Missing Tape', 'C2', 'Damage tape', '2024-06-13 07:24:37'),
(27, 'C', 'Missing Tape', 'C3', 'Missing spot tape', '2024-06-13 07:24:37'),
(28, 'C', 'Missing Tape', 'C4', 'Missing combined taping of LA terminal', '2024-06-13 07:24:37'),
(29, 'C', 'Missing Tape', 'C5', 'Missing Cross-taping', '2024-06-13 07:24:37'),
(30, 'C', 'Missing Tape', 'C6', 'Missing 1 side taping on offset clamp (For 2-sided clamp only)', '2024-06-13 07:24:37'),
(31, 'C', 'Missing Tape', 'C7', 'Missing fixing on protector', '2024-06-13 07:24:37'),
(32, 'C', 'Missing Tape', 'C8', 'Missing close and open taping', '2024-06-13 07:24:37'),
(33, 'D', 'Option Tape', 'D1', 'Missing Option tape', '2024-06-13 07:24:37'),
(34, 'D', 'Option Tape', 'D2', 'Damage Option tape', '2024-06-13 07:24:37'),
(35, 'E', 'Taping Defect', 'E1', 'Wrong taping method (B type to A-type taping only)', '2024-06-13 07:24:37'),
(36, 'E', 'Taping Defect', 'E2', 'No good taping condition (slant end taping)', '2024-06-13 07:24:37'),
(37, 'F', 'Damage Parts', 'F1', 'Damage Soft tape', '2024-06-13 07:24:37'),
(38, 'F', 'Damage Parts', 'F2', 'Damage and scratch cover on RFB-BYXO and RFB-B84MAO', '2024-06-13 07:24:37'),
(39, 'G', 'La Terminal Defect', 'G1', 'Detached combined on LA terminal', '2024-06-13 07:24:37'),
(40, 'G', 'La Terminal Defect', 'G2', 'Mis-aligned position of LA terminal (add tape only)', '2024-06-13 07:24:37'),
(41, 'H', 'Wrong View', 'H1', 'Wrong view of  connector (with option)', '2024-06-13 07:24:37'),
(42, 'I', 'Foreign Material', 'I1', 'Bandcut', '2024-06-13 07:24:37'),
(43, 'I', 'Foreign Material', 'I2', 'Dust', '2024-06-13 07:24:37'),
(44, 'I', 'Foreign Material', 'I3', 'Fiber', '2024-06-13 07:24:37'),
(45, 'I', 'Foreign Material', 'I4', 'Insulation', '2024-06-13 07:24:37'),
(46, 'I', 'Foreign Material', 'I5', 'Gomusen', '2024-06-13 07:24:37'),
(47, 'I', 'Foreign Material', 'I6', 'Paper', '2024-06-13 07:24:37'),
(48, 'I', 'Foreign Material', 'I7', 'Tape', '2024-06-13 07:24:37'),
(49, 'I', 'Foreign Material', 'I8', 'Tsumesen', '2024-06-13 07:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `t_minor_defect_f`
--

CREATE TABLE `t_minor_defect_f` (
  `id` int(11) NOT NULL,
  `defect_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_detected` date DEFAULT NULL,
  `car_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_no` int(10) DEFAULT NULL,
  `process` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_d` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shift` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lot_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defect_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequence_no` int(10) DEFAULT NULL,
  `connector_no` int(10) DEFAULT NULL,
  `repaired_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_defect_category`
--
ALTER TABLE `m_defect_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_defect_details`
--
ALTER TABLE `m_defect_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_minor_defect_f`
--
ALTER TABLE `t_minor_defect_f`
  ADD PRIMARY KEY (`id`),
  ADD KEY `defect_id` (`defect_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_defect_category`
--
ALTER TABLE `m_defect_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `m_defect_details`
--
ALTER TABLE `m_defect_details`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `t_minor_defect_f`
--
ALTER TABLE `t_minor_defect_f`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
