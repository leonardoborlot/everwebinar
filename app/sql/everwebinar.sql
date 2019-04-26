SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `everwebinar`;
CREATE DATABASE `everwebinar`;

USE `everwebinar`;

DROP TABLE IF EXISTS `Registration`;
CREATE TABLE `Registration` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned NOT NULL,
  `WebinarId` int(10) unsigned NOT NULL,
  `Promo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `UserId` (`UserId`),
  KEY `WebinarId` (`WebinarId`),
  CONSTRAINT `registration_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `User` (`Id`),
  CONSTRAINT `registration_ibfk_2` FOREIGN KEY (`WebinarId`) REFERENCES `Webinar` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(100) NOT NULL,
  `Lastname` varchar(100) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Webinar`;
CREATE TABLE `Webinar` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ApiId` varchar(20) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

