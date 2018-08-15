-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2018 at 05:27 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `site_name`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `parameter_name` varchar(30) NOT NULL,
  `value` float NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`parameter_name`, `value`, `time`) VALUES
('temperature', 300, '2018-10-03 15:30:00'),
('temperature', 200, '2018-10-03 15:35:00'),
('temperature', 50, '2018-10-01 15:00:00'),
('temperature', 300, '2018-10-02 16:30:00'),
('temperature', 100, '2018-10-02 12:30:00'),
('temperature', 30, '2018-10-04 15:30:00'),
('temperature', 50, '2018-10-04 15:30:00'),
('temperature', 45, '2018-10-07 15:30:00'),
('temperature', 86, '2018-10-07 15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `parameter_threshold`
--

CREATE TABLE `parameter_threshold` (
  `parameter_name` varchar(15) NOT NULL,
  `normal_value` smallint(6) NOT NULL,
  `warning_value` smallint(6) NOT NULL,
  `error_value` smallint(6) NOT NULL,
  `critical_value` smallint(6) NOT NULL,
  `alert_value` smallint(6) NOT NULL,
  `emergency` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parameter_threshold`
--

INSERT INTO `parameter_threshold` (`parameter_name`, `normal_value`, `warning_value`, `error_value`, `critical_value`, `alert_value`, `emergency`) VALUES
('temperature', 20, 40, 60, 80, 100, 200),
('smoke', 20, 40, 60, 80, 100, 200),
('motion', 21, 20, 10, 5, 2, 0),
('fuel_level', 500, 250, 150, 100, 50, 10),
('generator', 1, 0, 0, 0, 0, 0),
('voltage_level', 1, 0, 2, 2, 2, 2),
('battery_charge', 100, 50, 30, 20, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
