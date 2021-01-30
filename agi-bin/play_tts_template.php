#!/usr/bin/php7.3  -q
<?php

 set_time_limit(60);
 ob_implicit_flush(false);  //  turns off output buffering
 error_reporting(0);

  include_once('lib/functions-agi.php');
  $call = new AGIInterface('/var/lib/asterisk/agi-bin/config.ini');    
  $tags = json_decode( $call->get('X-CRM-TAGS') ,true);

  $LANG = $argv[1];
  $TXT =  $argv[2];

  if( preg_match('/|/', $TXT )){
     list( $TXT, $TXT_FOLL)  = explode('|', $TXT,2);
  }	  

  function mk_preg($n){
      return '/'.$n.'/';
  }
  // Example of execution: 
  // AGI(play_tts_template.php,ru_RU,Вас приветствует группа тестирования колцентра firm alert1 alert2 | Вас приветствует единая группа тестирования )
  // if firm alert1 alert2 is defined in  [X-CRM-TAGS], it will be replaced  - in other case just removed from the text
  // TXT_FOLL - played if no any tags received for this call

  // If no tags - this unknown caller , we play text after '|' delimiter (txt_foll) 
  // if we have tags - parce all tags and replace keys in text with values
  if(is_array($tags) && count($tags) > 3 ){
     foreach(  array('alert','alert1','alert2','alert3','alert4','alert5' )  as $v)
	     $tags[$v] = isset($tags[$v]) ? $tags[$v] : '';

     $TXT = preg_replace( array_map('mk_preg',array_keys($tags) ), array_values($tags), $TXT ) ;
  }else
     $TXT = $TXT_FOLL ? $TXT_FOLL : $TXT;

  $call->log("Playing.. LANG: {$LANG} Text:[ {$TXT} ]");
  $call->tts($TXT, $LANG, 0, 'auto');


  // Check for Menu TAG here ?? or use separate  context script better ? 
  //  this template player triggers when Media Type set to 'Text2Speech text template is se ton IVR menu 


?>
