-- phpMyAdmin SQL Dump
-- version 4.2.9
-- http://www.phpmyadmin.net
--
-- Host: dbhome.cs.nctu.edu.tw
-- Generation Time: May 13, 2019 at 02:15 PM
-- Server version: 5.6.34-log
-- PHP Version: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yanghc87222_cs_simple_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `all_information`
--

CREATE TABLE IF NOT EXISTS `all_information` (
`ID` int(10) unsigned NOT NULL,
  `information` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `all_information`
--

INSERT INTO `all_information` (`ID`, `information`) VALUES
(1, 'laundry facilities'),
(2, 'wifi'),
(3, 'lockers'),
(4, 'kitchen'),
(5, 'elevator'),
(6, 'no smoking'),
(7, 'television'),
(8, 'breakfast'),
(9, 'toiletries provided'),
(10, 'shuttle service'),
(11, 'info_test_1'),
(16, '4'),
(17, 'Hey'),
(20, 'Hi'),
(21, 'Yo');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
`ID` int(10) unsigned NOT NULL,
  `houseID` int(10) unsigned NOT NULL,
  `visitorID` int(10) unsigned NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE IF NOT EXISTS `favorite` (
`ID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned DEFAULT NULL,
  `favoriteID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE IF NOT EXISTS `house` (
`ID` int(10) unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(10) NOT NULL,
  `locationID` int(10) unsigned DEFAULT NULL,
  `time` date NOT NULL,
  `ownerID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE IF NOT EXISTS `information` (
`ID` int(10) unsigned NOT NULL,
  `informationID` int(10) unsigned DEFAULT NULL,
  `houseID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
`ID` int(10) unsigned NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`ID`, `location`) VALUES
(1, 'Keelung'),
(3, 'Tainan'),
(4, 'Taipei'),
(5, 'Hsinchu'),
(6, 'Taoyuan'),
(7, 'Yunlin'),
(8, 'Kaohsiung'),
(9, 'Taitung'),
(10, 'Chiayi'),
(11, 'Yilan'),
(12, 'Hualien'),
(13, 'Changhua'),
(14, 'Taichung'),
(15, 'Pingtung'),
(17, 'tetetest');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`userID` int(10) unsigned NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `name`, `email`, `identity`) VALUES
(1, 'admin', '$2y$10$2RGOZ5F2ZQiURhkmdh2oUugFdsqKst04Pd31RuqLqUqP0pkUz6mTa', 'admin', 'admin@mail.com', 'admin'),
(88, 'aaa', '$2y$10$C.qYhp1lR5f29Q6b0wv2SeaUdJvKJd.Fy3WuncPQ.x55y52RGRC/O', 'aaa', 'aaa@mail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `all_information`
--
ALTER TABLE `all_information`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
 ADD PRIMARY KEY (`ID`), ADD KEY `booking_fk1` (`houseID`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
 ADD PRIMARY KEY (`ID`), ADD KEY `userID` (`userID`), ADD KEY `favoriteID` (`favoriteID`);

--
-- Indexes for table `house`
--
ALTER TABLE `house`
 ADD PRIMARY KEY (`ID`), ADD KEY `ownerID` (`ownerID`) USING BTREE, ADD KEY `locationID` (`locationID`);

--
-- Indexes for table `information`
--
ALTER TABLE `information`
 ADD PRIMARY KEY (`ID`), ADD KEY `informationID` (`informationID`), ADD KEY `info_fk2` (`houseID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `all_information`
--
ALTER TABLE `all_information`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `house`
--
ALTER TABLE `house`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `userID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=89;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
ADD CONSTRAINT `booking_fk1` FOREIGN KEY (`houseID`) REFERENCES `house` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`favoriteID`) REFERENCES `house` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `house`
--
ALTER TABLE `house`
ADD CONSTRAINT `house_fk_1` FOREIGN KEY (`ownerID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `house_fk_2` FOREIGN KEY (`locationID`) REFERENCES `location` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `information`
--
ALTER TABLE `information`
ADD CONSTRAINT `info_fk1` FOREIGN KEY (`informationID`) REFERENCES `all_information` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `info_fk2` FOREIGN KEY (`houseID`) REFERENCES `house` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
