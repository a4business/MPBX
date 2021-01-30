DROP PROCEDURE IF EXISTS `get_callscreening`;
DELIMITER ;;
CREATE PROCEDURE `get_callscreening`(IN user_in INT,IN inbound_cid VARCHAR(60),IN inbound_cname VARCHAR(60))
BEGIN

 declare SCREENED INT DEFAULT 0;
 declare SCREEN_CID_MODE INT DEFAULT 1;     /* Always ask  the CID when screened - (1) or when empty only 2, or NEVER ask(0) */
 declare SCREEN_ASK_CID INT DEFAULT 0;      /* DEFAULT NEVER */

 declare SCREEN_CNAME_MODE INT DEFAULT 1;   /* Always ask  the CNAME when screened - (1) or when empty only 2, or NEVER ask(0) */
 declare SCREEN_ASK_CNAME TINYINT DEFAULT 0;        /* DEFAULT NEVER */

 declare DEFAULT_SCREENING INT DEFAULT 0;
 declare SCREEN_INFO VARCHAR(100);
 declare EXTRA_INFO VARCHAR(100);
 declare IS_ANONYM_CID TINYINT DEFAULT 0;
 declare IS_ANONYM_CNAME TINYINT DEFAULT 0;
 declare MATCHED_CALLER VARCHAR(60) ;
 declare IS_MATCHED_SCREENED TINYINT DEFAULT 1;

  SELECT call_screening, call_screening_ask_cname, call_screening_ask_cid,
         if(inbound_cid = '' OR inbound_cid = 'anonymous',TRUE,FALSE),
         if(inbound_cname = '' OR inbound_cname = 'anonymous',TRUE,FALSE),
         usl.callerid, usl.screened
         into
         DEFAULT_SCREENING, SCREEN_CNAME_MODE, SCREEN_CID_MODE,
         IS_ANONYM_CID, IS_ANONYM_CNAME,
         MATCHED_CALLER, IS_MATCHED_SCREENED
     FROM t_user_options as u
            LEFT JOIN t_user_screening usl ON usl.t_sip_user_id = usl.t_sip_user_id AND usl.callerid = inbound_cid
    WHERE u.id = user_in ;


    set SCREENED = DEFAULT_SCREENING;
    set SCREEN_INFO = concat(' SCREENINIG[',SCREENED,']');

   IF( (SCREENED != 1) AND  MATCHED_CALLER != ''  AND (IS_MATCHED_SCREENED = 1) ) then
       set SCREENED = 1;
       set EXTRA_INFO = concat(" SCREENING[MATCHED CALLER[",inbound_cid,"] ENFORCED] ");
   end if;


    IF( MATCHED_CALLER != '' AND (IS_MATCHED_SCREENED != 1) ) then
           set SCREENED = 0;
           set SCREEN_ASK_CID = 0;
           set SCREEN_ASK_CNAME = 0;
           set SCREEN_INFO = concat(" SCREENING[ DISABLED FOR MATCHED CALLER[",inbound_cid,"] ]");
    end if;


    IF ( SCREENED ) then
         
         IF( SCREEN_CID_MODE = 2 AND IS_ANONYM_CID ) then
           set SCREEN_ASK_CID = 1;
           set SCREEN_INFO = concat("  ASKING CID FOR ANONYM [ ", inbound_cid," ] while calling",user_in);
         end if;

         IF( SCREEN_CID_MODE = 1 ) then
           set SCREEN_ASK_CID = 1;
           set SCREEN_INFO = "  ALWAYS ASK CID ";  /* DEFAULT */
         end if;

         IF( SCREEN_CID_MODE = 0 ) then
           set SCREEN_ASK_CID = 0;
           set SCREEN_INFO = "  NEVER  ASK CID";
         end if;

         IF( SCREEN_CNAME_MODE = 2 AND IS_ANONYM_CNAME ) then
           set SCREEN_ASK_CNAME = 1;
           set SCREEN_INFO = concat(SCREEN_INFO,"  ASKING CNAME (ANONYM/EMPTY) [ ", inbound_cname," ] FOR uid:",user_in);
         end if;

         IF( SCREEN_CNAME_MODE = 1 ) then
           set SCREEN_ASK_CNAME = 1;
           set SCREEN_INFO = concat(SCREEN_INFO,"  ALWAYS ASK CNAME "); /* DEFAULT */
         end if;

         IF( SCREEN_CNAME_MODE = 0 ) then
           set SCREEN_ASK_CNAME = 0;
           set SCREEN_INFO = concat(SCREEN_INFO, "  NEVER ASK CNAME ");
         end if;

   
   end if;



  SELECT SCREENED, SCREEN_ASK_CID, SCREEN_ASK_CNAME, concat(ifnull(EXTRA_INFO,''),ifnull(SCREEN_INFO,'')) as SCREEN_INFO;

END ;;
DELIMITER ;

call get_callscreening(3,'12323 Anonymous','Anonymous');
