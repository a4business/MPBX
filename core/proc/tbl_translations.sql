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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (65,'Ð’Ð¾Ð¹Ñ‚Ð¸','Sign IN','Ð£Ð²iÐ¹Ñ‚Ð¸'),(66,'Ð¡Ð¾ÑÑ‚Ð¾ÑÐ½Ð¸Ðµ','Status Page','Ð¡Ñ‚Ð°Ñ‚ÑƒÑ'),(67,'ÐžÑ‚Ð´ÐµÐ» Ð¿Ð¾ Ñ€Ð°Ð±Ð¾Ñ‚Ðµ Ñ Ð°Ð±Ð¾Ð½ÐµÐ½Ñ‚Ð°Ð¼Ð¸ | Callcenter','Customer care department | Callcenter','Ð’iÐ´Ð´iÐ» Ð¿Ð¾ Ñ€Ð¾Ð±Ð¾Ñ‚i Ð· Ð°Ð±Ð¾Ð½ÐµÐ½Ñ‚Ð°Ð¼Ð¸ | ÐšÐ¾Ð»Ñ†ÐµÐ½Ñ‚Ñ€ '),(68,'ÐÐ±Ð¾Ð½ÐµÐ½Ñ‚ÑÐºÐ¸Ð¹ Ð¾Ñ‚Ð´ÐµÐ»','Customer care department','Ð’iÐ´Ð´iÐ» Ð¿Ð¾ Ñ€Ð¾Ð±Ð¾Ñ‚i Ð· Ð°Ð±Ð¾Ð½ÐµÐ½Ñ‚Ð°Ð¼Ð¸ '),(69,'ÐžÐ¿ÐµÑ€Ð°Ñ‚Ð¾Ñ€','operator','ÐžÐ¿ÐµÑ€Ð°Ñ‚Ð¾Ñ€'),(70,'Ð›Ð¾Ð³Ð¸Ð½','Login','Ð›Ð¾Ð³Ð¸Ð½'),(71,'Ð¿Ð°Ñ€Ð¾Ð»ÑŒ','Password','ÐŸÐ°Ñ€Ð¾Ð»ÑŒ'),(72,'Ð—Ð°Ð¿Ð¾Ð¼Ð½Ð¸Ñ‚ÑŒ Ð¼ÐµÐ½Ñ','Remember me','Ð—Ð°Ð¿Ð°Ð¼\"ÑÑ‚Ð°Ñ‚Ð¸ Ð¼ÐµÐ½Ðµ'),(73,'Ð—Ð°Ð±Ñ‹Ð» Ð¿Ð°Ñ€Ð¾Ð»ÑŒ?','Forgot password?','Ð—Ð°Ð±ÑƒÐ»Ð¸ Ð¿Ð°Ñ€Ð¾Ð»Ñ?'),(74,'ÐÐµÑ‚ ÑƒÑ‡ÐµÑ‚Ð½Ð¾Ð¹ Ð·Ð°Ð¿Ð¸ÑÐ¸?','Have no account?','ÐÐµÐ¼Ð°ÐµÑ‚Ðµ Ð»Ð¾Ð³iÐ½Ñƒ?'),(75,'Ð—Ð°Ñ€ÐµÐ³Ð¸ÑÑ‚Ñ€Ð¸Ñ€Ð¾Ð²Ð°Ñ‚ÑÑ','Register now','Ð—Ð°Ñ€ÐµÐµÑÑ‚Ñ€ÑƒÐ²Ð°Ñ‚Ð¸ÑÑ'),(76,'ÐÐ°Ð²Ð¸Ð³Ð°Ñ†Ð¸Ñ','Navigation','ÐÐ°Ð²iÐ³Ð°Ñ†iÑ'),(78,'ÐœÑÐ½ÐµÐ´Ð¶ÐµÑ€','Manager','ÐœÐµÐ½ÐµÐ´Ð¶ÐµÑ€'),(79,'Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½','Phone','Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½'),(80,'ÐÐ°Ð±Ñ€Ð°Ñ‚ÑŒ Ð½Ð¾Ð¼ÐµÑ€','Dial number','ÐÐ°Ð±Ñ€Ð°Ñ‚Ð¸ Ð½Ð¾Ð¼ÐµÑ€'),(81,'Ð“Ñ€ÑƒÐ¿Ð¿Ñ‹ Ð´Ð¾Ð·Ð²Ð¾Ð½Ð°:','',NULL),(82,'ÐŸÐ¾Ð¸ÑÐº...','Searching ...',NULL),(83,'ÐŸÑ€Ð¾Ð¿ÑƒÑ‰ÐµÐ½Ð½Ñ‹Ð¹ Ð·ÐºÐ¾Ð½Ð¾Ðº','Missed son-of-the-son',NULL),(84,'ÐŸÑ€Ð¾Ð¿Ð°Ð» UP-Ð»Ð¸Ð½Ðº !','',NULL),(85,'Ð—Ð°Ð¿Ð¸ÑÐ¸ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²:','',NULL),(86,'Ð”Ð°Ñ‚Ð°','Date',NULL),(87,'ÐšÑ‚Ð¾ Ð·Ð²Ð¾Ð½Ð¸Ð»','Who called?',NULL),(88,'ÐšÑƒÐ´Ð° Ð·Ð²Ð¾Ð½Ð¸Ð»','Where did you call?',NULL),(89,'ÐžÐ¶Ð¸Ð´Ð°Ð½Ð¸Ðµ','Wait',NULL),(90,'Ð Ð°Ð·Ð³Ð¾Ð²Ð¾Ñ€','The Conversation',NULL),(91,'ÐšÐ°Ð½Ð°Ð»','Channel',NULL),(92,'Ð—Ð°Ð¿Ð¸ÑÑŒ','Record',NULL),(93,'','',NULL),(94,'Ð“Ñ€ÑƒÐ¿Ð¿Ñ‹ Ð´Ð¾Ð·Ð²Ð¾Ð½Ð°','Dial groups',NULL),(95,'ÐŸÑ€Ð¾Ð¿Ð°Ð» UP-Ð»Ð¸Ð½Ðº','UP-Link Missing',NULL),(96,'Ð—Ð°Ð¿Ð¸ÑÐ¸ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²','Call Entries',NULL),(97,'ÐÐºÑ‚Ð¸Ð²Ð½Ñ‹Ðµ Ð·Ð²Ð¾Ð½ÐºÐ¸','Active Calls',NULL),(98,'Ð¡Ñ‚Ð°Ñ€Ñ‚ Ð·Ð²Ð¾Ð½ÐºÐ°','Start Call',NULL),(99,'ÐžÐºÐ¾Ð½Ñ‡Ð°Ð½Ð¸Ðµ','End',NULL),(100,'ÐžÐ±Ñ‰Ð°Ñ Ð´Ð»Ð¸Ñ‚.','Total dits.',NULL),(101,'Ð¡Ñ‚Ð°Ñ€Ñ‚','Start',NULL),(102,'ÐžÑ‚Ð²ÐµÑ‡ÐµÐ½','Responded',NULL),(103,'Ð”ÐµÐ¹ÑÑ‚Ð²Ð¸Ñ','Actions',NULL),(104,'translateText','translateText',NULL),(105,'ÐÐµÑ‚ SIP Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½Ð°','No SIP Phone',NULL),(106,'ÐžÑ‚ÐºÐ»ÑŽÑ‡ÐµÐ½','Disconnected',NULL),(107,'ÐÐ°Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ','Direction',NULL),(108,'ÐžÐ½Ð»Ð°Ð¹Ð½','Online',NULL),(109,'Ð¢Ð¸Ð¿','Type',NULL),(110,'ÐžÐ±Ð½Ð¾Ð²Ð¸Ñ‚ÑŒ','Refresh',NULL),(111,'Ð˜ÑÑ…Ð¾Ð´ÑÑ‰Ð¸Ðµ','Outgoing',NULL),(112,'Ð’Ñ…Ð¾Ð´ÑÑ‰Ð¸Ðµ','Inbox',NULL),(113,'ÐŸÑ€Ð¾Ð¿ÑƒÑ‰ÐµÐ½Ð½Ñ‹Ðµ','Missed',NULL),(114,'ÐÐµÑ‚ Ð°ÐºÑ‚Ð¸Ð²Ð½Ñ‹Ñ… Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²','No active calls',NULL),(115,'ÐŸÐ¾Ð¸ÑÐº','Search',NULL),(116,'ÐÐ°Ð±Ð¸Ñ€Ð°ÐµÐ¼ Ð½Ð¾Ð¼ÐµÑ€','',NULL),(117,'Ð—Ð²Ð¾Ð½Ð¾Ðº Ð¾ÐºÐ¾Ð½Ñ‡ÐµÐ½','The Call is Over',NULL),(118,'Ð Ð°Ð·Ð³Ð¾Ð²Ð¾Ñ€ Ñ','Chat with',NULL),(119,'Ð¾Ñ‚','from',NULL),(120,'Ð’Ñ…Ð¾Ð´ÑÑ‰Ð¸Ð¹ Ð·Ð²Ð¾Ð½Ð¾Ðº','Incoming call',NULL),(121,'ÐŸÑ€Ð¾Ð¸Ð³Ñ€Ð°Ñ‚ÑŒ','Play',NULL),(122,'ÐŸÑ€Ð¾ÑÐ»ÑƒÑˆÐ°Ñ‚ÑŒ','Listen',NULL),(123,'Ð¡Ð¾ÐµÐ´Ð¸Ð½ÐµÐ½ Ñ','Connected to',NULL),(124,'Ð˜ÑÑ‚Ð¾Ñ€Ð¸Ñ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²','Call History',NULL);
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-20 23:22:07
