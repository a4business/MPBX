<?php

 include_once(dirname( __DIR__)  . '/include/config.php');
 
 function gen_ringgroups_context() {
 	
	  //echo "[ring-groups]\n";
		  $res = mysql_query("SELECT *,tenants.id as tenant_id,rg.id as rg_id,rg.name as rg_name,
		                             t_queues.name as queue_name, t_queues.musiconhold as queue_musiconhold, t_queues.queue_welcome as queue_welcome,
		                             rg.default_action as default_action,
		                             rg.default_action_data as default_action_data

		                      FROM t_ringgroups rg
		                           LEFT JOIN t_queues on t_queues.name = rg.default_action_data AND t_queues.default_action = 'queue' AND  t_queues.tenant_id = rg.tenant_id,
		                           tenants
		                        WHERE tenants.id = rg.tenant_id 
		                        ORDER BY rg.id  ");
		                        
		    if (!$res) die(mysql_error()."\n");
		    while($row = mysql_fetch_assoc($res)){
		    	  $i=0;
		    	  $_tenant = $row['ref_id'];
              $_tenant_id = $row['tenant_id'];
		    	  $_rgname = $row['rg_name'];
		    	  $_rgid = $row['rg_id'];
			  $ext = explode('.', $row['announcement_file']);
		    	  $_announce = basename($row['announcement_file'], '.' . end($ext) );
		    	  $_prefixed = $row['callername_prefix'];
		     	
		    	    $items = array();
		    	    $items[] =  ";;; RingGroup {$row['rg_name']}  {$row['description']}  ";
		    	    $items[] =  "exten => s,1(start),Verbose(3, Starting \${CONTEXT} ) ";
                    $items[] = " exten => s,n,Set(CDR(userfield)={$_tenant}::ringgroup-{$_rgid}::${tenant_id})"  ;
		    $items[] = " exten => s,n,Set(CDR(tenant_id)=${tenant_id})"  ;
                    $items[] = " exten => s,n,ExecIf(\$[ \"\${INBOUND_DID}\" != \"\" ]?Set(CDR(INBOUND_DID)=\${INBOUND_DID}))";

		    	    
		    	    if ( $_prefixed ) $items[] =  "exten => s,n,Set(CALLERID(name)={$row['callername_prefix']} \${CALLERID(name)})";
                if ( $_announce ) $items[] =  "exten => s,n,Playback(snd_{$_tenant}/{$_announce})";
                
		          // RingGroup Hunt Lists//		          
		          $ring_res = mysql_query("SELECT * FROM t_ringgroup_lists rgl
		                                   WHERE rgl.t_ringgroups_id = {$_rgid} AND
 		                                         rgl.tenant_id = {$_tenant_id} ");
                 		                                         
		           if (!$ring_res) die(mysql_error()."\n");
		            while($grp = mysql_fetch_assoc($ring_res)){
		            	$DATA = $grp['extensions'];
		            	$grp['timeout'] = $grp['timeout'] ? $grp['timeout'] : 60; 
		            	$items[] = "exten => s,n,Verbose(4, Step ".++$i.": RG-Members:'{$grp['description']}', Type:{$grp['group_type']} " . ( ( $grp['group_type'] != 'queue')? " DialMethod:{$grp['extension_method']}":"" ) . ")";
				$ext = explode('.', $grp['announcement_file']) ;
		            	$_play_rec = basename($grp['announcement_file'], '.' . end($ext));
		            	if( $_play_rec )  $items[] =  " exten => s,n,Playback('snd_{$_tenant}/{$_play_rec}')";
		            	
		            	switch( $grp['group_type'] ){
		            		
		            		case 'queue':                     
                            //$items[] = "exten => s,n,Dial(Local/s@internal-{$_tenant}-queue-{$DATA},{$grp['timeout']},gtT)" ;
                            $items[] = "exten => s,n,Gosub(app-pbx-service,s,1(queue,{$DATA},{$_tenant}))";
                          break; 
                          
                        case 'extension':
                          $EXTENSIONS_IDS = ($DATA)  ? json_decode($DATA)  :array();
                          $EXTENSIONS = array();
                          $PHONES = array();                          
                          $exten_res=mysql_query("SELECT extension,name FROM t_sip_users WHERE id in (" . implode(',',$EXTENSIONS_IDS) .")");
                         if($exten_res)
			   while($r = mysql_fetch_assoc($exten_res)){
 -                           	    $EXTENSIONS[] = ($grp['extension_method'] == 'exten_based') ? "Local/{$r['extension']}@internal-{$_tenant}-local" : "SIP/{$r['name']}";
                               $items[] =  "exten => s,n,Verbose(2,[ RingGroup {$_rgname} ADD " . (($grp['extension_method'] == 'device_based') ?  "DEV[ SIP/{$r['name']} ]" :  " EXTEN[ Local/{$r['extension']}@internal-{$_tenant}-local ]") . " )";
                          }
                          $PHONE_LIST = ($grp['phone_numbers']) ? explode(',', $grp['phone_numbers'] ):array();
		                    if(count($PHONE_LIST))
		                      foreach($PHONE_LIST as $N){
		                        $PHONES[]="Local/${N}@internal-{$_tenant}-outbound";
		                        $items[] =  "exten => s,n,Verbose(2,      [ RingGroup $_rgname ADD Destn: Local/${N}@internal-{$_tenant}-outbound  ])";
		                      }    
                          $RESULT_LIST = implode('&',  array_merge( count($PHONES)?$PHONES:array(), count($EXTENSIONS)?$EXTENSIONS:array() ) );
                          $I = $grp['ignore_redirects']? 'i' : '';
                          $APP = $RESULT_LIST ? "{$RESULT_LIST},{$grp['timeout']},{$I}gU(set-service^{$_tenant}-ringgroup-{$_rgid}:ANSWERED)" : "Local/s@general-{$_tenant}-error";
			               $items[] =  "exten => s,n,Set(__FWD_KEEP_CID=1)";
                          $items[] =  "exten => s,n,Dial({$APP})";    
                          break;
                          
                      }    
                     
		             } 
		             
              //$items[] =  "exten => s,n,DumpChan()" ;
              		             
              $items[] =  "\n\n  ;; if we are back - then we are failed?";
              $items[] =  "  exten => s,n,Verbose(2,[ HuntList Status: \${DIALSTATUS} ]) " ;
              $items[] =  '  exten => s,n,Goto(dial-${DIALSTATUS},1)';              
              $items[] =  "  exten => dial-CHANUNAVAIL,1,Goto(default,1)" ;
              $items[] =  "  exten => dial-CONGESTION,1,Goto(default,1)" ;
              $items[] =  "  exten => dial-BUSY,1,Goto(default,1)" ;              
              $items[] =  "  exten => dial-NOANSWER,1,Goto(default,1)" ;
              $items[] =  "  exten => dial-,1,Goto(default,1)" ;
              
              $items[] =  "  exten => dial-CANCEL,1,Goto(done,1)" ;
              $items[] =  "  exten => dial-ANSWER,1,Goto(done,1)" ;
   	        
              
              $items[] =  "  exten => done,1,NoOp(RingGroup Finished - \${DIALSTATUS})";
              $items[] =  "  exten => done,n,Hangup";
   	              
		       // RingGroup Default ROUTING LOGIC  //
		        $items[] = "\n  exten => default,1,Verbose(2,[ Ringgroup-{$_tenant}-{$_rgname} DEEFAULT action '{$row['default_action']}'(id: {$row['default_action_data']} ) triggered! ] )";
		        $items[] = "exten => default,n,Set(CDR(service_status)={$_tenant}-ringgroup-{$_rgid}:ABANDONED)\n"  ;
		        
		        $DST = $row['default_action_data'];
		        switch( $row['default_action'] ) {
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
		          	   $items[] = " exten => default,n,Playback(".'/tts/' . md5("{$DST}..en-US_LisaVoice.1").")" ;          	   
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
		          $items[] =  " exten => default,n,Hangup()\n" ; 

       echo "\n\n[internal-{$_tenant}-pbx]";
       echo "\n  exten => {$row['rg_name']},1,Goto(internal-{$_tenant}-ringgroup-{$_rgid},s,1)";

		       
         echo "\n\n[internal-{$_tenant}-ringgroup-{$_rgid}]\n";		       
		   if (!count($items))
		     echo  "   exten => s,n,Dial(Local/s@general-{$_tenant}-error)\n";
		   else		   
		    foreach( $items as $item )
		     echo "  {$item} \n";
		     
		   echo  "  exten => h,1,Verbose(2, HANGUP  Event happened, \${DIALSTATUS} )\n"; 
		    
		   
		   
   }		     
		     
      
	      
	       
}		
