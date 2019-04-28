/*
Navicat MySQL Data Transfer

Source Server         : a
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : xiaowei

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2019-04-28 21:06:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `xiaowei_product_cost_material`
-- ----------------------------
DROP TABLE IF EXISTS `qi_product_cost_material`;
CREATE TABLE `qi_product_cost_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cost_amount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xiaowei_product_cost_material
-- ----------------------------
