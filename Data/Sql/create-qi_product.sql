/*
Navicat MySQL Data Transfer

Source Server         : a
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : xiaowei

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2019-04-28 21:05:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `xiaowei_product`
-- ----------------------------
DROP TABLE IF EXISTS `qi_product`;
CREATE TABLE `qi_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8 NOT NULL,
  `model` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `amount` double NOT NULL,
  `remain_amount` double NOT NULL,
  `production_date` datetime DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xiaowei_product
-- ----------------------------
