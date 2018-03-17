<?php

  include_once(dirname( __DIR__)  . '/include/config.php');

 
  function gen_outbound_context(){
			    $rs = mysql_query("SELECT ref_id,
			  									  t_route.id as route_id,
			  									  t_route.name as route_name,
			  									  t_route.outbound_callerid as set_route_callerid,
			  									  tenants.outbound_callerid as tenant_callerid,
			  									  tenants.outbound_callername as tenant_callername	    									  
			  							  FROM t_route,tenants 
			  							  WHERE t_route.route_enabled = 1 AND
			  							        t_route.tenant_id = tenants.id ");
			    if (!$rs) die(mysql_error()."\n");			         
			    while($routes = mysql_fetch_assoc($rs) ){ 
			    	echo "[outbound-{$routes['ref_id']}-{$routes['route_id']}]\n";			    	
			    	$TENANT_FCALLER_ID = "{$routes['tenant_callerid']}";
			    	$TENANT_FCALLER_NAME = "{$routes['tenant_callername']}";
			    	$r = mysql_query("SELECT *,t1.name as trunk1_name, t2.name as trunk2_name,
			    	 								  t1.dial_timeout as t1_dial_timeout,
			    	 								  t2.dial_timeout as t2_dial_timeout,
			    	                          ifnull(concat(':',strip),'') as strip
			    	                    FROM routes_list 
			    	                         LEFT JOIN trunks t1 ON t1.id = routes_list.trunk_id    	                    		 
			    	                    		 LEFT JOIN trunks t2 ON t2.id = routes_list.trunk2_id
			    	                    WHERE 
			    	                         route_id = {$routes['route_id']}");
			      if (!$r) die(mysql_error()."\n");
				    	while($route = mysql_fetch_assoc($r)) {    		 
				          $num= $route['add_prefix'] ? $route['add_prefix']  . '${EXTEN'.$route['strip'].'}'  :  '${EXTEN'.$route['strip'].'}';
				          $OPT='';
				          echo "  exten => {$route['dial_pattern']},1,Verbose( Dial SIP/${num}@{$route['trunk1_name']}  \${CALLER_INFO} )\n";
				          if( !$routes['set_route_callerid'] )
                        echo "  exten => {$route['dial_pattern']},n,Macro(calleridManage,external,${TENANT_FCALLER_NAME},${TENANT_FCALLER_ID})\n";
                      else
                        $OPT = ",f({$routes['set_route_callerid']})"; // Overwrite CallerID  - DO forced (ignore tenant and exten settings )
                        
				          if( $route['trunk1_name'] )     		 
				    		   echo "  exten => {$route['dial_pattern']},n,Dial(SIP/${num}@{$route['trunk1_name']},{$route['t1_dial_timeout']}{$OPT})\n";
				          if( $route['trunk2_name'] )    		 
				    		   echo "  exten => {$route['dial_pattern']},n,Dial(SIP/${num}@{$route['trunk2_name']},{$route['t2_dial_timeout']}{$OPT})\n";
				    		   
				    		 echo "\n";  
				    	}
				   echo "\n";
			   }
  }	    
  
  