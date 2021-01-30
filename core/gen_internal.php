<?php

  include_once(dirname( __DIR__)  . '/include/config.php');
  
  
  function gen_internal_context(){
  	
		  $res = mysql_query("SELECT * FROM tenants");
		  if (!$res) die( mysql_error()."\n");  

	      while($row = mysql_fetch_assoc($res) ){
			    $tenant_extenstions = array();	
			    $tenant = $row['ref_id'];
			    $_tenant_sounds = $row['sounds_language']?$row['sounds_language']:'en';
			    $subscriptions = $row['enable_status_subscription'];
			    $tenant_id = $row['id'];
			    $tenant_moh = $row['parkedmusicclass'] ? $row['parkedmusicclass'] : 'default';
			    // auto_Простите. Набранный номер недоступен..ru_RU.1
			    $tts_err_file = '/tts/' . md5("auto_{$row['general_error_message']}..{$row['default_tts_lang']}.1");
			        
			    echo "\n\n;;;<================ TENANT: '{$tenant}' ====================> {$row['general_error_message']}..{$row['default_tts_lang']}.1 \n";
			    echo "[general-{$tenant}-error]\n";             
             echo "  exten => s,1,Verbose(2,GENERAL ERROR ) \n";
             echo "  same => n,NoCDR()\n";             
             echo "  ;; same => n,Answer()\n";  	               
             echo "  same => n,Background(" . ( $row['general_error_message'] ? $tts_err_file : 'cannot-complete-otherend-error' ) . ")\n";
             echo "  same => n,Hangup()\n\n\n";
             
             echo "[internal-{$tenant}]\n";

             if( $row['intertenant_routing'] ){
             	$allowed_tenants = json_decode($row['intertenant_routing'],true);             
             	echo "  ;;Inter-Tenant Call Exchange;;\n";
             	foreach ($allowed_tenants as $t) {             		
             		$q = mysql_query("SELECT ref_id FROM tenants WHERE  id = 0{$t}");
             		$tname = mysql_fetch_assoc($q);
             		if($tname['ref_id'])
             		  echo "  exten => _{$t}#X.,1,Dial(Local/\${EXTEN:3}@internal-{$tname['ref_id']},60)\n";
             	}
             	echo "\n\n";
             }	

             echo " include => internal-{$tenant}-pbx\n";
             echo " include => internal-{$tenant}-features\n";
		     echo " include => internal-{$tenant}-local\n";
		     echo " include => internal-{$tenant}-outbound\n";
			    
			 echo " exten => t,1,Verbose(3, TIMEOUT happened on [internal-{$tenant}] - FORCED TO HANGUP )\n";
             echo " exten => t,n,Hangup\n";

             echo " exten => i,1,Verbose(3, INVALID happened on [internal-{$tenant}] - FORCED TO HANGUP )\n";
             echo " exten => i,n,Hangup\n";

             echo " exten => e,1,Verbose(3, ERROR happened on [internal-{$tenant}] ) - FORCED TO HANGUP )\n";
             echo " exten => e,n,Hangup\n";
			    
            // This Tenant PBX Services //

             echo "\n\n[internal-{$tenant}-features]\n";            
             echo "switch => Realtime\n\n";
             
         // Tenant PBX Extensions - Queues, RingGroups etc..     
           echo "\n[internal-{$tenant}-pbx]\n";
             $res_conf = mysql_query("SELECT id,conference FROM t_conferences  WHERE  tenant_id = {$tenant_id}");
			    while ($conf = mysql_fetch_assoc($res_conf))
 	                       echo " exten => {$conf['conference']},1,Gosub(dialconference,s,1({$conf['id']},,,,snd_{$tenant}))\n" ;
 	          
 	         $res_queues = mysql_query("SELECT id,name FROM t_queues  WHERE tenant_id = {$tenant_id}");
			    while ($queue = mysql_fetch_assoc($res_queues))
			      if(strlen(preg_replace('/[^0-9]/','',$queue['name']) > 2 )){
			      	$QEXT = preg_replace('/[^0-9]/','',$queue['name']);
				 if($QEXT)
		 	          echo " exten => {$QEXT},1,Dial(Local/s@internal-{$tenant}-queue-{$queue['id']},,tT)\n" ; 	               
 	              } 

             $res_rgroups = mysql_query("SELECT id,name FROM t_ringgroups  WHERE tenant_id = {$tenant_id}");
	        while ($rgroup = mysql_fetch_assoc($res_rgroups))
		    if(strlen((int)$rgroup['name']) > 2 )
 	              echo " exten => ".(int)$rgroup['name'].",1,Dial(Local/s@internal-{$tenant}-ringgroup-{$rgroup['id']},,tT)\n" ;
 	              

        // This Tenant OutBound Routes
			    echo "\n[internal-{$tenant}-outbound]\n";             
             $res2 = mysql_query("SELECT id FROM t_route   WHERE route_enabled =1 AND tenant_id = {$tenant_id}");
			    while ($r = mysql_fetch_assoc($res2))
			       echo " include => outbound-{$tenant}-{$r['id']}\n";
			    echo "\n";
             
			    
			  // 1.1  Local Extensions Context     
			    echo "[internal-{$tenant}-local]\n";  
			    echo "include => internal-{$tenant}-local-custom\n";
			    echo "include => parkingspace-{$tenant}\n\n";
			    
			    echo "  exten => t,1,Verbose(3, TIMEOUT happened on [internal-{$tenant}] )\n";
			    
			    $tts_file = '/tts/' . md5("auto_{$row['general_error_message']}..{$row['default_tts_lang']}.1");
             echo "  exten => e,1,Verbose(3, ERROR happened on [internal-{$tenant}] )\n";
             echo "  exten => e,n,ExecIf(\$[\${error-loop}s = s ]?Set(error-loop=1)\n";
             echo "  exten => e,n,Set(error-loop=\$[\${error-loop} + 1])\n";             
             echo "  exten => e,n,Background({$tts_file})\n\n";
             echo "  exten => e,n,ExecIf($[\${error-loop} > 5]?Hangup)\n";
             echo "  exten => e,n,ExecIf($[\${error-loop} > 3]?Goto(\${CUT(DIALEDPEERNUMBER,@,2)},s,1))\n\n";
             
             
             $tts_file = '/tts/' . md5("auto_{$row['general_invalid_message']}..{$row['default_tts_lang']}.1");
             echo "  exten => i,1,Verbose(3, INVALID happened on [internal-{$tenant}] )\n";
             echo "  exten => i,n,ExecIf(\$[\${invalid-loop}s = s ]?Set(invalid-loop=1)\n";
             echo "  exten => i,n,Set(invalid-loop=\$[\${invalid-loop} + 1])\n";             
             echo "  exten => i,n,Background({$tts_file})\n";
             //echo " exten => i,n,Dumpchan()\n";
             echo "  exten => i,n,ExecIf($[\${invalid-loop} > 5]?Hangup)\n";
             echo "  exten => i,n,ExecIf($[\${invalid-loop} > 3]?Goto(\${CUT(DIALEDPEERNUMBER,@,2)},s,1))\n\n\n";

             
           
			   
			    $res2 = mysql_query("SELECT * FROM t_sip_users 
			    							    LEFT JOIN t_vmusers ON t_vmusers.mailbox = t_sip_users.extension AND t_vmusers.tenant_id = t_sip_users.tenant_id 
			    							 WHERE  t_sip_users.tenant_id = {$tenant_id} ");
			   if (!$res2) die(mysql_error()."\n");      										  
			    while ($USER = mysql_fetch_assoc($res2)){
			       $exten = $USER['extension'];
			       $dev = $USER['name'];
			       $vm_tm = $USER['vm_timeout'];
			       
			       echo "  exten => {$exten},1,NooP(Dialed Exten: \${EXTEN} )\n";
			       //echo "  exten => {$exten},n,Set(MUSICCLASS()={$tenant_moh})\n";  // Set(CHANNEL(musicclass)=${HASH(CALLEE,musicclass)})  ??
			       echo "  exten => {$exten},n,Set(__DIALED_NUMBER=\${EXTEN})\n";
			       echo "  exten => {$exten},n,Set(CHANNEL(language)={$_tenant_sounds})\n";
			       //echo "  exten => {$exten},n,Macro(calleridManage,internal)\n"; // THis is done inside pbx-inbound
			       echo "  exten => {$exten},n,Gosub(pbx-inbound,s,1(SIP/${dev},{$exten}@{$tenant}-vmdefault))\n";
			       //echo "  exten => {$exten},n,Dial(SIP/${dev},{$vm_tm},rRTtkK)\n";  // T - remote can transfer  t = local can transfer
			       //echo "  exten => {$exten},n,VoiceMail({$exten}@{$tenant}-vmdefault,u)\n";
			       echo "  exten => {$exten},n,Hangup\n";
			       echo "\n";
			    
			       $tenant_extenstions[] =  $exten;   
			   }
			   
			   
			 // 1.3 Custom ONLY CERTAIN ROUTE Contexts for Extensions (assigned as context for peer, to limit it)  \n";
			  $res3 = mysql_query("SELECT outbound_route, extension FROM  t_sip_users
			                        WHERE t_sip_users.tenant_id = {$tenant_id} AND  ifnull(outbound_route,1) != 1 ");
			   //if (!$res3) die(mysql_error()."\n");      										  
			    while ($r = mysql_fetch_assoc($res3)){
			        echo "\n[internal-{$tenant}-exten-{$r['extension']}]\n";
			        $route = ( $r['outbound_route'] == -1 ) ? 'local' : $r['outbound_route'];      
			        echo " \n\ninclude => outbound-{$tenant}-{$route}\n";
			   }
		
		 if ( count($tenant_extenstions) && $subscriptions ){
 		  echo "[internal-{$tenant}-BLF]\n"; 
 		  echo "include => parkingspace-{$tenant}\n";
		  foreach($tenant_extenstions as $t_exten)
		 	  echo "  exten => {$t_exten},hint,SIP/{$tenant}-{$t_exten}\n";
  		 } 
			    	 
	  }  // End of Tenants cycle
 } 



?>
