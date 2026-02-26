-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 26, 2026 at 05:48 AM
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
-- Table structure for table `active_users`
--

CREATE TABLE `active_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `draft_pick` int(11) DEFAULT NULL,
  `wins` int(11) DEFAULT '0',
  `losses` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `active_users`
--

INSERT INTO `active_users` (`id`, `user_id`, `draft_pick`, `wins`, `losses`) VALUES
(3, 1, 2, 0, 0),
(4, 2, 1, 0, 0),
(6, 3, 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `drafted_pkmn`
--

CREATE TABLE `drafted_pkmn` (
  `id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `active_user` int(11) NOT NULL,
  `showdown_pkmn` int(11) NOT NULL,
  `pick_number` int(11) NOT NULL,
  `drafted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `drafted_pkmn`
--

INSERT INTO `drafted_pkmn` (`id`, `season_id`, `active_user`, `showdown_pkmn`, `pick_number`, `drafted_at`) VALUES
(1, 1, 4, 2, 1, '2026-02-19 13:17:56');

-- --------------------------------------------------------

--
-- Table structure for table `draft_info`
--

CREATE TABLE `draft_info` (
  `id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `current_pick` int(11) DEFAULT '1',
  `total_picks` int(11) NOT NULL,
  `direction` enum('up','down') DEFAULT 'up',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `draft_info`
--

INSERT INTO `draft_info` (`id`, `season_id`, `current_pick`, `total_picks`, `direction`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 'up', '2026-02-23 21:47:29', '2026-02-23 21:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `leagues`
--

CREATE TABLE `leagues` (
  `league_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `legacy_stats_playoffs`
--

CREATE TABLE `legacy_stats_playoffs` (
  `id` int(2) DEFAULT NULL,
  `team_a` varchar(4) DEFAULT NULL,
  `team_b` varchar(4) DEFAULT NULL,
  `win` varchar(4) DEFAULT NULL,
  `loss` varchar(4) DEFAULT NULL,
  `season` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `legacy_stats_playoffs`
--

INSERT INTO `legacy_stats_playoffs` (`id`, `team_a`, `team_b`, `win`, `loss`, `season`) VALUES
(1, 'dzn', 'rx', 'rx', 'dzn', 2),
(2, 'sgs', 'orng', 'sgs', 'orng', 2),
(3, 'rx', 'sgs', 'sgs', 'rx', 2),
(4, 'rx', 'krl', 'rx', 'krl', 3),
(5, 'orng', 'js', 'orng', 'js', 3),
(6, 'rx', 'sgs', 'sgs', 'rx', 3),
(7, 'dzn', 'orng', 'orng', 'dzn', 3),
(8, 'sgs', 'orng', 'sgs', 'orng', 4),
(9, 'orng', 'aro', 'orng', 'aro', 4),
(10, 'rx', 'cbb', 'rx', 'cbb', 4),
(11, 'orng', 'dzn', 'dzn', 'orng', 4),
(12, 'sgs', 'rx', 'rx', 'sgs', 4),
(13, 'dzn', 'rx', 'rx', 'dzn', 4),
(14, 'sgs', 'aro', 'sgs', 'aro', 5),
(15, 'rx', 'krl', 'rx', 'krl', 5),
(16, 'sgs', 'dzn', 'sgs', 'dzn', 5),
(17, 'orng', 'rx', 'orng', 'rx', 5),
(18, 'sgs', 'orng', 'orng', 'sgs', 5),
(19, 'dzn', 'cbb', 'dzn', 'cbb', 6),
(20, 'krl', 'rx', 'rx', 'krl', 6),
(21, 'orng', 'js', 'orng', 'js', 6),
(22, 'sgs', 'aro', 'sgs', 'aro', 6),
(23, 'dzn', 'rx', 'rx', 'dzn', 6),
(24, 'orng', 'sgs', 'orng', 'sgs', 6),
(25, 'orng', 'rx', 'orng', 'rx', 6),
(26, 'dzn', 'rx', 'dzn', 'rx', 7),
(27, 'sgs', 'syg', 'sgs', 'syg', 7),
(28, 'krl', 'cbb', 'krl', 'cbb', 7),
(29, 'orng', 'js', 'js', 'orng', 7),
(30, 'dzn', 'sgs', 'sgs', 'dzn', 7),
(31, 'krl', 'js', 'krl', 'js', 7),
(32, 'sgs', 'krl', 'sgs', 'krl', 7),
(33, 'orng', 'mint', 'orng', 'mint', 8),
(34, 'dzn', 'krl', 'dzn', 'krl', 8),
(35, 'aro', 'mmm', 'aro', 'mmm', 8),
(36, 'syg', 'js', 'js', 'syg', 8),
(37, 'orng', 'dzn', 'orng', 'dzn', 8),
(38, 'aro', 'alt', 'aro', 'alt', 9),
(39, 'mint', 'js', 'js', 'mint', 9),
(40, 'dzn', 'mmm', 'dzn', 'mmm', 9),
(41, 'orng', 'don', 'orng', 'don', 9),
(42, 'alt', 'mint', 'mint', 'alt', 9),
(43, 'mmm', 'don', 'don', 'mmm', 9),
(44, 'aro', 'js', 'js', 'aro', 9),
(45, 'dzn', 'orng', 'orng', 'dzn', 9),
(46, 'dzn', 'mint', 'dzn', 'mint', 9),
(47, 'aro', 'don', 'aro', 'don', 9),
(48, 'js', 'orng', 'orng', 'js', 9),
(49, 'dzn', 'aro', 'dzn', 'aro', 9),
(50, 'dzn', 'js', 'dzn', 'js', 9),
(51, 'orng', 'dzn', 'orng', 'dzn', 9),
(52, 'wood', 'don', 'wood', 'don', 10),
(53, 'dzn', 'wood', 'dzn', 'wood', 10),
(54, 'js', 'cbb', 'js', 'cbb', 10),
(55, 'orng', 'syn', 'orng', 'syn', 10),
(56, 'sgs', 'aro', 'aro', 'sgs', 10),
(57, 'sgs', 'don', 'sgs', 'don', 10),
(58, 'syn', 'sgs', 'sgs', 'syn', 10),
(59, 'cbb', 'wood', 'wood', 'cbb', 10),
(60, 'dzn', 'js', 'js', 'dzn', 10),
(61, 'orng', 'aro', 'aro', 'orng', 10),
(62, 'dzn', 'sgs', 'dzn', 'sgs', 10),
(63, 'orng', 'wood', 'wood', 'orng', 10),
(64, 'js', 'aro', 'aro', 'js', 10),
(65, 'dzn', 'wood', 'wood', 'dzn', 10),
(66, 'js', 'wood', 'js', 'wood', 10),
(67, 'aro', 'js', 'js', 'aro', 10),
(68, 'js', 'aro', 'js', 'aro', 10),
(69, 'huh', 'syn', 'syn', 'huh', 11),
(70, 'sgs', 'syn', 'sgs', 'syn', 11),
(71, 'aro', 'js', 'js', 'aro', 11),
(72, 'dzn', 'cbb', 'cbb', 'dzn', 11),
(73, 'orng', 'don', 'orng', 'don', 11),
(74, 'don', 'syn', 'don', 'syn', 11),
(75, 'dzn', 'don', 'dzn', 'don', 11),
(76, 'aro', 'syn', 'aro', 'syn', 11),
(77, 'sgs', 'js', 'sgs', 'js', 11),
(78, 'cbb', 'orng', 'orng', 'cbb', 11),
(79, 'js', 'dzn', 'dzn', 'js', 11),
(80, 'cbb', 'aro', 'cbb', 'aro', 11),
(81, 'sgs', 'orng', 'orng', 'sgs', 11),
(82, 'dzn', 'cbb', 'dzn', 'cbb', 11),
(83, 'sgs', 'dzn', 'dzn', 'sgs', 11),
(84, 'orng', 'dzn', 'dzn', 'orng', 11),
(85, 'dzn', 'orng', 'dzn', 'orng', 11);

-- --------------------------------------------------------

--
-- Table structure for table `legacy_stats_seasons`
--

CREATE TABLE `legacy_stats_seasons` (
  `id` int(3) DEFAULT NULL,
  `team_a` varchar(4) DEFAULT NULL,
  `team_b` varchar(5) DEFAULT NULL,
  `win` varchar(4) DEFAULT NULL,
  `loss` varchar(4) DEFAULT NULL,
  `season` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `legacy_stats_seasons`
--

INSERT INTO `legacy_stats_seasons` (`id`, `team_a`, `team_b`, `win`, `loss`, `season`) VALUES
(1, 'krl', 'sgs', 'sgs', 'krl', '1'),
(2, 'dzn', 'rx', 'rx', 'dzn', '1'),
(3, 'orng', 'js', 'orng', 'js', '1'),
(4, 'krl', 'dzn', 'krl', 'dzn', '1'),
(5, 'sgs', 'orng', 'sgs', 'orng', '1'),
(6, 'rx', 'js', 'rx', 'js', '1'),
(7, 'krl', 'rx', 'rx', 'krl', '1'),
(8, 'sgs', 'js', 'sgs', 'js', '1'),
(9, 'dzn', 'orng', 'dzn', 'orng', '1'),
(10, 'krl', 'orng', 'orng', 'krl', '1'),
(11, 'sgs', 'rx', 'sgs', 'rx', '1'),
(12, 'dzn', 'js', 'dzn', 'js', '1'),
(13, 'krl', 'js', 'krl', 'js', '1'),
(14, 'sgs', 'dzn', 'sgs', 'dzn', '1'),
(15, 'rx', 'orng', 'orng', 'rx', '1'),
(16, 'krl', 'sgs', 'sgs', 'krl', '1'),
(17, 'dzn', 'rx', 'rx', 'dzn', '1'),
(18, 'orng', 'js', 'orng', 'js', '1'),
(19, 'krl', 'dzn', 'dzn', 'krl', '1'),
(20, 'sgs', 'orng', 'orng', 'sgs', '1'),
(21, 'rx', 'js', 'rx', 'js', '1'),
(22, 'krl', 'rx', 'rx', 'krl', '1'),
(23, 'sgs', 'js', 'sgs', 'js', '1'),
(24, 'dzn', 'orng', 'orng', 'dzn', '1'),
(25, 'krl', 'orng', 'orng', 'krl', '1'),
(26, 'sgs', 'rx', 'rx', 'sgs', '1'),
(27, 'dzn', 'js', 'dzn', 'js', '1'),
(28, 'krl', 'js', 'krl', 'js', '1'),
(29, 'sgs', 'dzn', 'sgs', 'dzn', '1'),
(30, 'rx', 'orng', 'orng', 'rx', '1'),
(31, 'krl', 'drk', 'krl', 'drk', '2'),
(32, 'sgs', 'rx', 'sgs', 'rx', '2'),
(33, 'mthd', 'js', 'mthd', 'js', '2'),
(34, 'dzn', 'mint', 'dzn', 'mint', '2'),
(35, 'king', 'orng', 'orng', 'king', '2'),
(36, 'krl', 'rx', 'rx', 'krl', '2'),
(37, 'drk', 'orng', 'orng', 'drk', '2'),
(38, 'mthd', 'king', 'king', 'mthd', '2'),
(39, 'js', 'mint', 'js', 'mint', '2'),
(40, 'sgs', 'dzn', 'dzn', 'sgs', '2'),
(41, 'krl', 'king', 'king', 'krl', '2'),
(42, 'sgs', 'js', 'sgs', 'js', '2'),
(43, 'mthd', 'orng', 'orng', 'mthd', '2'),
(44, 'dzn', 'rx', 'dzn', 'rx', '2'),
(45, 'drk', 'mint', 'mint', 'drk', '2'),
(46, 'krl', 'mthd', 'krl', 'mthd', '2'),
(47, 'js', 'rx', 'rx', 'js', '2'),
(48, 'sgs', 'mint', 'sgs', 'mint', '2'),
(49, 'dzn', 'orng', 'orng', 'dzn', '2'),
(50, 'king', 'drk', 'king', 'drk', '2'),
(51, 'krl', 'dzn', 'dzn', 'krl', '2'),
(52, 'sgs', 'drk', 'sgs', 'drk', '2'),
(53, 'rx', 'mthd', 'mthd', 'rx', '2'),
(54, 'orng', 'js', 'orng', 'js', '2'),
(55, 'king', 'mint', 'king', 'mint', '2'),
(56, 'krl', 'js', 'js', 'krl', '2'),
(57, 'dzn', 'drk', 'dzn', 'drk', '2'),
(58, 'orng', 'mint', 'orng', 'mint', '2'),
(59, 'mthd', 'sgs', 'sgs', 'mthd', '2'),
(60, 'king', 'rx', 'rx', 'king', '2'),
(61, 'aro', 'mint', 'mint', 'aro', '2'),
(62, 'sgs', 'orng', 'orng', 'sgs', '2'),
(63, 'king', 'js', 'king', 'js', '2'),
(64, 'mthd', 'dzn', 'mthd', 'dzn', '2'),
(65, 'rx', 'drk', 'rx', 'drk', '2'),
(66, 'aro', 'sgs', 'sgs', 'aro', '2'),
(67, 'orng', 'rx', 'rx', 'orng', '2'),
(68, 'dzn', 'king', 'dzn', 'king', '2'),
(69, 'drk', 'js', 'js', 'drk', '2'),
(70, 'mthd', 'mint ', 'mint', 'mthd', '2'),
(71, 'aro', 'orng', 'orng', 'mint', '2'),
(72, 'mint', 'rx', 'mint', 'rx', '2'),
(73, 'dzn', 'js', 'dzn', 'js', '2'),
(74, 'sgs', 'king', 'king', 'sgs', '2'),
(75, 'drk', 'mthd', 'mthd', 'drk', '2'),
(76, 'aro', 'drk', 'aro', 'drk', '2'),
(77, 'sgs', 'rx', 'sgs', 'rx', '2'),
(78, 'mthd', 'js', 'js', 'mthd', '2'),
(79, 'orng', 'king', 'king', 'orng', '2'),
(80, 'mint', 'dzn', 'mint', 'dzn', '2'),
(81, 'aro', 'rx', 'rx', 'aro', '2'),
(82, 'mthd', 'king', 'mthd', 'king', '2'),
(83, 'orng', 'drk', 'orng', 'drk', '2'),
(84, 'js', 'mint', 'js', 'mint', '2'),
(85, 'sgs', 'dzn', 'dzn', 'sgs', '2'),
(86, 'aro', 'king', 'king', 'aro', '2'),
(87, 'mint', 'drk', 'mint', 'drk', '2'),
(88, 'orng', 'mthd', 'orng', 'mthd', '2'),
(89, 'rx', 'dzn', 'dzn', 'rx', '2'),
(90, 'sgs', 'js', 'sgs', 'js', '2'),
(91, 'aro', 'mthd', 'mthd', 'aro', '2'),
(92, 'orng', 'dzn', 'dzn', 'orng', '2'),
(93, 'rx', 'js', 'rx', 'js', '2'),
(94, 'sgs ', 'mint', 'sgs', 'mint', '2'),
(95, 'drk', 'king', 'king', 'drk', '2'),
(96, 'aro', 'dzn', 'dzn', 'aro', '2'),
(97, 'rx', 'mthd', 'mthd', 'rx', '2'),
(98, 'mint', 'king', 'mint', 'king', '2'),
(99, 'orng', 'js', 'orng', 'js', '2'),
(100, 'drk', 'sgs', 'sgs', 'drk', '2'),
(101, 'aro', 'js', 'js', 'aro', '2'),
(102, 'sgs', 'mthd', 'sgs', 'mthd', '2'),
(103, 'mint', 'orng', 'orng', 'mint', '2'),
(104, 'rx', 'king', 'rx', 'king', '2'),
(105, 'dzn', 'drk', 'dzn', 'drk', '2'),
(106, 'aro', 'mint', 'mint', 'aro', '2'),
(107, 'dzn', 'mthd', 'dzn', 'mthd', '2'),
(108, 'sgs', 'orng', 'sgs', 'orng', '2'),
(109, 'king', 'js', 'js', 'king', '2'),
(110, 'rx', 'drk', 'rx', 'drk', '2'),
(111, 'aro', 'sgs', 'sgs', 'aro', '2'),
(112, 'mint', 'mthd', 'mthd', 'mint', '2'),
(113, 'rx', 'orng', 'orng', 'rx', '2'),
(114, 'js', 'drk', 'js', 'drk', '2'),
(115, 'king', 'dzn', 'dzn', 'king', '2'),
(116, 'aro', 'orng', 'aro', 'orng', '2'),
(117, 'mthd', 'drk', 'mthd', 'drk', '2'),
(118, 'king', 'sgs', 'king', 'sgs', '2'),
(119, 'rx', 'mint', 'rx', 'mint', '2'),
(120, 'js', 'dzn', 'js', 'dzn', '2'),
(121, 'cbb', 'js', 'js', 'cbb', '3'),
(122, 'cbb', 'dzn', 'cbb', 'dzn', '3'),
(123, 'js', 'rx', 'rx', 'js', '3'),
(124, 'dzn', 'aro', 'dzn', 'aro', '3'),
(125, 'rx', 'king', 'rx', 'king', '3'),
(126, 'aro', 'c2c', 'aro', 'c2c', '3'),
(127, 'king', 'c2c', 'king', 'c2c', '3'),
(128, 'mthd', 'cbb', 'cbb', 'mthd', '3'),
(129, 'drk', 'c2c', 'c2c', 'drk', '3'),
(130, 'fs', 'king', 'king', 'fs', '3'),
(131, 'krl', 'aro', 'krl', 'aro', '3'),
(132, 'sgs', 'rx', 'rx', 'sgs', '3'),
(133, 'mint', 'dzn', 'mint', 'dzn', '3'),
(134, 'orng', 'js', 'orng', 'js', '3'),
(135, 'mthd', 'drk', 'mthd', 'drk', '3'),
(136, 'mthd', 'fs', 'mthd', 'fs', '3'),
(137, 'drk', 'krl', 'krl', 'drk', '3'),
(138, 'fs', 'sgs', 'sgs', 'fs', '3'),
(139, 'krl', 'mint', 'krl', 'mint', '3'),
(140, 'sgs', 'orng', 'sgs', 'orng', '3'),
(141, 'mint', 'orng', 'orng', 'mint', '3'),
(142, 'cbb', 'rx', 'rx', 'cbb', '3'),
(143, 'cbb', 'aro', 'aro', 'cbb', '3'),
(144, 'js', 'aro', 'js', 'aro', '3'),
(145, 'js', 'king', 'js', 'king', '3'),
(146, 'dzn', 'king', 'dzn', 'king', '3'),
(147, 'dzn', 'c2c', 'dzn', 'c2c', '3'),
(148, 'rx', 'c2c', 'c2c', 'rx', '3'),
(149, 'mthd', 'js', 'js', 'mthd', '3'),
(150, 'drk', 'cbb', 'cbb', 'drk', '3'),
(151, 'fs', 'c2c', 'c2c', 'fs', '3'),
(152, 'krl', 'king', 'king', 'krl', '3'),
(153, 'sgs', 'aro', 'sgs', 'aro', '3'),
(154, 'mint', 'rx', 'rx', 'mint', '3'),
(155, 'orng', 'dzn', 'orng', 'dzn', '3'),
(156, 'mthd', 'krl', 'mthd', 'krl', '3'),
(157, 'mthd', 'sgs', 'sgs', 'mthd', '3'),
(158, 'drk', 'sgs', 'sgs', 'drk', '3'),
(159, 'drk', 'mint', 'mint', 'drk', '3'),
(160, 'fs', 'mint', 'mint', 'fs', '3'),
(161, 'fs', 'orng', 'orng', 'fs', '3'),
(162, 'krl', 'orng', 'krl', 'orng', '3'),
(163, 'cbb', 'king', 'king', 'cbb', '3'),
(164, 'cbb', 'c2c', 'c2c', 'cbb', '3'),
(165, 'js', 'dzn', 'js', 'dzn', '3'),
(166, 'js', 'c2c', 'js', 'c2c', '3'),
(167, 'dzn', 'rx', 'dzn', 'rx', '3'),
(168, 'rx', 'aro', 'rx', 'aro', '3'),
(169, 'aro', 'king', 'king', 'aro', '3'),
(170, 'mthd', 'dzn', 'dzn', 'mthd', '3'),
(171, 'drk', 'js', 'js', 'drk', '3'),
(172, 'fs', 'cbb', 'cbb', 'fs', '3'),
(173, 'krl', 'c2c', 'krl', 'c2c', '3'),
(174, 'sgs', 'king', 'sgs', 'king', '3'),
(175, 'mint', 'aro', 'mint', 'aro', '3'),
(176, 'orng', 'rx', 'orng', 'rx', '3'),
(177, 'mthd', 'mint', 'mthd', 'mint', '3'),
(178, 'mthd', 'orng', 'mthd', 'orng', '3'),
(179, 'drk', 'fs', 'fs', 'drk', '3'),
(180, 'drk', 'orng', 'orng', 'drk', '3'),
(181, 'fs', 'krl', 'krl', 'fs', '3'),
(182, 'krl', 'sgs', 'krl', 'sgs', '3'),
(183, 'sgs', 'mint', 'sgs', 'mint', '3'),
(184, 'cbb', 'js', 'cbb', 'js', '3'),
(185, 'cbb', 'aro', 'cbb', 'aro', '3'),
(186, 'js', 'king', 'js', 'king', '3'),
(187, 'dzn', 'aro', 'dzn', 'aro', '3'),
(188, 'dzn', 'c2c', 'dzn', 'c2c', '3'),
(189, 'rx', 'king', 'rx', 'king', '3'),
(190, 'rx', 'c2c', 'rx', 'c2c', '3'),
(191, 'mthd', 'rx', 'rx', 'mthd', '3'),
(192, 'drk', 'dzn', 'dzn', 'drk', '3'),
(193, 'fs', 'js', 'js', 'fs', '3'),
(194, 'krl', 'cbb', 'krl', 'cbb', '3'),
(195, 'sgs', 'c2c', 'sgs', 'c2c', '3'),
(196, 'mint', 'king', 'mint', 'king', '3'),
(197, 'orng', 'aro', 'aro', 'orng', '3'),
(198, 'mthd', 'drk', 'mthd', 'drk', '3'),
(199, 'mthd', 'sgs', 'mthd', 'sgs', '3'),
(200, 'drk', 'mint', 'mint', 'drk', '3'),
(201, 'fs', 'sgs', 'sgs', 'fs', '3'),
(202, 'fs', 'orng', 'orng', 'fs', '3'),
(203, 'krl', 'mint', 'mint', 'krl', '3'),
(204, 'krl', 'orng', 'orng', 'krl', '3'),
(205, 'cbb', 'dzn', 'dzn', 'cbb', '3'),
(206, 'cbb', 'king', 'cbb', 'king', '3'),
(207, 'js', 'rx', 'rx', 'js', '3'),
(208, 'js', 'c2c', 'js', 'c2c', '3'),
(209, 'dzn', 'rx', 'dzn', 'rx', '3'),
(210, 'aro', 'king', 'aro', 'king', '3'),
(211, 'aro', 'c2c', 'c2c', 'aro', '3'),
(212, 'mthd', 'aro', 'mthd', 'aro', '3'),
(213, 'drk', 'rx', 'rx', 'drk', '3'),
(214, 'fs', 'dzn', 'dzn', 'fs', '3'),
(215, 'krl', 'js', 'js', 'krl', '3'),
(216, 'sgs', 'cbb', 'sgs', 'cbb', '3'),
(217, 'mint', 'c2c', 'mint', 'c2c', '3'),
(218, 'orng', 'king', 'orng', 'king', '3'),
(219, 'mthd', 'fs', 'mthd', 'fs', '3'),
(220, 'mthd', 'mint', 'mthd', 'mint', '3'),
(221, 'drk', 'krl', 'krl', 'drk', '3'),
(222, 'drk', 'orng', 'orng', 'drk', '3'),
(223, 'fs', 'krl', 'krl', 'fs', '3'),
(224, 'sgs', 'mint', 'sgs', 'mint', '3'),
(225, 'sgs', 'orng', 'sgs', 'orng', '3'),
(226, 'rx', 'cbb', 'rx', 'cbb', '3'),
(227, 'cbb', 'c2c', 'cbb', 'c2c', '3'),
(228, 'js', 'dzn', 'js', 'dzn', '3'),
(229, 'js', 'aro', 'aro', 'js', '3'),
(230, 'dzn', 'king', 'dzn', 'king', '3'),
(231, 'rx', 'aro', 'aro', 'rx', '3'),
(232, 'king', 'c2c', 'c2c', 'king', '3'),
(233, 'mthd', 'king', 'king', 'mthd', '3'),
(234, 'drk', 'aro', 'aro', 'drk', '3'),
(235, 'fs', 'rx', 'rx', 'fs', '3'),
(236, 'krl', 'dzn', 'dzn', 'krl', '3'),
(237, 'sgs', 'js', 'sgs', 'js', '3'),
(238, 'mint', 'cbb', 'mint', 'cbb', '3'),
(239, 'orng', 'c2c', 'orng', 'c2c', '3'),
(240, 'mthd', 'krl', 'krl', 'mthd', '3'),
(241, 'mthd', 'orng', 'orng', 'mthd', '3'),
(242, 'drk', 'fs', 'fs', 'drk', '3'),
(243, 'drk', 'sgs', 'sgs', 'drk', '3'),
(244, 'fs', 'mint', 'mint', 'fs', '3'),
(245, 'krl', 'sgs', 'sgs', 'krl', '3'),
(246, 'mint', 'orng', 'orng', 'mint', '3'),
(247, 'cbb', 'js', 'cbb', 'js', '3'),
(248, 'cbb', 'king', 'king', 'cbb', '3'),
(249, 'js', 'c2c', 'js', 'c2c', '3'),
(250, 'dzn', 'rx', 'dzn', 'rx', '3'),
(251, 'dzn', 'aro', 'dzn', 'aro', '3'),
(252, 'aro', 'c2c', 'aro', 'c2c', '3'),
(253, 'mthd', 'c2c', 'mthd', 'c2c', '3'),
(254, 'drk', 'king', 'king', 'drk', '3'),
(255, 'fs', 'aro', 'aro', 'fs', '3'),
(256, 'krl', 'rx', 'krl', 'rx', '3'),
(257, 'sgs', 'dzn', 'sgs', 'dzn', '3'),
(258, 'mint', 'js', 'js', 'mint', '3'),
(259, 'orng', 'cbb', 'orng', 'cbb', '3'),
(260, 'mthd', 'drk', 'mthd', 'drk', '3'),
(261, 'mthd', 'mint', 'mthd', 'mint', '3'),
(262, 'drk', 'orng', 'orng', 'drk', '3'),
(263, 'fs', 'krl', 'krl', 'fs', '3'),
(264, 'fs', 'sgs', 'sgs', 'fs', '3'),
(265, 'krl', 'mint', 'krl', 'mint', '3'),
(266, 'sgs', 'orng', 'sgs', 'orng', '3'),
(267, 'cbb', 'aro', 'aro', 'cbb', '3'),
(268, 'cbb', 'c2c', 'cbb', 'c2c', '3'),
(269, 'js', 'dzn', 'dzn', 'js', '3'),
(270, 'js', 'rx', 'rx', 'js', '3'),
(271, 'dzn', 'king', 'dzn', 'king', '3'),
(272, 'dzn', 'c2c', 'dzn', 'c2c', '3'),
(273, 'aro', 'king', 'king', 'aro', '3'),
(274, 'cbb', 'dzn', 'dzn', 'cbb', '3'),
(275, 'cbb', 'rx', 'rx', 'cbb', '3'),
(276, 'js', 'aro', 'js', 'aro', '3'),
(277, 'js', 'king', 'js', 'king', '3'),
(278, 'rx', 'aro', 'aro', 'rx', '3'),
(279, 'rx', 'c2c', 'rx', 'c2c', '3'),
(280, 'king', 'c2c', 'king', 'c2c', '3'),
(281, 'mthd', 'sgs', 'mthd', 'sgs', '3'),
(282, 'mthd', 'orng', 'orng', 'mthd', '3'),
(283, 'drk', 'fs', 'fs', 'drk', '3'),
(284, 'drk', 'krl', 'krl', 'drk', '3'),
(285, 'fs', 'mint', 'mint', 'fs', '3'),
(286, 'fs', 'orng', 'orng', 'fs', '3'),
(287, 'sgs', 'mint', 'sgs', 'mint', '3'),
(288, 'mthd', 'fs', 'mthd', 'fs', '3'),
(289, 'mthd', 'krl', 'krl', 'mthd', '3'),
(290, 'drk', 'sgs', 'sgs', 'drk', '3'),
(291, 'drk', 'mint', 'drk', 'mint', '3'),
(292, 'krl', 'sgs', 'krl', 'sgs', '3'),
(293, 'krl', 'orng', 'orng', 'krl', '3'),
(294, 'mint', 'orng', 'mint', 'orng', '3'),
(295, 'cbb', 'drk', 'cbb', 'drk', '4'),
(296, 'cbb', 'fs', 'cbb', 'fs', '4'),
(297, 'drk', 'dzn', 'dzn', 'drk', '4'),
(298, 'fs', 'rx', 'rx', 'fs', '4'),
(299, 'dzn', 'rx', 'dzn', 'rx', '4'),
(300, 'cbb', 'dzn', 'dzn', 'cbb', '4'),
(301, 'cbb', 'rx', 'rx', 'cbb', '4'),
(302, 'drk', 'fs', 'fs', 'drk', '4'),
(303, 'drk', 'rx', 'rx', 'drk', '4'),
(304, 'fs', 'dzn', 'dzn', 'fs', '4'),
(305, 'cbb', 'drk', 'drk', 'cbb', '4'),
(306, 'cbb', 'dzn', 'dzn', 'cbb', '4'),
(307, 'drk', 'fs', 'drk', 'fs', '4'),
(308, 'fs', 'rx', 'rx', 'fs', '4'),
(309, 'dzn', 'rx', 'rx', 'dzn', '4'),
(310, 'cbb', 'rx', 'rx', 'cbb', '4'),
(311, 'drk', 'dzn', 'dzn', 'drk', '4'),
(312, 'drk', 'rx', 'rx', 'drk', '4'),
(313, 'fs', 'dzn', 'fs', 'dzn', '4'),
(314, 'cbb', 'krl', 'cbb', 'krl', '4'),
(315, 'cbb', 'dzn', 'cbb', 'dzn', '4'),
(316, 'drk', 'dzn', 'dzn', 'drk', '4'),
(317, 'drk', 'rx', 'rx', 'drk', '4'),
(318, 'krl', 'rx', 'krl', 'rx', '4'),
(319, 'cbb', 'drk', 'cbb', 'drk', '4'),
(320, 'cbb', 'rx', 'cbb', 'rx', '4'),
(321, 'drk', 'krl', 'krl', 'drk', '4'),
(322, 'krl', 'dzn', 'dzn', 'krl', '4'),
(323, 'dzn', 'rx', 'dzn', 'rx', '4'),
(324, 'mthd', 'king', 'king', 'mthd', '4'),
(325, 'mthd', 'orng', 'orng', 'mthd', '4'),
(326, 'king', 'sgs', 'sgs', 'king', '4'),
(327, 'orng', 'aro', 'orng', 'aro', '4'),
(328, 'sgs', 'aro', 'sgs', 'aro', '4'),
(329, 'mthd', 'sgs', 'sgs', 'mthd', '4'),
(330, 'mthd', 'aro', 'aro', 'mthd', '4'),
(331, 'king', 'orng', 'king', 'orng', '4'),
(332, 'king', 'aro', 'aro', 'king', '4'),
(333, 'orng', 'sgs', 'sgs', 'orng', '4'),
(334, 'mthd', 'king', 'mthd', 'king', '4'),
(335, 'mthd', 'sgs', 'mthd', 'sgs', '4'),
(336, 'king', 'orng', 'orng', 'king', '4'),
(337, 'orng', 'aro', 'orng', 'aro', '4'),
(338, 'sgs', 'aro', 'sgs', 'aro', '4'),
(339, 'mthd', 'orng', 'orng', 'mthd', '4'),
(340, 'mthd', 'aro', 'aro', 'mthd', '4'),
(341, 'king', 'sgs', 'sgs', 'king', '4'),
(342, 'king', 'aro', 'aro', 'king', '4'),
(343, 'orng', 'sgs', 'sgs', 'orng', '4'),
(344, 'mthd', 'orng', 'orng', 'mthd', '4'),
(345, 'mthd', 'sgs', 'sgs', 'mthd', '4'),
(346, 'king', 'sgs', 'sgs', 'king', '4'),
(347, 'king', 'aro', 'aro', 'king', '4'),
(348, 'orng', 'aro', 'aro', 'orng', '4'),
(349, 'mthd', 'king', 'king', 'mthd', '4'),
(350, 'mthd', 'aro', 'aro', 'mthd', '4'),
(351, 'king', 'orng', 'orng', 'king', '4'),
(352, 'orng', 'sgs', 'sgs', 'orng', '4'),
(353, 'sgs', 'aro', 'aro', 'sgs', '4'),
(354, 'mthd', 'cbb', 'mthd', 'cbb', '4'),
(355, 'drk', 'aro', 'aro', 'drk', '4'),
(356, 'krl', 'sgs', 'sgs', 'krl', '4'),
(357, 'dzn', 'orng', 'dzn', 'orng', '4'),
(358, 'rx', 'king', 'rx', 'king', '4'),
(359, 'cbb', 'king', 'king', 'cbb', '4'),
(360, 'drk', 'mthd', 'mthd', 'drk', '4'),
(361, 'krl', 'aro', 'aro', 'krl', '4'),
(362, 'dzn', 'sgs', 'sgs', 'dzn', '4'),
(363, 'rx', 'orng', 'rx', 'orng', '4'),
(364, 'cbb', 'orng', 'orng', 'cbb', '4'),
(365, 'drk', 'king', 'king', 'drk', '4'),
(366, 'krl', 'mthd', 'mthd', 'krl', '4'),
(367, 'dzn', 'aro', 'aro', 'dzn', '4'),
(368, 'rx', 'sgs', 'sgs', 'rx', '4'),
(369, 'cbb', 'sgs', 'sgs', 'cbb', '4'),
(370, 'drk', 'orng', 'orng', 'drk', '4'),
(371, 'krl', 'king', 'krl', 'king', '4'),
(372, 'dzn', 'mthd', 'dzn', 'mthd', '4'),
(373, 'rx', 'aro', 'rx', 'aro', '4'),
(374, 'cbb', 'aro', 'cbb', 'aro', '4'),
(375, 'drk', 'sgs', 'sgs', 'drk', '4'),
(376, 'krl', 'orng', 'orng', 'krl', '4'),
(377, 'dzn', 'king', 'dzn', 'king', '4'),
(378, 'rx', 'mthd', 'rx', 'mthd', '4'),
(379, 'sgs', 'krl', 'sgs', 'krl', '5'),
(380, 'sgs', 'cbb', 'sgs', 'cbb', '5'),
(381, 'krl', 'dzn', 'dzn', 'krl', '5'),
(382, 'cbb', 'rx', 'rx', 'cbb', '5'),
(383, 'dzn', 'rx', 'dzn', 'rx', '5'),
(384, 'sgs', 'dzn', 'dzn', 'sgs', '5'),
(385, 'sgs', 'rx', 'rx', 'sgs', '5'),
(386, 'krl', 'cbb', 'krl', 'cbb', '5'),
(387, 'krl', 'rx', 'rx', 'krl', '5'),
(388, 'cbb', 'dzn', 'dzn', 'cbb', '5'),
(389, 'sgs', 'krl', 'sgs', 'krl', '5'),
(390, 'sgs', 'dzn', 'dzn', 'sgs', '5'),
(391, 'krl', 'cbb', 'krl', 'cbb', '5'),
(392, 'cbb', 'rx', 'cbb', 'rx', '5'),
(393, 'dzn', 'rx', 'rx', 'dzn', '5'),
(394, 'sgs', 'cbb', 'sgs', 'cbb', '5'),
(395, 'sgs', 'rx', 'rx', 'sgs', '5'),
(396, 'krl', 'dzn', 'dzn', 'krl', '5'),
(397, 'krl', 'rx', 'krl', 'rx', '5'),
(398, 'cbb', 'dzn', 'dzn', 'cbb', '5'),
(399, 'sgs', 'cbb', 'sgs', 'cbb', '5'),
(400, 'sgs', 'dzn', 'dzn', 'sgs', '5'),
(401, 'krl', 'dzn', 'dzn', 'krl', '5'),
(402, 'krl', 'rx', 'rx', 'krl', '5'),
(403, 'cbb', 'rx', 'rx', 'cbb', '5'),
(404, 'sgs', 'krl', 'krl', 'sgs', '5'),
(405, 'sgs', 'rx', 'sgs', 'rx', '5'),
(406, 'krl', 'cbb', 'krl', 'cbb', '5'),
(407, 'cbb', 'dzn', 'dzn', 'cbb', '5'),
(408, 'dzn', 'rx', 'rx', 'dzn', '5'),
(409, 'orng', 'js', 'orng', 'js', '5'),
(410, 'orng', 'king', 'king', 'orng', '5'),
(411, 'js', 'c2c', 'c2c', 'js', '5'),
(412, 'king', 'aro', 'king', 'aro', '5'),
(413, 'c2c', 'aro', 'c2c', 'aro', '5'),
(414, 'orng', 'c2c', 'orng', 'c2c', '5'),
(415, 'orng', 'aro', 'aro', 'orng', '5'),
(416, 'js', 'king', 'js', 'king', '5'),
(417, 'js', 'aro', 'js', 'aro', '5'),
(418, 'king', 'c2c', 'king', 'c2c', '5'),
(419, 'orng', 'js', 'orng', 'js', '5'),
(420, 'orng', 'c2c', 'orng', 'c2c', '5'),
(421, 'js', 'king', 'king', 'js', '5'),
(422, 'king', 'aro', 'aro', 'king', '5'),
(423, 'c2c', 'aro', 'aro', 'c2c', '5'),
(424, 'orng', 'king', 'orng', 'king', '5'),
(425, 'orng', 'aro', 'aro', 'orng', '5'),
(426, 'js', 'c2c', 'c2c', 'js', '5'),
(427, 'js ', 'aro', 'aro', 'js', '5'),
(428, 'king', 'c2c', 'king', 'c2c', '5'),
(429, 'orng', 'king', 'orng', 'king', '5'),
(430, 'orng', 'c2c', 'orng', 'c2c', '5'),
(431, 'js', 'c2c', 'js', 'c2c', '5'),
(432, 'js', 'aro', 'aro', 'js', '5'),
(433, 'king', 'aro', 'aro', 'king', '5'),
(434, 'orng', 'js', 'orng', 'js', '5'),
(435, 'orng', 'aro', 'orng', 'aro', '5'),
(436, 'king', 'c2c', 'c2c', 'king', '5'),
(437, 'c2c', 'aro', 'aro', 'c2c', '5'),
(438, 'sgs', 'orng', 'orng', 'sgs', '5'),
(439, 'krl', 'aro', 'krl', 'aro', '5'),
(440, 'cbb', 'c2c', 'cbb', 'c2c', '5'),
(441, 'dzn', 'king', 'dzn', 'king', '5'),
(442, 'rx', 'js', 'rx', 'js', '5'),
(443, 'sgs', 'js', 'sgs', 'js', '5'),
(444, 'krl', 'orng', 'orng', 'krl', '5'),
(445, 'cbb', 'aro', 'aro', 'cbb', '5'),
(446, 'dzn', 'c2c', 'dzn', 'c2c', '5'),
(447, 'rx', 'king', 'rx', 'king', '5'),
(448, 'sgs', 'king', 'sgs', 'king', '5'),
(449, 'krl', 'js', 'krl', 'js', '5'),
(450, 'cbb', 'orng', 'orng', 'cbb', '5'),
(451, 'dzn', 'aro', 'aro', 'dzn', '5'),
(452, 'rx', 'c2c', 'rx', 'c2c', '5'),
(453, 'sgs', 'c2c', 'c2c', 'sgs', '5'),
(454, 'krl', 'king', 'krl', 'king', '5'),
(455, 'cbb', 'js', 'js', 'cbb', '5'),
(456, 'dzn', 'orng', 'orng', 'dzn', '5'),
(457, 'rx', 'aro', 'aro', 'rx', '5'),
(458, 'sgs', 'aro', 'sgs', 'aro', '5'),
(459, 'krl', 'c2c', 'krl', 'c2c', '5'),
(460, 'cbb', 'king', 'king', 'cbb', '5'),
(461, 'dzn', 'js', 'dzn', 'js', '5'),
(462, 'rx', 'orng', 'orng', 'rx', '5'),
(463, 'cbb', 'dzn', 'dzn', 'cbb', '6'),
(464, 'cbb', 'mint', 'cbb', 'mint', '6'),
(465, 'dzn', 'rx', 'rx', 'dzn', '6'),
(466, 'sgs', 'mmm', 'sgs', 'mmm', '6'),
(467, 'mint', 'sgs', 'sgs', 'mint', '6'),
(468, 'rx', 'mmm', 'rx', 'mmm', '6'),
(469, 'cbb', 'rx', 'cbb', 'rx', '6'),
(470, 'cbb', 'sgs', 'sgs', 'cbb', '6'),
(471, 'dzn', 'sgs', 'dzn', 'sgs', '6'),
(472, 'dzn', 'mmm', 'mmm', 'dzn', '6'),
(473, 'mint', 'rx', 'rx', 'mint', '6'),
(474, 'mint', 'mmm', 'mint', 'mmm', '6'),
(475, 'cbb', 'dzn', 'cbb', 'dzn', '6'),
(476, 'cbb', 'mmm', 'mmm', 'cbb', '6'),
(477, 'dzn', 'mint', 'dzn', 'mint', '6'),
(478, 'mint', 'sgs', 'sgs', 'mint', '6'),
(479, 'rx', 'sgs', 'rx', 'sgs', '6'),
(480, 'rx', 'mmm', 'rx', 'mmm', '6'),
(481, 'cbb', 'mint', 'cbb', 'mint', '6'),
(482, 'cbb', 'sgs', 'sgs', 'cbb', '6'),
(483, 'dzn', 'rx', 'dzn', 'rx', '6'),
(484, 'dzn', 'mmm', 'dzn', 'mmm', '6'),
(485, 'mint', 'rx', 'rx', 'mint', '6'),
(486, 'sgs', 'mmm', 'sgs', 'mmm', '6'),
(487, 'cbb', 'rx', 'rx', 'cbb', '6'),
(488, 'cbb', 'mmm', 'cbb', 'mmm', '6'),
(489, 'dzn', 'mint', 'dzn', 'mint', '6'),
(490, 'dzn', 'sgs', 'dzn', 'sgs', '6'),
(491, 'mint', 'mmm', 'mint', 'mmm', '6'),
(492, 'rx', 'sgs', 'rx', 'sgs', '6'),
(493, 'cbb', 'dzn', 'dzn', 'cbb', '6'),
(494, 'cbb', 'sgs', 'sgs', 'cbb', '6'),
(495, 'dzn', 'mmm', 'dzn', 'mmm', '6'),
(496, 'mint', 'rx', 'mint', 'rx', '6'),
(497, 'mint', 'sgs', 'sgs', 'mint', '6'),
(498, 'rx', 'mmm', 'rx', 'mmm', '6'),
(499, 'cbb', 'mint', 'cbb', 'mint', '6'),
(500, 'cbb', 'rx', 'rx', 'cbb', '6'),
(501, 'cbb', 'mmm', 'cbb', 'mmm', '6'),
(502, 'dzn', 'mint', 'dzn', 'mint', '6'),
(503, 'dzn', 'rx', 'dzn', 'rx', '6'),
(504, 'dzn', 'sgs', 'sgs', 'dzn', '6'),
(505, 'mint', 'mmm', 'mint', 'mmm', '6'),
(506, 'rx', 'sgs', 'sgs', 'rx', '6'),
(507, 'sgs', 'mmm', 'sgs', 'mmm', '6'),
(508, 'krl', 'orng', 'orng', 'krl', '6'),
(509, 'krl', 'aro', 'krl', 'aro', '6'),
(510, 'orng', 'c2c', 'orng', 'c2c', '6'),
(511, 'js', 'syg', 'syg', 'js', '6'),
(512, 'aro', 'js', 'aro', 'js', '6'),
(513, 'c2c', 'syg', 'syg', 'c2c', '6'),
(514, 'krl', 'c2c', 'c2c', 'krl', '6'),
(515, 'krl', 'js', 'js', 'krl', '6'),
(516, 'orng', 'js', 'js', 'orng', '6'),
(517, 'orng', 'syg', 'orng', 'syg', '6'),
(518, 'aro', 'c2c', 'aro', 'c2c', '6'),
(519, 'aro', 'syg', 'aro', 'syg', '6'),
(520, 'krl', 'orng', 'orng', 'krl', '6'),
(521, 'krl', 'syg', 'krl', 'syg', '6'),
(522, 'orng', 'aro', 'orng', 'aro', '6'),
(523, 'aro', 'js', 'js', 'aro', '6'),
(524, 'c2c', 'js', 'c2c', 'js', '6'),
(525, 'c2c', 'syg', 'c2c', 'syg', '6'),
(526, 'krl', 'aro', 'aro', 'krl', '6'),
(527, 'krl', 'js', 'js', 'krl', '6'),
(528, 'orng', 'c2c', 'orng', 'c2c', '6'),
(529, 'orng', 'syg', 'syg', 'orng', '6'),
(530, 'aro', 'c2c ', 'aro', 'c2c', '6'),
(531, 'js', 'syg', 'js', 'syg', '6'),
(532, 'krl', 'c2c', 'krl', 'c2c', '6'),
(533, 'krl', 'syg', 'syg', 'krl', '6'),
(534, 'orng', 'aro', 'orng', 'aro', '6'),
(535, 'orng', 'js', 'js', 'orng', '6'),
(536, 'aro', 'syg', 'aro', 'syg', '6'),
(537, 'c2c', 'js', 'c2c', 'js', '6'),
(538, 'krl', 'orng', 'krl', 'orng', '6'),
(539, 'krl', 'js', 'krl', 'js', '6'),
(540, 'orng', 'syg', 'orng', 'syg', '6'),
(541, 'aro', 'c2c', 'aro', 'c2c', '6'),
(542, 'aro', 'js', 'aro', 'js', '6'),
(543, 'krl', 'aro', 'aro', 'krl', '6'),
(544, 'krl', 'c2c', 'krl', 'c2c', '6'),
(545, 'krl', 'syg', 'krl', 'syg', '6'),
(546, 'orng', 'aro', 'orng', 'aro', '6'),
(547, 'orng', 'c2c', 'orng', 'c2c', '6'),
(548, 'orng', 'js', 'orng', 'js', '6'),
(549, 'aro', 'syg', 'syg', 'aro', '6'),
(550, 'c2c', 'js', 'js', 'c2c', '6'),
(551, 'js', 'syg', 'syg', 'js', '6'),
(552, 'cbb', 'krl', 'krl', 'cbb', '6'),
(553, 'dzn', 'syg', 'dzn', 'syg', '6'),
(554, 'mint', 'js', 'js', 'mint', '6'),
(555, 'rx', 'c2c', 'rx', 'c2c', '6'),
(556, 'sgs', 'aro', 'sgs', 'aro', '6'),
(557, 'mmm', 'orng', 'orng', 'mmm', '6'),
(558, 'cbb', 'orng', 'orng', 'cbb', '6'),
(559, 'dzn', 'krl', 'dzn', 'krl', '6'),
(560, 'mint', 'syg', 'syg', 'mint', '6'),
(561, 'rx', 'js', 'rx', 'js', '6'),
(562, 'sgs', 'c2c', 'sgs', 'c2c', '6'),
(563, 'mmm ', 'aro', 'aro', 'mmm', '6'),
(564, 'cbb', 'aro', 'aro', 'cbb', '6'),
(565, 'dzn', 'orng', 'orng', 'dzn', '6'),
(566, 'mint', 'krl', 'mint', 'krl', '6'),
(567, 'rx', 'syg', 'rx', 'syg', '6'),
(568, 'sgs', 'js', 'sgs', 'js', '6'),
(569, 'mmm', 'c2c', 'mmm ', 'c2c', '6'),
(570, 'cbb', 'c2c', 'cbb', 'c2c', '6'),
(571, 'dzn', 'aro', 'dzn', 'aro', '6'),
(572, 'mint', 'orng', 'orng', 'mint', '6'),
(573, 'rx', 'krl', 'krl', 'rx', '6'),
(574, 'sgs', 'syg', 'sgs', 'syg', '6'),
(575, 'mmm', 'js', 'mmm', 'js', '6'),
(576, 'cbb', 'js', 'cbb', 'js', '6'),
(577, 'dzn', 'c2c', 'dzn', 'c2c', '6'),
(578, 'mint', 'aro', 'mint', 'aro', '6'),
(579, 'rx', 'orng', 'rx', 'orng', '6'),
(580, 'sgs', 'krl', 'krl', 'sgs', '6'),
(581, 'mmm', 'syg', 'mmm', 'syg', '6'),
(582, 'dzn', 'js', 'dzn', 'js', '6'),
(583, 'mint', 'c2c', 'c2c', 'mint', '6'),
(584, 'rx', 'aro', 'rx', 'aro', '6'),
(585, 'sgs', 'orng', 'orng', 'sgs', '6'),
(586, 'mmm', 'krl', 'krl', 'mmm', '6'),
(587, 'cbb', 'rx', 'rx', 'cbb', '7'),
(588, 'cbb', 'mmm', 'mmm', 'cbb', '7'),
(589, 'rx', 'mthd', 'rx', 'mthd', '7'),
(590, 'dzn', 'krl', 'dzn', 'krl', '7'),
(591, 'mmm', 'dzn', 'dzn', 'mmm', '7'),
(592, 'mthd', 'krl', 'krl', 'mthd', '7'),
(593, 'cbb', 'mthd', 'cbb', 'mthd', '7'),
(594, 'cbb', 'dzn', 'cbb', 'dzn', '7'),
(595, 'rx', 'dzn', 'dzn', 'rx', '7'),
(596, 'rx', 'krl', 'krl', 'rx', '7'),
(597, 'mmm', 'mthd', 'mmm', 'mthd', '7'),
(598, 'mmm', 'krl', 'krl', 'mmm', '7'),
(599, 'cbb', 'rx', 'cbb', 'rx', '7'),
(600, 'cbb', 'krl', 'krl', 'cbb', '7'),
(601, 'rx', 'mmm', 'rx', 'mmm', '7'),
(602, 'mmm', 'dzn', 'mmm', 'dzn', '7'),
(603, 'mthd', 'dzn', 'dzn', 'mthd', '7'),
(604, 'mthd', 'krl', 'krl', 'mthd', '7'),
(605, 'cbb', 'mmm', 'cbb', 'mmm', '7'),
(606, 'cbb', 'dzn', 'dzn', 'cbb', '7'),
(607, 'rx', 'mthd', 'rx', 'mthd', '7'),
(608, 'rx', 'krl', 'krl', 'rx', '7'),
(609, 'mmm', 'mthd', 'mthd', 'mmm', '7'),
(610, 'dzn', 'krl', 'dzn', 'krl', '7'),
(611, 'cbb', 'mthd', 'cbb', 'mthd', '7'),
(612, 'cbb', 'krl', 'krl', 'cbb', '7'),
(613, 'rx', 'mmm', 'rx', 'mmm', '7'),
(614, 'rx', 'dzn', 'dzn', 'rx', '7'),
(615, 'mmm', 'krl', 'krl', 'mmm', '7'),
(616, 'mthd', 'dzn', 'dzn', 'mthd', '7'),
(617, 'cbb', 'rx', 'cbb', 'rx', '7'),
(618, 'cbb', 'dzn', 'dzn', 'cbb', '7'),
(619, 'rx', 'krl', 'krl', 'rx', '7'),
(620, 'mmm', 'mthd', 'mmm', 'mthd', '7'),
(621, 'mmm', 'dzn', 'dzn', 'mmm', '7'),
(622, 'mthd', 'krl', 'krl', 'mthd', '7'),
(623, 'cbb', 'mmm', 'mmm', 'cbb', '7'),
(624, 'cbb', 'mthd', 'cbb', 'mthd', '7'),
(625, 'cbb', 'krl', 'krl', 'cbb', '7'),
(626, 'rx', 'mmm', 'mmm', 'rx', '7'),
(627, 'rx', 'mthd', 'rx', 'mthd', '7'),
(628, 'rx', 'dzn', 'dzn', 'rx', '7'),
(629, 'mmm', 'krl', 'krl', 'mmm', '7'),
(630, 'mthd', 'dzn', 'mthd', 'dzn', '7'),
(631, 'dzn', 'krl', 'dzn', 'krl', '7'),
(632, 'aro', 'king', 'aro', 'king', '7'),
(633, 'aro', 'orng', 'orng', 'aro', '7'),
(634, 'king', 'sgs', 'king', 'sgs', '7'),
(635, 'js', 'syg', 'syg', 'js', '7'),
(636, 'orng', 'js', 'orng', 'js', '7'),
(637, 'sgs', 'syg ', 'sgs', 'syg', '7'),
(638, 'aro', 'sgs', 'sgs', 'aro', '7'),
(639, 'aro', 'js', 'js', 'aro', '7'),
(640, 'king', 'js', 'js', 'king', '7'),
(641, 'king', 'syg', 'king', 'syg', '7'),
(642, 'orng', 'sgs', 'orng', 'sgs', '7'),
(643, 'orng', 'syg', 'orng', 'syg', '7'),
(644, 'aro', 'king', 'aro', 'king', '7'),
(645, 'aro', 'syg', 'aro', 'syg', '7'),
(646, 'king', 'orng', 'orng', 'king', '7'),
(647, 'orng', 'js', 'orng', 'js', '7'),
(648, 'sgs', 'js', 'js', 'sgs', '7'),
(649, 'sgs', 'syg', 'sgs', 'syg', '7'),
(650, 'aro', 'orng', 'orng', 'aro', '7'),
(651, 'aro', 'js', 'js', 'aro', '7'),
(652, 'king', 'sgs', 'sgs', 'king', '7'),
(653, 'king', 'syg', 'syg', 'king', '7'),
(654, 'orng', 'sgs', 'sgs', 'orng', '7'),
(655, 'js', 'syg', 'js', 'syg', '7'),
(656, 'aro', 'sgs', 'sgs', 'aro', '7'),
(657, 'aro', 'syg', 'aro', 'syg', '7'),
(658, 'king', 'orng', 'orng', 'king', '7'),
(659, 'king', 'js', 'js', 'king', '7'),
(660, 'orng', 'syg', 'syg', 'orng', '7'),
(661, 'sgs', 'js', 'sgs', 'js', '7'),
(662, 'aro', 'king', 'king', 'aro', '7'),
(663, 'aro', 'js', 'aro', 'js', '7'),
(664, 'king', 'syg', 'syg', 'king', '7'),
(665, 'orng', 'sgs', 'orng', 'sgs', '7'),
(666, 'orng', 'js', 'orng', 'js', '7'),
(667, 'sgs', 'syg ', 'syg', 'sgs', '7'),
(668, 'aro', 'orng', 'aro', 'orng', '7'),
(669, 'aro', 'sgs', 'sgs', 'aro', '7'),
(670, 'aro', 'syg', 'syg', 'aro', '7'),
(671, 'orng', 'king', 'orng', 'king', '7'),
(672, 'sgs', 'king', 'sgs', 'king', '7'),
(673, 'orng', 'syg', 'syg', 'orng', '7'),
(674, 'sgs', 'js', 'js', 'sgs', '7'),
(675, 'js', 'syg', 'syg', 'js', '7'),
(676, 'cbb', 'aro', 'cbb', 'aro', '7'),
(677, 'rx', 'syg', 'syg', 'rx', '7'),
(678, 'mmm', 'js', 'js', 'mmm', '7'),
(679, 'mthd', 'sgs', 'sgs', 'mthd', '7'),
(680, 'dzn', 'orng', 'dzn', 'orng', '7'),
(681, 'krl', 'king', 'king', 'krl', '7'),
(682, 'cbb', 'king', 'king', 'cbb', '7'),
(683, 'rx', 'aro', 'rx', 'aro', '7'),
(684, 'mmm', 'syg', 'syg', 'mmm', '7'),
(685, 'mthd', 'js', 'js', 'mthd', '7'),
(686, 'dzn', 'sgs', 'sgs', 'dzn', '7'),
(687, 'krl', 'orng', 'orng', 'krl', '7'),
(688, 'cbb', 'orng', 'cbb', 'orng', '7'),
(689, 'rx', 'king', 'king', 'rx', '7'),
(690, 'mmm', 'aro', 'mmm', 'aro', '7'),
(691, 'mthd', 'syg', 'syg', 'mthd', '7'),
(692, 'dzn', 'js', 'js', 'dzn', '7'),
(693, 'krl', 'sgs', 'sgs', 'krl', '7'),
(694, 'cbb', 'sgs', 'sgs', 'cbb', '7'),
(695, 'rx', 'orng', 'orng', 'rx', '7'),
(696, 'mmm', 'king', 'king', 'mmm', '7'),
(697, 'mthd', 'aro', 'mthd', 'aro', '7'),
(698, 'dzn', 'syg', 'dzn', 'syg', '7'),
(699, 'krl', 'js', 'krl', 'js', '7'),
(700, 'cbb', 'js', 'js', 'cbb', '7'),
(701, 'rx', 'sgs', 'sgs', 'rx', '7'),
(702, 'mmm', 'orng', 'orng', 'mmm', '7'),
(703, 'mthd', 'king', 'mthd', 'king', '7'),
(704, 'dzn', 'aro', 'dzn', 'aro', '7'),
(705, 'krl', 'syg', 'krl', 'syg', '7'),
(706, 'cbb', 'syg', 'syg', 'cbb', '7'),
(707, 'rx', 'js', 'rx', 'js', '7'),
(708, 'mmm', 'sgs', 'sgs', 'mmm', '7'),
(709, 'mthd', 'orng', 'orng', 'mthd', '7'),
(710, 'dzn', 'king', 'dzn', 'king', '7'),
(711, 'krl', 'aro', 'krl', 'aro', '7'),
(712, 'mmm', 'mint', 'mmm', 'mint', '8'),
(713, 'mmm', 'orng', 'orng', 'mmm', '8'),
(714, 'mmm', 'lsmc', 'mmm', 'lmsc', '8'),
(715, 'mmm', 'king', 'mmm', 'king', '8'),
(716, 'mint', 'orng', 'orng', 'mint', '8'),
(717, 'mint', 'lmsc', 'lmsc', 'mint', '8'),
(718, 'mint', 'king', 'mint', 'king', '8'),
(719, 'orng', 'lmsc', 'orng', 'lmsc', '8'),
(720, 'orng', 'king', 'orng', 'king', '8'),
(721, 'lmsc', 'king', 'king', 'lmsc', '8'),
(722, 'mmm', 'mint', 'mint', 'mmm', '8'),
(723, 'mmm', 'orng', 'orng', 'mmm', '8'),
(724, 'mmm', 'lmsc', 'lmsc', 'mmm', '8'),
(725, 'mmm', 'king', 'mmm', 'king', '8'),
(726, 'mint', 'orng', 'orng', 'mint', '8'),
(727, 'mint', 'lmsc', 'lmsc', 'mint', '8'),
(728, 'orng', 'lmsc', 'orng', 'lmsc', '8'),
(729, 'orng', 'king', 'orng', 'king', '8'),
(730, 'lmsc', 'king', 'lmsc', 'king', '8'),
(731, 'mmm', 'orng', 'orng', 'mmm', '8'),
(732, 'mmm', 'lmsc', 'lmsc', 'mmm', '8'),
(733, 'mmm', 'king', 'king', 'mmm', '8'),
(734, 'mint', 'orng', 'orng', 'mint', '8'),
(735, 'orng', 'lmsc', 'orng', 'lmsc', '8'),
(736, 'orng', 'king', 'orng', 'king', '8'),
(737, 'syg', 'dzn', 'dzn', 'syg', '8'),
(738, 'syg', 'krl', 'syg', 'krl', '8'),
(739, 'syg', 'js', 'syg', 'js', '8'),
(740, 'syg', 'aro', 'syg', 'aro', '8'),
(741, 'dzn', 'krl', 'dzn', 'krl', '8'),
(742, '', '', '', '', ''),
(743, 'dzn', 'aro', 'aro', 'dzn', '8'),
(744, 'krl', 'js', 'krl', 'js', '8'),
(745, 'krl', 'aro', 'aro', 'krl', '8'),
(746, 'js', 'aro', 'aro', 'js', '8'),
(747, 'syg', 'dzn', 'syg', 'dzn', '8'),
(748, 'syg', 'krl', 'krl', 'syg', '8'),
(749, 'syg', 'js', 'syg', 'js', '8'),
(750, 'syg', 'aro', 'syg', 'aro', '8'),
(751, 'dzn', 'krl', 'dzn', 'krl', '8'),
(752, 'dzn', 'js', 'dzn', 'js', '8'),
(753, 'dzn', 'aro', 'dzn', 'aro', '8'),
(754, 'krl', 'js', 'js', 'krl', '8'),
(755, 'krl', 'aro', 'krl', 'aro', '8'),
(756, 'js', 'aro', 'aro', 'js', '8'),
(757, 'syg', 'dzn', 'dzn', 'syg', '8'),
(758, 'syg', 'krl', 'syg', 'krl', '8'),
(759, 'syg', 'js', 'js', 'syg', '8'),
(760, 'syg', 'aro', 'syg', 'aro', '8'),
(761, 'dzn', 'krl', 'dzn', 'krl', '8'),
(762, 'dzn', 'js', 'js', 'dzn', '8'),
(763, 'dzn', 'aro', 'aro', 'dzn', '8'),
(764, 'krl', 'js', 'krl', 'js', ''),
(765, 'krl', 'aro', 'aro', 'krl', '8'),
(766, 'js', 'aro', 'aro', 'js', '8'),
(767, 'mmm', 'aro', 'aro', 'mmm', '8'),
(768, 'mmm', 'syg', 'mmm', 'syg', '8'),
(769, 'mint', 'js', 'mint', 'js', '8'),
(770, 'mint', 'aro', 'aro', 'mint', '8'),
(771, 'orng', 'krl', 'krl', 'orng', '8'),
(772, 'orng', 'js', 'js', 'orng', '8'),
(773, 'lmsc', 'dzn', 'dzn', 'lmsc', '8'),
(774, 'lmsc', 'krl', 'krl', 'lmsc', '8'),
(775, 'king', 'syg', 'syg', 'king', '8'),
(776, 'mmm', 'dzn', 'dzn', 'mmm', '8'),
(777, 'mmm', 'krl', 'krl', 'mmm', '8'),
(778, 'mint', 'syg', 'syg', 'mint', '8'),
(779, 'orng', 'aro', 'orng', 'aro', '8'),
(780, 'orng', 'syg', 'orng', 'syg', '8'),
(781, 'lmsc', 'js', 'js', 'lmsc', '8'),
(782, 'lmsc', 'aro', 'aro', 'lmsc', '8'),
(783, 'king', 'krl', 'krl', 'king', '8'),
(784, 'king', 'js', 'js', 'king', '8'),
(785, 'orng', 'dzn', 'orng', 'dzn', '8'),
(786, 'lmsc', 'syg', 'syg', 'lmsc', '8'),
(787, 'king', 'aro', 'aro', 'king', '8'),
(788, 'alt', 'orng', 'orng', 'alt', '9'),
(789, 'js', 'mmm', 'js', 'mmm', '9'),
(790, 'mint', 'aro', 'aro', 'mint', '9'),
(791, 'don', 'dzn', 'dzn', 'don', '9'),
(792, 'aro', 'don', 'aro', 'don', '9'),
(793, 'dzn', 'alt', 'dzn', 'alt', '9'),
(794, 'mmm', 'mint', 'mint', 'mmm', '9'),
(795, 'orng', 'js', 'js', 'orng', '9'),
(796, 'don', 'mmm', 'don', 'mmm', '9'),
(797, 'mint', 'orng', 'orng', 'mint', '9'),
(798, 'alt', 'js', 'js', 'alt', '9'),
(799, 'dzn', 'aro', 'aro', 'dzn', '9'),
(800, 'js', 'mint', 'mint', 'js', '9'),
(801, 'mmm', 'dzn', 'dzn', 'mmm', '9'),
(802, 'orng', 'don', 'orng', 'don', '9'),
(803, 'aro', 'alt', 'aro', 'alt', '9'),
(804, 'aro', 'mmm', 'aro', 'mmm', '9'),
(805, 'don', 'js', 'js', 'don', '9'),
(806, 'dzn', 'orng', 'dzn', 'orng', '9'),
(807, 'alt', 'mint', 'mint', 'alt', '9'),
(808, 'js', 'dzn', 'dzn', 'js', '9'),
(809, 'orng', 'aro', 'orng', 'aro', '9'),
(810, 'mmm', 'alt', 'mmm', 'alt', '9'),
(811, 'mint', 'don', 'mint', 'don', '9'),
(812, 'dzn', 'mint', 'dzn', 'mint', '9'),
(813, 'alt', 'don', 'alt', 'don', '9'),
(814, 'aro', 'js', 'aro', 'js', '9'),
(815, 'mmm', 'orng', 'orng', 'mmm', '9'),
(816, 'cbb', 'syn', 'cbb', 'syn', '10'),
(817, 'orng', 'js', 'js', 'orng', '10'),
(818, 'don', 'wood', 'don', 'wood', '10'),
(819, 'dzn', 'aro', 'dzn', 'aro', '10'),
(820, 'wood', 'dzn', 'dzn', 'wood', '10'),
(821, 'js', 'don', 'js', 'don', '10'),
(822, 'syn', 'orng', 'orng', 'syn', '10'),
(823, 'sgs', 'cbb', 'sgs', 'cbb', '10'),
(824, 'orng', 'sgs', 'orng', 'sgs', '10'),
(825, 'don', 'syn', 'syn', 'don', '10'),
(826, 'dzn', 'js', 'dzn', 'js', '10'),
(827, 'aro', 'wood', 'aro', 'wood', '10'),
(828, 'js', 'aro', 'js', 'aro', '10'),
(829, 'syn', 'dzn', 'dzn', 'syn', '10'),
(830, 'sgs', 'don', 'sgs', 'don', '10'),
(831, 'cbb', 'orng', 'orng', 'cbb', '10'),
(832, 'don', 'cbb', 'cbb', 'don', '10'),
(833, 'dzn', 'sgs', 'dzn', 'sgs', '10'),
(834, 'aro', 'syn', 'aro', 'syn', '10'),
(835, 'wood', 'js', 'wood', 'js', '10'),
(836, 'syn', 'wood', 'syn', 'wood', '10'),
(837, 'sgs', 'aro', 'sgs', 'aro', '10'),
(838, 'cbb', 'dzn', 'dzn', 'cbb', '10'),
(839, 'orng', 'don', 'orng', 'don', '10'),
(840, 'dzn', 'orng', 'orng', 'dzn', '10'),
(841, 'aro', 'cbb', 'cbb', 'aro', '10'),
(842, 'wood', 'sgs', 'sgs', 'wood', '10'),
(843, 'js', 'syn', 'js', 'syn', '10'),
(844, 'sgs', 'js', 'sgs', 'js', '10'),
(845, 'cbb', 'wood', 'cbb', 'wood', '10'),
(846, 'orng', 'aro', 'orng', 'aro', '10'),
(847, 'don', 'dzn', 'dzn', 'don', '10'),
(848, 'aro', 'don', 'aro', 'don', '10'),
(849, 'wood', 'orng', 'wood', 'orng', '10'),
(850, 'js', 'cbb', 'js', 'cbb', '10'),
(851, 'syn', 'sgs', 'sgs', 'syn', '10'),
(852, 'orng', 'js', 'js', 'orng', '11'),
(853, 'aro', 'syn', 'aro', 'syn', '11'),
(854, 'dzn', 'huh', 'dzn', 'huh', '11'),
(855, 'sgs', 'cbb', 'sgs', 'cbb', '11'),
(856, 'huh', 'sgs', 'sgs', 'huh', '11'),
(857, 'syn', 'dzn', 'dzn', 'syn', '11'),
(858, 'js', 'aro', 'aro', 'js', '11'),
(859, 'don', 'orng', 'orng', 'don', '11'),
(860, 'aro', 'don', 'aro', 'don', '11'),
(861, 'dzn', 'js', 'dzn', 'js', '11'),
(862, 'sgs', 'syn', 'sgs', 'syn', '11'),
(863, 'cbb', 'huh', 'cbb', 'huh', '11'),
(864, 'syn', 'cbb', 'cbb', 'syn', '11'),
(865, 'js', 'sgs', 'sgs', 'js', '11'),
(866, 'don', 'dzn', 'dzn', 'don', '11'),
(867, 'orng', 'aro', 'orng', 'aro', '11'),
(868, 'dzn', 'orng', 'dzn', 'orng', '11'),
(869, 'sgs', 'don', 'sgs', 'don', '11'),
(870, 'cbb', 'js', 'js', 'cbb', '11'),
(871, 'huh', 'syn', 'huh', 'syn', '11'),
(872, 'js', 'huh', 'js', 'huh', '11'),
(873, 'don', 'cbb', 'don', 'cbb', '11'),
(874, 'orng', 'sgs', 'sgs', 'orng', '11'),
(875, 'aro', 'dzn', 'dzn', 'aro', '11'),
(876, 'sgs', 'aro', 'sgs', 'aro', '11'),
(877, 'cbb', 'orng', 'orng', 'cbb', '11'),
(878, 'huh', 'don', 'huh', 'don', '11'),
(879, 'syn', 'js', 'syn', 'js', '11'),
(880, 'don', 'syn', 'don', 'syn', '11'),
(881, 'orng', 'huh', 'orng', 'huh', '11'),
(882, 'aro', 'cbb', 'aro', 'cbb', '11'),
(883, 'dzn', 'sgs', 'sgs', 'dzn', '11'),
(884, 'cbb', 'dzn', 'dzn', 'cbb', '11'),
(885, 'huh', 'aro', 'aro', 'huh', '11'),
(886, 'syn', 'orng', 'orng', 'syn', '11'),
(887, 'js', 'don', 'js', 'don', '11');

-- --------------------------------------------------------

--
-- Table structure for table `roster_pkmn`
--

CREATE TABLE `roster_pkmn` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active_user` int(11) NOT NULL,
  `showdown_pkmn` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roster_pkmn`
--

INSERT INTO `roster_pkmn` (`id`, `name`, `active_user`, `showdown_pkmn`, `created_at`) VALUES
(5, NULL, 3, 1, '2026-02-19 11:32:37'),
(9, NULL, 4, 2, '2026-02-19 13:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE `seasons` (
  `season_id` int(11) NOT NULL,
  `league_id` int(11) NOT NULL,
  `season_number` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `playoff_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `showdown_pkmn`
--

CREATE TABLE `showdown_pkmn` (
  `id` int(11) NOT NULL,
  `showdown_id` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `Tier` varchar(10) DEFAULT NULL,
  `type1` varchar(20) DEFAULT NULL,
  `type2` varchar(20) DEFAULT NULL,
  `generation` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `showdown_pkmn`
--

INSERT INTO `showdown_pkmn` (`id`, `showdown_id`, `name`, `Tier`, `type1`, `type2`, `generation`, `created_at`) VALUES
(1, 'clefable', 'Clefable', NULL, NULL, NULL, NULL, '2026-02-18 14:11:52'),
(2, 'tyranitar', 'Tyranitar', NULL, NULL, NULL, NULL, '2026-02-18 14:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gamerTag` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `gamerTag`, `created_at`) VALUES
(1, 'user1@example.com', 'password1', 'player1', '2026-02-18 14:09:10'),
(2, 'user2@example.com', 'password2', 'player2', '2026-02-18 14:09:10'),
(3, 'user@example.com', 'password3', 'player3', '2026-02-20 16:28:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_users`
--
ALTER TABLE `active_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `drafted_pkmn`
--
ALTER TABLE `drafted_pkmn`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pokemon_per_season` (`season_id`,`showdown_pkmn`),
  ADD KEY `active_user` (`active_user`),
  ADD KEY `showdown_pkmn` (`showdown_pkmn`);

--
-- Indexes for table `draft_info`
--
ALTER TABLE `draft_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leagues`
--
ALTER TABLE `leagues`
  ADD PRIMARY KEY (`league_id`);

--
-- Indexes for table `roster_pkmn`
--
ALTER TABLE `roster_pkmn`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `active_user` (`active_user`,`showdown_pkmn`),
  ADD UNIQUE KEY `unique_pokemon` (`showdown_pkmn`);

--
-- Indexes for table `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`season_id`),
  ADD UNIQUE KEY `league_id` (`league_id`,`season_number`);

--
-- Indexes for table `showdown_pkmn`
--
ALTER TABLE `showdown_pkmn`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `showdown_id` (`showdown_id`);

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
-- AUTO_INCREMENT for table `active_users`
--
ALTER TABLE `active_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `drafted_pkmn`
--
ALTER TABLE `drafted_pkmn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `draft_info`
--
ALTER TABLE `draft_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leagues`
--
ALTER TABLE `leagues`
  MODIFY `league_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roster_pkmn`
--
ALTER TABLE `roster_pkmn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `season_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `showdown_pkmn`
--
ALTER TABLE `showdown_pkmn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `active_users`
--
ALTER TABLE `active_users`
  ADD CONSTRAINT `active_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `drafted_pkmn`
--
ALTER TABLE `drafted_pkmn`
  ADD CONSTRAINT `drafted_pkmn_ibfk_1` FOREIGN KEY (`active_user`) REFERENCES `active_users` (`id`),
  ADD CONSTRAINT `drafted_pkmn_ibfk_2` FOREIGN KEY (`showdown_pkmn`) REFERENCES `showdown_pkmn` (`id`);

--
-- Constraints for table `roster_pkmn`
--
ALTER TABLE `roster_pkmn`
  ADD CONSTRAINT `fk_roster_pokemon` FOREIGN KEY (`showdown_pkmn`) REFERENCES `showdown_pkmn` (`id`),
  ADD CONSTRAINT `fk_roster_user` FOREIGN KEY (`active_user`) REFERENCES `active_users` (`id`);

--
-- Constraints for table `seasons`
--
ALTER TABLE `seasons`
  ADD CONSTRAINT `seasons_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`league_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
