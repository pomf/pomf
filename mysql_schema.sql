-- MySQL dump 10.13  Distrib 5.6.21-70.1, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: pomf

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hash` char(40) default NULL,
  `originalname` varchar(255) default NULL,
  `filename` varchar(30) default NULL,
  `size` int(10) unsigned default NULL,
  `date` date default NULL,
  `expire` date default NULL,
  `delid` char(40) default NULL,
  `user` int(10) unsigned default '0',
  `dir` int(2) default '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
-- `filename` length (30) may be a bit excessive, since the average length would be smaller
-- [6 for the name + the file extension length], but since there is no limit for the file
-- extension length (at the moment, at least) let's keep it high in order to avoid problems


-- Dump completed on 2014-12-07 19:30:13
