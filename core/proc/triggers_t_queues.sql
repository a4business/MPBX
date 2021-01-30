DROP TRIGGER IF EXISTS trigger_t_queues_bu;
DELIMITER ;;

/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_queues_bu` BEFORE UPDATE ON `t_queues` FOR EACH ROW
BEGIN

  IF NEW.name != OLD.name
   THEN
   UPDATE t_queue_members 
      SET  queue_name = NEW.name
     WHERE  queue_name = OLD.name  AND
	    t_queue_members.tenant_id = NEW.tenant_id;
   END IF;

END */;;



DELIMITER ;
