<?php

  include_once(dirname( __DIR__)  . '/include/config.php');
  
  
  function gen_internal_context(){
  	
			   $res = mysql_query("SELECT * FROM tenants");
			   if (!$res) die( mysql_error()."\n");  
			   while($row = mysql_fetch_assoc($res) ){
			    $tenant = $row['ref_id'];
			    $tenant_id = $row['id'];
			    $tenant_moh = $row['parkedmusicclass'] ? $row['parkedmusicclass'] : 'default';
			        
			    echo "\n\n;;;<================ TENANT: '{$tenant}' ====================>\n";
			    echo "[general-{$tenant}-error]\n";
             echo "  exten => s,1,Answer()\n";  	  
             echo "  same => n,Playback(" . ( $row['general_error_message'] ? $row['general_error_message'] : 'cannot-complete-otherend-error' ) . ")\n";
             echo "  same => n,Hangup()\n\n\n";
             
             echo "[internal-{$tenant}]\n";
             echo " include => internal-{$tenant}-pbx\n";
			    echo " include => internal-{$tenant}-local\n";
			    echo " include => internal-{$tenant}-outbound\n";
			    echo " include => internal-{$tenant}-features\n";
            // This Tenant PBX Services //

             echo "[internal-{$tenant}-features]\n";            
             echo "switch => Realtime\n\n";
             
             echo "\n[internal-{$tenant}-pbx]\n";
             $res_conf = mysql_query("SELECT id,conference FROM t_conferences  WHERE  tenant_id = {$tenant_id}");
			    while ($conf = mysql_fetch_assoc($res_conf))
 	              echo " exten => {$conf['conference']},1,Macro(dialconference,{$conf['id']},,,,snd_{$tenant})\n" ;
 	          
 	               
		        			    

            // This Tenant OutBound Routes
			    echo "\n[internal-{$tenant}-outbound]\n";             
             $res2 = mysql_query("SELECT id FROM t_route   WHERE route_enabled =1 AND tenant_id = {$tenant_id}");
			    while ($r = mysql_fetch_assoc($res2))
			       echo " include => outbound-{$tenant}-{$r['id']}\n";
			    echo "\n";
             
			    
			  // 1.1  Local Extensions Context     
			    echo "[internal-{$tenant}-local]\n";  
			    echo "include => internal-{$tenant}-local-custom\n";
			    echo "include => parkingspace-{$tenant}\n"; 
			  
			    $res2 = mysql_query("SELECT *  FROM  t_sip_users 
			    							    LEFT JOIN t_vmusers ON t_vmusers.mailbox = t_sip_users.extension
			    							 WHERE  t_sip_users.tenant_id = {$tenant_id} ");
			   if (!$res2) die(mysql_error()."\n");      										  
			    while ($USER = mysql_fetch_assoc($res2)){
			       $exten = $USER['extension'];
			       $dev = $USER['name'];
			       $vm_tm = $USER['vm_timeout'];
			       
			       echo "  exten => {$exten},1,NooP(Dialed Exten: \${EXTEN} )\n";
			       echo "  exten => {$exten},n,Set(MUSICCLASS()={$tenant_moh})\n";   
			       echo "  exten => {$exten},n,Macro(calleridManage,internal)\n";
			       echo "  exten => {$exten},n,Dial(SIP/${dev},{$vm_tm},rRTtkK)\n";  // T - remote can transfer  t = local can transfer
			       echo "  exten => {$exten},n,VoiceMail({$exten}@{$tenant}-vmdefault,u)\n";
			       echo "  exten => {$exten},n,Hangup\n";
			       
			       echo "\n";
			       
			   }
			   
			   
			 // 1.3 Custom ONLY CERTAIN ROUTE Contexts for Extensions (assigned as context for peer, to limit it)  \n";
			  $res3 = mysql_query("SELECT outbound_route, extension FROM  t_sip_users
			                        WHERE t_sip_users.tenant_id = {$tenant_id} AND  ifnull(outbound_route,1) != 1 ");
			   if (!$res3) die(mysql_error()."\n");      										  
			    while ($r = mysql_fetch_assoc($res3)){
			       echo "\n\n[internal-{$tenant}-exten-{$r['extension']}]\n";
			        $route = ( $r['outbound_route'] == -1 ) ? 'local' : $r['outbound_route'];      
			        echo " include => outbound-{$tenant}-{$route}\n";
			   }
			    	 
	  }  // End of Tenants cycle
 } 



?>