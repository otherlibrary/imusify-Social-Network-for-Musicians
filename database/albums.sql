/*
Navicat MySQL Data Transfer

Source Server         : Local Host
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : imusify_zip

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-04-17 13:32:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for albums
-- ----------------------------
DROP TABLE IF EXISTS `albums`;
CREATE TABLE `albums` (
  `id` double NOT NULL AUTO_INCREMENT,
  `userId` double NOT NULL,
  `genre` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_selleble` enum('y','n') NOT NULL DEFAULT 'n',
  `selleble_type` enum('f','p') NOT NULL DEFAULT 'p' COMMENT 'f = Full  And p = personal song',
  `price` double NOT NULL,
  `label` varchar(255) NOT NULL,
  `perLink` varchar(255) NOT NULL,
  `release_mm` int(2) NOT NULL,
  `release_dd` int(2) NOT NULL,
  `release_yy` int(4) NOT NULL,
  `status` enum('y','n') NOT NULL DEFAULT 'y',
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  CONSTRAINT `album_delete` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of albums
-- ----------------------------
