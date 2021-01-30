DROP PROCEDURE IF EXISTS `get_callrecording`;
DELIMITER ;;
CREATE PROCEDURE `get_callrecording`(IN user_in INT,IN inbound_cid VARCHAR(60))
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

call get_callrecording(2,'112233');

