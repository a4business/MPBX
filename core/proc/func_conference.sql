DROP PROCEDURE IF EXISTS `get_conferenceinfo`; 
DELIMITER ;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `get_conferenceinfo`(IN conference_id VARCHAR(64))
BEGIN

SELECT
         status,
         moh_class as music_on_hold_class,
         maxusers as max_members,
         password as userpin,
         admin_password as adminpin,
        if(enable_moh = 1, 'yes', 'no') as music_on_hold_when_empty,
        if(enable_menu = 1, 'yes', 'no') as enable_menu,
        if(enable_recording = 1, 'yes', 'no') as record_conference,
        if(announce_count = 1, 'yes', 'no') as announce_user_count,
        if(detect_talker = 1, 'yes', 'no') as talk_detection_events,
        if(talker_optimization = 1, 'yes', 'no') as talker_optimization,
        if(announce_join = 1, 'yes', 'no') as announce_join_leave,
        if(wait_marked = 1, 'yes', 'no') as wait_marked,
        if(end_marked = 1, 'yes', 'no') as end_marked,
        options,
	if(announcement_file = '0','none',announcement_file) as announcement_file,
	ref_id as tenant
        FROM t_conferences, tenants 
        WHERE tenants.id = t_conferences.tenant_id AND
              t_conferences.id = conference_id;

END */;;
DELIMITER ;


call get_conferenceinfo(8);
call get_conferenceinfo(6);
