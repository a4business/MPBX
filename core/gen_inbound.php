<?php

 include_once(dirname( __DIR__)  . '/include/config.php');
 
 
 function gen_inbound_context() {
	  echo "[did-inbound]\n";
		  $res = mysql_query("SELECT dids.did, action, destination, ref_id, opt1 as timeout, opt2 as prefix, tag,
		                             t_moh.name as moh_name,
		                             t_inbound_rules.*,
		                             t_ivrmenu.name as ivrmenu_name,
		                             tenants.id as tenant_id,
		                             t_sip_users.extension as user_exten,
		                             t_queues.name as queue_name, t_queues.musiconhold as queue_musiconhold, 
		                             t_queues.queue_welcome as queue_welcome,
		                             concat(t_vmusers.mailbox,'@',t_vmusers.context) as vmailbox,
		                             tenants.parkext_announce as parkext_announce,
                                     tenants.paging_retry_count as paging_retry_count,
                                     tenants.paging_interval as paging_interval,
                                     tenants.sounds_language as tenant_ivr_lang,
                                     t_inbound.context_script as context_script,
                                     fc.app as app
		                        FROM dids 
		                               LEFT JOIN t_inbound ON t_inbound.did_id = dids.id
		                               LEFT JOIN feature_codes as fc ON trim(fc.appdata) =  trim(t_inbound.context_script)
		                               LEFT JOIN t_inbound_rules ON t_inbound_rules.inbound_id = t_inbound.id    
		                               LEFT JOIN t_moh ON t_moh.id = t_inbound_rules.destination AND t_inbound_rules.action = 'moh'
                                     LEFT JOIN t_sip_users ON t_sip_users.id = t_inbound_rules.destination AND t_inbound_rules.action = 'extension'
                                     LEFT JOIN t_queues ON t_queues.id = t_inbound_rules.destination AND t_inbound_rules.action = 'queue'
                                     LEFT JOIN t_ivrmenu ON t_ivrmenu.id = t_inbound_rules.destination AND t_inbound_rules.action = 'ivrmenu'
                                     LEFT JOIN t_vmusers ON t_vmusers.id = t_inbound_rules.destination AND ( t_inbound_rules.action = 'voicemail' OR t_inbound_rules.action = 'checkvm'),
		                             tenants  
		                        WHERE tenants.id = dids.tenant_id 
		                        ORDER BY dids.did      ")  or die( 'error:' . mysql_error() );
		                          
		    while($row = mysql_fetch_assoc($res)){
		    	  $_tenant = $row['ref_id'];
		    	  $_tenant_id = $row['tenant_id'];
		    	  $_tenant_sounds = $row['tenant_ivr_lang']?$row['tenant_ivr_lang']:'en';
		    	  $DID = trim($row['did']);  // We support only numeric INBOUND destinations - HA! 075 ? 
		    	  $EXT = preg_replace('/SIP\/|Local\//','',$row['destination']);
		    	  $EXT = $EXT ? $EXT : 0;

		      $IS_FIRST_ENTRY = ($previous == $DID)?false:true;
		          if ( $IS_FIRST_ENTRY && isset($previous) )
		          echo "   exten => {$previous},n,Hangup()\n\n\n" ;
		           
              $previous = $DID; 	  
              if ($IS_FIRST_ENTRY ){
                    echo "   exten => {$DID},1,Noop(Start New Inbound CALL)\n";
	            echo "   exten => {$DID},n,ExecIf(\$[ \"\${CALLERID(num)}\" = \"\${EXTEN}\" ]?Set(CALLERID(num)=\${FILTER(0-9,\${SIP_HEADER(Remote-Party-ID):2:12})}))\n";
	    	    echo "   exten => {$DID},n,Verbose(2, Inbound DID: {$DID} FROM \${FILTER(0-9,\${SIP_HEADER(Remote-Party-ID):2:12})} Route: {$_tenant}-> [{$row['action']}][{$row['user_exten']} {$row['destination']} {$row['ivrmenu_name']} ] at ".'${STRFTIME(${EPOCH},,weekDay:%a(%u) Time:%T )})'." \n";
                    echo "   exten => {$DID},n,Gosub(set-variables,s,1({$_tenant},inbound-did))\n";
                    echo "   exten => {$DID},n,Set(CDR(direction)=INBOUND)\n";
                    echo "   exten => {$DID},n,Set(CDR(INBOUND_DID)={$DID})\n";
		            echo "   exten => {$DID},n,Set(CDR(tenant_id)={$_tenant_id})\n";
                    echo "   exten => {$DID},n,Set(__DIRECTION=INBOUND)\n";
                    echo "   exten => {$DID},n,Set(__INBOUND_DID={$DID})\n";
                    echo "   exten => {$DID},n,Gosub(app-recording,s,1)\n";
                    echo "   exten => {$DID},n,Set(CHANNEL(language)={$_tenant_sounds})\n";
                    echo "   exten => {$DID},n,Set(CHANNEL(hangup_handler_push)=hnd-inbound,s,1)\n";
                    //echo "   exten => {$DID},n,Set(CALLERID(name)=\${REPLACE(\${CALLERID(name)},\${CALLERID(num)},'')})\n";
               //   echo "   exten => {$DID},n,Set(__DYNAMIC_FEATURES=mix-mon#mix-mon2#nway-start)\n";
		          if ( $row['tag']  )     
		           echo "   exten => {$DID},n,Set(CALLERID(name)=[{$row['tag']}] \${CALLERID(name)})\n";
      		       
			      if($row['context_script']){			        
			        echo "   ;;;   Context Script execution app:'{$row['app']}' {$row['context_script']} ;;;;; \n";			  
			        if( $row['app'] == 'AGI' )
			         echo "   exten => {$DID},n,AGI({$row['context_script']})\n";
			        if( $row['app'] == 'Gosub' )
			         echo "   exten => {$DID},n,Gosub({$row['context_script']})\n";
			      }  
		        }  
		          		    	    
		    	  $exec = array();
		              
         // INBOUND ROUTING ENTRIES  //         	     
		        if ( isset($_tenant) && isset($row['action']) ){
		          switch( $row['action'] ) {
		          	case "extension":
		               $exec[] = "Dial(Local/{$row['user_exten']}@internal-{$_tenant}-local,{$row['timeout']},r)" ;
		          	   break;          	   
		          	   
		          	case "disa":
 		          	   $exec[] = "Gosub(app-pbx-service,s,1({$row['action']},${EXT},{$_tenant})";
 		          	   break;
 		          	   
	          	   case "pagegroup":
	          	      $pg_res = mysql_query("SELECT interface, t_pagegroups.full_duplex, t_pagegroups.no_beep FROM t_pagegroups,t_pagegroup_members 
	          	                              WHERE t_pagegroups.id = ${EXT} AND 
	          	                                    t_pagegroups.tenant_id = {$_tenant_id} AND t_pagegroup_members.tenant_id = {$_tenant_id} AND 
	          	      									   t_pagegroup_members.pagegroup_id = t_pagegroups.id ");
	          	      while($pg_ext = mysql_fetch_assoc($pg_res)) {
	          	        $page_ext_group .= $pg_ext['interface'] . '&';
	          	        	          	        
	          	        $opts =  $pg_ext['full_duplex'] ? 'd':'' ;
	          	        $opts =  $pg_ext['no_beep']  ? "{$opts}q": $opts ; 
                       $opts =  $opts?"|{$opts}":'';
                  }
         	      
 		          	   $exec[] = "Gosub(app-pbx-service,s,1({$row['action']}," . trim($page_ext_group,'&') . "{$opts},{$_tenant}))";
 		          	  break;
		          	   
		          	case "number":
		          	   $exec[] = "Dial(Local/{$EXT}@internal-{$_tenant},{$row['timeout']},r)" ;          	   
		          	  break;        
		          	   
                    case "voicemail":
		          	   //$exec[] = "VoiceMail(${row['vmailbox']})" ;          	   
		          	   $exec[] = "Gosub(app-pbx-service,s,1(voicemail,${row['vmailbox']},{$_tenant}))";
		          	  break;

		            case "dirbyname":               
                       $exec[] = "Gosub(app-pbx-service,s,1(directory,b,{$_tenant}))";              
                       break;  
		          	   
		          	case "checkvm":
		          	   $exec[] = "VoiceMailMain(${row['vmailbox']})" ;          	   
		          	   break;
		          	
		          	case "conference":
		          	   $exec[] = "Gosub(dialconference,s,1({$EXT},,,,snd_{$_tenant}))" ;
		          	   break; 
		          	    
		          	case "ivrmenu":		          	       
		          	     if ( $EXT )
		          	       $exec[] = "Dial(Local/s@internal-{$_tenant}-ivrmenu-{$EXT},{$row['timeout']},tT)" ;          	   
		          	   break;
		          	   
		          	case "ringgroup":		          	       
		          	     if ( $EXT )
		          	       $exec[] = "Dial(Local/s@internal-{$_tenant}-ringgroup-{$EXT},{$row['timeout']},tT)" ;          	   
		          	   break;
		          	    
	          	   case "queue":
	          	      $exec[] = "Dial(Local/s@internal-{$_tenant}-queue-{$EXT},,tT)" ;
		          	   break;    
		          	   
		          	case "play_invalid":
		          	   $exec[] = "Playback(invalid)" ;          	   
		          	   break;
		          	   
		          	case "park_announce_rec":
	          			$PLAY_REC = $EXT;
				            if( !preg_match('/PARKED/',$PLAY_REC) ){
				             $PLAY_REC .= ':PARKED';  // This variable needed for ParkAndAnnounce() // 
				            }
				            $PLAY_REC = "snd_{$_tenant}/{$PLAY_REC}";
				            $page_extens = json_decode($row['parkext_announce'],true);
			                $page_extens_str = is_array($page_extens) ? 'SIP/' . implode('&SIP/', $page_extens) : 'Local/s@general-{$_tenant}-error';
			                $exec[] = "Verbose( Got values: {$row['parkext_announce']} ";
			 		        $exec[] = "Gosub(app-pbx-service,s,1(park_announce_rec,{$PLAY_REC}|{$page_extens_str},{$_tenant}))";  //  // This | delimiter used by Macro app-pbx-service, which separate recording and announce exten 
			 		        break; 		   
		          	   
                  case "play_rec":
		          	   $exec[] = "Playback(snd_{$_tenant}/{$EXT})" ;          	   
		          	   break;
		          	   
		          	case "play_tts":
		          	   $tts_file = '/tts/' . md5("{$EXT}..en-US_LisaVoice.1");
		          	   $exec[] = "Playback({$tts_file})" ;          	   
		          	   break;

 	          	   case "moh": 	          	      
		          	   //$exec[] = "MusicOnHold({$row['moh_name']})" ;
		          	   $exec[] = "Gosub(app-pbx-service,s,1(moh,{$row['moh_name']},{$_tenant}))";          	   
		          	   break;
		          	   
		          	case "hangup":
		          	   $exec[] = "Verbose(2,'HANGUP Action executed')" ;          	   
		          	   break;
		          } 
		       }else{
		       	  echo   "   exten => {$DID},n,NoCDR()\n";
		          echo   "   exten => {$DID},n,Playback(ss-noservice)\n";
		       }  
		   
		   if (!count($exec)){
		    // echo  "   exten => {$DID},n,Set(__DYNAMIC_FEATURES=mix-mon)\n";
		     echo  "   exten => {$DID},n,Dial(Local/s@general-{$_tenant}-error)\n";
		   }else{		  
		     // DID,n,ExecIfTime(18:01-21:00,mon-fri,1-31,jan-dec?Playback(good_night))
          $row['day_time_from'] = $row['day_time_from'] ? $row['day_time_from'] : '00:00:00';
          $row['day_time_to'] = $row['day_time_to'] ? $row['day_time_to'] : '23:59:59'; 
          echo "   exten => ${DID},n,Verbose(3, Inbound Route for ${DID}, Time: \${STRFTIME(\${EPOCH},,weekDay:%a(%u) Time %T )} )\n"; 
		    foreach( $exec as $item )
		      echo "   exten => ${DID},n,ExecIfTime({$row['day_time_from']}-{$row['day_time_to']},{$row['week_day_from']}-{$row['week_day_to']},,,?{$item}) \n";
		   }  
		     
		     
      }		     
	      
	       
}		
