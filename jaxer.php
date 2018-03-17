<?php

include_once('include/config.php');

$expected_api = array('get_data','username', 'password','logout','id', 'showTenants','switch_tenant_to','get_next_exten', 'reload_tenants','reload_extensions','run' );
$allowed_data_tables = array('sip_users','tenants');

 session_start();

foreach ( array_merge($_POST,$_GET) as $key => $value) {
  if (!in_array($key, $expected_api)) {
    continue;
  } 
  ${$key} = $value;  
}

if($logout) {	
	unset($_SESSION['UID']);
   session_destroy();
   echo json_encode( array( 'success'=> true, 'error' => "Logged out") );
}
if ( !is_null($username) ){
	if ( $username == "admin" && $password == 'etor' ){
     $_SESSION['UID'] = 1; //$row['id'];
     $_SESSION['ROLE'] = 1; //$row['id'];
     $_SESSION['USERNAME'] = 'admin';  //$row['username'];
     $_SESSION['tenantid'] = '2';  //$row['username'];
     $_SESSION['tenantname'] = 'scnd';  //$row['username'];
     $_SESSION['tenantref'] = 'def';  //$row['username'];
     echo json_encode( array( 'success' => true ) );
     return;
   }  
   echo json_encode( array( 'success'=> false, 'error' => "Wrong login details") );
   return;
}

if($switch_tenant_to){
	session_start();
   $_SESSION['tenantid']  = (int)$switch_tenant_to;
   $res = mysql_query("SELECT * FROM tenants WHERE id = {$_SESSION['tenantid']}");
   $row = mysql_fetch_assoc($res); 
   $_SESSION['tenantref'] = $row['ref_id'];  
   $_SESSION['tenantname'] = $row['title'];
   echo json_encode( array( 'success'=> true, 'error' => "OK: Switched to $switch_tenant_to") );
   return;
}


if ( isset($get_next_exten) ){
	   
   $_res = mysql_query("SELECT max(convert(extension,decimal(10))) as extension FROM t_sip_users WHERE tenant_id = {$_SESSION['tenantid']}");
   $_row = mysql_fetch_assoc($_res);
   $_ext = is_null($_row['extension']) ? '101' : $_row['extension']+1;
   
   echo json_encode( array( 'extension' => $_ext, 'name' => $_SESSION['tenantref'].'-'.$_ext, 'secret' => bin2hex(openssl_random_pseudo_bytes(4))   ));
   
}

if ( isset($run) ){	  
  
  switch( $run ) {
  	case 'reload_dialplan':
     $config->reload_dialplan();
     break;
     
   case 'repopulate_features':
     $s = mysql_query("SELECT * FROM tenants");
     while( $r1 = mysql_fetch_assoc($s))
         $r2 = mysql_query("INSERT INTO t_extensions SELECT null, {$r1['id']},3,0,description,'internal-{$r1['ref_id']}-features',exten,1,app,appdata FROM feature_codes WHERE exten NOT IN (SELECT exten FROM t_extensions WHERE tenant_id = {$r1['id']} )");
                                                     
     echo json_encode( array( 'response' => array( 'status' => ($r2?'OK':'FAIL'), 'message' => "{$run} " . ($r2?'OK':'FAIL') . " err: ".mysql_error() ) ) );
    break;
  }
  
}


	

 
/*
 $data = array( array( 'itemName' => 'First' ),
                array( 'itemName' => 'Second' ),
                array( 'itemName' => 'Third' )
              );
  
 
 echo (json_encode( $data ));
 
*/








?>