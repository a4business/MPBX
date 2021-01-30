DROP TRIGGER IF EXISTS trigger_t_sip_users_on_delete;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003  TRIGGER `trigger_t_sip_users_on_delete` AFTER DELETE ON t_sip_users
 FOR EACH ROW
 BEGIN
   DELETE FROM `t_vmusers`
     WHERE t_vmusers.tenant_id = old.tenant_id AND
           t_vmusers.mailbox = old.extension;

   DELETE FROM `t_user_options` 
     WHERE t_sip_user_id = old.id;

   DELETE FROM `t_queue_members` 
     WHERE `t_queue_members`.tenant_id = old.tenant_id AND
	   concat('SIP/',old.name) = `t_queue_members`.interface;
  
 END */;;

DELIMITER ;

DROP TRIGGER IF EXISTS trigger_t_sip_users_bi;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_sip_users_bi` BEFORE INSERT ON `t_sip_users` FOR EACH ROW
BEGIN
 DECLARE next_pbx_id integer; 

 if ( NEW.tenant_id != '') then
    SET NEW.subscribecontext = (SELECT concat('internal-', ref_id, '-BLF') FROM tenants  WHERE id = NEW.tenant_id );
    SET NEW.language = (SELECT ifnull(sounds_language,'en') FROM tenants WHERE id = NEW.tenant_id );

    SET NEW.namedpickupgroup = (SELECT ref_id FROM tenants WHERE id = NEW.tenant_id );
    SET NEW.namedcallgroup = (SELECT ref_id FROM tenants WHERE id = NEW.tenant_id );

    if( EXISTS( SELECT 1 FROM tenants WHERE id = NEW.tenant_id AND ifnull(encrypt_sip_secrets,0) = 1 ) ) then
      SET NEW.md5secret =  md5(concat(NEW.name,':asterisk:',NEW.secret));
      SET NEW.secret= '[encrypted]';
    end if;
 end if;

  if( EXISTS( SELECT 1 FROM blacklist WHERE ip = NEW.ipaddr AND ifnull(NEW.ipaddr,'') != '' AND block_sip_registration = 1) ) then
    SET NEW.host = 'local';
  end if;
 

  SET next_pbx_id = ( SELECT pbx_item_id + 1 FROM tenants LIMIT 1 );
     /** validate ID  **/
  IF next_pbx_id < (SELECT max(id)+1 FROM t_sip_users) 
   THEN
     SET next_pbx_id = (SELECT max(id)+1 FROM t_sip_users);
   ELSE
     SET NEW.id = next_pbx_id;
   END IF;
  UPDATE tenants SET pbx_item_id = next_pbx_id  ;

 
 
 SET NEW.mohsuggest = NEW.mohinterpret ;
END */;;

DROP TRIGGER IF EXISTS trigger_t_sip_users_bu;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_sip_users_bu` BEFORE UPDATE ON `t_sip_users` FOR EACH ROW
BEGIN

 IF  ifnull(NEW.first_name,'') != ''  OR ifnull(NEW.last_name,'') != ''  
  THEN
    IF  EXISTS(SELECT 1 FROM t_vmusers WHERE  tenant_id = NEW.tenant_id AND mailbox = NEW.extension AND ifnull(fullname,'') = '' ) 
     THEN
       UPDATE t_vmusers 
          SET fullname = concat( ifnull(NEW.first_name,''),' ', ifnull(NEW.last_name,'') )
            WHERE tenant_id = NEW.tenant_id AND mailbox = NEW.extension ;
     END IF;
  END IF;


 SET NEW.mohsuggest = NEW.mohinterpret ;
  if( NEW.secret != '[encrypted]' AND  EXISTS( SELECT 1 FROM tenants WHERE id = NEW.tenant_id AND ifnull(encrypt_sip_secrets,0) = 1 ) ) 
    then
      SET NEW.md5secret =  md5(concat(NEW.name,':asterisk:',NEW.secret));
      SET NEW.secret= '[encrypted]';
    else
      SET NEW.md5secret = '';
    end if;

END */;;



DELIMITER ;



