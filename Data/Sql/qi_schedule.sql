DROP TABLE IF EXISTS `qi_schedule`;
CREATE TABLE `qi_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  `content` text,
  `location` varchar(50) DEFAULT '',
  `priority` int(11) DEFAULT NULL,
  `actor` varchar(200) DEFAULT '',
  `user_id` int(11) DEFAULT '0',
  `start_time` datetime NOT NULL ,
  `end_time` datetime NOT NULL ,
  `add_file` varchar(200) DEFAULT '',
  `is_del` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
