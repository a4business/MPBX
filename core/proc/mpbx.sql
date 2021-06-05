-- MySQL dump 10.13  Distrib 5.6.36, for Linux (x86_64)
--
-- Host: localhost    Database: mpbx
-- ------------------------------------------------------
-- Server version	5.6.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_user_log`
--

DROP TABLE IF EXISTS `admin_user_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user_log` (
  `user_id` int(11) DEFAULT NULL,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_agent` varchar(200) DEFAULT NULL,
  `method` varchar(60) DEFAULT NULL,
  `request_data` varchar(250) DEFAULT NULL,
  `from_ip` varchar(100) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16248 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_user_roles`
--

DROP TABLE IF EXISTS `admin_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user_roles` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(20) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `default_tenant_id` int(10) DEFAULT NULL,
  `role` int(10) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT '2018-01-01 05:00:00',
  `allowed_sections` varchar(250) DEFAULT NULL,
  `last_login_ip` varchar(100) DEFAULT NULL,
  `gui_style` varchar(100) DEFAULT 'EnterpriseBlue',
  `email` varchar(150) DEFAULT '',
  `user_fname` varchar(100) DEFAULT NULL,
  `user_lname` varchar(100) DEFAULT NULL,
  `sip_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blacklist`
--

DROP TABLE IF EXISTS `blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_info` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `hit_count` int(11) DEFAULT NULL,
  `last_hit` datetime DEFAULT NULL,
  `redirect_to` varchar(250) DEFAULT NULL,
  `block_sip_registration` tinyint(4) DEFAULT '1',
  `block_web_access` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `callgates`
--

DROP TABLE IF EXISTS `callgates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `callgates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calling` varchar(100) DEFAULT NULL,
  `called` varchar(200) DEFAULT NULL,
  `DID` varchar(100) DEFAULT NULL,
  `life_time` int(11) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cscart_orders`
--

DROP TABLE IF EXISTS `cscart_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cscart_orders` (
  `user_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dids`
--

DROP TABLE IF EXISTS `dids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DID` varchar(100) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `assigned_destination` varchar(100) DEFAULT '(none)',
  `description` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=505 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etor_users`
--

DROP TABLE IF EXISTS `etor_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etor_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `allowed_tabs` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feature_codes`
--

DROP TABLE IF EXISTS `feature_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feature_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `subtype` tinyint(1) DEFAULT '0',
  `description` varchar(128) NOT NULL DEFAULT '',
  `context` varchar(128) NOT NULL DEFAULT '',
  `exten` varchar(128) NOT NULL DEFAULT '',
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `app` varchar(128) NOT NULL DEFAULT '',
  `appdata` varchar(1024) NOT NULL DEFAULT '',
  `about` text,
  PRIMARY KEY (`context`,`exten`,`priority`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mtree`
--

DROP TABLE IF EXISTS `mtree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mtree` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `name` varchar(100) DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `nodeIcon` varchar(100) DEFAULT NULL,
  `visible` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=511 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `routes_list`
--

DROP TABLE IF EXISTS `routes_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `routes_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) DEFAULT NULL,
  `dial_pattern` varchar(100) DEFAULT NULL,
  `length` varchar(100) DEFAULT NULL,
  `strip` int(11) DEFAULT NULL,
  `add_prefix` varchar(100) DEFAULT NULL,
  `trunk_id` int(11) DEFAULT NULL,
  `trunk2_id` int(11) DEFAULT NULL,
  `time_rule` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sip_conf`
--

DROP TABLE IF EXISTS `sip_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sip_conf` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_metric` int(11) NOT NULL DEFAULT '0',
  `var_metric` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(128) DEFAULT 'sip.conf',
  `category` varchar(128) DEFAULT 'general',
  `var_name` varchar(128) NOT NULL,
  `var_val` varchar(128) NOT NULL,
  `commented` smallint(6) NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1132 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `speech`
--

DROP TABLE IF EXISTS `speech`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `speech` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `message` varchar(1000) DEFAULT NULL,
  `uniqueid` varchar(50) DEFAULT NULL,
  `confidence` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `speech_suggest`
--

DROP TABLE IF EXISTS `speech_suggest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `speech_suggest` (
  `suggestion` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_campaign_leads`
--

DROP TABLE IF EXISTS `t_campaign_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_campaign_leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `t_campaign_id` int(11) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `result` varchar(40) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `last_called` datetime DEFAULT NULL,
  `api_url` varchar(200) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `phone_field_idx` int(11) DEFAULT NULL,
  `field1` varchar(250) DEFAULT '',
  `field2` varchar(250) DEFAULT '',
  `field3` varchar(250) DEFAULT '',
  `field4` varchar(250) DEFAULT '',
  `field5` varchar(250) DEFAULT '',
  `field6` varchar(250) DEFAULT '',
  `field7` varchar(250) DEFAULT '',
  `field8` varchar(250) DEFAULT '',
  `field9` varchar(250) DEFAULT '',
  `field10` varchar(250) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `t_campaign_leads_phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=227688 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_campaign_leads_tmp`
--

DROP TABLE IF EXISTS `t_campaign_leads_tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_campaign_leads_tmp` (
  `id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `t_campaign_id` int(11) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `result` varchar(40) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `last_called` datetime DEFAULT NULL,
  `api_url` varchar(200) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `phone_field_idx` int(11) DEFAULT NULL,
  `field1` varchar(250) DEFAULT '',
  `field2` varchar(250) DEFAULT '',
  `field3` varchar(250) DEFAULT '',
  `field4` varchar(250) DEFAULT '',
  `field5` varchar(250) DEFAULT '',
  `field6` varchar(250) DEFAULT '',
  `field7` varchar(250) DEFAULT '',
  `field8` varchar(250) DEFAULT '',
  `field9` varchar(250) DEFAULT '',
  `field10` varchar(250) DEFAULT '',
  KEY `t_campaign_leads_tmp_phone` (`phone`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_campaigns`
--

DROP TABLE IF EXISTS `t_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `default_action` varchar(100) DEFAULT NULL,
  `default_action_data` varchar(100) DEFAULT NULL,
  `max_active_calls` int(11) DEFAULT '0',
  `current_calls` int(11) DEFAULT NULL,
  `leads_total` int(11) DEFAULT '0',
  `leads_dialed` int(11) DEFAULT '0',
  `leads_answered` int(11) DEFAULT '0',
  `campaign_status` enum('RUNNING','PAUSED','STOPPED') DEFAULT 'STOPPED',
  `lead_field_names` varchar(300) DEFAULT NULL,
  `phone_field_idx` int(11) DEFAULT NULL,
  `max_call_duration` int(11) DEFAULT NULL,
  `call_timeout` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_cdrs`
--

DROP TABLE IF EXISTS `t_cdrs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_cdrs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `tenant_id` int(11) DEFAULT NULL,
  `calldate` datetime DEFAULT '2018-01-01 00:00:00',
  `clid` varchar(80) NOT NULL DEFAULT '',
  `src` varchar(80) NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `dcontext` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `lastapp` varchar(80) NOT NULL DEFAULT '',
  `lastdata` varchar(80) NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  `linkedid` varchar(32) DEFAULT NULL,
  `userfield` varchar(255) NOT NULL DEFAULT '',
  `rate` decimal(8,2) DEFAULT NULL,
  `recording` varchar(250) DEFAULT NULL,
  `did` varchar(60) DEFAULT NULL,
  `routing_info` varchar(60) DEFAULT NULL,
  `from_ip` varchar(60) DEFAULT NULL,
  `recvip` varchar(60) DEFAULT NULL,
  `rtpsource` varchar(60) DEFAULT NULL,
  `rtpdest` varchar(60) DEFAULT NULL,
  `peername` varchar(60) DEFAULT NULL,
  `service_status` varchar(200) DEFAULT NULL,
  `served` varchar(50) DEFAULT NULL,
  `direction` varchar(100) DEFAULT NULL,
  `tags` varchar(500) DEFAULT '',
  `INBOUND_DID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`),
  KEY `src` (`src`),
  KEY `disposition` (`disposition`),
  KEY `uniqueid` (`uniqueid`),
  KEY `t_cdrs_tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28515 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_cdrs_bi` BEFORE INSERT ON `t_cdrs` FOR EACH ROW
BEGIN
 DECLARE the_tenant_ref varchar(60);

 if ( NEW.userfield != '') then
     SET the_tenant_ref = SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 1),':',-1 );
     if ( EXISTS( SELECT 1 FROM tenants WHERE ref_id = the_tenant_ref) ) then
       SET NEW.tenant_id = (SELECT id FROM tenants  WHERE ref_id = the_tenant_ref );
     end if;
     SET NEW.did =     SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 2),'::',-1 ); 
     SET NEW.from_ip = SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 3),'::',-1 );
     SET NEW.recvip =  SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 4),'::',-1 );
     SET NEW.rtpsource=SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 5),'::',-1 );
     SET NEW.rtpdest = SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 6),'::',-1 );
     SET NEW.peername =SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 7),'::',-1 );
 end if;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `t_cdrs_archive`
--

DROP TABLE IF EXISTS `t_cdrs_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_cdrs_archive` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `tenant_id` int(11) DEFAULT NULL,
  `calldate` datetime DEFAULT '2018-01-01 00:00:00',
  `clid` varchar(80) NOT NULL DEFAULT '',
  `src` varchar(80) NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `dcontext` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `lastapp` varchar(80) NOT NULL DEFAULT '',
  `lastdata` varchar(80) NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  `linkedid` varchar(32) DEFAULT NULL,
  `userfield` varchar(255) NOT NULL DEFAULT '',
  `rate` decimal(8,2) DEFAULT NULL,
  `recording` varchar(250) DEFAULT NULL,
  `did` varchar(60) DEFAULT NULL,
  `routing_info` varchar(60) DEFAULT NULL,
  `from_ip` varchar(60) DEFAULT NULL,
  `recvip` varchar(60) DEFAULT NULL,
  `rtpsource` varchar(60) DEFAULT NULL,
  `rtpdest` varchar(60) DEFAULT NULL,
  `peername` varchar(60) DEFAULT NULL,
  `service_status` varchar(200) DEFAULT NULL,
  `served` varchar(50) DEFAULT NULL,
  `direction` varchar(100) DEFAULT NULL,
  `tags` varchar(500) DEFAULT '',
  `INBOUND_DID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`),
  KEY `src` (`src`),
  KEY `disposition` (`disposition`),
  KEY `uniqueid` (`uniqueid`),
  KEY `t_cdrs_tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_conferences`
--

DROP TABLE IF EXISTS `t_conferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_conferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `conference` varchar(255) NOT NULL,
  `enable_moh` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `enable_menu` tinyint(4) DEFAULT NULL,
  `users` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `enable_recording` tinyint(4) DEFAULT NULL,
  `announce_count` tinyint(1) NOT NULL DEFAULT '1',
  `moh_class` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `maxusers` int(4) DEFAULT NULL,
  `talker_optimization` tinyint(1) NOT NULL DEFAULT '1',
  `description` varchar(255) NOT NULL DEFAULT '',
  `announce_join` tinyint(1) NOT NULL DEFAULT '1',
  `other` varchar(255) NOT NULL DEFAULT '',
  `wait_marked` tinyint(1) NOT NULL DEFAULT '1',
  `end_marked` tinyint(1) NOT NULL DEFAULT '1',
  `detect_talker` tinyint(1) NOT NULL DEFAULT '1',
  `admin_password` varchar(255) NOT NULL DEFAULT '',
  `options` varchar(10) NOT NULL DEFAULT '',
  `announcement_file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_extensions`
--

DROP TABLE IF EXISTS `t_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `subtype` tinyint(1) DEFAULT '0',
  `description` varchar(128) NOT NULL DEFAULT '',
  `context` varchar(128) NOT NULL DEFAULT '',
  `exten` varchar(128) NOT NULL DEFAULT '',
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `app` varchar(128) NOT NULL DEFAULT '',
  `appdata` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`context`,`exten`,`priority`),
  UNIQUE KEY `id` (`id`),
  KEY `tenantid` (`tenant_id`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=551 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_inbound`
--

DROP TABLE IF EXISTS `t_inbound`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_inbound` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `did_id` varchar(80) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `is_enabled` int(11) DEFAULT '1',
  `tag` varchar(200) DEFAULT NULL,
  `context_script` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_inbound_rules`
--

DROP TABLE IF EXISTS `t_inbound_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_inbound_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `inbound_id` int(11) NOT NULL,
  `timefilter_id` int(11) DEFAULT NULL,
  `action` varchar(20) DEFAULT NULL,
  `opt1` varchar(50) DEFAULT NULL,
  `opt2` varchar(50) DEFAULT NULL,
  `opt3` varchar(50) DEFAULT NULL,
  `opt4` varchar(50) DEFAULT NULL,
  `opt5` varchar(50) DEFAULT NULL,
  `opt6` varchar(50) DEFAULT NULL,
  `info` text,
  `destination` varchar(300) DEFAULT NULL,
  `week_day_from` enum('mon','tue','web','thu','fri','sat','sun') DEFAULT 'mon',
  `week_day_to` enum('mon','tue','web','thu','fri','sat','sun') DEFAULT 'mon',
  `day_time_from` time DEFAULT NULL,
  `day_time_to` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122247 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_ivrmenu`
--

DROP TABLE IF EXISTS `t_ivrmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_ivrmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `announcement_type` enum('recording','tts','moh') DEFAULT 'recording',
  `announcement` text,
  `announcement_lang` varchar(100) DEFAULT 'en-US_MichaelVoice',
  `moh_class` varchar(100) DEFAULT NULL,
  `voicemail_box` varchar(100) DEFAULT NULL,
  `recordings_lang` varchar(100) DEFAULT NULL,
  `menu_timeout` int(11) DEFAULT NULL,
  `ring_while_wait` enum('yes','no') DEFAULT 'yes',
  `delay_before_start` int(11) DEFAULT NULL,
  `allow_dialing_exten` enum('yes','no') DEFAULT 'no',
  `allow_dialing_featurecode` enum('yes','no') DEFAULT 'no',
  `allow_dialing_external` enum('yes','no') DEFAULT 'no',
  `digit_timeout` int(11) DEFAULT NULL,
  `context_script` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=239 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_ivrmenu_items`
--

DROP TABLE IF EXISTS `t_ivrmenu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_ivrmenu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t_ivrmenu_id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `selection` varchar(5) DEFAULT NULL,
  `destination` varchar(50) DEFAULT NULL,
  `announcement_type` varchar(20) DEFAULT NULL,
  `announcement` varchar(120) DEFAULT NULL,
  `announcement_lang` varchar(30) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `item_action` varchar(50) DEFAULT NULL,
  `item_data` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=232 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_moh`
--

DROP TABLE IF EXISTS `t_moh`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_moh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `directory` varchar(255) NOT NULL DEFAULT '',
  `application` varchar(255) NOT NULL DEFAULT '',
  `mode` varchar(80) NOT NULL DEFAULT 'files',
  `digit` char(1) NOT NULL DEFAULT '',
  `sort` varchar(16) NOT NULL DEFAULT '',
  `format` varchar(16) NOT NULL DEFAULT '',
  `tenant_id` int(11) DEFAULT NULL,
  `announcement` varchar(100) DEFAULT NULL,
  `network_media_url` varchar(150) DEFAULT NULL,
  `directory_2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_moh_bu` BEFORE UPDATE ON `t_moh` FOR EACH ROW
BEGIN

  IF NEW.mode = 'custom' AND NEW.directory != ''
   THEN
    SET NEW.directory_2 = NEW.directory,   
        NEW.directory = '';
   END IF;
   
  IF NEW.mode = 'files' AND NEW.directory = ''  THEN
    if NEW.directory_2 != '' THEN
      SET NEW.directory = NEW.directory_2;
    ELSE 
      SET NEW.directory = NEW.name;     
    END IF   ;    
 END IF;
   

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `t_pagegroup_members`
--

DROP TABLE IF EXISTS `t_pagegroup_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_pagegroup_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `membername` varchar(40) DEFAULT NULL,
  `pagegroup_id` int(11) DEFAULT NULL,
  `interface` varchar(128) DEFAULT NULL,
  `paused` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=342 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_pagegroups`
--

DROP TABLE IF EXISTS `t_pagegroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_pagegroups` (
  `name` varchar(128) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `announce` varchar(128) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pg_extension` varchar(60) DEFAULT NULL,
  `full_duplex` tinyint(4) DEFAULT '0',
  `no_beep` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_queue_members`
--

DROP TABLE IF EXISTS `t_queue_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_queue_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `membername` varchar(40) DEFAULT NULL,
  `queue_name` varchar(128) DEFAULT NULL,
  `interface` varchar(128) DEFAULT NULL,
  `penalty` varchar(3) DEFAULT '',
  `paused` varchar(4) DEFAULT 'no',
  `uniqueid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `queue_interface` (`queue_name`,`interface`)
) ENGINE=MyISAM AUTO_INCREMENT=450 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_bi` BEFORE INSERT ON `t_queue_members` FOR EACH ROW
BEGIN
  
 INSERT INTO t_queue_members_log(event_type,event_data,queue_name,sip_name,tenant_id)
     VALUES('QLogin', 'Member LOGIN', NEW.queue_name, NEW.interface, NEW.tenant_id);

  SET NEW.uniqueid = LAST_INSERT_ID();
  SET NEW.uniqueid =  (SELECT AUTO_INCREMENT FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 't_queue_members');

  
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_bu` BEFORE UPDATE ON `t_queue_members` FOR EACH ROW
BEGIN
  	
	IF NEW.paused = 0  
	THEN
	  INSERT INTO t_queue_members_log(event_type,event_data,queue_name,sip_name,tenant_id)
	     VALUES( 'QLogin', 'Member LOGIN',NEW.queue_name,NEW.interface,NEW.tenant_id);
	ELSE 
	  INSERT INTO t_queue_members_log(event_type,event_data,queue_name,sip_name,tenant_id)
	     VALUES( 'QLogout', 'Member LOGOUT',NEW.queue_name,NEW.interface,NEW.tenant_id);
	END IF;

	

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_on_delete` AFTER DELETE ON t_queue_members
 FOR EACH ROW
 BEGIN
  
  INSERT INTO t_queue_members_log(event_type,event_data,queue_name,sip_name,tenant_id)
     VALUES('QLogout','Member logout', OLD.queue_name, OLD.interface, OLD.tenant_id );
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `t_queue_members_log`
--

DROP TABLE IF EXISTS `t_queue_members_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_queue_members_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sip_name` varchar(100) DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `queue_name` varchar(100) DEFAULT NULL,
  `event_data` varchar(100) DEFAULT NULL,
  `event_details` varchar(100) DEFAULT NULL,
  `session_time` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `ts_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `break_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_log_bi` BEFORE INSERT ON `t_queue_members_log` FOR EACH ROW
BEGIN
  
  DECLARE last_ts timestamp;
  DECLARE last_event varchar(100);


  IF NEW.event_type = 'QLogout' 
   THEN
    
    SELECT ts, event_type INTO last_ts, last_event
          FROM t_queue_members_log
          WHERE sip_name = NEW.sip_name AND
                queue_name = NEW.queue_name AND
		            event_type = 'QLogin'
          ORDER by ts desc LIMIT 1 ; 
    
    IF( last_event = 'QLogin'  ) 
    THEN
       SET NEW.event_details =  concat(ifnull(NEW.event_details,' '), 'since:[',last_ts,']'  );
       SET NEW.session_time =   timestampdiff(SECOND, last_ts, now() );      
       SET NEW.ts_login =   last_ts;      
    ELSE
      SET NEW.event_details = ' LOGOUT WITHOUT LOGIN !! Ignore calculation';
      SET NEW.session_time = 0;
    END IF; 

  END IF;



END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `t_queues`
--

DROP TABLE IF EXISTS `t_queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_queues` (
  `name` varchar(128) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `musiconhold` varchar(128) DEFAULT 'default',
  `announce` varchar(128) DEFAULT NULL,
  `context` varchar(128) DEFAULT NULL,
  `timeout` int(11) DEFAULT '60',
  `monitor_join` tinyint(1) DEFAULT NULL,
  `monitor_format` varchar(128) DEFAULT NULL,
  `queue_youarenext` varchar(128) DEFAULT NULL,
  `queue_thereare` varchar(128) DEFAULT NULL,
  `queue_callswaiting` varchar(128) DEFAULT NULL,
  `queue_holdtime` varchar(128) DEFAULT NULL,
  `queue_minutes` varchar(128) DEFAULT NULL,
  `queue_seconds` varchar(128) DEFAULT NULL,
  `queue_lessthan` varchar(128) DEFAULT NULL,
  `queue_thankyou` varchar(128) DEFAULT NULL,
  `queue_reporthold` varchar(128) DEFAULT NULL,
  `announce_frequency` int(11) DEFAULT NULL,
  `announce_round_seconds` int(11) DEFAULT NULL,
  `announce_holdtime` varchar(128) DEFAULT NULL,
  `periodic_announce` varchar(128) DEFAULT NULL,
  `periodic_announce_frequency` int(11) DEFAULT NULL,
  `retry` int(11) DEFAULT '5',
  `ringinuse` varchar(5) NOT NULL DEFAULT 'no',
  `autofill` varchar(5) NOT NULL DEFAULT 'yes',
  `autopause` varchar(5) NOT NULL DEFAULT 'no',
  `setinterfacevar` varchar(5) NOT NULL DEFAULT 'yes',
  `wrapuptime` int(11) DEFAULT '30',
  `maxlen` int(11) DEFAULT NULL,
  `servicelevel` int(11) DEFAULT NULL,
  `strategy` varchar(128) DEFAULT 'ringall',
  `joinempty` varchar(128) DEFAULT 'no',
  `leavewhenempty` varchar(128) DEFAULT 'yes',
  `eventmemberstatus` tinyint(1) DEFAULT NULL,
  `eventwhencalled` tinyint(1) DEFAULT NULL,
  `reportholdtime` varchar(5) DEFAULT 'yes',
  `memberdelay` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `timeoutrestart` varchar(5) DEFAULT 'yes',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_welcome` varchar(128) DEFAULT NULL,
  `default_action` varchar(100) DEFAULT NULL,
  `default_action_data` varchar(100) DEFAULT NULL,
  `context_script` varchar(100) DEFAULT '',
  `stats_email` varchar(150) DEFAULT NULL,
  `qlabel` varchar(150) DEFAULT NULL,
  `queue_calltag` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=291 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queues_bu` BEFORE UPDATE ON `t_queues` FOR EACH ROW
BEGIN

  IF NEW.name != OLD.name
   THEN
   UPDATE t_queue_members 
      SET  queue_name = NEW.name
     WHERE  queue_name = OLD.name  AND
	    t_queue_members.tenant_id = NEW.tenant_id;
   END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `t_ringgroup_lists`
--

DROP TABLE IF EXISTS `t_ringgroup_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_ringgroup_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `t_ringgroups_id` int(11) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `announcement_type` enum('tts','recording','none') DEFAULT 'none',
  `announcement_file` varchar(30) DEFAULT NULL,
  `announcement_txt` text,
  `announcement_txt_lang` varchar(100) DEFAULT 'en-US_Michael',
  `phone_numbers` varchar(300) DEFAULT NULL,
  `extensions` varchar(300) DEFAULT '',
  `timeout` int(11) DEFAULT NULL,
  `group_type` enum('queue','extension','ringgroup') DEFAULT 'extension',
  `extension_method` enum('exten_based','device_based') DEFAULT 'device_based',
  `ignore_redirects` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=179 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_ringgroups`
--

DROP TABLE IF EXISTS `t_ringgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_ringgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `default_action` varchar(100) DEFAULT NULL,
  `default_action_data` varchar(100) DEFAULT NULL,
  `announcement_file` varchar(200) DEFAULT NULL,
  `callername_prefix` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1010 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_route`
--

DROP TABLE IF EXISTS `t_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `route_enabled` tinyint(4) DEFAULT NULL,
  `work_hours` varchar(100) DEFAULT NULL,
  `work_days` varchar(100) DEFAULT NULL,
  `fialover_trunk` int(11) DEFAULT NULL,
  `max_call_time` int(11) DEFAULT NULL,
  `outbound_callerid` varchar(30) DEFAULT NULL,
  `context_script` varchar(250) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_scheduler`
--

DROP TABLE IF EXISTS `t_scheduler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tenant_id` int(11) DEFAULT NULL,
  `emails` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_params` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_sent` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_shifts`
--

DROP TABLE IF EXISTS `t_shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `send_to_email` varchar(250) DEFAULT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `last_sent` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_sip_user_devices`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_sip_users`
--

DROP TABLE IF EXISTS `t_sip_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_sip_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `secret` varchar(80) NOT NULL,
  `transport` varchar(31) DEFAULT 'udp',
  `host` varchar(31) DEFAULT 'dynamic',
  `context` varchar(80) DEFAULT 'default',
  `deny` varchar(95) DEFAULT NULL,
  `permit` varchar(95) DEFAULT NULL,
  `md5secret` varchar(80) DEFAULT NULL,
  `remotesecret` varchar(250) DEFAULT NULL,
  `nat` varchar(20) DEFAULT 'force_rport,comedia',
  `type` enum('user','peer','friend') NOT NULL DEFAULT 'friend',
  `accountcode` varchar(20) DEFAULT NULL,
  `amaflags` varchar(13) DEFAULT NULL,
  `callgroup` varchar(10) DEFAULT NULL,
  `callerid` varchar(80) DEFAULT NULL,
  `defaultip` varchar(15) DEFAULT NULL,
  `dtmfmode` varchar(7) DEFAULT NULL,
  `fromuser` varchar(80) DEFAULT NULL,
  `fromdomain` varchar(80) DEFAULT NULL,
  `insecure` varchar(50) DEFAULT NULL,
  `language` char(2) DEFAULT NULL,
  `mailbox` varchar(50) DEFAULT NULL,
  `pickupgroup` varchar(10) DEFAULT NULL,
  `qualify` char(3) DEFAULT NULL,
  `regexten` varchar(80) DEFAULT NULL,
  `rtptimeout` char(3) DEFAULT NULL,
  `rtpholdtimeout` char(3) DEFAULT NULL,
  `setvar` varchar(300) DEFAULT NULL,
  `disallow` varchar(100) DEFAULT 'all',
  `allow` varchar(50) NOT NULL DEFAULT 'ulaw;alaw;gsm',
  `fullcontact` varchar(250) DEFAULT NULL,
  `ipaddr` varchar(45) DEFAULT NULL,
  `port` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(80) NOT NULL DEFAULT '',
  `defaultuser` varchar(80) NOT NULL DEFAULT '',
  `subscribecontext` varchar(80) DEFAULT NULL,
  `directmedia` enum('yes','no') DEFAULT NULL,
  `trustrpid` enum('yes','no') DEFAULT NULL,
  `sendrpid` enum('yes','no') DEFAULT NULL,
  `progressinband` enum('never','yes','no') DEFAULT NULL,
  `callingpres` enum('allowed_not_screened','allowed_passed_screen','allowed_failed_screen','allowed','prohib_not_screened','prohib_passed_screen','prohib_failed_screen','prohib','unavailable') DEFAULT 'allowed_not_screened',
  `promiscredir` enum('yes','no') DEFAULT NULL,
  `useclientcode` enum('yes','no') DEFAULT NULL,
  `callcounter` enum('yes','no') DEFAULT 'yes',
  `busylevel` int(10) unsigned DEFAULT NULL,
  `allowoverlap` enum('yes','no') DEFAULT 'yes',
  `allowsubscribe` enum('yes','no') DEFAULT 'yes',
  `allowtransfer` enum('yes','no') DEFAULT 'yes',
  `ignoresdpversion` enum('yes','no') DEFAULT 'no',
  `videosupport` enum('yes','no','always') DEFAULT 'no',
  `maxcallbitrate` int(10) unsigned DEFAULT NULL,
  `rfc2833compensate` enum('yes','no') DEFAULT 'yes',
  `session-timers` enum('originate','accept','refuse') DEFAULT 'accept',
  `session-expires` int(5) unsigned DEFAULT '1800',
  `session-minse` int(5) unsigned DEFAULT '90',
  `session-refresher` enum('uac','uas') DEFAULT 'uas',
  `t38pt_usertpsource` enum('yes','no') DEFAULT NULL,
  `outboundproxy` varchar(250) DEFAULT NULL,
  `callbackextension` varchar(250) DEFAULT NULL,
  `registertrying` enum('yes','no') DEFAULT 'yes',
  `timert1` int(5) unsigned DEFAULT '500',
  `timerb` int(8) unsigned DEFAULT NULL,
  `qualifyfreq` int(5) unsigned DEFAULT '120',
  `contactpermit` varchar(250) DEFAULT NULL,
  `contactdeny` varchar(250) DEFAULT NULL,
  `lastms` int(11) DEFAULT '0',
  `regserver` varchar(100) NOT NULL DEFAULT '',
  `regseconds` int(11) NOT NULL DEFAULT '0',
  `useragent` varchar(50) NOT NULL DEFAULT '',
  `avpf` varchar(10) DEFAULT 'no',
  `force_rport` varchar(30) DEFAULT 'yes',
  `email` varchar(50) DEFAULT NULL,
  `register` varchar(10) DEFAULT NULL,
  `canreinvite` varchar(10) DEFAULT 'no',
  `extension` varchar(30) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `email_pager` varchar(100) DEFAULT NULL,
  `other_options` text,
  `vmexten` varchar(100) DEFAULT NULL,
  `enable_mwi` enum('yes','no') DEFAULT 'yes',
  `outbound_route` int(11) DEFAULT '1',
  `did_id` int(11) DEFAULT NULL,
  `mohsuggest` varchar(30) DEFAULT NULL,
  `mohinterpret` varchar(30) DEFAULT NULL,
  `parkinglot` varchar(100) DEFAULT NULL,
  `outbound_callerid` varchar(50) DEFAULT NULL,
  `outbound_callername` varchar(50) DEFAULT NULL,
  `internal_callerid` varchar(50) DEFAULT NULL,
  `internal_callername` varchar(50) DEFAULT NULL,
  `first_name` varchar(60) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `icesupport` varchar(10) DEFAULT 'yes',
  `encryption` varchar(10) DEFAULT 'no',
  `force_avp` varchar(10) DEFAULT 'no',
  `dtlsenable` varchar(10) DEFAULT 'no',
  `dtlsfingerprint` varchar(10) DEFAULT 'sha-1',
  `dtlsverify` varchar(50) NOT NULL DEFAULT 'no',
  `dtlscertfile` varchar(255) DEFAULT '/etc/asterisk/keys/TLS.pem',
  `dtlscafile` varchar(255) DEFAULT '/etc/asterisk/keys/fullchain.pem',
  `dtlssetup` varchar(30) NOT NULL DEFAULT 'actpass',
  `click2dial_enabled` varchar(5) DEFAULT NULL,
  `click2dial_url` varchar(200) DEFAULT '',
  `click2dial_exten` varchar(50) DEFAULT NULL,
  `click2talk_enabled` varchar(10) DEFAULT '0',
  `click2talk_options` varchar(500) DEFAULT NULL,
  `rtcp_mux` varchar(10) DEFAULT 'yes',
  `namedcallgroup` varchar(100) DEFAULT NULL,
  `namedpickupgroup` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sip_name` (`name`),
  UNIQUE KEY `tenant_exten` (`tenant_id`,`extension`)
) ENGINE=MyISAM AUTO_INCREMENT=224 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_sip_users_bi` BEFORE INSERT ON `t_sip_users` FOR EACH ROW
BEGIN
 DECLARE next_pbx_id integer; 

 if ( NEW.tenant_id != '') then
    SET NEW.subscribecontext = (SELECT concat('internal-', ref_id, '-BLF') FROM tenants  WHERE id = NEW.tenant_id );
    SET NEW.language = (SELECT ifnull(sounds_language,'en') FROM tenants WHERE id = NEW.tenant_id );
    SET NEW.namedpickupgroup = (SELECT ref_id FROM tenants WHERE id = NEW.tenant_id );
    SET NEW.namedcallgroup = (SELECT ref_id FROM tenants WHERE id = NEW.tenant_id );
    if( EXISTS( SELECT 1 FROM tenants WHERE id = NEW.tenant_id AND ifnull(encrypt_sip_secrets,0) = 1 ) ) then
      SET NEW.md5secret =  md5(concat(NEW.name,':asterisk:',NEW.secret));
      SET NEW.secret= '[encrypted]';
    end if;
 end if;

  if( EXISTS( SELECT 1 FROM blacklist WHERE ip = NEW.ipaddr AND ifnull(NEW.ipaddr,'') != '' AND block_sip_registration = 1) ) then
    SET NEW.host = 'local';
  end if;
 

  SET next_pbx_id = ( SELECT pbx_item_id + 1 FROM tenants LIMIT 1 );
     
  IF next_pbx_id < (SELECT max(id)+1 FROM t_sip_users) 
   THEN
     SET next_pbx_id = (SELECT max(id)+1 FROM t_sip_users);
   ELSE
     SET NEW.id = next_pbx_id;
   END IF;
  UPDATE tenants SET pbx_item_id = next_pbx_id  ;

 
 
 SET NEW.mohsuggest = NEW.mohinterpret ;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_sip_users_bu` BEFORE UPDATE ON `t_sip_users` FOR EACH ROW
BEGIN

 IF  ifnull(NEW.first_name,'') != ''  OR ifnull(NEW.last_name,'') != ''  
  THEN
    IF  EXISTS(SELECT 1 FROM t_vmusers WHERE  tenant_id = NEW.tenant_id AND mailbox = NEW.extension AND ifnull(fullname,'') = '' ) 
     THEN
       UPDATE t_vmusers 
          SET fullname = concat( ifnull(NEW.first_name,''),' ', ifnull(NEW.last_name,'') )
            WHERE tenant_id = NEW.tenant_id AND mailbox = NEW.extension ;
     END IF;
  END IF;


 SET NEW.mohsuggest = NEW.mohinterpret ;
  if( NEW.secret != '[encrypted]' AND  EXISTS( SELECT 1 FROM tenants WHERE id = NEW.tenant_id AND ifnull(encrypt_sip_secrets,0) = 1 ) ) 
    then
      SET NEW.md5secret =  md5(concat(NEW.name,':asterisk:',NEW.secret));
      SET NEW.secret= '[encrypted]';
    else
      SET NEW.md5secret = '';
    end if;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_sip_users_on_delete` AFTER DELETE ON t_sip_users
 FOR EACH ROW
 BEGIN
   DELETE FROM `t_vmusers`
     WHERE t_vmusers.tenant_id = old.tenant_id AND
           t_vmusers.mailbox = old.extension;

   DELETE FROM `t_user_options` 
     WHERE t_sip_user_id = old.id;

   DELETE FROM `t_queue_members` 
     WHERE `t_queue_members`.tenant_id = old.tenant_id AND
	   concat('SIP/',old.name) = `t_queue_members`.interface;
  
 END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `t_timefilters`
--

DROP TABLE IF EXISTS `t_timefilters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_timefilters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `name` varchar(80) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `time_period` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_blocklist`
--

DROP TABLE IF EXISTS `t_user_blocklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_blocklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `t_sip_user_id` int(11) DEFAULT NULL,
  `callerid` varchar(80) NOT NULL,
  `allowed` tinyint(1) DEFAULT '0',
  `hit_counter` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_followme`
--

DROP TABLE IF EXISTS `t_user_followme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_followme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `t_sip_user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `timeout` tinyint(1) DEFAULT '0',
  `phonenumber` varchar(200) DEFAULT NULL,
  `ordinal` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_options`
--

DROP TABLE IF EXISTS `t_user_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `t_sip_user_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `shadow_email` varchar(255) NOT NULL DEFAULT '',
  `callerid` varchar(255) DEFAULT NULL,
  `callerid_override` varchar(255) DEFAULT NULL,
  `emergency_callerid` varchar(255) NOT NULL DEFAULT '',
  `emergency_trunk` varchar(255) NOT NULL DEFAULT '',
  `ext` varchar(255) NOT NULL DEFAULT '',
  `forwardpager` tinyint(1) NOT NULL DEFAULT '0',
  `crm_cidstrip` tinyint(1) NOT NULL DEFAULT '0',
  `crm_use_defaults` tinyint(1) NOT NULL DEFAULT '1',
  `crm_url_format` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `phones` varchar(255) NOT NULL DEFAULT '',
  `callerid_option` tinyint(1) DEFAULT NULL,
  `crm_cidminlength` tinyint(1) NOT NULL DEFAULT '0',
  `did` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `tag` varchar(255) NOT NULL DEFAULT '',
  `user_name_alias` varchar(255) DEFAULT NULL,
  `dialbyname_override` varchar(255) DEFAULT NULL,
  `forwardemail` tinyint(1) NOT NULL DEFAULT '0',
  `create_conference` tinyint(1) DEFAULT NULL,
  `callerid_name_option` tinyint(1) DEFAULT NULL,
  `dialbyname_option` tinyint(1) DEFAULT NULL,
  `pager` varchar(255) NOT NULL DEFAULT '',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `mailbox` varchar(255) NOT NULL DEFAULT '',
  `forwarddelete` tinyint(1) NOT NULL DEFAULT '0',
  `vm_settings` tinyint(1) NOT NULL DEFAULT '1',
  `call_forwarding` varchar(30) DEFAULT NULL,
  `call_forward_tag` varchar(30) DEFAULT NULL,
  `call_forward_timeout` int(11) DEFAULT NULL,
  `call_forward_onbusy` varchar(20) DEFAULT NULL,
  `call_screening` tinyint(1) NOT NULL DEFAULT '1',
  `call_screening_ask_cname` int(11) DEFAULT '1',
  `call_screening_ask_cid` int(11) DEFAULT '1',
  `call_blocking` tinyint(1) NOT NULL DEFAULT '1',
  `call_blocking_anonym` tinyint(1) DEFAULT '0',
  `call_blocking_local` tinyint(1) DEFAULT '0',
  `call_blocking_mode` tinyint(1) DEFAULT '0',
  `call_followme_ringmethod` tinyint(4) DEFAULT '0',
  `call_followme_options` varchar(20) DEFAULT NULL,
  `call_followme_status` tinyint(4) DEFAULT '0',
  `call_followme_ontimeout` varchar(100) DEFAULT NULL,
  `call_followme_ontimeout_var` varchar(20) DEFAULT NULL,
  `call_waiting` tinyint(4) DEFAULT NULL,
  `allowrecording` tinyint(1) NOT NULL DEFAULT '1',
  `contacts` tinyint(1) NOT NULL DEFAULT '1',
  `vm` tinyint(1) NOT NULL DEFAULT '1',
  `recording_listen` tinyint(1) NOT NULL DEFAULT '1',
  `set_musiconhold` tinyint(1) NOT NULL DEFAULT '1',
  `call_history` tinyint(1) NOT NULL DEFAULT '1',
  `send_faxes` tinyint(1) DEFAULT NULL,
  `set_officemode` tinyint(1) NOT NULL DEFAULT '0',
  `integrations` tinyint(1) NOT NULL DEFAULT '1',
  `preferences` tinyint(1) NOT NULL DEFAULT '1',
  `contactphone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `pin` varchar(255) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `crm_scope` varchar(255) NOT NULL DEFAULT 'crmapi',
  `context` varchar(100) DEFAULT NULL,
  `pls_hold_prompt` varchar(40) DEFAULT 'followme/pls-hold-while-try',
  `call_forward_preserve_cid` tinyint(4) DEFAULT '1',
  `call_recording` int(11) DEFAULT '0',
  `dnd` varchar(5) DEFAULT '0',
  `rtcp_mux` varchar(50) DEFAULT 'yes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Ony_one_user_sip_option_allowed` (`t_sip_user_id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_screening`
--

DROP TABLE IF EXISTS `t_user_screening`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_screening` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `t_sip_user_id` int(11) DEFAULT NULL,
  `callerid` varchar(80) NOT NULL,
  `screened` tinyint(1) DEFAULT '0',
  `hit_counter` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_vmusers`
--

DROP TABLE IF EXISTS `t_vmusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_vmusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueid` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `context` varchar(80) NOT NULL,
  `mailbox` varchar(30) NOT NULL,
  `password` varchar(80) DEFAULT NULL,
  `fullname` varchar(80) DEFAULT NULL,
  `email` varchar(80) NOT NULL DEFAULT '',
  `shadow_email` varchar(80) NOT NULL DEFAULT '',
  `pager` varchar(80) NOT NULL DEFAULT '',
  `shadow_pager` varchar(80) NOT NULL DEFAULT '',
  `tz` varchar(80) DEFAULT NULL,
  `attach` varchar(4) DEFAULT NULL,
  `saycid` varchar(4) DEFAULT NULL,
  `dialout` varchar(80) DEFAULT NULL,
  `callback` varchar(80) DEFAULT NULL,
  `review` varchar(4) DEFAULT NULL,
  `operator` varchar(4) DEFAULT 'yes',
  `envelope` varchar(4) DEFAULT NULL,
  `sayduration` varchar(4) DEFAULT NULL,
  `saydurationm` tinyint(4) DEFAULT NULL,
  `sendvoicemail` varchar(4) NOT NULL DEFAULT 'no',
  `delete` varchar(4) DEFAULT NULL,
  `nextaftercmd` varchar(4) DEFAULT NULL,
  `forcename` varchar(4) DEFAULT NULL,
  `forcegreetings` varchar(4) DEFAULT NULL,
  `hidefromdir` varchar(4) NOT NULL DEFAULT 'no',
  `maxmsg` int(5) DEFAULT NULL,
  `maxsecs` int(5) DEFAULT NULL,
  `entered_other` varchar(2048) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vm_timeout` int(11) DEFAULT NULL,
  `operator_exten` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mailbox_context` (`mailbox`,`context`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_vmusers_bi` BEFORE INSERT ON `t_vmusers` FOR EACH ROW
BEGIN
   DECLARE tenant varchar(100);
  IF IFNULL(NEW.tenant_id,0) > 0 
   THEN
     SET tenant = (SELECT ref_id FROM tenants WHERE id = NEW.tenant_id LIMIT 1);
     SET NEW.context = CONCAT(tenant,'-vmdefault');
   END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `tenant_shiftreports`
--

DROP TABLE IF EXISTS `tenant_shiftreports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenant_shiftreports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `send_to_email` varchar(120) DEFAULT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_id` varchar(50) DEFAULT NULL,
  `extensions_count_limit` int(11) DEFAULT '0',
  `extensions_count` int(11) DEFAULT '0',
  `parkext` varchar(100) DEFAULT NULL,
  `parkext_announce` varchar(50) DEFAULT NULL,
  `parkpos` int(11) DEFAULT NULL,
  `parkfindslot` enum('next','first') DEFAULT 'next',
  `parkingtime` int(11) DEFAULT NULL,
  `parkcomebacktoorigin` enum('yes','no') DEFAULT 'no',
  `parkedmusicclass` varchar(100) DEFAULT NULL,
  `sounds_language` varchar(10) DEFAULT NULL,
  `general_error_message` varchar(200) DEFAULT NULL,
  `general_invalid_message` varchar(200) DEFAULT NULL,
  `outbound_callerid` varchar(100) DEFAULT NULL,
  `outbound_callername` varchar(100) DEFAULT NULL,
  `logo_image` varchar(100) DEFAULT NULL,
  `default_call_recording` int(11) DEFAULT '2',
  `default_tts_lang` varchar(100) DEFAULT NULL,
  `active_calls` int(11) DEFAULT '0',
  `active_calls_limit` int(11) DEFAULT '0',
  `enable_status_subscription` tinyint(4) DEFAULT NULL,
  `paging_retry_count` int(11) DEFAULT '5',
  `paging_interval` int(11) DEFAULT '30',
  `parked_ontimeout_ivr` varchar(40) DEFAULT NULL,
  `days_to_keep_recs` int(11) DEFAULT '180',
  `encrypt_sip_secrets` tinyint(4) DEFAULT '0',
  `pbx_item_id` int(11) DEFAULT '1',
  `vm_operator_exten` varchar(100) DEFAULT NULL,
  `shabash` varchar(20) DEFAULT '18:00',
  `intertenant_routing` varchar(100) DEFAULT NULL,
  `archivate_cdrs_after` int(11) DEFAULT '90',
  `smtp_host` varchar(200) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `smtp_user` varchar(200) DEFAULT NULL,
  `smtp_password` varchar(200) DEFAULT NULL,
  `smtp_from` varchar(200) DEFAULT NULL,
  `smtp_from_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_tenants_on_delete` AFTER DELETE ON tenants
 FOR EACH ROW
  BEGIN
   DELETE FROM `t_vmusers`
     WHERE t_vmusers.tenant_id = old.id ;

   DELETE FROM `t_sip_users`
     WHERE t_sip_users.tenant_id = old.id ;

  END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `ru` text,
  `en` text,
  `ua` text,
  `tr` text,
  `it` text,
  `hi` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=331 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trunks`
--

DROP TABLE IF EXISTS `trunks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trunks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `trunk_type` varchar(50) DEFAULT 'peer',
  `description` varchar(200) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `trunk_options` text,
  `context` varchar(100) DEFAULT NULL,
  `sip_register` varchar(100) DEFAULT NULL,
  `qualify` int(11) DEFAULT NULL,
  `dial_timeout` int(11) DEFAULT NULL,
  `dial_options` varchar(200) DEFAULT NULL,
  `defaultuser` varchar(100) DEFAULT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `max_concurrent_calls` int(11) DEFAULT '0',
  `domain` varchar(50) DEFAULT NULL,
  `max_call_duration` int(11) DEFAULT '0',
  `inTenants` varchar(500) DEFAULT NULL,
  `other_options` text,
  `register` varchar(200) DEFAULT NULL,
  `auth_user` varchar(100) DEFAULT NULL,
  `md5secret` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_trunks_bi` BEFORE INSERT ON `trunks` FOR EACH ROW
BEGIN
   if(  NEW.secret != '' ) THEN
      SET NEW.md5secret =  md5(concat(NEW.defaultuser,':asterisk:',NEW.secret));
      SET NEW.secret= '[encrypted]';
   END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_trunks_bu` BEFORE UPDATE ON `trunks` FOR EACH ROW
BEGIN
  if(  NEW.secret != '[encrypted]' ) THEN
    SET NEW.md5secret =  md5(concat(NEW.defaultuser,':asterisk:',NEW.secret));
    SET NEW.secret= '[encrypted]';
  END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping routines for database 'mpbx'
--
/*!50003 DROP PROCEDURE IF EXISTS `get_callblocking` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_callblocking`(IN user_in VARCHAR(64),IN inbound_cid VARCHAR(60))
BEGIN
 declare BLOCK INT DEFAULT 0;
 declare BLOCK_MODE INT DEFAULT 0;
 declare BLOCK_INFO VARCHAR(100);
 declare DEFAULT_POLICY TINYINT DEFAULT 0;
 declare BLOCK_ANONYM TINYINT DEFAULT 1;
 declare IS_ANONYM TINYINT DEFAULT 0;
 declare MATCHED_CALLER VARCHAR(60);
 declare IS_MATCHED_ALLOWED TINYINT DEFAULT 1;

  SELECT call_blocking_mode, call_blocking, call_blocking_anonym, if(inbound_cid = '' OR inbound_cid = 'anonymous',TRUE,FALSE), ubl.callerid, ubl.allowed
         into
         BLOCK_MODE, DEFAULT_POLICY, BLOCK_ANONYM, IS_ANONYM,  MATCHED_CALLER, IS_MATCHED_ALLOWED
     FROM t_user_options as u
            LEFT JOIN t_user_blocklist ubl ON ubl.t_sip_user_id = u.t_sip_user_id AND ubl.callerid = inbound_cid
    WHERE u.id = user_in ;

    set BLOCK = DEFAULT_POLICY;
    set BLOCK_INFO = concat('CALL BLOCK MODE:',BLOCK);

    IF ( IS_ANONYM AND BLOCK_ANONYM = 1  ) then
         set BLOCK =1;
         set BLOCK_INFO = concat(" ->CALL BLOCKED: ANONYM [ from:", inbound_cid," ],which Called UID:",user_in);
      end if;
 
    IF ( DEFAULT_POLICY = 0 AND IS_MATCHED_ALLOWED != 1 ) then
        set BLOCK = 1;
        set BLOCK_INFO = concat("CALL BLOCKED MATCHED callerid[",inbound_cid,"=",MATCHED_CALLER,"], wich not allowed");
      end if;
   

    IF( DEFAULT_POLICY = 1 AND IS_MATCHED_ALLOWED = 1 ) then
        set BLOCK = 0;
        set BLOCK_INFO = concat("CALL Allowed BY MATCHED callerid[", inbound_cid, "=", MATCHED_CALLER, "]");
      end if;


  SELECT BLOCK, BLOCK_MODE, BLOCK_INFO;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_callfollowme` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_callfollowme`(IN user_in INT,IN inbound_cid VARCHAR(60))
BEGIN

 declare FOLLOWME VARCHAR(60) DEFAULT 0;
 declare FOLLOWME_ID VARCHAR(20);
 declare FOLLOWME_OPTS VARCHAR(60);
 declare FWD_KEEP_CID varchar(10) DEFAULT '0';
 declare FWD_TAG VARCHAR(10);
 declare FOLLOWME_ONTIMEOUT VARCHAR(30);
 declare FOLLOWME_ONTIMEOUT_VAR VARCHAR(30);

 SELECT  call_followme_status, name,
         concat(if(instr(call_followme_options,'a'),'a',''),if(instr(call_followme_options,'s'),'s',''),if(instr(call_followme_options,'n'),'n','')),
         ifnull(call_forward_tag,''),call_forward_preserve_cid, call_followme_ontimeout,call_followme_ontimeout_var
         into
         FOLLOWME, FOLLOWME_ID, FOLLOWME_OPTS, FWD_TAG, FWD_KEEP_CID, FOLLOWME_ONTIMEOUT, FOLLOWME_ONTIMEOUT_VAR
     FROM t_user_options
    WHERE t_user_options.id = user_in ;

    
 
 SELECT FOLLOWME, FOLLOWME_ID,FOLLOWME_OPTS,FWD_TAG,FWD_KEEP_CID,FOLLOWME_ONTIMEOUT,FOLLOWME_ONTIMEOUT_VAR;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_callforwarding` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_callforwarding`(IN user_in INT,IN inbound_cid VARCHAR(60))
BEGIN

 declare FORWARD VARCHAR(60) DEFAULT 0;
 declare FORWARD_TO VARCHAR(60) DEFAULT "0";
 declare FORWARD_ONBUSY VARCHAR(60) DEFAULT "0";
 declare FORWARD_TIMEOUT INT DEFAULT 0;
 declare FWD_TAG VARCHAR(60);
 declare FWD_KEEP_CID VARCHAR(4) DEFAULT '0';
 declare FORWARD_INFO VARCHAR(250) DEFAULT "no Forward";

 SELECT  ifnull(call_forwarding,0),ifnull(call_forward_onbusy,0),
         ifnull(call_forward_timeout,0),ifnull(call_forward_tag,''), call_forward_preserve_cid
         into
         FORWARD_TO, FORWARD_ONBUSY, FORWARD_TIMEOUT, FWD_TAG,FWD_KEEP_CID
     FROM t_user_options
    WHERE t_user_options.id = user_in ;

 set FORWARD_INFO = concat(' FORWARDING:',IF(FORWARD_TO=1,'TO-VMAIL',FORWARD_TO),' ON BUSY:',IF(FORWARD_ONBUSY=1,'TO-VMAIL',FORWARD_ONBUSY),' TIMEOUT:',FORWARD_TIMEOUT);
 
 SELECT FORWARD_TO,FORWARD_ONBUSY,FORWARD_TIMEOUT,FWD_TAG,FORWARD_INFO,FWD_KEEP_CID ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_callrecording` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_callrecording`(IN user_in INT,IN inbound_cid VARCHAR(60))
BEGIN
declare RECORD tinyint DEFAULT 0;
declare DEFAULT_RECORDING tinyint DEFAULT 2;
declare RECORD_INFO VARCHAR(60) DEFAULT '';

  SELECT ifnull(call_recording,0),ifnull(default_call_recording,2)
   INTO  RECORD,DEFAULT_RECORDING
  FROM t_user_options, tenants
  WHERE t_user_options.tenant_id = tenants.id AND
        t_user_options.id = user_in ;
      
        
  IF( RECORD = 0 ) then
    SET RECORD = ifnull(DEFAULT_RECORDING,2);
    SET RECORD_INFO = concat(RECORD_INFO,'(PBX Default)');
  end if;
  
  SET RECORD_INFO = concat('RECORD CALLS:', if(RECORD=1,' ALWAYS ',''), if(RECORD=2,' NEVER ',''), if(RECORD=3,' ON-DEMAND ',''),RECORD_INFO );
  
  SELECT RECORD,RECORD_INFO;
  

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_callscreening` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_callscreening`(IN user_in INT,IN inbound_cid VARCHAR(60),IN inbound_cname VARCHAR(60))
BEGIN

 declare SCREENED INT DEFAULT 0;
 declare SCREEN_CID_MODE INT DEFAULT 1;     
 declare SCREEN_ASK_CID INT DEFAULT 0;      

 declare SCREEN_CNAME_MODE INT DEFAULT 1;   
 declare SCREEN_ASK_CNAME TINYINT DEFAULT 0;        

 declare DEFAULT_SCREENING INT DEFAULT 0;
 declare SCREEN_INFO VARCHAR(100);
 declare EXTRA_INFO VARCHAR(100);
 declare IS_ANONYM_CID TINYINT DEFAULT 0;
 declare IS_ANONYM_CNAME TINYINT DEFAULT 0;
 declare MATCHED_CALLER VARCHAR(60) ;
 declare IS_MATCHED_SCREENED TINYINT DEFAULT 1;

  SELECT call_screening, call_screening_ask_cname, call_screening_ask_cid,
         if(inbound_cid = '' OR inbound_cid = 'anonymous',TRUE,FALSE),
         if(inbound_cname = '' OR inbound_cname = 'anonymous',TRUE,FALSE),
         usl.callerid, usl.screened
         into
         DEFAULT_SCREENING, SCREEN_CNAME_MODE, SCREEN_CID_MODE,
         IS_ANONYM_CID, IS_ANONYM_CNAME,
         MATCHED_CALLER, IS_MATCHED_SCREENED
     FROM t_user_options as u
            LEFT JOIN t_user_screening usl ON usl.t_sip_user_id = usl.t_sip_user_id AND usl.callerid = inbound_cid
    WHERE u.id = user_in ;


    set SCREENED = DEFAULT_SCREENING;
    set SCREEN_INFO = concat(' SCREENINIG[',SCREENED,']');

   IF( (SCREENED != 1) AND  MATCHED_CALLER != ''  AND (IS_MATCHED_SCREENED = 1) ) then
       set SCREENED = 1;
       set EXTRA_INFO = concat(" SCREENING[MATCHED CALLER[",inbound_cid,"] ENFORCED] ");
   end if;


    IF( MATCHED_CALLER != '' AND (IS_MATCHED_SCREENED != 1) ) then
           set SCREENED = 0;
           set SCREEN_ASK_CID = 0;
           set SCREEN_ASK_CNAME = 0;
           set SCREEN_INFO = concat(" SCREENING[ DISABLED FOR MATCHED CALLER[",inbound_cid,"] ]");
    end if;


    IF ( SCREENED ) then
         
         IF( SCREEN_CID_MODE = 2 AND IS_ANONYM_CID ) then
           set SCREEN_ASK_CID = 1;
           set SCREEN_INFO = concat("  ASKING CID FOR ANONYM [ ", inbound_cid," ] while calling",user_in);
         end if;

         IF( SCREEN_CID_MODE = 1 ) then
           set SCREEN_ASK_CID = 1;
           set SCREEN_INFO = "  ALWAYS ASK CID ";  
         end if;

         IF( SCREEN_CID_MODE = 0 ) then
           set SCREEN_ASK_CID = 0;
           set SCREEN_INFO = "  NEVER  ASK CID";
         end if;

         IF( SCREEN_CNAME_MODE = 2 AND IS_ANONYM_CNAME ) then
           set SCREEN_ASK_CNAME = 1;
           set SCREEN_INFO = concat(SCREEN_INFO,"  ASKING CNAME (ANONYM/EMPTY) [ ", inbound_cname," ] FOR uid:",user_in);
         end if;

         IF( SCREEN_CNAME_MODE = 1 ) then
           set SCREEN_ASK_CNAME = 1;
           set SCREEN_INFO = concat(SCREEN_INFO,"  ALWAYS ASK CNAME "); 
         end if;

         IF( SCREEN_CNAME_MODE = 0 ) then
           set SCREEN_ASK_CNAME = 0;
           set SCREEN_INFO = concat(SCREEN_INFO, "  NEVER ASK CNAME ");
         end if;

   
   end if;



  SELECT SCREENED, SCREEN_ASK_CID, SCREEN_ASK_CNAME, concat(ifnull(EXTRA_INFO,''),ifnull(SCREEN_INFO,'')) as SCREEN_INFO;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_conferenceinfo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_conferenceinfo`(IN conference_id VARCHAR(64))
BEGIN

SELECT
         status,
         moh_class as music_on_hold_class,
         maxusers as max_members,
         password as userpin,
         admin_password as adminpin,
        if(enable_moh = 1, 'yes', 'no') as music_on_hold_when_empty,
        if(enable_menu = 1, 'yes', 'no') as enable_menu,
        if(enable_recording = 1, 'yes', 'no') as record_conference,
        if(announce_count = 1, 'yes', 'no') as announce_user_count,
        if(detect_talker = 1, 'yes', 'no') as talk_detection_events,
        if(talker_optimization = 1, 'yes', 'no') as talker_optimization,
        if(announce_join = 1, 'yes', 'no') as announce_join_leave,
        if(wait_marked = 1, 'yes', 'no') as wait_marked,
        if(end_marked = 1, 'yes', 'no') as end_marked,
        options,
	if(announcement_file = '0','none',announcement_file) as announcement_file,
	ref_id as tenant
        FROM t_conferences, tenants 
        WHERE tenants.id = t_conferences.tenant_id AND
              t_conferences.id = conference_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_pagegroup` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pagegroup`(IN in_pg_access_number VARCHAR(64), IN in_caller_num VARCHAR(60))
BEGIN

  SELECT concat( group_concat(interface SEPARATOR '&'), 
                 IF(t_pagegroups.full_duplex = 1  OR t_pagegroups.no_beep = 1 ,'|','') , 
                 IF(t_pagegroups.full_duplex = 1 ,'d',''), 
                 IF(t_pagegroups.no_beep = 1 ,'q','') )
        AS PG_EXTEN,
        tenants.id as PG_TENANT_ID
  FROM t_pagegroups, t_pagegroup_members,tenants
    WHERE t_pagegroups.pg_extension = replace(in_pg_access_number,'#','') AND 
          t_pagegroups.tenant_id = tenants.id AND  
          t_pagegroups.id = t_pagegroup_members.pagegroup_id AND
          interface != concat('SIP/',in_caller_num); 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_pbxitem` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pbxitem`(IN action VARCHAR(164),IN item_id VARCHAR(220), IN in_tenant_ref VARCHAR(70) )
BEGIN
 declare PBX_ITEM VARCHAR(220);
 declare PBX_ITEM2 VARCHAR(30);
 declare PBX_ITEM3 VARCHAR(30);

    IF( action = 'conference' ) then
      SELECT conference INTO  PBX_ITEM FROM t_conferences WHERE id = item_id;
    end if;

    IF ( action = 'disa' ) then
      SELECT item_id INTO PBX_ITEM;
    end if;

    IF ( action = 'userlogonpass' ) then
      SELECT password , 'hotdesk-enabled' INTO PBX_ITEM , PBX_ITEM2
      FROM t_vmusers, tenants  
        WHERE
          t_vmusers.tenant_id = tenants.id AND
          tenants.ref_id = in_tenant_ref AND
          mailbox = item_id ;
    end if;    

    IF( action = 'number' ) then
     SELECT item_id INTO PBX_ITEM;
    end if;

    IF( action = 'park_announce_rec' ) then
       SELECT ifnull(paging_retry_count,5) as paging_retry_count,  ifnull(paging_interval,30) as paging_interval, ifnull(parked_ontimeout_ivr,'') as ivr_ontimeout INTO PBX_ITEM, PBX_ITEM2, PBX_ITEM3 FROM tenants WHERE ref_id = in_tenant_ref;
    end if;  
  
    IF( action = 'queue' ) then
      SELECT name, timeout  INTO  PBX_ITEM,  PBX_ITEM2 FROM t_queues WHERE id = item_id;
    end if;

   
    IF( action = 'voicemail' ) then
      SELECT concat(mailbox,'@',context) INTO  PBX_ITEM FROM t_vmusers WHERE id = item_id;
    end if;
 
    IF( action = 'extension' ) then
      SELECT concat(extension,'@internal-',ref_id,'-local') INTO  PBX_ITEM FROM t_sip_users,tenants WHERE t_sip_users.tenant_id = tenants.id AND t_sip_users.id = item_id;
    end if;
   
    IF( action ='ivrmenu' ) then
        SELECT concat('s@internal-',ref_id,'-ivrmenu-',item_id) INTO  PBX_ITEM FROM t_ivrmenu,tenants WHERE t_ivrmenu.tenant_id = tenants.id AND t_ivrmenu.id = item_id;
    end if;

    IF( action ='hrstatus' ) then       
        SELECT CASE WHEN  IFNULL( event_type ,'off' ) = 'hrlogon' THEN 'ON' ELSE 'OFF' END 
        INTO PBX_ITEM
        FROM t_queue_members_log,tenants
            WHERE ref_id = in_tenant_ref AND
		  tenants.id = t_queue_members_log.tenant_id AND
                  sip_name = item_id
        ORDER BY ts DESC LIMIT 1;
    end if;
  

  SELECT PBX_ITEM, PBX_ITEM2;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_tenant` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_tenant`(IN in_refid VARCHAR(64))
BEGIN

  SELECT active_calls_limit as IN_LIMIT,
         active_calls_limit as OUT_LIMIT,
         active_calls as IN_CURRENT,
         active_calls as OUT_CURRENT,
         if( active_calls > active_calls_limit AND active_calls_limit != 0, active_calls - active_calls_limit,0) AS IN_OVER_LIMIT,
         if( active_calls > active_calls_limit AND active_calls_limit != 0, active_calls - active_calls_limit,0) AS OUT_OVER_LIMIT,
	 default_call_recording as DEF_RECORDING,
         vm_operator_exten AS VM_OPER_EXTEN

  FROM tenants 
      WHERE ref_id = in_refid;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_userinfo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_userinfo`(IN user_in VARCHAR(64))
BEGIN



  SELECT if(ifnull(call_recording,0) = 0,default_call_recording,call_recording) as RECORD,
       sip.last_name,sip.first_name,sip.internal_callername,sip.internal_callerid,sip.outbound_callername,
        sip.outbound_callerid,
        sip.mohinterpret,sip.mohsuggest,sip.did_id,sip.outbound_route,sip.tenant_id,
        sip.extension,
        sip.username,sip.callerid,sip.type,
        sip.context,sip.host,sip.name,sip.id,
        ifnull((SELECT group_concat(concat('SIP/',name) SEPARATOR '&') as HD
           FROM t_sip_user_devices,t_sip_users 
           WHERE t_sip_user_devices.exten = t_sip_users.extension AND
                 t_sip_user_devices.tenant_id = t_sip_users.tenant_id AND
                 t_sip_user_id = sip.id AND
                 t_sip_user_devices.exten != sip.extension
           GROUP BY t_sip_user_id ),'none') as hotDesk,
         t.ref_id  as TENANT,
         o.id as user_id,
         ifnull(vm_timeout,60) as VMTIMEOUT,
         ifnull(o.call_waiting,0) as CALL_WAITING
  FROM t_sip_users as sip
        LEFT JOIN t_user_options as o ON sip.id = o.t_sip_user_id  AND  sip.tenant_id = o.tenant_id
        LEFT JOIN t_vmusers as vmail  ON sip.extension = vmail.mailbox AND sip.tenant_id = vmail.tenant_id,
       tenants as t
  WHERE sip.name = user_in AND
        sip.tenant_id = t.id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `set_user_option` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `set_user_option`(IN in_tenant_name varchar(100),IN in_exten VARCHAR(100), IN in_opt_name VARCHAR(60), IN in_opt_val VARCHAR(200) )
BEGIN

 declare IN_TENANT_ID INTEGER DEFAULT 0;
 declare SIP_ID INTEGER DEFAULT 0;
 declare SIP_NAME VARCHAR(100) DEFAULT '';
 declare QNAME_BYLABLE VARCHAR(100) DEFAULT '';
 declare _last_ts timestamp DEFAULT null;
 declare _last_event varchar(100) DEFAULT '';

 
   

 SELECT id INTO IN_TENANT_ID
   FROM tenants WHERE ref_id = in_tenant_name
  LIMIT 1;

IF( in_opt_val != "1" ) THEN
 SELECT name INTO QNAME_BYLABLE
   FROM t_queues 
    WHERE tenant_id = IN_TENANT_ID AND
         qlabel = in_opt_val
  LIMIT 1;
ELSE
  SET QNAME_BYLABLE = "1";
END IF;  

 

 SELECT id,name  INTO SIP_ID,SIP_NAME
   FROM t_sip_users WHERE  (extension = in_exten AND tenant_id = IN_TENANT_ID)
 LIMIT 1;

 IF ( in_opt_name = 'dnd' ) then
  UPDATE t_user_options SET dnd = in_opt_val
    WHERE t_sip_user_id = SIP_ID ;
 END IF;
 

  IF ( in_opt_name = 'queue_logon' ) then
   IF ( in_opt_val = "1" ) THEN
     INSERT INTO t_queue_members(tenant_id,membername,queue_name,interface)
      SELECT IN_TENANT_ID, SIP_NAME, name, concat('SIP/',SIP_NAME)   
        FROM t_queues WHERE 
          t_queues.tenant_id = IN_TENANT_ID AND
          t_queues.name NOT IN(SELECT queue_name FROM t_queue_members 
                                      WHERE tenant_id = IN_TENANT_ID AND
                                            interface = concat('SIP/',SIP_NAME) ) ;
     
   ELSE
     
       IF ( !EXISTS( SELECT 1 FROM t_queue_members WHERE tenant_id =  IN_TENANT_ID AND 
                               queue_name  = QNAME_BYLABLE AND 
                               interface = concat('SIP/',SIP_NAME) ) )
         THEN  
           INSERT INTO t_queue_members(tenant_id,membername,queue_name,interface)
           VALUES( IN_TENANT_ID, SIP_NAME, QNAME_BYLABLE, concat('SIP/',SIP_NAME) );
        END IF;    
     
   END IF;

    
 END IF;


 IF ( in_opt_name = 'queue_logoff' ) then

      DELETE FROM t_queue_members 
              WHERE tenant_id =  IN_TENANT_ID AND 
                   ( queue_name  = QNAME_BYLABLE OR (QNAME_BYLABLE = '1' AND queue_name  != '') ) AND 
                   interface = concat('SIP/',SIP_NAME) ;
 END IF;



 IF ( in_opt_name = 'queue_pause' ) then
  UPDATE t_queue_members SET paused = 0
    WHERE tenant_id =  IN_TENANT_ID AND
     queue_name  = QNAME_BYLABLE AND
     interface = concat('SIP/',SIP_NAME) ;
 END IF;

 IF ( in_opt_name = 'queue_unpause' ) then
  UPDATE t_queue_members SET paused = 1
    WHERE tenant_id =  IN_TENANT_ID AND
     queue_name  = QNAME_BYLABLE AND
     interface = concat('SIP/',SIP_NAME) ;
 END IF;

 IF ( in_opt_name = 'queues_unpause' ) then
  UPDATE t_queue_members SET paused = 0
    WHERE tenant_id =  IN_TENANT_ID AND
     interface = concat('SIP/',SIP_NAME) ;
 END IF;

  IF ( in_opt_name = 'queues_pause' ) then
  UPDATE t_queue_members SET paused = 1
    WHERE tenant_id =  IN_TENANT_ID AND
     interface = concat('SIP/',SIP_NAME) ;
 END IF;

 

 IF ( in_opt_name = 'userlogon' ) then
   IF ( !EXISTS(SELECT 1 FROM t_sip_user_devices WHERE tenant_id = IN_TENANT_ID AND t_sip_user_id = SIP_ID AND exten = in_opt_val) ) THEN
    INSERT INTO t_sip_user_devices(tenant_id,t_sip_user_id,exten)  VALUES( IN_TENANT_ID, SIP_ID, in_opt_val );
   END IF;
 END IF;

 IF ( in_opt_name = 'userlogoff' ) then
    DELETE FROM t_sip_user_devices 
      WHERE tenant_id = IN_TENANT_ID  AND
            ( exten =  in_opt_val OR 
	      sip_user_id IN (SELECT id FROM t_sip_users WHERE tenant_id = IN_TENANT_ID AND extension = in_opt_val) ) ;   
    
 END IF;


 

  IF ( in_opt_name = 'hrlogon' ) THEN
    SET _last_ts = now();
    SET _last_event = 'First_Logon';
    SELECT t_queue_members_log.ts, t_queue_members_log.event_type 
        INTO _last_ts, _last_event
      FROM t_queue_members_log
            WHERE tenant_id = IN_TENANT_ID AND
		  t_queue_members_log.sip_name = in_exten AND
		  datediff( ts, now() ) = 0
            ORDER BY t_queue_members_log.ts DESC LIMIT 1;

       IF ( ifnull(_last_event,'')  != 'hrlogon'  )  THEN 
            INSERT INTO t_queue_members_log(event_type, event_data, queue_name, event_details, break_time, session_time,`sip_name`, tenant_id)
              VALUES('hrlogon', 'HR-LOGIN Event', 'PBX-Office-HR', 
                      concat('HR LOGGED IN!, Break Time was:' , timestampdiff(SECOND, _last_ts, now() ), ' since :[' ,_last_ts, '] for: ' , in_exten, '  lastEven:', _last_event ),
                      timestampdiff(SECOND, _last_ts, now() ),
		      0,
		      in_exten, 
		      IN_TENANT_ID
                   );
       END IF;    
  END IF;

 IF ( in_opt_name = 'hrlogoff' ) THEN 

   SELECT t_queue_members_log.ts, t_queue_members_log.event_type 
    	INTO _last_ts, _last_event
  	FROM t_queue_members_log
            WHERE
  		tenant_id = IN_TENANT_ID AND
  		t_queue_members_log.sip_name = in_exten AND
		datediff( ts,now() ) = 0
   	 ORDER BY t_queue_members_log.ts DESC LIMIT 1;

    
    IF( _last_event  = 'hrlogon'  ) 
    THEN
       INSERT INTO t_queue_members_log(event_type, event_data, queue_name, event_details, session_time, break_time, sip_name, tenant_id)
         VALUES('hrlogoff', 'HR-LOGOUT Event', 'PBX-Office-HR',  
                concat('Working Time: ', timestampdiff(SECOND, _last_ts, now() ), ' since:[',_last_ts,'] for: ' , in_exten,'Blast:',_last_event ),
                timestampdiff(SECOND, _last_ts, now() ),
		0,
                in_exten,
                IN_TENANT_ID);            
    ELSE
      INSERT INTO t_queue_members_log(event_type, event_data, queue_name, event_details, session_time, break_time, `sip_name`, tenant_id)
         VALUES('hrlogoff', 'HR-LOGOUT Event', 'PBX-Office-HR',  
                concat('LOGOUT WITHOUT LAST LOGIN !! Ignore calculation for "',in_exten,'" NO login  event FOR TODAY! ten_id:', IN_TENANT_ID, ' ',ifnull(_last_event,'no last') ),
                0,
		0,
                in_exten,
                IN_TENANT_ID);            
    END IF; 

 END IF;





END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `set_user_options` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `set_user_options`(IN in_tenant_name varchar(100),IN in_exten VARCHAR(100), IN in_opt_name VARCHAR(60), IN in_opt_val VARCHAR(200) )
BEGIN

 declare TENANT_ID INTEGER DEFAULT 0;
 declare SIP_ID INTEGER DEFAULT 0;

 SELECT id INTO TENANT_ID
   FROM tenants WHERE ref_id = in_tenant_name;

 SELECT id INTO SIP_ID
   FROM t_sip_users WHERE extension = in_exten;


 UPDATE t_user_options SET dnd = in_opt_val 
    WHERE t_sip_user_id = SIP_ID AND tenant_id = TENANT_ID;
    
 

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-05 16:42:30
