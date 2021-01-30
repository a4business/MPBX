DROP TABLE IF EXISTS `t_scheduler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tenant_id` int(11) DEFAULT NULL,
  `emails` varchar(250) DEFAULT NULL,
  `action` varchar(250) DEFAULT NULL,
  `action_params` varchar(250),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


