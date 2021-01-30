<?php 
ini_set('display_errors', 1);
error_reporting(1);

$SIPCONF = "/etc/asterisk/sip.conf";
$counters = array();

use PAMI\Client\Impl\ClientImpl as PamiClient;  
use PAMI\Message\Event\EventMessage; 
use PAMI\Listener\IEventListener;  
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
use PAMI\Message\Action\SetVarAction;
use PAMI\Message\Action\GetVarAction;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\CoreShowChannelsAction;
use PAMI\Message\Action\MessageSendAction;

require_once("vendor/autoload.php");

$pamiClientOptions = array(
    'log4php.properties' => __DIR__ . '/log4php.properties',    
    'host' => 'localhost',  
    'scheme' => 'tcp://',  
    'port' => 5038,  
    'username' => 'pbx-manager-dev',  
    'secret' => '92jdf3hfdf',  
    'connect_timeout' => 100,  
    'read_timeout' => 10000  
    );

   $link = mysqli_connect("localhost","mpbx_web","PBX_p@ssw0rd", "mpbx");

   $pamiClient = new PamiClient($pamiClientOptions);  
   $pamiClient->open();  


  $pamiClient->registerEventListener(
     function (EventMessage $event) {
	GLOBAL $conf;

        $last_event = $event->getKeys();
        $event_type = $last_event["event"];
     

//        echo " EventType flown: $event_type\r\n";

        preg_match("/SIP\/(.*)\-/", $last_event['channel'] , $m );
        $PEER = $m[1];

        switch (true) {

            case $event_type == 'DialState':
                 DialState($last_event);
                 break;

	    case $event_type == 'VarSet' && $last_event['variable'] == 'SIPCALLID' :
    		 CallStartEvent($last_event,$PEER);		
                 break;

            case $event_type == 'Newchannel':
                 NewChannelEvent( $last_event, $PEER);
		 break;
	 
	    case $event_type == 'DialEnd' :
   	         CallEndEvent($last_event,$PEER);

            default:
                # code...
                break;
        }
      }
  );  



$running = true; 

echo "Go ..!\n";


  while($running) {

    try {
      $pamiClient->process();
    } catch (Exception $e) {
      echo "Throw Exception:" . $e->getMessage() . "\r\n" ;   
    }
     
  }  

  $pamiClient->close();  






function NewChannelEvent($event,$PEER){ 
   GLOBAL $conf;
   //$wfr=$conf[$PEER]['waitforring'];
   echo "     New call  for $PEER  {$event['channel']}  \r\n";
   
}

function CallEndEvent($event, $PEER){
    GLOBAL $conf;
    GLOBAL $counters;
   //  print_r($event);
   echo "  CALL END-- - > > Peer: $PEER; Status: $status \r\n";
}

function CallStartEvent($event, $PEER){
    GLOBAL $link;
    GLOBAL $pamiClient;
   
  //print_r($event);

   $get =  $pamiClient->send( new GetVarAction('CALLERID(num)', $event['channel'] ))->getKeys() ;
   $originalCLI = $get['value'];
   

   //$newCLI = '9999';
   $pamiClient->send( new SetVarAction('CALLERID(num)', $newCLI, $event['channel'] ) );

   $EXTEN  = $pamiClient->send( new GetVarAction('EXTEN', $event['channel'] ));
   echo " Call Start event: {$event['channel']} \r\n";
   echo " CallerID rewrite:  $originalCLI -->  $newCLI for destination: \r\n" ;


}


function DialState($event){
    GLOBAL $conf;
    GLOBAL $counters;
  //  print_r($event);
    preg_match("/SIP\/(.*)\-/", $event['destchannel'] , $m );
    $PEER = $m[1];
    echo " DialState: {$PEER} newState: {$event['dialstatus']}  for {$event['destchannel']} \r\n";

}

     


function send_sms($sip_to,$sms)
{
    GLOBAL $pamiClientOptions;
    GLOBAL $pamiClient;

    $MessageSend = new MessageSendAction($sip_to,$sms);
    $result = $pamiClient->send($MessageSend);
    echo "$sms\r\n";
    print_r($result->getKeys());
    echo "\r\n";
}


