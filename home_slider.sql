-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 08:18 AM
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
-- Database: `isaber`
--

-- --------------------------------------------------------

--
-- Table structure for table `home_slider`
--

CREATE TABLE `home_slider` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_slider`
--

INSERT INTO `home_slider` (`id`, `title`, `subtitle`, `description`, `date_added`, `added_by`, `date_modified`, `modified_by`, `image`) VALUES
(1, 'Gentle Care for Pain-Free Smiles', 'Redefining Dental Care With A Personal Touch', 'At Medicax Clinic, we’re dedicated providing exceptional dental care that puts your smile first, with an team skilled professional.', '2025-07-18 05:48:02', NULL, '2025-07-18 05:48:02', NULL, 'uploads/images014210c93877a07140c3309d7709555e.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `home_slider`
--
ALTER TABLE `home_slider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_date_added` (`date_added`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `home_slider`
--
ALTER TABLE `home_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
