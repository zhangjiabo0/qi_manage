/*
Navicat MySQL Data Transfer

Source Server         : a
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : xiaowei

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2019-04-27 16:24:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `xiaowei_stock_out_product`
-- ----------------------------
DROP TABLE IF EXISTS `xiaowei_stock_out_product`;
CREATE TABLE `xiaowei_stock_out_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_out_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of xiaowei_stock_out_product
-- ----------------------------
