-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 06:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gaia`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `Username` varchar(200) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `image_states` varchar(20) DEFAULT '0,0,0,0,0,0,0,0,0',
  `OTP` varchar(64) NOT NULL,
  `Avatar` varchar(255) DEFAULT 'avatars/avatar1.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Username` varchar(200) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Username`, `Email`, `Password`) VALUES
('Marwin', '21-04524@g.batstate-u.edu.ph', '123'),
('Kim', '21-02522@g.batstate-u.edu.ph', '123'),
('Kurt', '21-08108@g.batstate-u.edu.ph', '123'),
('Mickus', '21-08635@g.batstate-u.edu.ph', '123'),
('JB', '21-00510@g.batstate-u.edu.ph', '123'),
('Dr. Jeffrey', 'jeffrey.sarmiento@g.batstate-u.edu.ph', '123');

-- --------------------------------------------------------

--
-- Table structure for table `sars_projects`
--

CREATE TABLE `sars_projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon_image` varchar(255) DEFAULT NULL,
  `about_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sars_projects`
--

INSERT INTO `sars_projects` (`id`, `title`, `description`, `link`, `icon_image`, `about_image`, `created_at`) VALUES
(21, 'IslaSarVis', 'This project aims to address the challenges of disaster severity assessment by developing an innovative system that integrates remote sensing technologies, specifically Synthetic Aperture Radar (SAR) data from Sentinel-1 and optical imagery from Landsat 8. The primary goal is to provide a reliable and timely tool for evaluating the severity of natural disasters, supporting disaster response efforts through real-time, accurate assessments that inform decision-making and resource allocation. By combining SAR and Landsat 8 data, the project enhances the richness and reliability of disaster evaluations, offering a comprehensive understanding of disaster impacts. A web application developed as part of this project provides a user-friendly interface for stakeholders to visualize disaster severity through intuitive heatmaps. These color-coded overlays allow users to quickly assess severity levels across affected regions, facilitating more informed and effective responses.', 'https://ee-21-08319.projects.earthengine.app/view/islasarvis', '../main/images/uploads/icons/1.png', '../main/images/uploads/about_images/1.png', '2025-04-22 00:53:56'),
(22, 'Health Tree ', 'HEALTH-TREE is a web-based project designed to assess and monitor forest health across the Southeast Asia region. It integrates Synthetic Aperture Radar (SAR) and Geographic Information System (GIS) technologies to classify forest conditions as healthy, at risk, or restorable. The study identifies key parameters used to predict forest status in the region and analyzes their correlation to overall forest health by combining multiple factors. By leveraging heatmaps, machine learning models, and interactive maps, the project aims to support conservation efforts and provide actionable insights for stakeholders.', 'https://ee-21-08885.projects.earthengine.app/view/health-tree', '../main/images/uploads/icons/2.png', '../main/images/uploads/about_images/2.png', '2025-04-22 01:33:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sars_projects`
--
ALTER TABLE `sars_projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sars_projects`
--
ALTER TABLE `sars_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
