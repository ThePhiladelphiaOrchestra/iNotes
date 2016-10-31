-- phpMyAdmin SQL Dump
-- version 3.3.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2016 at 02:28 PM
-- Server version: 5.0.92
-- PHP Version: 5.3.26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `content`
--

-- --------------------------------------------------------

--
-- Table structure for table `currentMeasure`
--

CREATE TABLE IF NOT EXISTS `currentMeasure` (
  `currentMeasure` int(11) default NULL,
  `currentPiece` char(100) default NULL,
  `currentNotification` char(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currentMeasure`
--

INSERT INTO `currentMeasure` (`currentMeasure`, `currentPiece`, `currentNotification`) VALUES
(86, 'Strauss, Don Juan: Don Juan', 'PUSH|||BUTTON||');
