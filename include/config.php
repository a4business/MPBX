<?php 

// PBX Main Configuration File //

   $config = new PBXConfig();
   $config->DBConect();
  
 
 class PBXConfig{ 	
 
 	 public function __construct(){
 	 	  $this->ini = parse_ini_file( 'config.ini' , true );
 	 }
 	    
    public function DBConect(){
       mysql_connect( $this->ini['DB']['host'] ,$this->ini['DB']['user'] , $this->ini['DB']['password']);
       @mysql_select_db( $this->ini['DB']['name'] ) or die( "Unable to select database");
    }	
    
 	 public function getPamiOptions(){
 	 	return array(
		    'log4php.properties' => __DIR__ . '/log4php.properties',    
		    'host'     => $this->ini['AMI']['host'],  
		    'scheme'   => 'tcp://',  
		    'port'     => $this->ini['AMI']['port'],  
		    'username' => $this->ini['AMI']['username'],  
		    'secret'   => $this->ini['AMI']['password'],  
		    'connect_timeout' => 100,  
		    'read_timeout' => 10000  
		 );
    }
    
    public function reload_dialplan(){
        exec("/var/www/html/core/gen_extensions.php > /etc/asterisk/extensions.tenants  ");
        exec("/var/www/html/core/gen_trunks.php >     /etc/asterisk/sip.tenants 2>&1 ");
        exec("/var/www/html/core/gen_parking.php >     /etc/asterisk/res_parking.tenants 2>&1 ");
        $ret = $this->ami_exec('dialplan reload');
        $ret = $this->ami_exec('sip reload');
    }   
    
    public function reload_sip(){
    	 $ret = $this->ami_exec('sip reload');
    }
         
    
    public function ami_exec($cmd){
        $socket = fsockopen( $this->ini['AMI']['host'] ,    $this->ini['AMI']['port'],  $errno, $errstr, 10);
        if ( !$socket ){
        	   return  array( 'response' => array( 'status' => 'FAIL', 'message' => "AMI Fail: $errstr ( $errno )\n" ) ); 
        	}
        fputs($socket, "Action: Login\r\n");
        fputs($socket, "UserName: {$this->ini['AMI']['username']}\r\n");
        fputs($socket, "Secret: {$this->ini['AMI']['password']}\r\n\r\n");
        usleep(500);
        fputs($socket, "Action: Command\r\n");
        fputs($socket, "Command: {$cmd}\r\n\r\n");
        usleep(900);
        fputs($socket, "Action: Logoff\r\n\r\n");
        usleep(900);
        $wrets=fgets($socket,128);
        $wrets=fgets($socket,128);
        fclose($socket);
        
        return  array( 'response' => array( 'status' => 'OK', 'message' => "AMI cmd [ $cmd ] - [ ${wrets} ]  \n", 'return' => $wrets ) );

   }
  }   		 
  
   
// Default Lang is English, unless other is defined in user session.
 $language = 'en';
 $lang = array(
   'txt_login' => 'Login:',
   'txt_password' => 'Password:'
 );




?>