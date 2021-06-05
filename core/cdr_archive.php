#!/usr/bin/php -q
<?php
//
//  SCript to  archive OLD cdrs 
//
  $interval = $argv[1] ? $argv[1] : 90 ;
  include_once(dirname( __DIR__)  . '/include/config.php');
  // Archive only on overloaded DB: //
  $rows = mysql_fetch_assoc(mysql_query("SELECT table_rows as cnt FROM information_schema.tables WHERE table_name='t_cdrs' AND table_schema='etor_pbx'"));
  echo date('m/d/y H:m'). " Total CDRs:" . $rows['cnt'] ;
 if( $rows['cnt'] > 100000 ){
  if(!mysql_query("SELECT 1 FROM t_cdrs_archive LIMIT 1")){
    echo " Init Archive... ";
    mysql_query("CREATE TABLE t_cdrs_archive like t_cdrs");
  }
  $result = mysql_fetch_assoc(mysql_query("SELECT count(*) as cnt FROM t_cdrs WHERE datediff(now(),calldate) > {$interval}"));
  if( isset($result['cnt']) && $result['cnt'] > 10  ){
        echo " [] CDRs, older then {$interval}: {$result['cnt']} , Move t_cdrs --> t_cdrs_archive :";
          mysql_query("INSERT INTO t_cdrs_archive SELECT * FROM t_cdrs WHERE datediff(now(),calldate) > {$interval}");
          mysql_query("DELETE FROM t_cdrs WHERE datediff(now(),calldate) > {$interval}");
	echo " [] Done: Deleted:" . mysql_affected_rows() . " rows \n"; 
  }else{
    echo " [] No old cdrs to clear \n";
  }
 }


?>
