/*
Navicat MySQL Data Transfer

Source Server         : a
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : xiaowei

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2019-04-28 21:28:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `xiaowei_stock_in_material`
-- ----------------------------
DROP TABLE IF EXISTS `qi_stock_in_material`;
CREATE TABLE `qi_stock_in_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_in_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `unit` varchar(255) CHARACTER SET utf8 NOT NULL,
  `model` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `unit_price` double DEFAULT NULL,
  `remain_amount` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xiaowei_stock_in_material
-- ----------------------------
