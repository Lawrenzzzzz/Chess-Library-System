-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2026 at 06:23 PM
-- Server version: 12.3.3-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chess_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `tag` varchar(60) NOT NULL DEFAULT '',
  `pinned` tinyint(1) NOT NULL DEFAULT 0,
  `author_name` varchar(255) NOT NULL DEFAULT '',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `body`, `tag`, `pinned`, `author_name`, `created_by`, `created_at`) VALUES
(6, 'Welcome', 'Good Day, Everyone!', 'Community Update', 1, 'Administrator', 9, '2026-10-12 15:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `section` varchar(100) NOT NULL DEFAULT 'General',
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL DEFAULT '',
  `level` varchar(100) NOT NULL DEFAULT '',
  `topic` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(20) NOT NULL DEFAULT '#7a2e2e',
  `width` int(11) NOT NULL DEFAULT 52,
  `height` int(11) NOT NULL DEFAULT 184,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file` varchar(255) DEFAULT NULL,
  `original_name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `category`, `section`, `title`, `author`, `level`, `topic`, `color`, `width`, `height`, `created_by`, `created_at`, `file`, `original_name`) VALUES
(25, 'fundamentals', 'Fundamentals', 'Open Games', 'N/A', 'Beginner', 'Principles and Opening for Open Games', '#27415e', 63, 177, 9, '2026-10-12 15:09:42', 'b486e6e786fd09c9371bc7e4463f4f37.pdf', 'open-games-chesskids-academy-649.pdf'),
(28, 'puzzles', 'Puzzles', 'Chess Puzzles Workbook', 'Todd Bardwick', 'Beginner', 'Lesson about Puzzles', '#27415e', 59, 219, 9, '2026-10-12 15:14:15', '64f8493f464ee78ea8c84f7ed8112596.pdf', 'chess-tactics-and-combinations-workbook-todd-bardwick-638.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `created_at`, `password`, `role`) VALUES
(2, 'Lawrence Rosario', 'lawrencerosario237@gmail.com', '2026-10-12 14:09:34', '$2y$10$tEUrmN5E9y.UyyeUBLrs4.mxrnXWXW1QyZFLOZWA9heXc/Tc8FbFu', 'member'),
(9, 'Administrator', 'chsslbrr@gmail.com', '2026-10-12 14:42:59', '$2y$10$9Hbxc4jCB9vDiNPBywAlseGQD4roG3qyfTk5ZfslRyF8vCuMOwf3i', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`);

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
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
