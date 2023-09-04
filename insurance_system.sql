-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 03, 2023 at 01:58 PM
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
-- Database: `insurance_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`username`, `password`) VALUES
('admin1', '123'),
('Admin2', 'yahoo0123'),
('Admin22', '123');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `insurance_id` int(11) NOT NULL,
  `id` varchar(30) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `model` varchar(30) NOT NULL,
  `plate` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `plans` varchar(20) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `insurance_id`, `id`, `tel`, `email`, `address`, `model`, `plate`, `type`, `plans`, `expiry_date`, `status`) VALUES
('Jufri', 1, '040623100275', '0196111568', 'jufrifirdaus321@gmail.com', 'Setia Alam', 'Vios', 'ABC 123', 'Car', 'Yearly', '2023-11-17', 'Active'),
('asdfasdf', 28, 'q12341234', '12341234', '2314234@gmail.com', 'qwe2345', '2345', '2345wert', '2345r23', '23452345', '2023-09-03', 'Active'),
('&amp;&amp;&amp;&amp;&quot;hehe&quot;', 29, '**', '7', '7wh@gmail.com', 'w7yhxw', 'w7hd7', '7wgdw', 'w7wduj', 'w7hdu', '2023-10-07', 'Active'),
('a', 30, '123', '123', '12@gmail.com', 'ajsd', 'dad', 'qd', 'qd', 'eqd', '2023-09-22', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`insurance_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `insurance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
