/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50529
 Source Host           : localhost:3306
 Source Schema         : lott

 Target Server Type    : MySQL
 Target Server Version : 50529
 File Encoding         : 65001

 Date: 13/05/2021 23:09:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for person_fucai
-- ----------------------------
DROP TABLE IF EXISTS `person_fucai`;
CREATE TABLE `person_fucai`  (
  `fc_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `day_code` mediumint(8) NOT NULL COMMENT '期号',
  `red1` tinyint(2) NOT NULL,
  `red2` tinyint(2) NOT NULL,
  `red3` tinyint(2) NOT NULL,
  `red4` tinyint(2) NOT NULL,
  `red5` tinyint(2) NOT NULL,
  `red6` tinyint(2) NOT NULL,
  `blue` tinyint(2) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0互联网，1自动生成',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0未删除1已删除',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`fc_id`) USING BTREE,
  UNIQUE INDEX `彩票期号`(`day_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of person_fucai
-- ----------------------------
INSERT INTO `person_fucai` VALUES (1, 1013730, 4, 8, 19, 22, 23, 30, 15, 1, 0, 1620632703, '2021-05-10 15:45:03');
INSERT INTO `person_fucai` VALUES (2, 1016755, 2, 3, 12, 18, 20, 24, 6, 1, 0, 1620636030, '2021-05-10 16:40:30');
INSERT INTO `person_fucai` VALUES (3, 1014570, 7, 22, 25, 29, 31, 33, 5, 1, 0, 1620636031, '2021-05-10 16:40:31');

SET FOREIGN_KEY_CHECKS = 1;
