DROP TABLE IF EXISTS `t_sip_user_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sip_user_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `t_sip_user_id` int(11) DEFAULT NULL,
  `exten` varchar(80) NOT NULL,
  `device` varchar(100) DEFAULT NULL,
  `time_period` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;

