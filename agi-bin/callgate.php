#!/usr/bin/php -q
<?php
 set_time_limit(60);
 ob_implicit_flush(false);  //  turns off output buffering
 error_reporting(0);

  include_once '/var/www/html/agi-bin/connector.php';
  require_once('/var/www/html/agi-bin/lib/functions-agi.php');
  include_once('/var/www/html/agi-bin/lib/functions-ari.php');


  $call = new AGIInterface('/var/www/html/agi-bin/connector.ini');
  $call->DBConnect();

 
   $CID =  preg_replace('/[^0-9]/','',$call->get('agi_callerid') );
   $DNIS = preg_replace('/[^0-9]/','',$call->get('agi_dnid') );
   $EXT =  $call->get('agi_extension');
   $PEERIP = $call->get("CHANNEL(peerip)");
   $AGENT =  $call->get("CHANNEL(useragent)");

   $get = mysql_query("SELECT * FROM callgates
                          WHERE DID = '{$DNIS}' AND 
                                status != 'CANCELED' AND
                                ( calling = '${CID}' OR called = '${CID}' ) AND
                                DATE_ADD( ts, INTERVAL life_time SECOND ) > now() ");
   $row = mysql_fetch_assoc($get);
   
   $call->log("Call to '{$DNIS}' FROM '{$CID}' = '{$row['calling']}' - '{$row['called']}', Checking... SELECT * FROM callgates WHERE DID = '{$DNIS}' AND  DATE_ADD( ts, INTERVAL life_time SECOND ) > now() " );

    if( $row['DID'] ){      
      
         $FWD = ( $CID == $row['called'] ) ? $row['calling'] : $row['called'];         
         $FWD = preg_replace("/^\+/","",$FWD);
         $call->log("ORDER: FWD ->> : {$FWD} ");
         $call->tts( "Hello, you have active order, connecting with you driver");    
         $call->agi->set_var("CALLERID(num)", '+' . $DNIS  );
         $call->Dial("SIP/twilio-mike/+{$FWD}");

    }else{
       $call->tts("Hello, you have noactive orders, please hold, agent will answer shortly  ");      
       //$ret = $call->Dial("SIP/twilio-mike/+441618508378",60);
       $ret = $call->agi->exec("Queue","1111",60);
    
       $call->log(" Dial Status:" . json_encode($ret) );
    }

    
   // $call->tts("Welcome, operator will assist you shortly...");
   // $call->tts("Привет совки, что куда?",'ru',3,'google');
   // $call->tts("Добро пожаловать к нам в СОХО нэт?",'ru-RU',3,'yandex');
   // $call->tts("Добро пожаловать к нам в СОХО нэт?",'ru-RU',3,'yandex');
   // $call->tts("Привет добро пожаловать в такси Лондона  поехали",'ru-RU',3,'yandex');      

    $call->agi->Hangup();
  




?>

