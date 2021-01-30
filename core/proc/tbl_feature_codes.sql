-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: localhost    Database: mpbx
-- ------------------------------------------------------
-- Server version	5.7.24

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
  `description` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `context` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `exten` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `app` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `appdata` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `about` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`context`,`exten`,`priority`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feature_codes`
--

LOCK TABLES `feature_codes` WRITE;
/*!40000 ALTER TABLE `feature_codes` DISABLE KEYS */;
INSERT INTO `feature_codes` VALUES (25,NULL,3,0,'LOGOFF To certain Queue by Reference','internal-Armour-features','_*0#X.',1,'Gosub','app-set-opt,s,1(queue_logoff,${EXTEN:3})','Logs  member OFF  mentioned Qeueue ( by integer ID in its name) for   current extesion interface ( if it present as member  )\n\n User must specify existing Queue ID to log into it\n User can be logged in into many queues\n'),(22,NULL,3,0,'Login To certain Queue by Reference','internal-Armour-features','_*1#X.',1,'Gosub','app-set-opt,s,1(queue_logon,${EXTEN:3})','Logs  member on mentioned Qeueue ( by integer ID in its name) for   current extesion interface ( if it present as member  )\n\n User must specify existing Queue ID to log into it\n User can be logged in into many queues\n'),(20,NULL,3,0,'all_queues_logoff','internal-Armour-features','*0#*',1,'Gosub','app-set-opt,s,1(queue_logoff,1)','DTMF code  used to remote user  TO SET PAUSE=YES  ON  all queues, where this member is assigned to \n it does NOT  remove user from the queue, just update pause '),(21,NULL,3,0,'all_queues_logon','internal-Armour-features','*1#*',1,'Gosub','app-set-opt,s,1(queue_logon,1)','DTMF code  used by  remote user  to resume receiving calls \n( it sets PAUSE=NO)   ON  all queues, where this member is assigned to \n it does NOT  remove or add member  from/to  the queue, just update pause  status'),(9,NULL,3,0,'agent-pause-inqueues','internal-Armour-features','*110',1,'Gosub','agent-pause,s,1','Pauses  (blocks calls for) a queue member equal to caller.\nThe given extension will be paused in  queues. \nThis prevents any calls from being sent from the queue to the interface until it is unpaused with another feature code or by the manager in the GUI interface.'),(10,NULL,3,0,'agent-unpause-inqueues','internal-Armour-features','*120',1,'Gosub','agent-unpause,s,1','Unoause The caller in  queues in the  system, and start receive calls. \n'),(12,NULL,3,0,'Extensions voicemail box','internal-default-features','_*11X.',1,'Gosub','app-exten-vmail,s,1(${EXTEN})','Transfer incoming caller directly to extensions voicemail Box.  While conversation, press:\n ##*85'),(13,NULL,3,0,'Call Listen','internal-joe-features','_*221X.',1,'Gosub','app-call-listen,s,1(${EXTEN})','Listen: Monitor an agents call. The manager can hear both the agent and client channels, but no-one can hear the manager'),(14,NULL,3,0,'Call Whisper','internal-joe-features','_*222X.',1,'Gosub','app-call-whisper,s,1(${EXTEN})','Whisper:  Whisper to the agent. The manager can hear both the agent and client channels, and the agent can also hear the manager, but the client can only hear the agent, hence â€œwhisper.â€\n\n'),(15,NULL,3,0,'Call Barge','internal-joe-features','_*223X.',1,'Gosub','app-call-barge,s,1(${EXTEN})','Barge: Barge in on both channels. The manager channel is joined onto the agent and client channels, and all parties can hear each other. Be warned, if the original agent leaves the call, the call is dropped. This is not a 3-way call.\n(However you can barge in, and when comfortable, initiate a 3way call to your extension so you can continue the call without the agent. This procedure varies from client to client (soft/hard phones))'),(16,NULL,3,0,'app-set-options','internal-joe-features','*000',1,'Gosub','app-set-opt,s,1(dnd,0)','Do Not disturb option  Disabled\n calls will be connected with extension'),(18,NULL,3,0,'pbx-service Logon','internal-joe-features','*11',1,'Gosub','app-pbx-service,s,1(userlogon)','User Logon from current extension:\n He will be prompted for extension number and password\n then current extension will start receive call for provided extension'),(17,NULL,3,0,'app-set-options','internal-joe-features','*111',1,'Gosub','app-set-opt,s,1(dnd,1)','Do Not disturb option  ENABLED\n calls will be connected with extension'),(19,NULL,3,0,'pbx-service Logoff','internal-joe-features','*12',1,'Gosub','app-pbx-service,s,1(userlogoff)','User LogOFF from current extension ( release current exten)\n no questions will be asked - just remove this extension from  hotDesk registry'),(1,2,3,0,'Check VoiceMail with PIN','internal-joe-features','*95',1,'Gosub','app-check-vmail,s,1(${EXTEN})','Dial this code to \nCheck VoiceMail with PIN\n\n. '),(2,2,3,0,'Check VoiceMail with no  PIN','internal-parm-features','_*98.',1,'Gosub','app-check-vmail,s,s,1(${EXTEN})','Check VoiceMail with no  PIN, works for local extensions '),(4,NULL,3,0,'Call exten speacker-phone','internal-parm-features','_*99.',1,'Gosub','app-intercom,3,s,1(${EXTEN})','Connect directly to users speaker phone by dialing a code plus the extension'),(3,NULL,3,0,'Check VideoMail','internal-parm-features','*97',1,'Gosub','app-check-vmail,s,1(${EXTEN})','Same as Check VoiceMail, but play Video Message is available'),(8,NULL,3,0,'pagegroup-access','internal-scnd-features','_*33.',1,'Gosub','app-pagegroup-access,s,1(${EXTEN})','Call To PageGroup access number to page a group of extensions. \n To dial into page group access number 54321, you have to dial:\n\n*3354321'),(7,NULL,3,0,'Call to FolloeMe','internal-scnd-features','_*44.',1,'Gosub','app-followme,s,1(${EXTEN})','Dial into FollowME ID ,  followed after 44,  \n this ID is equal to extension, the code: *44101 \nwill connect with 101 ext followme'),(6,NULL,3,0,'PickUp call on extension','internal-scnd-features','_*8X.',1,'Gosub','app-call-pickup,s,1(${EXTEN})','PickUp call on certain  extension, followed after 8.\nExample: to pickup a call for 201,  Dial feature code :\n  *8201\n'),(11,NULL,3,0,'test ','internal-scnd-features','*600',1,'Gosub','app-echo,s,1(${EXTEN})','Start Echo Test program that echos audio read back to the user'),(5,NULL,3,0,'Extension\'s voicemail box','internal-scnd-features','*85',1,'Gosub','app-exten-vmail,s,1(${EXTEN})','Transfer incoming caller directly to extension\'s voicemail Box.  While conversation, press:\n ##*85');
/*!40000 ALTER TABLE `feature_codes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-17 16:32:30
