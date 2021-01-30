<?php

// RealTime calls information //
 require_once __DIR__ . '/../vendor/autoload.php';

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
use PAMI\Message\Action\DongleSendSMSAction;
use PAMI\Message\Action\DongleShowDevicesAction;
use PAMI\Message\Action\DongleReloadAction;
use PAMI\Message\Action\DongleStartAction;
use PAMI\Message\Action\DongleRestartAction;
use PAMI\Message\Action\DongleStopAction;
use PAMI\Message\Action\DongleResetAction;
use PAMI\Message\Action\DongleSendUSSDAction;
use PAMI\Message\Action\DongleSendPDUAction;


class A implements IEventListener
{
    public function handle(EventMessage $event)
    {
    	//$PBX->pami->registerEventListener(new A());
        $keys = $event->getkeys();
        foreach ($keys as $key => $value) {
        	echo "Key: {$key}  Val; {$value} <br>";        	
        }
        echo '=============================<br>';
    }
}




class Realtime{

   static function getQueues($qname = null){
     global $PBX;
     try{

     //  $tenant_id = getDatabase()->one("SELECT id FROM tenants WHERE ref_id = '{$t_name}'")['id'];

      // chmod +s /usr/sbin/asterisk  //
      $qname = $qname ? '"'.$qname.'"' : '';
      exec("/usr/sbin/asterisk -irx 'queue show {$qname}' 2>/dev/null |egrep -vi 'Members|Unavailable|ending'|awk -F'W:' '{print($1)}'",  $QCalls_ );      
       if( $qname && preg_match('/has (\d+) calls/' , implode("\n", $QCalls_ ) ,$m) ){
         $calls = $m[1];         
       }
      
       return  array('calls'=> $calls,
                     'stats' => Realtime::bashColorToHtml( preg_replace("/'/","", implode("<br>\n", $QCalls_ ) ) ) 
                    );

      
     }catch (Exception $e) {
        //echo 'PAMI Caught exception: ';
       return $PBX->log("Caught exception:" . $e->getMessage() . "\n" );
      }
   }





  // CONCURRENT CALLS:::: Currently  running Calls data //
  static function getCalls( $sdata = null){         
        global $PBX;
        $json = array();
        $data = array();

        $new_call = array(      
        	             'uniqueid' => '',
	                     'src' => '',
        			     		 'dst' => '',
        			     		 'channel' => '',			     		 
        			     		 'status' => '',	
        			     		 'duration' => 0, 			     		 
        			     		 'actions' => '',
        			     		 '_tenant_id' => '',
        			     		 '_isQueue' => '',
        			     		 '_isIvr' => '',
        			     		 '_dnid' => ''
			       	);

         $dt_fields = array(      
        	             'uniqueid' => '',
	                     'src' => '',
			     		 'dst' => '',
			     		 'channel' => '',
			     		 'status' => '',	                   
			     		 'duration' => 0, 			     	 	 
			     		 'actions' => '' 
			       	);
    try{

        $active_calls = array();
        $PBX->pami->open();                
	      $response =  $PBX->pami->send(new CoreShowChannelsAction()) ;



        $events = (array) $response->getEvents(); 

        foreach( $events as $key => $value ){
            $call = $value->getKeys();                 
            if ( $call['event'] == 'CoreShowChannel' ) {

	          $MASTER_ID = $call['linkedid']; // Master Channel                                            
		     //// GET CHANNEL VARIABLES                   

                        $isQueue =    $PBX->pami->send(new GetVarAction('QUEUE',          $call['channel'] ) )->getKeys()['value'];
                      	$isIvr =      $PBX->pami->send(new GetVarAction('IVR',            $call['channel'] ) )->getKeys()['value'];
                        $MYID =       $PBX->pami->send(new GetVarAction('MYID',           $call['channel'] ) )->getKeys()['value'];	
                        $callerid =   $PBX->pami->send(new GetVarAction('CDR(clid)',      $call['channel'] ) )->getKeys()['value'];
			$app =        $PBX->pami->send(new GetVarAction('CDR(lastapp)',   $call['channel'] ) )->getKeys()['value'];
			$appData =    $PBX->pami->send(new GetVarAction('CDR(appdata)',   $call['channel'] ) )->getKeys()['value'];
                        $dnid =       $PBX->pami->send(new GetVarAction('CDR(dnid)',      $call['channel'] ) )->getKeys()['value'];
                        $DID =        $PBX->pami->send(new GetVarAction('INBOUND_DID',    $call['channel'] ) )->getKeys()['value'];	
                        $dir =        $PBX->pami->send(new GetVarAction('DIRECTION',      $call['channel'] ) )->getKeys()['value'];
			$dstchan =    $PBX->pami->send(new GetVarAction('CDR(dstchannel)',$call['channel'] ) )->getKeys()['value'];

		     if( !isset($active_calls[$MASTER_ID]) )
                        $active_calls[$MASTER_ID]                 = $new_call;	

                    // MAIN Channel - take initial variables
                    if( $call['uniqueid'] == $MASTER_ID ){                     	
                     	$active_calls[$MASTER_ID]['channel']      = $call['channel'];
                        $active_calls[$MASTER_ID]['dst']          = $dnid ? $dnid  : $call['exten'];

                        $active_calls[$MASTER_ID]['src']          = $call['calleridnum'] ;   

                        $active_calls[$MASTER_ID]['app']          = $call['application'];                        
	                $active_calls[$MASTER_ID]['duration']     = $call['duration'];
	                $active_calls[$MASTER_ID]['status']       = Realtime::ChanStatus($call['channel']) ; 
	                //$active_calls[$MASTER_ID]['status'] = '';
                    }

		        $active_calls[$MASTER_ID]['_dnid']       = $dnid;      
          	    	$active_calls[$MASTER_ID]['_isQueue'] = $isQueue;
	                $active_calls[$MASTER_ID]['_isIvr'] = $isIvr;	                    
	                $active_calls[$MASTER_ID]['_DID'] = $DID; 
                   	    
		           if( !$active_calls[$MASTER_ID]['_tenant_id'] ){
                                  $tenant_id =  $PBX->pami->send(new GetVarAction('CDR(tenant_id)', $call['channel'] ) )->getKeys()['value'];	
		                  $t_name = $PBX->pami->send(new GetVarAction('tenant', $call['channel'] ) )->getKeys()['value'];
		                  if(!$t_name){
		                     $cname = $PBX->pami->send(new GetVarAction('CDR(dcontext)', $call['channel'] ) )->getKeys()['value'];
                                 $t_name = explode('-',$cname)[1];
                      }
		      if(!$tenant_id)
                   	 $tenant_id = getDatabase()->one("SELECT id FROM tenants WHERE ref_id = '{$t_name}'")['id'];

                      $active_calls[$MASTER_ID]['_tenant_id'] =  $tenant_id;                     
	   	                $active_calls[$MASTER_ID]['_tenant_name'] = $t_name;
		             }

		      //$active_calls[$MASTER_ID]['_tenant_id'] = $PBX->pami->send(new GetVarAction('CDR(dcontext)',         $call['channel'] ) )->getKeys()['value'];


                    $hasTAGS =    $PBX->pami->send(new GetVarAction('X-CRM-TAGS',     $call['channel'] ) )->getKeys()['value'];
                    if( $hasTAGS && is_array(json_decode($hasTAGS, true)) ){
                       $TAGs =  json_decode($hasTAGS, true);            
                       $active_calls[$MASTER_ID]['_tags'] = $TAGs;
                    }

                    

                    
                 /// Resolving DST SIP Channel :                        
           	if( preg_match('/^SIP/', $dstchan ) && !$active_calls[$MASTER_ID]['_toPeer'] ){
               	    $EP = implode('-', explode('-', preg_replace('/SIP\//','', $dstchan), -1 ) );
               	    // Inernal call - to local user:
                    $toLocation = getDatabase()->one("SELECT concat(sip.extension,' ', sip.first_name) as sip_name
	                                                   FROM t_sip_users sip
	                                                    LEFT JOIN admin_users au ON au.default_tenant_id = 0{$tenant_id} AND au.sip_user_id = sip.id
	                                                   WHERE tenant_id = 0{$tenant_id} AND 
	                                                         ( sip.name = '{$EP}' OR sip.extension = '{$EP}') ")['sip_name'];
                    // Call to Peer name
                   if(!$toLocation)	                        
	                          $toLocation = getDatabase()->one("SELECT ifnull(description,name) as tname 
								  FROM trunks  WHERE name = '{$EP}'")['tname'];

		    if($toLocation)	
 	                      $active_calls[$MASTER_ID]['_toPeer'] =    $toLocation ;
	                        
		   }         

		    // Under question
		    if(!$active_calls[$MASTER_ID]['_toPeer'])
			 $active_calls[$MASTER_ID]['_toPeer'] = $appData;    
			    
		     

		       

                     //if( preg_match('/Local/(.*)@(.*)/', $dstchan, $match ) && !$active_calls[$MASTER_ID]['_toPeer']){ 
                     //   $active_calls[$MASTER_ID]['_toPeer'] = $match[1].'@'.$match[2] ;
                    // }  

				                  

                     ///Correct FROM Resolving BY MYID (internal call):				        
                     if( !$active_calls[$MASTER_ID]['_fromPhone'] && !$active_calls[$MASTER_ID]['_fromUser'] ) {
                       //$active_calls[$MASTER_ID]['src']  = $MYID .'1';
		                    if(  preg_match('/^(.*)-([0-9]*)/', $MYID, $m ) || 
                             preg_match('/^(.*)-([0-9]*)/', $active_calls[$MASTER_ID]['src'], $m )  ){

                          

                         //$active_calls[$MASTER_ID]['src']  =  $tenant_id;
	                        $fromUser = getDatabase()->one("SELECT concat(sip.extension,' ', sip.first_name) as sip_name
	                                                   FROM t_sip_users sip
	                                                    LEFT JOIN admin_users au ON au.default_tenant_id = 0{$tenant_id} AND  
	                                                                                au.sip_user_id = sip.id
	                                                   WHERE tenant_id = 0{$tenant_id} AND 
	                                                         ( sip.name = '{$MYID}' OR sip.extension = '{$m[2]}') ")['sip_name'];

	                         $active_calls[$MASTER_ID]['_fromUser'] = $fromUser ;
                           $active_calls[$MASTER_ID]['src'] = $fromUser ;
                				}else{
                				   $active_calls[$MASTER_ID]['_fromPhone'] = $callerid ;
                				}

                     }
		 		        
                   $active_calls[$MASTER_ID]['_toUser'] =  $call['dchannel'];
	                  
                     
                   //foreach($call as $s=>$c) 
                   //  	 echo $s .'->'.$c.'<br>';
                   //  	echo '------------------------<br>';
	           // Append Secondary Data to MASTER Channel                         
	                    
            }
        }


	$PBX->pami->close();                
	unset($call);

        // Parse & format Data array of calls TO  output for DataTable//  
        foreach ($active_calls as $ID => $call) {                    	
        	if( !isset( $_SESSION['CRM_user']['default_tenant_id'] ) || 
                 ( isset( $_SESSION['CRM_user']['default_tenant_id'] ) && $_SESSION['CRM_user']['default_tenant_id'] == $call['_tenant_id']  )) {        	 

              // Format Variables  //
        		
              $queue_name = isset($call['_isQueue']) ? "<span class='badge badge-info p-2'><i class='mdi mdi-account-group'></i> {$call['_isQueue']}</span>":'';
              $ivr_name   = isset($call['_isIvr'])   ? "<span class='badge badge-warning p-1'><i class='mdi mdi-account-group'></i> {$call['_isIvr']}</span>":'';
	            $from = '';
              if( isset($call['_fromUser']) ) 
                 $from = "<span class='badge badge-info p-1'><i class='mdi mdi-headset'></i>{$call['_fromUser']}</span>";	
              elseif( isset($call['_fromPhone']) )
                 $from = "<span class='badge badge-info p-1'><i class='mdi mdi-card'></i>{$call['_fromPhone']}</span>";

              $toPeer   = isset($call['_toPeer']) ? "<span class='text-primary'><i class='mdi mdi-server'></i>{$call['_toPeer']}</span>":'';
              $toUser   = isset($call['_toUser'])  ? "<span class='text-success'><i class='mdi mdi-headset'></i>{$call['_toUser']}</span>":'';
              
              
              if($call['_status'])
                  $call['_status'] = "<span class='badge badge-info p-2 '>{$call['_status']}</span>";
            
               // Try to Guess SERVICE  DESTINATION Number ( exten: s )
               if( $call['dst'] == 's' ){ 
               	  if( preg_match('/Echo/', $call['app'] )) {                 	                 	
                   	$dst_name = "<i class=\"  mdi mdi-surround-sound\"></i> "._l("Эхо тест",true) ." </i>";
                  }elseIf( preg_match('/BackGround/', $call['app'] )) {
                    $dst_name = "<i class='mdi mdi-music' title='Play media'> Playback ";                     
                  }
               }


              $call['src'] =  preg_replace('/^\+?38/', '', $call['src']);
              $call['dst'] =  preg_replace('/^\+?38/', '', $call['dst']);

           // Show TAGS //                             
              if( isset($call['_tags']['url']) ){
                $tagged_num = preg_replace('/^38/','',$call['_tags']['num']);
                $tags = Realtime::parseTags($call['_tags']);

                $call['src'] = ( $call['src'] == $tagged_num) ? $tags : $from;
                $call['dst'] = ( $call['dst'] == $tagged_num) ? $tags : $call['dst'];
              }else{
              	$call['src'] = $from ;
              }  
              
              //$call['channel'] = $ivr_name . $queue_name . $dst_peer . $user_dst . $user_src ;                              
              //$call['channel'] = $ivr_name . $queue_name . $dst_peer . $user_dst . $user_src ;                              

              $call['channel'] = $toPeer.$toUser;

	      $data[] = array_values( array_intersect_key($call, $dt_fields) );

           }
        }

      }catch (Exception $e) {
        //echo 'PAMI Caught exception: ';
	      $PBX->log("PAMI Caught exception:" . $e->getMessage() . "\n" );
	      echo 'FAILED';

      }

 //       if(isset($data[0]) && count($data[0]) < 6 )
//		 var_dump($data[0]

        $json['data'] = $data  ;
        $json['draw'] = $_GET['draw'] ? $_GET['draw'] : 1;        
        $json['recordsTotal'] = count($data);
        $json['recordsFiltered'] = count($data);
                 
	//     echo (count($json) == 1) ? json_encode($json[0]) :       
	    echo json_encode($json);

      }

	static function GetPeer($PEER){
	   GLOBAL $PBX;
	     $response =  $$PBX->ami->send(new SIPPeersAction()) ;
	     $events = (array) $response->getEvents();
	     foreach( $events as $key => $value ){
	       $peer = $value->getKeys();
	        if( ( $peer['event'] == 'PeerEntry' ) && $peer['objectname'] == $PEER )
	         return $peer;
	    }
	}

   static function bashColorToHtml($string) {
                $colors = [
              '/[[:^print:]]/us' => '',
              '/\;40m/' => 'm',              
              '/\[0;30m(.*?)\[0m/s' => '<span class="black">$1</span>',
              '/\[0;31m(.*?)\[0m/s' => '<span class="red">$1</span>',
              '/\[0;32m(.*?)\[0m/s' => '<span class="green">$1</span>',
              '/\[0;33m(.*?)\[0m/s' => '<span class="brown">$1</span>',
              '/\[0;34m(.*?)\[0m/s' => '<span class="blue">$1</span>',
              '/\[0;35m(.*?)\[0m/s' => '<span class="purple">$1</span>',
              '/\[0;36m(.*?)\[0m/s' => '<span class="cyan">$1</span>',
              '/\[0;37m(.*?)\[0m/s' => '<span class="light-gray">$1</span>',
              '/\[1;30m(.*?)\[0m/s' => '<span class="dark-gray">$1</span>',
              '/\[1;31m(.*?)\[0m/s' => '<span class="light-red">$1</span>',
              '/\[1;32m(.*?)\[0m/s' => '<span class="light-green">$1</span>',
              '/\[1;33m(.*?)\[0m/s' => '<span class="yellow">$1</span>',
              '/\[1;34m(.*?)\[0m/s' => '<span class="light-blue">$1</span>',
              '/\[1;35m(.*?)\[0m/s' => '<span class="light-purple">$1</span>',
              '/\[1;36m(.*?)\[0m/s' => '<span class="light-cyan">$1</span>',
              '/\[1;37m(.*?)\[0m/s' => '<span class="white">$1</span>',
              '/\[1;30m/s' => '<span style="color:black">',
              '/\[1;31m/s' => '<span style="color:red">',
              '/\[1;32;40m/s' => '<span style="color:green">',
              '/\[1;33m/s' => '<span style="color:yellow">',
              '/\[1;34m/s' => '<span style="color:blue">',
              '/\[1;35m/s' => '<span style="color:purple">',
              '/\[1;36m/s' => '<span style="color:cyan">',
              '/\[1;37m/s' => '<span style="color:white">',
              '/\[0m/s'   => ' ',
              '/In use/s' => '<span style="color:orange;font-weight:bold;">Занят</span>',
              '/Busy/s' => '<span style="color:red;font-weight:bold;">Занят</span>',
              '/\(ringinuse disabled\)/' => '',
              '/in call/s' => '<span style="color:lime;font-weight:bold;">Отвечает</span>',
              '/Not in use/s' => '<span style="color:#00b6ff">Свободен</span>',
              '/ SIP\//s' => '&nbsp;&nbsp;&nbsp; SIP/',
              '/paused was/' => '<span style="color:yellow">' . _l("На паузе",true) . '</span>',
              '/has taken no calls yet/' => '',
              '/\(realtime\)/' => '',
              '/\(\ds holdtime, \ds talktime\)/' => '',              
              '/has \d+ calls \(max \d+\)/' => '',
              '/with penalty /' => 'p:',
              '/has taken \d+ calls \(last was \d+ secs ago\)/' => '',
              '/No Callers/' => '<span style="color:silver">'._l('(нет звонков)',true) .'</span>',
              '/in \w+ strategy\s+,/' => ''              

          ];

      return preg_replace(array_keys($colors), $colors, $string);
      // return $string;

    }



   static function ChanStatus($channel){
   	 global $PBX;
   	 $STATES = array( 
				0 => _l("Свободна",true),
				1 => _l("Резерв",true),
				2 => _l("Гудок",true),
				3 => _l("Набор",true),
				4 => _l("Вызов...",true),
				5 => _l("Звонит",true),
				6 => _l("Разговор",true),
				7 => _l("Занято",true)
			);   

   	 $StatusEvents = (array) $PBX->pami->send(new StatusAction($channel))->getEvents();
      foreach( $StatusEvents as $StatusEvent => $StatusData ){
        $SKeys = $StatusData->getKeys();                 
         if($SKeys['event'] == 'Status' )
          return $STATES[ $SKeys['channelstate'] ] ? $STATES[ $SKeys['channelstate'] ] : $SKeys['channelstate'];  
      }    
   }





	static public function parseTags($tags)
	{
		  // Pre-Format  Tags to HTML: //
                       
      	    $tags['num'] = preg_replace('/^38/','',$tags['num']);
            $balance = isset($tags['balance'])?round($tags['balance'],2):0;                       
            $bal_class  = ($balance < 0)? 'text-danger' : 'text-success';
      	    //$B['uid'] = ( !$B['uid'] && preg_match('/\?id=(\d+)/', $B['link'], $m ) ) ? $m[1] : '' ;
            switch( true ){
              case ( (int)$tags['astatus']  === -1  ):  // No billing account, not yet connected client 
                  $type_class =  ' mdi-account-outline bordered' ;                             

                  $type_color = 'text-muted '; 
                  $title = " {$tags['num']}, НЕПОДКЛЮЧЕН, ID:{$tags['uid']} \n{$tags['comments']}\n ${tags['actual_address']} ";
                  break;
                ;;
              case ( (int)$tags['astatus']  === 0  ):  // ACTIVE Account, non blocked                               
                  $type_class =  ' mdi-account-card-details ' ;
                  $type_color = ( (int)$tags['istatus']  === 1) ? 'text-primary ' :'text-muted ' ; // Internet Blocked only
                  
                  $title = " {$tags['num']}, АКТИВНЫЙ, {$balance}грн, ID:{$tags['uid']} \n{$tags['comments']}\n ${tags['actual_address']} "; 
                  break;
                ;;  
              case ( (int)$tags['astatus']  === 1  ):  // BLOCKED  Account!
                  $type_class =  ' mdi-account-card-details ' ;
                  $type_color = 'text-danger '; // Account Blocked  totally
                  $title =" {$tags['num']}, БЛОКИРОВКА, {$balance}грн, ID:{$tags['uid']} \n{$tags['comments']}\n ${tags['actual_address']} ";
                  break;
                ;;
            }

            $REGION_INFO = (isset($tags['firm']) || isset($tags['district']) ) ? "<span class='float-right text-muted'><i class='fa fa-map-marker'></i> {$tags['firm']}, {$tags['district']} </span>"  : '';

             if( !$REGION_INFO && isset($tags['actual_address']) ){                                                    
              $actual_addr = preg_replace('/ул. |\'|\"/','', $tags['actual_address']);                          
              $REGION_INFO = "<span class='float-right text-muted location-label' title='{$actual_addr}'><i class='fa fa-map-marker'></i> {$actual_addr} </span>";
             }

             $titled_url = preg_replace("/<a /","<a title='{$title}'", $tags['url'] );                          
             $tagged_info = "<b>{$tags['num']}</b> <i class='mdi {$type_class} {$type_color}' ></i> " . $titled_url . "<i class='mdi mdi-open-in-new text-primary'></i>,<span class='tags-bal {$bal_class}'>{$balance}грн</span> " . $REGION_INFO;

             return $tagged_info;

    	
	}



}

/*
===========
event:Status
privilege:Call
channel:SIP/SOHO-206-00000abc
channelstate:4
channelstatedesc:Ring
calleridnum:SOHO-206
calleridname:
connectedlinenum:
connectedlinename:
language:ru
accountcode:
context:internal-SOHO
exten:112233
priority:14
uniqueid:1588173682.4932
linkedid:1588173682.4932
type:SIP
dnid:112233
effectiveconnectedlinenum:
effectiveconnectedlinename:
timetohangup:0
bridgeid:
application:Dial
data:SIP/112233112233@Asterisk2,60,u
nativeformats:(ulaw)
readformat:ulaw
readtrans:
writeformat:ulaw
writetrans:
callgroup:0
pickupgroup:0
seconds:35
actionid:1588173717.6322
event:StatusComplete
actionid:1588173717.6322
eventlist:Complete
listitems:1
items:1
===========
event:Status
privilege:Call
channel:SIP/Asterisk2-00000abd
channelstate:5
channelstatedesc:Ringing
calleridnum:112233
calleridname:
connectedlinenum:SOHO-206
connectedlinename:
language:en
accountcode:
context:from-pstn
exten:112233
priority:1
uniqueid:1588173682.4933
linkedid:1588173682.4932
type:SIP
dnid:
effectiveconnectedlinenum:SOHO-206
effectiveconnectedlinename:
timetohangup:0
bridgeid:
application:AppDial
data:(Outgoing Line)
nativeformats:(ulaw)
readformat:ulaw
readtrans:
writeformat:ulaw
writetrans:
callgroup:0
pickupgroup:0
seconds:34
actionid:1588173717.6493
event:StatusComplete
actionid:1588173717.6493
eventlist:Complete
*/




?>
