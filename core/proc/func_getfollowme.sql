DROP PROCEDURE IF EXISTS `get_callfollowme`;
DELIMITER ;;
CREATE PROCEDURE `get_callfollowme`(IN user_in INT,IN inbound_cid VARCHAR(60))
BEGIN

 declare FOLLOWME VARCHAR(60) DEFAULT 0;
 declare FOLLOWME_ID VARCHAR(20);
 declare FOLLOWME_OPTS VARCHAR(60);
 declare FWD_KEEP_CID varchar(10) DEFAULT '0';
 declare FWD_TAG VARCHAR(100);
 declare FOLLOWME_ONTIMEOUT VARCHAR(30);
 declare FOLLOWME_ONTIMEOUT_VAR VARCHAR(30);

 SELECT  call_followme_status, name,
         concat(if(instr(call_followme_options,'a'),'a',''),if(instr(call_followme_options,'s'),'s',''),if(instr(call_followme_options,'n'),'n','')),
         ifnull(call_forward_tag,''),call_forward_preserve_cid, call_followme_ontimeout,call_followme_ontimeout_var
         into
         FOLLOWME, FOLLOWME_ID, FOLLOWME_OPTS, FWD_TAG, FWD_KEEP_CID, FOLLOWME_ONTIMEOUT, FOLLOWME_ONTIMEOUT_VAR
     FROM t_user_options
    WHERE t_user_options.id = user_in ;

    
 
 SELECT FOLLOWME, FOLLOWME_ID,FOLLOWME_OPTS,FWD_TAG,FWD_KEEP_CID,FOLLOWME_ONTIMEOUT,FOLLOWME_ONTIMEOUT_VAR;

END ;;
DELIMITER ;

call get_callfollowme(2,'112233');
