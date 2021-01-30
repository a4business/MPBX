set sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

DROP PROCEDURE IF EXISTS `get_pagegroup`;
DELIMITER ;;
CREATE PROCEDURE `get_pagegroup`(IN in_pg_access_number VARCHAR(64), IN in_caller_num VARCHAR(60))
BEGIN

  SELECT concat( group_concat(interface SEPARATOR '&'), 
                 IF(t_pagegroups.full_duplex = 1  OR t_pagegroups.no_beep = 1 ,'|','') , 
                 IF(t_pagegroups.full_duplex = 1 ,'d',''), 
                 IF(t_pagegroups.no_beep = 1 ,'q','') )
        AS PG_EXTEN,
        tenants.id as PG_TENANT_ID
  FROM t_pagegroups, t_pagegroup_members,tenants
    WHERE t_pagegroups.pg_extension = replace(in_pg_access_number,'#','') AND 
          t_pagegroups.tenant_id = tenants.id AND  
          t_pagegroups.id = t_pagegroup_members.pagegroup_id AND
          interface != concat('SIP/',in_caller_num); 

END ;;
DELIMITER ;

call get_pagegroup('999#','scnd-103');
