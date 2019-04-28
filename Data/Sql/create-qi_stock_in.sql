/*
Navicat MySQL Data Transfer

Source Server         : a
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : xiaowei

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2019-04-28 21:18:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `xiaowei_stock_in`
-- ----------------------------
DROP TABLE IF EXISTS `qi_stock_in`;
CREATE TABLE `qi_stock_in` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no` varchar(255) NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created_time` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xiaowei_stock_in
-- ----------------------------
