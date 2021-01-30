<?php
error_reporting(E_ALL);

session_start();
include_once('include/config.php');

$str = json_encode(array_map('trim', array_merge( array_map('addslashes',$_POST), array_map('addslashes',$_GET) ) ) ); 
$config->log->putLog( ' DS IP:' . $_SERVER['REMOTE_ADDR'] .  '   Req:' . $str );


 $ip_blocked = mysql_fetch_assoc( mysql_query("SELECT * FROM blacklist WHERE ip = '{$_SERVER['REMOTE_ADDR']}' AND ip != '' LIMIT 1") );
 if( isset($ip_blocked) && $ip_blocked['block_web_access'] ){
   echo json_encode( array( 'response' => array( 'status' => 'LOGOUT', 'message' => "Session timeout!  Re-login you shortly, moment please... " ) ) );
    return;
 }




 if ( !isset($_SESSION['UID']) ) {
 	if ( $_POST['set' ] || $_GET['get'] || $_POST['del'] || $_POST['add'] || $ip_blocked ){
 	 echo json_encode( array( 'response' => array( 'status' => 'LOGOUT', 'message' => "Session timeout!  Re-login you shortly, moment please... " ) ) );
 	 return;
 	}else{
	    header("Location:entrance.php");
	    return;
  }  
 }else{
    mysql_query("UPDATE admin_users SET last_login = now(), last_login_ip = '{$_SERVER['REMOTE_ADDR']}' WHERE id = {$_SESSION['UID']} ");
     mysql_query("UPDATE admin_users SET last_login_ip = '{$_SERVER['REMOTE_ADDR']}' WHERE id = {$_SESSION['UID']} ");
     $METHOD = $_POST ? 'POST':'GET';
     $DATA =  json_encode( array_merge($_POST,$_GET) ) ;
     mysql_query("INSERT INTO admin_user_log(user_id,user_agent,method,request_data,from_ip) 
                   VALUES({$_SESSION['UID']}, 
                         '{$_SERVER['HTTP_USER_AGENT']}',
                         '${METHOD}', 
                         '${DATA}',
                         '{$_SERVER['REMOTE_ADDR']}'
                         )"
                 );
  }   
 
 
 
 	
	$_tenant_id = (int)$_SESSION['tenantid'];
	//echo $_tenant_id; 
	$ttres = mysql_query("SELECT ref_id FROM tenants WHERE id = {$_tenant_id}");
	$ttrow = mysql_fetch_assoc($ttres);
	$_tenant_name = $ttrow['ref_id'];

	$params_array = array_merge($_POST,$_GET);	

        foreach ( $params_array as $key => $value) { ${$key} = $value; }   // strymno

   
	$allowed_set = array('t_sip_users', 't_ivrmenu','t_ivrmenu_items', 't_vmusers','t_route', 't_inbound', 't_inbound_rules', 't_queues','t_queue_members','t_ringgroups','t_ringgroup_lists','t_conferences','t_extensions','feature_codes','t_user_options','t_user_blocklist','t_user_screening','t_user_followme','t_cdrs','t_campaigns','admin_users','t_pagegroups','t_pagegroup_members','t_scheduler',
								'next_exten', 'routes_list',
								'view_route_tables', 't_moh', 'view_dids', 'view_tenants', 'view_mohfiles', 'view_tenantmoh', 'view_tenant_did','view_tenant_items','view_tenant_queuesmembers','view_tenant_pagegroupmembers','t_cdrs_view','view_tenant_extensions',
								'dids',  'tenants','trunks','tenant_shiftreports','t_shifts','blacklist','t_sip_user_devices','view_tenant_admin_sip');	 
	$allowed_get = array_merge( $allowed_set, array( 'mtree','t_lostcalls_view', 'view_trunks','view_tenant_items2','admin_user_log' , 'did','clid', 't_recordings_dt' ) );    // If we allow set -for sure, we allow get //
	
	$dialplan_tables = array('tenants','trunks','t_route','routes_list','t_sip_users','t_inbound','t_inbound_rules','t_ivrmenu','t_ivrmenu_items','t_ringgroups','t_ringgroup_lists','t_pagegroups','t_pagegroup_members','t_shifts');
	
	if ( ( isset($get) && !in_array($get, $allowed_get)) || ( isset($set) && !in_array($set, $allowed_set)) || ( isset($del) && !in_array($del, $allowed_set)) ) {
	  echo json_encode( array( 'response' => array( 'status' => 'FAIL', 'message' => "REQUEST  $get - $set NOT ALLOWED!" ) ) );
	  return;
	}

	 switch(true){	     
	  case isset($get) :
        if ( !isset($format) )
	       echo json_encode( DBSelect($get, json_decode($set_data,true ) ) );
        else
          DataExport( DBSelect($get, json_decode($set_data,true ) ) , $format );
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
	     echo  json_encode( array( 'response' => array( 'status' => 'FAIL', 'message' => " DB Operation failed: Command not implemented!" ) ) );
	     return;
	
	 }
	 
	 
	if ( in_array($set,$dialplan_tables ) || in_array($add,$dialplan_tables ) || in_array($del,$dialplan_tables ) ){		  
		 if( $config->auto_reload ) {
          $config->reload_dialplan();
          $config->reload_sip();
       }   
	}


function DataExport($_data, $format = 'csv'){
             
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=PBXexportData.csv');
    header('Pragma: no-cache');
    header("Expires: 0");

    //$outstream = fopen("php://output", "w");    
    $outstream = fopen("/tmp/123.csv", "w");    
    fputcsv($outstream, array_keys($_data[0]));

    foreach($_data as $row)
    {
        fputcsv($outstream, $row);
    }

    fclose($outstream);
    echo '123123';
 }


//========================
// Simple-select Returns array //
//========================
function DBSelect($_table, $_filter){
	
	  global $config;
    global $_tenant_id;
    global $_tenant_name;
    global $params_array;
    
 // UPGRADES SECTION

  
  if( !mysql_query("SELECT linkedid FROM t_cdrs limit 1")) {
      mysql_query("ALTER TABLE t_cdrs ADD linkedid varchar(32) after uniqueid");      
   }

   if( !mysql_query("SELECT context_script FROM t_inbound LINIT 1")  ){
        mysql_query("ALTER TABLE t_inbound add context_script varchar(100) ");
    }
 
  if( !mysql_query("SELECT archivate_cdrs_after FROM tenants LIMIT 1") ){
	mysql_query("ALTER TABLE tenants ADD archivate_cdrs_after integer default 90");
        mysql_query("ALTER TABLE tenants ADD cdrs_total integer null");
	if(!mysql_query("SELECT max(id) FROM t_cdrs_archive LIMIT 1")){
	  mysql_query("CREATE TABLE IF NOT EXISTS t_cdrs_archive LIKE t_cdrs");
	}
   }

     if( !mysql_query("SELECT default_action from t_queues LIMIT 1") ){
    mysql_query("ALTER TABLE t_queues add default_action    varchar(100) ");
 }

  if( !mysql_query("SELECT served FROM t_cdrs limit 1")) {
   mysql_query("ALTER TABLE t_cdrs ADD served varchar(50) after service_status");
   mysql_query("ALTER TABLE t_cdrs_archive ADD served varchar(50) after service_status");
  }

  
  
  
    
     
    if ( substr($_table,0,2) == 't_' && ( !isset($_filter['id']) || $_filter['id'] > 0 ) )   // If id = 0 - then we do not filter tenant, return this zero default global object
    $_filter = array_merge( $_filter, array( 'tenant_id' => $_tenant_id ) );
      
     // Show only users from same tenant for 'Tenant Admin' admin Role 
    if ( $_table == 'admin_users' && $config->isTenantAdmin() ) 
       $_filter = array_merge( $_filter, array( 'default_tenant_id' => $_tenant_id ) );
       
    if ( $_table == 't_cdrs_view' ){
      // $_filter = array_merge( $_filter, array( 'dstchannel' => '!' ) );
      $_filter = array_merge( $_filter, array( 'dcontext' => '!helpers' ) );
      $WHERE_CLAUS = isset($WHERE_CLAUS)  ?  $WHERE_CLAUS . ' AND ifnull(uniqueid,"") !="" ' : 'WHERE  ifnull(uniqueid,"") !="" ';

    }

  
       
    $WHERE_CLAUS = '';      
    foreach( $_filter as $_f => $_v ){
      	if( isset($_v) && $_f != 'download_filename' && $_f != 'has_recording' && $_f != 'limit') {
          $WHERE_CLAUS = $WHERE_CLAUS ? $WHERE_CLAUS . ' AND ' : '';
          if( $_v[0] != '!' )          
            $WHERE_CLAUS .= " `{$_f}` = '{$_v}' ";
          else
            $WHERE_CLAUS .= " `{$_f}` != '".substr($_v,1)."' ";
         }
    }	 

    $WHERE_CLAUS = $WHERE_CLAUS ? "WHERE {$WHERE_CLAUS}":'';
    
    if ($_table == 't_moh' && !isset($_filter['id']) ){
    	 $WHERE_CLAUS = $WHERE_CLAUS  ? $WHERE_CLAUS . ' AND id > 0 ' :  'WHERE id > 0 ' ;
    }

  
 // Build THE Customized SELECT SQL Queries  ://
   switch($_table) {
  
       case 'mtree':
	        $UID = $_SESSION['UID'];
	        if($UID){
	        	 // Get from Json :
	        	 $r = mysql_query("SELECT replace(replace(allowed_sections,'[',''),']','') as allowed,role FROM admin_users WHERE id = {$UID} LIMIT 1" );
	  	       $row = mysql_fetch_assoc($r);
	  	       $arr_data = ( $row['allowed'] &&  is_array( explode(',', $row['allowed']) ) ) ? explode(',', $row['allowed']) : array(1) ;

              if(!mysql_query("SELECT visible FROM mtree LIMIT 1")){
                  mysql_query("ALTER TABLE mtree ADD visible integer default 1");
              }
	  	         	       

	  	       if( in_array(0, $arr_data) )      // nothing allowed - show only tree nodes (action = '' )
	  	          $SQL = "SELECT * FROM mtree WHERE visible =1 AND action = '' ";
	  	       elseif( in_array(1,$arr_data ))  // all allowed , show all?	 
	  	          $SQL = "SELECT * FROM mtree WHERE visible = 1  ";
	  	       else                             // Custom section list allowed 
	  	          $SQL = "SELECT * FROM mtree WHERE visible =1 AND ( id in ( {$row['allowed']}) OR id in (SELECT parent_id FROM mtree WHERE id in ( {$row['allowed']} )) ) ";

             $super_admin_nodes = '14,15,16,18,19,20,21,23';
		         $admin_nodes = '13,14,15,16,18,19,20,21,23';
	         

	  	       
                       // Only Tenant Level(user) access cut sections  //             	  	       
	  	       if( $row['role'] == 3 )
	  	       	$SQL = $SQL . " AND  ( id NOT IN ( {$admin_nodes} ))";
	  	       	
	  	       // Only Tenant Level(Admin) access - cut sections  except Users tab //  	
	  	       if( $row['role'] == 2 )
	  	       	$SQL = $SQL . " AND  ( id NOT IN ( {$super_admin_nodes} ) )";
	  	       	  
	  	       $SQL = $SQL . " ORDER BY `id`";
	  	     }else{
	  	     	 $SQL = "SELECT 0 as id,'Invalid Session, relogin!' as name,'' as action,1 as parent_id, '/images/Adminusers.png' as nodeIcon ";
	  	     }
  	  
        break;
        
       case 't_lostcalls_view':
       
         $SQL = "SELECT 
                  date(calldate) as cdate,                  
                  CASE WHEN t_queues.name IS NOT NULL THEN concat( t_queues.name ,' - ', SUBSTRING_INDEX(service_status, ':', 1))
                       WHEN t_ringgroups.name IS NOT NULL THEN concat( t_ringgroups.name ,' - ', SUBSTRING_INDEX(service_status, ':', 1))
                  END as call_group_name,     
                  sum(CASE WHEN service_status LIKE '%ABANDONED%' THEN 1 END) as lost_calls,
                  sum(CASE WHEN service_status LIKE '%ANSWERED%' THEN 1 END) as answered,
                  count(*) as total_calls,
                  ROUND((sum(CASE WHEN service_status LIKE '%ABANDONED%' THEN 1 END) * 100) / count(*)) as lost_percent
                FROM t_cdrs  
                   LEFT JOIN t_ringgroups ON t_ringgroups.id = SUBSTRING_INDEX(SUBSTRING_INDEX(service_status, ':', 1),'-',-1)  AND t_ringgroups.tenant_id = t_cdrs.tenant_id 
                   LEFT JOIN t_queues ON t_queues.id = SUBSTRING_INDEX(SUBSTRING_INDEX(service_status, ':', 1),'-',-1)  AND t_queues.tenant_id = t_cdrs.tenant_id
                 WHERE service_status is not null AND
                       t_cdrs.tenant_id = $_tenant_id
                 GROUP BY 2,1
                 ORDER BY 1,2";
       
        break; 

       case 't_recordings_dt' :

	$json = array();
      	$data = array();
      	$fields = array( 'uniqueid' => '#ID#',
          'calldate' => 'Call date',
          'src' => 'From',
          'dst' => 'To',
          'duration'  =>  'Duration',
          'billsec'   => 'Talk',
          'disposition' => 'Result',
          'channel'  => 'Channel',
          'recording' => 'Recording'
      	);

      	$p_size = isset($length) ? $length : 10;
      	$offset = isset($start) ? $start .', ': '0,';
      	if( isset($order) ){
          $order = $order;
          $order_field_name = array_keys($fields)[ $order[0]['column'] ];
          $order = " ORDER BY {$order_field_name} {$order[0]["dir"]}";
      	}
      	$OPTIONS = " {$order} LIMIT {$offset} {$p_size}";
         //$WHERE[] = " direction like 'INBOUND' and recordingfile <> 'none' ";

      	if(!empty($calldate)) {
          $startDate = date('Y-m-d 00:00:00', strtotime($date));
          $endDate = date('Y-m-d 23:59:59', strtotime($date));
          $WHERE[] = " calldate >= '{$startDate}' and  calldate <= '{$endDate}'";
      	}
      	if(!empty($src)) $WHERE[] = " src like '%{$src}%' ";
      	if(!empty($dst)) $WHERE[] = " dstchannel like '%/{$dst}-%' ";

        $WHERE = $WHERE ? ' AND ' . implode($WHERE, 'and') : '';
      	$F = $WHERE_CLAUS . $WHERE ;

      	$SQL = "SELECT " . implode(',', array_keys($fields)) . " FROM t_cdrs {$F} {$OPTIONS}";

       break;
 
        
       case 't_cdrs_view': 

          $filter_fields = array('tenant_name','disposition','clid','did','dst','peername');  
          $CDR_FILTER = array();

        // Search for Single Criteria //
          foreach($filter_fields as $field){
            if(isset($params_array[$field])){
              $val = $params_array[$field];
              $CDR_FILTER[] = "  `{$field}` like '%{$val}%' "  ;
            }          
          }

          $sign = array( 'greaterOrEqual' => '>=',
                         'lessOrEqual' => '<=',
                         'iContains' => 'like'
                        );

        // Criteria Constructor //  
         if(isset($params_array['_constructor'])){         
          foreach($params_array['crit'] as $criteria_json ){
            $criteria = json_decode( $criteria_json , true);
            switch( true ){              
              case isset($criteria['_constructor']):  // nested-constructor , usually for date fields 
                 foreach( $criteria['criteria'] as $sub_crit)                                      
                   $CDR_FILTER[] = " date(`{$sub_crit['fieldName']}`) {$sign[$sub_crit['operator']]} date('{$sub_crit['value']}') ";   
              break;

              case $criteria['fieldName'] == 'calldate' : 
                $CDR_FILTER[] = " date(`{$criteria['fieldName']}`) {$sign[$criteria['operator']]} date('{$criteria['value']}') ";
              break;

              case $criteria['operator'] == 'iContains':
                 $CDR_FILTER[] = " `{$criteria['fieldName']}` {$sign[$criteria['operator']]} '%{$criteria['value']}%' ";
              break;    

             // default:  
             //   $CDR_FILTER[] = " `{$criteria['fieldName']}` {$sign[$criteria['operator']]} '{$criteria['value']}' ";
             // break;
            } 
            
          }
         }

         $FILTER = count($CDR_FILTER) ? join($CDR_FILTER,' AND ') . ' AND' : '';

          $SQL = "SELECT  *,
                         replace(lastdata,',',':') as lastdata,
                         replace(replace(channel,',',':'),';',':' ) as channel,
                         (SELECT title from tenants where id = t_cdrs.tenant_id) as title,                          
                         sec_to_time(billsec) as billsec
                  FROM t_cdrs                       
                       {$WHERE_CLAUS} AND {$FILTER}                                        
                      (
                        ( lastapp != 'Queue' )  OR 
                        ( lastapp = 'Queue' AND SUBSTRING_INDEX(service_status, ':', -1) != service_status  )
                      ) 
                  ORDER BY 3 DESC ";
        // echo $SQL;
        break;   	
   	
       case 'view_trunks':
   	   $res = mysql_query("SELECT id, inTenants FROM trunks");
   	   while( $trunk = mysql_fetch_assoc($res) )
           if( in_array($_tenant_id, json_decode($trunk['inTenants']) )) 
              $myTrunks[]= $trunk['id'];
   	   $SQL = "SELECT 0 as 'id', 'none' as name UNION SELECT id,name FROM trunks WHERE id IN (0," . implode(',',$myTrunks) . ")";
   	   
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
   	  			 SELECT id, concat(name,CASE WHEN route_enabled = 0 THEN ' (private) ' ELSE '' END ) as name FROM t_route WHERE  tenant_id = $_tenant_id";
         break;   	   
   	   
       case 'view_tenants':
         $SQL = "SELECT id,title,ref_id,
       		          (CASE WHEN id = 0{$_tenant_id} THEN '1' ELSE '0' END ) AS `is_selected` 
   	   	     FROM tenants ";
   	   if ( !$config->isAdmin() )
   	   	  $SQL .= " WHERE id = (SELECT default_tenant_id FROM admin_users WHERE id = " . $config->getUID() . " LIMIT 1)" ;
         break;
         
         
       case 'tenants':       
         $SQL = "SELECT *,(select count(*) FROM t_sip_users WHERE tenant_id = tenants.id) as extensions_count FROM `{$_table}` {$WHERE_CLAUS} ORDER BY `id`";
         
         break;  
       case 'view_tenant_pagegroupmembers':
       
         $SQL = "SELECT t_pagegroups.id as pagegroup_id,
                       extension as membername,
         					concat('SIP/',t_sip_users.name) as interface
         		 FROM t_pagegroups,t_sip_users 
         		 WHERE t_pagegroups.tenant_id = t_sip_users.tenant_id AND 
         		       concat('SIP/',t_sip_users.name) not in (SELECT interface FROM t_pagegroup_members WHERE pagegroup_id = t_pagegroups.id AND t_pagegroup_members.tenant_id = t_pagegroups.tenant_id) AND
         		       t_pagegroups.id = '{$_filter['pagegroup_id']}'  AND  
         		       t_pagegroups.tenant_id = {$_tenant_id}";
         		       //echo $SQL;
                    		       
          
         break;   
         
       case 'view_tenant_queuesmembers':          
         
         $S = isset($_filter['membername']) ? $_filter['membername'] : $_filter['interface'];
         $F = ( isset($S) && $S != '' )? "  t_sip_users.name LIKE '%{$S}%' AND " : ''; 
         

         $SQL = "SELECT 
                  t_queues.name as queue_name,
         					CASE WHEN a.user IS NOT NULL THEN concat(extension,' ', ifnull(a.user,''),' ',ifnull(a.user_fname,'')) ELSE extension END  as membername,
         					concat('SIP/',t_sip_users.name) as interface,
         					0 as paused 
         		 FROM t_queues, t_sip_users 
                  LEFT JOIN admin_users a ON a.sip_user_id = t_sip_users.id AND a.default_tenant_id = t_sip_users.tenant_id
         		 WHERE t_queues.tenant_id = t_sip_users.tenant_id AND 
                   t_queues.name = '{$_filter['queue_name']}'  AND  
                   t_queues.tenant_id = {$_tenant_id} AND                      
                   {$F}
         		       concat('SIP/',t_sip_users.name) not in (SELECT interface FROM t_queue_members 
                                                            WHERE queue_name = t_queues.name AND 
                                                                  t_queue_members.tenant_id = t_queues.tenant_id)  ";
                 //echo $SQL;   		       
          
         break;

       case 'view_tenant_extensions':
   	   $SQL = "SELECT id, extension as name, name as 'device', 'extension' as 'item_type',tenant_id FROM t_sip_users {$WHERE_CLAUS} order by 1 asc";
         break;   	
   	
    
       case 'view_tenant_items':
             
             if( isset($_filter['item_type']) &&  ( $_filter['item_type'] == 'play_rec' || $_filter['item_type'] == 'park_announce_rec' ) )
                foreach ( glob("/var/lib/asterisk/sounds/snd_{$_tenant_name}/*.*") as $MEDIA_FILE )
                  if(!is_dir($MEDIA_FILE)) {
                  	$REC = $REC ? $REC : "UNION\nSELECT  '' as id, 'none' as name, '{$_filter['item_type']}' as item_type,0 as tenant_id\n"  ; 
                     $f = basename($MEDIA_FILE, '.' . end(explode('.', $MEDIA_FILE))); 
             	      $REC .= "UNION\n SELECT  '{$f}' as id, '{$f}' as name, '{$_filter['item_type']}' as item_type, 0 as tenant_id\n"  ;
             	      if($_filter['item_type'] == 'park_announce_rec' )
             	       $REC .= "UNION\nSELECT  '' as id, 'Upload new....' as name, '{$_filter['item_type']}' as item_type,0 as tenant_id\n"; 
             	     }
             
             $SQL = "SELECT * FROM ( SELECT id, extension as name, 'extension' as 'item_type',tenant_id FROM t_sip_users WHERE tenant_id = $_tenant_id 
             			                UNION
             			                SELECT id, name, 'ringgroup' as 'item_type',tenant_id FROM t_ringgroups WHERE tenant_id = {$_tenant_id}
             			                UNION
             			                SELECT id, name, 'ivrmenu'   as 'item_type', tenant_id FROM t_ivrmenu WHERE tenant_id = {$_tenant_id}
             			                UNION
                                     SELECT id, name, 'queue' as item_type,tenant_id FROM t_queues  WHERE tenant_id = {$_tenant_id}
                                     UNION 
												          SELECT id, name, 'pagegroup' as item_type, tenant_id FROM t_pagegroups  WHERE tenant_id = {$_tenant_id}			
                                     UNION 
             			                SELECT id, concat( mailbox,'@',context) as name, 'voicemail' as item_type,tenant_id FROM t_vmusers WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT id, concat( mailbox,'@',context) as name, 'checkvm' as item_type,tenant_id FROM t_vmusers WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT id, conference as name, 'conference' as item_type,tenant_id FROM t_conferences WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT name as id, name, 'followme' as item_type, tenant_id FROM t_user_options  WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT 9999, 'Coming soon..' as name, 'dirbyname' as item_type, 0 as tenant_id
                                     UNION
                                     SELECT id, concat(DID, ' (', description,')') AS name, 'did' AS item_type, tenant_id FROM  dids WHERE tenant_id = {$_tenant_id}
                                     UNION 
                                     SELECT id, name, 'timefilter'as item_type,tenant_id FROM t_timefilters WHERE tenant_id = {$_tenant_id}
                                     UNION
                                     SELECT id, name, 'moh' as item_type, tenant_id FROM t_moh WHERE tenant_id = {$_tenant_id}
                                     UNION 
                                     SELECT id, name, 'sections' as item_type, 0 FROM mtree WHERE action != ''
                                     UNION
                                     SELECT appdata as id, concat(description,' | ',appdata,'') as name, 'feature_code' as item_type, tenant_id FROM feature_codes
                                     UNION 
                                     SELECT id, name, 'role' as item_type, 0 FROM admin_user_roles WHERE id >= 0" . $config->getRole() . "            
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

       case 'view_tenant_admin_sip':  
         if(isset( $_filter['id']) && $_filter['id'] != 0)
          $SQL = "SELECT id, extension as name, 'extension' as 'item_type',tenant_id  FROM t_sip_users WHERE id = {$_filter['id']} ";
         else                          
          $SQL = "SELECT 0 as id, '(none)' as 'name', 'extension' as 'item_type', {$_tenant_id} as tenant_id
                  UNION  
                  SELECT id, extension as name, 'extension' as 'item_type',tenant_id
                  FROM t_sip_users 
                  WHERE 
                     tenant_id = 0{$_filter['user_def_tenant_id']} AND
                     ( id = 0{$_filter['current_sip_id']} OR              
                       id NOT IN (SELECT ifnull(sip_user_id,0) FROM admin_users  WHERE tenant_id = 0{$_filter['user_def_tenant_id']})  
                      ) 
                     ";  
         break;
         
       case 'admin_users':
          $SQL = "SELECT *,admin_users.id as id,                          
                          IF( time_to_sec(timediff( ifnull(last_login,'2018-01-01 00:00:00'), now() )) < -300,'','LOGGED IN') as status,
                          (SELECT name FROM t_sip_users WHERE  id = admin_users.sip_user_id AND  tenant_id = admin_users.default_tenant_id) as user_sip_exten
                          
                  FROM admin_users
                   {$WHERE_CLAUS} order by 1 asc ";
         break;   
         
       case 't_campaigns':
           $res = mysql_query("UPDATE t_campaigns SET 
                                     leads_total =    (SELECT count(*) FROM t_campaign_leads WHERE t_campaign_id = t_campaigns.id),
                                     leads_answered = (SELECT count(*) FROM t_campaign_leads WHERE t_campaign_id = t_campaigns.id AND campaign_status = 'ANSWERED'),
                                     leads_dialed =   (SELECT count(*) FROM t_campaign_leads WHERE t_campaign_id = t_campaigns.id AND ifnull(last_called,0) != 0)
                                {$WHERE_CLAUS} ") ; 
          $SQL = "SELECT *,
                    if(leads_total > 0,'progress','') as progress, 
                    if(leads_total > 0 ,round(leads_dialed*100/leads_total),0) as completed 
                 FROM t_campaigns {$WHERE_CLAUS} ORDER BY `id`";
          break;  
          
          
       default:
         $SQL = "SELECT * FROM `{$_table}` {$WHERE_CLAUS} ORDER BY `id`";
         
   }	  
  

 // Now - makre the query, and return result //
   if ( isset($_filter['limit']) )
     $limit = (int)$_filter['limit'];
   else
     $limit = $config->ini['DB']['max_result'] ? $config->ini['DB']['max_result'] : 10000;

   $LIMIT_ROWS = $limit ? "LIMIT ${limit}" : '';
    // Apply limits only it is not yet defined //
   if( !preg_match('/LIMIT/',$SQL) ){
      if ( isset($_filter['limit']) )
        $limit = (int)$_filter['limit'];
      else
        $limit = $config->ini['DB']['max_result'] ? $config->ini['DB']['max_result'] : 10000;

      $LIMIT_ROWS = $limit ? "LIMIT ${limit}" : '';
      $SQL = $SQL . " " . $LIMIT_ROWS;
   }   


   $_res = mysql_query($SQL) or die( json_encode( array( 'response' => array( 'status' => 'FAIL', 'message' => " DB Operation failed: " . mysql_error()  ) ) ) );     
   $_results = array();
      
   if ( !$_res ) 
     $_results = array( 'response' => array( 'status' => 'FAIL', 'message' => "SELECT FAIL!  $SQL: ".mysql_error() )  ) ;
   else
     while( $_row = mysql_fetch_assoc($_res)){ 


       if($_table=='t_cdrs_view' || $_table == 't_recordings_dt' ){
          $_row['recording'] = isset($_row['recording']) ? $_row['recording'] : $_row['uniqueid'];
          $_row['recording'] = file_exists("/var/spool/asterisk/monitor/{$_row['recording']}.WAV") ? $_row['recording'] : $_row['uniqueid'];          
         $is_recorded = file_exists("/var/spool/asterisk/monitor/{$_row['recording']}.WAV") && ( filesize("/var/spool/asterisk/monitor/{$_row['recording']}.WAV") > 61 )   ? 1 : 0 ;
         $_row['has_recording']  = $is_recorded;

         if( isset($_filter['has_recording']) && $_filter['has_recording'] == 1 && !$is_recorded )
            continue;
       }       

       if($_table == 'trunks' ){
        if( $_row['sip_register'] )
           $_row['trunk_reg_status'] = get_registry_status( $_row['defaultuser'] );
        else  
          $_row['trunk_reg_status'] = get_peer_status( $_row['name'] );
       }
       	 
       if($_table=='t_sip_users'){
          $_row['reg_status'] = get_sip_status($_row['id']);	           
          $_row['click2dial_url'] = $config->ini['general']['pbx_web_address'] . '/c2c.php';
          $_row['click2dial_exten'] =  substr(md5($_row['name']),0,10);

       if( $_row['click2talk_enabled'] && isset($config->ini['general']['local_pbx_web_address'])){
          $pbx_host = $config->ini['general']['local_pbx_web_address'] ;   
          $c2t_key = substr(strrev(sha1($_row['name'])),0,10);
          $c2t_code = "<script type='text/javascript' src='{$pbx_host}/c2talk/{$c2t_key}.htm'></script>";
          $c2t_code = file_get_contents("{$pbx_host}/c2talk/{$c2t_key}.htm");
          $_row['click2talk_options'] = $_row['click2talk_options'] ? $_row['click2talk_options'] : $c2t_code;
       }
          $crm_user = mysql_fetch_assoc( mysql_query("SELECT * FROM admin_users WHERE sip_user_id = {$_row['id']} AND default_tenant_id = {$_row['tenant_id']} ") ) ;
          if($crm_user){
            $_row['crm_enabled'] = 1;
            $_row['crm_username'] = $crm_user['user'];
            $_row['crm_password'] = $crm_user['pass'];
          }  
          $FWDOnBusy='';
          $FWD='';
          $user_options = mysql_fetch_assoc( mysql_query("SELECT call_forward_onbusy,call_forwarding,call_forward_timeout FROM t_user_options WHERE t_sip_user_id = {$_row['id']} ")); 
          if($user_options){
            if($user_options['call_forward_onbusy'])
              $FWDOnBusy =' OnBusy:'. (($user_options['call_forward_onbusy'] == 1) ?  'VoiceMail;' : $user_options['call_forward_onbusy'] ); 
            if($user_options['call_forwarding'])
              $FWD = 'to:'.(($user_options['call_forwarding'] == 1)?  'VoiceMail;' : $user_options['call_forwarding']); 

            $_row['option_forwarding'] = $FWD . $FWDOnBusy;
            $_row['option_timeout'] = $user_options['call_forward_timeout'];
          }
       }

       	 
       $_results[] = $_row; 
     }

  // Convert data into DataTable()  json format //

     if( preg_match('/_dt/',$_table)  ){
          $json = array();
          $json['data'] = $_results;
          $json['draw'] = $_GET['draw'] ? $_GET['draw'] : 1;
          $json['recordsTotal'] = count($_results);
          $json['recordsFiltered'] = count($_results);  // Count total without offset and limits //
          //echo (count($json['data']) == 1) ? json_encode($json['data']) : json_encode($json);
	  $_results = $json;
     }
    
    return $_results;
}




function get_registry_status($trunk_name){
  exec("/usr/sbin/rasterisk -rx 'sip show registry'|grep '{$trunk_name}'",$r);
  return preg_replace(array('/N     /','/          /','/      /'),'',$r[0] );
  
}

function get_peer_status($trunk_name){
  unset($match);
  exec("/usr/sbin/rasterisk -rx 'sip show peers'|grep '{$trunk_name}'",$r);
  preg_match("/Unmonitored|UNREACHABLE|OK \((.*)\)/",$r[0],$match);
  return $match[0];  
}


function get_sip_status($id){
	$res = mysql_query("SELECT 
  (CASE
                      WHEN IFNULL(t_sip_users.ipaddr,'') = '' THEN 'REG_OFF'
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) > 0) THEN 'REG_ON'
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) < 10000) THEN 'REG_OFF' 
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) < 100) THEN concat('EXPIRED[',regseconds - UNIX_TIMESTAMP(),'ms]')
                         END) AS chan_reg_status
                         FROM t_sip_users WHERE id = {$id}");
	$dres = mysql_query("SELECT dnd FROM t_user_options WHERE t_sip_user_id = {$id}");
        $dnd = mysql_fetch_assoc($dres);
        $dnd_mark = $dnd['dnd'] ? '_DND':'';
    $ret = mysql_fetch_assoc($res);
    return $ret['chan_reg_status'] . $dnd_mark;                           

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
	    	if( !preg_match('/^_embedded/',$field))
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
    
    if ( $_table == 'view_tenant_queuesmembers' || $_table == 'view_tenant_pagegroupsmembers' )
	   return  $_data;
	    
    $_data = Validate($_table, $_data);

 

  foreach($_data as $d_key => $d_value) 
    if(preg_match('/^_embedded/', $d_key)) 
        unset($_data[$d_key]);


           
    $_f = implode( array_keys($_data), '`,`');        
    $_v = implode( array_values($_data) , "','"); 
     
    $IGNORE = ($_table == 't_vmusers') ? 'IGNORE' : ''; 
         
    $SQL = "INSERT {$IGNORE} INTO {$_table}(`{$_f}`) VALUES('{$_v}')";
    //echo $SQL;        
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
   
   if ( $_table == 't_pagegroups' && $_filter_id ){
   	mysql_query("DELETE FROM t_pagegroup_members WHERE pagegroup_id = {$_filter_id} and tenant_id =  $_tenant_id");   	 
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

  $t = mysql_query("SELECT 1 FROM admin_user_roles WHERE id = 6");
  if( !mysql_affected_rows() ){
     mysql_query("INSERT INTO admin_user_roles VALUES(6,'Disabled','Account disabled, no any activity enabled')");
  }


  if(!mysql_query("SELECT namedpickupgroup FROM t_sip_users")){
    mysql_query("alter table t_sip_users add namedpickupgroup varchar(100)");
    mysql_query("alter table t_sip_users add namedcallgroup varchar(100)");
  }
     
  //if(!mysql_query("SELECT direction FROM t_cdrs LIMIT 1")){
  //    mysql_query("ALTER TABLE t_cdrs ADD direction varchar(100)");
  //    mysql_query("CREATE INDEX t_cdrs_tenant_id ON t_cdrs(tenant_id)" );
  // }

   if( !mysql_query("SELECT shabash FROM tenants LIMIT 1"))
         mysql_query("ALTER TABLE tenants add shabash varchar(50) DEFAULT '18:00'");

   if( !mysql_query("SELECT intertenant_routing FROM tenants LIMIT 1"))
         mysql_query("ALTER TABLE tenants add intertenant_routing varchar(100)");

   if(!mysql_query("SELECT operator_exten FROM t_vmusers LIMIT 1"))
         mysql_query("ALTER TABLE t_vmusers ADD operator_exten varchar(20) DEFAULT ''");

   if($_tbl_name == 't_sip_users' ){
       if(!mysql_query("SELECT dnd FROM t_user_options LIMIT 1")){
           mysql_query("ALTER TABLE t_user_options ADD dnd varchar(5) DEFAULT '0'");
       }    

       if(!mysql_query("SELECT rtcp_mux FROM t_user_options LIMIT 1")){
           mysql_query("ALTER TABLE t_user_options ADD rtcp_mux varchar(50) DEFAULT 'yes'");
       }    
   }



   if($_tbl_name == 't_queues'){
      if(!mysql_query("SELECT default_action FROM t_queues LIMIT 1")){
       mysql_query("ALTER TABLE t_queues add default_action varchar(100)");
       mysql_query("ALTER TABLE t_queues add default_action_data varchar(100)");
      } 
   }
 

  /*   
   // Versions Upgrade Code:   
  
   if(!mysql_fetch_assoc(mysql_query("SELECT 1 FROM mtree WHERE id = 501")) ){
     mysql_query("insert into mtree values(500,'Reports','',10, '/images/reports.png');");
     mysql_query("insert into mtree values(501,'Summary reports','summary_reports',500, '/images/summary_reports.png')");
     mysql_query("update mtree set parent_id = 500 where id =310;");
     mysql_query("update mtree set parent_id = 500 where id =300;");
   }

   if(!mysql_query("SELECT tag FROM t_inbound LIMIT 1")){
      mysql_query("ALTER TABLE t_inbound ADD tag varchar(200)");
   }

   if(!mysql_query("SELECT service_status FROM t_cdrs LIMIT 1")){
      mysql_query("ALTER TABLE t_cdrs ADD service_status varchar(200)");
   }
   if(!mysql_query("SELECT 1 FROM tenant_shiftreports")){
       mysql_query("CREATE TABLE `tenant_shiftreports` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `tenant_id` int(11) DEFAULT NULL,
	  `send_to_email` varchar(120) DEFAULT NULL,
	  `shift_start` time DEFAULT NULL,
	  `shift_end` time DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
	");
   }

   if( !mysql_query("SELECT pbx_item_id FROM tenants LIMIT 1"))
         mysql_query("ALTER TABLE tenants add pbx_item_id int DEFAULT 1");


   if($_tbl_name == 'admin_users'){
   	if(!mysql_query("SELECT gui_style FROM admin_users LIMIT 1"))
   	   mysql_query("ALTER TABLE admin_users ADD gui_style varchar(100) DEFAULT ''");

       if(!mysql_query("SELECT email FROM admin_users LIMIT 1"))
           mysql_query("ALTER TABLE admin_users ADD email varchar(150) DEFAULT ''");
    }
 
    if($_tbl_name == 'trunks'){
       if(!mysql_query("SELECT md5secret FROM trunks LIMIT 1"))
           mysql_query("ALTER TABLE trunks ADD md5secret varchar(150)");
    }

 
   if($_tbl_name == 't_sip_users' || $_tbl_name == 'tenants'){
       if(!mysql_query("SELECT encrypt_sip_secrets FROM tenants LIMIT 1"))
           mysql_query("ALTER TABLE tenants ADD encrypt_sip_secrets tinyint DEFAULT 0");
   }
 
  if($_tbl_name == 't_user_options'){
      if(!mysql_query("SELECT call_waiting FROM t_user_options LIMIT 1")){
          mysql_query("ALTER TABLE t_user_options ADD `call_waiting` tinyint default 1 after  `call_followme_ontimeout_var` ");
          mysql_query("UPDATE t_user_options SET call_waiting = 1 ");
      }    
  }
   
    if($_tbl_name == 't_sip_users'){
    	// Enryption &security staff: 

    	if(!mysql_query("SELECT encryption FROM t_sip_users LIMIT 1")){
           mysql_query("ALTER TABLE t_sip_users ADD `icesupport` enum('yes','no') DEFAULT 'yes'");
           mysql_query("ALTER TABLE t_sip_users ADD `encryption` varchar(10) DEFAULT 'no'");
           mysql_query("ALTER TABLE t_sip_users ADD `force_avp`  varchar(10) DEFAULT 'no'");
           mysql_query("ALTER TABLE t_sip_users ADD `dtlsenable` varchar(10) DEFAULT 'no'");
           mysql_query("ALTER TABLE t_sip_users ADD `dtlsfingerprint` varchar(10) DEFAULT 'sha-1'");
           mysql_query("ALTER TABLE t_sip_users ADD `dtlsverify` varchar(50) NOT NULL DEFAULT 'no'");
           mysql_query("ALTER TABLE t_sip_users ADD `dtlscertfile` varchar(255) DEFAULT '/etc/asterisk/keys/TLS.pem'");
           mysql_query("ALTER TABLE t_sip_users ADD `dtlscafile` varchar(255) DEFAULT '/etc/asterisk/keys/fullchain.pem'");
           mysql_query("ALTER TABLE t_sip_users ADD `dtlssetup` varchar(30) NOT NULL DEFAULT 'actpass'");
    	}    	
    }
    
 
   */ 
   //
   
   // Kostil stoit tut
   if ( $_tbl_name == 't_queue_members' || $_tbl_name == 't_pagegroup_members' || $_tbl_name == 't_shifts')
  foreach(array_keys($_row_data) as $key)
       if ( preg_match('/_selection_/',$key) || preg_match('/_recordComponents_/',$key) )
   	   unset( $_row_data[$key]);
   

 // We use 't_' prefix for tables with tenant data - make sure reference is set to filter tenant //
   if ( $_tenant_id && substr($_tbl_name,0,2) == 't_' ){
       $_row_data = array_merge( $_row_data,  array('tenant_id' => $_tenant_id ) ); 
    }	 
    
 // Data conversion on the fly
  if ( $_tbl_name == 'tenants' ){
     $_row_data['parkext_announce'] = json_encode( $_row_data['parkext_announce'] );
     $_row_data['intertenant_routing'] = json_encode( $_row_data['intertenant_routing'] );
     if(isset( $_row_data['ref_id'] ) )
       $_row_data['ref_id'] = preg_replace('/ /', '',$_row_data['ref_id']); 
     if( !isset($_row_data['id'])  )
         $_row_data['title'] = $_row_data['title'] ? $_row_data['title'] : $_row_data['ref_id'] ; 
     //$_row_data['paging_interval'] = $_row_data['paging_interval']  ? $_row_data['paging_interval'] : 30;
     //$_row_data['paging_retry_count'] = $_row_data['paging_retry_count']  ? $_row_data['paging_retry_count'] : 5;
     //$_row_data['general_error_message'] = $_row_data['general_error_message'] ? $_row_data['general_error_message'] : 'Sorry, we cannot complete your call';
     //$_row_data['general_invalid_message'] = $_row_data['general_invalid_message'] ? $_row_data['general_invalid_message'] : 'Sorry, that extension number is invalid.';
  }
  
  // Data conversion on the fly
  if ( $_tbl_name == 'admin_users' ){
     $_row_data['allowed_sections'] = json_encode( $_row_data['allowed_sections'] );
     unset($_row_data['status']);
     if( strlen($_row_data['last_login']) === 0 )  {
     	 unset($_row_data['last_login']);
     } 
  }
     
  if ( $_tbl_name == 'trunks'){
     $_row_data['inTenants'] = json_encode( $_row_data['inTenants'] );
     if(isset($_row_data['name']))
       $_row_data['name'] = preg_replace('/[^A-Za-z0-9_\.\-]/','',$_row_data['name']);
  }  
  if ( $_tbl_name == 't_ringgroup_lists' && isset($_row_data['extensions'])){
     $_row_data['extensions'] = json_encode( $_row_data['extensions'] );
  }
  
 if ( $_tbl_name == 't_conferences' && isset($_row_data['users'])){
     $_row_data['users'] = json_encode( $_row_data['users'] );
  }
  
  //if ( $_tbl_name == 't_campaigns' ) {
  	 //if ( isset($_row_data['lead_field_names']) )
    //   $_row_data['lead_field_names'] = json_encode( $_row_data['lead_field_names'] );
//  }
  
  // FollowMe Validator:
  if ( $_tbl_name == 't_user_options' || $_tbl_name == 't_user_followme' ){
  	
  	 if(isset($_row_data['call_followme_options']))
  	    $_row_data['call_followme_options'] = json_encode( $_row_data['call_followme_options'] );
  	    
  	 if ($_row_data['id'])
  	    $ext = mysql_fetch_assoc(mysql_query("SELECT extension FROM t_sip_users WHERE id = (SELECT t_sip_user_id FROM {$_tbl_name} WHERE id = {$_row_data['id']})"));
  	 if ($_row_data['t_sip_user_id'])
  	    $ext = mysql_fetch_assoc(mysql_query("SELECT extension FROM t_sip_users WHERE id = {$_row_data['t_sip_user_id']}"));

     $_row_data['name'] = "{$_SESSION['tenantref']}-{$ext['extension']}"; 
     if ( $_tbl_name == 't_user_options' )
      $_row_data['context'] = "internal-{$_SESSION['tenantref']}";
      
     if ( $_tbl_name == 't_user_followme' && !isset($_row_data['id']) && !isset($_row_data['ordinal']) ){
       $order = mysql_fetch_assoc(mysql_query("SELECT ifnull(max(ordinal),0) + 1  as next_ordinal FROM t_user_followme WHERE t_sip_user_id = {$_row_data['t_sip_user_id']}"));
       $_row_data['ordinal'] = $order['next_ordinal'] ;
     } 
       
    }
  
  
  

 // t_extensions validator: for feature codes used only
  if ( $_tbl_name == 't_extensions' || $_tbl_name == 'feature_codes' ){
     $_row_data['context'] = "internal-{$_SESSION['tenantref']}-features";
     $_row_data['priority'] = 1;
     //$_row_data['app'] = 'Gosub';
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
   
  // New MOH // 
     if ( array_key_exists( 'name', $_row_data ) && $_row_data['name'] != ''  ){
     	  $_row_data['directory'] = $_row_data['name'];
     }	 
     
     if ( array_key_exists( 'directory', $_row_data ) && $_row_data['mode'] != 'custom' && !(strpos($_row_data['directory'],$_SESSION['tenantref']) === 0) )          
         $_row_data['directory'] = $_row_data['directory'] ? $_SESSION['tenantref'].'-'.$_row_data['directory'] : '' ;
     
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
     
     if ( array_key_exists( 'network_media_url', $_row_data ) && $_row_data['network_media_url'] != '' ) {
     	   $mpg123_path = $config->ini['general']['mpg123'] ? $config->ini['general']['mpg123'] : '/usr/local/bin/mpg123';        	 
     	   $_row_data['application'] = $mpg123_path .' -q -s -m -r 8000 -f 8192 -b 0 ' . $_row_data['network_media_url'];
     	   $_row_data['directory'] = '';
     }      	 
   	 
   }    
    
 // Sip Users valuidator	 //
  if ( $_tbl_name == 't_sip_users' ){
  	  
  	 // DEfault value:
    $_row_data['parkinglot'] = "parkinglot-{$_SESSION['tenantref']}";  	 
  	   
  	 if ( isset( $_row_data['outbound_route'])){
  	 	if( $_row_data['outbound_route'] == -1)
  	 	 // Only Local 
        $_row_data['context'] = "internal-{$_SESSION['tenantref']}-local" ;
      elseif( $_row_data['outbound_route'] > 1)
       // Certain route only 
  	    $_row_data['context'] = "outbound-{$_SESSION['tenantref']}-{$_row_data['outbound_route']}" ;
      elseif( $_row_data['outbound_route'] == 0 )
       // Disabled
       $_row_data['context'] = "general-{$_SESSION['tenantref']}-error" ;
      else 
       // Default = 1, allowed all
  	    $_row_data['context'] = "internal-{$_SESSION['tenantref']}" ;
  	 }else{
  	 	//if new Row, outbound_route not set, - default context value - set all// 
  	 	if(!isset($_row_data['id']))
  	 	  $_row_data['context'] = "internal-{$_SESSION['tenantref']}" ;
  	 }   
  	    
  	    
  	   $t_subscr = mysql_fetch_assoc( mysql_query("SELECT id,enable_status_subscription FROM tenants WHERE id = {$_tenant_id}") );
  	  
     $_row_data['subscribecontext'] =  $t_subscr['enable_status_subscription'] ? 'internal-' .  $_SESSION['tenantref'] . '-BLF' : 'NULL';	   
  	  
  	 if( !array_key_exists( 'extension', $_row_data ) ) 
  	  if ( isset($_row_data['id']) ){  
   	 // Extract extension for validate Name field later - Updating Existing row MODE
  	      $_res = mysql_query("SELECT * FROM t_sip_users WHERE id = {$_row_data['id']} ");
         $_row = mysql_fetch_assoc($_res);
         $_row_data['extension'] = $_row_data['extension'] ? $_row_data['extension'] : $_row['extension'];
       //$_row_data['name'] =     $_row_data['name'] ? $_row_data['name'] : $_row['name'];
       //$_row_data['secret'] =   !is_null($_row_data['secret']) ? $_row_data['secret'] : $_row['secret'];
     }else{  
         // Generate Default Data for new extension
         $_res = mysql_query("SELECT max(convert(extension,decimal(12))) as extension FROM t_sip_users WHERE tenant_id = {$_SESSION['tenantid']}");
         $_row = mysql_fetch_assoc($_res);
         $_row_data['extension'] = is_null($_row['extension']) ? '101' : $_row['extension']+1;
     }
      
  	    
  	  if( ( array_key_exists( 'name', $_row_data ) && !$_row_data['name'] ) ||
   	   ( !isset($_row_data['id']) && !array_key_exists( 'name', $_row_data ) )  )  
        $_row_data['name'] = $_SESSION['tenantref'] . '-' . $_row_data['extension'];
    
     if ( array_key_exists( 'name', $_row_data ) && !(strpos($_row_data['name'],$_SESSION['tenantref']) === 0) )
        $_row_data['name'] = $_SESSION['tenantref'].'-'.$_row_data['name'] ;    	 
        
        
    	 
     if ( array_key_exists( 'secret', $_row_data ) &&  trim($_row_data['secret']) == '' ) {
       // We do not generate if md5 exists, 
       // md5 generated if tenants.encrypt_sip_secret = 1 by t_sip_users trigger //
        if( !$_row['md5secret'])
          $_row_data['secret'] = bin2hex(openssl_random_pseudo_bytes(4));
        
     }
   
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
     $out_cid   = isset($_row_data['outbound_callerid'] ) ? $_row_data['outbound_callerid']   :$_db_data['outbound_callerid'];
     $out_cname = isset($_row_data['outbound_callername'] ) ? $_row_data['outbound_callername'] :$_db_data['outbound_callername'];
     $int_cid   = isset($_row_data['internal_callerid'] ) ? $_row_data['internal_callerid']   :$_db_data['internal_callerid'];
     $int_cname = isset($_row_data['internal_callername'] ) ? $_row_data['internal_callername'] :$_db_data['internal_callername'];
     
     $vars = array();
     if ( $out_cid   ) $vars[] = "__SET_EXTOUT_CID={$out_cid}";
     if ( $out_cname ) $vars[] = "__SET_EXTOUT_CNAME={$out_cname}";
     if ( $int_cid   ) $vars[] = "__SET_EXTINT_CID={$int_cid}";
     if ( $int_cname ) $vars[] = "__SET_EXTINT_CNAME={$int_cname}";
     $vars[] = "tenant={$_SESSION['tenantref']}";
     $vars[] = "CDR(tenant_id)={$_tenant_id}";

     $_row_data['setvar'] = count($vars) ? implode(';',$vars) : '';

  /* 
     if(isset($_row_data['click2talk_enabled'])) {
       $val = ( $_row_data['click2talk_enabled'] == 1 ) ? 'yes' : 'no';
       $_row_data['avpf'] = $val;
       $_row_data['encryption'] = $val;
     }
   */  
     // enryption options enabled all WEBrtc related settings //
     if ( isset( $_row_data['encryption'] )) {
     	 $_row_data['dtlsenable'] = $_row_data['encryption'];     	 
       $_row_data['dtlsverify'] = 'no'; 
       $_row_data['force_avp'] = $_row_data['encryption'];
       $_row_data['avpf'] = $_row_data['encryption'];

     }
     
     if(isset($_row_data['crm_enabled']) || isset($_row_data['crm_username']) || isset($_row_data['crm_password']) ){       
       if( isset($_row_data['crm_username']) || isset($_row_data['crm_password']) ){
          $check = mysql_fetch_assoc( mysql_query("SELECT id,user,pass FROM admin_users WHERE sip_user_id = {$_row_data['id']} "));
          if($check){
            $u = isset($_row_data['crm_username']) && $_row_data['crm_username'] ?  $_row_data['crm_username'] : $check['user'];
            $p = isset($_row_data['crm_password']) && $_row_data['crm_password'] ?  $_row_data['crm_password'] : $check['pass'];
            mysql_query("UPDATE admin_users SET user='{$u}',
                                                pass='{$p}'
                                WHERE id = {$check['id']} ");
          }else{
            mysql_query("INSERT INTO admin_users(user,pass,role,sip_user_id,default_tenant_id)
                                VALUES('{$_row_data['crm_username']}',
                                       '{$_row_data['crm_password']}',
                                       5,
                                       {$_row_data['id']},
                                       {$_row_data['tenant_id']}
                                      ) ");
          }
       }else{
        // crm_enabled is set BUT  = false! , since no user/pass received! -  delete user if he is a PHONE role
         $r = mysql_query("DELETE FROM admin_users 
                                 WHERE sip_user_id = {$_row_data['id']} AND 
                                       default_tenant_id = {$_row_data['tenant_id']} AND 
                                       role = 5");
         $r = mysql_query("UPDATE admin_users SET  sip_user_id = 0 
                            WHERE sip_user_id = {$_row_data['id']} AND
                                  default_tenant_id = {$_row_data['tenant_id']} AND 
                                  role < 5");
       }

     }    
      // Those fields does no exists in t_sip_users, it just a computed fields from admin_users
        unset($_row_data['crm_enabled']);
        unset($_row_data['crm_username']);
        unset($_row_data['crm_password']);
        unset($_row_data['option_forwarding']);
        unset($_row_data['option_timeout']);



  }	 
  
  //var_dump($_row_data);
	 
  return $_row_data;  	 
}


?>
