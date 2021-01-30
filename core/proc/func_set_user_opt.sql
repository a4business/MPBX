DROP PROCEDURE IF EXISTS `set_user_option`;
DELIMITER ;;
CREATE PROCEDURE `set_user_option`(IN in_tenant_name varchar(100),IN in_exten VARCHAR(100), IN in_opt_name VARCHAR(60), IN in_opt_val VARCHAR(200) )
BEGIN

 declare IN_TENANT_ID INTEGER DEFAULT 0;
 declare SIP_ID INTEGER DEFAULT 0;
 declare SIP_NAME VARCHAR(100) DEFAULT '';
 declare QNAME_BYLABLE VARCHAR(100) DEFAULT '';
 declare _last_ts timestamp DEFAULT null;
 declare _last_event varchar(100) DEFAULT '';

 
   /* Write correct Session Duration when user UNREGISTER */

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

 /*   Host DESKING  */

 IF ( in_opt_name = 'userlogon' ) then
    INSERT INTO t_sip_user_devices(tenant_id,t_sip_user_id,exten)
     VALUES( IN_TENANT_ID, SIP_ID, in_opt_val );
 END IF;

 IF ( in_opt_name = 'userlogoff' ) then
    DELETE FROM t_sip_user_devices 
      WHERE tenant_id = IN_TENANT_ID  AND
            exten =  in_opt_val ;   
 END IF;


 /*     HUMAN Resources ACTIVITY     */

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





END;;
DELIMITER ;

/* call set_user_option('joe','101','dnd',1); */
/* call set_user_option('joe','101','userlogon',102);  */
call set_user_option('Armour',250,'queues_logon',0); 


