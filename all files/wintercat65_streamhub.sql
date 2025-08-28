-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 26, 2024 at 05:02 PM
-- Server version: 10.6.19-MariaDB-cll-lve
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wintercat65_streamhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$5kqDvVaVqSJkxe.juWB5AeNUnf61NrGzabCWCAL5w3EeGPDCBPvHu', '2024-11-26 20:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `contactmessages`
--

CREATE TABLE `contactmessages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_submitted` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactmessages`
--

INSERT INTO `contactmessages` (`id`, `name`, `email`, `subject`, `message`, `date_submitted`) VALUES
(1, 'Rashed Islam', 'golomo3276@gitated.com', 'when will be new video?', 'this is a demo data', '2024-11-26 16:29:10'),
(3, 'Md Aminul', 'amilabi515b@gmail.com', 'Demo 1', 'Hello how are you?', '2024-11-26 16:57:51'),
(4, 'WinTer', 'aminul@gmail.com', '123456', '123456', '2024-11-26 16:58:09');

-- --------------------------------------------------------

--
-- Table structure for table `passwordreset`
--

CREATE TABLE `passwordreset` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subscription_level` enum('basic','standard','premium') NOT NULL,
  `payment_method` enum('bank','bkash','paypal') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `subscription_level`, `payment_method`, `created_at`, `payment_details`) VALUES
(2, 'basic', 'basic@gmail.com', '$2y$10$OHPGXU8.ybxALRu8Sjmycu9cQk5zS06Q4QCiLfOYyLBcrvYQdhBI.', 'Basic Account', '0142911', 'basic', 'bkash', '2024-11-26 20:42:11', NULL),
(3, 'standard', 'standard@gmail.com', '$2y$10$yrEoF.FchrMiaGay62nDBOqqRkIiUU2tno7jaYx4lTCSugqu/IEda', 'Standard Account', '019110', 'standard', 'bank', '2024-11-26 20:42:53', NULL),
(4, 'premium', 'premium@gmail.com', '$2y$10$N4h.RqvcQxNAjArap9DUhO5vxmgzEzd53tWZcYLoydA/la3AbHFAK', 'Premium Master', '017105323', 'premium', 'paypal', '2024-11-26 20:44:07', '{\"orderID\":\"6P9595265X4889630\",\"payerID\":\"R6AF8YZ2PZXN8\",\"paymentID\":\"6P9595265X4889630\",\"status\":\"COMPLETED\",\"payerName\":\"John Doe\",\"payerEmail\":\"sb-9gp7n34269285@personal.example.com\"}'),
(5, 'labib1', 'aminulisl541ib@gmail.com', '$2y$10$WPHxjIOacQdyRvHpljI0UeBSwL6qXw.dHFj2xOifTZU1W4PDr85W6', 'Md Amin Labib', '014401', 'premium', 'paypal', '2024-11-26 21:33:06', '{\"orderID\":\"8DB49248KM468341L\",\"payerID\":\"R6AF8YZ2PZXN8\",\"paymentID\":\"8DB49248KM468341L\",\"status\":\"COMPLETED\",\"payerName\":\"Aminul Islam\",\"payerEmail\":\"sb-9gp7n34269285@personal.example.com\"}');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) NOT NULL,
  `subscription_level` enum('basic','standard','premium') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `description`, `file_path`, `thumbnail_path`, `subscription_level`, `created_at`, `last_updated`) VALUES
(2, 'Excel [Standard]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/1.mp4', 'uploads/videos/4a.jpg', 'standard', '2024-11-26 21:12:16', '2024-11-26 21:52:02'),
(3, 'Photography [Premium]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/2.mp4', 'uploads/videos/11a.jpg', 'premium', '2024-11-26 21:12:28', '2024-11-26 21:52:10'),
(4, 'Time [Standard]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/3.mp4', 'uploads/videos/10a.jpg', 'standard', '2024-11-26 21:14:01', '2024-11-26 21:55:46'),
(5, 'Writing [Basic]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/4.mp4', 'uploads/videos/9a.jpg', 'basic', '2024-11-26 21:14:04', '2024-11-26 21:55:46'),
(6, 'Drawing [Premium]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/5.mp4', 'uploads/videos/8a.jpg', 'premium', '2024-11-26 21:14:09', '2024-11-26 21:55:46'),
(7, 'Guitar [Standard]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/6.mp4', 'uploads/videos/6a.jpg', 'standard', '2024-11-26 21:14:13', '2024-11-26 21:55:46'),
(8, 'Gardening [Basic]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/7.mp4', 'uploads/videos/5a.jpg', 'basic', '2024-11-26 21:14:18', '2024-11-26 21:55:46'),
(9, 'Meditation [Premium]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/8.mp4', 'uploads/videos/3a.jpg', 'premium', '2024-11-26 21:14:22', '2024-11-26 21:55:46'),
(10, 'Yoga [Standard]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/9.mp4', 'uploads/videos/2a.jpg', 'standard', '2024-11-26 21:14:26', '2024-11-26 21:55:46'),
(11, 'Cooking [Basic]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/10.mp4', 'uploads/videos/1a.jpg', 'basic', '2024-11-26 21:14:30', '2024-11-26 21:55:46'),
(12, 'Speaking [Premium]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/11.mp4', '../uploads/videos/1.jpg', 'premium', '2024-11-26 21:19:02', '2024-11-26 21:55:46'),
(13, 'Crypto [Premium]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/12.mp4', '../uploads/videos/2.jpg', 'premium', '2024-11-26 21:19:04', '2024-11-26 21:55:46'),
(14, 'Design [Premium]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/13.mp4', '../uploads/videos/3.jpg', 'premium', '2024-11-26 21:19:06', '2024-11-26 21:55:46'),
(15, 'Baking [Premium]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/14.mp4', '../uploads/videos/4.jpg', 'premium', '2024-11-26 21:19:08', '2024-11-26 21:55:46'),
(16, 'First Aid [Basic]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/15.mp4', '../uploads/videos/5.jpg', 'basic', '2024-11-26 21:19:13', '2024-11-26 21:55:46'),
(17, 'Marketing [Basic]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/16.mp4', '../uploads/videos/6.jpg', 'basic', '2024-11-26 21:19:16', '2024-11-26 21:55:46'),
(18, 'Web [Basic]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/17.mp4', '../uploads/videos/7.jpg', 'basic', '2024-11-26 21:19:21', '2024-11-26 21:55:47'),
(19, 'Spanish [Basic]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/18.mp4', '../uploads/videos/8.jpg', 'basic', '2024-11-26 21:19:26', '2024-11-26 21:55:47'),
(20, 'Investing [Standard]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/19.mp4', '../uploads/videos/9.jpg', 'standard', '2024-11-26 21:19:32', '2024-11-26 21:55:47'),
(21, 'Editing [Standard]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/20.mp4', '../uploads/videos/10.jpg', 'standard', '2024-11-26 21:19:36', '2024-11-26 21:55:47'),
(22, 'Space [Standard]', 'Enhance your skills with our comprehensive video, designed to boost your knowledge. Ideal for both beginners and professionals seeking to expand their expertise effectively today.', '../uploads/videos/21.mp4', '../uploads/videos/11.jpg', 'standard', '2024-11-26 21:19:39', '2024-11-26 21:55:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `contactmessages`
--
ALTER TABLE `contactmessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passwordreset`
--
ALTER TABLE `passwordreset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contactmessages`
--
ALTER TABLE `contactmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `passwordreset`
--
ALTER TABLE `passwordreset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
