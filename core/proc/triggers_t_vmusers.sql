DROP TRIGGER IF EXISTS trigger_t_vmusers_bi;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_vmusers_bi` BEFORE INSERT ON `t_vmusers` FOR EACH ROW
BEGIN
  DECLARE tenant varchar(100);
  DECLARE next_pbx_id integer;
  IF IFNULL(NEW.tenant_id,0) > 0 
   THEN
     SET tenant = (SELECT ref_id FROM tenants WHERE id = NEW.tenant_id LIMIT 1);
     SET NEW.context = CONCAT(tenant,'-vmdefault');

     SET next_pbx_id = ( SELECT pbx_item_id + 1 FROM tenants LIMIT 1 );
     /** validate ID  **/
     IF next_pbx_id < (SELECT max(id)+1 FROM t_vmusers)
     THEN
       SET next_pbx_id = (SELECT max(id)+1 FROM t_vmusers) ;
     ELSE
       SET NEW.id = next_pbx_id; 
     END IF;
     UPDATE tenants SET pbx_item_id = next_pbx_id  ;
      
   END IF;
END */;;
DELIMITER ;
