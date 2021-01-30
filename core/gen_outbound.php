<?php

  include_once(dirname( __DIR__)  . '/include/config.php');

 
  function gen_outbound_context(){
			    $rs = mysql_query("SELECT tenants.id as tenant_id,
			    								  ref_id,
			  									  t_route.id as route_id,
			  									  t_route.name as route_name,
			  									  t_route.outbound_callerid as set_route_callerid,
			  									  tenants.outbound_callerid as tenant_callerid,
			  									  tenants.outbound_callername as tenant_callername,
			  									  t_route.context_script as context_script,
			  									  feature_codes.app as context_script_app
			  							  FROM t_route LEFT JOIN feature_codes ON trim(appdata) =  trim(t_route.context_script),
			  							       tenants
			  							  WHERE t_route.route_enabled > 0 AND
			  							        t_route.tenant_id = tenants.id ");
			    if (!$rs) die(mysql_error()."\n");			         
			    while($routes = mysql_fetch_assoc($rs) ){ 
			    	$TENANT_FCALLER_ID = "{$routes['tenant_callerid']}";
			    	$TENANT_FCALLER_NAME = "{$routes['tenant_callername']}";
			    	$TENANT_ID = $routes['tenant_id'];
			    	$TENANT_NAME = $routes['ref_id'];

 			    	echo "[outbound-{$TENANT_NAME}-{$routes['route_id']}]\n";
 			    	echo "include => internal-{$TENANT_NAME}-local\n";
 			    	echo "\n  exten => _+X.,1,Goto(\${EXTEN:1},1)\n";
			    	

			    	$r = mysql_query("SELECT *,t1.name as trunk1_name, t2.name as trunk2_name,
			    	 								  t1.dial_timeout as t1_dial_timeout,
			    	 								  t2.dial_timeout as t2_dial_timeout,
			    	                          ifnull(concat(':',strip),'') as strip,
			    	                          t1.other_options as t1_options,
			    	                          t2.other_options as t2_options
			    	                    FROM routes_list 
			    	                         LEFT JOIN trunks t1 ON t1.id = routes_list.trunk_id    	                    		 
			    	                    	 LEFT JOIN trunks t2 ON t2.id = routes_list.trunk2_id
			    	                    WHERE 
			    	                         route_id = {$routes['route_id']}");
			         if (!$r) die(mysql_error()."\n");
				     while($route = mysql_fetch_assoc($r)) {    		 
				          $num= $route['add_prefix'] ? $route['add_prefix']  . '${EXTEN'.$route['strip'].'}'  :  '${EXTEN'.$route['strip'].'}';
				          $OPT=',uR';
				          $t1_pref='';
				          $t2_pref='';
				          if($route['t1_options'] && preg_match('/=/',$route['t1_options']))				          	
				          	foreach( explode("\n", $route['t1_options']) as $t1_pair )
				          	  $t1_pref = ( explode('=', $t1_pair)[0] == 'outbound_prefix' )	? explode('=', $t1_pair)[1]: $t1_pref;
				          	  

				          if($route['t2_options'] && preg_match('/=/',$route['t2_options']))				          	
				          	foreach( explode("\n", $route['t2_options']) as $t2_pair )
				          	  $t2_pref = ( explode('=', $t2_pair)[0] == 'outbound_prefix' )	? explode('=', $t2_pair)[1]: $t2_pref;
				          


				          echo "  exten => {$route['dial_pattern']},1,Verbose(10, Outbound Dialing SIP/${num}@{$route['trunk1_name']}  \${CALLER_INFO} )\n";
				          
				         if( !$routes['set_route_callerid'] ){
				          	echo "  exten => {$route['dial_pattern']},n,Set(TENANT_FCALLER_NAME=${TENANT_FCALLER_NAME})\n";
				          	echo "  exten => {$route['dial_pattern']},n,Set(TENANT_FCALLER_ID=${TENANT_FCALLER_ID})\n";				          	
                            echo "  exten => {$route['dial_pattern']},n,Gosub(calleridManage,s,1(external))\n";
                         }else{
	                      $OPT .= "f({$routes['set_route_callerid']})"; // Overwrite CallerID  on the Route - DO forced (ignore tenant and exten caller settings )
	                     } 

	                      echo "  exten => {$route['dial_pattern']},n,Gosub(set-variables,s,1({$routes['ref_id']}))\n";                        
	                      echo "  exten => {$route['dial_pattern']},n,Set(HASH(_CALLER)=\${GET_USER(\${MYID})})\n";
	                      
	                      // Try to identifie destination ( BUT = if is in FORWARD, then it is already set to original Exten, keep it)
	                      echo "  exten => {$route['dial_pattern']},n,ExecIf(\$['\${HASH(CALLEE,extension)}' != '']?Set(HASH(_CALLEE)=\${GET_USER(\${EXTEN})})\n";
	                      echo "  exten => {$route['dial_pattern']},n(recording),Gosub(app-recording,s,1)\n";
	                      echo "  exten => {$route['dial_pattern']},n,Set(CDR(tenant_id)={$TENANT_ID})\n";     
	                      echo "  exten => {$route['dial_pattern']},n,Set(CDR(direction)=OUTBOUND)\n";	
	                      echo "  exten => {$route['dial_pattern']},n,Set(__DIRECTION=OUTBOUND)\n";                      
						  if($routes['context_script']){						   
						        echo "  ;;;   Context Script execution: {$routes['context_script']} ;;;;; \n";				  
						        if( $routes['context_script_app'] == 'AGI' )
						         echo "    exten => {$route['dial_pattern']},n,AGI({$routes['context_script']})\n";
						        if( $routes['context_script_app'] == 'Gosub' )
						         echo "    exten => {$route['dial_pattern']},n,Gosub({$routes['context_script']})\n";
						   }  
	                      echo "  exten => {$route['dial_pattern']},n,Set(CHANNEL(hangup_handler_push)=hnd-outbound,s,1)\n";
	                      echo "  exten => {$route['dial_pattern']},n,Verbose(2,    OUTBOUND Call:[\${tenant}] (\${HASH(CALLER,name)}/\${HASH(CALLER,extension)}[as \${CALLERID(all)}]) -> (\${HASH(CALLEE,name)}/\${HASH(CALLEE,extension)}) => \${EXTEN}@{$route['trunk1_name']},{$route['trunk2_name']}  )\n";
	                      if($route['trunk1_name'] == '' && $route['trunk2_name'] == ''){
	                         echo "  exten => {$route['dial_pattern']},n,Verbose(2,  E R R O R[\${tenant}]: OUTBOUND PEER is MISSING  for Call to \${EXTEN} )\n";
	                      }else{ 
					           if( $route['trunk1_name'] )     		 
					    		   echo "  exten => {$route['dial_pattern']},n,Dial(SIP/{$t1_pref}${num}@{$route['trunk1_name']},{$route['t1_dial_timeout']}{$OPT})\n";				    		   
					           if( $route['trunk2_name'] ){
					           	   echo "  exten => {$route['dial_pattern']},n,ExecIf($[ '\${DIALSTATUS}' = 'BUSY' ]?Goto(END-\${DIALSTATUS},1))\n";
					           	   echo "  exten => {$route['dial_pattern']},n,ExecIf($[ '\${DIALSTATUS}' = 'ANSWER' ]?Goto(END-\${DIALSTATUS},1))\n";
					    		   echo "  exten => {$route['dial_pattern']},n,Dial(SIP/{$t2_pref}${num}@{$route['trunk2_name']},{$route['t2_dial_timeout']}{$OPT})\n";
					           }
					      }  
	                   
		                      echo "   exten => {$route['dial_pattern']},n,Verbose(2, OUTBOUND END: ds:\${DIALSTATUS} hc:\${HANGUP_CAUSE} d:\${CDR(billsec)}  \${CDR(duration)}  )\n";
		                      echo "   exten => {$route['dial_pattern']},n,Goto(END-\${DIALSTATUS},1) \n\n\n";
                           
				        }
				   echo "\n\n";

                   echo "   exten => END-ANSWER,1,Hangup() \n";
                   echo "   exten => END-CONGESTION,1,Goto(general-{$TENANT_NAME}-error,s,1) \n";
                   echo "   exten => END-BUSY,1,Goto(general-{$TENANT_NAME}-error,s,1) \n";
                   echo "   exten => END-CHANUNAVAIL,1,Goto(general-{$TENANT_NAME}-error,s,1) \n";
                   echo "   exten => END-CANCEL,1,Goto(general-{$TENANT_NAME}-error,s,1) \n";
                   echo "   exten => END-NOANSWER,1,Goto(general-{$TENANT_NAME}-error,s,1) \n";	
		           echo "   exten => END-,1,Goto(general-{$TENANT_NAME}-error,s,1) \n";
		           echo "\n\n\n";  

			   }
  }	    
  
  
