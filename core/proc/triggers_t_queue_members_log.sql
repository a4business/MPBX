DROP TRIGGER IF EXISTS trigger_t_queue_members_log_bi;
DELIMITER ;;

/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queue_members_log_bi` BEFORE INSERT ON `t_queue_members_log` FOR EACH ROW
BEGIN
  /* Write correct Session Duration when user UNREGISTER */
  DECLARE last_ts timestamp;
  DECLARE last_event varchar(100);

/* CATCH Queue LOGOUT EVENT ONLY FOR QUEUES*/
  IF NEW.event_type = 'QLogout' 
   THEN
    /* GET LAST LOGIN EVENT */
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

