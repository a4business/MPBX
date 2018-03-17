#!/usr/bin/php -q
<?php

  include_once(dirname( __DIR__)  . '/include/config.php');
  
  function gen_parking_context() {
		  $res = mysql_query("SELECT *, ifnull(parkfindslot,'next') as parkfindslot,
												  ifnull(parkingtime,120) as parkingtime 
		  								FROM tenants");
		  if (!$res) die(mysql_error()."\n");
		  echo "\n\n";
		  while($row = mysql_fetch_assoc($res) ){
		  	 $tenant = $row['ref_id'];
		    $tenant_id = $row['id'];
		    $row['parkext'] = $row['parkext'] ? $row['parkext'] : '700';
		    $row['parkpos'] = $row['parkpos'] ? $row['parkpos'] : '10';
		    $row['parkedmusicclass'] = $row['parkedmusicclass'] ? $row['parkedmusicclass'] : 'default';
		    echo "[parkinglot-{$tenant}]\n";
		    echo "context  => parkingspace-{$tenant}\n";
		    echo "parkext  => {$row['parkext']}\n";
		    echo "parkinghints => yes\n";
		    echo "parkext_exclusive  => yes\n";
		    echo "parkpos  => " . ((int)$row['parkext'] + 1) . '-' . ((int)$row['parkext'] + 1 + $row['parkpos'] ) . "\n";
		    echo "findslot => {$row['parkfindslot']}\n";
		    echo "parkingtime => {$row['parkingtime']}\n";
		    echo "comebacktoorigin = {$row['parkcomebacktoorigin']}\n";
		    echo "parkedmusicclass => {$row['parkedmusicclass']}\n";
		    echo "\n\n";
		  }
  }
  
  
?>  