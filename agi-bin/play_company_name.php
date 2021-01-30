#!/usr/bin/php7.3  -q
<?php

 set_time_limit(60);
 ob_implicit_flush(false);  //  turns off output buffering
 error_reporting(0);

  $welcome = 'Вас приветствует служба технической поддержки компании';

  include_once('lib/functions-agi.php');
  $call = new AGIInterface('/var/lib/asterisk/agi-bin/config.ini');    
  $tags = json_decode( $call->get('X-CRM-TAGS') ,true);

  if(isset($tags['firm'] ))
     $call->tts( $welcome . ' ' . $tags['firm'], 'ru_RU', 0, 'auto');          
  else
     $call->tts( 'Вас приветствует единая служба технической поддержки',  'ru_RU', 0, 'auto' );

?>
