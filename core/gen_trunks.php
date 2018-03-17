#!/usr/bin/php -q
<?php
  include_once(dirname( __DIR__)  . '/include/config.php');
  exec("echo ';;; Trunks Registrations ;;;' > /etc/asterisk/sip-register.tenants");
  $res = mysql_query("SELECT * FROM trunks WHERE ifnull(status,0) > 0");
  if (!$res) die(mysql_error()."\n");
  while($row = mysql_fetch_assoc($res) ){  
   echo "\n[{$row['name']}]\n" ;
   echo "type={$row['trunk_type']}\n";
   echo "host={$row['host']}\n";
   if ( $row['context'] )   echo "context={$row['context']}\n";
   if ( $row['secret'] ) echo "secret={$row['secret']}\n";
   if ( $row['defaultuser'] ) echo "defaultuser={$row['defaultuser']}\n";
   
   echo $row['other_options'];
/*   foreach( split("\n", $row['other_options']) as $pair){
     echo $pair."\n";
   }
 */ 
   echo "\n\n";
    
   if ( $row['sip_register'] )
     exec("echo 'register => {$row['name']}?{$row['register']}\n' >> /etc/asterisk/sip-register.tenants " );     	
 }
 
  
  
  ?>
