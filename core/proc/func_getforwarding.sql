DROP PROCEDURE IF EXISTS `get_callforwarding`;
DELIMITER ;;
CREATE PROCEDURE `get_callforwarding`(IN user_in INT,IN inbound_cid VARCHAR(60))
BEGIN

 declare FORWARD VARCHAR(60) DEFAULT 0;
 declare FORWARD_TO VARCHAR(60) DEFAULT "0";
 declare FORWARD_ONBUSY VARCHAR(60) DEFAULT "0";
 declare FORWARD_TIMEOUT INT DEFAULT 0;
 declare FWD_TAG VARCHAR(60);
 declare FWD_KEEP_CID VARCHAR(4) DEFAULT '0';
 declare FORWARD_INFO VARCHAR(250) DEFAULT "no Forward";

 SELECT  ifnull(call_forwarding,0),ifnull(call_forward_onbusy,0),
         ifnull(call_forward_timeout,0),ifnull(call_forward_tag,''), call_forward_preserve_cid
         into
         FORWARD_TO, FORWARD_ONBUSY, FORWARD_TIMEOUT, FWD_TAG,FWD_KEEP_CID
     FROM t_user_options
    WHERE t_user_options.id = user_in ;

 set FORWARD_INFO = concat(' FORWARDING:',IF(FORWARD_TO=1,'TO-VMAIL',FORWARD_TO),' ON BUSY:',IF(FORWARD_ONBUSY=1,'TO-VMAIL',FORWARD_ONBUSY),' TIMEOUT:',FORWARD_TIMEOUT);
 
 SELECT FORWARD_TO,FORWARD_ONBUSY,FORWARD_TIMEOUT,FWD_TAG,FORWARD_INFO,FWD_KEEP_CID ;

END ;;
DELIMITER ;

/* call get_callforwarding(2,'112233'); */
call get_callforwarding(12,'112233');

