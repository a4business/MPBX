<?php
 //
 // fileManager: 
 //  Used to list, upload and Delete asterisk media files - MOH
 //



error_reporting(0);

ini_set('display_errors', 'Off'); 
ini_set( 'upload_max_filesize' , "150M" ) ;
ini_set( 'post_max_size', "300M");
ini_set( 'memory_limit', "500M" );

 
 session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location:  entrance.php");
    return;
 }
 $_TENANT_ID = (int)$_SESSION['tenantid'];
 $_TENANT = $_SESSION['tenantref'];

 $BASE_DIR = '/var/lib/asterisk';


 if( ($_GET['show'] == 'undefined') || ($_GET['del'] == 'undefined') ){  	   
  	    echo  json_encode( array('response' => array( 'status' => 'FAIL', 'message' => "Failed to proccess request: Wrong command !" ) ) );
  	    return; 
  } 

  if( !(count($_POST) || count($_GET)) && !count($_FILES) ){
  	   echo "<script type='text/javascript'> parent.isc.say('Failed to upload file! Make sure File size below PHP upload_max_filesize =  " . ini_get('upload_max_filesize') ." or post_max_size=" . ini_get('post_max_size') . " ');</script>";
  	   return;
  } 
  

if(!empty($_FILES['upload_file']))
  {
  	 $_err = $_FILES['upload_file']['error'];  	  	  
    // Directory - in fileManager is a reference for Uploaded file,  for CSVmanager.php upload - it is Master Row ID //
  	 $directory =  trim(urldecode($_POST['directory']));
    if ( $directory == 'tenant' )  $directory = 'sounds/snd_' . $_TENANT;
    if ( $directory == 'default' || !strlen($directory) ) $directory = 'sounds/en';
    
    if ( !file_exists( $BASE_DIR . '/' . $directory ) ){
      // Must be allowed for APACHE to create DIRs there!!!!!
    	mkdir($BASE_DIR . '/' . $directory);
    }	  	    	 
    $tmp_file_name = $BASE_DIR . '/' . $directory . '/tmp_' . $_FILES['upload_file']['name']; 
    
    $dst_file_name = ( $_POST['dst_filename'] )? $_POST['dst_filename'] : $_FILES['upload_file']['name'];
    list($f_name,$f_ext) = explode('.',basename($dst_file_name) );

    var_dump( $_FILES );
        
    if( !$_err && move_uploaded_file($_FILES['upload_file']['tmp_name'], $tmp_file_name)) {       
       $F_encoded  = $BASE_DIR . '/' . $directory . '/' . $f_name . '.wav' ;
       $F_original = $BASE_DIR . '/' . $directory . '/' . $dst_file_name ;
       if ( file_exists($F_encoded) ) unlink($F_encoded);  // We rewrite ?    
       exec("/usr/bin/ffmpeg -y  -i '{$tmp_file_name}'  -ar 8000 -ac 1  -acodec pcm_s16le -f wav '{$F_encoded}' 2>/dev/null" );          
       //exec("`which sox` {$tmp_file_name} -twav -b16 -r 8000 -c 1 {$F_encoded} 2>/dev/null");
       if ( !file_exists($F_encoded) ) exec("mv '{$tmp_file_name}' '{$F_original}' 2>/dev/null");  // We failed to convert - JUST put the original //
       unlink($tmp_file_name);
       
      echo "<script type='text/javascript'> 
              if (parent.uploadComplete) parent.uploadComplete('{$f_name}','{$directory}','{$_POST['sender_obj']}');
            </script>";    
    }else
      echo "<script type='text/javascript'> 
              parent.isc.say('Failed to upload the file! '+ '{$_err}');
              console.log( 'Failed to upload the file! '+ '[err:{$_err}] '+'[{$_FILES['upload_file']['tmp_name']}]' +' to '+ ' {$tmp_file_name}'  );
              if (parent.uploadComplete) parent.uploadComplete('{$f_name}','{$directory}','{$_POST['sender_obj']}');
            </script>";
    
  } 	
 	
 	
 if ( ( $_GET['del'] == '_mohfiles' ) ) {
    $_dir = ( $_POST['directory'] ) ? $_POST['directory'] : 'moh';
    $_file = $_POST['file_name'];    
    $_delFILE = "/var/lib/asterisk/{$_dir}/{$_file}";
    echo  json_encode( unlinkMediaFile($_delFILE ) );   
 }

 if ( $_GET['show'] == '_mohfiles' ){  
   $DIR = $_GET['directory'] ? $_GET['directory'] : 'moh';
   $PATH = $BASE_DIR .'/' . $DIR;
   $search = '';
   echo json_encode( MediaFilesListing($PATH, $search, $DIR ) );         
  }
  
 if ( $_GET['del'] == '_sndfilesDefault' || $_GET['del'] == '_sndfilesTenants' ) { 	 
    $_dir =  ( $_GET['del'] == '_sndfilesDefault' ) ? 'en' :  'snd_'.$_TENANT ;     // We del in sounds/en folder by default
    $_file = trim($_POST['file_name']);
    $_delFILE = $BASE_DIR  . "/sounds/{$_dir}/{$_file}";
    echo json_encode( unlinkMediaFile($_delFILE) );    	  
 }


  
  if ( $_GET['show'] == '_sndfilesDefault' || $_GET['show'] == '_sndfilesTenants' ){  
   //$DIR = $_GET['directory'] ?  $_GET['directory'] : 'sounds/en';  // Usualy it is sounds/<tenant> may be sounds_<tenant> in future (hi)
   $DIR = ( $_GET['show'] == '_sndfilesDefault' ) ?  'sounds/en' : 'sounds/snd_' . $_TENANT ;
   $PATH = $BASE_DIR .'/' . $DIR;
   if ( !file_exists($PATH) ) mkdir($PATH);   
   $search = $_POST['file_name'];
   echo json_encode( MediaFilesListing($PATH, $search, $DIR ) );
  }





function unlinkMediaFile($_delFILE){
   if ( file_exists($_delFILE) ){
    	 unlink($_delFILE);
    	 return   array ('file_name' => basename($_delFILE) );
    }else
    	 return   array('response' => array( 'status' => 'FAIL', 'message' => "Failed to locate FILE to DEL: {$_delFILE}" )) ;  
}    

 function MediaFilesListing($PATH, $search = '', $DIR, $limit = 100){ 
   $files = array();   
   if(is_dir($PATH))
     foreach ( glob("{$PATH}/*.*") as $MEDIA_FILE) 
       if( !is_dir($MEDIA_FILE) && (count($files) < $limit ) && (  !strlen($search) || preg_match('/'.$search.'/', $MEDIA_FILE ))  )
         $files[] = array( "file_name" => basename($MEDIA_FILE),
                           "size" => MakeReadable( filesize($MEDIA_FILE) ),
                           "format" =>  mime_content_type($MEDIA_FILE),
                           "duration" => exec("`which soxi`  -d $MEDIA_FILE 2>/dev/null|head -1"),
                           "directory" => $DIR
                         );
    return $files;                        
  }                           
  
  
 function MakeReadable($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
 }
 
?>
