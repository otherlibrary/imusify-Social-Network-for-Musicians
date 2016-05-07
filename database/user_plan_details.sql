/*
Navicat MySQL Data Transfer

Source Server         : Local Host
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : imusify_zip

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-04-16 20:01:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for user_plan_details
-- ----------------------------
DROP TABLE IF EXISTS `user_plan_details`;
CREATE TABLE `user_plan_details` (
  `id` double NOT NULL AUTO_INCREMENT,
  `userId` double NOT NULL,
  `planId` double NOT NULL,
  `subscriptionId` varchar(255) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `createdDate` datetime NOT NULL,
  `status` enum('a','c','can') NOT NULL DEFAULT 'a' COMMENT 'a = active c = completed can = cancelled',
  `amount` double NOT NULL,
  `paymentId` double NOT NULL,
  `planDetails` longtext NOT NULL,
  `discountStauts` enum('y','n') NOT NULL DEFAULT 'n',
  `currentBillingCycle` double NOT NULL,
  `numberOfBillingCycles` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_plan_details
-- ----------------------------
INSERT INTO `user_plan_details` VALUES ('2', '27', '2', '5nfsg2', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 12:54:22', 'can', '1', '2', '{\"id\":\"2\",\"planId\":\"2\",\"space\":\"5368709120\",\"can_message\":\"y\",\"frontpage\":\"n\",\"mp3_split_imusify\":\"10\",\"mp3_split_composer\":\"90\",\"licence_split_imusify\":\"40\",\"licence_split_composer\":\"60\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('3', '27', '3', 'hv6qkg', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:06:15', 'can', '3', '3', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('4', '27', '3', 'hcjktb', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:08:23', 'can', '3', '4', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('5', '27', '3', 'bsrjzg', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:36:31', 'can', '3', '5', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('6', '27', '3', '6nrf3r', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:43:36', 'can', '3', '6', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('7', '27', '3', 'j2ghcr', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:50:06', 'can', '3', '7', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('8', '27', '3', 'hjtcmm', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:52:57', 'can', '3', '8', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('9', '27', '3', '7xkhxr', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:55:11', 'can', '3', '9', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('10', '27', '3', 'h89j8w', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:57:58', 'can', '3', '10', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('11', '27', '3', 'hyrqwm', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 13:59:59', 'can', '3', '11', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('12', '27', '2', 'hyrqwm', '2016-04-15 00:00:00', '2016-05-14 00:00:00', '2016-04-15 14:03:10', 'can', '1', '12', '{\"id\":\"2\",\"planId\":\"2\",\"space\":\"5368709120\",\"can_message\":\"y\",\"frontpage\":\"n\",\"mp3_split_imusify\":\"10\",\"mp3_split_composer\":\"90\",\"licence_split_imusify\":\"40\",\"licence_split_composer\":\"60\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('13', '27', '3', '8bcsmm', '2016-04-16 00:00:00', '2016-05-15 00:00:00', '2016-04-16 08:40:32', 'can', '3', '13', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('14', '27', '3', '7c3j4b', '2016-04-16 00:00:00', '2016-05-15 00:00:00', '2016-04-16 08:55:15', 'can', '3', '14', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('15', '27', '2', 'h5fp6m', '2016-04-16 00:00:00', '2016-05-15 00:00:00', '2016-04-16 08:59:32', 'can', '1', '15', '{\"id\":\"2\",\"planId\":\"2\",\"space\":\"5368709120\",\"can_message\":\"y\",\"frontpage\":\"n\",\"mp3_split_imusify\":\"10\",\"mp3_split_composer\":\"90\",\"licence_split_imusify\":\"40\",\"licence_split_composer\":\"60\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
INSERT INTO `user_plan_details` VALUES ('16', '27', '3', 'h5fp6m', '2016-04-16 00:00:00', '2016-05-15 00:00:00', '2016-04-16 09:01:10', 'can', '3', '16', '{\"id\":\"3\",\"planId\":\"3\",\"space\":\"-1\",\"can_message\":\"y\",\"frontpage\":\"y\",\"mp3_split_imusify\":\"0\",\"mp3_split_composer\":\"100\",\"licence_split_imusify\":\"30\",\"licence_split_composer\":\"70\",\"can_vote_new_features\":\"y\",\"stats\":\"y\",\"widget\":\"y\",\"ads\":\"y\",\"aiff_wav\":\"y\",\"free_distribution\":\"y\",\"placement_opportunities\":\"y\"}', 'n', '0', '0');
