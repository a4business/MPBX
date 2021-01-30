<?php

 include_once(dirname( __DIR__)  . '/include/config.php');
 
 function gen_queues_context() {
	  
	$res = mysql_query("SELECT *,
                             t_queues.id as queue_id,
		             tenants.id as tenantID,
                             t_queues.name as queue_name  
	                    FROM t_queues
                                 LEFT JOIN feature_codes as fc ON trim(fc.appdata) =  trim(t_queues.context_script),
                           tenants 
                      WHERE tenants.id = t_queues.tenant_id  
                      ORDER BY t_queues.id  ");
   if (!$res) die(mysql_error()."\n");
   $items = array();

   
	while($row = mysql_fetch_assoc($res)){
    $_tenant = $row['ref_id'];
    $_tts_voice = $row['default_tts_lang'];
 
// Add Direct number for this queue is it was set and ineger, and not exists  
    if( isset($row['qlabel']) && $row['qlabel'] &&  (int)$row['qlabel'] == $row['qlabel']  ) {
      $items[] = "\n\n[internal-{$_tenant}-pbx]";
      $items[] = "\n exten => {$row['qlabel']},1,Goto(internal-{$_tenant}-queue-{$row['queue_id']},s,1)";
    }

    $items[] = "\n\n[internal-{$_tenant}-queue-{$row['queue_id']}]";
    $items[] = "\n exten => s,1,Verbose(4,Starting Queue {$row['name']})";      
    $items[] = " exten => s,n,Answer()";   // Answer is important in order not to have ringing in the background of your hold music.z

    $items[] = " exten => s,n,Set(CHANNEL(language)=ru)";
    $items[] = " exten => s,n,Set(CDR(service_status)={$_tenant}-queue-{$row['queue_id']})"  ;
    $items[] = " exten => s,n,Set(CDR(direction)=INBOUND)"  ;
    $items[] = " exten => s,n,Set(CDR(tenant_id)={$row['tenantID']})";
    $items[] = " exten => s,n,Set(CDR(userfield)={$_tenant}::queue-{$row['queue_id']}::{$row['queue_name']})"  ;
    $items[] = " exten => s,n,Set(CDR(tags)=${X-CRM-TAGS})" ;     // Copy Tags into current CDR leg ()
    $items[] = " exten => s,n,ExecIf(\$[ \"\${INBOUND_DID}\" != \"\" ]?Set(CDR(INBOUND_DID)=\${INBOUND_DID}))";
    $items[] = " exten => s,n,Set(__FWD_KEEP_CID=1)";
    $items[] = " exten => s,n,Set(__QUEUE={$row['queue_name']})";
    $items[] = " exten => s,n,Set(__QUEUE_REC_ID=\${UNIQUEID})\n";
    $items[] = " exten => s,n,Set(__CWIGNORE=TRUE)";
    $items[] = " exten => s,n,Set(__CFIGNORE=TRUE)";
    //$items[] = " exten => s,n,Set(__FORWARD_CONTEXT=block-cf)";


    if($row['context_script']){
        $items[] = "  ;;;   Context Script execution '{$row['app']}' {$row['context_script']} ;;;;; \n";
        if( $row['app'] == 'AGI' )
         $items[] =  "  exten => s,n,AGI({$row['context_script']})\n";

        if( $row['app'] == 'Gosub' )
         $items[] =  "  exten => s,n,Gosub({$row['context_script']})\n";
      }


    if( isset($row['queue_welcome'] ))   
        $items[] = " exten => s,n,Playback(snd_{$_tenant}/".preg_replace('/&/',"&snd_{$_tenant}/",$row['queue_welcome']) .")" ;
      
    if(isset($row['queue_musiconhold']))
	      $items[] = " exten => s,n,Set(CHANNEL(musicclass)={$row['queue_musiconhold']})";

    if(isset($row['queue_calltag']) && trim($row['queue_calltag']) != '' )
        $items[] = " exten => s,n,Set(CALLERID(name)=[{$row['queue_calltag']}] \${CALLERID(name)})";


  /// START OF THE CALL QUEUE HERE /// 
  		$items[] = " exten => s,n,Wait(1)";
		$items[] = " exten => s,n,Queue({$row['queue_name']},IiKtkwx,,,{$row['timeout']},,,app-recording)" ;
    //$items[] = " exten => s,n,Queue({$row['queue_name']},twkx,,,,,,app-recording)" ;

		$items[] = " exten => s,n,ExecIf($['\${ABANDONED}'='TRUE']?Set(Q_STATUS=ABANDONED))";
		$items[] = " exten => s,n,ExecIf($['\${MEMBERNAME}'!='']?Set(Q_STATUS=ANSWERED))";
		$items[] = " exten => s,n,Set(CDR(service_status)={$_tenant}-queue-{$row['queue_id']}:\${Q_STATUS})"  ;
		$items[] = " exten => s,n,Verbose(3,    NO-ANSWER EVENT IN QUEUE {$_tenant}-queue-{$row['queue_id']} )" ;

		 $items[] = "\n\n  ;; if we are back - then we are failed?";
              $items[] =  "  exten => s,n,Verbose(2,[ Queue Status: \${DIALSTATUS} ]) " ;
              $items[] =  '  exten => s,n,Goto(dial-${DIALSTATUS},1)';              
              $items[] =  "  exten => dial-CHANUNAVAIL,1,Goto(default,1)" ;
              $items[] =  "  exten => dial-CONGESTION,1,Goto(default,1)" ;
              $items[] =  "  exten => dial-BUSY,1,Goto(default,1)" ;              
              $items[] =  "  exten => dial-NOANSWER,1,Goto(default,1)" ;
              $items[] =  "  exten => dial-,1,Goto(default,1)" ;              
              $items[] =  "  exten => dial-CANCEL,1,Goto(done,1)" ;
              $items[] =  "  exten => dial-ANSWER,1,Goto(done,1)" ;
              $items[] =  "  exten => done,1,NoOp(RingGroup Finished - ${DIALSTATUS})";
              $items[] =  "  exten => done,n,Hangup";

        
        $DST = $row['default_action_data'] ;

        if( isset($row['default_action']) )
          // QUEUE Default ROUTING LOGIC  //
          $items[] = "\n  exten => default,1,Verbose(2,[ QUEUE {$_tenant}-queue-{$row['queue_id']} DEEFAULT action '{$row['default_action']}'(id: {$row['default_action_data']} ) triggered! ] )";

          switch( $row['default_action'] ){
        	 case "repeat":
        	      $items[] = " exten => default,n,Set(RETRY=\${MATH(0\${RETRY}+1,i)})" ;
  	              $items[] = " exten => default,n,GotoIf(\$[\${RETRY} < {$DST}]?s,1)" ;
             break;
        	      
          	case "extension": if ( $DST )
              $r = mysql_fetch_assoc( mysql_query("SELECT extension FROM t_sip_users WHERE id = {$DST}") );
                  $items[] = " exten => default,n,Dial(Local/{$r['extension']}@internal-{$_tenant}-local,,tT)" ;
          	  break;   
          	   
          	case "number":	 if ( $DST )
          	   $items[] = " exten => default,n,Dial(Local/{$DST}@internal-{$_tenant},,tT)" ;          	   
          	   break;        
          	     	   
          	case "ivrmenu": if ( $DST )
          	      $items[] = " exten =>  default,n,Dial(Local/s@internal-{$_tenant}-ivrmenu-{$DST},,tT)" ;          	   
          	   break;
          	
          	case "ringgroup":
          	      $items[] = " exten => default,n,Dial(Local/s@internal-{$_tenant}-ringgroup-{$DST},,tT)" ;
          	   break;   
          	   
      	    case "queue":
          	      $items[] = " exten => default,n,Dial(Local/s@internal-{$_tenant}-queue-{$DST},,tT)" ;
          	   break;    
          	   
          	case "play_invalid":
          	   $items[] = " exten => default,n,Playback(invalid)" ;          	   
          	   break;
          	   
            case "play_rec":
          	   $items[] = " exten => default,n,Playback(snd_{$_tenant}/{$DST})" ;          	   
          	   break;
          	   
          	case "play_tts":
          	    //$items[] = " exten => default,n,Playback(".'/tts/' . md5("{$DST}..en-US_LisaVoice.1").")" ;
                $VAR = preg_replace('/,/','.',$DST);
                $items[] = "exten => default,n,AGI(tts.php,{$_tts_voice},{$VAR},0,auto)";
          	   break;

          	   case "moh": 	          	      
          	   $items[] = " exten => default,n,MusicOnHold({$DST})" ;          	   
          	   break;
          	   
          	case "hangup":
          	   $items[] = " exten => default,n,Verbose(2,'HANGUP ACtion executed')" ;          	   
          	   break;
          	   
          	   	   
	         case "voicemail":
	            $r = mysql_fetch_assoc( mysql_query("SELECT concat(t_vmusers.mailbox,'@',t_vmusers.context) as vmail_box FROM t_vmusers WHERE id = {$DST} limit 1") );          	         
  	         $items[] = " exten => default,n,Gosub(app-pbx-service,s,1(voicemail,{$r['vmail_box']},{$_tenant}))";         	   
  	         break;   
  	     	   
          }
          $items[] =  "exten => default,n,Hangup()\n" ; 	
		$items[] = "\n\n\n\n";
      //$items[] = " exten => s,n,Hangup()\n" ;
      
		$items[] = " exten => h,1,Verbose(10,HANGUP EVENT IN {$_tenant}-queue-{$row['queue_id']}  Happened)" ;
		$items[] = " exten => h,n,ExecIf($[ \"\${ABANDONED}\" =\"TRUE\" ]?Set(Q_STATUS=ABANDONED))";
		$items[] = " exten => h,n,ExecIf($[ \"\${MEMBERNAME}\" != \"\" ]?Set(Q_STATUS=ANSWERED))";
		$items[] = " exten => h,n,Set(CDR(service_status)={$_tenant}-queue-{$row['queue_id']}:\${Q_STATUS})\n"  ;
		//$items[] = " exten => h,n,Dumpchan()\n" ;
		          	   
	}
	
	if (count($items))
	  foreach( $items as $item )
		 echo "  {$item} \n";
	
}
