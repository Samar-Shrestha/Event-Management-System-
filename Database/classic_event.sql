-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 05:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `classic_event`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nm` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pswd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nm`, `email`, `pswd`) VALUES
(1, 'Samar', 'samarshrestha77@gmail.com', '$2y$10$s0Ze/w5EpDo.Ut1VQ30lD.OxL8TUWV829KroQ87Xdg2oDUXBKM7vq');

-- --------------------------------------------------------

--
-- Table structure for table `anniversary`
--

CREATE TABLE `anniversary` (
  `id` int(11) NOT NULL,
  `img` varchar(1000) NOT NULL,
  `nm` varchar(20) NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `anniversary`
--

INSERT INTO `anniversary` (`id`, `img`, `nm`, `price`) VALUES
(1, 'IMG_9909.JPG', 'Balloon Decoration w', 210000),
(2, 'cs_anniversary1.jpg', 'Table Decoration1', 48000),
(3, 'cs_anniversary3.jpg', 'Anniversary Cake', 2000);

-- --------------------------------------------------------

--
-- Table structure for table `birthday`
--

CREATE TABLE `birthday` (
  `id` int(11) NOT NULL,
  `img` varchar(1000) NOT NULL,
  `nm` varchar(20) NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `birthday`
--

INSERT INTO `birthday` (`id`, `img`, `nm`, `price`) VALUES
(1, 'cs_birthday1.jpg', 'Baby Pink balloon bi', 80000),
(2, 'cs_minion.jpg', 'Minion birthday them', 120000),
(3, '13164198_965117990250248_2782481749866692985_n.jpg', 'Birthday decoration ', 130000),
(4, 'cs_birthday3.jpg', 'Birthday Cake', 1200),
(5, 'birthday1.jpg', 'Balloon', 100000);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `nm` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `mo` int(10) NOT NULL,
  `theme` varchar(1000) NOT NULL,
  `thm_nm` varchar(20) NOT NULL,
  `price` int(10) NOT NULL,
  `date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `nm`, `email`, `mo`, `theme`, `thm_nm`, `price`, `date`) VALUES
(1, 'Samar', 'samar@gmail.com', 2147483647, 'cs_birthday1.jpg', 'Baby Pink balloon bi', 80000, '2026-01-07');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `unm` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `comment` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `unm`, `email`, `comment`) VALUES
(3, 'shruti', 'shrutipatel@gmail.com', 'its very nice site.');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `unm` varchar(39) NOT NULL,
  `pswd` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `unm`, `pswd`) VALUES
(1, 'abc', 'abc'),
(2, 'c', 'e'),
(3, 'b', 'abc'),
(4, 'abc', 'abc'),
(5, 'samar', 'samar');

-- --------------------------------------------------------

--
-- Table structure for table `otherevent`
--

CREATE TABLE `otherevent` (
  `id` int(11) NOT NULL,
  `img` varchar(1000) NOT NULL,
  `nm` varchar(2000) NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `otherevent`
--

INSERT INTO `otherevent` (`id`, `img`, `nm`, `price`) VALUES
(1, 'cs_dj1.jpg', 'Dj parties in club', 90000),
(2, 'cs_dj-sound1.jpg', 'Wedding enjoyment', 60000),
(3, 'cs_eno2.JPG', 'Inoguration of new shop', 30000),
(4, 'cs_gift.jpg', 'Gift for function', 30000),
(5, 'IMG_9871.JPG', 'Selfy Zone', 85000),
(6, 'cs_dj-sound.jpg', 'Wedding Dhol', 15000),
(7, '11707822_846071408796477_7148431446458227749_n.jpg', 'asjdgjsagdj', 2565372);

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `nm` varchar(20) NOT NULL,
  `surnm` varchar(20) NOT NULL,
  `unm` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pswd` varchar(30) NOT NULL,
  `mo` int(11) NOT NULL,
  `gen` tinyint(1) NOT NULL,
  `adrs` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `nm`, `surnm`, `unm`, `email`, `pswd`, `mo`, `gen`, `adrs`) VALUES
(1, 'abc', 'a', 'b', 'c', 'abc', 687688, 0, 'mbjaj'),
(2, 'abc', 'abc', 'abc', 'abc', 'abc', 2801909, 0, 'abc'),
(3, 'sam', 'sam', 'samar', 'samar@gmail.com', 'samar', 2147483647, 0, 'patan');

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `id` int(11) NOT NULL,
  `img` varchar(500) NOT NULL,
  `nm` varchar(200) NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `temp`
--

INSERT INTO `temp` (`id`, `img`, `nm`, `price`) VALUES
(1, 'cs_wedding1.jpg', 'Yellow Rajwadi', 500000);

-- --------------------------------------------------------

--
-- Table structure for table `wedding`
--

CREATE TABLE `wedding` (
  `id` int(11) NOT NULL,
  `img` varchar(1000) NOT NULL,
  `nm` varchar(200) NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wedding`
--

INSERT INTO `wedding` (`id`, `img`, `nm`, `price`) VALUES
(1, 'cs_wedding1.jpg', 'Yellow Rajwadi', 500000),
(5, 'cs_wedding4.jpg', 'Snow white theme', 450000),
(6, 'cs_wedding7 - Copy.jpg', 'Rajwadi theme', 505000),
(7, 'cs_dj-sound.jpg', 'Enjoyment', 5000),
(8, 'cs_wedding_mandap.jpg', 'Wedding mandap', 10000),
(9, 'cs_wedding_flower.jpg', 'Flower Decoration', 20000),
(10, '1795470_933513173385633_6804003732512774959_n.jpg', 'white wedding theme', 480000),
(12, '14191925_1227924543944493_6325969755918013020_n.jpg', 'Red flower decoratio', 460000),
(13, 'cs_sofa.jpg', 'Lighting & sofa', 70000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nm` (`nm`);

--
-- Indexes for table `anniversary`
--
ALTER TABLE `anniversary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `birthday`
--
ALTER TABLE `birthday`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otherevent`
--
ALTER TABLE `otherevent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wedding`
--
ALTER TABLE `wedding`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `anniversary`
--
ALTER TABLE `anniversary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `birthday`
--
ALTER TABLE `birthday`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `otherevent`
--
ALTER TABLE `otherevent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `temp`
--
ALTER TABLE `temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wedding`
--
ALTER TABLE `wedding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
