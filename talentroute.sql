-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 08:08 PM
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
-- Database: `talentroute`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `apply_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `resume_file` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `status` enum('pending','reviewing','interview','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `total_leave_allowed` int(11) NOT NULL DEFAULT 5,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `company_id`, `apply_date`, `start_date`, `end_date`, `resume_file`, `company_name`, `status`, `remarks`, `total_leave_allowed`, `created`, `modified`) VALUES
(1, 1, 2, '2026-07-14', '2026-08-01', '2027-01-01', 'resume_user_15_20260714170549_4c862c82.pdf', 'Pixel Creative Agency', 'approved', 'WELCOME', 5, '2026-07-14 17:06:28', '2026-07-14 17:07:18'),
(2, 2, 3, '2026-07-14', '2026-10-01', '2027-08-01', 'resume_application_2_3_20260714172330_be34b7.pdf', 'YG X', 'pending', 'HELLO, NICE TO MEET YOU', 5, '2026-07-14 17:23:30', '2026-07-14 17:23:30'),
(3, 2, 1, '2026-07-14', '2026-10-01', '2027-08-01', 'resume_application_2_3_20260714172330_be34b7.pdf', 'Nexus Tech Sdn Bhd', 'rejected', 'SORRY FULL', 5, '2026-07-14 17:24:45', '2026-07-14 17:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `registration_number` varchar(50) NOT NULL,
  `industry` varchar(100) NOT NULL DEFAULT 'Other',
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `user_id`, `company_name`, `registration_number`, `industry`, `address_line1`, `address_line2`, `postcode`, `city`, `state`, `contact_person`, `phone_number`) VALUES
(1, 3, 'Nexus Tech Sdn Bhd', '202601012345', 'Other', 'No 1, Jalan Teknologi', NULL, '50000', 'Kuala Lumpur', 'WPKL', NULL, NULL),
(2, 4, 'Pixel Creative Agency', '202601054321', 'Other', 'No 2, Jalan Kreatif', 'BATU 12', '40000', 'Shah Alam', 'Selangor', 'MR. AA', '0123654789'),
(3, 16, 'YG X', '200200801245', 'Cloud Computing', 'JALAN CINTA', 'SUNGAI CINTA', '10250', 'CINTA', 'Johor', 'MR. YIBO', '01124268545');

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `leave_type` enum('annual','medical','emergency','personal','family','other') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `total_days` int(11) NOT NULL DEFAULT 1,
  `mc_doc_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `matrix_number` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `semester` int(11) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `annual_leave_balance` int(11) DEFAULT 14,
  `medical_leave_balance` int(11) DEFAULT 14,
  `resume` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `matrix_number`, `name`, `faculty`, `course`, `semester`, `phone_number`, `address`, `city`, `postcode`, `state`, `qr_code_path`, `resume_path`, `annual_leave_balance`, `medical_leave_balance`, `resume`) VALUES
(1, 15, '202612345612', 'NURLIN', 'Fakulti Kejuruteraan', 'Kejuruteraan Kimia', 8, '0123456789', 'RESAK', 'SHAH ALAM', '05100', 'Selangor', NULL, 'resume_user_15_20260714170549_4c862c82.pdf', 14, 14, 'resume_user_15_20260714170549_4c862c82.pdf'),
(2, 17, '202612345614', 'WANG HEYU', 'Fakulti Kejuruteraan', 'Kejuruteraan Mekanikal', 5, '01125369845', 'JALAN KASIH SAYANG', 'SHAH ALAM', '05100', 'Wilayah Persekutuan', NULL, 'resume_application_2_3_20260714172330_be34b7.pdf', 14, 14, 'resume_application_2_3_20260714172330_be34b7.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','company') NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created`, `modified`) VALUES
(1, 'admin@talentroute.com', 'admin123456', 'admin', '2026-07-12 18:04:48', '2026-07-12 18:04:48'),
(3, 'hr@nexustech.com', 'password123', 'company', '2026-07-13 05:04:49', '2026-07-13 05:04:49'),
(4, 'career@pixelcreative.com', 'password123', 'company', '2026-07-13 05:04:49', '2026-07-14 17:07:55'),
(15, 'lyngojes@gmail.com', '123456789', 'student', NULL, NULL),
(16, 'ygx@gmail.com', '$2y$10$uA8x15XIlwzL131YE2CUnOfs8g0BzOHCm8m86mM2CbeGfTSyV9Iyq', 'company', '2026-07-14 17:10:51', '2026-07-14 17:10:51'),
(17, 'heyu@gamil.com', '$2y$10$Yznx9/jRJgrxPEEavfEipu2D80msTndAY4EhEKeM2jbsewaXRMX8q', 'student', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matrix_number` (`matrix_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `internships_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `internships_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
