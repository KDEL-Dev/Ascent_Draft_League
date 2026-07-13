-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 11, 2026 at 05:08 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ascent_league_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gamerTag` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `team_name` varchar(5) NOT NULL,
  `team_mascot_pkmn` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `gamerTag`, `created_at`, `team_name`, `team_mascot_pkmn`) VALUES
(1, 'truburd@gmail.com', '$2y$10$g6T5t5n9fMplpOKB6QwjUuZ0x9/v5EEnmWIAhaX6yzjmemrBVDJnK', 'DZN', '2026-02-18 14:09:10', 'DAYZN', 'Okidogi'),
(9, 'player2@gmail.com', '$2y$10$lTUpuVHpBcC4fY411mveVekeBSFYXStWKB/a5IJr3z1140ixCGLSa', NULL, '2026-04-07 14:41:49', 'MUNX', 'Munkidori'),
(10, 'player3@gmail.com', '$2y$10$csfT/9Y2KMIENMds3uTW5eJsbIM0Wx0pzR/OyUAs7fEX.tvzWFLVO', NULL, '2026-04-11 13:16:12', 'DB', 'Exploud'),
(11, 'K.liburd7@gmail.com', '$2y$10$uXiWQO3jb86/szeSGCulA.UXzHmb.GuN5X.5knRGh7dIJN0NoaKby', NULL, '2026-04-14 14:23:06', 'KRL', 'Krookodile'),
(12, 'sgspete@gmail.com', '$2y$10$L6yvHVBZyiZeu9Q4L8dAD.XoFUL4klvUGqNAsYF/82WBTC1tY6fWy', NULL, '2026-04-14 18:29:23', 'SGS', 'Volcarona'),
(13, 'webdev@humber.ca', '$2y$10$95.z5o3JlndtnDt3pL1Cs.06zlwL8DGFdHU2TWoJVBLiZtyAEH1f.', NULL, '2026-04-16 15:59:41', 'DEV', 'Galvantula'),
(14, 'sean.doyle@humber.ca', '$2y$10$4be0QswwlSAg2Uw.gR6NSOPsGYvIUNQRb98hBnDcRnzNO8Wrl2XK2', NULL, '2026-04-19 14:29:35', 'PROFD', 'Pikachu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `gamerTag` (`gamerTag`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
