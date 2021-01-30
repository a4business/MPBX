#!/usr/bin/php -q
<?php

 include_once('include/config.php');

 $IP=file_get_contents("http://ifconfig.so");

 echo "localnet=192.168.0.0/255.255.0.0\n";
 echo "localnet=10.25.0.0/255.255.0.0\n";
 echo "externaddr = {$IP}\n";

if($IP){
 $res = mysql_query("SELECT * FROM sip_conf WHERE var_name = 'externaddr'");
 if ( !$res ){
  mysql_query("INSERT INTO sip_conf values(1,0,0,'sip.conf','general', 'externaddr', '{$IP}',0,'External server address  detected automatically') ");
  $config->reload_sip();
 }else{
    $row = mysql_fetch_assoc($res);
    if( $row['var_val'] != $IP ){
      mysql_query("UPDATE sip_conf SET var_val = '{$IP}' WHERE var_name = 'externaddr'");
      $config->reload_sip();
    }
 }

}


?>
