-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 06, 2020 at 05:41 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id14520930_bnb`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `account_user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `account_pass` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `account_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Action'),
(2, 'Adventure'),
(3, 'Comedy'),
(4, 'Demons'),
(5, 'Drama'),
(6, 'Ecchi'),
(7, 'Fantasy'),
(8, 'Gender Bender'),
(9, 'Harem'),
(10, 'Historical'),
(11, 'Horror'),
(12, 'Josei'),
(13, 'Magic'),
(14, 'Material Arts'),
(15, 'Mature'),
(16, 'Mecha'),
(17, 'Military'),
(18, 'Mystery'),
(19, 'One Shot'),
(20, 'Psychological'),
(21, 'Romance'),
(22, 'School Life'),
(23, 'Sci-Fi'),
(24, 'Seinen'),
(25, 'Shoujo'),
(26, 'Shoujoai'),
(27, 'Shounen'),
(28, 'Shounenai'),
(29, 'Slice of Life'),
(30, 'Smut'),
(31, 'Sports'),
(32, 'Super Power'),
(33, 'Supernatural'),
(34, 'Tragedy'),
(35, 'Vampire'),
(36, 'Yaoi'),
(37, 'Yuri');

-- --------------------------------------------------------

--
-- Table structure for table `chapter`
--

CREATE TABLE `chapter` (
  `chapter_id` int(11) NOT NULL,
  `chapter_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chapter_date` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chapter_latest` tinyint(1) DEFAULT NULL,
  `chapter_link` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `commnet_content` text COLLATE utf8_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL,
  `image_link` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_path` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `chapter_id` int(11) NOT NULL,
  `image_download_fail` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_download_firsttime` tinyint(1) DEFAULT NULL,
  `image_download_folder_path` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `store_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_category_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_alternate_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_artist` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_author` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_picture` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_status` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_reading_direction` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_release` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_link` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`chapter_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `chapter`
--
ALTER TABLE `chapter`
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
