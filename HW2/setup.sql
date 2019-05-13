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
-- Database: `yanghc87222_cs_simple_house_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE IF NOT EXISTS `favorite` (
  `ID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned DEFAULT NULL,
  `favoriteID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`ID`, `userID`, `favoriteID`) VALUES
(25, 1, 28),
(26, 1, 29);

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE IF NOT EXISTS `house` (
`ID` int(10) unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `location` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` date NOT NULL,
  `ownerID` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `house`
--

INSERT INTO `house` (`ID`, `name`, `price`, `location`, `time`, `ownerID`) VALUES
(4, 'Winterfell', 1000, 'Keelung', '2017-01-28', 73),
(5, 'Honey Bee', 750, 'Tainan', '2017-06-09', 73),
(6, 'Sky Tree', 1500, 'Taipei', '2017-04-11', 73),
(7, 'Pinky House', 880, 'Hsinchu', '2017-05-05', 74),
(8, 'Sam''s Park', 950, 'Tainan', '2017-03-17', 74),
(9, 'Wonderland Hotel', 1800, 'Taoyuan', '2017-08-24', 75),
(10, 'Owl''s Nest Inn', 600, 'Yunlin', '2017-04-01', 75),
(11, 'Lalaland', 990, 'Taitung', '2017-01-08', 75),
(12, 'Shanghai Tan', 1430, 'Kaohsiung', '2017-12-24', 76),
(13, 'Big Mom', 1200, 'Chiayi', '2017-09-04', 76),
(14, 'Little Place', 650, 'Yilan', '2017-03-19', 77),
(15, 'Beach Hotel', 2500, 'Hualien', '2017-07-01', 77),
(16, 'Sofa Bed', 499, 'Changhua', '2017-05-28', 77),
(17, 'You Are Wellcome', 800, 'Taipei', '2017-02-08', 78),
(18, 'Kitty''s Home', 1100, 'Taoyuan', '2017-03-14', 78),
(19, 'Sunshine Place', 999, 'Kaohsiung', '2017-01-04', 78),
(20, 'Greed Island', 666, 'Taitung', '2017-10-30', 78),
(21, 'My Home', 560, 'Tainan', '2017-03-21', 78),
(22, 'S Hotel', 1400, 'Kaohsiung', '2017-06-11', 79),
(23, 'Cow Bed', 500, 'Yunlin', '2017-11-28', 79),
(24, 'Hotelevision', 780, 'Hsinchu', '2017-08-07', 80),
(25, 'I''m Inn', 700, 'Taichung', '2017-11-11', 81),
(26, 'Stone House', 1100, 'Pingtung', '2017-06-19', 81),
(27, 'IWantToSleep', 0, 'Bed', '2017-11-24', 1),
(28, 'blablabla', 487, 'eight-seven', '1987-08-07', 1),
(29, 'fatman', 5487, 'sofat', '0001-01-08', 1),
(42, 'tttttt', 9999, 'Hsin', '1994-12-10', 1),
(43, 'fatma', 0, 'fat', '0002-03-04', 1),
(44, 'fatmannnn', 0, '123', '0001-02-03', 1),
(45, 'fatmannnnn', 32, 'qwe', '0002-02-02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE IF NOT EXISTS `information` (
`ID` int(10) unsigned NOT NULL,
  `information` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `houseID` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=532 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `information`
--

INSERT INTO `information` (`ID`, `information`, `houseID`) VALUES
(56, 'laundry facilities', 7),
(57, 'wifi', 7),
(58, 'elevator', 7),
(59, 'breakfast', 7),
(60, 'wifi', 8),
(61, 'kitchen', 8),
(62, 'no smoking', 8),
(63, 'television', 8),
(64, 'shuttle service', 8),
(65, 'laundry facilities', 9),
(66, 'wifi', 9),
(67, 'lockers', 9),
(68, 'elevator', 9),
(69, 'television', 9),
(70, 'toiletries provided', 9),
(71, 'wifi', 10),
(72, 'kitchen', 10),
(73, 'television', 10),
(74, 'laundry facilities', 11),
(75, 'wifi', 11),
(76, 'breakfast', 11),
(77, 'shuttle service', 11),
(78, 'wifi', 12),
(79, 'lockers', 12),
(80, 'kitchen', 12),
(81, 'elevator', 12),
(82, 'breakfast', 12),
(83, 'laundry facilities', 13),
(84, 'kitchen', 13),
(85, 'no smoking', 13),
(86, 'breakfast', 13),
(87, 'toiletries provided', 13),
(88, 'wifi', 14),
(89, 'no smoking', 14),
(90, 'wifi', 15),
(91, 'lockers', 15),
(92, 'elevator', 15),
(93, 'television', 15),
(94, 'breakfast', 15),
(95, 'shuttle service', 15),
(96, 'wifi', 16),
(97, 'kitchen', 16),
(98, 'television', 16),
(99, 'laundry facilities', 17),
(100, 'wifi', 17),
(101, 'breakfast', 17),
(102, 'toiletries provided', 17),
(103, 'no smoking', 18),
(104, 'television', 18),
(105, 'toiletries provided', 18),
(106, 'lockers', 19),
(107, 'kitchen', 19),
(108, 'breakfast', 19),
(109, 'wifi', 20),
(110, 'shuttle service', 20),
(111, 'laundry facilities', 21),
(112, 'wifi', 21),
(113, 'kitchen', 21),
(114, 'shuttle service', 21),
(115, 'laundry facilities', 22),
(116, 'no smoking', 22),
(117, 'breakfast', 22),
(118, 'toiletries provided', 22),
(119, 'elevator', 23),
(120, 'television', 23),
(121, 'shuttle service', 23),
(122, 'television', 24),
(123, 'wifi', 25),
(124, 'no smoking', 25),
(125, 'breakfast', 25),
(126, 'lockers', 26),
(127, 'kitchen', 26),
(128, 'elevator', 26),
(129, 'toiletries provided', 26),
(130, 'shuttle service', 26),
(373, 'lockers', 4),
(374, 'kitchen', 4),
(375, 'laundry facilities', 5),
(376, 'wifi', 5),
(377, 'no smoking', 5),
(378, 'television', 5),
(379, 'breakfast', 5),
(380, 'shuttle service', 5),
(381, 'wifi', 6),
(382, 'lockers', 6),
(383, 'elevator', 6),
(384, 'no smoking', 6),
(385, 'television', 6),
(386, 'toiletries provided', 6),
(516, 'laundry facilities', 27),
(517, 'wifi', 27),
(518, 'lockers', 27),
(519, 'kitchen', 27),
(520, 'elevator', 27),
(521, 'no smoking', 27),
(522, 'television', 27),
(523, 'breakfast', 27),
(524, 'toiletries provided', 27),
(525, 'shuttle service', 27),
(526, 'no smoking', 28),
(527, 'kitchen', 29),
(528, 'elevator', 29),
(529, 'elevator', 42),
(530, 'no smoking', 42),
(531, 'television', 42);

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
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `name`, `email`, `identity`) VALUES
(1, 'admin', '$2y$10$/NXvILvcpF5y.GN8IfQNCeEYYSQAVGM25aPyqxGPVHTtq6RsWc3Bq', 'admin_for_demo', 'db2017@nctu.edu.tw', 'admin'),
(73, 'Robb', '$2y$10$5Q9DUi/4HhYnotFyhtAf4uzDNMgdk3T2Ij6KCjVfOt5JkJ21JoEMy', 'Robb', 'robb@mail.com', 'user'),
(74, 'Sam', '$2y$10$49dwduTMci3T2OANM8rnw.RM8kklq.PiBg1kgM2LejElVirrMOgSG', 'Sam', 'sam@mail.com', 'user'),
(75, 'Lisa', '$2y$10$.a206zJKBdbOGIILaCP9Z.JkItxq3JKO3X/YlY9pv8Q3MIBYCkNKG', 'Lisa', 'lisa@mail.com', 'user'),
(76, 'Helen', '$2y$10$1FEb6aRW/s75tbFEyhgO.OBBCsjwNG3a9CT3Lga5KAa1romDwQx7q', 'Helen', 'helen@mail.com', 'user'),
(77, 'Jordan', '$2y$10$e8sQewiwz4aaeD0kn3qOGO9PW6bMp/EO94xsCvbCNvtqsmqWTLyi6', 'Jordan', 'jordan@mail.com', 'user'),
(78, 'Berry', '$2y$10$tjBqesPipymKCocfm/tShuwcpFYBmyDOoY2H4jozc/L0yeraf4qKy', 'Berry', 'berry@mail.com', 'user'),
(79, 'Peter', '$2y$10$lhh7chc/aYWgREWjqpC3Bew/cEGg5a4ZEdYIKvOSQF8CiPhR46ACS', 'Peter', 'peter@mail.com', 'user'),
(80, 'Ricky', '$2y$10$2esD0aQAMlrLR1Kk74.1Qe1bp7wfGtOqlJQ/HpiC/8vTd/24.hvIm', 'Ricky', 'ricky@mail.com', 'user'),
(81, 'Vava', '$2y$10$q3Uft3qENIrbE0LW.9b7nOA0jJ9mFRyL5lSOGjGm7F7xL3S8txU/W', 'Vava', 'vava@mail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `house`
--
ALTER TABLE `house`
 ADD PRIMARY KEY (`ID`), ADD KEY `ownerID` (`ownerID`) USING BTREE;

--
-- Indexes for table `information`
--
ALTER TABLE `information`
 ADD PRIMARY KEY (`ID`), ADD KEY `houseID` (`houseID`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `house`
--
ALTER TABLE `house`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
MODIFY `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=532;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `userID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `house`
--
ALTER TABLE `house`
ADD CONSTRAINT `house_ibfk_1` FOREIGN KEY (`ownerID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `information`
--
ALTER TABLE `information`
ADD CONSTRAINT `information_ibfk_1` FOREIGN KEY (`houseID`) REFERENCES `house` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
