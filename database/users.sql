/*
Navicat MySQL Data Transfer

Source Server         : Local Host
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : imusify_zip

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-04-17 13:32:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` double NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `sc_username` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `ipaddress` varchar(50) NOT NULL,
  `fp_code` varchar(255) NOT NULL,
  `gender` enum('m','f','u') NOT NULL COMMENT 'm = male,f = female,u = unspecified',
  `status` enum('y','n') NOT NULL COMMENT 'y = active,n = blocked',
  `emailverified` enum('y','n') NOT NULL DEFAULT 'n' COMMENT 'y = yes,n = not verified',
  `usertype` enum('u','a','s') NOT NULL COMMENT 'u = user,a = admin,s = sub admin',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `lastlogin` datetime NOT NULL,
  `fbverified` enum('y','n') NOT NULL,
  `linkedinverified` enum('y','n') NOT NULL COMMENT 'y = yes n = not verified',
  `twitterverified` enum('y','n') NOT NULL COMMENT 'y = yes n = not verified',
  `scverified` enum('y','n') NOT NULL COMMENT 'y = yes n = not verified',
  `fbid` varchar(100) NOT NULL,
  `linkedinid` varchar(100) NOT NULL,
  `scid` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `role_added` enum('y','n') NOT NULL DEFAULT 'n' COMMENT 'y - yes and n - no',
  `profileLink` varchar(250) DEFAULT NULL,
  `dob_m` int(2) NOT NULL,
  `dob_d` int(2) NOT NULL,
  `dob_y` int(4) NOT NULL,
  `weburl` text NOT NULL,
  `countryId` int(5) NOT NULL,
  `stateId` int(5) NOT NULL,
  `cityId` int(10) NOT NULL,
  `member_plan` enum('u','a') NOT NULL DEFAULT 'u' COMMENT 'u = user a = artist paid membership',
  `invitedFromId` double NOT NULL,
  `total_space` double NOT NULL,
  `avail_space` double NOT NULL,
  `used_space` double NOT NULL,
  `braintreecustId` varchar(255) NOT NULL,
  `stripe_connect` enum('y','n') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('2', 'admin', 'admin', 'admin', '', 'admin@imusify.com', '21232f297a57a5a743894a0e4a801fc3', '', '', '', 'm', 'y', 'n', 'a', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '', '', '', '', '', 'n', 'admin', '0', '0', '0', '', '0', '0', '0', 'u', '0', '0', '10485760', '0', '', 'n');
INSERT INTO `users` VALUES ('27', 'David', 'Walters', 'a', '', 'a@aaa.com', '21232f297a57a5a743894a0e4a801fc3', 'something something', '192.168.1.102', '', 'm', 'y', 'n', 'u', '0000-00-00 00:00:00', '2016-01-19 06:41:32', '0000-00-00 00:00:00', 'y', 'y', 'y', 'y', '', '', '', '0be5df82904d3ff779f19c77fd9c17ce', 'y', 'a', '8', '13', '1991', 'http://www.davidwalters.de', '0', '0', '0', 'a', '0', '0', '1050956200', '0', '10625748', 'y');
INSERT INTO `users` VALUES ('28', 'andy', 'admin', 'andy', '', 'andy@yahoo.com', '21232f297a57a5a743894a0e4a801fc3', '', '', '', 'm', 'y', 'n', 'u', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'y', 'y', 'y', 'y', '', '', '', '', 'y', null, '0', '0', '0', '', '0', '0', '0', 'u', '0', '0', '100000000', '0', '', 'n');
