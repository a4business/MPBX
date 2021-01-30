DROP PROCEDURE IF EXISTS `get_tenant`;
DELIMITER ;;
CREATE PROCEDURE `get_tenant`(IN in_refid VARCHAR(64))
BEGIN

  SELECT active_calls_limit as IN_LIMIT,
         active_calls_limit as OUT_LIMIT,
         active_calls as IN_CURRENT,
         active_calls as OUT_CURRENT,
         if( active_calls > active_calls_limit AND active_calls_limit != 0, active_calls - active_calls_limit,0) AS IN_OVER_LIMIT,
         if( active_calls > active_calls_limit AND active_calls_limit != 0, active_calls - active_calls_limit,0) AS OUT_OVER_LIMIT,
	 default_call_recording as DEF_RECORDING,
         vm_operator_exten AS VM_OPER_EXTEN

  FROM tenants 
      WHERE ref_id = in_refid;

END ;;
DELIMITER ;

call get_tenant('def');
