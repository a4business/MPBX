<?php

// Enable on Deploy to fix installation errors://
 ini_set('display_errors', 'On');
 error_reporting(1);
//

include_once('include/config.php');
require_once __DIR__ . '/include/mailManager.php';
require_once __DIR__ . '/vendor/autoload.php';
use GeoIp2\Database\Reader;



//$str = json_encode(array_map('trim', array_merge( array_map('addslashes',$_POST), array_map('addslashes',$_GET) ) ) ); 
$config->log->putLog( 'WEB Request[' . $_SERVER['REMOTE_ADDR'] . ']  POST[' . json_encode(array_map('addslashes',$_POST )) .'] GET' . json_encode(array_map('addslashes',$_GET )) );

$expected_api = array('get_data','get_stats','to_email','report_name','username', 'password','logout','id', 'showTenants','switch_tenant_to','get_next_exten', 'reload_tenants','reload_extensions','run','clear_admin_log','extensions','dids_usage','drange','d','delScheduler','runScheduler','to_emails','rtype','output_format','shift_id','send_stats','blockIP', 'block_reason','get_c2t_code','type','mode','send_qactivity_emails','tenant_id', 'cleanCDRS');
$allowed_data_tables = array('sip_users','tenants');

 if(!isset($_SESSION) )
    session_start();



foreach ( array_merge($_POST,$_GET) as $key => $value) {
  if (!in_array($key, $expected_api)) {
    continue;
  } 
  ${$key} = mysql_escape_string( $value );  
}


if(isset($get_c2t_code)){
  $key = $get_c2t_code;
  $row = mysql_fetch_assoc(mysql_query("SELECT name,secret FROM t_sip_users WHERE substring(reverse(sha(name)),1,10) = '{$key}' AND click2talk_enabled = 1"));
  if($type=='htm'){
    echo "<script type='text/javascript' src='https://mpbx.a4business.com/assets/js/c2c-api-styled.js'></script>\n";        
    echo "<script type='text/javascript'>\n";
  }else{
    echo "   document.write(\"<script src='https://mpbx.a4business.com/assets/js/c2c-api.js' type='text/javascript'></script>\");\n\n";    
  }  
  echo "    c2c.from = '{$row['name']}';\n";
  echo "    c2c.pass = '{$row['secret']}';\n";
  echo "    c2c.to = '*600';\n";
  echo "    c2c.text = 'Call us &raquo;'\n";  
  echo "    c2c.cls = 'btn btn-large btn-success';\n";
  echo "    c2c.glass = true;\n";
  echo "    c2c.init();\n";
  if($type=='htm')
    echo "</script>\n";


  
}


///// THIS OPTION  blocks IP by GET request (clicked from Email boty sent to ADmin ) /////
if(isset($blockIP)){

  $reader = new Reader('include/GeoLite2-City/GeoLite2-City.mmdb');
  try{
    $by = $reader->city($_SERVER['REMOTE_ADDR']);
    $by_info =  $by->country->isoCode . '/' . $by->country->name . ', ' . $by->city->name .', ' . $by->mostSpecificSubdivision->name;
  }catch(Exception $e){
    $config->log->putLog( 'WARNNING:  IP:' . $_SERVER['REMOTE_ADDR'] .  'GeoIP Render Failed:' .  $e->getMessage() );
  }  

  $blocked = $reader->city($blockIP);
  $blocked_info = $blocked->country->isoCode . '/' . $blocked->country->name . ', ' . $blocked->city->name .', ' . $blocked->mostSpecificSubdivision->name;
   
  $ip_exists = mysql_fetch_assoc( mysql_query("SELECT * FROM blacklist WHERE ip = '{$blockIP}' LIMIT 1"));
  if($ip_exists){
     echo "IP:  $blockIP Already IN BANNED[BLOCKED:{$ip_exists['blocked']}] at '{$ip_exists['tstamp']}'!! HitCount: {$ip_exists['hit_count']} LastHitAt: {$ip_exists['last_hit']}";
  }else{
     $MSG = " ADDING $blockIP ( $blocked_info )  TO BLACKLIST[ BLOCK=1 ] ( by ADMIN request {$_SERVER['REMOTE_ADDR']}/$by_info )!" ;
     $res = mysql_query("INSERT INTO blacklist(ip, ip_info, description, block_web_access, block_sip_registration ) VALUES('{$blockIP}', '{$blocked_info}', '{$MSG}', 1, 1)") or die(mysql_error() );
      
     echo json_encode(array('success' => true,'message' => $MSG));
  }

  return;

}



  if (isset($_POST['change_passwd'])){
	 $UID = $config->getUID();
	 if ( !$UID ) { 
	   echo json_encode( array( 'success'=> false, 'error' => "User Not found! Login first!") );
      return;
	 }   
	 $P = mysql_real_escape_string( $_POST['change_passwd'] );
	 $r = mysql_query("UPDATE admin_users SET pass = '{$P}' WHERE id = {$UID}");
	 if (!$r) 
	    echo  json_encode( array( 'success'=> false, 'error' => "Failed to update password: "  . mysql_error() ) );
	 else   
	    echo  json_encode( array( 'success'=> true, 'error' => "Password is set!") );
	     
	 exit; 
  }


  if(isset($clear_admin_log)){
    $uid = (int)$clear_admin_log;
    if( $_SESSION['user']['role'] == 1 ){
       mysql_query("DELETE FROM admin_user_log WHERE user_id = $uid");
       echo json_encode( array( 'success'=> true, 'error' => "Logs cleared!") );
    }else
       echo json_encode( array( 'success'=> false, 'error' => "Failed to clear logs!") );
  }


  if (isset($_POST['logout'])) {
    $save_logo = $_SESSION['logo_image'] ;
     mysql_query("UPDATE admin_users SET last_login = date_sub(now(), INTERVAL 300 SECOND )  WHERE id = {$_SESSION['UID']}");
     unset($_SESSION['user']);
     unset($_SESSION['UID']);
     session_start();
     $_SESSION['logo_image'] = $save_logo ;
     echo json_encode( array( 'success'=> true, 'error' => "Logged out") );
     return;
  }



  $ip_blocked = mysql_fetch_assoc( mysql_query("SELECT * FROM blacklist WHERE ip = '{$_SERVER['REMOTE_ADDR']}' AND ip != ''" ) );
  if ( $ip_blocked ){

    // VERSION UPGRADE //
    if(!mysql_query("SELECT block_web_access FROM blacklist LIMIT 1")){
        mysql_query("ALTER TABLE blacklist ADD block_web_access integer DEFAULT 0");
    }

    $ip_blocked = mysql_fetch_assoc( mysql_query("SELECT * FROM blacklist WHERE ip = '{$_SERVER['REMOTE_ADDR']}' AND ip != ''" ) );

    mysql_query("UPDATE blacklist SET hit_count = ifnull(hit_count,0) + 1, last_hit = now() WHERE  ip = '{$_SERVER['REMOTE_ADDR']}'") ;
    if( isset($ip_blocked['block_web_access']) && $ip_blocked['block_web_access']  ){
	// Throu AWAY it: //
	    unset($_SESSION['user']);
	    unset($_SESSION['UID']);
	    $config->log->putLog( ' IP:' . $_SERVER['REMOTE_ADDR'] .  ' Access denied by BLACKLIST!' );
	    $location = $ip_blocked['redirect_to'] ? $ip_blocked['redirect_to'] : 'https://webkay.robinlinus.com/';
	    session_destroy();
	    header("Location: $location");
	    return;
	    exit;
    }
  
  }



if ( isset($username) && isset($password) ){

    // VERSION UPGRADE //
     if(!mysql_query("SELECT block_web_access FROM blacklist LIMIT 1")){
        mysql_query("ALTER TABLE blacklist ADD block_web_access integer");
     }



// Try to get remote Country by client IP addr FOR LOGED USERS 
  $reader = new Reader('include/GeoLite2-City/GeoLite2-City.mmdb');
  $blocked_country = false;
 try{

    $location = $reader->city($_SERVER['REMOTE_ADDR']);
    $allowed_countries = '/Canada|India|Ukraine|Turkey|Italy|Bahrain/';
    $IPINFO  = "  {$location->country->isoCode}/{$location->country->name},{$location->city->name},{$location->mostSpecificSubdivision->name}";
    $blocked_country = !( preg_match( $allowed_countries, $location->country->name ) ) ? true : false;

 }catch(Exception $e){
    $config->log->putLog( 'IP:' . $_SERVER['REMOTE_ADDR'] .  'GeoIP Render Failed:' .  $e->getMessage() );    
    $IPINFO='';
 }  


 // LOGIN Filter by country  and SQL Injection 
   if( $blocked_country || preg_match('/ or /',$username) || preg_match('/ or /',$password) ){
     $MSG = " AUTOBLOCK: {$_SERVER['REMOTE_ADDR']}  BY COUNTRY: [ {$location->country->name} ]  ( AUTO-Self-locked )! : while login from: {$username} " ;
     $config->log->putLog( $MSG );
     $res = mysql_query("INSERT INTO blacklist(ip, ip_info, description, block_web_access ) VALUES('{$_SERVER['REMOTE_ADDR']}','${IPINFO}','{$MSG}', 1 )") or die( mysql_error() );
     $ret = SendAlert( $config->ini['general']['superadmin_email'] ,
                 'newiplogin',
                 array('/##USER##/'      => $username . "  [uid:{$user['id']}]",
                       '/##IP_INFO##/'   => ' [::AUTO-BLOCKED-IP-BY-COUNTRY::]   ' . $IPINFO,
                       '/##IP##/'        => $_SERVER['REMOTE_ADDR'],
                       '/##USERAGENT##/' => $_SERVER['HTTP_USER_AGENT'],
                       '/##SERVER_IP##/' => file_get_contents('http://ifconfig.so')
                       )
                 );
      
      header("Location: https://webkay.robinlinus.com ");
      return;
   }
 
  $username = preg_replace( array('/ /','/"/',"/'/",'/`/') , '', $username);
  $password = preg_replace( array('/ /','/"/',"/'/",'/`/') , '', $password);
  $SQL = "SELECT admin_users.id as id, 
	                user,pass,role,email,
	                logo_image,
	                ref_id,
                  replace(replace(allowed_sections,'[',''),']','') as allowed,
	                default_tenant_id, 
	                tenants.title as tenant_name,
	                gui_style,
                  role
	             FROM admin_users
	              LEFT JOIN tenants on tenants.id = admin_users.default_tenant_id 
				WHERE user = '{$username}' AND pass = '{$password}' AND pass != '' AND user != '' ";
   //echo $SQL;							   
   $res = mysql_query($SQL) or die('Auth error: ' . mysql_error() );
   $user = mysql_fetch_assoc($res);							   


// Success LOGIN handling //
if ( isset($user['user']) ){     


     if( $user['role'] > 3 ){
	echo json_encode( array( 'success' => false, status=>'false','msg' => 'Permission denied: only amdin roles allowed to LOGIN to ADMIN GUI!' ) );
	return;
     }else{

         echo json_encode( array( 'success' => true, 'msg' => 'OK' ) );

         mysql_query("UPDATE admin_users SET last_login = now(),last_login_ip = '{$_SERVER['REMOTE_ADDR']}' WHERE id = {$user['id']}");
         $sections = ( $user['allowed'] &&  is_array( explode(',', $user['allowed']) ) ) ? explode(',', $user['allowed']) : array(1)  ;

	// Default page goes to first allowed, or 'extensions' by default'
         if( $sections[0] > 1 ){
            $first_section_row = mysql_fetch_assoc(mysql_query("SELECT action FROM mtree WHERE id = {$sections[0]}") );
            $first_section = $first_section_row['action'];
         }else
           $first_section = 'extensions' ;
      
         $_SESSION['UID'] = $user['id']; //$row['id'];
         $_SESSION['user'] = $user; //$row['id'];
         $_SESSION['logo_image'] = $user['logo_image']; //$row['id'];
         $_SESSION['USERNAME'] = $username;  //$row['username'];
         $_SESSION['tenantid'] = $user['default_tenant_id'];
         $_SESSION['tenantname'] = $user['tenant_name'];
         $_SESSION['tenantref'] = $user['ref_id']; 
         $_SESSION['default_section'] = $first_section; 

         
         $check_ip = mysql_fetch_assoc(mysql_query("SELECT count(*) as cnt FROM admin_user_log 
                                                  WHERE user_id = {$user['id']} AND 
                                                        method = 'LOGGED_IN' AND
                                                        from_ip ='{$_SERVER['REMOTE_ADDR']}'"));
         if( $check_ip['cnt'] == 0 ){

             $ret = SendAlert( $user['email'] ? $user['email'] : $config->ini['general']['superadmin_email'] ,
                     'newiplogin', 
                     array('/##USER##/' => $username . "  [uid:{$user['id']}]",
                           '/##IP_INFO##/' =>   $IPINFO,
                           '/##IP##/' =>       $_SERVER['REMOTE_ADDR'],
                           '/##USERAGENT##/' => $_SERVER['HTTP_USER_AGENT'],
                           '/##SERVER_IP##/' => file_get_contents('http://ifconfig.so')
                           )
                     );
          }

          mysql_query("INSERT INTO admin_user_log(user_id,user_agent,method,request_data,from_ip)
                       VALUES({$user['id']},'{$_SERVER['HTTP_USER_AGENT']}', 'LOGGED_IN', 'OK','{$_SERVER['REMOTE_ADDR']}')");  

          return;
      }    

   }else{

       // FAILED LOGIN ATTEMPT HANDLING ///
       echo json_encode( array( 'success' => false,
		            	'error' => "Failed to login from IP:{$_SERVER['REMOTE_ADDR']} as '{$username} ", 
			    	'msg' => $msg,
			    	'status'=>false )
		     	);

       //// TRACK FAILED LOGIN ATTEMPTS by USERNAME  ////
       $failed_user = mysql_fetch_assoc( mysql_query("SELECT * FROM admin_users WHERE user = '{$username}'"));
       $failed_user_id = $failed_user['id'] ? $failed_user['id'] : 0;
       if($failed_user_id){
          $interval = mysql_fetch_assoc(mysql_query("SELECT time_to_sec(timediff( now(),tstamp )) as since_last
                                           FROM admin_user_log 
                                           WHERE user_id = {$failed_user_id} AND  method = 'FAILED_LOGIN_ALERT'     
                                           ORDER BY tstamp  DESC
                                           LIMIT 1"));

          if( !isset($interval['since_last']) || $interval['since_last'] > 20  )
            $send = "_ALERT";
       }

   
    // Do not lock local users trying to remember pass by DEFAULT, ONLY REGISTER EVENT! //
    // 38.142.63 - etor
      $BLOCKED = !preg_match( '/^192\.168\./', $_SERVER['REMOTE_ADDR'] ) &&
		 !preg_match( '/^38\.142\.63/', $_SERVER['REMOTE_ADDR'] ) &&  
		 !preg_match( '/^38\.113\.171/', $_SERVER['REMOTE_ADDR'] ) &&
		 !preg_match( '/^10\./', $_SERVER['REMOTE_ADDR'] ) &&
		 !preg_match( '/^172\./', $_SERVER['REMOTE_ADDR'] ) ? 1 : 0 ;

      $descr = $_SERVER['REMOTE_ADDR'] . ' Blocked after failed Login as: ' . $username  ;
   
     if( !mysql_fetch_assoc(mysql_query("SELECT 1 FROM blacklist WHERE ip = '{$_SERVER['REMOTE_ADDR']}'  LIMIT 1"))){
      $res = mysql_query( "INSERT INTO blacklist(ip, ip_info, description, hit_count, last_hit, block_web_access )  VALUES( '{$_SERVER['REMOTE_ADDR']}', '${IPINFO}', '{$descr}', 1, now(), {$BLOCKED} )" ) or die(' FAIL to ADD: ' . mysql_error() );     
      }else{
	$res = mysql_query("UPDATE blacklist SET description = '{$descr}' WHERE  ip = '{$_SERVER['REMOTE_ADDR']}'")  or die(' FAIL to ADD: ' . mysql_error() );
      }

      $res = mysql_query("INSERT INTO admin_user_log(user_id,user_agent,method,request_data,from_ip) 
			   VALUES(0{$failed_user_id},
				  '{$_SERVER['HTTP_USER_AGENT']}',
				  'FAILED_LOGIN{$send}',
				  '{$username}:{$password}',
				  '{$_SERVER['REMOTE_ADDR']}')") or die('  FAIL to LOG: '. mysql_error() );
    

    // $res = mysql_query("INSERT INTO blacklist(ip, ip_info, description ) VALUES('{$_SERVER['REMOTE_ADDR']}','${IPINFO}','Blocked after failed Login!')") or die( mysql_error() );     
 
     mysql_query("INSERT INTO admin_user_log(user_id,user_agent,method,request_data,from_ip)
                   VALUES(0{$failed_user_id},'{$_SERVER['HTTP_USER_AGENT']}', 'FAILED_LOGIN{$send}', '{$username}:{$password}','{$_SERVER['REMOTE_ADDR']}')");
     
     if($send){
       $sip_info = mysql_fetch_assoc(mysql_query("SELECT concat(' <div>There is SIP registration:<br>',name,' [',useragent,'] from the same IP</div>') as txt FROM t_sip_users WHERE ipaddr = '{$_SERVER['REMOTE_ADDR']}' "));
     
      try{
	$sip_info = mysql_fetch_assoc(mysql_query("SELECT concat(' <div>There is SIP registration:<br>',name,' [',useragent,'] from the same IP</div>') as txt FROM t_sip_users WHERE ipaddr = '{$_SERVER['REMOTE_ADDR']}' "));
        $reader = new Reader('include/GeoLite2-City/GeoLite2-City.mmdb');  
        $location = $reader->city($_SERVER['REMOTE_ADDR']);
        $IPINFO  = "  {$location->country->isoCode}/{$location->country->name},{$location->city->name},{$location->mostSpecificSubdivision->name}";
      }catch(Exception $e){
        $config->log->putLog( 'IP:' . $_SERVER['REMOTE_ADDR'] .  'GeoIP Render Failed:' .  $e->getMessage() );
        $IPINFO='unknown';
      }    
      $ret = SendAlert( $failed_user['email'] ? $failed_user['email'] : $config->ini['general']['superadmin_email'] ,
                 'loginfail', 
                 array('/##USER##/' => $username . "  [uid:{$failed_user_id}]",
                       '/##PASS##/' => substr($password, 0,3) . ' ... ',
                       '/##IP_INFO##/' =>   $IPINFO,
                       '/##IP##/' =>        $_SERVER['REMOTE_ADDR'],
                       '/##USERAGENT##/' => $_SERVER['HTTP_USER_AGENT'],
                       '/##MORE_INFO##/' => $sip_info['txt'],
                       '/##SERVER_IP##/' => file_get_contents('http://ifconfig.so'),
                       '/##SINCE_LAST_TRY##/' => $interval['since_last'] ? $interval['since_last']  . "s since last try" : "<small style='color:gray;'>First time trying</small>" )
                 );
    }

    return;
  }

}



if( isset($cleanCDRS) ) {
  if(  $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '::1' ){
     echo json_encode( array( 'error' => true, 'message' => "Not Authorized to call this request, IP: {$_SERVER['REMOTE_ADDR']} is logged!" ) );
     exit; 
   }
   // If 0  - disable CDR clean for this Tenant //		
  if( mysql_query("SELECT archivate_cdrs_after FROM tenants WHERE ifnull(archivate_cdrs_after,0) > 0 LIMIT 1") ){
    mysql_query("CREATE TABLE IF NOT EXISTS t_cdrs_archive LIKE t_cdrs"); 
    $res = mysql_query("SELECT id, ifnull(archivate_cdrs_after,90) as archivate_cdrs_after, title FROM tenants WHERE  ifnull(archivate_cdrs_after,0) > 0");
    $total = 0 ;
    echo "[ " . date("F j, Y, g:i a") . " ] ============================ Start Archivation =============================\n";
    while($row = mysql_fetch_assoc($res)){
      $tenant_id = $row['id']; 
      $days = ( $cleanCDRS > 1 ) ? $cleanCDRS : $row['archivate_cdrs_after'];
      $start = microtime(true);
      // Take much time... //
      $rcount = mysql_query("SELECT count(*) as cnt FROM t_cdrs WHERE tenant_id = {$tenant_id} ") ;
      $r = mysql_query("UPDATE tenants set cdrs_total = 0{$rcount} WHERE id ={$tenant_id}");
      $count_total = mysql_fetch_assoc($rcount)['cnt'];
      $rcount =  mysql_query("SELECT count(*) as cnt FROM t_cdrs WHERE tenant_id = {$tenant_id} AND datediff(now(),calldate) > {$days} ") ;
      // how to optimize??? //
      $count_to_clean = mysql_fetch_assoc($rcount)['cnt'];
      if( ($count_total > 10000 && $count_to_clean < 50000 && $count_to_clean )){
          $r = mysql_query("INSERT INTO t_cdrs_archive SELECT * FROM t_cdrs WHERE tenant_id = {$tenant_id} AND datediff(now(),calldate) > {$days} ") ;
  	  $affected = mysql_affected_rows();
  	  $total = $total + $affected ;
          $time_elapsed_secs = microtime(true) - $start;
  	  $start = microtime(true);
          echo "[ " . date("F j, Y, g:i a") . " ] Tenant:[{$row['id']}] {$row['title']} -> Archived {$affected} CDRs older {$days} days[Exectime: {$time_elapsed_secs}s], Deleting {$affected} ... ";
          $r = mysql_query("DELETE FROM t_cdrs WHERE tenant_id = {$tenant_id} AND datediff(now(),calldate) > {$days} ");
	  $time_elapsed_secs = microtime(true) - $start;
	  echo " ... deleted " . mysql_affected_rows() . ' [ExecTime: ' . $time_elapsed_secs ."sec]\n";
      }else{
	if( $count > 0 )        
		echo "[ " . date("F j, Y, g:i a") . " ] WARNING-BIG AMOUNT: Tenant:[{$row['id']}] {$row['title']} has {$count} in Total ( ! 50000 > {$count} > 5000  ), skipp archivation \n";
	else
		echo "[ " . date("F j, Y, g:i a") . " ] No CDRs Archivation for Tenant {$row['title']}[{$tenant_id}] older {$days} days \n";	
      }
    }
   echo " ----------------- Total $total CDRs moved to t_cdrs_archive \n";
  }
 }


 //
// 
// Scheduler - is URL of report with options inside ( row[action_params] ), emails(appeneded to URL as to_emails ) , and time to run this report checked by this section
// of fucked jaxer code !! hate it! but have no time to REDU //
// RUN certain scheduler(with no check of date)  - OR WHEN  -1 : check all schedulers for scheduled RUN:
//    

      
    if(isset( $runScheduler )){
      
          if( (int)$runScheduler > 0 ) 
            $filter =" id=".(int)$runScheduler; 
          else
            $filter = " TIME(now()) > TIME(tstamp) AND ( ifnull(datediff(now(),last_sent),1) > 0 OR TIME(last_sent) < TIME(tstamp) ) "; // time to trigger and  last send more then day ago//
                 
          $res = mysql_query(" SELECT * FROM t_scheduler WHERE {$filter}") or die( 'MySQL error:' . mysql_error() );
          $was_sent = array();                    
          $WEB_ADDR = $config->ini['general']['pbx_web_address'] ? $config->ini['general']['pbx_web_address'] : 'https://localhost';
           if(mysql_affected_rows()){
                while($row = mysql_fetch_assoc($res)){
                    $emails = preg_replace('/\n/', ',' ,$row['emails'] );
		                $emails = preg_replace('/,\s/', ',' ,$row['emails'] );
                    $URL = $WEB_ADDR .'/'. $row['action_params'].'&send_qactivity_emails=1&to_emails='.$emails.'&tenant_id='.$row['tenant_id'];
                    $ch = curl_init( $URL );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt( $ch, CURLOPT_FAILONERROR, false);
                    curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.107 Safari/537.36');
                    $data = date("F j, Y, g:i a") . ' : ' . curl_exec($ch);    
                  echo 'CURL exec repo RESULT:'.$data . '\n';
                  if (curl_errno($ch)) 
                    echo 'CURL exec repo ERR:'.curl_error($ch);
                  else
                    array_push($was_sent, $row['id']);

                  curl_close($ch); 
                }

                if(count($was_sent) > 0 ) {                 
                  mysql_query("UPDATE t_scheduler SET last_sent = now() WHERE id in (" . implode(',',$was_sent) . ");");
                  echo date("F j, Y, g:i a") . ': Triggered Schedulers: [ ' .  implode(',',$was_sent) .' ] ';
                }
                  
            }else{
              echo date("F j, Y, g:i a") . " No Scheduled tasks!!! Exit..\n";
            }   

    }



// SUmmary REports
if( isset($extensions) ) {


  $_tmp = tmpfile();
  $_tmp = stream_get_meta_data($_tmp);
  $tmp = $_tmp['uri'];
  $output_format = $output_format ? $output_format : 'html';

   if(!isset($tenant_id))
    $tenant_id = isset($_SESSION['tenantid'])  ? $_SESSION['tenantid'] : $tenant_id ;

    $selected =  ( $extensions == 'all' ) ? 'all' :  "'SIP/" . implode("','SIP/", explode(",", $extensions )) . "'";


    list($from_date,$to_date) = explode("to",$drange);  
    if( $from_date && $to_date ){
      $range = "  date(calldate) between date('{$from_date}') AND date('{$to_date}') ";
      $day_str = $from_date . '_' . $to_date;
      $repo_range = " [ {$from_date} : {$to_date} ]";
    }else{
      $range = " date(calldate) = date(now()) ";
      $day_str = date("Y-m-d");
      $repo_range = "  '$day_str' ";
    }

    $e='';
    foreach(explode(',',$extensions) as $ex_dev)
     $e .= explode('-',$ex_dev)[1] . ', ';
    $report_filter = "<small>Selected Ext:&nbsp; " . rtrim(substr($e,0,100),', ') . ((strlen($e)>100)?'...':'') . '</small>';
     



// Inbound / Outbound calls //
 if( $rtype == 'in' || $rtype == 'out' ){
         $offset = ( $tenant_id == 213 ) ? 3 : 2;
         $field_name = ( $rtype == 'in' ) ? 'dstchannel' : 'channel';   
         $direction = ( $rtype == 'in' ) ? 'Incoming' : 'Outgoing';
         $resource_name = ( $rtype == 'in' ) ? '' : 'Dialed to';
         $t=0;

         $res = mysql_query(" SELECT 
                 calldate,
                 substring_index( {$field_name},'-', {$offset})  as sip_name,
                 substring_index( substring_index({$field_name},'-', {$offset}) , '-', -1 ) as extension,
                 CASE WHEN LOCATE('@',channel) > 0 THEN  substring_index(channel,'@', 1) ELSE substring_index(channel,'-', {$offset}) END  as source_channel,
                 clid as CallerID, 
                 dst,
                 (billsec + duration) as billsec,
                 duration,
                 disposition 
               
                 FROM t_cdrs
                  WHERE 
                        tenant_id = {$tenant_id}  AND 
                        {$range} AND                        
      		              dcontext not like 'internal-%-local' AND
                        ((  disposition = 'ANSWERED' AND duration > 0 ) OR disposition != 'ANSWERED' ) AND
                        substring_index({$field_name},'-', {$offset}) IN ( {$selected} )
                       
                 "
                );
       

            file_put_contents($tmp, "<h3 style='display:inline'>{$direction} calls {$repo_range}</h3><div>{$report_filter}</div> \n" . 
                                    "<table class='table .table-striped table-responsive repo_results ' style='border: none' >\n
                 <tr style='background-color:#DFDFDF;
        border-top:1px solid white;
        border-left:1px solid silver;
        border-bottom:1px solid gray;
        border-right:1px solid gray;'><th>Date</th><th>Exten# </th> <th nowrap>{$direction} CLID</th> <th nowrap>{$resource_name}</th> <th>Duration</th><th style='width:auto'>Status</th></tr>\n",
            FILE_APPEND | LOCK_EX);;

          while ( $row = mysql_fetch_assoc($res) ) {  
              $t_billsec +=  $row['billsec'];
              $t++;
              $t_ans += ( $row['disposition'] == 'ANSWERED' ) ? 1 : 0;
              $style=($row['disposition'] == 'ANSWERED') ? "style='color:green;white-space:nowrap'" : "style='color:#FF4400;white-space:nowrap'";
              $row['resource_name'] = ( $rtype == 'in' ) ? '' : $row['dst'];
             

              file_put_contents($tmp, "<tr style='border:1px solid silver;border-right:1px solid gray;'><td nowrap>{$row['calldate']}</td><td title='{$row['sip_exten']}' nowrap> {$row['extension']} </td><td nowrap> {$row['CallerID']} </td><td nowrap> {$row['resource_name']} </td><td nowrap>{$row['billsec']}s </td><td nowrap {$style} ><b>{$row['disposition']}</b></td></tr>\n", FILE_APPEND | LOCK_EX);

           }
           // Footer summary raw:
           $p = round( ($t_ans * 100 ) / $t );
           file_put_contents($tmp,  "<tr style='background-color:#DFDFDF;
        border-top:1px solid white;
        border-left:1px solid silver;
        border-bottom:1px solid gray;
        border-right:1px solid gray;'><th nowrap colspan=3 ><b>Total Calls: {$t}</b>&nbsp;&nbsp;&nbsp;<span style='float:right;'>Answered calls:<b>{$t_ans} ( {$p}% )</b>&nbsp;&nbsp;&nbsp;&nbsp;</span></th><th nowrap></th><th>{$t_billsec}s</th><td></td></tr>\n", FILE_APPEND | LOCK_EX);
           file_put_contents($tmp,  "</table>\n", FILE_APPEND | LOCK_EX);

         if(isset($send_qactivity_emails))
              $output_format = 'pdf';

         switch ($output_format){
           case 'csv':
               header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
               header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
               //header("Last-Modified: {$now} GMT");
           
              // force download
              header("Content-Type: application/force-download");
              header("Content-Type: application/octet-stream");
              header("Content-Type: application/download");
              header("Content-Disposition: attachment;filename={$rtype}_queue_{$day_str}.csv");
              header("Content-Transfer-Encoding: binary");

               echo "# DATE,EXTEN#,{$direction} CLID, {$resource_name},DURATION,STATUS\n\r";
               mysql_data_seek($res, 0);
                while ( $row = mysql_fetch_assoc($res) ) 
                   echo "{$row['calldate']},{$row['extension']},{$row['CallerID']},{$row['resource_name']},{$row['billsec']},{$row['disposition']}\n\r";
               echo " Total Calls: {$t}, ANSWERED:{$t_ans} ( {$p}% ), TOTAL DURATION: {$t_billsec}\n\r";   

               break;
           case 'html':
           
             echo file_get_contents($tmp);
             echo "<script type='text/javascript'> \n";
             echo " \$('#export_panel').css('visibility', 'visible');\n"; 
             echo " \$('#export_panel button').attr('report_type','{$rtype}') ;\n";       
             echo "</script>";
             break;

           case 'pdf':
            require_once __DIR__ . '/vendor/autoload.php';
            $PDFHeader = " {$_SESSION['tenantname']} | Extensions {$rtype}Bound Report for: {$day_str} | {PAGENO}";
            $PDFFooter = "<small>Generated at [ ".date("Y-m-d H:m") ." ], by CloudPBX server </small>";
            $mpdf = new mPDF();
            $mpdf->SetHeader($PDFHeader);
            $mpdf->WriteHTML(   file_get_contents($tmp) );      
            $mpdf->SetFooter($PDFFooter);
            ob_clean();
              if(isset($send_qactivity_emails)){
                 $PDFfile  =  '/tmp/' . $rtype . 'Bound_calls_'. $day_str . '.pdf';
                //$to_email = $to_emails?$to_emails:'p.deol@etornetworks.com hayer@armour-insurance.com';
                 $send_to = $to_emails?$to_emails:'voip.linux.expert@gmail.com failed@a4business.com';
                 
                 $mpdf->Output( $PDFfile, 'F');                               
                 if(file_exists($PDFfile) ){                                         
                    if ( sendCallStats( $send_to , $PDFfile, "::: Autoatic {$rtype}Bound Calls  Report :::" ) == 'OK' )
                      echo json_encode( array( 'success'=> true, 'error' => "The {$rtype} report has been SENT to {$send_to}  ") );
                 }else{
                   echo json_encode( array( 'success'=> false, 'error' => "Failed to generate {$rtype}Bound repo {$PDFfile} for:{$send_to}" ) );
                 }  
              }else{
                $mpdf->Output( $rtype . '_bound_calls_' . $day_str . '.pdf', 'D');
              }  
             break;
           } 
 }
 

    if($rtype == 'inbound_summary'){
      $res = mysql_query(" SELECT 
                 substring_index(dstchannel,'-', 2)  as exten,
                 SUM( CASE WHEN disposition != 'ANSWERED' THEN 1 ELSE 0 END ) as T_NOANSWER,
                 SUM( CASE WHEN disposition = 'ANSWERED' THEN 1 ELSE 0 END ) as T_ANSWERED,
                 count(*) as TOTAL
             FROM t_cdrs
              WHERE tenant_id = {$tenant_id}  AND 
              {$range} AND 
              substring_index(dstchannel,'-', 2) IN ( {$selected} ) GROUP BY 1"
            ) or die('MySQL error:' . mysql_error() );


      $out = '<style type="text/css"> .text-primary{ color:#188ae2 !important;} .text-danger{ color: #ff5b5b !important; } .cdr_row{ border:1px solid silver;border-right:1px solid gray; border-right:1px solid gray; background-color:white !important;} .cdr_row td{border-right:1px solid silver;padding:0 6px}  </style>';
      $out .=  "<h3 style='display:inline;white-space:nowrap;'>Exten Inbound calls </h3><div>{$repo_range}</div>";
      $out .= "<table  style='background-color:#DFDFDF;
        border-top:1px solid silver;
        border-left:1px solid silver;
        border-bottom:1px solid gray;
        border-right:1px solid gray;' >\n
                 <tr class=cdr_head > ";
      $out .=  "<th>Extension# </th> <th>Total</th> <th>Answered</th> <th>Missed</th><th >Missed %</th></tr>\n";
       while ( $row = mysql_fetch_assoc($res) ) {
         $missed =  ( $row['TOTAL'] > 0 ) ? round( (100 * $row['T_NOANSWER'] )/$row['TOTAL'], 0) :0;
         $out .= "<tr class=cdr_row><td nowrap> {$row['exten']} </td><td> {$row['TOTAL']} </td><td> {$row['T_ANSWERED']} </td><td>{$row['T_NOANSWER']} </td><td><b>{$missed}%</b></td></tr>\n";
       }
       $out .=  "</table>\n";

 
    $res = mysql_query(" SELECT 
           dids.DID  as exten,
           description,
           SUM( CASE WHEN disposition != 'ANSWERED' THEN 1 ELSE 0 END ) as T_NOANSWER,
           SUM( CASE WHEN disposition = 'ANSWERED' THEN 1 ELSE 0 END ) as T_ANSWERED,
           count(*) as TOTAL
           FROM t_cdrs , dids
            WHERE t_cdrs.tenant_id = {$tenant_id}  AND 
                  ( dids.DID = dst OR substring(dids.DID,3) =  dst ) AND
                 {$range}
            GROUP BY 1,2"
          ) or die('MySQL error:' . mysql_error() );

    $out .=  "<br><h3 style='display:inline'>Inbound Numbers usage </h3><div>{$repo_range}</div>\n";
    $out .=  "<table style='width:100%;background-color:#DFDFDF;
        border-top:1px solid silver;
        border-left:1px solid silver;
        border-bottom:1px solid gray;
        border-right:1px solid gray;'  >";
    $out .=  "<tr  style='background-color:silver;'><th>DID# </th> <th>Calls</th> <th>Answered</th> <th>Missed</th><th>Missed %</th></tr>\n";
     while ( $row = mysql_fetch_assoc($res) ) {
       $missed =  ( $row['TOTAL'] > 0 ) ? round( (100 * $row['T_NOANSWER'] )/$row['TOTAL'], 0) :0;
       $out .= "<tr class=cdr_row><td> {$row['exten']} </td><td> {$row['TOTAL']} </td><td> {$row['T_ANSWERED']} </td><td>{$row['T_NOANSWER']} </td><td ><b>{$missed}%</b></td></tr>\n";
     }
    $out .=  "</table>\n";

 
  
      switch ($output_format){
        case 'html':
           echo $out;
           echo "<script type='text/javascript'> \n";
           echo " \$('#export_panel').css('visibility', 'visible');\n"; 
           echo " \$('#export_panel button').attr('report_type','{$rtype}') ;\n";       
           echo "</script>";  
           break;

        case 'pdf':      
            require_once __DIR__ . '/vendor/autoload.php';
            $PDFHeader = " {$_SESSION['tenantname']} | Extensions {$rtype} Report for: {$day_str} | {PAGENO}";
            $PDFFooter = "<small>Generated at [ ".date("Y-m-d H:m") ." ], by CloudPBX server </small>";
            $mpdf = new mPDF();
            $mpdf->SetHeader($PDFHeader);
            $mpdf->WriteHTML( $out );      
            $mpdf->SetFooter($PDFFooter);
            ob_clean();            
            $send_to = $to_emails?$to_emails:'voip.linux.expert@gmail.com failed@a4business.com';
            if(isset($send_qactivity_emails)){
              $PDFfile  =  '/tmp/automatic_' . $rtype . '_calls_'. $day_str . '.pdf';                
              $mpdf->Output( $PDFfile, 'F');        
              if(file_exists($PDFfile) ){                   
                if ( sendCallStats( $send_to , $PDFfile, ":: Auto {$rtype} Calls Report ::" ) == 'OK' )
                 echo json_encode( array( 'success'=> true, 'error' => "{$rtype} report has been SENT to {$send_to}  ") );
                else
                 echo json_encode( array( 'success'=> false, 'error' => "Failed to generate {$rtype} repo {$PDFfile} for:{$send_to}" ) );
              }   
                
            }else{                 
               $mpdf->Output( 'Inbound_Summary_report.pdf', 'D');
            }
         
       }  

    }


    if($rtype == 'queue_activity' ){

	    $range = preg_replace('/calldate/','ts',$range);
      $members = ( $selected == 'all') ? '' : " sip_name  IN ( {$selected} ) AND";

     // EMAIL ACTIVITY :::: SENDING
     // IT RUN BY CRON:  Generate stats for the queues with Email set,  and SEND it in PDF  for all queues in  alltenants
      if(isset($send_qactivity_emails)){

        $emails_res = mysql_query("SELECT stats_email, tenant_id, title FROM t_queues , tenants
                                   WHERE t_queues.tenant_id = tenants.id AND
                                         ifnull(stats_email,'') != '' 
                                  GROUP by stats_email ,tenant_id, title");

        while ( $row = mysql_fetch_assoc($emails_res) ) {
                 $to_email  = $row['stats_email'];
                 $queues_res = mysql_query(" SELECT * ,
                             sec_to_time(ifnull(session_time,0)) as session_time
                             FROM t_queues,t_queue_members_log,t_sip_users
                              WHERE t_queues.stats_email = '{$row['stats_email']}' AND 
                                    t_queues.stats_email  is not NULL AND
                                    t_queues.tenant_id = {$row['tenant_id']} AND
                                    t_queues.name = t_queue_members_log.queue_name AND
                                    t_queue_members_log.tenant_id = t_queues.tenant_id AND
                                    t_queue_members_log.tenant_id = t_sip_users.tenant_id  AND
                                    t_queue_members_log.sip_name = concat('SIP/',t_sip_users.name) AND 
                                    event_type = 'QLogout' AND        
                                    {$members}
                                    {$range}
                              ORDER BY sip_name, queue_name " );
                   $has_row = false;
                   file_put_contents($tmp,"<h3 style='display:inline'>Queue Activity summary {$row['title']} </h3><div>{$repo_range},  sent to {$row['stats_email']} </div>\nAgents: {$extensions}  <table class='table table-striped table-responsive' >\n  <tr  style='background-color:silver;'><th>User </th> <th>Queue</th> <th>Login</th><th>Logout</th><th>Total Time</th></tr>\n", FILE_APPEND | LOCK_EX );
                   while ( $report_row = mysql_fetch_assoc($queues_res) ) {
                         $has_row = true;
                         $login = preg_match('/\[(.*)\]/',$report_row['event_details'],$m) ? $m[1] : '';
                         file_put_contents($tmp, "<tr><td nowrap> {$report_row['extension']}  {$report_row['first_name']} {$report_row['last_name']}</td><td nowrap> {$report_row['queue_name']} </td><td nowrap>{$login}&nbsp;&nbsp;</td><td nowrap>{$report_row['ts']} </td>  <td><b>{$report_row['session_time']}s</b></td></tr>\r\n", FILE_APPEND | LOCK_EX);
                   }
                   file_put_contents($tmp, "</table>\n", FILE_APPEND | LOCK_EX);


                 // Summary FOR QUEUES with SELECTED EMAIL set as report
                 // with earlest and oldest event and time duration between 
                  if($has_row){
                   $res_summary = mysql_query("SELECT t_sip_users.first_name,t_sip_users.last_name,t_sip_users.extension,
                                                   min(date_format(substring_index(substring_index(event_details,'[',-1),']',1), GET_FORMAT(DATETIME,'ISO'))) as qstart,
                                                   max(ts) as qend,
                                                   sec_to_time(sum(Ifnull(session_time, 0))) AS session_time ,
                                                   sec_to_time( TIMESTAMPDIFF(SECOND,min(timestamp(substring_index(substring_index(event_details,'[',-1),']',1))), max(ts) ) ) as daywork_time 
                                           FROM t_queues,t_queue_members_log,t_sip_users
                                            WHERE t_queues.stats_email = '{$row['stats_email']}' AND 
                                                  t_queues.stats_email  is not NULL AND
                                                  t_queues.tenant_id = {$row['tenant_id']} AND
                                                  t_queues.name = t_queue_members_log.queue_name AND
                                                  t_queue_members_log.tenant_id = t_queues.tenant_id AND
                                                  t_queue_members_log.tenant_id = t_sip_users.tenant_id  AND
                                                  t_queue_members_log.sip_name = concat('SIP/',t_sip_users.name) AND 
                                                  event_type = 'QLogout' AND        
                                                  {$members}
                                                  {$range} 
                                           GROUP BY t_sip_users.name");

                   file_put_contents($tmp,"<br><h4 style='display:inline;'>Agents time summary  </h3><div>{$repo_range}</div>\nAgents: {$extensions}  <table class='table table-striped table-responsive' >\n  <tr  style='background-color:silver;'><th>User </th> <th>Login</th> <th>Logout</th><th>at work</th><th>In Queues</th></tr>\n", FILE_APPEND | LOCK_EX );

                   while ( $row = mysql_fetch_assoc($res_summary)){                    
                         file_put_contents($tmp, "<tr><td nowrap><b>{$row['extension']} {$row['first_name']} {$row['last_name']}</b></td><td nowrap> {$row['qstart']} </td><td nowrap>{$row['qend']}&nbsp;&nbsp;</td><td nowrap>{$row['daywork_time']} </td><td><b>{$row['session_time']}s</b></td></tr>\r\n", FILE_APPEND | LOCK_EX);
                   }
                   file_put_contents($tmp, "</table>\n", FILE_APPEND | LOCK_EX);
                  } 


                    require_once __DIR__ . '/vendor/autoload.php';                    
                    $mpdf = new mPDF();
                    $mpdf->SetHeader(" Member Activity Report for: {$day_str} | {PAGENO}" );
                    $mpdf->WriteHTML(   file_get_contents($tmp) );      
                    $mpdf->SetFooter( "<small>Generated at [ ".date("Y-m-d H:m") ." ], by CloudPBX server </small>" );
                    ob_clean();
                    $PDFfile  =  '/tmp/' . $rtype . '_'.$report_name.'_' . $day_str . '.pdf';
                    $mpdf->Output( $PDFfile, 'F');
                    if(file_exists($PDFfile) ){                                         
                     if ( sendCallStats($to_email , $PDFfile, $report_name . $shift_title_txt, "Queue Agents Activity ::: {$title}" ) == 'OK' )
                       echo json_encode( array( 'success'=> true, 'error' => "Report has been SENT to {$to_email}  ") );                           
                    }else{
                      echo json_encode( array( 'success'=> false, 'error' => "Failed to generate {$PDFfile} for:{$to_email}" ) );
                    }  
             
           
             
             } //Of while
        
      }else{
                  // 1. Raw table with  events 
              	    $res = mysql_query(" SELECT * ,
                      	   sec_to_time(ifnull(session_time,0)) as session_time
              	           FROM t_queue_members_log,t_sip_users
              	            WHERE t_queue_members_log.tenant_id = {$tenant_id} AND
              			              t_queue_members_log.tenant_id = t_sip_users.tenant_id  AND
              			              t_queue_members_log.sip_name = concat('SIP/',t_sip_users.name) AND 
              			              event_type = 'QLogout' AND 			  
                      	          {$members}
                              	  {$range}
              	            ORDER BY sip_name, queue_name "
                        ) ;//or die( 'MySQL error:' . mysql_error() );
                   
                   $has_row = false;
                   $extensions_cut = (  strlen($extensions) > 100 ) ? substr($extensions, 0, 80 ).'. . .' : $extensions;
                   file_put_contents($tmp,"<h3 style='display:inline'>Queue Activity summary </h3><div>{$repo_range}</div>\nAgents: {$extensions_cut}  <table class='table table-striped table-responsive' >\n  <tr  style='background-color:silver;'><th>User </th> <th>Queue</th> <th>Login</th><th>Logout</th><th>Total Time</th></tr>\n", FILE_APPEND | LOCK_EX );
                   while ( $row = mysql_fetch_assoc($res)){
                         $has_row = true;
                         $login = preg_match('/\[(.*)\]/',$row['event_details'],$m) ? $m[1] : '';
                         file_put_contents($tmp, "<tr><td nowrap> {$row['extension']} {$row['first_name']} {$row['last_name']}</td><td nowrap> {$row['queue_name']} </td><td nowrap>{$login}&nbsp;&nbsp;</td><td nowrap>{$row['ts']} </td>  <td><b>{$row['session_time']}s</b></td></tr>\r\n", FILE_APPEND | LOCK_EX);
                   }
                   file_put_contents($tmp, "</table>\n", FILE_APPEND | LOCK_EX);


                 //2. Summary with earlest and oldest event and time duration between 
                   $res = mysql_query("SELECT t_sip_users.first_name,t_sip_users.last_name,t_sip_users.extension,
                           min(date_format(substring_index(substring_index(event_details,'[',-1),']',1), GET_FORMAT(DATETIME,'ISO'))) as qstart,
                           max(ts) as qend,
                           sec_to_time(sum(Ifnull(session_time, 0))) AS session_time ,
                           sec_to_time( TIMESTAMPDIFF(SECOND,min(timestamp(substring_index(substring_index(event_details,'[',-1),']',1))), max(ts) ) ) as daywork_time 
                           FROM t_queue_members_log,t_sip_users
                            WHERE t_queue_members_log.tenant_id = {$tenant_id} AND
                                  t_queue_members_log.tenant_id = t_sip_users.tenant_id  AND
                                  t_queue_members_log.sip_name = concat('SIP/',t_sip_users.name) AND 
                                  event_type = 'QLogout' AND        
                                  {$members}
                                  {$range} 
                           GROUP BY t_sip_users.name");

                   file_put_contents($tmp,"<br><h4 style='display:inline;'>Agents time summary  </h3><div>{$repo_range}</div>\nAgents: {$extensions_cut}  <table class='table table-striped table-responsive' >\n  <tr  style='background-color:silver;'><th>User </th> <th>Login</th> <th>Logout</th><th>at work</th><th>In Queues</th></tr>\n", FILE_APPEND | LOCK_EX );
                   while ( $row = mysql_fetch_assoc($res)){
                         $has_row = true;                         
                         file_put_contents($tmp, "<tr><td nowrap><b>{$row['extension']} {$row['first_name']} {$row['last_name']}</b></td><td nowrap> {$row['qstart']} </td><td nowrap>{$row['qend']}&nbsp;&nbsp;</td><td nowrap>{$row['daywork_time']} </td><td><b>{$row['session_time']}s</b></td></tr>\r\n", FILE_APPEND | LOCK_EX);
                   }
                   file_put_contents($tmp, "</table>\n", FILE_APPEND | LOCK_EX);



                    switch ($output_format){
                         case 'csv':
                             header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
                             header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                             //header("Last-Modified: {$now} GMT");
                     
                            // force download
                            header("Content-Type: application/force-download");
                            header("Content-Type: application/octet-stream");
                            header("Content-Type: application/download");
                            header("Content-Disposition: attachment;filename={$rtype}_bound_calls_{$day_str}.csv");
                            header("Content-Transfer-Encoding: binary");
                            echo "#   User , Queue Name, Login, Logout, Session Time\n\r";
                            mysql_data_seek($res, 0);
                            while ( $row = mysql_fetch_assoc($res) ) {
                               $row['login'] = preg_match('/\[(.*)\]/',$row['event_details'],$m) ? $m[1] : '';
                               echo " {$row['first_name']} {$row['last_name']},  {$row['queue_name']}, {$row['login']}, {$row['ts']}, {$row['session_time']}s\r\n";
                             }  
                             break;

                         case 'html':
                           if($has_row)
                             echo file_get_contents($tmp);
                           else
                             echo " <h4 style='white-space:nowrap;'>No Records for selected data</h4>"; 
                           echo "<script type='text/javascript'> \n";
                           echo " \$('#export_panel').css('visibility', 'visible');\n"; 
                           echo " \$('#export_panel button').attr('report_type','{$rtype}') ;\n";       
                           echo "</script>";
                           break;

                         case 'pdf':
                            require_once __DIR__ . '/vendor/autoload.php';                            
                            $mpdf = new mPDF();
                            $mpdf->SetHeader( " {$_SESSION['tenantname']} | Extensions {$rtype}Bound Report for: {$day_str} | {PAGENO}" );
                            $mpdf->WriteHTML(   file_get_contents($tmp) );      
                            $mpdf->SetFooter(" <small>Generated at [ ".date("Y-m-d H:m") ." ], by CloudPBX server </small>" );
                            ob_clean();
                            $mpdf->Output( $rtype . '_bound_calls_' . $day_str . '.pdf', 'D');
                           break;
                    } 
            }            
       }



    if( $rtype == 'hr_shabash' ){
      // THis action generate HR_LOGOFF of all members if given time reached //
      // Executed by CROND
       if( !isset($tenant_id) )
         $tenant_id = 69;

     // Get late workers by CHECKING agent latest EVENT type(logon) a //
      $MSG = '';
      $res = mysql_query(" SELECT sip_name  as late_worker,
                                  event_type as last_event,
                        				  shabash,
                        				  now() as nowTime,
                        				  ref_id
                        			    FROM t_queue_members_log, tenants
                        			   WHERE datediff(ts,now())  = 0 AND
                        				 t_queue_members_log.tenant_id = tenants.id AND
                        				 tenants.id = {$tenant_id} AND 
                        				 HOUR(now())  >=  HOUR(shabash) 
                        			  ORDER BY ts DESC
                        			  LIMIT 1");

         while ( $row = mysql_fetch_assoc($res)){
	        if( $row['last_event'] == 'hrlogon' ) {
          		 $MSG .= "  OverTimeD worker:[  " . $row['late_worker'] . "  ] STILL logged IN  while offtime!!, FinishHOUR[ {$row['shabash']} ], now: [ {$row['nowTime']} ],DO  KICK-OFF!\n";
            		 mysql_query("call set_user_option ('{$row['ref_id']}' , '{$row['late_worker']}', 'hrlogoff', 0 );");
             }
         }
 
         echo $MSG ? $MSG : " No Overtime workers for tenant: {$tenant_id} !\n";				
    }



   
    if( $rtype == 'hr_activity' ){

                  $range = preg_replace('/calldate/','ts',$range);
                  $members = ( $selected == 'all') ? '' : " concat('SIP/',sip_name)  IN ( {$selected} ) AND";

                 // EMAIL ACTIVITY :::: SENDING
                 // IT RUN BY CRON:  Generate stats for the queues with Email set,  and SEND it in PDF  for all queues in  alltenants
                // TODO //
                  
                if( !isset($tenant_id) )
                     $tenant_id = 69; 

                if(isset($send_qactivity_emails))
                     $output_format = 'pdf';

                  // 1. Raw table with  events 
                    $res = mysql_query(" SELECT * ,
                           concat(ifnull(t_sip_users.first_name,''),' ',ifnull(t_sip_users.last_name,''),' ',t_sip_users.name ) as worker,
                           sec_to_time(ifnull(session_time,0)) as session_time,
			                     sec_to_time(ifnull(break_time,0)) as break_time
                             FROM t_queue_members_log,t_sip_users,tenants
                            WHERE t_queue_members_log.tenant_id = tenants.id AND 
                                  t_queue_members_log.tenant_id = {$tenant_id} AND
                                  t_queue_members_log.tenant_id = t_sip_users.tenant_id  AND
                                  t_queue_members_log.sip_name = t_sip_users.name AND 
                                  (event_type = 'hrlogoff' OR event_type = 'hrlogon' ) AND        
                                  {$members}
                                  {$range}
                            ORDER BY sip_name, queue_name "
                        ) ;//or die( 'MySQL error:' . mysql_error() );
                   
                   file_put_contents($tmp, '<style type="text/css"> .text-primary{ color:#188ae2 !important;} .text-danger{ color: #ff5b5b !important; } ',  FILE_APPEND | LOCK_EX);
                   file_put_contents($tmp, ' .text-muted{ color: #98a6ad !important;} </style>', FILE_APPEND | LOCK_EX);
                   $has_row = false;
                   $extensions_cut = (  strlen($extensions) > 100 ) ? substr($extensions, 0, 80 ).'. . .' : $extensions;
                   file_put_contents($tmp,"<h3 style='display:inline'> HR Activity summary </h3><div>{$repo_range}</div>{$extensions_cut}  <table class='table table-striped table-responsive' >\n  <tr  style='background-color:silver;'><th>Worker </th> <th>Office Department</th> <th>Event</th><th>Time</th><th><i class='text-primary'>Work</i>/<i class='text-danger'>Break</i></th></tr>\n", FILE_APPEND | LOCK_EX );
                   while ( $row = mysql_fetch_assoc($res)){
                         $has_row = true;                                                 
		                  	 $time = ( preg_match('/LOGIN/',$row['event_data'] )) ? "<i class='text-danger'>{$row['break_time']}</i>" : "<i class='text-primary'>{$row['session_time']}</i>"  ; 
		                     $time  = preg_replace("/danger'>00:00:00/", "muted '>started", $time );
                         $time  = preg_replace("/primary'>00:00:00/", "muted '>closed", $time );
                         file_put_contents($tmp, "<tr><td nowrap><b> {$row['worker']}</b></td><td nowrap> {$row['title']} </td><td nowrap>{$row['event_data']}&nbsp;&nbsp;</td><td nowrap>{$row['ts']} </td>  <td><b>{$time}</b></td></tr>\r\n", FILE_APPEND | LOCK_EX);
                   }
                   file_put_contents($tmp, "</table>\n", FILE_APPEND | LOCK_EX);


                 //2. Summary with earlest and oldest event and time duration between 
                   $res = mysql_query("SELECT 
                           concat(ifnull(t_sip_users.first_name,''),' ',ifnull(t_sip_users.last_name,''),' ',t_sip_users.name ) as worker,
                           min(date_format(substring_index(substring_index(event_details,'[',-1),']',1), GET_FORMAT(DATETIME,'ISO'))) as qstart,
                           max(ts) as qend,
                           sec_to_time(sum(Ifnull(session_time, 0))) AS session_time ,
			                     sec_to_time(sum(Ifnull(break_time, 0))) AS break_time ,
                           sec_to_time( TIMESTAMPDIFF(SECOND,min(timestamp(substring_index(substring_index(event_details,'[',-1),']',1))), max(ts) ) ) as daywork_time 
                           FROM t_queue_members_log,t_sip_users
                            WHERE t_queue_members_log.tenant_id = {$tenant_id} AND
                                  t_queue_members_log.tenant_id = t_sip_users.tenant_id  AND
                                  t_queue_members_log.sip_name = t_sip_users.name AND 
                                  ( event_type = 'hrlogoff' OR event_type = 'hrlogon' )AND        
                                  {$members}
                                  {$range} 
                           GROUP BY t_sip_users.name");

                   file_put_contents($tmp,"<br><h4 style='display:inline;'>Employer Report: HR Workers Time summary  </h3><div>{$repo_range}, {$extensions} </div> <table class='table table-striped table-responsive' >\n  <tr  style='background-color:silver;'><th>Worker </th> <th>Login</th> <th>Logout</th><th>at work</th><th>Work time</th><th>Breaks time</th></tr>\n", FILE_APPEND | LOCK_EX );
                   while ( $row = mysql_fetch_assoc($res)){
                         $has_row = true;                                                  
                         file_put_contents($tmp, "<tr><td nowrap><b>{$row['worker']}</b></td><td nowrap> {$row['qstart']} </td><td nowrap>{$row['qend']}&nbsp;&nbsp;</td><td nowrap>{$row['daywork_time']} </td><td><b>{$row['session_time']}s</b></td><td class='text-danger'><b>{$row['break_time']}</b></td></tr>\r\n", FILE_APPEND | LOCK_EX);
                   }
                   file_put_contents($tmp, "</table>\n", FILE_APPEND | LOCK_EX);
                     

                    switch ($output_format){
                         case 'csv':
                             header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
                             header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                             //header("Last-Modified: {$now} GMT");
                     
                            // force download
                            header("Content-Type: application/force-download");
                            header("Content-Type: application/octet-stream");
                            header("Content-Type: application/download");
                            header("Content-Disposition: attachment;filename={$rtype}_hrReport_{$day_str}.csv");
                            header("Content-Transfer-Encoding: binary");
                            echo "#   Worker , Dep, Login, Logout, Session Time\n\r";
                            mysql_data_seek($res, 0);
                            while ( $row = mysql_fetch_assoc($res) ) {                               
                               echo " {$row['worker']},  {$row['queue_name']}, {$row['name']}, {$row['ts']}, {$row['session_time']}s\r\n";
                             }  
                             break;

                         case 'html':                        

                           if($has_row)
                             echo file_get_contents($tmp);
                           else
                             echo " <h4 style='white-space:nowrap;'>No Records for selected Report </h4>"; 

                           echo "<script type='text/javascript'> \n";
                           echo " \$('#export_panel').css('visibility', 'visible');\n"; 
                           echo " \$('#export_panel button').attr('report_type','{$rtype}') ;\n";       
                           echo "</script>";
                           break;

                         case 'pdf':                         
                            require_once __DIR__ . '/vendor/autoload.php';                            
                            $mpdf = new mPDF('utf-8', 'A4-L');
                            $mpdf->SetHeader( "HR Activity Report for: {$day_str} | {PAGENO}" );
                            $mpdf->WriteHTML(   file_get_contents($tmp) );      
                            $mpdf->SetFooter(" <small>Generated at [ ".date("Y-m-d H:m") ." ], by CloudPBX server </small>" );
                            ob_clean();                            
                            $PDFfile  =  '/tmp/' . $rtype . '_'.$report_name.'_' . $day_str . '.pdf';

                            if(isset($send_qactivity_emails)){

                              //$to_email = $to_emails?$to_emails:'p.deol@etornetworks.com hayer@armour-insurance.com';
                              $send_to = $to_emails?$to_emails:'voip.linux.expert@gmail.com failed@a4business.com';
                               
                               $mpdf->Output( $PDFfile, 'F');                               
                               if(file_exists($PDFfile) ){                                         
                                  if ( sendCallStats( $send_to , $PDFfile, "::: HR Activity Repo :::" ) == 'OK' )
                                    echo json_encode( array( 'success'=> true, 'error' => "HR repo has been SENT to {$send_to}  ") );
                               }else{
                                 echo json_encode( array( 'success'=> false, 'error' => "Failed to generate {$PDFfile} for:{$send_to}" ) );
                               }  

                            }else
                              $mpdf->Output( $PDFfile, 'D');

                           break;
                    } 
            }            


  }
   


// REport of DIDs   D  I  D  
//
//if ( !isset($_SESSION['tenantid']) ) {
//
//	echo '<span style="color:red"> <b>Session expired!</b> Please relogin</span>';
//	exit;
// }

if( isset($dids_usage) ){

  $_tmp = tmpfile();
  $_tmp = stream_get_meta_data($_tmp);
  $tmp = $_tmp['uri'];
  $output_format = $output_format ? $output_format : 'html';

    $tenant_id = $_SESSION['tenantid'];
    
    $selected =  "'" . implode( "','" , explode(',' , $dids_usage ) ) . "'";


    list($from_date,$to_date) = explode("to",$drange);  
    if( $from_date && $to_date ){
      $range = "  date(calldate) between date('{$from_date}') AND date('{$to_date}') ";
      $day_str = $from_date . '_' . $to_date;
      $repo_range = " [ {$from_date} : {$to_date} ]";
    }else{
      $range = " date(calldate)  = date(now()) ";
      $day_str = date("Y-m-d");
      $repo_range = " [ {$day_str} ]";
    }

    $repo_filter = "<small>DIDs:&nbsp; " . rtrim(substr($dids_usage,0,100),', ') . ((strlen($dids_usage)>100)?'...':'') . '</small>';

    if(!$tenant_id){
	    echo "Failed";
    }

// Inbound Summary 
   if($rtype == 'inbound_summary'){
                  $res = mysql_query(" SELECT 
                             dst as exten,
                             (select service_status FROM t_cdrs t2 
                                where t2.linkedid = t_cdrs.uniqueid  AND 
                                      ifnull(service_status,'') != '' AND 
                                      LOCATE(':',service_status ) > 0 
                              LIMIT 1) as served,                             
                              count(*) as TOTAL
                         FROM t_cdrs
			  WHERE tenant_id = {$tenant_id}  AND
				dstchannel like '%-ivrmenu-%' AND
                                {$range} AND  channel not like 'Local%internal%' AND  
            		        dst IN ( {$selected} ) GROUP BY 1,2"
                        ) or die('MySQL error:' . mysql_error()  );


                  $out = '<style type="text/css"> .text-primary{ color:#188ae2 !important;} .text-danger{ color: #ff5b5b !important; } .cdr_row{ border:1px solid silver;border-right:1px solid gray; } </style>';
                  $out .=  "<h3 style='display:inline;white-space:nowrap;'>Inbound calls summary </h3><div>{$repo_range}</div> $repo_filter ";
                  $out .= "<table class='table .table-striped  summary' style='width:600px;border: none' >\n
                             <tr style='background-color:#DFDFDF;
                    border-top:1px solid white;
                    border-left:1px solid silver;
                    border-bottom:1px solid gray;
                    border-right:1px solid gray;'  class=cdr_head > ";
                  $out .=  "<th>DID# </th> <th>Total calls</th> <th>OPERATOR</th> <th >IVR</th><th >Missed </th></tr>\n";
                   while ( $row = mysql_fetch_assoc($res) ) {
                     //$missed =  ( $row['TOTAL'] > 0 ) ? round( (100 * $row['T_NOANSWER'] )/$row['TOTAL'], 0) :0;
                     //$out .= "<tr class=cdr_row><td nowrap> {$row['exten']} </td><td> {$row['TOTAL']} </td><td> {$row['T_ANSWERED']} </td><td>{$row['T_NOANSWER']} </td><td><b>{$missed}% {$row['served']}</b></td></tr>\n";                    
                    if( preg_match('/ANSWERED/',$row['served'],$m) )
                      $DIDS[$row['exten']]['ANSWERED'] = $row['TOTAL'];  // Reached Queue and got answered
                    elseif( preg_match('/ABANDONED/',$row['served'],$m) )  
                      $DIDS[$row['exten']]['ABANDONED'] = $row['TOTAL'];   // Reached Queue and was miseed
                    else
                      $DIDS[$row['exten']]['IVR'] = $row['TOTAL'];        // Never Reached QUeue - ended while in IVR 

                   }
                   foreach ($DIDS as $DID => $VALUES ) {
                    $total = $VALUES['ANSWERED'] +  $VALUES['ABANDONED'] + $VALUES['IVR'] ;
                    $out .= "<tr class=cdr_row><td nowrap> {$DID} </td><td> <b> {$total} </b></td><td style='color:#71b6f9'> <b>{$VALUES['ANSWERED']}</ b> </td><td style='color:green;'><b>{$VALUES['IVR']} </b> </td><td style='color:red'><b>{$VALUES['ABANDONED']} </b></td></tr>\n";                    
                   }


            	$out .=  "</table>\n";

            	echo $out;
                     echo "<script type='text/javascript'> \n";
                     echo " \$('#export_panel').css('visibility', 'visible');\n";
                     echo " \$('#export_panel button').attr('report_type','{$rtype}') ;\n";
                     echo "</script>";


    }


// Inbound 
 if( $rtype == 'in' || $rtype == 'in_missed' ){
                 $offset = ( $tenant_id == 213 ) ? 3 : 2;
                 $field_name = ( $rtype == 'in' ) ? 'dstchannel' : 'channel';   
		 $direction = ( $rtype == 'in' ) ? 'Incoming' : 'Outgoing';
//		 $selected = $selected ? $selected.',"s"' : $selected;
                 

                 $SQL= " SELECT  id,src,
                         calldate,
                         dst as DID,
                         clid as ANI,
                         CASE WHEN LOCATE('@',channel) > 0 THEN  substring_index(channel,'@', 1) ELSE substring_index(channel,'-', {$offset}) END  as source_name,
                         uniqueid, linkedid,service_status,lastapp,lastdata,
                  	 dstchannel,
     			 served,
                  	 channel,
                         billsec,
                         duration,
                         disposition 
                       FROM t_cdrs
                          WHERE billsec > 5 AND 
                                tenant_id = {$tenant_id}  AND 
                                {$range} AND
	                        dst IN ( {$selected} )                 
     		      ORDER BY calldate DESC
			";
                   $res = mysql_query( $SQL )  or die('Mysql Error:' . mysql_error() );
                 
                  file_put_contents($tmp, "<h3 style='display:inline;white-space:nowrap;'>Inward dialed numbers {$repo_range} </h3><div>{$repo_filter}</div>\n",FILE_APPEND | LOCK_EX);
                  file_put_contents($tmp, "<table class='table table-striped table-responsive' style='border: 5px solid #ddd !important;' >\n",FILE_APPEND | LOCK_EX);
//                   file_put_contents($tmp, "<tr style='background-color:#DFDFDF;border-top:1px solid white;  border-left:1px olid silver;  border-bottom:1px solid gray;  border-right:1px solid gray;'><th>Date</th><th>DID# </th> <th nowrap>Inbound CLID</th>  <th>Duration</th><th>Status</th><th style=''>Connected with</th></tr>\n",FILE_APPEND | LOCK_EX);
		   $tmp_string = '';
		   $t_served=0;
       $c_oper = 0;
       $c_miss = 0;
       $c_ivr  = 0;
       
       while ( $row = mysql_fetch_assoc($res) ) {  
	       // Get call FINAL  service status //
	       if( !$row['served']  ){
		       $get_res = mysql_query("SELECT service_status FROM t_cdrs where linkedid = {$row['uniqueid']} AND ifnull(service_status,'') != ''
						    AND LOCATE(':',service_status ) > 0  LIMIT 1");
		       if($get_res){
			$get_service = mysql_fetch_assoc($get_res);
			$row['served'] = $get_service['service_status'];
		       }else{
			$row['served'] = '-';
		       }
		       $set = mysql_query("UPDATE t_cdrs SET served = '{$row['served']}' WHERE id = {$row['id']}");
		}
                      $t++;
	              $t_ans += ( $row['disposition'] == 'ANSWERED' ) ? 1 : 0;

                      // Following was not needed - DID always answered by IVR, no missed calls 
                      if ( $row['disposition'] == 'ANSWERED'  && $rtype == 'in_missed' ) {
                        continue;
		      }
		      // Remove duplicatinos of DID and ANI in ANI field, make ANI to be shows only once :
		      $row['ANI'] = $row['src'] . ' ' . preg_replace(array('/'.$row['DID'].'/','/'.$row['src'].'/','/>/','/</'),'',$row['ANI'] );
		      $t_billsec +=  $row['billsec'];

                      
		      if(isset($row['served'])){
			      $t_served++;
			      if(  preg_match('/ANSWERED/',$row['served'],$m) ){
				       $service = "<span style='color:#71b6f9'>OPERATOR</span>";
			               $c_oper++;
			      }elseif( preg_match('/ABANDONED/',$row['served'],$m) ){
				       $service = "<span style='color:red'>Q:MISSED </span>";
			               $c_miss++;
			      }else{
			         $service = '<span style="color:green">IVR</span>';
			         $c_ivr++;
			      }   
		      }else
			       $service = '';
                     
          $style=($row['disposition'] == 'ANSWERED') ? "style='color:green;'" : "";
                     
		      // file_put_contents($tmp, "<tr style='border:1px solid silver;border-right:1px solid gray;'><td nowrap>{$row['calldate']}</td><td title='{$row['DID']}' nowrap> {$row['DID']} </td><td nowrap> {$row['ANI']}  </td><td nowrap>{$row['billsec']}s </td><td nowrap {$style} ><b>{$row['disposition']}</b></td></tr>\n", FILE_APPEND | LOCK_EX);
		      $tmp_string .= "<tr style='border:1px solid silver;border-right:1px solid gray;'><td nowrap>{$row['calldate']}</td><td title='{$row['DID']}' nowrap> {$row['DID']} &nbsp;&nbsp;</td><td nowrap> {$row['ANI']}  </td><td nowrap>{$row['billsec']}s </td><td nowrap {$style} ><b>{$row['disposition']}</b></td><td>{$service}</td></tr>\n";

       }
      // CALCULATE Header/Footer summary row:
		  $p = round( ($t_ans * 100 ) / $t );
      $p_oper = round( ($c_oper * 100 ) / $t_served ) ;
      $p_miss = round( ($c_miss * 100 ) / $t_served ) ;
      $p_ivr = round( ($c_ivr * 100 ) / $t_served ) ;
      $sub_head1 = "<tr style='background-color:#DFDFDF;height:10px;
                          border-top:1px solid white;
                          border-left:1px solid silver;
                          border-bottom:1px solid gray;
			  border-right:1px solid gray;'><td nowrap colspan=3 ><b>Total calls: {$t}</b>&nbsp;&nbsp;&nbsp;<span style='float:right;'>Answered calls:<b>{$t_ans} ( {$p}% )</b>&nbsp;&nbsp;&nbsp;&nbsp;</span></td><td nowrap><b>{$t_billsec}s</b></td><td></td><td>Connected:<b> {$t_served}</b></td></tr>\n";
      $sub_head2 =  "<tr style='background-color:#DFDFDF;height:10px;
                          border-top:1px solid white;
                          border-left:1px solid silver;
                          border-bottom:1px solid gray;
        border-right:1px solid gray;' ><td nowrap colspan=4 >Connected with <b> 
        <div style='text-align:right;float:right;'> <span style='color:#71b6f9'>OPERATOR: {$c_oper}</span> <br>
            <span style='color:green'>IVR : {$c_ivr}</span><br>
            <span style='color:red'>Missed :{$c_miss}  </span>
            </b></div>
          </td>
          <td nowrap colspan=2 > 
               <div style='margin:2px;box-shadow:2px 2px 2px gray;height:18px;text-align:right;color:white;background-color:#71b6f9;width:".($p_oper*2)."px;'><b>{$p_oper}%</b></div>
               <div style='margin:2px;box-shadow:2px 2px 2px gray;height:18px;text-align:right;color:white;background-color:green;width:".($p_ivr*2)."px;'><b>{$p_ivr}%</b></div>
               <div style='margin-bottom:2px;box-shadow:2px 2px 2px gray;height:18px;text-align:right;color:white;background-color:red;width:".($p_miss*2)."px;'><b>{$p_miss}%</b></div>
          </td>
	  </tr>\n";
       $head = "<tr style='border-top:1px solid white;  border-left:1px olid silver;  border-bottom:1px solid gray;  border-right:1px solid gray;'><th>Date</th><th>DID# </th> <th nowrap>Inbound CLID</th>  <th>Duration</th><th>Status</th><th style=''>Connected with</th></tr>\n";

       if( $rtype != 'in_missed' )
		   file_put_contents($tmp,  $sub_head1 . $sub_head2 . $head, FILE_APPEND | LOCK_EX);
		   file_put_contents($tmp, $tmp_string , FILE_APPEND | LOCK_EX);
		   if( $rtype != 'in_missed' )
                           file_put_contents($tmp,  preg_replace('/td/','th',$head) , FILE_APPEND | LOCK_EX);

		   file_put_contents($tmp,  "</table>\n", FILE_APPEND | LOCK_EX);
                   

                 switch ($output_format){
                   case 'csv':
                       header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
                       header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
                       //header("Last-Modified: {$now} GMT");
               
                  // force download
                  header("Content-Type: application/force-download");
                  header("Content-Type: application/octet-stream");
                  header("Content-Type: application/download");
                  header("Content-Disposition: attachment;filename={$rtype}_DID_calls_{$day_str}.csv");
                  header("Content-Transfer-Encoding: binary");

                       echo "# DATE,DID#,Inbound CLID, Source, DURATION, STATUS\n\r";
                       mysql_data_seek($res, 0);
                        while ( $row = mysql_fetch_assoc($res) ) 
                           echo "{$row['calldate']},{$row['DID']},{$row['ANI']},{$row['source_name']},{$row['billsec']},{$row['disposition']}\n\r";
                       echo " Total Calls: {$t}, ANSWERED:{$t_ans} ( {$p}% ), TOTAL DURATION: {$t_billsec}\n\r";   

                       break;
                   case 'html':
                   
                     echo file_get_contents($tmp);
                     echo "<script type='text/javascript'> \n";
                     echo " \$('#export_panel').css('visibility', 'visible');\n"; 
                     echo " \$('#export_panel button').attr('report_type','{$rtype}') ;\n";       
                     echo "</script>";
                     break;

                   case 'pdf':
                    require_once __DIR__ . '/vendor/autoload.php';
                    $PDFHeader = " {$_SESSION['tenantname']} | DID Usage Report for: {$day_str} | {PAGENO}";
                    $PDFFooter = "<small>Generated at [ ".date("Y-m-d H:m") ." ], by CloudPBX server </small>";
                    $mpdf = new mPDF();
                    $mpdf->SetHeader($PDFHeader);
                    $mpdf->WriteHTML(   file_get_contents($tmp) );      
                    $mpdf->SetFooter($PDFFooter);
                    ob_clean();
                    $mpdf->Output( $rtype . '_DID_calls_' . $day_str . '.pdf', 'D');
                     break;
                   } 
}



 


}
 

 



if(isset($switch_tenant_to)){
	session_start();

   $_SESSION['tenantid']  = (int)$switch_tenant_to;
   $res = mysql_query("SELECT * FROM tenants WHERE id = {$_SESSION['tenantid']}");
   $row = mysql_fetch_assoc($res); 
   $_SESSION['tenantref'] = $row['ref_id'];
   $_SESSION['logo_image'] = $row['logo_image']; //$row['id'];  
   $_SESSION['tenantname'] = $row['title'];
   echo json_encode( array( 'success'=> true, 'error' => "OK: Switched to $switch_tenant_to") );
   return;
}


if(isset( $delScheduler )){ 
  mysql_query("DELETE FROM t_scheduler WHERE id={$delScheduler}");
  echo 'OK - Deleted!';
}




// Deliver stats to Emails for the finished Shifts ( which not has been sent )
if (isset($send_stats)){


  // Check for finished non-sent Today shifts , they sent one time only on shift end!!//
  $res = mysql_query( "SELECT * FROM t_shifts
                      WHERE  addtime(concat(date(now()),' 00:00:00'), shift_end) < now() AND
                             datediff(now(),ifnull(last_sent,'2019-01-01') ) > 0 "
                    );
  if(mysql_affected_rows()){
    while($row = mysql_fetch_assoc($res)){
      $URL = "https://localhost:8182/jaxer.php?get_stats=1&shift_id={$row['id']}&d=Today";
        $ch = curl_init( $URL );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt( $ch, CURLOPT_FAILONERROR, true);
        curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.107 Safari/537.36');
        $data = date("F j, Y, g:i a") . ' : ' . curl_exec($ch);    
      echo $data . '\n';
      if (curl_errno($ch)) 
        echo curl_error($ch);
      
      curl_close($ch); 
    }

  }else{
    echo date("F j, Y, g:i a") . " No Shift reports for now YET!\n";
  }

}


// 
// QUEUES Missed calls statisric (to PDF ) - called by curl /get_stats=1 with certain finished shiftID, and not yet generated Email 
// Generated according to the SHIFT TIME (when DEFINED shift_id ), or all scope for the day  when no shift_id //  
// OR for  certain Queue name  ( when report_name DEFINED )
if (isset($get_stats)){

	$summary = array();
  $max_rows = 100;
  $grant_lost = 0;
  $grant_talk = 0;
	$_tmp = tmpfile();
  $_tmp = stream_get_meta_data($_tmp);
  $tmp = $_tmp['uri'];

  $day = ( trim($d)  && trim($d) != 'Today' ) ? "'".$d."'" : "'". date("Y-m-d")."'";
	
  if(isset($report_name))
	  $filter =  " AND  trim(SUBSTRING_INDEX(service_status, ':', 1)) = '{$report_name}'" ;
  else{
    $filter = '';
    $report_name = 'Shift_Report';
  }

  $shift_filter = '';
  $shift_title  = '';

  if(isset( $shift_id )){    
    $fday = str_replace("'", "", $day);
    $sql = "SELECT *, 
                    tenants.id as tenant_id,
                    title as tenant_name,                    
                    CASE WHEN timediff(shift_end, shift_start) > 0 
                        THEN timediff(shift_end, shift_start) 
                        ELSE addtime( timediff('24:00:00', shift_start  ), shift_end ) 
                    END as shift_length,
                    ADDTIME('{$fday} 00:00:00', shift_end ) as end_tstamp
                   FROM t_shifts, tenants   
                      WHERE t_shifts.id = ${shift_id} AND
                            t_shifts.tenant_id = tenants.id
                      LIMIT 1";
    $shift = mysql_fetch_assoc( mysql_query($sql) );    
    // echo $shift['tenant_id'] . "2222\n";
    if($shift){
      $to_email = $to_email ? $to_email : $shift['send_to_email'];      
      $_SESSION['tenantname'] = $_SESSION['tenantname'] ? $_SESSION['tenantname'] : $shift['tenant_name'];
      $_SESSION['tenantid'] = $_SESSION['tenantid'] ? $_SESSION['tenantid'] : $shift['tenant_id'];

      $shift_filter = "calldate between ADDTIME('{$shift['end_tstamp']}', '-{$shift['shift_length']}') AND '{$shift['end_tstamp']}' AND";

      $shift_title_txt = " Shift[ {$shift['id']} ] Report From {$shift['shift_start']}  till {$shift['shift_end']} ";
      $shift_title = "<div style='width:100%;border:1px solid blue;margin:0;padding:2px 20px'> {$shift_title_txt}, &nbsp;&nbsp;&nbsp; Mailing to: {$shift['send_to_email']} </div>" ;
    }
    
  }else{
    $shift_filter = " datediff( {$day} ,calldate) = 0 AND ";
  }
 
	$res = mysql_query("SELECT trim(SUBSTRING_INDEX(service_status, ':', 1)) as group_name,
				                    t_queues.name as queue_name,
				                    max(calldate) as till_date,
				                    min(calldate) as from_date ,
                            SUM( CASE WHEN ifnull(trim(SUBSTRING_INDEX(service_status, ':', -1)),'') = 'ABANDONED' THEN 1 ELSE 0 END ) as lost_grant_cnt,
                            SUM( CASE WHEN ifnull(trim(SUBSTRING_INDEX(service_status, ':', -1)),'') = 'ANSWERED' THEN 1 ELSE 0 END ) as talk_grant_cnt
	                     FROM t_cdrs , t_queues
	                      WHERE SUBSTRING_INDEX(did, '-', -1) = t_queues.id AND
				                      t_cdrs.tenant_id = t_queues.tenant_id AND
				                      service_status is not null AND 
                              {$shift_filter}                              
	                            t_cdrs.tenant_id = {$_SESSION['tenantid']} 
	                            $filter                              
                        GROUP BY  1,2 
			 ") or die(mysql_error());
          
   if(mysql_affected_rows())      
     while($rr = mysql_fetch_assoc($res)) {
          $grant_lost += $rr['lost_grant_cnt'];
          $grant_talk += $rr['talk_grant_cnt'];
	        $total_rows = mysql_fetch_assoc(mysql_query("SELECT count(*) as cnt,
                      					  SUM( CASE WHEN ifnull(trim(SUBSTRING_INDEX(service_status, ':', -1)),'') = 'ABANDONED' THEN 1 ELSE 0 END ) as lost_cnt,
                      					  SUM( CASE WHEN ifnull(trim(SUBSTRING_INDEX(service_status, ':', -1)),'') = 'ANSWERED' THEN 1 ELSE 0 END ) as talk_cnt
                      					  FROM t_cdrs
                      					  WHERE t_cdrs.tenant_id = {$_SESSION['tenantid']}  AND
                                       {$shift_filter}                	                     
                        	             trim(SUBSTRING_INDEX(service_status, ':', 1)) = '{$rr['group_name']}' 
                                        "));

            $LIMIT = ($total_rows['lost_cnt'] > $max_rows) ? "LIMIT ${max_rows}" : '';

          	 $sub_r = mysql_query("SELECT calldate, clid, did, 
	                			    sec_to_time(duration) as wait_time_str,
				                    trim(SUBSTRING_INDEX(service_status, ':', -1)) as status 
     	                     FROM t_cdrs
				                   WHERE t_cdrs.tenant_id = {$_SESSION['tenantid']}  AND 
                                   {$shift_filter}				                           
     	                             trim(SUBSTRING_INDEX(service_status, ':', 1)) = '{$rr['group_name']}' AND
     	                             ifnull(trim(SUBSTRING_INDEX(service_status, ':', -1)),'') = 'ABANDONED'
                                 {$LIMIT}" );

           	$total_calls =  $total_rows['lost_cnt'] + $total_rows['talk_cnt'] ;
            $lost_percent =  round( ( $total_rows['lost_cnt'] * 100 ) /  $total_calls  ,0);
            $summary[] = array('q'=>  $rr['queue_name'], 'lost' => $lost_percent );
            $html  = "<h2 style='margin-bottom:2px'> {$rr['queue_name']} - {$lost_percent}% losts </h2><small> Lost <b>{$total_rows['lost_cnt']} out of {$total_calls} calls ( {$lost_percent}% )</b> calls<br> Period: {$shift['shift_start']} - {$shift['shift_end']}</small>";
           
            if( $total_rows['lost_cnt'] > 0 ){
                  $html .= ($total_rows['lost_cnt'] > $max_rows ) ? "<small> (shown only first ${max_rows} rows)</small>" : '';
             	  $html .= "<hr width=60%><table style='width:100%'><tr><th> Call date</th> <th> From</th>  <th> Wait time </th>   </tr>";
             	  $i=0;
                  while($rr2 = mysql_fetch_assoc($sub_r)) {
               	    $bgColor=(($i++)%2)?'silver':'white';  
               	    $html .= "<tr nowrap style='background-color:{$bgColor}'><td nowrap>{$rr2['calldate']}</td><td nowrap>{$rr2['clid']} </td><td>{$rr2['wait_time_str']}</td></tr>";
                  }	 
                  $html .= "</table>";	
               }
                file_put_contents($tmp, $html . '<br><br>' ,FILE_APPEND | LOCK_EX);
    }else{
        file_put_contents($tmp, "<center><hr width=40%><span style='color:gray;margin:auto'> (No Missed calls for department/group: {$report_name} ) </span><hr width=40%></center>",FILE_APPEND | LOCK_EX);
    }

   usort($summary, function($a,$b){ 
      return $b['lost'] - $a['lost'] ;   
    });

   $header_chart = "<center><h1 style='background-color:#B0B0B0;padding:2px 2px'>" . $_SESSION['tenantname'] . ", Lost calls for {$day} </h1>  </center>";
   $grant_total = $grant_lost + $grant_talk;
   $grant_lost_pcnt = ($grant_total>0) ? round( $grant_lost * (100/$grant_total), 0) : 0;
   $header_chart .= $shift_title;
   $header_chart .= "<span> Total received calls: {$grant_total} ;    Total lost {$grant_lost_pcnt} %  </span><hr>";  
   $header_chart .=  "<table style='width:100%'> ";
   foreach($summary as $item ){

     $header_chart .= "<tr><td style='width:20%;text-align:right;'> {$item['q']} </td>
         <td style='width:400px;text-align:left;'><div style='width:100%;background-color:#ddd;'>
         <span style='color:white;background-color:#4CAF50;display:inline'>" . str_pad(' ' ,$item['lost'],' - - - ' )  .  "</span>" .
            str_pad(' ' ,100 - $item['lost'],' ' ) . "{$item['lost']}%  
           </div>
          </td></tr>";

   }

   $header_chart .= "</table><hr height=5px><br><br>";

   //file_put_contents($tmp, $chart, FILE_APPEND | LOCK_EX);
  
   

   require_once __DIR__ . '/vendor/autoload.php';
   $mpdf = new mPDF();
   $mpdf->WriteHTML(  $header_chart . file_get_contents($tmp) );
   
   if( $mode == 'show' || !isset($to_email)){
    ob_clean();
    $mpdf->Output('missed_calls_' . preg_replace("/'/",'',$day ) . '.pdf', 'D');
  }else{
     if(isset( $shift_id ))
       $res = mysql_query("UPDATE t_shifts SET last_sent = now() WHERE id = {$shift_id}");
     $PDFfile = '/tmp/' . $report_name . '.pdf';
     ob_clean();
     $mpdf->Output($PDFfile,'F');

     if(file_exists($PDFfile) ){
      if(isset($to_email)){ 
       $r = sendCallStats($to_email, $PDFfile, $report_name . $shift_title_txt, $_SESSION['tenantname'] );
       if ($r == 'OK' ) 
         echo json_encode( array( 'success'=> true, 'error' => "Shift Report {$report_name} {$shift_title_txt}  has been SENT to {$to_email}! ") );        
       }
     }else 
      echo json_encode( array( 'success'=> false, 'error' => "Failed to generate {$PDFfile} for:(" . $to_email .')' ) );
   } 
 	
}


if ( isset($get_next_exten) ){
	   
   $_res = mysql_query("SELECT max(convert(extension,decimal(10))) as extension FROM t_sip_users WHERE tenant_id = {$_SESSION['tenantid']}");
   $_row = mysql_fetch_assoc($_res);
   $_ext = is_null($_row['extension']) ? '101' : $_row['extension']+1;
   
   //echo json_encode( array( 'extension' => $_ext, 'name' => $_SESSION['tenantref'].'-'.$_ext, 'secret' => bin2hex(openssl_random_pseudo_bytes(4))   ));
   echo json_encode( array( 'extension' => $_ext, 'secret' => bin2hex(openssl_random_pseudo_bytes(4)) ));
   
}

if ( isset($run) ){	  
  
  switch( $run ) {
    case 'reload_asterisk':
          $ret = $config->reload_dialplan();
          $config->core_reload();
          echo json_encode( array( 'response' => array( 'status' => 'OK', 'message' => "PBX Core reloaded!" ) ) );
     break;
     
   case 'reload_conf':
     $config->reload_dialplan();
     $config->reload_sip();
     echo json_encode( array( 'response' => array( 'status' => 'OK', 'message' => "Config reloaded!" ) ) );
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
