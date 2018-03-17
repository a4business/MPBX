-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: etor_pbx
-- ------------------------------------------------------
-- Server version	5.1.73-log

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dids`
--

LOCK TABLES `dids` WRITE;
/*!40000 ALTER TABLE `dids` DISABLE KEYS */;
INSERT INTO `dids` VALUES (1,'22222',2,'(none)','Main Office  umber'),(2,'3333',2,'(none)','Office 1'),(3,'444',2,'(none)','444'),(4,'555',42,'(none)','555'),(5,'6474797781',48,'(none)','Test number'),(6,'6478121381',48,'(none)','Test number 2');
/*!40000 ALTER TABLE `dids` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `etor_users`
--

LOCK TABLES `etor_users` WRITE;
/*!40000 ALTER TABLE `etor_users` DISABLE KEYS */;
INSERT INTO `etor_users` VALUES (1,'admin','etor','',NULL);
/*!40000 ALTER TABLE `etor_users` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`context`,`exten`,`priority`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feature_codes`
--

LOCK TABLES `feature_codes` WRITE;
/*!40000 ALTER TABLE `feature_codes` DISABLE KEYS */;
INSERT INTO `feature_codes` VALUES (1,2,3,0,'Check VoiceMail with PIN','internal-scnd-features','*95',1,'Macro','app-check-vmail'),(3,NULL,3,0,'Check VideoMail','internal-scnd-features','*97',1,'Macro','app-check-vmail'),(2,2,3,0,'Check VoiceMail with no  PIN','internal-scnd-features','*98',1,'Macro','app-check-vmail,s');
/*!40000 ALTER TABLE `feature_codes` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=223 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mtree`
--

LOCK TABLES `mtree` WRITE;
/*!40000 ALTER TABLE `mtree` DISABLE KEYS */;
INSERT INTO `mtree` VALUES (30,'Current Tenant PBX','action1',10),(40,'Extensions','extensions',30),(50,'Inbound DIDs','inbound',30),(60,'Outbound Routes','routes',30),(14,'Tenants','tenants',10),(25,'Trunks','trunks',10),(16,'DIDs','dids',10),(71,'Auto Attendant','ivrmenu',30),(81,'Music On Hold','moh',80),(217,'Default Music-on-Hold','mohdefault',200),(219,'Default Recordings','snddefault',200),(80,'Media','',30),(82,'Recordings','sndtenants',80),(91,'Ring Groups','ringgroups',30),(101,'Time Rules','timefilters',30),(94,'Queues','queues',30),(111,'Conferences','conferences',30),(150,'Feature Coces','features',30),(200,'Tenant Defaults','',10),(18,'Feature Codes','featuresdef',200);
/*!40000 ALTER TABLE `mtree` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes_list`
--

LOCK TABLES `routes_list` WRITE;
/*!40000 ALTER TABLE `routes_list` DISABLE KEYS */;
INSERT INTO `routes_list` VALUES (1,1,'998877','1',0,'',9,NULL,NULL),(11,11,'11','1',1,NULL,NULL,NULL,NULL),(12,NULL,'2222','2222',NULL,NULL,NULL,NULL,NULL),(13,NULL,'223','10',NULL,NULL,NULL,NULL,NULL),(14,NULL,'111','22',NULL,NULL,NULL,NULL,NULL),(15,NULL,'111','22',NULL,NULL,NULL,NULL,NULL),(16,NULL,'222','22',222,NULL,NULL,NULL,NULL),(17,NULL,'22','22',22,NULL,NULL,NULL,NULL),(18,NULL,'222','222',222,'222',NULL,NULL,NULL),(19,NULL,'22','22',22,'22',NULL,NULL,NULL),(20,NULL,'33','33',33,NULL,NULL,NULL,NULL),(21,NULL,'222','22',22,'22',NULL,NULL,NULL),(22,NULL,'22','22',22,'22',NULL,NULL,NULL),(23,NULL,'111',NULL,11,NULL,NULL,NULL,NULL),(24,NULL,'1222',NULL,222,NULL,NULL,NULL,NULL),(25,111,'122','22',22,'22',NULL,NULL,NULL),(26,111,'1222',NULL,222,NULL,NULL,NULL,NULL),(27,111,'1',NULL,222,NULL,NULL,NULL,NULL),(28,111,'1',NULL,22,NULL,NULL,NULL,NULL),(29,111,'1',NULL,222,NULL,NULL,NULL,NULL),(30,NULL,'2222',NULL,222,NULL,NULL,NULL,NULL),(31,NULL,'12323',NULL,232,NULL,NULL,NULL,NULL),(32,0,'12323',NULL,2323,NULL,NULL,NULL,NULL),(33,0,'12323',NULL,2323,NULL,NULL,NULL,NULL),(34,0,'122',NULL,22,NULL,NULL,NULL,NULL),(35,NULL,'232',NULL,2323,NULL,NULL,NULL,NULL),(36,NULL,'1',NULL,2323,NULL,NULL,NULL,NULL),(37,NULL,'1',NULL,232,NULL,NULL,NULL,NULL),(38,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL),(39,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL),(40,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL),(41,0,'1123','123',23,'213213',NULL,NULL,NULL),(42,1,'_98X.','23',2,'11',1,NULL,NULL),(43,2,'123','123',123,NULL,NULL,NULL,NULL),(44,2,'123','213',1232,NULL,NULL,NULL,NULL),(46,3,'_3331X.','11',3,'222',9,2,NULL),(48,3,'_999X.','22',3,'111',1,NULL,NULL),(49,4,'_1X.',NULL,NULL,'22',2,NULL,NULL),(50,4,'_33X.',NULL,NULL,'22',2,NULL,NULL),(51,3,'_1X.',NULL,NULL,'111',1,NULL,NULL),(54,5,'_1X.',NULL,NULL,NULL,10,NULL,NULL),(58,6,'_3XX',NULL,NULL,NULL,1,NULL,NULL),(56,1,'_X!',NULL,NULL,NULL,10,NULL,NULL),(59,5,'_X!',NULL,NULL,NULL,10,NULL,NULL);
/*!40000 ALTER TABLE `routes_list` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `sip_conf`
--

LOCK TABLES `sip_conf` WRITE;
/*!40000 ALTER TABLE `sip_conf` DISABLE KEYS */;
/*!40000 ALTER TABLE `sip_conf` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_conferences`
--

LOCK TABLES `t_conferences` WRITE;
/*!40000 ALTER TABLE `t_conferences` DISABLE KEYS */;
INSERT INTO `t_conferences` VALUES (6,2,'1111',1,1,1,'[\"scnd-201\"]','33',1,1,'default','',NULL,1,'324',1,'',1,1,1,'33','','222'),(14,48,'1111',1,1,1,'[\"101\"]','2222',NULL,1,'default','',10,1,'test',1,'',1,1,1,'3333','','0');
/*!40000 ALTER TABLE `t_conferences` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_extensions`
--

LOCK TABLES `t_extensions` WRITE;
/*!40000 ALTER TABLE `t_extensions` DISABLE KEYS */;
INSERT INTO `t_extensions` VALUES (150,42,3,0,'Check VoiceMail with PIN','internal-121212-features','*95',1,'Macro','app_check_vmail,PIN'),(165,42,3,0,'Check VideoMail','internal-121212-features','*97',1,'Macro','app-check-vmail'),(151,42,3,0,'Check VoiceMail with no  PIN','internal-121212-features','*98',1,'Macro','app_check_vmail,NOPIN'),(144,1,3,0,'Check VoiceMail with PIN','internal-def-features','*95',1,'Macro','app_check_vmail,PIN'),(162,1,3,0,'Check VideoMail','internal-def-features','*97',1,'Macro','app-check-vmail'),(145,1,3,0,'Check VoiceMail with no  PIN','internal-def-features','*98',1,'Macro','app_check_vmail,NOPIN'),(153,51,3,0,'Check VoiceMail with PIN','internal-eTorNetwor-features','*95',1,'Macro','app_check_vmail,PIN'),(166,51,3,0,'Check VideoMail','internal-eTorNetwor-features','*97',1,'Macro','app-check-vmail'),(154,51,3,0,'Check VoiceMail with no  PIN','internal-eTorNetwor-features','*98',1,'Macro','app_check_vmail,NOPIN'),(159,48,3,0,'Check VoiceMail with PIN','internal-parm-features','*95',1,'Macro','app-check-vmail'),(164,48,3,0,'Check VideoMail','internal-parm-features','*97',1,'Macro','app-check-vmail'),(160,48,3,0,'Check VoiceMail with no Voicemail PIN','internal-parm-features','*98',1,'Macro','app-check-vmail,s'),(156,2,3,0,'Check VoiceMail with PIN','internal-scnd-features','*95',1,'Macro','app-check-vmail'),(163,2,3,0,'Check VideoMail','internal-scnd-features','*97',1,'Macro','app-check-vmail'),(157,2,3,0,'Check VoiceMail with no  PIN','internal-scnd-features','*98',1,'Macro','app-check-vmail,s');
/*!40000 ALTER TABLE `t_extensions` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_inbound`
--

LOCK TABLES `t_inbound` WRITE;
/*!40000 ALTER TABLE `t_inbound` DISABLE KEYS */;
INSERT INTO `t_inbound` VALUES (1,2,'2','222',0),(2,2,'1','Inbound number 2',1),(3,48,'6','test',1),(4,48,'5','test number 2',1),(5,42,'4',NULL,1);
/*!40000 ALTER TABLE `t_inbound` ENABLE KEYS */;
UNLOCK TABLES;

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
  `week_day_from` int(11) DEFAULT NULL,
  `week_day_to` int(11) DEFAULT NULL,
  `day_time_from` time DEFAULT NULL,
  `day_time_to` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_inbound_rules`
--

LOCK TABLES `t_inbound_rules` WRITE;
/*!40000 ALTER TABLE `t_inbound_rules` DISABLE KEYS */;
INSERT INTO `t_inbound_rules` VALUES (11,48,4,NULL,'ivrmenu','60',NULL,NULL,NULL,NULL,NULL,NULL,'216',1,7,'00:00:00',NULL),(12,2,2,NULL,'ringgroup','60',NULL,NULL,NULL,NULL,NULL,NULL,'525',1,7,NULL,NULL),(13,48,3,NULL,'ivrmenu','60',NULL,NULL,NULL,NULL,NULL,NULL,'216',1,7,NULL,'00:00:00'),(15,2,1,NULL,'voicemail','60',NULL,NULL,NULL,NULL,NULL,NULL,'8',1,7,NULL,NULL);
/*!40000 ALTER TABLE `t_inbound_rules` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=222 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_ivrmenu`
--

LOCK TABLES `t_ivrmenu` WRITE;
/*!40000 ALTER TABLE `t_ivrmenu` DISABLE KEYS */;
INSERT INTO `t_ivrmenu` VALUES (213,2,'Receptionist 11','First Office','recording','Welcome, thank you for calling','en-US_AllisonVoice','scnd-234234',NULL,'en',60,'yes',0,'yes','no','yes',NULL),(214,2,'Receptionist 22','Second Office','tts','welcome','en-US_MichaelVoice','scnd-Tenant-Moh','106@scnd-vmdefault','en',60,'yes',0,'no','no','no',NULL),(216,48,'att','attendand','recording','etor-main','en-US_MichaelVoice','default','102@parm-vmdefault','en',60,'yes',0,'yes','yes','no',NULL),(220,2,'test','test','recording','maingreetingnew','en-US_MichaelVoice','default','201@scnd-vmdefault',NULL,60,'yes',0,'no','yes','no',NULL),(221,48,'etorlocations','etor locations','recording','locationsetor','en-US_MichaelVoice','default',NULL,NULL,30,'yes',0,'yes','yes','no',NULL);
/*!40000 ALTER TABLE `t_ivrmenu` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_ivrmenu_items`
--

LOCK TABLES `t_ivrmenu_items` WRITE;
/*!40000 ALTER TABLE `t_ivrmenu_items` DISABLE KEYS */;
INSERT INTO `t_ivrmenu_items` VALUES (185,221,48,'2',NULL,'no',NULL,NULL,NULL,'ivrmenu','att'),(180,213,2,'t',NULL,'no',NULL,NULL,NULL,'repeat',NULL),(181,216,48,'2',NULL,'no','',NULL,NULL,'conference',''),(177,213,2,'4',NULL,'tts','To repeat this menu, press 4',NULL,NULL,'repeat',NULL),(124,0,2,NULL,NULL,'recording',NULL,'en-US_MichaelVoice',NULL,NULL,NULL),(125,0,2,'44','444','recording','44','en-US_MichaelVoice',NULL,NULL,NULL),(126,3333,2,'333','333','recording',NULL,'en-US_MichaelVoice',NULL,NULL,NULL),(127,0,2,'33','33','recording',NULL,'en-US_MichaelVoice',NULL,NULL,NULL),(128,22,2,'2',NULL,'recording',NULL,'en-US_MichaelVoice',NULL,NULL,NULL),(129,0,2,'2','2','recording',NULL,'en-US_MichaelVoice',NULL,NULL,NULL),(134,214,2,'3','SIP/123','tts','to talk with operator, press 3',NULL,NULL,'extension','105'),(140,0,48,'1','sip/123','recording','welcome',NULL,NULL,NULL,NULL),(141,0,48,'1','1','recording','welcome',NULL,NULL,NULL,NULL),(142,0,48,'2','2','recording',NULL,NULL,NULL,NULL,NULL),(143,0,48,'1','1','recording','welcome',NULL,NULL,NULL,NULL),(144,0,48,'1','1','recording','welcome',NULL,NULL,NULL,NULL),(145,216,48,'1','2','recording','queueintro',NULL,NULL,'queue','Queue'),(164,214,2,'4',NULL,'tts','To leave VoiceMail, press 4',NULL,NULL,'voicemail','104@scnd-vmdefault'),(147,217,2,'2',NULL,'recording',NULL,NULL,NULL,'voicemail','103@scnd-vmdefault'),(149,217,2,'3',NULL,'recording',NULL,NULL,NULL,'voicemail','104@scnd-vmdefault'),(150,217,2,'4',NULL,'recording',NULL,NULL,NULL,'voicemail','104@scnd-vmdefault'),(152,218,2,'1',NULL,'recording','welcome',NULL,NULL,'extension','SIP/104'),(154,218,2,'2',NULL,'recording',NULL,NULL,NULL,'voicemail','104@scnd-vmdefault'),(171,214,2,'6',NULL,'tts','to repeat this menu, press 6',NULL,NULL,'repeat',''),(170,214,2,'5',NULL,'tts','To Dial number, press 5',NULL,NULL,'number','33333333'),(168,219,51,'1',NULL,'recording','CD1',NULL,NULL,'extension','SIP/222'),(172,216,48,'0',NULL,'no',NULL,NULL,NULL,'voicemail','101@parm-vmdefault'),(173,214,2,'t',NULL,'no',NULL,NULL,NULL,'extension','105'),(174,214,2,'i',NULL,'no',NULL,NULL,NULL,'play_invalid',''),(179,213,2,'1',NULL,'tts','To call phone number, press 1',NULL,NULL,'number','99932112323'),(178,213,2,'2',NULL,'tts','To leave voicemail, press 2',NULL,NULL,'voicemail','105@scnd-vmdefault'),(184,221,48,'1',NULL,'no',NULL,NULL,NULL,'ivrmenu','etorlocations'),(182,216,48,'3',NULL,'no',NULL,NULL,NULL,'extension','SIP/102'),(183,213,2,'i',NULL,'no',NULL,NULL,NULL,'play_invalid',''),(186,216,48,'1111',NULL,'no',NULL,NULL,NULL,'conference','1111');
/*!40000 ALTER TABLE `t_ivrmenu_items` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_moh`
--

LOCK TABLES `t_moh` WRITE;
/*!40000 ALTER TABLE `t_moh` DISABLE KEYS */;
INSERT INTO `t_moh` VALUES (0,'default','moh','','files','','random','slin',2,NULL),(1,'parm-bc2f','parm-test2','','files','#','random','slin',48,'welcome'),(2,'parm-11','parm-default','','files','','random','slin',48,''),(3,'parm-myMOH','parm-myMOH','','files','','random','slin',48,''),(8,'parm-my','parm-my','','files','','random','slin',48,NULL),(9,'scnd-Tenant-Moh','scnd-Tenant-Moh','','files','#','random','slin',2,NULL),(10,'def-9a7d','def-9a7d','','files','','random','slin',1,NULL),(15,'scnd-234234','scnd-234234','','files','','random','slin',2,NULL);
/*!40000 ALTER TABLE `t_moh` ENABLE KEYS */;
UNLOCK TABLES;

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
  `penalty` int(11) DEFAULT NULL,
  `paused` int(11) DEFAULT NULL,
  `uniqueid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `queue_interface` (`queue_name`,`interface`)
) ENGINE=MyISAM AUTO_INCREMENT=416 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_queue_members`
--

LOCK TABLES `t_queue_members` WRITE;
/*!40000 ALTER TABLE `t_queue_members` DISABLE KEYS */;
INSERT INTO `t_queue_members` VALUES (406,2,'201','222','SIP/scnd-201',NULL,0,406),(407,2,'104','222','SIP/scnd-104',NULL,0,407),(404,2,'203','222','SIP/scnd-203',NULL,0,404),(411,2,'105','queueueue','SIP/scnd-105',NULL,0,411),(402,48,'102','testqueue','SIP/parm-102',NULL,1,402),(405,2,'202','222','SIP/scnd-202',NULL,0,405),(410,2,'104','queueueue','SIP/scnd-104',NULL,0,410),(409,48,'101','Queue','SIP/parm-101',NULL,0,409),(412,2,'201','queueueue','SIP/scnd-201',NULL,0,412),(413,2,'202','queueueue','SIP/scnd-202',NULL,0,413),(414,2,'105','222','SIP/scnd-105',NULL,0,414);
/*!40000 ALTER TABLE `t_queue_members` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_bi` BEFORE INSERT ON `t_queue_members` FOR EACH ROW
BEGIN
   SET NEW.uniqueid = LAST_INSERT_ID();
  SET NEW.uniqueid =  (SELECT AUTO_INCREMENT FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 't_queue_members');
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
  `reportholdtime` tinyint(1) DEFAULT NULL,
  `memberdelay` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `timeoutrestart` tinyint(1) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_welcome` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=282 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_queues`
--

LOCK TABLES `t_queues` WRITE;
/*!40000 ALTER TABLE `t_queues` DISABLE KEYS */;
INSERT INTO `t_queues` VALUES ('Queue',48,'default','welcome',NULL,30,NULL,NULL,'queue-youarenext',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,60,NULL,'no',NULL,NULL,5,'yes','yes','no','yes',5,10,NULL,'ringall','','no',NULL,NULL,0,NULL,NULL,NULL,250,'queueintro'),('222',2,'default',NULL,NULL,30,NULL,NULL,'queue-youarenext',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,60,NULL,'once',NULL,NULL,5,'no','yes','yes','yes',5,10,NULL,'random','no','no',NULL,NULL,0,NULL,NULL,NULL,260,NULL),('testqueue',48,'default',NULL,NULL,60,NULL,NULL,'queue-youarenext',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,20,NULL,'once',NULL,NULL,5,'no','yes','yes','yes',5,100,NULL,'ringall','','no',NULL,NULL,0,NULL,NULL,NULL,270,NULL),('queueueue',2,'default',NULL,NULL,30,NULL,NULL,'queue-youarenext',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,60,NULL,'once',NULL,NULL,5,'no','yes','yes','yes',5,10,NULL,'random','no','no',NULL,NULL,0,NULL,NULL,NULL,280,NULL),('12333333123213',2,'default',NULL,NULL,30,NULL,NULL,'queue-youarenext',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,60,NULL,'once',NULL,NULL,5,'no','yes','yes','yes',5,10,NULL,'random','no','no',NULL,NULL,0,NULL,NULL,NULL,281,NULL);
/*!40000 ALTER TABLE `t_queues` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_ringgroup_lists`
--

LOCK TABLES `t_ringgroup_lists` WRITE;
/*!40000 ALTER TABLE `t_ringgroup_lists` DISABLE KEYS */;
INSERT INTO `t_ringgroup_lists` VALUES (140,2,522,'2344432432','none','CD1.wav',NULL,'en-US_Michael','234423434','[\"SIP/105\",\"SIP/201\",\"SIP/203\"]',NULL,'extension'),(141,2,524,'2323','none',NULL,NULL,'en-US_Michael',NULL,'[\"SIP/105\",\"SIP/201\"]',NULL,'extension'),(144,2,523,'2323','none',NULL,NULL,'en-US_Michael',NULL,'[\"queueueue\"]',NULL,'queue'),(155,2,525,'24234234','none',NULL,NULL,'en-US_Michael',NULL,'[118]',NULL,'extension'),(149,2,525,'HUnt List 1','none','0',NULL,'en-US_Michael','11111','[116,117,118,133]',NULL,'extension'),(147,2,526,'uioiy','none',NULL,NULL,'en-US_Michael',NULL,'[\"SIP/105\",\"SIP/201\",\"SIP/203\"]',NULL,'extension'),(157,48,501,NULL,'none',NULL,NULL,'en-US_Michael',NULL,'',NULL,'extension'),(159,48,1000,'test1','none',NULL,NULL,'en-US_Michael',NULL,'[129]',6,'extension'),(163,48,1000,'test2','none',NULL,NULL,'en-US_Michael',NULL,'[129]',6,'extension');
/*!40000 ALTER TABLE `t_ringgroup_lists` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_ringgroups`
--

LOCK TABLES `t_ringgroups` WRITE;
/*!40000 ALTER TABLE `t_ringgroups` DISABLE KEYS */;
INSERT INTO `t_ringgroups` VALUES (501,48,'RingGroup 1','123','number','14163631113','',NULL),(525,2,'12323',NULL,'unassigned','','222',NULL),(1000,48,'test',NULL,'voicemail','26',NULL,NULL);
/*!40000 ALTER TABLE `t_ringgroups` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_route`
--

LOCK TABLES `t_route` WRITE;
/*!40000 ALTER TABLE `t_route` DISABLE KEYS */;
INSERT INTO `t_route` VALUES (6,2,'To-Office3',1,NULL,NULL,NULL,NULL,NULL),(4,1,'Main Routing Table ',1,NULL,NULL,NULL,NULL,NULL),(3,2,'Special Rote for Client',1,NULL,NULL,NULL,NULL,''),(5,48,'outbound',1,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `t_route` ENABLE KEYS */;
UNLOCK TABLES;

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
  `fullcontact` varchar(80) NOT NULL DEFAULT '',
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
  `lastms` int(11) NOT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `sip_name` (`name`),
  UNIQUE KEY `tenant_exten` (`tenant_id`,`extension`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_sip_users`
--

LOCK TABLES `t_sip_users` WRITE;
/*!40000 ALTER TABLE `t_sip_users` DISABLE KEYS */;
INSERT INTO `t_sip_users` VALUES (120,'scnd-201','b541444a','udp','dynamic','internal-scnd',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'201@scnd-vmdefault',NULL,'yes',NULL,NULL,NULL,'tenant=scnd','all','ulaw;alaw;gsm','','',0,'scnd-201','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','p.deol@etornetworks.com',NULL,'no','201',2,'','qualify=yes',NULL,'yes',1,0,'parm-myMOH','parm-myMOH','parkinglot-scnd','','',NULL,NULL,NULL,NULL),(118,'scnd-105','97724e2a','udp','dynamic','internal-scnd',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,NULL,NULL,'yes',NULL,NULL,NULL,'tenant=scnd','all','ulaw;alaw;gsm','',NULL,0,'','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','',NULL,'no','105',2,'',NULL,NULL,'yes',1,0,'parm-myMOH','parm-myMOH','parkinglot-scnd','','',NULL,NULL,NULL,NULL),(95,'def-101','0f8ecf8c','udp','dynamic','internal-def',NULL,NULL,NULL,NULL,'force_rport,comedia','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes',NULL,NULL,NULL,'tenant=def','all','ulaw;alaw;gsm','','',0,'','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes',NULL,NULL,'no','101',1,NULL,NULL,NULL,'yes',1,NULL,'parm-myMOH','parm-myMOH','parkinglot-def','','',NULL,NULL,NULL,NULL),(96,'def-100','91a3e5c6','udp','dynamic','internal-def',NULL,NULL,NULL,NULL,'force_rport,comedia','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes',NULL,NULL,NULL,'tenant=def','all','ulaw;alaw;gsm','','',0,'def-100','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes',NULL,NULL,'no','100',1,NULL,NULL,NULL,'yes',1,NULL,'parm-myMOH','parm-myMOH','parkinglot-def','','',NULL,NULL,NULL,NULL),(98,'def-124','542b93c9','udp','dynamic','internal-def',NULL,NULL,NULL,NULL,'force_rport,comedia','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes',NULL,NULL,NULL,'tenant=def','all','ulaw;alaw;gsm','','',0,'','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes',NULL,NULL,'no','124',1,NULL,NULL,NULL,'yes',1,NULL,'parm-myMOH','parm-myMOH','parkinglot-def','','',NULL,NULL,NULL,NULL),(99,'ffff-101','90deb1d6','udp','dynamic','internal-121212',NULL,NULL,NULL,NULL,'force_rport,comedia','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes',NULL,NULL,NULL,'tenant=121212','all','ulaw;alaw;gsm','',NULL,0,'','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes',NULL,NULL,'no','101',42,NULL,NULL,NULL,'yes',1,NULL,'parm-myMOH','parm-myMOH','parkinglot-121212','','',NULL,NULL,NULL,NULL),(100,'ffff-102','9e8a99de','udp','dynamic','internal-121212',NULL,NULL,NULL,NULL,'force_rport,comedia','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes',NULL,NULL,NULL,'','all','ulaw;alaw;gsm','','',0,'ffff-102','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes',NULL,NULL,'no','102',42,NULL,NULL,NULL,'yes',1,NULL,'parm-myMOH','parm-myMOH','parkinglot-121212','','',NULL,NULL,NULL,NULL),(119,'121212-103','e9d96aac','udp','dynamic','internal-121212',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,NULL,NULL,'11',NULL,NULL,NULL,'','all','ulaw;alaw;gsm','',NULL,0,'','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','',NULL,'no','103',42,'',NULL,NULL,'yes',1,NULL,'parm-myMOH','parm-myMOH','parkinglot-121212','','',NULL,NULL,NULL,NULL),(116,'scnd-103','41850b68','udp','dynamic','internal-scnd',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'103@scnd-vmdefault',NULL,'yes',NULL,NULL,NULL,'SET_EXTOUT_CID=33331;SET_EXTINT_CID=44441;tenant=scnd','all','h264,ulaw','sip:scnd-103@79.140.9.229:45436^3Brinstance=93a6cc4ac3f8aa03^3Btransport=UDP','79.140.9.229',45436,'scnd-103','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,45,'',1521312636,'Z 3.3.25608 r25552','no','yes','voip.linux.expert@gmail.com',NULL,'no','103',2,'','__CALLER_INFO=1231223','103','yes',1,2,'parm-myMOH','parm-myMOH','parkinglot-scnd','33331','','44441',NULL,NULL,NULL),(117,'scnd-104','18ea0853','udp','dynamic','internal-scnd',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'104@scnd-vmdefault',NULL,'yes',NULL,NULL,NULL,'SET_EXTOUT_CID=88881;SET_EXTOUT_CNAME=99991;SET_EXTINT_CID=5555551;SET_EXTINT_CNAME=6666661;tenant=scnd','all','ulaw;alaw;gsm','','',0,'s','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','',NULL,'no','104',2,'',NULL,NULL,'yes',1,3,'parm-myMOH','parm-myMOH','parkinglot-scnd','88881','99991','5555551','6666661',NULL,NULL),(121,'scnd-202','83cf12a4','udp','dynamic','internal-scnd',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,NULL,NULL,'yes',NULL,NULL,NULL,'tenant=scnd','all','ulaw;alaw;gsm','','',0,'scnd-202','',NULL,'yes',NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','',NULL,'no','202',2,'',NULL,NULL,'yes',1,4,'parm-myMOH','parm-myMOH','parkinglot-scnd','','',NULL,NULL,NULL,NULL),(129,'parm-101','11bdfb7b','udp','dynamic','internal-parm',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'101@parm-vmdefault',NULL,'yes',NULL,NULL,NULL,'SET_EXTOUT_CID=4163631113;SET_EXTOUT_CNAME=eTor Networks;tenant=parm','all','ulaw,gsm,h264','sip:parm-101@192.168.0.51:5060','99.248.181.100',1055,'parm-101','',NULL,NULL,NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,116,'',1521311743,'Grandstream GXV3275 1.0.3.92','no','yes','',NULL,'no','101',48,'','','101','yes',1,0,'parm-myMOH','parm-myMOH','parkinglot-parm','4163631113','eTor Networks',NULL,NULL,NULL,NULL),(130,'parm-102','970a22f2','udp','dynamic','internal-parm',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'102@parm-vmdefault',NULL,'yes',NULL,NULL,NULL,'SET_EXTOUT_CID=4163631113;SET_EXTOUT_CNAME=eTor Networks;tenant=parm','all','ulaw,gsm,h264','sip:parm-102@206.223.163.20:5060','206.223.163.20',5060,'parm-102','',NULL,NULL,NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,102,'',1521313068,'Grandstream GXV3275 1.0.3.92','no','yes','',NULL,'no','102',48,'','','102','yes',1,5,'parm-myMOH','parm-myMOH','parkinglot-parm','4163631113','eTor Networks',NULL,NULL,NULL,NULL),(131,'parm-103','0370d236','udp','dynamic','internal-parm',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'103@parm-vmdefault',NULL,'yes',NULL,NULL,NULL,'SET_EXTOUT_CID=1231234432423;SET_EXTOUT_CNAME=Etor  Networksssss;tenant=parm','all','ulaw,gsm,h263','','',0,'parm-103','',NULL,NULL,NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','',NULL,'no','103',48,'','','103','yes',1,NULL,'parm-myMOH','parm-myMOH','parkinglot-parm','1231234432423','Etor  Networksssss',NULL,NULL,NULL,NULL),(133,'scnd-203','45957c49','udp','dynamic','internal-scnd',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'203@scnd-vmdefault',NULL,'yes',NULL,NULL,NULL,'tenant=scnd','all','ulaw,gsm,h263','','',0,'','',NULL,NULL,NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','',NULL,'no','203',2,'','','203','yes',1,1,NULL,NULL,'parkinglot-scnd','','',NULL,NULL,NULL,NULL),(134,'eTorNetwor-201','d55d881c','udp','dynamic','internal-eTorNetwor',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'201@eTorNetwor-vmdefault',NULL,'yes',NULL,NULL,NULL,'tenant=eTorNetwor','all','ulaw,gsm,h264','','',0,'eTorNetwor-201','',NULL,NULL,NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','gary@etornetworks.com',NULL,'no','201',51,'','','201','yes',1,NULL,NULL,NULL,'parkinglot-eTorNetwor','','',NULL,NULL,NULL,NULL),(135,'eTorNetwor-222','c7c07faa','udp','dynamic','internal-eTorNetwor',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'222@eTorNetwor-vmdefault',NULL,'yes',NULL,NULL,NULL,'tenant=eTorNetwor','all','ulaw,gsm,h264','sip:eTorNetwor-222@192.0.0.4:17663','72.143.208.1',32907,'eTorNetwor-222','',NULL,NULL,NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,-1,'',1519411378,'Grandstream Wave 1.0.2.20','no','yes','p.deol@etornetworks.com',NULL,'no','222',51,'','','222','yes',1,NULL,NULL,NULL,'parkinglot-eTorNetwor','','',NULL,NULL,NULL,NULL),(140,'def-125','ee078d44','udp','dynamic','internal-def',NULL,NULL,NULL,NULL,'force_rport,comedia','friend',NULL,NULL,NULL,NULL,NULL,'auto',NULL,NULL,NULL,NULL,'125@def-vmdefault',NULL,'yes',NULL,NULL,NULL,'tenant=def','all','ulaw,gsm,h263','',NULL,0,'','',NULL,NULL,NULL,NULL,NULL,'allowed_not_screened',NULL,NULL,'yes',NULL,'yes','yes','yes','no','yes',NULL,'yes','accept',1800,90,'uas',NULL,NULL,NULL,'yes',500,NULL,120,NULL,NULL,0,'',0,'','no','yes','',NULL,'no','125',1,'',NULL,'125','yes',1,NULL,NULL,NULL,'parkinglot-def','','','','','','');
/*!40000 ALTER TABLE `t_sip_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_sip_users_on_delete` AFTER DELETE ON t_sip_users
 FOR EACH ROW
   DELETE FROM `t_vmusers`
     WHERE t_vmusers.tenant_id = old.tenant_id AND
           t_vmusers.mailbox = old.extension */;;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_timefilters`
--

LOCK TABLES `t_timefilters` WRITE;
/*!40000 ALTER TABLE `t_timefilters` DISABLE KEYS */;
INSERT INTO `t_timefilters` VALUES (1,2,'Always','any time','00:00-23:59'),(2,2,'Day TIme','8am - 20pm every day','08:00-20:00'),(3,2,'Night - Time','Not(08am - 20pm)  every day','!08:00-20:00'),(4,2,'Business Time','09am - 6pm Mon - Fri','09:00-18:00|1-5'),(5,2,'Business Off-time','Not 09am-6pm Mon-Fru','!09:00-18:00|1-5');
/*!40000 ALTER TABLE `t_timefilters` ENABLE KEYS */;
UNLOCK TABLES;

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
  `operator` varchar(4) DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `mailbox_context` (`mailbox`,`context`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_vmusers`
--

LOCK TABLES `t_vmusers` WRITE;
/*!40000 ALTER TABLE `t_vmusers` DISABLE KEYS */;
INSERT INTO `t_vmusers` VALUES (7,NULL,1,'def-vmdefault','124','6133',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',11),(8,NULL,2,'scnd-vmdefault','103','2222',NULL,'voip.linux.expert@gmail.com','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','no',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',11),(9,NULL,2,'scnd-vmdefault','104123','6539',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',NULL),(10,NULL,2,'scnd-vmdefault','105','1907',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',11),(11,NULL,42,'121212-vmdefault','103','5670',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',11),(12,NULL,2,'scnd-vmdefault','104','7002',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','no',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(13,NULL,2,'scnd-vmdefault','106','1111',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',12),(14,NULL,2,'scnd-vmdefault','202','',NULL,'','','','',NULL,'no',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'no','no',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(15,NULL,2,'scnd-vmdefault','201','1111',NULL,'p.deol@etornetworks.com','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','no',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',12),(19,NULL,2,'scnd-vmdefault','','6661',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',NULL),(25,NULL,48,'parm-vmdefault','101','15584',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',6),(26,NULL,48,'parm-vmdefault','102','90358',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',5),(27,NULL,48,'parm-vmdefault','103','1111',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(29,NULL,2,'scnd-vmdefault','203','12139',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(30,NULL,51,'eTorNetwor-vmdefault','101','1111',NULL,'gary@etornetworks.com','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',20),(31,NULL,51,'eTorNetwor-vmdefault','201','32389',NULL,'gary@etornetworks.com','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(32,NULL,51,'eTorNetwor-vmdefault','222','13534',NULL,'p.deol@etornetworks.com','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(37,NULL,42,'121212-vmdefault','101','22064',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(38,NULL,42,'121212-vmdefault','102','52489',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',NULL),(39,NULL,1,'def-vmdefault','101','50729',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(40,NULL,1,'def-vmdefault','100','10410',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0),(41,NULL,1,'def-vmdefault','125','39738',NULL,'','','','',NULL,'yes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'yes','yes',NULL,NULL,NULL,'no',NULL,NULL,'','0000-00-00 00:00:00',0);
/*!40000 ALTER TABLE `t_vmusers` ENABLE KEYS */;
UNLOCK TABLES;
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
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Record ID',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_id` varchar(50) DEFAULT NULL,
  `max_extensions` int(11) DEFAULT NULL,
  `parkext` varchar(100) DEFAULT NULL,
  `parkpos` int(11) DEFAULT NULL,
  `parkfindslot` enum('next','first') DEFAULT 'next',
  `parkingtime` int(11) DEFAULT NULL,
  `parkcomebacktoorigin` enum('yes','no') DEFAULT 'no',
  `parkedmusicclass` varchar(100) DEFAULT NULL,
  `sounds_language` varchar(10) DEFAULT NULL,
  `general_error_message` varchar(200) DEFAULT NULL,
  `outbound_callerid` varchar(100) DEFAULT NULL,
  `outbound_callername` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'The Default','def',10,'7001',10,'first',120,'no','def-9a7d',NULL,'cannot-complete-otherend-error',NULL,NULL),(2,'second','scnd',112,'700',11,NULL,120,'no','scnd-Tenant-Moh',NULL,'cannot-complete-otherend-error','',''),(48,'parm','parm',10,'700',5,'first',60,'yes','default',NULL,NULL,'5555555555','Parm'),(42,'value1','121212',11,'700',10,'first',120,'no','default',NULL,NULL,NULL,NULL),(51,'eTorNetworks','eTorNetwor',99,'700',10,'first',60,'yes','default','en',NULL,NULL,NULL);
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;
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
  `inTenants` varchar(100) DEFAULT NULL,
  `other_options` text,
  `register` varchar(200) DEFAULT NULL,
  `auth_user` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trunks`
--

LOCK TABLES `trunks` WRITE;
/*!40000 ALTER TABLE `trunks` DISABLE KEYS */;
INSERT INTO `trunks` VALUES (1,'trunk1','friend','test','localhost',1,NULL,'from-pstn',NULL,22,60,NULL,'pass','pass',11,'',11,'[2,42]',NULL,NULL,NULL),(2,'trunk2','peer','222','192.168.1.1',1,NULL,'from-pstn',NULL,20,60,NULL,'test133','test',2,'',22,'[1,2]','qualify=40\ndefaultuser=test\n','',NULL),(9,'etor','peer','Etor Trank descr','162.210.197.66',1,NULL,'from-pstn','etor:verysecret@applesscall.com',NULL,60,NULL,'etor','verysecret',0,NULL,0,'[2]','disallow=all\nallow=ulaw\nallow=alaw\nallow=gsm\ninsecure=invite','etor:verysecret@applesscall.com',''),(10,'VoIPms','friend',NULL,'montreal4.voip.ms',1,NULL,'from-pstn',NULL,NULL,60,NULL,'',NULL,0,NULL,0,'[48]','qualify=yes',NULL,NULL);
/*!40000 ALTER TABLE `trunks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-17 14:10:02
