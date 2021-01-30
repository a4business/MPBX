<?php
// Asterisk Event Listener - AstListen
ini_set('display_errors', 0); 
error_reporting(0);

require_once(__DIR__ ."/vendor/autoload.php");
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

//use PAMI\Client\Impl\ClientImpl;
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



 class AMI{ 	
 
 	 public function __construct($conf){ 	 	  
         try{
				   $this->pamiClient = new PamiClient( $conf->getPamiOptions() );
				   $this->pamiClient->open();
				 } catch (Exception $e) {
				  	  echo ' ERROR: Caught exception: ',  $e->getMessage(), "\n";
				 }  
	  }													
														
    public  function __destruct() {
      	// print "Destroying " . __CLASS__ . "\n";
      	$this->pamiClient->close();
     }														 
	
	public function GetQueues($tenantID = ''){
		/*    Member status:     */
		$mstatus = array(
			1 => 'Not in Use',
			2 => 'In Use',
			3 => 'Busy',
			4 =>'',
			5 => 'Unavailable',
			6 => 'Ringing'
		); 

		 $queues = array();
		 $tenant_queues = array();		
		 $res = mysql_query("SELECT name FROM t_queues WHERE tenant_id = {$tenantID}");		 
		 while($r = mysql_fetch_assoc($res)) 
		   $tenant_queues[] = $r['name'];
		   
		 if(count($tenant_queues)){  
		          $r =         $this->pamiClient->send(new CommandAction('queue show'));  // This updates Queuestatus listing 
				    $response =  $this->pamiClient->send(new QueueStatusAction()) ;
					 $events = (array) $response->getEvents();
					  foreach( $events as $key => $value ){
			          $queue = $value->getKeys();
			          if( in_array($queue['queue'], $tenant_queues)){
			           if ( $queue['event'] == 'QueueParams' )           	
			           	 $queues[$queue['queue']] = $queue;            
			           if ( $queue['event'] == 'QueueMember' ){
			           	 $queue['status'] = $mstatus[$queue['status']];
			           	 $queues[$queue['queue']]['members'][] = $queue;
			           }	 
			         
			          }   
			        }             
			        return $queues;
		  }    
      

	}
  
    public function GetActiveCalls($tenant=''){
    
     
     $active_calls = array();
     $tenant_calls = array();
     
     $new_call = array(      
	                     'callerid' => '',
			               'dialed_number' => '',
	                     'uniqueid' => '',
	                     'channels' => '',
			               'status' => '',
                        'channel' => '',
			               'duration' => 0 
			             );
     
     $response =  $this->pamiClient->send(new CoreShowChannelsAction()) ;
     $events = (array) $response->getEvents();
    
     foreach( $events as $key => $value ){
        $call = $value->getKeys();            
        if (  $call['event'] == 'CoreShowChannel'  ){
//             if ( $tenant != '' && $this->get_var('tenant',$call['uniqueid']) != $tenant ) 
//               continue;
     
             $LID = isset($call['linkedid']) ? $call['linkedid'] : $call['uniqueid'];
          // if( !array_key_exists($LID, $active_calls ) ) {    
             $active_calls[$LID] =  $new_call;
             $active_calls[$LID]['uniqueid']  .=  " {$call['uniqueid']} ";
             $active_calls[$LID]['channels']  .= " {$call['channel']} ";
             $active_calls[$LID]['context']  .= $call['context'] . ' ';
             
             $active_calls[$LID]['channel']  =   $active_calls[$LID]['channel']  ?  $active_calls[$LID]['channel']  : $call['channel'];
             $active_calls[$LID]['duration'] = $call['duration'];
	          $active_calls[$LID]['dialed_number'] = $call['exten']? $call['exten'] :$this->get_var('did',$call['uniqueid']);
	          $active_calls[$LID]['callerid'] =  $call['calleridnum']  ;
             $active_calls[$LID]['status']  = str_replace('Down','Ringing' ,str_replace('Up','Connected',$call['channelstatedesc']));
             preg_match("/(PJSIP|IAX|SIP|Local)\/(.*)\-/", $call['channel'] , $m );
             $PEER = isset($m[2])? $m[2] : '';
             $peer_info = $PEER ?  $this->GetPeer($PEER) : array('ipaddress' => '' );
             $active_calls[$LID]['ipaddress'] = $peer_info['ipaddress'];
             
           // get more info from Channel 
             $msg = $this->pamiClient->send(new CommandAction('core show channel '.$call['channel']) );
             $active_calls[$LID]['data'] = $msg->getKeys();
             foreach($active_calls[$LID]['data']  as $k=>$lines)
              if(preg_match('/-- general --/',$k)){
      	       foreach(split("\n",$lines) as $line){      	 
     	 	         list($var,$val) = split( "=|: ", $line );
     	 	         $active_calls[$LID][trim($var)] = $active_calls[$LID][trim($var)] ?  $active_calls[$LID][trim($var)] : $val;
      	       }
             }
             
           // get more info from SIP 
   	      $sip_chan = $this->pamiClient->send(new CommandAction('sip show channel '.$calls[$key]['SIPCALLID'] ) );
            $active_calls[$LID]['sip_data'] = $sip_chan->getKeys(); 
            foreach($active_calls[$LID]['sip_data']  as $sip_key=>$sip_val){   
             if(preg_match('/sip call/',$sip_key)){       
               foreach(split("\n",$sip_val) as $sip_line){  	 
     	 	        list($svar,$sval) = split( ":|\t", $sip_line,2 );
     	 	        $calls[$key]['sip_'.trim($svar)] = $sval;
     	 	        // echo " $svar ------> $sval <br>";
     	 	      } 
     	 	     }   
            }
        // Active call recorded// 
        //var_dump($call);
        }
        unset($call);
            
   } // CoreShowChannels parced
  return $active_calls;
}
 

public  function GetPeer($PEER){
  
     $response =  $this->pamiClient->send(new SIPPeersAction()) ;
     $events = (array) $response->getEvents();
     foreach( $events as $key => $value ){
       $peer = $value->getKeys();
        if( ( $peer['event'] == 'PeerEntry' ) && $peer['objectname'] == $PEER )
         return $peer;
    }
}

public function getCallStatus($extension='') {
	$chan_states = array( 
			0 => 'Available',
			1 => 'Reserved',
			2 => 'Off hook',
			3 => 'Dialing',
			4 => 'Ringing',
			5 => 'Remote end is ringing',
			6 => 'Up',
			7 => 'Busy'
 );
	   if($extension){
		  $response =  $this->pamiClient->send(new StatusAction()) ;
	     $events = (array) $response->getEvents();
	     //print_r($events);
	     foreach( $events as $key => $value ){
		   $status = $value->getKeys();
		   if( $status['privilege'] == 'Call' && preg_match("/SIP\/{$extension}/",$status['channel'])  ){
		   	$t = $status['channelstate']; 
		   	$status['channelstate'] = $chan_states[$t];
		      return $status;
		   }   
		  }
		}     
}

public function getExtensions(){
	session_start();
	$tenant_id = $_SESSION['tenantid'];
	$ext_array = array();
	if($tenant_id){
		$res = mysql_query("SELECT  *,
		                   (CASE  WHEN IFNULL(t_sip_users.ipaddr,'') = '' THEN 'OFFLINE'
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) > 0) THEN 'ONLINE'
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) < 10) THEN 'OFFLINE' 
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) < 5) THEN concat('EXPIRED[',regseconds - UNIX_TIMESTAMP(),'ms]')
                         END) AS chan_reg_status,
                         (CASE  WHEN IFNULL(t_sip_users.ipaddr,'') = '' THEN 'inverse'
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) > 0) THEN 'success'
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) < 10) THEN 'inverse' 
                                WHEN ( (regseconds - UNIX_TIMESTAMP() ) < 5) THEN 'warning'
                         END) AS chan_reg_class
                         FROM t_sip_users, t_user_options 
                          WHERE t_sip_users.tenant_id = {$tenant_id} AND
                             t_sip_users.id  = t_user_options.t_sip_user_id ");
		if( $res )	  
		  while($row = mysql_fetch_assoc($res)){
		    $row['data'] = $this->getCallStatus($row['name']);
		    $ext_array[] = $row;
		  }  
		    
		return $ext_array;
	}
	
}


public function get_var( $var_name, $chan ){
  
   
   if( $chan ){   	
	 $ret = $this->pamiClient->send(new GetVarAction($var_name, $chan ));
    $t = $ret->getKeys();
    unset($m);  
    if( !isset($t['value'])  && preg_match("/(general|internal|outbound)-([^-]*)-?.*/", $chan , $m ) )
      $t['value'] = $m[2];
   }
   return isset($t['value'])?$t['value'] : '';
}



}
?>
