<?php

require_once("/var/www/html/vendor/autoload.php");

class ARIInterface
{
	private  $file,$timestamp;
    public $mysqli;
    public function __construct($ini='', $agi, $debug = -1)
    {	  
	  $this->ari = new phpari();       
	  if($agi)
	    $this->agi = $agi;       

	  if ( file_exists($ini) ){
        $this->ini = parse_ini_file($ini, true);                
        $this->log_file = isset($this->ini['general']['log']) ? $this->ini['general']['log'] : "/var/log/ast.log";
        $this->debug_level = isset($this->ini['general']['debug'])?$this->ini['general']['debug'] : $debug;
        $this->log("ARI: Initialized! [{$ini}]");
	 }else{
	   	$this->log('ARI: Failed to get INI file:' . $ini );
	 }    
    }

    public function get_online( $users ){
       $filtered_ep = array();
       if( is_array($users) && !count($users) ){	 
       	 return array();
       }
       
      foreach ( $this->sip_endpoints as $key => $endpoint ) 
        if(is_numeric($endpoint['resource']) && array_key_exists ($endpoint['resource'], $users)){
         if($endpoint['state'] == 'online') 	
           $filtered_ep[$endpoint['resource']] = $endpoint; 
         $this->log(" SIP PEER: {$endpoint['resource']} / {$endpoint['state']}",1);
      }    
      return $filtered_ep;
   }  

    public function sip_endpoints(){    	
    	return  $this->ari->endpoints()->show('SIP');    	
    } 	

    public function get_channels(){
     
      $channels =  $this->ari->channels()->show();
      $final_channels = array();
      foreach ($channels as $channel) {
        $final_channels[$channel['id']] = $channel;
        $this->log('Channel:' . $channel['id'] ) ;
      }
    }

    public function log($entry, $level = 1) {        
        if (!is_numeric($level)||!isset($level)) {
                $level = $this->debug_level;
        }
        if( $level != -1 ){
	        $entry = str_replace(array('"',','),array('\"',' '),$entry);	
	        if($this->agi)
	           $this->agi->exec("VERBOSE \"DEBUG: $entry\"", $level);       
	        file_put_contents($this->log_file, date("D M d y h:i A") . ': ARI:' . $entry . "\n", FILE_APPEND);
	    }    
     }


      function __get($name){
	    if(method_exists($this, $name)){
	      return $this->$name();
	    }
	    elseif(property_exists($this,$name)){
	      // Getter/Setter not defined so return property if it exists
	      return $this->$name;
	    }
	    return null;
    }
  
 }   


 
  

?>
