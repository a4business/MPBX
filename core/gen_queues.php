<?php

 include_once(dirname( __DIR__)  . '/include/config.php');
 
 function gen_queues_context() {
	  
	$res = mysql_query("SELECT *,t_queues.id as queue_id, t_queues.name as queue_name  
	                    FROM t_queues,tenants WHERE tenants.id = t_queues.tenant_id  ORDER BY t_queues.id  ");
   if (!$res) die(mysql_error()."\n");
   $items = array();
   
	while($row = mysql_fetch_assoc($res)){
      $_tenant = $row['ref_id'];
      $items[] = "[internal-{$_tenant}-queue-{$row['queue_id']}]";
      $items[] = " exten => s,1,Verbose(4,Starting Queue {$row['name']})";      
      $items[] = " exten => s,n,Answer()";
     	if( isset($row['queue_welcome'] ))   
        $items[] = " exten => s,n,Playback(snd_{$_tenant}/{$row['queue_welcome']})" ;
      if(isset($row['queue_musiconhold']))
        $items[] = " exten => s,n,Set(CHANNEL(musicclass)={$row['queue_musiconhold']})";
		$items[] = " exten => s,n,Queue({$row['queue_name']})\n" ;          	   
	}
	
	if (count($items))
	  foreach( $items as $item )
		     echo "  {$item} \n";
	
}