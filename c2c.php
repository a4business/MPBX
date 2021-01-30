<?php
//
// Click2Call Service  -  RESTApi  interface
// 

include_once('include/config.php');
require_once('include/mailManager.php');

$str = json_encode(array_map('trim', array_merge( array_map('addslashes',$_POST), array_map('addslashes',$_GET) ) ) , true); 
$IP = $_SERVER['REMOTE_ADDR'];
$config->log->putLog( " C2C IP:[ {$IP} ]  REQ: {$str} ");
if(!isset($_SESSION))
     session_start();

 if(isset($_GET['exten'])){
 	$key = mysql_real_escape_string($_GET['exten']); 
 	$number = mysql_real_escape_string($_GET['phone']);
 	$number = preg_replace("/[^0-9]/",'',$number);
 	echo $number;

 	$res = mysql_query( "SELECT extension, ref_id  FROM t_sip_users ,tenants
 		                  WHERE tenants.id = t_sip_users.tenant_id AND
 		                        click2dial_enabled = 1 AND 
 		                        substr( md5(name), 1,10 )  = '{$key}' ") ;
 	if($res)
 	   $exten = mysql_fetch_assoc( $res );

 	$config->log->putLog(" C2C IP:[ {$IP} ] Tenant:{$exten['ref_id']} CallTo:[ {$number} ] from Ext:[ '{$exten['extension']}' ]" )  ;

 	if( is_array( $exten ) && count($exten) )
      echo  json_encode(
                   $config->click2call(
                   		$exten['extension'],
                   		$number,
                   		$exten['ref_id'] 
                   	)
               );
 		// Initiate a call here:
    else{
    	echo json_encode( array('response' => array('status' => 'False', 'message' => 'No such extension !'))) ;
    }


 }

?>
