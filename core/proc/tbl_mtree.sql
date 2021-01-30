-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: mpbx
-- ------------------------------------------------------
-- Server version	5.7.27

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
-- Dumping data for table `mtree`
--

LOCK TABLES `mtree` WRITE;
/*!40000 ALTER TABLE `mtree` DISABLE KEYS */;
INSERT INTO `mtree` VALUES (30,'Current Tenant PBX','',10,NULL,1),(40,'Extensions','extensions',30,'/images/Extensions.png',1),(50,'Inbound DIDs','inbound',30,'/images/Inbound.png',1),(60,'Outbound Routes','routes',30,'/images/Routes.png',1),(14,'Tenants','tenants',10,'/images/Tenants.png',1),(15,'Trunks','trunks',10,'/images/Trunks.png',1),(16,'DIDs','dids',10,'/images/Dids.png',1),(71,'Auto Attendant','ivrmenu',30,'/images/Ivrmenu.png',1),(81,'MOH Profiles','moh',220,'/images/Moh.png',1),(19,'Default Music-on-Hold','mohdefault',20,'/images/Mohdefault.png',1),(21,'Default Recordings','snddefault',20,'/images/Snddefault.png',1),(220,'Media','',30,NULL,1),(82,'Recordings','sndtenants',220,'/images/Sndtenants.png',1),(91,'Ring Groups','ringgroups',30,'/images/Ringgroups.png',1),(101,'Shifts','shifts',500,'/images/Timefilters.png',1),(94,'Queues','queues',30,'/images/Queues.png',1),(111,'Conferences','conferences',30,'/images/Conferences.png',1),(150,'Feature Codes','features',30,'/images/Features.png',1),(20,'Tenant Defaults','',10,NULL,1),(18,'Feature Codes','featuresdef',20,'/images/Featuresdef.png',1),(400,'Call Center Functions','recordings',30,NULL,1),(300,'Call Recordings','recordings',500,'/images/recordings.png',1),(410,'Predictive Dialer','campaigns',400,'/images/Campaigns.png',1),(13,'Administrators','adminusers',10,'/images/Adminusers.png',1),(310,'CDRs','cdrs',500,'/images/cdrs.png',1),(92,'Page Groups','pagegroups',30,'/images/Ringgroups.png',1),(2,'Dashboard','dashboard',10,'/images/Dashboard.png',1),(95,'Lost Calls Report','lostcalls',500,'/images/missed_calls.png',1),(23,'Black List IP','blacklist',20,'/images/Blacklist.png',1),(500,'Reports','',10,'/images/reports.png',1),(501,'Summary reports','summary_reports',500,'/images/summary_reports.png',1),(5,'User Status','extenstatus',10,'/images/Conferences.png',1),(510,'DID usage stats','dids_usage',500,'/images/DIDs.png',1);
/*!40000 ALTER TABLE `mtree` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-11-05 21:04:14
