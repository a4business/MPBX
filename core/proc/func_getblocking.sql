DROP PROCEDURE IF EXISTS `get_callblocking`;
DELIMITER ;;
CREATE PROCEDURE `get_callblocking`(IN user_in VARCHAR(64),IN inbound_cid VARCHAR(60))
BEGIN
 declare BLOCK INT DEFAULT 0;
 declare BLOCK_MODE INT DEFAULT 0;
 declare BLOCK_INFO VARCHAR(100);
 declare DEFAULT_POLICY TINYINT DEFAULT 0;
 declare BLOCK_ANONYM TINYINT DEFAULT 1;
 declare IS_ANONYM TINYINT DEFAULT 0;
 declare MATCHED_CALLER VARCHAR(60);
 declare IS_MATCHED_ALLOWED TINYINT DEFAULT 1;

  SELECT call_blocking_mode, call_blocking, call_blocking_anonym, if(inbound_cid = '' OR inbound_cid = 'anonymous',TRUE,FALSE), ubl.callerid, ubl.allowed
         into
         BLOCK_MODE, DEFAULT_POLICY, BLOCK_ANONYM, IS_ANONYM,  MATCHED_CALLER, IS_MATCHED_ALLOWED
     FROM t_user_options as u
            LEFT JOIN t_user_blocklist ubl ON ubl.t_sip_user_id = u.t_sip_user_id AND ubl.callerid = inbound_cid
    WHERE u.id = user_in ;

    set BLOCK = DEFAULT_POLICY;
    set BLOCK_INFO = concat('CALL BLOCK MODE:',BLOCK);

    IF ( IS_ANONYM AND BLOCK_ANONYM = 1  ) then
         set BLOCK =1;
         set BLOCK_INFO = concat(" ->CALL BLOCKED: ANONYM [ from:", inbound_cid," ],which Called UID:",user_in);
      end if;
 
    IF ( DEFAULT_POLICY = 0 AND IS_MATCHED_ALLOWED != 1 ) then
        set BLOCK = 1;
        set BLOCK_INFO = concat("CALL BLOCKED MATCHED callerid[",inbound_cid,"=",MATCHED_CALLER,"], wich not allowed");
      end if;
   

    IF( DEFAULT_POLICY = 1 AND IS_MATCHED_ALLOWED = 1 ) then
        set BLOCK = 0;
        set BLOCK_INFO = concat("CALL Allowed BY MATCHED callerid[", inbound_cid, "=", MATCHED_CALLER, "]");
      end if;


  SELECT BLOCK, BLOCK_MODE, BLOCK_INFO;

END ;;
DELIMITER ;

call get_callblocking(2,'112233');
