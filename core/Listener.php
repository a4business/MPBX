<?php 


//ini_set('display_errors', 1);
//error_reporting(1);
require_once("vendor/autoload.php");
include_once(dirname( __DIR__)  . '/include/config.php');

use PAMI\Client\Impl\ClientImpl as PamiClient;
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


 //$conf =  file_exists( '/etc/asterisk/sip.conf' ) ? parse_ini_file( '/etc/asterisk/sip.conf', true, INI_SCANNER_RAW ) : false;
  
 
 $pamiClient = new PamiClient( $config->getPamiOptions() );
 $pamiClient->open();
 
 
 $pamiClient->registerEventListener(
    function (EventMessage $event) {
	    GLOBAL $conf;
       $last_event = $event->getKeys(); 
      //  var_dump($last_event);       
       $event_type = $last_event["event"];
       if ( preg_match("/SIP\/(.*)\-/", $last_event['channel'] , $m ) )
          $PEER_NAME = $m[1];

       switch (true) {	     
	      case $event_type == 'VarSet' && $last_event['variable'] == 'SIPCALLID' :
	          // New call - created new SIPCALLID
		       //CallStartEvent($last_event, $PEER_NAME);
            break;
            
         case $event_type == 'Newchannel':
             // New channel //
             var_dump( $last_event );
             //NewChannelEvent( $last_event, $PEER);
		     break;
	      case $event_type == 'Hangup' :
	         // Call END //
	         //CallEndEvent($last_event,$PEER);
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
     
      $active_calls = getActiveCalls();
      $peer_ip = '192.168.1.1';             
      $peer_calls = $active_calls[$peer_ip] ? $active_calls[$peer_ip] : 0;
      echo "  Tenant [ sncd ]  PEER: [ $peer_ip ],  Limit:[ 99 ] Running:[ {$peer_calls} ] \n";
      sleep(3);
    }catch (Exception $e){
      echo 'Error...'; 
    }
  }  

$pamiClient->close();  


function NewCall( $ENDPOINT, $phone, $duration, $campaign_id, $num_id ) {
    GLOBAL $pamiClient;

       $actionid = md5(uniqid());
//   $response = $pamiClient->send(new StatusAction());
        $originateMsg = new OriginateAction( 'Local/' . $phone . '@' . $ENDPOINT  );
        $originateMsg->setContext('context');
	     $originateMsg->setApplication('Dial');
        $originateMsg->setData("Local/{$phone}@context,60,L({$duration})");
        $originateMsg->setPriority('1');
        $originateMsg->setVariable('__campaign_id', $campaign_id );
        $originateMsg->setVariable('__num_id', $num_id );
       // $originateMsg->setVariable('CDR_PROP(disable)', 1 );
        $originateMsg->setVariable('__dest_phone', $phone );
        $originateMsg->setExtension( $phone );
        $originateMsg->setCallerId( $phone );
        $originateMsg->setAsync(true);
        $originateMsg->setActionID($actionid);
        $orgresp = $pamiClient->send($originateMsg);        
        return  $orgresp->getKeys();
}


function NewChannelEvent($event,$PEER){ 

   GLOBAL $conf;
   echo "     New channel  $PEER  {$event['channel']}  peer timer:" . $counters[$PEER]['timer'] .";\n";
   
}


function GetActiveCalls($pattern=''){
    GLOBAL $pamiClient;
    $peers = array();
    
     $response =  $pamiClient->send(new CoreShowChannelsAction()) ;
     $events = (array) $response->getEvents();

      foreach( $events as $key => $value ){
        $call = $value->getKeys();
       //var_dump($call); 
         if ( ( $call['event'] == 'CoreShowChannel' ) ){
         	         	
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
      }   
  return $peers;

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

     echo "    CALL START: {$event['channel']} peer[ {$PEER} ]  Grace period: {$conf[$PEER]['grace_period']}s Timer:{$timer}s ";
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





