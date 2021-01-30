#!/usr/bin/php7.3  -q
<?php

 set_time_limit(60);
 ob_implicit_flush(false);  //  turns off output buffering
 error_reporting(0);


  include_once('lib/functions-agi.php');
  $call = new AGIInterface('/var/lib/asterisk/agi-bin/config.ini');

   $CALL_TAGS = $call->get('X-CRM-TAGS');   

   // Save Tags Variable Into Current  CDRs
   if( $CALL_TAGS )
      $call->agi->set_variable('CDR(tags)', $CALL_TAGS ); 

   $tags = json_decode($CALL_TAGS,true);
   
// Debug
   if(is_array($tags)){    
    foreach( $tags as $key=>$tag ){
      $call->agi->verbose($key .':'.$tag ) ;
    }
   }


  if(isset($tags['balance'])){

      $call->tts('Ваш баланс' , 'ru_RU', 0, 'auto');          
      $call->agi->say_number((int)$tags['balance'],'','f'); 
      $balance  = preg_replace('/[^0-9]/','',round($tags['balance']) );
      if(strlen($balance) > 1 )   // check for 11-19 interval
	      $switch = (substr((string)$balance,-2,1) == '1' )? '0' : substr((string)$balance,-1);
      else 
	      $switch = $balance;

      switch( $switch ){
          case "1":              
           $call->tts('гривна' , 'ru_RU', 0, 'auto');                            
           break;
           ;;
          case "2":
          case "3":
          case "4":
           $call->tts('гривны' , 'ru_RU', 0, 'auto');                            
           break;
           ;;
          default:
           $call->tts('гривен' , 'ru_RU', 0, 'auto');                            
           ;;              
         }  
   }
     
   

?>

