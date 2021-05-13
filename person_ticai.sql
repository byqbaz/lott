/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50554
 Source Host           : localhost:3306
 Source Schema         : lott

 Target Server Type    : MySQL
 Target Server Version : 50554
 File Encoding         : 65001

 Date: 10/05/2021 17:25:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for person_ticai
-- ----------------------------
DROP TABLE IF EXISTS `person_ticai`;
CREATE TABLE `person_ticai`  (
  `tc_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `day_code` mediumint(8) NOT NULL,
  `red1` tinyint(2) NOT NULL,
  `red2` tinyint(2) NOT NULL,
  `red3` tinyint(2) NOT NULL,
  `red4` tinyint(2) NOT NULL,
  `red5` tinyint(2) NOT NULL,
  `blue1` tinyint(2) NOT NULL,
  `blue2` tinyint(2) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0互联网，1自动生成',
  `is_deleted` tinyint(1) NOT NULL COMMENT '0未删除，1删除',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`tc_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of person_ticai
-- ----------------------------
INSERT INTO `person_ticai` VALUES (1, 1023043, 5, 11, 13, 21, 25, 1, 10, 1, 0, 1620632905, '2021-05-10 15:48:25');
INSERT INTO `person_ticai` VALUES (2, 1024704, 3, 7, 20, 21, 28, 6, 12, 1, 0, 1620636028, '2021-05-10 16:40:28');
INSERT INTO `person_ticai` VALUES (3, 1027226, 4, 5, 9, 20, 21, 2, 11, 1, 0, 1620636029, '2021-05-10 16:40:29');

SET FOREIGN_KEY_CHECKS = 1;
