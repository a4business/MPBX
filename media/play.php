<?php
 // Tenant PBX Player
 // Play media object - text2speech or File recording. //
 // by a4business.com //
 
 session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location:entrance.php");
    return;
 }
 $_TENANT_ID = (int)$_SESSION['tenantid'];
 $_TENANT = $_SESSION['tenantref'];
 
 

  $debug = 0;
  $debug = ( $_GET['d'] ) ? $_GET['d'] : $debug;
  function dbg($txt){
    global $debug;
    if ($debug) echo "  $txt <br>";
  }
  $SOX = '/usr/local/bin/sox';
  $media_folder = "/var/lib/asterisk/sounds";
  $snd_lang = 'en'; 
  $moh_folder = "/var/lib/asterisk/";
  $cachedir = "/tts";
  $tts_lang_default = 'en-US_MichaelVoice';
  $supported_formats = array('.ogg','.wav','.gsm','.au');
  $tts_tmp = '/tts/convert_tts.wav';
 
 $mode = isset( $_GET['mode'] ) ? $_GET['mode'] : 'tts';   // Posible announcements modes are: recording, tts, moh
 $TEXT = trim($_GET['play_text']);
 
 
 switch( $mode ){
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
	    $LANG = trim(urldecode($_GET['ttslang']));
	    $TTS_LANG = ( !isset($LANG ) || $LANG == '' || $LANG == 'null' || $LANG == 'undefined' ) ? 'en-US_LisaVoice' : $LANG; 
	    $speed = 1;
	    $filename = md5("{$TEXT}..{$TTS_LANG}.{$speed}").'.sln';
	    $media_file = $cachedir . "/" . $filename;
	    dbg( " TTS HASH Base: {$TEXT}..{$TTS_LANG}.{$speed}  ;  Hash Media file name: {$media_file} ; Exists:" .(file_exists( $media_file )?'yes':'no'). '<br>' );
	  	  
	   if ( file_exists ($media_file ) && filesize( $media_file ) < 1 ) 
        unlink( $media_file );      
      
      if ( file_exists ($media_file ) ){
         dbg("    TTS FILE ALREADY PRESEND IN CACHE: $media_file <br>");
       }else{	
	      // IBM TTS Account: //
	       $user = "93c19afa-f968-444b-93d8-935a8f987f90";
	       $pass = "j3BklgxtJaH6";
	       $accept = "audio/wav";
	       
	      if ( file_exists($tts_tmp) ) 
	        unlink($tts_tmp);
      	
	      $URL = 'https://'.$user.':'.$pass.'@stream.watsonplatform.net/text-to-speech/api/v1/synthesize?accept=' . $accept . '&voice=' . $TTS_LANG . '&text=' . urlencode($TEXT);
	      dbg("Call URL: $URL");	   
	      $ch = curl_init( $URL );
	      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
	      curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
	      curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 1);
	      curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.107 Safari/537.36');
	      $data = curl_exec($ch);
	      
	       $h = fopen( $tts_tmp, "w+");
	       fputs($h, $data);
	       fclose($h);
	      if (file_exists( $tts_tmp )){
           $CMD = "$SOX $tts_tmp -t raw -q -r 8000 $media_file 2>/dev/null"; 
	        system($CMD);
           dbg("  Convert received SLN  file to WAV: [ {$CMD} ]  RESULT: " . (file_exists( $media_file )?'SUCESS':'FAIL'));
	      }else 
	        dbg("  Failed to receive Media File $tts_tmp <br>");
	     
       }
       break;
       
     case 'moh':
       dbg(" PLAY MOH OBJECT " );
       break;
              
  }


  if ( !file_exists( $media_file ) ) {
    dbg(" Failed to locate media file before play: $media_file ");
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
    header('Content-Disposition: filename="' . $tmp_mp3 . '"');
    header('Content-length: '.filesize($tmp_mp3));
    header('Cache-Control: no-cache');
    header("Content-Transfer-Encoding: chunked"); 
    header("Content-Type: audio/mpeg");
    readfile($tmp_mp3);
  }else{
    header("HTTP/1.0 404 Not Found");
  }


 

?>
