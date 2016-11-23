-- phpMyAdmin SQL Dump
-- version 3.3.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2016 at 02:46 PM
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
-- Table structure for table `CurrentConcert`
--

CREATE TABLE IF NOT EXISTS `CurrentConcert` (
  `PieceName` text NOT NULL,
  `Order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CurrentConcert`
--

INSERT INTO `CurrentConcert` (`PieceName`, `Order`) VALUES
('Strauss, Don Quixote: Don Quixote', 0),
('Strauss, Don Juan: Don Juan', 0);
