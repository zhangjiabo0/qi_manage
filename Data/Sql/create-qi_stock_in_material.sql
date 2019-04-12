/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : qi_manage

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2019-04-12 09:16:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `qi_stock_in_material`
-- ----------------------------
DROP TABLE IF EXISTS `qi_stock_in_material`;
CREATE TABLE `qi_stock_in_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_in_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `unit_price` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of qi_stock_in_material
-- ----------------------------
