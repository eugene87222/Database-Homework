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
-- Database: `yanghc87222_cs_simple_login_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`userID` int(10) unsigned NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `identity` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `name`, `email`, `identity`) VALUES
(1, 'admin', '$2y$10$SUDNmB4Y5mQboV67/LzI8O/B6aoivkJme3c8TsT11kkydGvIMT9wS', 'admin_for_demo', 'db2017@yahoo.com', 'admin'),
(14, 'aaa', '$2y$10$TGt6Y3.KG8EdgNPlI2fS1.Crjz7UZqq4DpClfuELdmYRzS00QvSsm', 'aaa', 'aaa@mail.com', 'user'),
(15, 'bbb', '$2y$10$/zhLlnufex.RfmRwBMItreRMs3wmavn9lX6MdIafBLeWyjW.x35HO', 'bbb', 'bbb@mail.com', 'admin'),
(16, 'ccc', '$2y$10$O8sy2EV28zLX0dON5iHMJe3.7wKzco1ZkLhI5EEqioGbsLT/t7sB2', 'ccc', 'ccc@mail.com', 'user'),
(31, 'gg', '$2y$10$MJtcvFoz2Hiifjx.01oR2ORsukqUyxUXUCPfznKHyp/2PXpCFh8Qa', 'gg', 'gg@mail.com', 'user'),
(34, 'db', '$2y$10$Uc77oyuCBx9TzUUSJ/BHE.JLE7sj/Yz404hbXxUInqiMYTTQtkCqy', 'db2', 'db@mail.com', 'admin'),
(38, 'ddd', '$2y$10$LF6Nnzqsw9ZFN20eAQpyreG0CAZw9TmZUxFJEo0avFzoZ27T6x/i2', 'dbd', 'ddd@mail.com', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `userID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
