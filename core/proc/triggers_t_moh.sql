DROP TRIGGER IF EXISTS trigger_t_moh_bu;
DELIMITER ;;

/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_t_moh_bu` BEFORE UPDATE ON `t_moh` FOR EACH ROW
BEGIN

  IF NEW.mode = 'custom' AND NEW.directory != ''
   THEN
    SET NEW.directory_2 = NEW.directory,   
        NEW.directory = '';
   END IF;
   
  IF NEW.mode = 'files' AND NEW.directory = ''  THEN
    if NEW.directory_2 != '' THEN
      SET NEW.directory = NEW.directory_2;
    ELSE 
      SET NEW.directory = NEW.name;     
    END IF   ;    
 END IF;
   

END */;;



DELIMITER ;
