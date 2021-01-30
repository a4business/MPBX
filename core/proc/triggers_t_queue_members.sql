DROP TRIGGER IF EXISTS trigger_t_queue_members_on_delete;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003  TRIGGER `trigger_t_queue_members_on_delete` AFTER DELETE ON t_queue_members
 FOR EACH ROW
 BEGIN
  /* Trigger calculate session time */
  INSERT INTO t_queue_members_log(event_type,event_data,queue_name,sip_name,tenant_id)
     VALUES('QLogout','Member logout', OLD.queue_name, OLD.interface, OLD.tenant_id );
 END */;;
DELIMITER ;

DROP TRIGGER IF EXISTS trigger_t_queue_members_bi;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_bi` BEFORE INSERT ON `t_queue_members` FOR EACH ROW
BEGIN
 /* Register login event with time, to calculate session time */ 
 INSERT INTO t_queue_members_log(event_type,event_data,queue_name,sip_name,tenant_id)
     VALUES('QLogin', 'Member LOGIN', NEW.queue_name, NEW.interface, NEW.tenant_id);

  SET NEW.uniqueid = LAST_INSERT_ID();
  SET NEW.uniqueid =  (SELECT AUTO_INCREMENT FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 't_queue_members');

  
END */;;

DELIMITER ;
DROP TRIGGER IF EXISTS trigger_t_queue_members_bu;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_bu` BEFORE UPDATE ON `t_queue_members` FOR EACH ROW
BEGIN
 /*Catch moment  of switching to/from  Pause */ 	
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
