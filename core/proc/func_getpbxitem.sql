DROP PROCEDURE IF EXISTS `get_pbxitem`;
DELIMITER ;;
CREATE PROCEDURE `get_pbxitem`(IN action VARCHAR(164),IN item_id VARCHAR(220), IN in_tenant_ref VARCHAR(70) )
BEGIN
 declare PBX_ITEM VARCHAR(220);
 declare PBX_ITEM2 VARCHAR(30);
 declare PBX_ITEM3 VARCHAR(30);

    IF( action = 'conference' ) then
      SELECT conference INTO  PBX_ITEM FROM t_conferences WHERE id = item_id;
    end if;

    IF ( action = 'disa' ) then
      SELECT item_id INTO PBX_ITEM;
    end if;

    IF ( action = 'userlogonpass' ) then
      SELECT password , 'hotdesk-enabled' INTO PBX_ITEM , PBX_ITEM2
      FROM t_vmusers, tenants  
        WHERE
          t_vmusers.tenant_id = tenants.id AND
          tenants.ref_id = in_tenant_ref AND
          mailbox = item_id ;
    end if;    

    IF( action = 'number' ) then
     SELECT item_id INTO PBX_ITEM;
    end if;

    IF( action = 'park_announce_rec' ) then
       SELECT ifnull(paging_retry_count,5) as paging_retry_count,  ifnull(paging_interval,30) as paging_interval, ifnull(parked_ontimeout_ivr,'') as ivr_ontimeout INTO PBX_ITEM, PBX_ITEM2, PBX_ITEM3 FROM tenants WHERE ref_id = in_tenant_ref;
    end if;  
  
    IF( action = 'queue' ) then
      SELECT name, timeout  INTO  PBX_ITEM,  PBX_ITEM2 FROM t_queues WHERE id = item_id;
    end if;

   
    IF( action = 'voicemail' ) then
      SELECT concat(mailbox,'@',context) INTO  PBX_ITEM FROM t_vmusers WHERE id = item_id;
    end if;
 
    IF( action = 'extension' ) then
      SELECT concat(extension,'@internal-',ref_id,'-local') INTO  PBX_ITEM FROM t_sip_users,tenants WHERE t_sip_users.tenant_id = tenants.id AND t_sip_users.id = item_id;
    end if;
   
    IF( action ='ivrmenu' ) then
        SELECT concat('s@internal-',ref_id,'-ivrmenu-',item_id) INTO  PBX_ITEM FROM t_ivrmenu,tenants WHERE t_ivrmenu.tenant_id = tenants.id AND t_ivrmenu.id = item_id;
    end if;

    IF( action ='hrstatus' ) then       
        SELECT CASE WHEN  IFNULL( event_type ,'off' ) = 'hrlogon' THEN 'ON' ELSE 'OFF' END 
        INTO PBX_ITEM
        FROM t_queue_members_log,tenants
            WHERE ref_id = in_tenant_ref AND
		  tenants.id = t_queue_members_log.tenant_id AND
                  sip_name = item_id
        ORDER BY ts DESC LIMIT 1;
    end if;
  

  SELECT PBX_ITEM, PBX_ITEM2;

END ;;
DELIMITER ;

/* call get_pbxitem('voicemail','8'); */
/* call get_pbxitem('extension','120'); */
/* call get_pbxitem('ivrmenu','213'); */
/* call get_pbxitem('extension',133); */
/* call get_pbxitem('queue',299,'SandhuKaler'); */
call get_pbxitem('userlogonpass',103,'joe');


