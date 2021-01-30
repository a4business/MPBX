<?php

// Asterisk Event Listener - AstListen
//ini_set('display_errors', 1); 
//error_reporting(1);
require_once("vendor/autoload.php");
include_once(dirname( __DIR__)  . '/include/config.php');

use PAMI\Client\Impl\ClientImpl as PamiClient;
/*
use PAMI\Listener\IEventListener;  
use PAMI\Message\Event\EventMessage; 
use PAMI\Message\Event\OriginateResponseEvent;  
use PAMI\Message\Event\ExtensionStatusEvent;
use PAMI\Message\Event\BridgeEvent;
use PAMI\Message\Event\DialEvent;
use PAMI\Message\Event\CELEvent;
use PAMI\Message\Event\DialBeginEvent;
use PAMI\Message\Event\DialEndEvent;
use PAMI\Message\Event\NewstateEvent;
use PAMI\Message\Event\StatusCompleteEvent;
use PAMI\Message\Event\StatusEvent;
use PAMI\Message\Action\HangupAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\MessageSendAction;
use PAMI\Message\Action\CoreShowChannelsAction;
use PAMI\Message\Action\GetVarAction;
*/

use PAMI\Client\Impl\ClientImpl;
use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;
use PAMI\Message\Action\ListCommandsAction;
use PAMI\Message\Action\ListCategoriesAction;
use PAMI\Message\Action\CoreShowChannelsAction;
use PAMI\Message\Action\CoreSettingsAction;
use PAMI\Message\Action\CoreStatusAction;
use PAMI\Message\Action\StatusAction;
use PAMI\Message\Action\ReloadAction;
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\HangupAction;
use PAMI\Message\Action\LogoffAction;
use PAMI\Message\Action\AbsoluteTimeoutAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\BridgeAction;
use PAMI\Message\Action\CreateConfigAction;
use PAMI\Message\Action\GetConfigAction;
use PAMI\Message\Action\GetConfigJSONAction;
use PAMI\Message\Action\AttendedTransferAction;
use PAMI\Message\Action\RedirectAction;
use PAMI\Message\Action\DAHDIShowChannelsAction;
use PAMI\Message\Action\DAHDIHangupAction;
use PAMI\Message\Action\DAHDIRestartAction;
use PAMI\Message\Action\DAHDIDialOffHookAction;
use PAMI\Message\Action\DAHDIDNDOnAction;
use PAMI\Message\Action\DAHDIDNDOffAction;
use PAMI\Message\Action\AgentsAction;
use PAMI\Message\Action\AgentLogoffAction;
use PAMI\Message\Action\MailboxStatusAction;
use PAMI\Message\Action\MailboxCountAction;
use PAMI\Message\Action\VoicemailUsersListAction;
use PAMI\Message\Action\PlayDTMFAction;
use PAMI\Message\Action\DBGetAction;
use PAMI\Message\Action\DBPutAction;
use PAMI\Message\Action\DBDelAction;
use PAMI\Message\Action\DBDelTreeAction;
use PAMI\Message\Action\GetVarAction;
use PAMI\Message\Action\SetVarAction;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\ParkedCallsAction;
use PAMI\Message\Action\SIPQualifyPeerAction;
use PAMI\Message\Action\SIPShowPeerAction;
use PAMI\Message\Action\SIPPeersAction;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\SIPNotifyAction;
use PAMI\Message\Action\QueuesAction;
use PAMI\Message\Action\QueueStatusAction;
use PAMI\Message\Action\QueueSummaryAction;
use PAMI\Message\Action\QueuePauseAction;
use PAMI\Message\Action\QueueRemoveAction;
use PAMI\Message\Action\QueueUnpauseAction;
use PAMI\Message\Action\QueueLogAction;
use PAMI\Message\Action\QueuePenaltyAction;
use PAMI\Message\Action\QueueReloadAction;
use PAMI\Message\Action\QueueResetAction;
use PAMI\Message\Action\QueueRuleAction;
use PAMI\Message\Action\MonitorAction;
use PAMI\Message\Action\PauseMonitorAction;
use PAMI\Message\Action\UnpauseMonitorAction;
use PAMI\Message\Action\StopMonitorAction;
use PAMI\Message\Action\ExtensionStateAction;
use PAMI\Message\Action\JabberSendAction;
use PAMI\Message\Action\LocalOptimizeAwayAction;
use PAMI\Message\Action\ModuleCheckAction;
use PAMI\Message\Action\ModuleLoadAction;
use PAMI\Message\Action\ModuleUnloadAction;
use PAMI\Message\Action\ModuleReloadAction;
use PAMI\Message\Action\ShowDialPlanAction;
use PAMI\Message\Action\ParkAction;
use PAMI\Message\Action\MeetmeListAction;
use PAMI\Message\Action\MeetmeMuteAction;
use PAMI\Message\Action\MeetmeUnmuteAction;
use PAMI\Message\Action\EventsAction;
use PAMI\Message\Action\VGMSMSTxAction;



 //$conf =  file_exists( '/etc/asterisk/sip.conf' ) ? parse_ini_file( '/etc/asterisk/sip.conf', true, INI_SCANNER_RAW ) : false;
  
 
 $pamiClient = new PamiClient( $config->getPamiOptions() );
 $pamiClient->open();
 
 
 $pamiClient->registerEventListener(
 
    function (EventMessage $event) {
	    GLOBAL $conf;
       GLOBAL $pamiClient;	    
	    
       $last_event = $event->getKeys();
       $event_type = $last_event["event"];
       
       //echo $event_type . "\n";

       switch (true) {	     
	      case $event_type == 'VarSet' && $last_event['variable'] == 'SIPCALLID' :
	          // New call - created new SIPCALLID
		       //CallStartEvent($last_event, $PEER_NAME);
            break;
              
         case $event_type == 'Newchannel':
           if ($last_event['linkedid'] == $last_event['uniqueid'] ){ 
             echo "  \t---> NEW CHAN EVENT:" . $last_event['channel'] .  "\n";
              // We can get vars from channel here get_channel_var();
           }   
             
             
		     break;
		     
	      case $event_type == 'Hangup' :
	         // Call END //
	         CallEndEvent($last_event);
		     break;
		     
		     
		   
         default:
             # code...
           break;
      }
        
    }
 );  



$running = true;
  while($running) {
    try{
      //ob_start(); //$content = ob_get_contents();    //ob_end_clean();
      $pamiClient->process();
    
      // Init arrays :
      unset($tenants_calls);
      $tenants_calls = array();
   if(false){
      $res_t = mysql_query("SELECT ref_id FROM tenants");
      while($item = mysql_fetch_assoc($res_t)) $tenants_calls[$item['ref_id']] =  0 ;
      
      unset($campaigns_calls);
      $campaigns_calls = array();
      $res_c = mysql_query("SELECT * FROM t_campaigns");
      while($camps = mysql_fetch_assoc($res_c)) $campaigns_calls[$camps['id']] =  0 ;
            
      $active = getActiveCalls();
      foreach($active as $key=>$value)
       if( $value && array_key_exists( 'tenant',$value ) && isset($tenants_calls[$value['tenant']]) )
      	$tenants_calls[$value['tenant']]++; 
      	     	
      foreach($active as $key=>$value)
        if(  array_key_exists( 'campaign_id',$value ) && isset($campaigns_calls[$value['campaign_id']]) )
      	 $campaigns_calls[$value['campaign_id']]++;

      foreach($tenants_calls as $t=>$c){      
       	mysql_query("UPDATE tenants SET active_calls = {$c} WHERE ref_id = '{$t}'");
         if ($c) echo " Tenant [ $t\t\t] : Running $c calls. \n";     
      } 
      
   }   
     
    /*  
      $res_peers = mysql_query("SELECT host FROM trunks");
      while( $peer = mysql_fetch_assoc( $res_peers )){             
       $peer_calls = isset($active_calls[$peer['host']]) ? $active_calls[$peer['host']] : 0;
       echo "  Tenant [ sncd\t ]  Running:[ {$peer_calls} ]  Limit:[ ?? ]  PEER: [ {$peer['host']} \t ],  \n";
      }  
      echo "\n";
    */  
    
    // Monitor Campaigns //
      $res = mysql_query("SELECT id, max_active_calls, name  FROM t_campaigns WHERE campaign_status = 'RUNNING'");
      if(mysql_affected_rows()){
        while( $campaign_row = mysql_fetch_assoc($res)){
      	  $campID = $campaign_row['id'] ;
      	  $cnt = isset($campaigns_calls[$campID]) ? $campaigns_calls[$campID] : 0;
      	  echo " Campaign[ {$campaign_row['name']}({$campID}) ] Running: {$cnt} calls  [MAX:{$campaign_row['max_active_calls']}]  \n";
      	     
      	  if( $cnt < $campaign_row['max_active_calls'] ){
      	  	 $to_start =  $campaign_row['max_active_calls'] - $cnt;
      	  	 $to_start = ($to_start > 10)?10:$to_start;  // LIMIT creating only 10 new calls per second 
            $l = true;
      	    for($c=1; $c <= $to_start && $l; $c++)
               $l = NewCampaignCall($campID);	  	
           }   
         }
      }else{
      	echo " \033[38;05;8m   No Campaign Running. \033[0m  \n";
      }   
    
     //echo "\n";
     sleep(1);
    }catch (Exception $e){    	
      echo "\033[38;05;9m   ERROR[Line:{$e->getLine()}]: \033[0m  {$e->getMessage()} \n\n";
    //  print_r($e); 
      return;
      // for code in {0..255}; do echo -e "\e[38;05;${code}m $code: Test"; done
    }
  }   

$pamiClient->close();  


function NewCampaignCall( $campaignID ) {
    GLOBAL $pamiClient;
       $camp_info  = mysql_fetch_assoc( mysql_query("SELECT * FROM t_campaigns,tenants WHERE tenants.id = t_campaigns.tenant_id AND t_campaigns.id = $campaignID") );
       $phone_info = mysql_fetch_assoc( mysql_query("SELECT * FROM t_campaign_leads WHERE t_campaign_id = $campaignID AND last_called  = 0 ORDER BY ID LIMIT 1") );


       if($phone_info['phone']){
       	 $LEG1 = "Local/{$phone_info['phone']}@internal-{$camp_info['ref_id']}-outbound";
       	 $LEG2 = get_location($camp_info['default_action'], $camp_info['default_action_data'],  $camp_info['ref_id']);
       	 
       	 echo "  \__NEW CAMPAIGN CALL: {$LEG1} ---> {$LEG2} \n";
       	 
          $new = Originate( $LEG1, $LEG2,
					  array("tenant" => $camp_info['ref_id'],
                                                "tenant_id" => $camp_info['tenant_id'],
						"dst_number" => $phone_info['phone'], 
                                                "lead_id" => $phone_info['id'],
                                                "lead_campaign_id" =>  $campaignID ) );

          if(preg_match('/successfully/',$new['message'],$m) ) {
            $r = mysql_query("UPDATE t_campaign_leads SET last_called = now() WHERE id = {$phone_info['id']}");
            echo "  \t \_ OK -> {$phone_info['phone']}\n"; 
	    return true;
          }else{
          	 echo "  \t \_ FAILED -> {$new['response']}: {$new['message']} \n";
		 return false;
          	 //print_r($new);
          }	 
           
       }else{ 
         echo "   \_WARNING: FAILED new call Campaign[{$campaignID}]: OUT of LEADS!  \n";
	 return false;
       }
}

function Originate( $LEG1, $LEG2, $vars) {
    GLOBAL $pamiClient;

       $actionid = md5(uniqid());
        if( preg_match("/^Macro\/(.*)-?.*/", $LEG2 , $m ) ){
         $APP = 'Macro';
         $LEG2= $m[1];
        }else{
        	$APP = "Dial";
        } 
//   $response = $pamiClient->send(new StatusAction());
        $originateMsg = new OriginateAction( $LEG1  );
        $originateMsg->setActionID($actionid);
	     $originateMsg->setApplication($APP);
        $originateMsg->setData( $LEG2 );
        $originateMsg->setContext('internal-' . $vars['tenant']);
        $originateMsg->setPriority('1');        
        $originateMsg->setExtension( $vars['dst_number']? $vars['dst_number'] : 's' );
//      $originateMsg->setCallerId( $phone );
        $originateMsg->setAsync(true);
        foreach( $vars as $var=>$val )
           $originateMsg->setVariable("__{$var}", $val );
                     
        $orgresp = $pamiClient->send($originateMsg);        
        return  $orgresp->getKeys();
}


function NewChannelEvent($event,$PEER){ 

   GLOBAL $conf;
   echo "     New channel  $PEER  {$event['channel']}  peer timer:" . $counters[$PEER]['timer'] .";\n";
   
}

function get_location($action, $data, $tenant){
	
	switch( $action ) {
		     	case "extension":
		     	case "number":
			         $ret = "Local/{$data}@internal-{$tenant}-local,60,r" ;
			         break;         
			          	   
	       	case "disa":
	       	case "play_invalid":
	       	case "play_rec":
	    	          $ret = "Macro/app-pbx-service,{$action},{$data},{$tenant}";
	 	        	   break;
	 	        	   
       	   case "ivrmenu":
       	   case "queue":
       	   case "ringgroup":
          	   $ret = "Local/s@internal-{$tenant}-{$action}-{$data},60,tT" ;          	   
          	   break;
	 		          	   
	    	   case "pagegroup":
	               $ret = '';
		          	break;
	          	   
	         case "voicemail":
	              	$vm_box =  mysql_fetch_assoc( mysql_query("SELECT vmailbox FROM t_vmusers WHERE id = $data"));   
	          	   $exec[] = "Macro/app-pbx-service,{$action},${vm_box['vmailbox']},{$tenant})";
	          	  break;
	          	   
	         case "checkvm":
	          	   //$exec[] = "VoiceMailMain(${row['vmailbox']})" ;
	               $ret = '';          	   
	          	   break;
          	
          	case "conference":
          	   $ret = "Macro/dialconference,{$data},,,,snd_{$tenant})" ;
          	   break; 
          	    
          	   
          	case "park_announce_rec":
          	    $ret = "";
          			//$PLAY_REC = $EXT;
		            //if( !preg_match('/PARKED/',$PLAY_REC) ){
		            // $PLAY_REC .= ':PARKED';  // This variable needed for ParkAndAnnounce() // 
		           // }
		           // $PLAY_REC = "snd_{$_tenant}/{$PLAY_REC}";
		           // $page_extens = json_decode($row['parkext_announce'],true);
	               //$page_extens_str = is_array($page_extens) ? 'SIP/' . implode('&SIP/', $page_extens) : 'Local/s@general-{$_tenant}-error';
	               //$exec[] = "Verbose( Got values: {$row['parkext_announce']} ";
	 		         //$exec[] = "Macro(app-pbx-service,park_announce_rec,{$PLAY_REC}|{$page_extens_str},{$_tenant})";  //  // This | delimiter used by Macro app-pbx-service, which separate recording and announce exten 
	 		        break; 		   
          	   
               
          	case "play_tts":
          	   $tts_file = '/tts/' . md5("{$data}..en-US_LisaVoice.1");
          	   $ret = "Macro/app-pbx-service,{$action},{$data},{$tenant}";
          	   break;

 	         case "moh":
 	            $moh =  mysql_fetch_assoc( mysql_query("SELECT name FROM t_moh WHERE id = {$data}"));
          	   $ret = "Macro/app-pbx-service,moh,{$moh['name']},{$tenant})";          	   
          	   break;
          	   
          	case "hangup":
          	   $ret = "" ;          	   
          	   break;
          } 
          
       return  $ret ? $ret : "Local/s@general-{$tenant}-error";
}

function get_channel_var( $event, $var_name ){
   GLOBAL $pamiClient;
   
   if( $event['channel'] ){   	
	 $ret = $pamiClient->send(new GetVarAction( $var_name, $event['channel']));
    $t = $ret->getKeys();
    unset($m);
    // Detect tenant by channel name:
    if( $var_name == 'tenant' && !$t['value'] && preg_match("/(general|internal|outbound)-([^-]*)-?.*/", $event['channel'] , $m ) )
      $t['value'] = $m[2];
   }
   
   if($t['response'] == 'Error'){
   	// echo "\t WARNING: " . __FUNCTION__ . "('{$event}','{$var_name}') FAILED: '{$t['message']}' \n";
   	 return '';
   }else{
    return $t['value']?$t['value'] : '';
   } 
}


function GetActiveCalls($pattern=''){
     GLOBAL $pamiClient;
     
     $active_calls = array();
     $tenant_calls = array();
     
     $new_call = array( 'tenant' => '',
     							'campaign_id' => '',
	                     'contexts' => '',
	                     'uniqueid' => '',
	                     'channels' => '');
     
     $response =  $pamiClient->send(new CoreShowChannelsAction()) ;
     $events = (array) $response->getEvents(); 
       
       // return array of events, each event is array of variables // 
      foreach( $events as $key => $value ){
        $call = $value->getKeys();
        
         if ( ( $call['event'] == 'CoreShowChannel' ) ){
         
           $LID = $call['linkedid'];
           if( !array_key_exists($LID, $active_calls))      
             $active_calls[$LID] =  $new_call;
             
           if( $active_calls[$LID]['tenant'] == '' )
           	 $active_calls[$LID]['tenant']  = get_channel_var($call,'tenant'); //get_tenant($call);
           	            
           $active_calls[$LID]['campaign_id']  = get_channel_var($call,'lead_campaign_id');             
           $active_calls[$LID]['contexts']  .= " {$call['context']} ";
           $active_calls[$LID]['uniqueid'] .= ( $LID != $call['uniqueid'] ) ? " {$call['uniqueid']} ":"";
           $active_calls[$LID]['channels']  .= " {$call['channel']} ";
             
         //  echo "CoreShowChannel({$key}): tenant:" . get_channel_var($call,'tenant') . " -- {$LID}   {$call['uniqueid']}  {$call['channel']}  {$call['context']}  \n";
         	         	
           //if ( $call['uniqueid'] ) 
           //  mysql_query("UPDATE cg_numbers set last_result = '({$call['channelstatedesc']}[{$call['channelstate']}])') WHERE num_uniqueid = {$call['uniqueid']} ");           					
         
           if ( $pattern ){
           	 if ( preg_match("/^{$pattern}/",$call['connectedlinenum'], $m ) ) 
               return array('exists' => true, 'message' => " /^{$pattern}/ -> {$call['connectedlinenum']} " );
             else    
               return array('exists' => false, 'message' => "" );
           }
            
          unset($m); 
          if ( preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $call['channel'] , $m ) )                     
            $peers[$m[0]] = $peers[$m[0]] + 1;
        }
          
       //if ( ( $call['event'] == 'CoreShowChannelsComplete' ) ){
       //    echo "\n\n==  {$call['event']}  Items:{$call['listitems']} =======\n\n";
       //} 
          
      }   
      
  return $active_calls;
}

function CallEndEvent($event){
   $camp_id =  get_channel_var($event,'lead_campaign_id');
   
   if($camp_id){
     echo "CAMPAIGN STOP CALL";
     print_r( $event );
	}
}


function CallStartEvent($event, $PEER){
    GLOBAL $conf;
    GLOBAL $counters;  
    GLOBAL $pamiClient;
  //  print_r($event);
      if ( $counters[$PEER]['timer'] ){
        $timer = time() -  $counters[$PEER]['timer'];
      }else{
        $timer = 999;
      }

     echo "    CALL STARTED: {$event['channel']} peer[ {$PEER} ]  Grace period: {$conf[$PEER]['grace_period']}s Timer:{$timer}s ";
     // exec("/stop.sh {$event['channel']} 0 >/dev/null 2>&1 & ");   
      if ( $timer <=  $conf[ $PEER]['grace_period'] ) {
         echo " \033[91m  REJECT CALL \033[0m \n";
         $pamiClient->send(new HangupAction($event['channel'])); 
      }else{
         echo "   ACCEPTED \n";
      }
 
//    if ( $conf[$PEER]['waitforring'] ){
//      echo "       [ \033[91m WaitForRING {$conf[$PEER]['waitforring']}s \033[0m ]";
//      exec("/stop.sh {$event['channel']} $conf[$PEER]['calltrytimer'] waitforring >/dev/null 2>&1 & ");
//    }


}









