-- MySQL dump 10.13  Distrib 5.6.21-70.1, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: pomf

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(40) DEFAULT NULL,
  `orginalname` varchar(255) DEFAULT NULL,
  `filename` varchar(30) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `expire` date DEFAULT NULL,
  `delid` char(40) DEFAULT NULL,
  `user` int(11) DEFAULT '0',
  `dir` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `invites`
--

CREATE TABLE `invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT '0',
  `code` varchar(50) DEFAULT '0',
  `used` int(11) DEFAULT '0',
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(40) DEFAULT '0',
  `date` date DEFAULT NULL,
  `file` varchar(255) NOT NULL DEFAULT '0',
  `fileid` int(11) DEFAULT '0',
  `reporter` varchar(255) NOT NULL DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


-- Dump completed on 2014-12-07 19:30:13
