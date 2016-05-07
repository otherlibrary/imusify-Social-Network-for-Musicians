/*
Navicat MySQL Data Transfer

Source Server         : Local Host
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : imusify_zip

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-04-17 13:30:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tracks
-- ----------------------------
DROP TABLE IF EXISTS `tracks`;
CREATE TABLE `tracks` (
  `id` double NOT NULL AUTO_INCREMENT,
  `albumId` double NOT NULL,
  `userId` double NOT NULL,
  `title` varchar(250) NOT NULL,
  `timelength` varchar(10) DEFAULT NULL,
  `bitrate` int(11) DEFAULT NULL,
  `perLink` varchar(255) NOT NULL,
  `trackName` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `release_mm` int(6) NOT NULL,
  `release_dd` int(6) NOT NULL,
  `release_yy` int(6) NOT NULL,
  `genreId` int(11) NOT NULL,
  `plays` double NOT NULL,
  `likes` double NOT NULL,
  `shares` double NOT NULL,
  `comments` double NOT NULL,
  `createdDate` datetime NOT NULL,
  `status` enum('y','n') NOT NULL DEFAULT 'y',
  `featured` enum('y','n') NOT NULL DEFAULT 'n' COMMENT 'y-yes n-no',
  `commentable` enum('y','n') NOT NULL DEFAULT 'y',
  `downloadable` enum('y','n') NOT NULL DEFAULT 'y',
  `price` double NOT NULL,
  `license` enum('y','n') NOT NULL DEFAULT 'n' COMMENT 'y-yes n-no',
  `is_sellable` enum('y','n') NOT NULL DEFAULT 'n' COMMENT 'y = selleble,n = free or not selleble',
  `track_type` enum('r','si','sp') NOT NULL COMMENT 'r = rap,si = singing,sp = speech',
  `track_musician_type` enum('m','f','b') NOT NULL COMMENT 'm = male,f = female,b = both',
  `track_nonprofit_avail` enum('y','n') NOT NULL DEFAULT 'y' COMMENT 'y = yes n = no',
  `waveGenerated` enum('y','n','r') NOT NULL DEFAULT 'n',
  `waveRunningDate` datetime NOT NULL,
  `waveCompletedDate` datetime NOT NULL,
  `isPublic` enum('y','n') NOT NULL DEFAULT 'y' COMMENT 'y = public song n = private song',
  `filesize` double NOT NULL,
  `trackuploadType` double NOT NULL,
  `track_buyable_types` varchar(255) DEFAULT NULL,
  `track_buyable_current_type` double NOT NULL DEFAULT '0',
  `usage_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `albumId` (`albumId`)  
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tracks
-- ----------------------------
