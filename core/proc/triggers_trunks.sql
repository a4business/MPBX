DROP TRIGGER IF EXISTS trigger_trunks_bi;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_trunks_bi` BEFORE INSERT ON `trunks` FOR EACH ROW
BEGIN
   if( NEW.secret != ''  AND EXISTS(SELECT 1 FROM tenants WHERE  ifnull(encrypt_sip_secrets,0) = 1 ORDER BY ID LIMIT 1) ) THEN
      SET NEW.md5secret =  md5(concat(NEW.defaultuser,':asterisk:',NEW.secret));
      SET NEW.secret= '[encrypted]';
   END IF;
END */;;

DROP TRIGGER IF EXISTS trigger_trunks_bu;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_trunks_bu` BEFORE UPDATE ON `trunks` FOR EACH ROW
BEGIN
  if(  NEW.secret != '' AND  NEW.secret != '[encrypted]' AND EXISTS(SELECT 1 FROM tenants WHERE ifnull(encrypt_sip_secrets,0) = 1 ) ) THEN
    SET NEW.md5secret =  md5(concat(NEW.defaultuser,':asterisk:',NEW.secret));
    SET NEW.secret= '[encrypted]';
  END IF;

END */;;



DELIMITER ;



