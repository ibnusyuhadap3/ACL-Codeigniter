-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2014 at 11:58 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tutorialcodeigniter`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_permissions`
--

CREATE TABLE IF NOT EXISTS `ci_permissions` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `key` varchar(300) NOT NULL,
  `description` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ci_permissions`
--

INSERT INTO `ci_permissions` (`id`, `key`, `description`) VALUES
(1, 'site_login', 'Allowed for login to site'),
(2, 'admin_login', 'Allowed for login to admin'),
(3, 'create', 'Allowed to create any content'),
(4, 'edit', 'Allowed to edit any content'),
(5, 'delete', 'Allowed to delete any content'),
(6, 'configuration', 'Allowed to config any data of a content');

-- --------------------------------------------------------

--
-- Table structure for table `ci_permissions_roles`
--

CREATE TABLE IF NOT EXISTS `ci_permissions_roles` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `permission_id` int(100) NOT NULL,
  `role_id` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `ci_permissions_roles`
--

INSERT INTO `ci_permissions_roles` (`id`, `permission_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 1, 2),
(8, 2, 2),
(9, 3, 2),
(10, 4, 2),
(11, 5, 2),
(13, 1, 3),
(14, 2, 3),
(15, 1, 4),
(16, 2, 4),
(17, 3, 4),
(18, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `ci_roles`
--

CREATE TABLE IF NOT EXISTS `ci_roles` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `has_parent` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ci_roles`
--

INSERT INTO `ci_roles` (`id`, `name`, `has_parent`) VALUES
(1, 'super_user', 0),
(2, 'manager', 1),
(3, 'editor', 1),
(4, 'public', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ci_roles_users`
--

CREATE TABLE IF NOT EXISTS `ci_roles_users` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `role_id` varchar(300) NOT NULL,
  `user_id` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ci_roles_users`
--

INSERT INTO `ci_roles_users` (`id`, `role_id`, `user_id`) VALUES
(1, '1', '1'),
(2, '2', '2');

-- --------------------------------------------------------

--
-- Table structure for table `ci_users`
--

CREATE TABLE IF NOT EXISTS `ci_users` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ci_users`
--

INSERT INTO `ci_users` (`id`, `username`, `name`, `email`, `password`) VALUES
(1, 'contoh', 'contoh', 'contoh@contoh.com', 'd3425fc757e0d2a7b735bfa22ea882d9'),
(2, 'admin', 'admin', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
