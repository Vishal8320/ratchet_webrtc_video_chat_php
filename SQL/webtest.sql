-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2023 at 09:08 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profileImage` varchar(255) NOT NULL DEFAULT 'assets/images/defaultImage.png',
  `sessionID` varchar(255) NOT NULL,
  `connectionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `name`, `email`, `password`, `profileImage`, `sessionID`, `connectionID`) VALUES
(1, 'vishal', 'vishalbhardwaj', 'vishal.dh8320@gmail.com', '$2y$10$l7AU8aY0xgvGz4echz3B/eacxBZZjOxuQM0QAAJRTx35LpmvcnkTi', 'assets/images/defaultImage.png', 'aqmpno0pdu9v671vg1fq338n05', 299),
(2, 'shyam', 'shyam', 'user2register@gmail.com', '$2y$10$l7AU8aY0xgvGz4echz3B/eacxBZZjOxuQM0QAAJRTx35LpmvcnkTi', 'assets/images/defaultImage.png', 'rsc2ds7fon542mkun2am0u5boj', 302),
(3, 'user3', 'User3', 'user3register@gmail.com', '$2y$10$l7AU8aY0xgvGz4echz3B/eacxBZZjOxuQM0QAAJRTx35LpmvcnkTi', 'assets/images/defaultImage.png', '0', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
