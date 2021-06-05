<?php 

// PBX Main Configuration File //
 ini_set('display_errors', 0); 


   $config = new PBXConfig(); 
   $config->setTimeZone();
   $config->DBConect();

 
  
 
 class PBXConfig{ 	
 
 	 public function __construct(){
 	  $this->ini = parse_ini_file(  'config.ini' , true );
          $this->log = new Logger("/var/log/pbx.log");

	  // Default Ini Values:
 	  $this->auto_reload = is_null($this->ini['general']['auto_reload_config']) ? 1 : $this->ini['general']['auto_reload_config'] ;
 	 }

    public function setTimeZone(){
        $zone = isset($this->ini['general']['timezone']) ? $this->ini['general']['timezone'] : 'America/Toronto'; 
        date_default_timezone_set( $zone );
    }

    public function isAdmin(){
    	session_start();
    	return ( isset( $_SESSION['user']['role'] ) && $_SESSION['user']['role'] == 1 ) ? true : false;
    }
    
    public function getRole(){
    	session_start();
    	return  isset( $_SESSION['user']['role'] ) ?  $_SESSION['user']['role'] : 99;
    }
    
    public function isTenantAdmin(){
    	session_start();
    	return ( isset( $_SESSION['user']['role'] ) && $_SESSION['user']['role'] == 2 ) ? true : false;
    }

 
   public function getSMTPHost(){
	global $tenant_id;
        if ( isset( $this->ini['SMTP']['host'] ) )
         return $this->ini['SMTP']['host'] ;
        else           
         return  mysql_fetch_assoc( mysql_query("SELECT ifnull(smtp_host,'localhost') as smtp_host FROM tenants WHERE id = 0{$tenant_id} UNION SELECT '' as smtp_host") )['smtp_host'] ; 
    }

    public function getSMTPPort(){
	global $tenant_id;
        if ( isset( $this->ini['SMTP']['port'] ) )
         return $this->ini['SMTP']['port'] ;
        else           
         return  mysql_fetch_assoc( mysql_query("SELECT ifnull(smtp_port,'587') as smtp_port FROM tenants WHERE id = 0{$tenant_id} UNION SELECT 0 as smtp_port") )['smtp_port'] ;         
    }


    public function getSMTPUser(){
	global $tenant_id;
        if ( isset( $this->ini['SMTP']['user'] ) )
         return $this->ini['SMTP']['user'] ;
        else           
         return  mysql_fetch_assoc( mysql_query("SELECT ifnull(smtp_user,'47f31c2189ae3a') as smtp_user FROM tenants WHERE id = 0{$tenant_id} UNION SELECT ''  as smtp_user") )['smtp_user'] ;         
    }
      
       
    public function getSMTPPassword(){
	global $tenant_id;
        if ( isset( $this->ini['SMTP']['password'] ) )
         return $this->ini['SMTP']['password'] ;
        else           
         return  mysql_fetch_assoc( mysql_query("SELECT ifnull(smtp_password,'3116e65eed81a5') as smtp_password FROM tenants WHERE id = 0{$tenant_id} UNION SELECT '' as smtp_password ") )['smtp_password'] ;         
    }

        
    public function getSMTPFrom(){
	global $tenant_id;
        if ( isset( $this->ini['SMTP']['from'] ) )
         return $this->ini['SMTP']['from'] ;
        else           
         return  mysql_fetch_assoc( mysql_query("SELECT ifnull(smtp_from,'alerts@pbx') as smtp_from FROM tenants WHERE id = 0{$tenant_id} UNION SELECT '' as smtp_from") )['smtp_from'] ;         
    }    

    public function getSMTPFromName(){
	global $tenant_id;
        if ( isset( $this->ini['SMTP']['from_name'] ) )
         return $this->ini['SMTP']['from_name'] ;
        else           
         return  mysql_fetch_assoc( mysql_query("SELECT ifnull(smtp_from_name,'From name') as smtp_from_name FROM tenants WHERE tenant_id = 0{$tenant_id} UNION SELECT '' as smtp_from_name") )['smtp_from_name'] ;         
    }    


    
    public function isTenantUser(){
    	session_start();
    	return ( isset( $this->ini['user']['role'] ) && $this->ini['user']['role'] == 3 ) ? true : false;
    }		
  
  
    public function getUID(){
     session_start();
     return isset( $_SESSION['user']['id'] )  ? $_SESSION['user']['id'] : 0;
    }
    
    public function getDefaultLogo(){
     session_start();
     return $this->ini['general']['default_logo'] ? $this->ini['general']['default_logo'] : 'Logo.png';
    }
    
    public function getTTS_APIUser(){
     session_start();
     return $this->ini['TTS']['user'] ? $this->ini['TTS']['user'] : '93c19afa-f968-444b-93d8-935a8f987f90';
    }      
    
    public function getTTS_APIPass(){
     session_start();
     return $this->ini['TTS']['pass'] ? $this->ini['TTS']['pass'] : 'j3BklgxtJaH6';
    }   

    public function getTTS_APILang(){
     session_start();
     return $this->ini['TTS']['default_lang'] ? $this->ini['TTS']['default_lang'] : 'en-US_MichaelVoice';
    }   
    
    public function getTTS_APIURL(){
     session_start();
     return $this->ini['TTS']['url'] ? $this->ini['TTS']['url'] : 'stream.watsonplatform.net/text-to-speech/api/v1/synthesize';
    }     
    
    public function getSkin(){
     session_start();
     if($_SESSION['user']['gui_style'])
        return $_SESSION['user']['gui_style'];
     else   
      if( $this->isAdmin() )
     	  return $this->ini['general']['admin_default_skin'] ? $this->ini['general']['admin_default_skin']  : 'EnterpriseBlue';
      else
     	  return $this->ini['general']['users_default_skin'] ? $this->ini['general']['users_default_skin']  : 'Graphite';
    } 
    
     public function getLoginSkin(){
       session_start();
     	 return $this->ini['general']['login_default_skin'] ? $this->ini['general']['login_default_skin']  : 'EnterpriseBlue';
    } 	    
 	    
    public function DBConect(){

 	$link =  mysql_connect( $this->ini['DB']['host'] ,$this->ini['DB']['user'] , $this->ini['DB']['password']) or   die(mysql_error().'t' );
               
        mysql_set_charset('utf8',$link);
        @mysql_select_db( $this->ini['DB']['name'] ) or die( "Unable to select database: " . $this->ini['DB']['name'] . mysql_error() . ' Server:' . $this->ini['DB']['host'] );
    }	

   public function getExtensions($tenant_id){
      $extens = array();
      $res = mysql_query("SELECT name,first_name FROM t_sip_users WHERE tenant_id = {$tenant_id} ");
      if($res)
	while($row=mysql_fetch_assoc($res))
          $extens[] = $row;

      return $extens;
   }
    
    public function getPamiOptions(){
 	 	return array(
		    'log4php.properties' => __DIR__ . '/log4php.properties',    
		    'host'     => $this->ini['AMI']['host'],  
		    'scheme'   => 'tcp://',  
		    'port'     => $this->ini['AMI']['port'],  
		    'username' => $this->ini['AMI']['username'],  
		    'secret'   => $this->ini['AMI']['password'],  
		    'connect_timeout' => 500,  
		    'read_timeout' => 10000  
		 );
    }
    
    public function core_reload(){
    	// $ret = $this->ami_exec('core reload'); 
	    exec("/usr/sbin/rasterisk -rx 'core reload'");
	     exec( __DIR__ . "/../core/gen_extensions.php > /etc/asterisk/extensions.tenants  2>&1 ");

    }	    
    
    public function reload_dialplan(){

        exec( __DIR__ . "/../core/gen_extensions.php > /etc/asterisk/extensions.tenants  ");
        exec( __DIR__ . "/../core/gen_trunks.php >     /etc/asterisk/sip.tenants 2>&1 ");        

     // $ret = $this->ami_exec('dialplan reload');
     // $ret = $this->ami_exec('sip reload');

     // Replacement to avoid asterisk hungs :

	   exec("/usr/sbin/rasterisk -rx 'sip reload'");
	   exec("/usr/sbin/rasterisk -rx 'dialplan reload'");
           exec("/usr/sbin/rasterisk -rx 'queue reload all'");
    }   
    
    public function reload_sip(){
      // exec("/var/www/html/core/gen_sip_settings.php >     /etc/asterisk/sip.include 2>&1 ");
       exec("/usr/sbin/rasterisk -rx 'sip reload'");
    }
         
   public function click2call($EXTEN, $NUMBER, $tenant ){
      if( $EXTEN && $NUMBER  ){
         $options = array(
             'Channel' => "Local/{$EXTEN}@internal-{$tenant}-local",
             'Exten'   => $NUMBER,
             'Context' => "internal-{$tenant}-outbound",
             'Priority' => "1",
             'Async'    => 'yes',
             'WaitTime' => '120',             
             'Callerid' => "C2C Call<{$NUMBER}>\r\n"
         );
         return $this->ami_exec( $options, 'Originate' );
     }else{
      return  array( 'response' => array( 'status' => 'FAIL', 'message' => "C2C Fail: Request Incomplete()\n" ) ); 
     }

    }     
    
    public function ami_exec($cmd, $action = 'Command'){


        $socket = fsockopen( $this->ini['AMI']['host'] ,    $this->ini['AMI']['port'],  $errno, $errstr, 10);
        if ( !$socket ){
             return  array( 'response' => array( 'status' => 'FAIL', 'message' => "AMI Fail: $errstr ( $errno )\n" ) ); 
          }
        fputs($socket, "Action: Login\r\n");
        fputs($socket, "UserName: {$this->ini['AMI']['username']}\r\n");
        fputs($socket, "Secret: {$this->ini['AMI']['password']}\r\n\r\n");
        $wrets1=fgets($socket,128);
      
        switch($action){
          case 'Originate':
            fputs($socket, "Action: Originate\r\n");
            foreach($cmd as $key => $val)
              fputs($socket, "{$key}: {$val}\r\n");            

            break;

          default:
            fputs($socket, "Action: {$action}\r\n");
            fputs($socket, "Command: {$cmd}\r\n\r\n");
            break;
        }

        $wrets2=fgets($socket,256);
        usleep(500);
        fputs($socket, "Action: Logoff\r\n\r\n");
        usleep(500);
        $wrets3=fgets($socket,128);
        fclose($socket);
        
        return  array( 'response' => array( 'status' => 'OK', 'message' => "AMI Action: {$action}  cmd [ $cmd ] - [ ${wrets2} ]  \n", 'return' => $wrets3 ) );

   }

/*
  public function translate_text( $text, $to_lang = 'ru') {
          $URL ='https://apikey:'. this->ini['translator ']['apikey'] .'@' . preg_replace('https://','',this->ini['translator ']['url'] ) .
          $ch = curl_init( $URL );
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 1);
          curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.107 Safari/537.36');
          $data = curl_exec($ch);

  }
  */

  public function generate_tts( $text, $path ){

           $user = $this->getTTS_APIUser();
           $pass = $this->getTTS_APIPass();
           $url = $this->getTTS_APIURL();

           $accept = "audio/wav";
           $TTS_LANG = 'en-US_LisaVoice';
           $speed = 1;
           $cachedir = "/tts";
           $tts_tmp = '/tts/convert_tts.wav';
           $SOX = '/usr/local/bin/sox';
 

           
           if(!$text)
             return false;
         
          $filename = md5("{$text}..{$TTS_LANG}.{$speed}").'.sln';
          $media_file = $cachedir . "/" . $filename;
          if ( file_exists ($media_file ) && filesize( $media_file ) < 5 ) 
            unlink( $media_file );      
          
          //if ( file_exists($tts_tmp) ) 
          //  unlink($tts_tmp);

        echo  $media_file ;
        if( !file_exists ($media_file )  ){

          $URL = 'https://'.$user.':'.$pass.'@'.$url.'?accept=' . $accept . '&voice=' . $TTS_LANG . '&text=' . urlencode($text);
          //dbg("Call URL: $URL");       
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
           $CMD = "$SOX $tts_tmp  -t raw -q -r 8000 $media_file 2>/dev/null &"; 
           system($CMD);

          }else{
            return false;
          }

         } 

        
        // $path Example : /var/spool/asterisk/voicemail/scnd-vmdefault/209/
        if( file_exists ($media_file ) && $path ){

           if(!file_exists($path) ) mkdir($path);
           if(!file_exists($path) ) return false;
           
           $vm_greet = $path . '/' . 'greet.wav'; 
           if(file_exists($vm_greet)) unlink($vm_greet);
           system("$SOX $media_file -c1 -b16 -r8000  $vm_greet 2>/dev/null");
           //$res = copy($media_file, $vm_greet) ;
                      
         }

  }


  }   		 
  

class Logger {

    private
        $file,
        $timestamp;

    public function __construct($filename) {
        $this->file = $filename;
    }


    public function putLog($insert) {
            $ts = date("D M d y h:i A ");
            file_put_contents($this->file, $ts. ': '.$insert."\n", FILE_APPEND);

    }

    public function getLog() {
        $content = @file_get_contents($this->file);
        return $content;
    }

}


   
// Default Lang is English, unless other is defined in user session.
 $language = 'en';
 $lang = array(
   'txt_login' => 'Login:',
   'txt_password' => 'Password:'
 );

?>
