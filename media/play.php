<?php
 // Tenant PBX Player
 // Play media object - text2speech or File recording. //
 // by a4business.com //
 
 session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location:entrance.php");
    return;
 }
 
 //include_once('../include/config.php');
 include_once('../agi-bin/lib/functions-agi.php');

 $_TENANT_ID = (int)$_SESSION['tenantid'];
 $_TENANT = $_SESSION['tenantref'];
 
 

  $debug =0;
  $debug = ( $_GET['d'] ) ? $_GET['d'] : $debug;

  function dbg($txt){
    global $debug;
    if ($debug) echo "  $txt <br>";
  }
  $SOX = '/usr/local/bin/sox';
  $media_folder = "/var/lib/asterisk/sounds";  
  $_REC_PATH = "/var/spool/asterisk/monitor";
  $snd_lang = 'en'; 
  $moh_folder = "/var/lib/asterisk/";
  $cachedir = "/tts";
  $tts_lang_default = 'en-US_MichaelVoice';
  $supported_formats = array('.ogg','.wav','.WAV','.gsm','.au');
  $tts_tmp = '/tts/convert_tts.wav';
 
 $mode = isset( $_GET['mode'] ) ? $_GET['mode'] : 'tts';   // Posible announcements modes are: recording, tts, moh
 $TEXT = trim($_GET['play_text']);
 
 
 switch( $mode ){
 	 case 'cdrs':
 	   $ID = trim($_GET['uniqueid']) ;
 	   $media_file = $_REC_PATH . '/' . preg_replace(array('/\.wav/','/\.WAV/'),'',$ID );
 	    if ( !file_exists($media_file) )
             foreach( $supported_formats as $format )
              $media_file = file_exists( $media_file . $format ) ? $media_file . $format : $media_file;

 	   dbg(" CDR Recording Media file: $media_file<br>");
 	   break;
 	   
 	 case 'recording':
 	    // Default media dir to:   /var/lib/asterisk/sounds/snd_<tenant>/  
 	    //$LANG = preg_replace('/^tenant$|^SndTenants$/', 'snd_'.$_TENANT, trim(urldecode($_GET['play_dir'])) );
 	    //$REC_LANG = ( !isset($LANG ) || $LANG == '' || $LANG == 'undefined' || $LANG == 'null' ) ? 'en' : $LANG;
 
       $REC_LANG = ( preg_match('/^tenant$|^SndTenants$/', trim(urldecode($_GET['play_dir'])) ) ) ?  'snd_'.$_TENANT : 'en' ;
       
       $media_file = $media_folder . '/' . $REC_LANG . '/'. $TEXT;
       if ( !file_exists($media_file) )
        foreach( $supported_formats as $format )
      	 $media_file = file_exists( $media_file . $format ) ? $media_file . $format : $media_file;
 	       dbg(" {$_TENANT} RECORDING: '{$recording_name}' : Media File Name:  [ {$media_file} ]  Exists: " . (file_exists( $media_file )?'yes':'no') . '<br>');
 	   
       break;
       	    
    case 'tts':
 	    // Ok,  We have text2Speech function here //
       $PBX = new AGIInterface('../include/config.ini',10,false);
       $LANG = trim(urldecode($_GET['ttslang']));
       $TTSENGINE = 'auto';
       $media_file = $PBX->tts( $TEXT, $LANG ? $LANG : 'default' , $debug, $TTSENGINE ,true);   
      

       break;
       
     case 'moh':
       $media_file = $moh_folder . '/' . $_GET['play_dir'] . '/'. $TEXT;
       dbg(" PLAY MOH OBJECT:  {$media_file} " );
       
       break;
              
  }


  if ( !file_exists( $media_file ) ) {
    dbg( __FILE__ . ":  Failed to locate media file before play: $media_file ");
    header("HTTP/1.0 404 Not Found");
    return;
  }else  
    dbg("  PLAYING FILE: <b> [ $media_file ] </b> <br>");
          


 // Generate file to play for Browsers ( mp3 )
  $tmp_mp3 = "/tts/file.mp3"; //  TODO: add randomg file name
  $tmp_wav = "/tts/file.wav"; //  TODO: add randomg file name
  if ( file_exists($tmp_mp3))  unlink($tmp_mp3);
  if ( file_exists($tmp_wav))  unlink($tmp_wav);
  $CMD1 = "$SOX '{$media_file}' -c 1 '{$tmp_wav}' 2>/dev/null";
  system($CMD1);
    dbg("   --> Try to create  WAV format [ $CMD1 ] , RESULT: " . (file_exists( $tmp_wav )?'SUCESS':'FAIL') );
  $CMD2 = "`which ffmpeg` -y  -i '$tmp_wav'  -acodec libmp3lame '$tmp_mp3' 2>/dev/null"; 
  system($CMD2);
    dbg("   --> Try to create MP3 format [ $CMD2 ] , RESULT: " . (file_exists( $tmp_mp3 )?'SUCESS':'FAIL') );


//DOWNLOADING:  We support WAV and MP3 downloads:
  if ( $_GET['download_format'] ){
  	 $send_file =  ( $_GET['download_format'] == 'mp3' ) ? $tmp_mp3 : $tmp_wav ;
  	 dbg(" Sending File: $send_file ; Exists:" . (file_exists($send_file)?'YES':'NOT-FOUND') ); 
    if ( file_exists($send_file) ){
      header('Content-Disposition: filename="' . $send_file . '"');
      header('Content-length: '.filesize($send_file));
      header('Cache-Control: no-cache');
      header("Content-Transfer-Encoding: chunked");
      header("Content-Type: audio/wav");
      readfile($send_file);
    }else{
      header("HTTP/1.0 404 Not Found");
    }
  }

 if ($debug)  return;
   
// PLAIING  PLAY FILE:
  if ( file_exists($tmp_mp3) ){
  	 $ID = trim($_GET['uniqueid']) ;
    header('Content-Disposition: filename="' . $ID . '.mp3"');
    header('Content-length: '.filesize($tmp_mp3));
    header('Cache-Control: no-cache');
    header("Content-Transfer-Encoding: chunked"); 
    header("Content-Type: audio/mpeg");
    readfile($tmp_mp3);
  }else{
    header("HTTP/1.0 404 Not Found");
  }


 

?>
