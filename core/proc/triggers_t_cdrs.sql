DROP TRIGGER IF EXISTS  `trigger_t_cdrs_bi`;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_cdrs_bi` BEFORE INSERT ON `t_cdrs` FOR EACH ROW
BEGIN
 DECLARE the_tenant_ref varchar(60);

 if ( NEW.userfield != '') then
     SET the_tenant_ref = SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 1),':',-1 );
     if ( EXISTS( SELECT 1 FROM tenants WHERE ref_id = the_tenant_ref) ) then
       SET NEW.tenant_id = (SELECT id FROM tenants  WHERE ref_id = the_tenant_ref order by id LIMIT 1);
     end if;
     SET NEW.did =     SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 2),'::',-1 ); 
     SET NEW.from_ip = SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 3),'::',-1 );
     SET NEW.recvip =  SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 4),'::',-1 );
     SET NEW.rtpsource=SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 5),'::',-1 );
     SET NEW.rtpdest = SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 6),'::',-1 );
     SET NEW.peername =SUBSTRING_INDEX( SUBSTRING_INDEX(NEW.userfield, '::', 7),'::',-1 );
 end if;

END */;;
DELIMITER ;

