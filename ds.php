<?php

session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location:entrance.php");
 }
 include_once('include/config.php');
 
	
	$_tenant_id = (int)$_SESSION['tenantid'];
	$ttres = mysql_query("SELECT ref_id FROM tenants WHERE id = {$_tenant_id}");
	$ttrow = mysql_fetch_assoc($ttres);
	$_tenant_name = $ttrow['ref_id'];
	  
	
		
   foreach ( array_merge($_POST,$_GET) as $key => $value) { ${$key} = $value; }   // strymno
   
	$allowed_set = array('t_sip_users', 't_ivrmenu','t_ivrmenu_items', 't_vmusers','t_route', 't_inbound', 't_inbound_rules', 't_timefilters','t_queues','t_queue_members','t_ringgroups','t_ringgroup_lists','t_conferences','t_extensions','feature_codes',
								'next_exten', 'routes_list',
								'view_route_tables', 't_moh', 'view_dids', 'view_tenants', 'view_mohfiles', 'view_tenantmoh', 'view_tenant_did','view_tenant_items','view_tenant_queuesmembers',
								'dids',  'tenants','trunks');	 
	$allowed_get = array_merge( $allowed_set, array( 'mtree','view_trunks','view_tenant_items2') );    // If we allow set -for sure, we allow get //
	
	$dialplan_tables = array('tenants','trunks','t_route','routes_list','t_sip_users','t_inbound','t_inbound_rules','t_ivrmenu','t_ivrmenu_items','t_ringgroups','t_ringgroup_lists');
	
	if ( ( isset($get) && !in_array($get, $allowed_get)) || ( isset($set) && !in_array($set, $allowed_set)) || ( isset($del) && !in_array($del, $allowed_set)) ) {
	  echo json_encode( array( 'response' => array( 'status' => 'FAIL', 'message' => "REQUEST  $get - $set NOT ALLOWED!" ) ) );
	  return;
	}
	

	 switch(true){	     
	  case isset($get) :
	     echo json_encode( DBSelect($get, json_decode($set_data,true ) ) );
	     break;
	
	  case isset($set) :
	     echo json_encode( DBUpdate($set, json_decode($set_data,true) ) );
	     break;
	
	  case isset($add) :
	     echo json_encode( DBInsert($add, json_decode($set_data,true) ) );
	     break;
	     
	  case isset($del) :
	     echo json_encode( DBDelete($del, $id, $_tenant_id ) );
	     break;
	 
	   default:
	     echo  json_encode( array( 'response' => array( 'status' => 'FAIL', 'message' => " DB Operation failed: Command not provided!" ) ) );
	     return;
	
	 }
	 
	 
	if ( in_array($set,$dialplan_tables ) || in_array($add,$dialplan_tables ) || in_array($del,$dialplan_tables ) ){
        $config->reload_dialplan();
        $config->reload_sip();
	}


//========================
// Simple-select Returns array //
function DBSelect($_table, $_filter){
    global $_tenant_id;
    global $_tenant_name;
    
     
    if ( substr($_table,0,2) == 't_' && ( !isset($_filter['id']) || $_filter['id'] > 0 ) )   // If id = 0 - then we do not filter tenant, return this zero default global object
      $_filter = array_merge( $_filter, array( 'tenant_id' => $_tenant_id ) );
  
       
    $WHERE_CLAUS = '';      
    foreach( $_filter as $_f => $_v ){
      	if( isset($_v) ) {
          $WHERE_CLAUS = $WHERE_CLAUS ? $WHERE_CLAUS . ' AND ' : '';         
          $WHERE_CLAUS .= " `{$_f}` = '{$_v}' ";
         }
    }	 
    $WHERE_CLAUS = $WHERE_CLAUS ? "WHERE {$WHERE_CLAUS}":'';
    
    if ($_table == 't_moh' && !isset($_filter['id']) ){
    	 $WHERE_CLAUS = $WHERE_CLAUS . ' AND id > 0 ';
    }
   	
  
 // Build THE Customized SELECT SQL Queries  ://
   switch($_table) {
   	 case 'view_trunks':
   	   $res = mysql_query("SELECT id, inTenants FROM trunks");
   	   while( $trunk = mysql_fetch_assoc($res) )
           if( in_array($_tenant_id, json_decode($trunk['inTenants']) )) 
              $myTrunks[]= $trunk['id'];
   	   $SQL = "SELECT id,name FROM trunks WHERE id IN (" . implode(',',$myTrunks) . ")";
   	   
         break;
         
       case 'view_dids':
   	   $SQL = "SELECT dids.id as id, dids.DID as DID, 
   	              concat( ifnull(concat(' -> Exten: ',t_sip_users.extension) ,'-') ) as assigned_to,
   	              assigned_destination,
   	              dids.description as description
   	           FROM dids
   	              LEFT JOIN t_sip_users ON t_sip_users.did_id = dids.id AND t_sip_users.tenant_id = dids.tenant_id   	              
   	   			WHERE dids.tenant_id = {$_tenant_id}";
         break;
         
   	 case 'view_route_tables':
   	  $SQL = "SELECT 1 as id,'All - (default)' as name UNION
   	          SELECT -1 as id,'Only Internal Calls' as name UNION
   	          SELECT 0 as id,'None - Dialing Disabled' as name UNION
   	  			 SELECT id,name FROM t_route WHERE route_enabled = 1 AND tenant_id = $_tenant_id";
         break;   	   
   	   
       case 'view_tenants':
         $SQL = "SELECT id,title,ref_id,
       		          (CASE WHEN id = 0{$_tenant_id} THEN '1' ELSE '0' END ) AS `is_selected` 
   	   	     FROM tenants";
         break;
         
       case 'view_tenant_queuesmembers':
       
         $SQL = "SELECT 
                        t_queues.name as queue_name,
         					extension as membername,
         					concat('SIP/',t_sip_users.name) as interface,
         					'no' as paused 
         		 FROM t_queues,t_sip_users 
         		 WHERE t_queues.tenant_id = t_sip_users.tenant_id AND 
         		       concat('SIP/',t_sip_users.name) not in (SELECT interface FROM t_queue_members WHERE queue_name = t_queues.name AND t_queue_members.tenant_id = t_queues.tenant_id) AND
         		       t_queues.name = '{$_filter['queue_name']}'  AND  
         		       t_queues.tenant_id = {$_tenant_id}";
                    		       
          
         break;

    
       case 'view_tenant_items':
             
             if(isset($_filter['item_type']) && $_filter['item_type'] == 'play_rec')
                foreach ( glob("/var/lib/asterisk/sounds/snd_{$_tenant_name}/*.*") as $MEDIA_FILE )
                  if(!is_dir($MEDIA_FILE)) {
                  	$REC = $REC ? $REC : "UNION\nSELECT  '' as id, 'none' as name, 'play_rec' as item_type\n"  ; 
                     $f = basename($MEDIA_FILE, '.' . end(explode('.', $MEDIA_FILE))); 
             	      $REC .= "UNION\n SELECT  '{$f}' as id, '{$f}' as name, 'play_rec' as item_type\n"  ;
             	     }
             	 
             $SQL = "SELECT * FROM ( SELECT id, extension as name, 'extension' as 'item_type' FROM t_sip_users WHERE tenant_id = {$_tenant_id}             			                
             			                UNION
             			            --    SELECT id, name, 'device' as 'item_type' FROM t_sip_users WHERE tenant_id = {$_tenant_id}             			                
             			            --   UNION
             			                SELECT id, name, 'ringgroup' as 'item_type' FROM t_ringgroups WHERE tenant_id = {$_tenant_id}
             			                UNION
             			                SELECT id, name, 'ivrmenu'   as 'item_type' FROM t_ivrmenu WHERE tenant_id = {$_tenant_id}
             			                UNION
                                     SELECT 9999 as id, 'Coming soon..' as 'name', 'featurecode' as item_type
                                     UNION
                                     SELECT id, name, 'queue' as item_type FROM t_queues  WHERE tenant_id = {$_tenant_id}
                                     UNION 
             			                SELECT id, concat( mailbox,'@',context) as name, 'voicemail' as item_type FROM t_vmusers WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT id, concat( mailbox,'@',context) as name, 'checkvm' as item_type FROM t_vmusers WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT id, conference as name, 'conference' as item_type FROM t_conferences WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT 9999, 'Coming soon..' as name, 'followme' as item_type
                                     UNION
                                     SELECT 9999, 'Coming soon..' as name, 'dirbyname' as item_type
                                     UNION
                                     SELECT id, concat(DID, ' (', description,')') AS name, 'did' AS item_type FROM  dids WHERE tenant_id = {$_tenant_id}
                                     UNION 
                                     SELECT id, name, 'timefilter'as item_type FROM t_timefilters WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT id, name, 'moh' as item_type FROM t_moh WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT appdata as id, concat(description,'[',appdata,']') as name, 'feature_code' as item_type FROM feature_codes           
                                     {$REC}                          
             			                ) t1
             	      {$WHERE_CLAUS} order by 1 asc";
         break;
         
       case 'view_tenantmoh':
             $SQL = "SELECT 0 as id, 'default' as moh_name UNION
             		   SELECT id, name as moh_name FROM t_moh WHERE tenant_id = " . ( $_filter['id'] ? $_filter['id'] : $_tenant_id ) ;
         break;
         
       case 'next_exten':
         // Return defailt values for next exten//
           return  Validate('t_sip_users', array() );           
         break; 
          
       default:
         $SQL = "SELECT * FROM `{$_table}` {$WHERE_CLAUS} ORDER BY `id`";
   }	  
   
   
 // Now - makre the query, and return result.   
   $_res = mysql_query($SQL);
   $_results = array();   
   if ( !$_res ) 
     $_results = array( 'response' => array( 'status' => 'FAIL', 'message' => "SELECT FAIL!  $SQL: ".mysql_error() )  ) ;
   else
     while( $_row = mysql_fetch_assoc($_res)){ $_results[] = $_row;  }
    
   return $_results;
}



// Returns array //
function DBUpdate($_table,$_data){
	// TODO - check tenant_id var - if it different from current one
   global $_tenant_id;
   $_results = array();
	 if (!isset( $_data['id'] ) || !is_numeric( $_data['id'] ) ){
	 	$_results = array( 'response' => array( 'status' => 'FAIL', 'message' => " UPDATE FAIL, wrong row ID for '{$_table}' !<br>Contact dev.team please" ) );
 	 }else{	
 	    $_data = Validate($_table, $_data);
	    foreach( $_data as $field => $value ){
	      $_new_values .= "`{$field}` = '{$value}',";
	    }
	    
	    $SQL = "UPDATE {$_table} SET ".trim($_new_values,',')." WHERE id = {$_data['id']}";
	    $res = mysql_query($SQL);   
	    if(!$res){
	    	$_results = array( 'response' => array( 'status' => 'FAIL', 'message' => " UPDATE '{$_table}' FAIL! err: ".mysql_error() ) );  
	    }else{
	      $_results = DBSelect($_table, array('id' =>  $_data['id'] )) ;
	    } 
	 }       
  return $_results;
}



function DBInsert($_table,$_data){    
// TODO - check tenant_id var - if it different from current one
    global $_tenant_id;
    
    if ( $_table == 'view_tenant_queuesmembers' )
	   return  $_data;
	    
    $_data = Validate($_table, $_data);
           
    $_f = implode( array_keys($_data), '`,`');        
    $_v = implode( array_values($_data) , "','");
        
    $SQL = "INSERT INTO {$_table}(`{$_f}`) VALUES('{$_v}')";        
    $res = mysql_query($SQL);
	 if(!$res)
	   return array( 'response' => array( 'status' => 'FAIL', 'message' => "  INSERT FAILED:   ". $SQL . "  " .mysql_error() ) );
    // Return new ID //	    
	 return  DBSelect($_table, array('id' => mysql_insert_id() ) );
	    
}



function DBDelete( $_table, $_filter_id ){
	// TODO - check tenant_id var - if it different from current one
	global $_tenant_id;
	
   
	
   if ( !isset($_table) || $_table == '' ||  !isset($_filter_id) || $_filter_id == '' || !is_numeric($_filter_id)  ) 
     return  array( 'response' => array( 'status' => 'FAIL', 'message' => "DELETE FAIL! Err: Wrong ID ( {$_filter_id} )"  ));
     
   /// Check Integrity
   if ( $_table == 'tenants' ){
   	 if ( $_filter_id == 1)  
         return  array( 'response' => array( 'status' => 'FAIL', 'message' => "CAN NOT DELETE DEFAULT TENANT! ( $_filter_id )"  ));
         
       if ( $_filter_id == $_SESSION['tenantid'] )
          $_SESSION['tenantid'] = 1;
   }
   
   
      
   // Validate deletion query - make sure we have data - what for? //
   $_res = mysql_query("SELECT id FROM `{$_table}`  WHERE `id` = {$_filter_id} ORDER BY `id`");   
	if ( !$_res ) 
	   return  array( 'response' => array( 'status' => 'FAIL', 'message' => "DELETE FAIL: Record  not found!  err: ".mysql_error() ) );

   // Delete row
	$_res = mysql_query("DELETE FROM `{$_table}`  WHERE `id` = {$_filter_id} ORDER BY `id`");
	if ( !$_res )
	   return  array( 'response' => array( 'status' => 'FAIL', 'message' => "DELETE FAIL! err: ".mysql_error() )) ;
	          
	return  array( 'id' => $_filter_id );   
	
}


// Function which checks table data for integrity 
function Validate($_tbl_name, $_row_data){
   global $_tenant_id;
   
   
   // Kostil stoit tut
   if ( $_tbl_name == 't_queue_members' )
  foreach(array_keys($_row_data) as $key)
       if ( preg_match('/_selection_/',$key) )
   	   unset( $_row_data[$key]);
   

 // We use 't_' prefix for tables with tenant data - make sure reference is set to filter tenant //
   if ( $_tenant_id && substr($_tbl_name,0,2) == 't_' ){
       $_row_data = array_merge( $_row_data,  array('tenant_id' => $_tenant_id ) ); 
    }	 
    
 // Data conversion on the fly   
  if ( $_tbl_name == 'trunks'){
     $_row_data['inTenants'] = json_encode( $_row_data['inTenants'] );
  }  
  if ( $_tbl_name == 't_ringgroup_lists' && isset($_row_data['extensions'])){
     $_row_data['extensions'] = json_encode( $_row_data['extensions'] );
  }
  
 if ( $_tbl_name == 't_conferences' && isset($_row_data['users'])){
     $_row_data['users'] = json_encode( $_row_data['users'] );
  }
  

 // t_extensions validator: 
  if ( $_tbl_name == 't_extensions' || $_tbl_name == 'feature_codes' ){
     $_row_data['context'] = "internal-{$_SESSION['tenantref']}-features";
     $_row_data['priority'] = 1;
     $_row_data['app'] = 'Macro';
     $_row_data['type'] = '3';
     $_row_data['subtype'] = '0';
  	}  
  
// VMail  valuidator	 // 
  if ( $_tbl_name == 't_vmusers' ){    
    if ( ( isset($_row_data['password'])  && trim($_row_data['password']) === '' ) ||
         (!isset($_row_data['id']) && ( trim($_row_data['password']) === '' || !isset($_row_data['password']) ) ) 
        )   
        $_row_data['password'] = rand ( 1000 , 9999 );
        
  }         
    
 // IVRMenuItems validator // 
  if ( $_tbl_name == 't_ivrmenu_items' ){
     $r  = 1;
   
  }   
    
 // MOH values valudator //
 
   if ( $_tbl_name == 't_moh' ){
     $_baseMOHdir = '/var/lib/asterisk/';
     if( array_key_exists( 'name', $_row_data ) && !$_row_data['name'] )  
        $_row_data['name'] = $_SESSION['tenantref'] . '-' . bin2hex(openssl_random_pseudo_bytes(2));
    
     if ( array_key_exists( 'name', $_row_data ) && !(strpos($_row_data['name'],$_SESSION['tenantref']) === 0) )
        $_row_data['name'] = $_SESSION['tenantref'].'-'.$_row_data['name'] ;
    
     if ( !isset($_row_data['id']) && !isset($_row_data['directory'])  ){
     	  $_row_data['directory'] = $_row_data['name'];
     }	 
     
     if ( array_key_exists( 'directory', $_row_data ) && !(strpos($_row_data['directory'],$_SESSION['tenantref']) === 0) )
        $_row_data['directory'] = $_SESSION['tenantref'].'-'.$_row_data['directory'] ;

     if ( isset($_row_data['directory']) && !is_dir($_baseMOHdir . $_row_data['directory']) ) {
       // Check if we rename existing//
       if ( isset($_row_data['id']) ) {       	 
     	   $_getolddir =  mysql_query("SELECT directory FROM t_moh WHERE id = {$_row_data['id']}");
     	   $_old= mysql_fetch_assoc($_getolddir);
     	   if ( mysql_affected_rows() && is_dir($_baseMOHdir . $_old['directory']) ) 
     	   	  rename($_baseMOHdir . $_old['directory'], $_baseMOHdir . $_row_data['directory'] );
     	 }
     	 if ( !is_dir($_baseMOHdir . $_row_data['directory'] ) )
          mkdir($_baseMOHdir . $_row_data['directory'],0777,true);
        
     }     	 
   	 
   }    
    
 // Sip Users valuidator	 //
  if ( $_tbl_name == 't_sip_users' ){
  	  
  	 // DEfault value:
    $_row_data['parkinglot'] = "parkinglot-{$_SESSION['tenantref']}";  	 
  	   
  	 if ( isset( $_row_data['outbound_route']) && $_row_data['outbound_route'] != 1 )
  	    $_row_data['context'] = "internal-{$_SESSION['tenantref']}-{$_row_data['outbound_route']}" ;
      else 
  	    $_row_data['context'] = "internal-{$_SESSION['tenantref']}" ;
  	 
  	  
  	 if( !array_key_exists( 'extension', $_row_data ) ) 
  	  if ( isset($_row_data['id']) ){  
   	 // Updating Existing row - extract extension for validate Name field later
  	      $_res = mysql_query("SELECT * FROM t_sip_users WHERE id = {$_row_data['id']} ");
         $_row = mysql_fetch_assoc($_res);
         $_row_data['extension'] = $_row_data['extension'] ? $_row_data['extension'] : $_row['extension'];
       //$_row_data['name'] =     $_row_data['name'] ? $_row_data['name'] : $_row['name'];
       //$_row_data['secret'] =   !is_null($_row_data['secret']) ? $_row_data['secret'] : $_row['secret'];
     }else{  
         // Generate Default Data for new extension
         $_res = mysql_query("SELECT max(convert(extension,decimal(10))) as extension FROM t_sip_users WHERE tenant_id = {$_SESSION['tenantid']}");
         $_row = mysql_fetch_assoc($_res);
         $_row_data['extension'] = is_null($_row['extension']) ? '101' : $_row['extension']+1;
     }
      
  	    
  	  if( array_key_exists( 'name', $_row_data ) && !$_row_data['name'] )  
        $_row_data['name'] = $_SESSION['tenantref'] . '-' . $_row_data['extension'];
    
     if ( array_key_exists( 'name', $_row_data ) && !(strpos($_row_data['name'],$_SESSION['tenantref']) === 0) )
        $_row_data['name'] = $_SESSION['tenantref'].'-'.$_row_data['name'] ;    	 
    	 
     if ( array_key_exists( 'secret', $_row_data ) &&   ! $_row_data['secret'] )   
        $_row_data['secret'] = bin2hex(openssl_random_pseudo_bytes(4));
   
     if ( isset( $_row_data['enable_mwi']) ) {     	 
          $_row_data['mailbox'] =  ( $_row_data['enable_mwi'] == 'yes' ) ?  $_row_data['extension'] . '@' . $_SESSION['tenantref'] . '-vmdefault' : '';
          $_row_data['vmexten'] =  ( $_row_data['enable_mwi'] == 'yes' ) ?  $_row_data['extension'] : '';
     } 
     
     if ( isset( $_row_data['did_id'] ) ) {
     	  $guess_id = isset($_row_data['id']) ? $_row_data['id'] : 0; 
     	  $check = mysql_query("SELECT 1 FROM t_sip_users WHERE did_id = {$_row_data['did_id']} AND id != {$guess_id}");
     	  if ( mysql_affected_rows() )  {
     	  	 $revert = mysql_query("SELECT did_id FROM t_sip_users WHERE id = {$guess_id} ");
     	  	 $rr = mysql_fetch_assoc($revert);
     	  	 $_row_data['did_id'] = $rr['did_id'] ? $rr['did_id'] : 0; 
          // return  array( 'response' => array( 'status' => 'FAIL', 'message' => "Can not re-assign ALREADY assigned DID" )) ;
        } 
     }
     
    // Generate Channel Variables
     $_db_data = mysql_fetch_assoc( mysql_query("SELECT outbound_callerid,outbound_callername,internal_callerid,internal_callername FROM t_sip_users WHERE id = {$_row_data['id']}"));           
     $out_cid   = $_row_data['outbound_callerid']   ? $_row_data['outbound_callerid']   :$_db_data['outbound_callerid'];
     $out_cname = $_row_data['outbound_callername'] ? $_row_data['outbound_callername'] :$_db_data['outbound_callername'];
     $int_cid   = $_row_data['internal_callerid']   ? $_row_data['internal_callerid']   :$_db_data['internal_callerid'];
     $int_cname = $_row_data['internal_callername'] ? $_row_data['internal_callername'] :$_db_data['internal_callername'];
     $vars = array();
     if ( $out_cid   ) $vars[] = "SET_EXTOUT_CID={$out_cid}";
     if ( $out_cname ) $vars[] = "SET_EXTOUT_CNAME={$out_cname}";
     if ( $int_cid   ) $vars[] = "SET_EXTINT_CID={$int_cid}";
     if ( $int_cname ) $vars[] = "SET_EXTINT_CNAME={$int_cname}";
     $vars[] = "tenant={$_SESSION['tenantref']}";
     $_row_data['setvar'] = count($vars) ? implode(';',$vars) : '';
        
  }	 
  
  //var_dump($_row_data);
	 
  return $_row_data;  	 
}


?>