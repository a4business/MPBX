DROP PROCEDURE IF EXISTS `get_userinfo`;
DELIMITER ;;
CREATE PROCEDURE `get_userinfo`(IN user_in VARCHAR(64))
BEGIN



  SELECT if(ifnull(call_recording,0) = 0,default_call_recording,call_recording) as RECORD,
       sip.last_name,sip.first_name,sip.internal_callername,sip.internal_callerid,sip.outbound_callername,
        sip.outbound_callerid,
        sip.mohinterpret,sip.mohsuggest,sip.did_id,sip.outbound_route,sip.tenant_id,
        sip.extension,
        sip.username,sip.callerid,sip.type,
        sip.context,sip.host,sip.name,sip.id,
        ifnull((SELECT group_concat(concat('SIP/',name) SEPARATOR '&') as HD
           FROM t_sip_user_devices,t_sip_users 
           WHERE t_sip_user_devices.exten = t_sip_users.extension AND
                 t_sip_user_devices.tenant_id = t_sip_users.tenant_id AND
                 t_sip_user_id = sip.id AND
                 t_sip_user_devices.exten != sip.extension
           GROUP BY t_sip_user_id ),'none') as hotDesk,
         t.ref_id  as TENANT,
         o.id as user_id,
         ifnull(vm_timeout,60) as VMTIMEOUT,
         ifnull(o.call_waiting,0) as CALL_WAITING
  FROM t_sip_users as sip
        LEFT JOIN t_user_options as o ON sip.id = o.t_sip_user_id  AND  sip.tenant_id = o.tenant_id
        LEFT JOIN t_vmusers as vmail  ON sip.extension = vmail.mailbox AND sip.tenant_id = vmail.tenant_id,
       tenants as t
  WHERE sip.name = user_in AND
        sip.tenant_id = t.id;

END ;;
DELIMITER ;

call get_userinfo('joe-103');
