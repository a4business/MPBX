#!/usr/bin/php -q
<?php
  include_once(dirname( __DIR__)  . '/include/config.php');
  $res = mysql_query("SELECT * FROM trunks WHERE ifnull(status,0) > 0 AND id > 0");  
  if (!$res) die(mysql_error()."\n");
  $registrations = '';  
  exec("echo ';;; Trunks Registration Strings ;;;' > /etc/asterisk/sip-register.tenants" );
  while($row = mysql_fetch_assoc($res) ){  
   echo "\n[{$row['name']}]\n" ;
   echo "type={$row['trunk_type']}\n";
   echo "host={$row['host']}\n";
   if($row['domain'])
    echo "fromdomain={$row['domain']}\n";
   
   if ( $row['context'] )   
     echo "context={$row['context']}\n";

   if ( $row['secret'] && $row['secret'] != '[encrypted]' )
     echo "secret={$row['secret']}\n";

   //elseif(trim($row['md5secret']) != '')
   //  echo "md5secret={$row['md5secret']}\n";

   if ( $row['defaultuser'] ){
   	  echo "defaultuser={$row['defaultuser']}\n"; 
   }	  
   
   echo $row['other_options'];

/*   foreach( split("\n", $row['other_options']) as $pair){
     echo $pair."\n";
   }
 */ 
   echo "\n\n";
   if ( $row['sip_register'] )
     $registrations .= "register => {$row['name']}?{$row['sip_register']}\n";
 }
 file_put_contents( '/etc/asterisk/sip-register.tenants', ";;; Registrations ;;;\n" . $registrations );
  
  
?>
