DROP TABLE IF EXISTS `t_timefilters`;
CREATE TABLE `t_timefilters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `name` varchar(80) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `time_period` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
