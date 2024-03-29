-- MySQL dump 10.13  Distrib 5.7.33, for Linux (x86_64)
--
-- Host: localhost    Database: mpbx
-- ------------------------------------------------------
-- Server version	5.7.34

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
  `smtp_port` int(11) DEFAULT NULL,
  `smtp_host` varchar(200) DEFAULT NULL,
  `smtp_user` varchar(200) DEFAULT NULL,
  `smtp_password` varchar(200) DEFAULT NULL,
  `smtp_from` varchar(200) DEFAULT NULL,
  `smtp_from_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-05 10:51:19
