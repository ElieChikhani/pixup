-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 05, 2024 at 07:41 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pixup`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` char(32) NOT NULL,
  `user_id` char(32) NOT NULL,
  `album_name` varchar(20) NOT NULL,
  `album_description` text NOT NULL,
  `album_creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `user_id`, `album_name`, `album_description`, `album_creation_date`) VALUES
('6751fed2c8662', '6751fed2c2f85', 'All', '', '2024-12-05 21:28:18'),
('6751fefb15a8a', '6751fefb10060', 'All', '', '2024-12-05 21:28:59'),
('6751ff14a9344', '6751fefb10060', 'Usek', '', '2024-12-05 21:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `album_image`
--

DROP TABLE IF EXISTS `album_image`;
CREATE TABLE IF NOT EXISTS `album_image` (
  `album_image_id` int NOT NULL AUTO_INCREMENT,
  `image_id` char(32) NOT NULL,
  `album_id` char(32) NOT NULL,
  `album_image_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `album_image`
--

INSERT INTO `album_image` (`album_image_id`, `image_id`, `album_id`, `album_image_date`) VALUES
(190, '6751ffa397476', '6751fefb15a8a', '2024-12-05 21:31:47'),
(191, '6751ffa397476', '6751ff14a9344', '2024-12-05 21:31:47'),
(192, '6751fffd5ad68', '6751fed2c8662', '2024-12-05 21:33:17');

-- --------------------------------------------------------

--
-- Table structure for table `category_image`
--

DROP TABLE IF EXISTS `category_image`;
CREATE TABLE IF NOT EXISTS `category_image` (
  `category_image_id` int NOT NULL AUTO_INCREMENT,
  `image_id` char(32) NOT NULL,
  `category` char(50) NOT NULL,
  PRIMARY KEY (`category_image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=413 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category_image`
--

INSERT INTO `category_image` (`category_image_id`, `image_id`, `category`) VALUES
(391, '6751ffa397476', 'architecture'),
(392, '6751ffa397476', 'building'),
(393, '6751ffa397476', 'city'),
(394, '6751ffa397476', 'structure'),
(395, '6751ffa397476', 'sky'),
(396, '6751ffa397476', 'planetarium'),
(397, '6751ffa397476', 'urban'),
(398, '6751ffa397476', 'modern'),
(399, '6751ffa397476', 'construction'),
(400, '6751ffa397476', 'office'),
(401, '6751ffa397476', 'house'),
(402, '6751fffd5ad68', 'sign'),
(403, '6751fffd5ad68', 'symbol'),
(404, '6751fffd5ad68', 'icon'),
(405, '6751fffd5ad68', 'design'),
(406, '6751fffd5ad68', 'graphic'),
(407, '6751fffd5ad68', 'business'),
(408, '6751fffd5ad68', 'button'),
(409, '6751fffd5ad68', 'label'),
(410, '6751fffd5ad68', 'element'),
(411, '6751fffd5ad68', 'arrow'),
(412, '6751fffd5ad68', 'art');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `image_id` char(32) NOT NULL,
  `path` varchar(100) NOT NULL,
  `title` varchar(20) NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` char(32) NOT NULL,
  `savedCount` int NOT NULL DEFAULT '0',
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`image_id`, `path`, `title`, `description`, `user_id`, `savedCount`, `upload_date`) VALUES
('6751fffd5ad68', 'images/6751fffd5ad68.jpg', 'Usek Logo', 'This is the logo of usek', '6751fed2c2f85', 0, '2024-12-05 21:33:17');

-- --------------------------------------------------------

--
-- Table structure for table `save_image`
--

DROP TABLE IF EXISTS `save_image`;
CREATE TABLE IF NOT EXISTS `save_image` (
  `save_id` int NOT NULL AUTO_INCREMENT,
  `image_id` char(32) NOT NULL,
  `user_id` char(32) NOT NULL,
  `save_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`save_id`)
) ENGINE=MyISAM AUTO_INCREMENT=283 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `save_image`
--

INSERT INTO `save_image` (`save_id`, `image_id`, `user_id`, `save_date`) VALUES
(282, '6751ffa397476', '6751fed2c2f85', '2024-12-05 21:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` char(32) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bio` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imageCount` int NOT NULL DEFAULT '0',
  `albumCount` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `bio`, `creation_date`, `imageCount`, `albumCount`) VALUES
('6751fed2c2f85', 'test1', 'test1@gmail.com', '$2y$10$w6Qlvjtt.a73fOSBk9XbHOMp77pjHLAoWqy2vRLoPk0bf6vVX4NIy', 'This is a test account in the betta version of PixUp', '2024-12-05 21:28:18', 1, 0),
('6751fefb10060', 'test2', 'test2@gmail.com', '$2y$10$DjWJL1GTKKlahydi5qhNFOot6fiyQ4VrOMDnBdqcWm8OGJlgcFzZK', 'This is a  test account for betta version of PixUp', '2024-12-05 21:28:59', 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
