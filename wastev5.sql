-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2024 at 04:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wastev5`
--

-- --------------------------------------------------------

--
-- Table structure for table `ewaste_data`
--

CREATE TABLE `ewaste_data` (
  `no` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ewaste_data`
--

INSERT INTO `ewaste_data` (`no`, `description`) VALUES
(1, 'Used telivision, Used cathode ray tube'),
(2, 'Used air-conditioner unit, Used electric cable'),
(3, 'Used computer, Used mobile phone'),
(4, 'Used refrigerator, Used motherboard'),
(5, 'Used washing machine, Used hard disk drive'),
(6, 'Used video recorder, Used printed circuit board'),
(7, 'Used telephone, Used lead frame'),
(8, 'Used photocopy machine, Used patterned wafer'),
(9, 'Used facsimile machine, Used ink cartridges'),
(10, 'Used microwave oven, Used or rejected or waste of integrated circuit'),
(11, 'Used radio, Used audio amplifier'),
(12, 'Used printer, Used electrical and electronic equipment/product');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Chan Jia Yi', 'Jia Yi', 'jiayi@gmail.com', '$2y$10$OajbdPdq819rjAN6ucm/NOgLyaH6XcuKcGbdfExsV9t9OkhHTcdsa', '2024-03-17 09:24:31'),
(2, 'Nikki Chan Ke Xin', 'Nikki', 'nikki@gmail.com', '$2y$10$rqG6ROTIfLrCHkGCTR3apOhsMIIlO9HdO/SsVn5JyxRBvjkum4ahG', '2024-03-17 09:33:20'),
(3, 'Jane Chan', 'Jane', 'jane@gmail.com', '$2y$10$57p/1mHYh4OrLt1MAIY.3.F398MSYVw9/.eWn3Agx3kEeYmBLg5h2', '2024-03-17 09:52:19'),
(4, 'Sally Wen', 'Sally', 'sally@gmail.com', '$2y$10$iNKKe6ZwjF97RaNIFORh1.iyB6IJuVlHvLQlGuzCEczFM8b8//SsW', '2024-04-25 14:10:41');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` int(11) NOT NULL,
  `session_token` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waste_classification`
--

CREATE TABLE `waste_classification` (
  `image_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `main_category` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_classification`
--

INSERT INTO `waste_classification` (`image_id`, `image_path`, `main_category`, `user_id`) VALUES
(1, 'C:\\xampp\\tmp\\phpB38B.tmp', 'metal', 1),
(2, 'C:\\xampp\\tmp\\phpE105.tmp', 'paper', 1),
(3, 'images/65f6b7825f7d0.jpg', 'cardboard', 1),
(4, 'images/65f79b69369b7.jpg', 'smartphone', 1),
(5, 'C:\\xampp\\tmp\\phpF36A.tmp', 'laptop', 1),
(6, 'C:\\xampp\\tmp\\phpB3DD.tmp', 'organic', 1),
(7, 'C:\\xampp\\tmp\\php2F39.tmp', 'organic', 1),
(8, 'C:\\xampp\\tmp\\php7E0C.tmp', 'laptop', 1),
(9, 'C:\\xampp\\tmp\\php4083.tmp', 'paper', 1),
(10, 'C:\\xampp\\tmp\\php1112.tmp', 'organic', 1),
(11, 'C:\\xampp\\tmp\\phpFACD.tmp', 'laptop', 1),
(12, 'C:\\xampp\\tmp\\phpBC2A.tmp', 'glass', 1),
(13, 'C:\\xampp\\tmp\\phpCA43.tmp', 'cardboard', 1),
(14, 'C:\\xampp\\tmp\\phpCA6A.tmp', 'paper', 1),
(15, 'C:\\xampp\\tmp\\php2EA9.tmp', 'glass', 1),
(16, 'C:\\xampp\\tmp\\php8EF6.tmp', 'trash', 1),
(17, 'C:\\xampp\\tmp\\php85BA.tmp', 'smartphone', 1),
(18, 'C:\\xampp\\tmp\\phpA5F1.tmp', 'organic', 1),
(19, 'C:\\xampp\\tmp\\phpAC6.tmp', 'metal', 1),
(20, 'C:\\xampp\\tmp\\php461B.tmp', 'glass', 1),
(21, 'C:\\xampp\\tmp\\php8596.tmp', 'laptop', 1),
(22, 'C:\\xampp\\tmp\\phpBFE1.tmp', 'trash', 1),
(23, 'C:\\xampp\\tmp\\phpA669.tmp', 'smartphone', 1),
(24, 'C:\\xampp\\tmp\\php29E2.tmp', 'battery', 1),
(25, 'C:\\xampp\\tmp\\phpDAC0.tmp', 'mouse', 1),
(26, 'C:\\xampp\\tmp\\php46E4.tmp', 'glass', 1),
(27, 'C:\\xampp\\tmp\\php2D2F.tmp', 'glass', 1),
(28, 'C:\\xampp\\tmp\\php1A4F.tmp', 'plastic', 1),
(29, 'C:\\xampp\\tmp\\phpD3D3.tmp', 'glass', 1),
(30, 'C:\\xampp\\tmp\\phpA795.tmp', 'cardboard', 4),
(31, 'C:\\xampp\\tmp\\php12D3.tmp', 'paper', 4),
(32, 'C:\\xampp\\tmp\\php4C43.tmp', 'glass', 4),
(33, 'C:\\xampp\\tmp\\php7875.tmp', 'battery', 4),
(34, 'C:\\xampp\\tmp\\phpAB7C.tmp', 'metal', 4),
(35, 'C:\\xampp\\tmp\\phpE24D.tmp', 'smartphone', 4),
(36, 'C:\\xampp\\tmp\\php306E.tmp', 'laptop', 4),
(37, 'C:\\xampp\\tmp\\php57FC.tmp', 'trash', 4),
(38, 'C:\\xampp\\tmp\\phpC86A.tmp', 'mouse', 4),
(39, 'C:\\xampp\\tmp\\phpFDA4.tmp', 'glass', 4),
(40, 'C:\\xampp\\tmp\\php49E1.tmp', 'organic', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ewaste_data`
--
ALTER TABLE `ewaste_data`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `waste_classification`
--
ALTER TABLE `waste_classification`
  ADD PRIMARY KEY (`image_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ewaste_data`
--
ALTER TABLE `ewaste_data`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waste_classification`
--
ALTER TABLE `waste_classification`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
