DROP TABLE IF EXISTS `t_queue_members_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_queue_members_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `sip_name` varchar(100) DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `queue_name` varchar(100) DEFAULT NULL,
  `event_data` varchar(100) DEFAULT NULL,
  `event_details` varchar(100) DEFAULT NULL,
  `session_time` int(11) DEFAULT NULL,
  `tenant_id` int(11),
  `ts_login`  timestamp  DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;
