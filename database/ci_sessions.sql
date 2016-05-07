/*
Navicat MySQL Data Transfer

Source Server         : Local Host
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : imusify_zip

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-04-16 20:13:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` longtext NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ci_sessions
-- ----------------------------
INSERT INTO `ci_sessions` VALUES ('9844c79adf8b8dc56cf9215de326ee2c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0', '1459067697', 'a:2:{s:9:\"user_data\";s:0:\"\";s:9:\"adminuser\";O:8:\"stdClass\":13:{s:2:\"id\";s:1:\"2\";s:5:\"email\";s:17:\"admin@imusify.com\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:32:\"21232f297a57a5a743894a0e4a801fc3\";s:8:\"usertype\";s:1:\"a\";s:10:\"role_added\";s:1:\"n\";s:11:\"profileLink\";s:5:\"admin\";s:9:\"firstname\";s:5:\"admin\";s:8:\"lastname\";s:0:\"\";s:15:\"braintreecustId\";s:0:\"\";s:11:\"avail_space\";s:8:\"10485760\";s:12:\"profileImage\";s:65:\"http://localhost/imusify/assets/images/64/64/user-profile-img.jpg\";s:8:\"loggedin\";b:1;}}');
INSERT INTO `ci_sessions` VALUES ('caf491bccb0af7f3f6c91c9c17f698ad', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0', '1458998185', 'a:2:{s:9:\"user_data\";s:0:\"\";s:9:\"adminuser\";O:8:\"stdClass\":13:{s:2:\"id\";s:1:\"2\";s:5:\"email\";s:17:\"admin@imusify.com\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:32:\"21232f297a57a5a743894a0e4a801fc3\";s:8:\"usertype\";s:1:\"a\";s:10:\"role_added\";s:1:\"n\";s:11:\"profileLink\";s:5:\"admin\";s:9:\"firstname\";s:5:\"admin\";s:8:\"lastname\";s:0:\"\";s:15:\"braintreecustId\";s:0:\"\";s:11:\"avail_space\";s:8:\"10485760\";s:12:\"profileImage\";s:65:\"http://localhost/imusify/assets/images/64/64/user-profile-img.jpg\";s:8:\"loggedin\";b:1;}}');
INSERT INTO `ci_sessions` VALUES ('dd4629247fdaf4d6c84f22d905df382e', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0', '1458998918', 'a:10:{s:9:\"user_data\";s:0:\"\";s:9:\"adminuser\";O:8:\"stdClass\":13:{s:2:\"id\";s:1:\"2\";s:5:\"email\";s:17:\"admin@imusify.com\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:32:\"21232f297a57a5a743894a0e4a801fc3\";s:8:\"usertype\";s:1:\"a\";s:10:\"role_added\";s:1:\"n\";s:11:\"profileLink\";s:5:\"admin\";s:9:\"firstname\";s:5:\"admin\";s:8:\"lastname\";s:0:\"\";s:15:\"braintreecustId\";s:0:\"\";s:11:\"avail_space\";s:8:\"10485760\";s:12:\"profileImage\";s:65:\"http://localhost/imusify/assets/images/64/64/user-profile-img.jpg\";s:8:\"loggedin\";b:1;}s:13:\"song_l1DOwf3I\";s:18:\"07. Main Title.mp3\";s:13:\"song_xAUtBuuO\";s:18:\"07. Main Title.mp3\";s:13:\"song_uWRdVFnd\";s:18:\"07. Main Title.mp3\";s:13:\"song_CpfnhpaS\";s:18:\"07. Main Title.mp3\";s:13:\"song_df22IEl9\";s:18:\"07. Main Title.mp3\";s:13:\"song_6Q6EKm2p\";s:18:\"07. Main Title.mp3\";s:13:\"song_EzlaPyhN\";s:18:\"07. Main Title.mp3\";s:5:\"album\";s:47:\"81c8f2a86a549d522f8eced9ef27917c1634533856.jpeg\";}');
INSERT INTO `ci_sessions` VALUES ('ee6e700515f2c0d4e98b4aead8527ac2', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0', '1460460501', 'a:4:{s:9:\"user_data\";s:0:\"\";s:4:\"user\";O:8:\"stdClass\":13:{s:2:\"id\";s:2:\"27\";s:5:\"email\";s:9:\"a@aaa.com\";s:8:\"username\";s:1:\"a\";s:8:\"password\";s:32:\"21232f297a57a5a743894a0e4a801fc3\";s:8:\"usertype\";s:1:\"u\";s:10:\"role_added\";s:1:\"y\";s:11:\"profileLink\";s:1:\"a\";s:9:\"firstname\";s:5:\"David\";s:8:\"lastname\";s:7:\"Walters\";s:15:\"braintreecustId\";s:8:\"10625748\";s:11:\"avail_space\";s:10:\"1073741824\";s:12:\"profileImage\";s:65:\"http://localhost/imusify/assets/images/64/64/user-profile-img.jpg\";s:8:\"loggedin\";b:1;}s:9:\"adminuser\";O:8:\"stdClass\":13:{s:2:\"id\";s:1:\"2\";s:5:\"email\";s:17:\"admin@imusify.com\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:32:\"21232f297a57a5a743894a0e4a801fc3\";s:8:\"usertype\";s:1:\"a\";s:10:\"role_added\";s:1:\"n\";s:11:\"profileLink\";s:5:\"admin\";s:9:\"firstname\";s:5:\"admin\";s:8:\"lastname\";s:5:\"admin\";s:15:\"braintreecustId\";s:0:\"\";s:11:\"avail_space\";s:8:\"10485760\";s:12:\"profileImage\";s:65:\"http://localhost/imusify/assets/images/64/64/user-profile-img.jpg\";s:8:\"loggedin\";b:1;}s:12:\"payment_info\";a:4:{s:4:\"plan\";s:7:\"Premium\";s:6:\"amount\";s:1:\"3\";s:7:\"plan_id\";s:7:\"premium\";s:2:\"id\";s:1:\"3\";}}');
INSERT INTO `ci_sessions` VALUES ('fda75ffe24c93dbba81de62476a3ea5d', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36', '1459001373', '');
