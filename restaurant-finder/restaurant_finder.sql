-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2014 at 03:11 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `restaurant_finder`
--

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE IF NOT EXISTS `restaurants` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `location_ref` varchar(500) NOT NULL,
  `orig_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `menu` tinyint(1) NOT NULL,
  `image` varchar(6) NOT NULL,
  `address1` varchar(200) NOT NULL,
  `address2` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `county` varchar(200) NOT NULL,
  `postcode` varchar(200) NOT NULL,
  `tel` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `website` varchar(200) NOT NULL,
  `user` varchar(200) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `location_ref`, `orig_date`, `menu`, `image`, `address1`, `address2`, `city`, `county`, `postcode`, `tel`, `email`, `website`, `user`, `deleted`) VALUES
(1, 'Quo Vadis', '50.721353, -3.503497', '2014-10-19 10:01:46', 1, 'jpg', '83 Fore St', 'Heavitree', 'Exeter', 'Devon', 'EX1 2RN', '01392 273705', 'QuoVadisRestaurant@Hotmail.Com', 'www.quovadis-exeter.co.uk', 'admin', 0),
(2, 'Tiffinwala', '50.721717, -3.505663', '2014-10-12 14:42:56', 0, '0', '49 Fore St', 'Heavitree', 'Exeter', 'Devon', 'EX1 2QN', '01392 278822', 'tiffinwala.exeter@gmail.com', 'www.tiffinwala-exeter.co.uk', 'admin', 0),
(3, 'my test rest 2', '123456', '2014-10-19 13:06:33', 0, '0', '11 Ludwell Lane', 'Wonford', 'Exeter', 'Devon', 'EX4 4PU', '441392722566', '', 'www.somesite.co.uk', 'admin', 1),
(4, 'The Matford Diner', '50.706140, -3.527426', '2014-10-18 08:38:55', 0, 'jpg', '10 Trusham Rd', 'Marsh Barton', 'Exeter', 'Devon', 'EX2 8QH', '01392 206520', '', '', 'admin', 0),
(5, 'Tong Kong Oriental Ltd', '50.708926, -3.528886', '2014-10-13 17:24:24', 0, '0', '8 Bridford Rd', '', 'Exeter', 'Devon', 'EX2 8QX', '01392 438633', '', '', 'admin', 0),
(6, 'Double Locks', '50.700636, -3.512664', '2014-10-19 10:01:39', 0, 'jpg', 'Canal Banks', '', 'Exeter', 'Devon', 'EX2 6LT', '01392 256947', 'doublelocks@youngs.co.uk', 'www.doublelocks.com', 'admin', 0),
(7, 'my test rest', '50.721353, -3.503497', '2014-10-18 11:06:11', 1, 'jpg', 'dsafsdf', 'adsf', 'adsf', 'adsf', 'EX2 5LU', 'adfs', 'afsd@fsadf.om', 'fd', 'admin', 1),
(9, 'The Seven Stars Hotel', '50.710149, -3.538245', '2014-10-19 10:01:02', 0, 'jpg', 'Alphington Rd', 'Rennes Drive', 'Exeter', 'Devon', 'EX2 8JB', '01392 250983', '', 'emberinns.co.uk', 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pwdhash` varchar(200) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pwdhash`, `deleted`) VALUES
(1, 'admin', 'h.connole@exeter.ac.uk', '$2y$10$oLq6vntGbA.FKzdIL.abH.59ud.lw0YT3ojDpI/xz2F/Ug81iLbpW', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
