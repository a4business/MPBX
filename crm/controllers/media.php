<?php

class media{

      function play($recFile){
        global $PBX;
        if ( !$recFile ) {
           $PBX->log(" Media file not set ! :  " . $recFile  );
           header("HTTP/1.0 404 Not Found");           
        }   
        
         $_REC_PATH = "/var/spool/asterisk/monitor";
         // Preserve 23112323.232   requested files exten as part of the filename
         if( !preg_match('/[0-9]?./', pathinfo($recFile, PATHINFO_EXTENSION)))
           $file_request = pathinfo($recFile, PATHINFO_FILENAME);
         else
           $file_request = $recFile ;

         $F = trim( $_REC_PATH . '/' . $file_request ) ;

         $media_file = file_exists( $F ) ?$F : $media_file;  // TODO FIX FIX FIX
         $media_file = file_exists( $F . '.WAV') ? $F . '.WAV' : $media_file;  // TODO FIX FIX FIX
         $media_file = file_exists( $F . '.wav') ? $F . '.wav' : $media_file;  // TODO FIX FIX FIX
         
         if ( !file_exists( $media_file ) ) {
           $PBX->log(" Media not found :  " . $media_file  );
           header("HTTP/1.0 404 Not Found");           
         }else{         
          $tmp_mp3 = "/tts/file.mp3"; //  TODO: add randomg file name
          $tmp_wav = "/tts/file.wav"; //  TODO: add randomg file name
          if ( file_exists($tmp_mp3))  unlink($tmp_mp3);
          if ( file_exists($tmp_wav))  unlink($tmp_wav);        
          // Convert from ulaw to wav  //
          system("`which sox` '{$media_file}' -c 1 '{$tmp_wav}' 2>/dev/null");          
          // Convert  wav to mp3 //
          system("`which ffmpeg` -y  -i '$tmp_wav'  -acodec libmp3lame '$tmp_mp3' 2>/dev/null");
          $PBX->log("Play:  " . $media_file . " as mp3:" . $tmp_mp3 );  
   
          // PLAIING  PLAY FILE:
            if ( file_exists($tmp_mp3) ){              
              header('Cache-Control: no-cache');
              header("Content-Transfer-Encoding: binary");
              header("Content-Type: audio/mpeg");
              header('Accept-Ranges: bytes');
              header('Content-Disposition: inline; filename="' . $recFile . '.mp3"');
              header('Content-length: '.filesize($tmp_mp3));
              
              readfile($tmp_mp3);
            }else{
              $PBX->log(" MP3 transcode {$tmp_mp3} not found:  " . $tmp_mp3  );
              header("HTTP/1.0 404 Not Found");
            }
           
        }
      }  
 
}

?>