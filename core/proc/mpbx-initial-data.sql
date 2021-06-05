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
-- Dumping data for table `admin_user_roles`
--

LOCK TABLES `admin_user_roles` WRITE;
/*!40000 ALTER TABLE `admin_user_roles` DISABLE KEYS */;
INSERT INTO `admin_user_roles` VALUES (1,'Global administrator','Super administrator for all tenants'),(2,'Tenant Admin','Tenant administrator for  assigned tenant'),(3,'Tenant User','Tenant User'),(4,'Operator','Tenant Operator on CRM '),(5,'Phone','Phone extension profile'),(6,'Disabled','Account disabled, no any activity enabled');
/*!40000 ALTER TABLE `admin_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','changeme!',61,1,'2021-06-05 12:17:41','[1]','','Graphite','',NULL,NULL,190);
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;


LOCK TABLES `feature_codes` WRITE;
/*!40000 ALTER TABLE `feature_codes` DISABLE KEYS */;
INSERT INTO `feature_codes` VALUES (12,NULL,3,0,'Extensions voicemail box','internal-default-features','_*11X.',1,'Gosub','app-exten-vmail,s,1(${EXTEN})','Transfer incoming caller directly to extensions voicemail Box.  While conversation, press:\n ##*85'),(16,NULL,3,0,'app-set-options','internal-joe-features','*000',1,'Gosub','app-set-opt,s,1(dnd,0)','Do Not disturb option  Disabled\n calls will be connected with extension'),(18,NULL,3,0,'pbx-service Logon','internal-joe-features','*11',1,'Gosub','app-pbx-service,s,1(userlogon)','User Logon from current extension:\n He will be prompted for extension number and password\n then current extension will start receive call for provided extension'),(17,NULL,3,0,'app-set-options','internal-joe-features','*111',1,'Gosub','app-set-opt,s,1(dnd,1)','Do Not disturb option  ENABLED\n calls will be connected with extension'),(19,NULL,3,0,'pbx-service Logoff','internal-joe-features','*12',1,'Gosub','app-pbx-service,s,1(userlogoff)','User LogOFF from current extension ( release current exten)\n no questions will be asked - just remove this extension from  hotDesk registry'),(21,NULL,3,0,'app-pbx-service-hrlogoff','internal-joe-features','*440',1,'Gosub','app-pbx-service,s,1(hrlogoff)','Logout HR'),(20,NULL,3,0,'app-pbx-service-hrlogon','internal-joe-features','*441',1,'Gosub','app-pbx-service,s,1(hrlogon)','HR department - regiser LOGON event'),(1,2,3,0,'Check VoiceMail with PIN','internal-joe-features','*95',1,'Gosub','app-check-vmail,s,1(${EXTEN})','Dial this code to \nCheck VoiceMail with PIN\n\n. '),(13,NULL,3,0,'Call Listen','internal-joe-features','_*221X.',1,'Gosub','app-call-listen,s,1(${EXTEN})','Listen: Monitor an agents call. The manager can hear both the agent and client channels, but no-one can hear the manager'),(14,NULL,3,0,'Call Whisper','internal-joe-features','_*222X.',1,'Gosub','app-call-whisper,s,1(${EXTEN})','Whisper:  Whisper to the agent. The manager can hear both the agent and client channels, and the agent can also hear the manager, but the client can only hear the agent, hence â€œwhisper.â€\n\n'),(15,NULL,3,0,'Call Barge','internal-joe-features','_*223X.',1,'Gosub','app-call-barge,s,1(${EXTEN})','Barge: Barge in on both channels. The manager channel is joined onto the agent and client channels, and all parties can hear each other. Be warned, if the original agent leaves the call, the call is dropped. This is not a 3-way call.\n(However you can barge in, and when comfortable, initiate a 3way call to your extension so you can continue the call without the agent. This procedure varies from client to client (soft/hard phones))'),(3,NULL,3,0,'Check VideoMail','internal-parm-features','*97',1,'Gosub','app-check-vmail,s,1(${EXTEN})','Same as Check VoiceMail, but play Video Message is available'),(2,2,3,0,'Check VoiceMail with no  PIN','internal-parm-features','_*98.',1,'Gosub','app-check-vmail,s,s,1(${EXTEN})','Check VoiceMail with no  PIN, works for local extensions '),(4,NULL,3,0,'Call exten speacker-phone','internal-parm-features','_*99.',1,'Gosub','app-intercom,3,s,1(${EXTEN})','Connect directly to users speaker phone by dialing a code plus the extension'),(9,NULL,3,0,'agent-pause-inqueues','internal-scnd-features','*110',1,'Gosub','agent-pause,s,1(${EXTEN})','Pauses  (blocks calls for) a queue member equal to caller.\nThe given extension will be paused in all queues. This prevents any calls from being sent from the queue to the interface until it is unpaused with another feature code or by the manager in the GUI interface.'),(10,NULL,3,0,'agent-unpause-inqueues','internal-scnd-features','*120',1,'Gosub','agent-unpause,s,1(${EXTEN})','Unoause The caller in all queues in the  system, and start receive calls. '),(11,NULL,3,0,'test ','internal-scnd-features','*600',1,'Gosub','app-echo,s,1(${EXTEN})','Start Echo Test program that echos audio read back to the user'),(5,NULL,3,0,'Extension\'s voicemail box','internal-scnd-features','*85',1,'Gosub','app-exten-vmail,s,1(${EXTEN})','Transfer incoming caller directly to extension\'s voicemail Box.  While conversation, press:\n ##*85'),(8,NULL,3,0,'pagegroup-access','internal-scnd-features','_*33.',1,'Gosub','app-pagegroup-access,s,1(${EXTEN})','Call To PageGroup access number to page a group of extensions. \n To dial into page group access number 54321, you have to dial:\n\n*3354321'),(7,NULL,3,0,'Call to FolloeMe','internal-scnd-features','_*44.',1,'Gosub','app-followme,s,1(${EXTEN})','Dial into FollowME ID ,  followed after 44,  \n this ID is equal to extension, the code: *44101 \nwill connect with 101 ext followme'),(6,NULL,3,0,'PickUp call on extension','internal-scnd-features','_*8X.',1,'Gosub','app-call-pickup,s,1(${EXTEN})','PickUp call on certain  extension, followed after 8.\nExample: to pickup a call for 201,  Dial feature code :\n  *8201\n');
/*!40000 ALTER TABLE `feature_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `mtree`
--

LOCK TABLES `mtree` WRITE;
/*!40000 ALTER TABLE `mtree` DISABLE KEYS */;
INSERT INTO `mtree` VALUES (30,'Current Tenant PBX','',10,NULL,1),(40,'Extensions','extensions',30,'/images/Extensions.png',1),(50,'Inbound DIDs','inbound',30,'/images/Inbound.png',1),(60,'Outbound Routes','routes',30,'/images/Routes.png',1),(14,'Tenants','tenants',10,'/images/Tenants.png',1),(15,'Trunks','trunks',10,'/images/Trunks.png',1),(16,'DIDs','dids',10,'/images/Dids.png',1),(71,'Auto Attendant','ivrmenu',30,'/images/Ivrmenu.png',1),(81,'MOH Profiles','moh',220,'/images/Moh.png',1),(19,'Default Music-on-Hold','mohdefault',20,'/images/Mohdefault.png',1),(21,'Default Recordings','snddefault',20,'/images/Snddefault.png',1),(220,'Media','',30,NULL,1),(82,'Recordings','sndtenants',220,'/images/Sndtenants.png',1),(91,'Ring Groups','ringgroups',30,'/images/Ringgroups.png',1),(101,'Shifts','shifts',500,'/images/Timefilters.png',1),(94,'Queues','queues',30,'/images/Queues.png',1),(111,'Conferences','conferences',30,'/images/Conferences.png',1),(150,'Feature Codes','features',30,'/images/Features.png',1),(20,'Tenant Defaults','',10,NULL,1),(18,'Feature Codes','featuresdef',20,'/images/Featuresdef.png',1),(400,'Call Center Functions','recordings',30,NULL,1),(300,'Call Recordings','recordings',500,'/images/recordings.png',1),(410,'Predictive Dialer','campaigns',400,'/images/Campaigns.png',1),(13,'Administrators','adminusers',10,'/images/Adminusers.png',1),(310,'CDRs','cdrs',500,'/images/cdrs.png',1),(92,'Page Groups','pagegroups',30,'/images/Ringgroups.png',1),(2,'Dashboard','dashboard',10,'/images/Dashboard.png',1),(95,'Lost Calls Report','lostcalls',500,'/images/missed_calls.png',1),(23,'Black List IP','blacklist',20,'/images/Blacklist.png',1),(500,'Reports','',10,'/images/reports.png',1),(501,'Summary reports','summary_reports',500,'/images/summary_reports.png',1),(5,'User Status','extenstatus',10,'/images/Conferences.png',1),(510,'DID usage stats','dids_usage',500,'/images/DIDs.png',1);
/*!40000 ALTER TABLE `mtree` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sip_conf`
--

LOCK TABLES `sip_conf` WRITE;
/*!40000 ALTER TABLE `sip_conf` DISABLE KEYS */;
/*!40000 ALTER TABLE `sip_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `speech`
--

LOCK TABLES `speech` WRITE;
/*!40000 ALTER TABLE `speech` DISABLE KEYS */;
INSERT INTO `speech` VALUES (35,'hello my dad','1567631222.86',0.53),(36,'let me go to bed','1567631785.88',0.20),(37,'front desk many other public pension','1567631958.90',0.60),(38,'the card is','1567631999.92',0.03),(39,'','1581287140.604',-1.00);
/*!40000 ALTER TABLE `speech` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `speech_suggest`
--

LOCK TABLES `speech_suggest` WRITE;
/*!40000 ALTER TABLE `speech_suggest` DISABLE KEYS */;
INSERT INTO `speech_suggest` VALUES ('school');
/*!40000 ALTER TABLE `speech_suggest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `t_campaign_leads`
--

LOCK TABLES `t_campaign_leads` WRITE;
/*!40000 ALTER TABLE `t_campaign_leads` DISABLE KEYS */;
INSERT INTO `t_campaign_leads` VALUES (227685,1,11,NULL,'9','','new',NULL,NULL,NULL,NULL,'9','05494E+11','','','','','','','',''),(227686,1,12,NULL,'9','','new',NULL,NULL,NULL,NULL,'9','05494E+11','','','','','','','',''),(227687,1,13,NULL,'9','','new',NULL,NULL,NULL,NULL,'9','05494E+11','','','','','','','','');
/*!40000 ALTER TABLE `t_campaign_leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `t_campaign_leads_tmp`
--

LOCK TABLES `t_campaign_leads_tmp` WRITE;
/*!40000 ALTER TABLE `t_campaign_leads_tmp` DISABLE KEYS */;
INSERT INTO `t_campaign_leads_tmp` VALUES (NULL,1,13,NULL,'9','','new',NULL,NULL,NULL,NULL,'9','05494E+11','','','','','','','','');
/*!40000 ALTER TABLE `t_campaign_leads_tmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `t_campaigns`
--

LOCK TABLES `t_campaigns` WRITE;
/*!40000 ALTER TABLE `t_campaigns` DISABLE KEYS */;
INSERT INTO `t_campaigns` VALUES (13,1,'test',NULL,'queue','287',1,NULL,1,0,0,'RUNNING',NULL,1,60,NULL),(14,61,'john','Peter','hangup','',20,NULL,0,0,0,'STOPPED','lgkum',2,NULL,NULL);
/*!40000 ALTER TABLE `t_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `t_cdrs`
--


LOCK TABLES `t_cdrs_archive` WRITE;
/*!40000 ALTER TABLE `t_cdrs_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_cdrs_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `t_conferences`
--

LOCK TABLES `t_conferences` WRITE;
/*!40000 ALTER TABLE `t_conferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_conferences` ENABLE KEYS */;
UNLOCK TABLES;

--

--
-- Dumping data for table `t_moh`
--

LOCK TABLES `t_moh` WRITE;
/*!40000 ALTER TABLE `t_moh` DISABLE KEYS */;
INSERT INTO `t_moh` VALUES (35,'def-moh','def-moh','','files','','random','slin',1,NULL,'',NULL),(36,'default-test','default-test','','files','','random','slin',61,NULL,'',NULL);


--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'Default','default',10,0,'7001','[204]',200,'first',130,'no','def-9a7d',NULL,'sorry. we can not dial this number',NULL,NULL,'1111','',1,NULL,0,0,0,NULL,NULL,NULL,NULL,1,223,NULL,'18:00','[0]',90,NULL,NULL,NULL,NULL,NULL,NULL);

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (65,'Ð’Ð¾Ð¹Ñ‚Ð¸','Sign IN','Ð£Ð²iÐ¹Ñ‚Ð¸','GiriÅŸ','Entrare','à¤²à¥‰à¤— à¤‡à¤¨ à¤•à¤°à¥‡à¤‚'),(66,'Ð¡Ð¾ÑÑ‚Ð¾ÑÐ½Ð¸Ðµ','Status Page','Ð¡Ñ‚Ð°Ñ‚ÑƒÑ','Durumu','Lo stato','à¤¹à¤¾à¤²à¤¤'),(67,'ÐžÑ‚Ð´ÐµÐ» Ð¿Ð¾ Ñ€Ð°Ð±Ð¾Ñ‚Ðµ Ñ Ð°Ð±Ð¾Ð½ÐµÐ½Ñ‚Ð°Ð¼Ð¸ | Callcenter','Customer care department | Callcenter','Ð’iÐ´Ð´iÐ» Ð¿Ð¾ Ñ€Ð¾Ð±Ð¾Ñ‚i Ð· Ð°Ð±Ð¾Ð½ÐµÐ½Ñ‚Ð°Ð¼Ð¸ | ÐšÐ¾Ð»Ñ†ÐµÐ½Ñ‚Ñ€ ','DepartmanÄ± arayanlar | Callcenter','Il dipartimento per gli iscritti | Callcenter','à¤µà¤¿à¤­à¤¾à¤— à¤•à¥‡ à¤•à¤¾à¤® à¤•à¥‡ à¤¸à¤¾à¤¥ à¤—à¥à¤°à¤¾à¤¹à¤•à¥‹à¤‚ | à¤•à¥‰à¤²à¤¸à¥‡à¤‚à¤Ÿà¤°'),(68,'ÐÐ±Ð¾Ð½ÐµÐ½Ñ‚ÑÐºÐ¸Ð¹ Ð¾Ñ‚Ð´ÐµÐ»','Customer care department','Ð’iÐ´Ð´iÐ» Ð¿Ð¾ Ñ€Ð¾Ð±Ð¾Ñ‚i Ð· Ð°Ð±Ð¾Ð½ÐµÐ½Ñ‚Ð°Ð¼Ð¸ ','Abone bÃ¶lÃ¼mÃ¼','P. o. dipartimento','à¤—à¥à¤°à¤¾à¤¹à¤• à¤¸à¥‡à¤µà¤¾ à¤µà¤¿à¤­à¤¾à¤—'),(69,'ÐžÐ¿ÐµÑ€Ð°Ñ‚Ð¾Ñ€','operator','ÐžÐ¿ÐµÑ€Ð°Ñ‚Ð¾Ñ€','OperatÃ¶r','Loperatore','à¤‘à¤ªà¤°à¥‡à¤Ÿà¤°'),(70,'Ð›Ð¾Ð³Ð¸Ð½','Login','Ð›Ð¾Ð³Ð¸Ð½','GiriÅŸ','Login','à¤²à¥‰à¤—à¤¿à¤¨'),(71,'Ð¿Ð°Ñ€Ð¾Ð»ÑŒ','Password','ÐŸÐ°Ñ€Ð¾Ð»ÑŒ','ÅŸifre','la password','à¤ªà¤¾à¤¸à¤µà¤°à¥à¤¡'),(72,'Ð—Ð°Ð¿Ð¾Ð¼Ð½Ð¸Ñ‚ÑŒ Ð¼ÐµÐ½Ñ','Remember me','Ð—Ð°Ð¿Ð°Ð¼\"ÑÑ‚Ð°Ñ‚Ð¸ Ð¼ÐµÐ½Ðµ','Beni hatÄ±rla','Ricordati di me','à¤®à¥à¤à¥‡ à¤¯à¤¾à¤¦ à¤¹à¥ˆ'),(73,'Ð—Ð°Ð±Ñ‹Ð» Ð¿Ð°Ñ€Ð¾Ð»ÑŒ?','Forgot password?','Ð—Ð°Ð±ÑƒÐ»Ð¸ Ð¿Ð°Ñ€Ð¾Ð»Ñ?','Åžifremi unuttum?','Dimenticato la password?','à¤ªà¤¾à¤¸à¤µà¤°à¥à¤¡ à¤­à¥‚à¤² à¤—à¤?'),(74,'ÐÐµÑ‚ ÑƒÑ‡ÐµÑ‚Ð½Ð¾Ð¹ Ð·Ð°Ð¿Ð¸ÑÐ¸?','Have no account?','ÐÐµÐ¼Ð°ÐµÑ‚Ðµ Ð»Ð¾Ð³iÐ½Ñƒ?','HesabÄ±nÄ±z yok?','Nessun account?','à¤•à¥‹à¤ˆ à¤–à¤¾à¤¤à¤¾ à¤¨à¤¹à¥€à¤‚ à¤¹à¥ˆ?'),(75,'Ð—Ð°Ñ€ÐµÐ³Ð¸ÑÑ‚Ñ€Ð¸Ñ€Ð¾Ð²Ð°Ñ‚ÑÑ','Register now','Ð—Ð°Ñ€ÐµÐµÑÑ‚Ñ€ÑƒÐ²Ð°Ñ‚Ð¸ÑÑ','Ð—Ð°Ñ€ÐµÐ³Ð¸ÑÑ‚Ñ€Ð¸Ñ€Ð¾Ð²Ð°Ñ‚ÑÑ','Contenuti','à¤°à¤œà¤¿à¤¸à¥à¤Ÿà¤°'),(76,'ÐÐ°Ð²Ð¸Ð³Ð°Ñ†Ð¸Ñ','Navigation','ÐÐ°Ð²iÐ³Ð°Ñ†iÑ',NULL,NULL,NULL),(78,'ÐœÑÐ½ÐµÐ´Ð¶ÐµÑ€','Manager','ÐœÐµÐ½ÐµÐ´Ð¶ÐµÑ€',NULL,NULL,NULL),(79,'Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½','Phone','Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½',NULL,NULL,NULL),(80,'ÐÐ°Ð±Ñ€Ð°Ñ‚ÑŒ Ð½Ð¾Ð¼ÐµÑ€','Dial number','ÐÐ°Ð±Ñ€Ð°Ñ‚Ð¸ Ð½Ð¾Ð¼ÐµÑ€','NumarayÄ±','Comporre il numero','à¤¡à¤¾à¤¯à¤²'),(81,'Ð“Ñ€ÑƒÐ¿Ð¿Ñ‹ Ð´Ð¾Ð·Ð²Ð¾Ð½Ð°:','',NULL,NULL,NULL,NULL),(82,'ÐŸÐ¾Ð¸ÑÐº...','Searching ...',NULL,'Arama...','La ricerca...','à¤–à¥‹à¤œ...'),(83,'ÐŸÑ€Ð¾Ð¿ÑƒÑ‰ÐµÐ½Ð½Ñ‹Ð¹ Ð·ÐºÐ¾Ð½Ð¾Ðº','Missed son-of-the-son',NULL,'CevapsÄ±z Ð·ÐºÐ¾Ð½Ð¾Ðº','Persa Ð·ÐºÐ¾Ð½Ð¾Ðº','à¤¯à¤¾à¤¦ sconox'),(84,'ÐŸÑ€Ð¾Ð¿Ð°Ð» UP-Ð»Ð¸Ð½Ðº !','',NULL,NULL,NULL,NULL),(85,'Ð—Ð°Ð¿Ð¸ÑÐ¸ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²:','',NULL,NULL,NULL,NULL),(86,'Ð”Ð°Ñ‚Ð°','Date',NULL,NULL,NULL,NULL),(87,'ÐšÑ‚Ð¾ Ð·Ð²Ð¾Ð½Ð¸Ð»','Who called?',NULL,NULL,NULL,NULL),(88,'ÐšÑƒÐ´Ð° Ð·Ð²Ð¾Ð½Ð¸Ð»','Where did you call?',NULL,NULL,NULL,NULL),(89,'ÐžÐ¶Ð¸Ð´Ð°Ð½Ð¸Ðµ','Wait',NULL,NULL,NULL,NULL),(90,'Ð Ð°Ð·Ð³Ð¾Ð²Ð¾Ñ€','The Conversation',NULL,'KonuÅŸma','Conversazione','à¤¬à¤¾à¤¤à¤šà¥€à¤¤'),(91,'ÐšÐ°Ð½Ð°Ð»','Channel',NULL,NULL,NULL,NULL),(92,'Ð—Ð°Ð¿Ð¸ÑÑŒ','Record',NULL,'KayÄ±t','Registrazione','à¤ªà¥à¤°à¤µà¤¿à¤·à¥à¤Ÿà¤¿'),(93,'','',NULL,NULL,NULL,NULL),(94,'Ð“Ñ€ÑƒÐ¿Ð¿Ñ‹ Ð´Ð¾Ð·Ð²Ð¾Ð½Ð°','Dial groups',NULL,NULL,NULL,NULL),(95,'ÐŸÑ€Ð¾Ð¿Ð°Ð» UP-Ð»Ð¸Ð½Ðº','UP-Link Missing',NULL,'KayÄ±p UP-link','E  scomparso UP-link','à¤Šà¤ªà¤° à¤šà¤²à¤¾ à¤—à¤¯à¤¾ à¤¹à¥ˆ-à¤²à¤¿à¤‚à¤•'),(96,'Ð—Ð°Ð¿Ð¸ÑÐ¸ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²','Call Entries',NULL,NULL,NULL,NULL),(97,'ÐÐºÑ‚Ð¸Ð²Ð½Ñ‹Ðµ Ð·Ð²Ð¾Ð½ÐºÐ¸','Active Calls',NULL,'Etkin aramalar','Le chiamate attive','à¤¸à¤•à¥à¤°à¤¿à¤¯ à¤•à¥‰à¤²'),(98,'Ð¡Ñ‚Ð°Ñ€Ñ‚ Ð·Ð²Ð¾Ð½ÐºÐ°','Start Call',NULL,NULL,NULL,NULL),(99,'ÐžÐºÐ¾Ð½Ñ‡Ð°Ð½Ð¸Ðµ','End',NULL,'BitiÅŸ','Fine','à¤…à¤‚à¤¤'),(100,'ÐžÐ±Ñ‰Ð°Ñ Ð´Ð»Ð¸Ñ‚.','Total dits.',NULL,'Toplam sÃ¼re.','Totale durata.','à¤•à¥à¤² à¤…à¤µà¤§à¤¿.'),(101,'Ð¡Ñ‚Ð°Ñ€Ñ‚','Start',NULL,'BaÅŸlangÄ±Ã§','Inizio','à¤¶à¥à¤°à¥‚'),(102,'ÐžÑ‚Ð²ÐµÑ‡ÐµÐ½','Responded',NULL,'Cevap','Risposta','à¤œà¤µà¤¾à¤¬'),(103,'Ð”ÐµÐ¹ÑÑ‚Ð²Ð¸Ñ','Actions',NULL,'Eylem','Azione','à¤•à¤¾à¤°à¥à¤°à¤µà¤¾à¤ˆ'),(104,'translateText','translateText',NULL,NULL,NULL,NULL),(105,'ÐÐµÑ‚ SIP Ð¢ÐµÐ»ÐµÑ„Ð¾Ð½Ð°','No SIP Phone',NULL,NULL,NULL,'à¤•à¥‹à¤ˆ à¤˜à¥‚à¤‚à¤Ÿ à¤«à¥‹à¤¨'),(106,'ÐžÑ‚ÐºÐ»ÑŽÑ‡ÐµÐ½','Disconnected',NULL,'Devre dÄ±ÅŸÄ±','Disabilitato','à¤µà¤¿à¤•à¤²à¤¾à¤‚à¤—'),(107,'ÐÐ°Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ','Direction',NULL,NULL,NULL,NULL),(108,'ÐžÐ½Ð»Ð°Ð¹Ð½','Online',NULL,'Online','Online',NULL),(109,'Ð¢Ð¸Ð¿','Type',NULL,'TÃ¼rÃ¼','Tipo','à¤ªà¥à¤°à¤•à¤¾à¤°'),(110,'ÐžÐ±Ð½Ð¾Ð²Ð¸Ñ‚ÑŒ','Refresh',NULL,'Yenile','Aggiornare','à¤…à¤¦à¥à¤¯à¤¤à¤¨'),(111,'Ð˜ÑÑ…Ð¾Ð´ÑÑ‰Ð¸Ðµ','Outgoing',NULL,'Giden','In uscita','à¤¨à¤¿à¤µà¤°à¥à¤¤à¤®à¤¾à¤¨'),(112,'Ð’Ñ…Ð¾Ð´ÑÑ‰Ð¸Ðµ','Inbox',NULL,NULL,'In arrivo','à¤†à¤¨à¥‡ à¤µà¤¾à¤²à¥€'),(113,'ÐŸÑ€Ð¾Ð¿ÑƒÑ‰ÐµÐ½Ð½Ñ‹Ðµ','Missed',NULL,'CevapsÄ±z','Perse','à¤¯à¤¾à¤¦ à¤•à¤¿à¤¯à¤¾'),(114,'ÐÐµÑ‚ Ð°ÐºÑ‚Ð¸Ð²Ð½Ñ‹Ñ… Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²','No active calls',NULL,'HayÄ±r aktif aramalar','No le chiamate attive','à¤µà¤¹à¤¾à¤ à¤°à¤¹à¥‡ à¤¹à¥ˆà¤‚ à¤•à¥‹à¤ˆ à¤¸à¤•à¥à¤°à¤¿à¤¯ à¤•à¥‰à¤²'),(115,'ÐŸÐ¾Ð¸ÑÐº','Search',NULL,'Arama','Ricerca','à¤–à¥‹à¤œ'),(116,'ÐÐ°Ð±Ð¸Ñ€Ð°ÐµÐ¼ Ð½Ð¾Ð¼ÐµÑ€','Dialed number',NULL,NULL,'Comporre un numero di',NULL),(117,'Ð—Ð²Ð¾Ð½Ð¾Ðº Ð¾ÐºÐ¾Ð½Ñ‡ÐµÐ½','The Call is Over',NULL,NULL,'La chiamata Ã¨ finita',NULL),(118,'Ð Ð°Ð·Ð³Ð¾Ð²Ð¾Ñ€ Ñ','Chat with',NULL,NULL,'Conversazione con',NULL),(119,'Ð¾Ñ‚','from',NULL,NULL,NULL,NULL),(120,'Ð’Ñ…Ð¾Ð´ÑÑ‰Ð¸Ð¹ Ð·Ð²Ð¾Ð½Ð¾Ðº','Incoming call',NULL,NULL,NULL,NULL),(121,'ÐŸÑ€Ð¾Ð¸Ð³Ñ€Ð°Ñ‚ÑŒ','Play',NULL,NULL,NULL,NULL),(122,'ÐŸÑ€Ð¾ÑÐ»ÑƒÑˆÐ°Ñ‚ÑŒ','Listen',NULL,'Dinle','Ascoltare','à¤•à¤°à¤¨à¥‡ à¤•à¥‡ à¤²à¤¿à¤ à¤¸à¥à¤¨à¥‹'),(123,'Ð¡Ð¾ÐµÐ´Ð¸Ð½ÐµÐ½ Ñ','Connected to',NULL,'BaÄŸlÄ±','Collegato con','à¤•à¤¨à¥‡à¤•à¥à¤Ÿ à¤•à¤°à¤¨à¥‡ à¤•à¥‡ à¤²à¤¿à¤'),(124,'Ð˜ÑÑ‚Ð¾Ñ€Ð¸Ñ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²','Call History',NULL,'Aramalar','Cronologia delle chiamate','à¤•à¥‰à¤² à¤‡à¤¤à¤¿à¤¹à¤¾à¤¸'),(125,'Отдел по работе с абонентами | Callcenter','Customer Service | Callcenter',NULL,'Departmanı arayanlar | Callcenter',NULL,NULL),(126,'Абонентский отдел','Customer Service',NULL,'Abone bölümü',NULL,NULL),(127,'Оператор','Operator',NULL,'Operatör',NULL,NULL),(128,'Логин','Loghin',NULL,'Giriş',NULL,NULL),(129,'пароль','password',NULL,'şifre',NULL,NULL),(130,'Запомнить меня','Remember me',NULL,'Beni hatırla',NULL,NULL),(131,'Войти','Login',NULL,'Giriş',NULL,NULL),(132,'Забыл пароль?','Forgot your password?',NULL,'Şifremi unuttum?',NULL,NULL),(133,'Нет учетной записи?','No account?',NULL,'Hesabınız yok?',NULL,NULL),(134,'Зарегистрироватся','Registered',NULL,'Зарегистрироватся',NULL,NULL),(135,'Поиск...','Searching ...',NULL,'Arama...',NULL,NULL),(136,'Отключен','Disconnected',NULL,'Devre dışı',NULL,NULL),(137,'Набрать номер','Inherit Number',NULL,'Numarayı',NULL,NULL),(138,'Пропущенный зконок','Missed son-of-the-son',NULL,'Cevapsız зконок',NULL,NULL),(139,'Оповещение','Broadcast',NULL,'Uyarı',NULL,NULL),(140,'Пропал UP-линк','UP-Link Missing',NULL,'Kayıp UP-link',NULL,NULL),(141,'Активные звонки','Active Calls',NULL,'Etkin aramalar',NULL,NULL),(142,'Кто звонит','Whos on the phone',NULL,'Kim arıyor',NULL,NULL),(143,'Куда звонит','Wheres the phone?',NULL,'Nereye çağırıyor',NULL,NULL),(144,'Состояние','Status',NULL,'Durumu',NULL,NULL),(145,'Старт','Start',NULL,'Başlangıç',NULL,NULL),(146,'Отвечен','Responded',NULL,'Cevap',NULL,NULL),(147,'Окончание','End',NULL,'Bitiş',NULL,NULL),(148,'Разговор','The Conversation',NULL,'Konuşma',NULL,NULL),(149,'Общая длит.','Total dits.',NULL,'Toplam süre.',NULL,NULL),(150,'Действия','Actions',NULL,'Eylem',NULL,NULL),(151,'История звонков','Call History',NULL,'Aramalar',NULL,NULL),(152,'Дата звонка','Date of call',NULL,'Tarihi çağrı',NULL,NULL),(153,'Тип','Type',NULL,'Türü',NULL,NULL),(154,'От','from',NULL,'Yer',NULL,NULL),(155,'На номер','To Number',NULL,'Numarası',NULL,NULL),(156,'Соединен с','Connected to',NULL,'Bağlı',NULL,NULL),(157,'Запись','Record',NULL,'Kayıt',NULL,NULL),(158,'Печать','Printing',NULL,'Baskı',NULL,NULL),(159,'Обновить','Refresh',NULL,'Yenile',NULL,NULL),(160,'Исходящие','Outgoing',NULL,'Giden',NULL,NULL),(161,'Входящие','Inbox',NULL,'Gelen',NULL,NULL),(162,'Пропущенные','Missed',NULL,'Cevapsız',NULL,NULL),(163,'Нет активных звонков','No active calls',NULL,'Hayır aktif aramalar',NULL,NULL),(164,'Поиск','Search',NULL,'Arama',NULL,NULL),(165,'Нет истории звонков','No Call History',NULL,'Hayır geçmişi',NULL,NULL),(166,'Онлайн','Online',NULL,NULL,NULL,NULL),(167,'Прослушать','Listen',NULL,'Dinle',NULL,NULL),(168,'Авто-ответчик','Auto Defendant',NULL,'Otomatik yanıtlayıcı',NULL,NULL),(169,'Нет SIP Телефона','No SIP Phone',NULL,NULL,NULL,NULL),(170,'ÐžÑ‚Ð²ÐµÑ‚Ð¸Ð»','ANSWERED',NULL,'CEVAP','RISPOSTO',NULL),(171,'Ð’Ñ…Ð¾Ð´ÑÑ‰Ð¸Ðµ','INBOUND',NULL,'GELEN','In arrivo','à¤†à¤¨à¥‡ à¤µà¤¾à¤²à¥€'),(172,NULL,'FAILED',NULL,'BAŞARISIZ',NULL,NULL),(173,'ÐžÐ¿Ð¾Ð²ÐµÑ‰ÐµÐ½Ð¸Ðµ','Alert',NULL,'UyarÄ±','Avviso','à¤šà¥‡à¤¤à¤¾à¤µà¤¨à¥€'),(174,'ÐÐ•Ð¢ ÐžÐ¢Ð’Ð•Ð¢Ð','NO ANSWER',NULL,'CEVAP YOK','NESSUNA RISPOSTA','à¤•à¥‹à¤ˆ à¤œà¤µà¤¾à¤¬ à¤¨à¤¹à¥€à¤‚'),(175,'ÐšÑ‚Ð¾ Ð·Ð²Ð¾Ð½Ð¸Ñ‚','Whos calling',NULL,'Kim arÄ±yor','Chi sta chiamando','à¤¬à¥à¤²à¤¾ à¤°à¤¹à¤¾ à¤¹à¥ˆ à¤œà¥‹'),(176,'ÐšÑƒÐ´Ð° Ð·Ð²Ð¾Ð½Ð¸Ñ‚','Where to call',NULL,'Nereye Ã§aÄŸÄ±rÄ±yor','Dove sta chiamando','à¤•à¥‰à¤² à¤•à¤°à¤¨à¥‡ à¤•à¥‡ à¤²à¤¿à¤ à¤œà¤¹à¤¾à¤‚'),(177,'Ð”Ð°Ñ‚Ð° Ð·Ð²Ð¾Ð½ÐºÐ°','Date of call',NULL,'Tarihi Ã§aÄŸrÄ±','Data della chiamata','à¤¤à¤¿à¤¥à¤¿ à¤•à¥‡ à¤²à¤¿à¤ à¤•à¥‰à¤²'),(178,'ÐžÑ‚','From',NULL,'Yer','Da','à¤¸à¥‡'),(179,'ÐÐ° Ð½Ð¾Ð¼ÐµÑ€','The room',NULL,'NumarasÄ±','In camera','à¤•à¤®à¤°à¥‡'),(180,'Ð’Ñ€ÐµÐ¼Ñ',NULL,NULL,'Zaman','Il tempo','à¤¸à¤®à¤¯'),(181,'ÐŸÐµÑ‡Ð°Ñ‚ÑŒ','Print',NULL,'BaskÄ±','Stampa','à¤ªà¥à¤°à¤¿à¤‚à¤Ÿ'),(182,NULL,'OUTBOUND',NULL,NULL,'In USCITA','à¤†à¤‰à¤Ÿà¤¬à¤¾à¤‰à¤‚à¤¡'),(183,'ÐŸÐ½','PN',NULL,'Pzt','Lun',NULL),(184,'Ð’Ñ‚','W',NULL,'W','W',NULL),(185,'Ð¡Ñ€','MS',NULL,'Ã‡ar','Cp',NULL),(186,'Ð§Ñ‚','Thu',NULL,'PrÅŸ','Gio',NULL),(187,'Ð¡Ð±','SB',NULL,'Sat','Sat',NULL),(188,'Ð’Ñ','Sun',NULL,'GÃ¼neÅŸ','Tutto',NULL),(189,'ÐžÑ‚ÐºÐ»ÑŽÑ‡Ð¸Ñ‚ÑŒ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½','To turn off the phone',NULL,'Devre dÄ±ÅŸÄ± bÄ±rakmak iÃ§in telefon','Scollegare il telefono',NULL),(190,'Ð­Ñ…Ð¾ Ñ‚ÐµÑÑ‚','Echo test',NULL,'Echo test','Echo test',NULL),(191,'ÐŸÐ¾ÐºÐ°Ð·Ð°Ñ‚ÑŒ Ð²ÑÐµ Ð·Ð²Ð¾Ð½ÐºÐ¸','Show all calls',NULL,'GÃ¶stermek iÃ§in tÃ¼m aramalar','Mostra tutte le chiamate',NULL),(192,'Ð’ÑÐµ','All',NULL,'TÃ¼m','Tutti',NULL),(193,'ÐÐµÑ‚ Ð¸ÑÑ‚Ð¾Ñ€Ð¸Ð¸ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²','No call history',NULL,'HayÄ±r geÃ§miÅŸi','No chiamate',NULL),(194,'ÐÐ²Ñ‚Ð¾-Ð¾Ñ‚Ð²ÐµÑ‚Ñ‡Ð¸Ðº','An auto-responder',NULL,'Otomatik yanÄ±tlayÄ±cÄ±','Auto-responder',NULL),(195,'ÐžÑ‡ÐµÑ€ÐµÐ´Ð¸','Queue',NULL,'Kuyruk','Coda',NULL),(196,'ÐŸÐ¾ÐºÐ°Ð·Ð°Ñ‚ÑŒ Ñ‚Ð¾Ð»ÑŒÐºÐ¾ Ð¼Ð¾Ð¸ Ð·Ð²Ð¾Ð½ÐºÐ¸','Show only my calls',NULL,'GÃ¶stermek sadece benim aramalar','Mostra solo le mie chiamate',NULL),(197,'ÐœÐ¾Ð¸','My',NULL,'Benim','I miei',NULL),(198,'Ð’Ñ…Ð¾Ð´ÑÑ‰Ð¸Ð¹','Inbound',NULL,NULL,'In arrivo',NULL),(199,'Ð—Ð°Ð½ÑÑ‚','BUSY',NULL,'MEÅžGUL','OCCUPATO',NULL),(200,'Ð­ÐºÑÐ¿Ð¾Ñ€Ñ‚ Ð´Ð°Ð½Ð½Ñ‹Ñ… Ð´Ð»Ñ ÑÐºÐ°Ñ‡Ð¸Ð²Ð°Ð½Ð¸Ñ','Data export for download',NULL,NULL,NULL,NULL),(201,'ÐžÑ‚Ð¾Ð±Ñ€Ð°Ð¶ÐµÐ½Ð¾','Displayed',NULL,NULL,NULL,NULL),(202,'Ð·Ð°Ð¿Ð¸ÑÐµÐ¹','records',NULL,NULL,NULL,NULL),(203,'ÐŸÐ¾ÐºÐ°Ð·Ð°Ñ‚ÑŒ','Show',NULL,NULL,NULL,NULL),(204,'Ð—Ð°Ð³Ñ€ÑƒÐ¶Ð°ÐµÑ‚ÑÑ','Loaded',NULL,NULL,NULL,NULL),(205,'ÐŸÐµÑ€Ð²Ð°Ñ','First',NULL,NULL,NULL,NULL),(206,'ÐŸÐ¾ÑÐ»ÐµÐ´.','Seq.',NULL,NULL,NULL,NULL),(207,'Ð¡ÐµÐ³Ð¾Ð´Ð½Ñ','Today',NULL,NULL,NULL,NULL),(208,'Ð’Ñ‡ÐµÑ€Ð°','Yesterday',NULL,NULL,NULL,NULL),(209,'ÐŸÐ¾ÑÐ»ÐµÐ´Ð½Ð¸Ðµ 7 Ð´Ð½ÐµÐ¹','Last 7 days',NULL,NULL,NULL,NULL),(210,'ÐŸÐ¾ÑÐ»ÐµÐ´Ð½Ð¸Ðµ 30 Ð´Ð½ÐµÐ¹','The past 30 days',NULL,NULL,NULL,NULL),(211,'Ð¢ÐµÐºÑƒÑ‰Ð¸Ð¹ Ð¼ÐµÑÑÑ†','Current month',NULL,NULL,NULL,NULL),(212,'ÐŸÑ€Ð¾ÑˆÐµÐ´ÑˆÐ¸Ð¹ Ð¼ÐµÑÑÑ†','Last month',NULL,NULL,NULL,NULL),(213,'ÐŸÑ€Ð¸Ð¼ÐµÐ½Ð¸Ñ‚ÑŒ','To apply',NULL,NULL,NULL,NULL),(214,'ÐžÑ‚Ð¼ÐµÐ½Ð¸Ñ‚ÑŒ','To cancel',NULL,NULL,NULL,NULL),(215,'Ð´Ð¾','to',NULL,NULL,NULL,NULL),(216,'Ð”Ñ€ÑƒÐ³Ð°Ñ..','Another..',NULL,NULL,NULL,NULL),(217,'Ð¯Ð½Ð²Ð°Ñ€ÑŒ','January',NULL,NULL,NULL,NULL),(218,'Ð¤ÐµÐ²Ñ€Ð°Ð»ÑŒ','February',NULL,NULL,NULL,NULL),(219,'ÐœÐ°Ñ€Ñ‚','March',NULL,NULL,NULL,NULL),(220,'ÐÐ¿Ñ€ÐµÐ»ÑŒ','April',NULL,NULL,NULL,NULL),(221,'ÐœÐ°Ð¹','May',NULL,NULL,NULL,NULL),(222,'Ð˜ÑŽÐ½ÑŒ','June',NULL,NULL,NULL,NULL),(223,'Ð˜ÑŽÐ»ÑŒ','July',NULL,NULL,NULL,NULL),(224,'ÐÐ²Ð³ÑƒÑÑ‚','August',NULL,NULL,NULL,NULL),(225,'Ð¡ÐµÐ½Ñ‚ÑÐ±Ñ€ÑŒ','September',NULL,NULL,NULL,NULL),(226,'ÐžÐºÑ‚ÑÐ±Ñ€ÑŒ','October',NULL,NULL,NULL,NULL),(227,'ÐÐ¾ÑÐ±Ñ€ÑŒ','November',NULL,NULL,NULL,NULL),(228,'Ð”ÐµÐºÐ°Ð±Ñ€ÑŒ','December',NULL,NULL,NULL,NULL),(229,'Ð—Ð°ÐºÑ€Ñ‹Ñ‚ÑŒ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½?','Close the phone?',NULL,NULL,NULL,NULL),(230,'ÐÐ°ÑÑ‚Ñ€Ð¾Ð¹ÐºÐ¸ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½Ð°','Phone settings',NULL,NULL,NULL,NULL),(231,'Ð›Ð¾ÐºÐ°Ð»ÑŒÐ½Ñ‹Ð¹ Ð½Ð¾Ð¼ÐµÑ€','Local number',NULL,NULL,NULL,NULL),(232,'SIP Ð›Ð¾Ð³Ð¸Ð½','SIP Login',NULL,NULL,NULL,NULL),(233,'SIP Ð¿Ð°Ñ€Ð¾Ð»ÑŒ','SIP password',NULL,NULL,NULL,NULL),(234,'SIP Ð¡ÐµÑ€Ð²ÐµÑ€','SIP Server',NULL,NULL,NULL,NULL),(235,'ÐÐµ Ð±ÐµÑÐ¿Ð¾ÐºÐ¾Ð¸Ñ‚ÑŒ','Do not disturb',NULL,NULL,NULL,NULL),(236,'ÐžÑ‚Ð¼ÐµÐ½Ð°','Cancel',NULL,NULL,NULL,NULL),(237,'Ð¡Ð¾Ñ…Ñ€Ð°Ð½Ð¸Ñ‚ÑŒ','Save',NULL,NULL,NULL,NULL),(238,'Ð¡Ð¾Ð¾Ð±Ñ‰ÐµÐ½Ð¸Ðµ','Message',NULL,NULL,NULL,NULL),(239,'Ð¡ Ð½Ð°Ñ‡Ð°Ð»Ð° Ð³Ð¾Ð´Ð°','Year to date',NULL,NULL,NULL,NULL),(240,'Настройки телефона','Phone Settings',NULL,NULL,NULL,NULL),(241,'Локальный номер','Local number',NULL,NULL,NULL,NULL),(242,'SIP Логин','SIP Logine',NULL,NULL,NULL,NULL),(243,'SIP пароль','SIP password',NULL,NULL,NULL,NULL),(244,'SIP Сервер','SIP Server',NULL,NULL,NULL,NULL),(245,'Не беспокоить','Do Not Disturt',NULL,NULL,NULL,NULL),(246,'Отмена','Cancel',NULL,NULL,NULL,NULL),(247,'Сохранить','Save',NULL,NULL,NULL,NULL),(248,'Пн','Pin',NULL,NULL,NULL,NULL),(249,'Вт','Tue',NULL,NULL,NULL,NULL),(250,'Ср','Ser',NULL,NULL,NULL,NULL),(251,'Чт','What?',NULL,NULL,NULL,NULL),(252,'Сб','SB',NULL,NULL,NULL,NULL),(253,'Вс','All',NULL,NULL,NULL,NULL),(254,'Дата','Date',NULL,NULL,NULL,NULL),(255,'На #','# To #',NULL,NULL,NULL,NULL),(256,'Неотвеченные','Unresponsive',NULL,NULL,NULL,NULL),(257,'Очереди','Queues',NULL,NULL,NULL,NULL),(258,'Показать все звонки','Show All Calls',NULL,NULL,NULL,NULL),(259,'Все','All',NULL,NULL,NULL,NULL),(260,'Показать только мои звонки','Show only my calls',NULL,NULL,NULL,NULL),(261,'Мои','My',NULL,NULL,NULL,NULL),(262,'Экспорт данных для скачивания','Exporting data for download',NULL,NULL,NULL,NULL),(263,'Отображено','Mapped',NULL,NULL,NULL,NULL),(264,'записей','Entries',NULL,NULL,NULL,NULL),(265,'Показать','Show',NULL,NULL,NULL,NULL),(266,'c','c',NULL,NULL,NULL,NULL),(267,'по','by',NULL,NULL,NULL,NULL),(268,'Загружается','Loading',NULL,NULL,NULL,NULL),(269,'Первая','First',NULL,NULL,NULL,NULL),(270,'Послед.','Get the ice.',NULL,NULL,NULL,NULL),(271,'Сегодня','Today',NULL,NULL,NULL,NULL),(272,'Вчера','Yesterday',NULL,NULL,NULL,NULL),(273,'Последние 7 дней','Last 7 days',NULL,NULL,NULL,NULL),(274,'Последние 30 дней','Last 30 days',NULL,NULL,NULL,NULL),(275,'Текущий месяц','Current Month',NULL,NULL,NULL,NULL),(276,'Прошедший месяц','Last Month',NULL,NULL,NULL,NULL),(277,'С начала года','Year to date',NULL,NULL,NULL,NULL),(278,'Применить','Apply',NULL,NULL,NULL,NULL),(279,'Отменить','Cancel',NULL,NULL,NULL,NULL),(280,'до','before',NULL,NULL,NULL,NULL),(281,'Другая..','The other ...',NULL,NULL,NULL,NULL),(282,'Январь','January',NULL,NULL,NULL,NULL),(283,'Февраль','February',NULL,NULL,NULL,NULL),(284,'Март','March',NULL,NULL,NULL,NULL),(285,'Апрель','April',NULL,NULL,NULL,NULL),(286,'Май','May',NULL,NULL,NULL,NULL),(287,'Июнь','June',NULL,NULL,NULL,NULL),(288,'Июль','July',NULL,NULL,NULL,NULL),(289,'Август','August',NULL,NULL,NULL,NULL),(290,'Сентябрь','September',NULL,NULL,NULL,NULL),(291,'Октябрь','October',NULL,NULL,NULL,NULL),(292,'Ноябрь','November',NULL,NULL,NULL,NULL),(293,'Декабрь','December',NULL,NULL,NULL,NULL),(294,'Отключить телефон','Disable Phone',NULL,NULL,NULL,NULL),(295,'Эхо тест','Test Echo',NULL,NULL,NULL,NULL),(296,'Закрыть телефон?','Close the phone?',NULL,NULL,NULL,NULL),(297,'Сообщение','Communication',NULL,NULL,NULL,NULL),(298,'Уведомления','Notifications',NULL,NULL,NULL,NULL),(299,'Набираем номер','Were dialing.',NULL,NULL,NULL,NULL),(300,'Разговор с','Chat with',NULL,NULL,NULL,NULL),(301,'Свободна','Free',NULL,NULL,NULL,NULL),(302,'Резерв','Reserve',NULL,NULL,NULL,NULL),(303,'Гудок','Dock',NULL,NULL,NULL,NULL),(304,'Набор','Set',NULL,NULL,NULL,NULL),(305,'Вызов...','Call ...',NULL,NULL,NULL,NULL),(306,'Звонит','Ringing.',NULL,NULL,NULL,NULL),(307,'Занято','Busy',NULL,NULL,NULL,NULL),(308,'Звонок окончен','The Call is Over',NULL,NULL,NULL,NULL),(309,'Customer Care | Callcenter','Customer Care | Callcenter',NULL,NULL,NULL,NULL),(310,'Ð£Ð²ÐµÐ´Ð¾Ð¼Ð»ÐµÐ½Ð¸Ñ','Notifications',NULL,NULL,NULL,NULL),(311,'ÐÐ° #','# To #',NULL,NULL,NULL,NULL),(312,'ÐÐµÐ¾Ñ‚Ð²ÐµÑ‡ÐµÐ½Ð½Ñ‹Ðµ','Unresponsive',NULL,NULL,NULL,NULL),(313,'Ð­ÐºÑÐ¿Ð¾Ñ€Ñ‚ Ð´Ð°Ð½Ð½Ñ‹Ñ…','Export Data',NULL,NULL,NULL,NULL),(314,'Ð¿Ð¾','by',NULL,NULL,NULL,NULL),(315,'ÐÐ° Ð¿Ð°ÑƒÐ·Ðµ','Pause',NULL,NULL,NULL,NULL),(316,'(Ð½ÐµÑ‚ Ð·Ð²Ð¾Ð½ÐºÐ¾Ð²)','(no calls)',NULL,NULL,NULL,NULL),(317,'Ð—Ð²Ð¾Ð½Ð¾Ðº','Call',NULL,NULL,NULL,NULL),(318,'Ð¡Ð²Ð¾Ð±Ð¾Ð´Ð½Ð°','Free',NULL,NULL,NULL,NULL),(319,'Ð ÐµÐ·ÐµÑ€Ð²','Reserve',NULL,NULL,NULL,NULL),(320,'Ð“ÑƒÐ´Ð¾Ðº','Dock',NULL,NULL,NULL,NULL),(321,'ÐÐ°Ð±Ð¾Ñ€','Set',NULL,NULL,NULL,NULL),(322,'Ð’Ñ‹Ð·Ð¾Ð²...','Call ...',NULL,NULL,NULL,NULL),(323,'Ð—Ð²Ð¾Ð½Ð¸Ñ‚','Ringing.',NULL,NULL,NULL,NULL),(324,'Ð—Ð°Ð½ÑÑ‚Ð¾','Busy',NULL,NULL,NULL,NULL),(325,'Ð’Ð²ÐµÐ´Ð¸Ñ‚Ðµ Ð½Ð¾Ð¼ÐµÑ€ Ð´Ð»Ñ ÑƒÑÐ»Ð¾Ð²Ð½Ð¾Ð¹ Ð¿ÐµÑ€ÐµÐ°Ð´Ñ€ÐµÑÐ°Ñ†Ð¸Ð¸ Ñ‚ÐµÐºÑƒÑ‰ÐµÐ³Ð¾ Ð·Ð²Ð¾Ð½ÐºÐ°','Enter a number to conditionally call the current call',NULL,NULL,NULL,NULL),(326,'Ð’Ð²ÐµÐ´Ð¸Ñ‚Ðµ Ð½Ð¾Ð¼ÐµÑ€ Ð´Ð»Ñ Ð¿ÐµÑ€ÐµÐ°Ð´Ñ€ÐµÑÐ°Ñ†Ð¸Ð¸ Ð·Ð²Ð¾Ð½ÐºÐ°','Enter the number to forward a call',NULL,NULL,NULL,NULL),(327,NULL,'CONGESTION',NULL,'TIKANIKLIK',NULL,NULL),(328,NULL,'Total unanswered numbers',NULL,'Toplam yanÄ±tlanmamÄ±ÅŸ toplam sayÄ±',NULL,NULL),(329,NULL,'Answers',NULL,'YanÄ±tlar',NULL,NULL),(330,NULL,'Phones of unanswered calls',NULL,'YanÄ±tlanmamÄ±ÅŸ aramalarÄ±n telefonlarÄ±',NULL,NULL);
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;

