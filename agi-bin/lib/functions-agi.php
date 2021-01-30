<?php

include_once 'phpagi.php'; 

//
// By george@a4business.com
//

class AGIInterface
{
    public $mysqli;
    public $tagsHistory;

    public function __construct($ini='',$debug = 0, $online = true )
    { 
        if($online){
         $this->agi = new AGI();
        }
        $this->logger = new Logger("/var/log/asterisk-agi.log");

        if ( file_exists($ini) ){
            $this->ini = parse_ini_file($ini, true);        
            $this->debug_level = isset($this->ini['general']['debug'])?$this->ini['general']['debug']:$debug;
            $this->log("AGI: Initialized! [{$ini}]");
        }else{
        	$this->log('AGI: Failed to get INI file:' . $ini );
        }    
    }
  
    public function DBConnect(){
       mysql_connect( $this->ini['DB']['host'] ,$this->ini['DB']['user'] , $this->ini['DB']['password']);
       @mysql_select_db( $this->ini['DB']['name'] ) or die( "Unable to select database");
    }	

    
    public function getTags($params = ''){
	if( $params == 'StepBack' ){
	  if( count($this->tagsHistory) > 1 )
	    $removed_step = array_pop( $this->tagsHistory );

          return end( $this->tagsHistory );
	}

        $NUM = $this->get('agi_callerid');
        $NUM = preg_replace('/[^0-9]/','', $NUM );
        $NUM = preg_replace('/^0/','380', $NUM );
        $NUM = (strlen($NUM) == 5) ? '38048' . $NUM : $NUM ;
	// For debug run custom URL for certain number  //
        if( isset( $this->ini[$NUM] ) )
          $URL = $this->ini[$NUM]['billingurl'] . $NUM;
        else
          $URL = $this->ini['external']['billingurl'] . $NUM;

	 if($params)
	  $URL = $URL . '&' . $params;

       if( strlen($NUM) > 3 ){
        $this->log("RUN CONTEXT SCRIPT API GET: [ $URL ]  )");
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $JSON = json_decode( $data, true );
	$JSON = ( count($JSON) == 1 ) ? $JSON[0] : $JSON ;

	if( is_array( $JSON ) ){
	 // array_push( $this->tagsHistory , $JSON );
	  $this->tagsHistory[] = $JSON;	
	  return end( $this->tagsHistory );
	}else
	  return false;

      }

    }

    public function Dial( $DEST,$TIMEOUT = 60,$OPTIONS=''){
        // "twilio-mike/+44-1-61-850-8378"
        // * Dial returns ${CAUSECODE}: If the dial failed, this is the errormessage.
        // * Dial returns ${DIALSTATUS}: Text code returning status of last dial attempt.
        // * @return array, see evaluate for return information.
        return $this->agi->exec( 'Dial', "{$DEST},{$TIMEOUT},${OPTIONS}" );
    }

    public function tts( $text, $voice, $debug = 0, $engine = 'ibm' , $just_return_file_name = false, $escape = '' ){
        // Engines:  ibm, yandex, google 
        $debug = isset($debug)  ? $debug  : $this->debug_level;        
      	$text = trim($text);
        if(!$text)
           return; 
        $voice = ( $voice && $voice != 'default' ) ? $voice : 'en-US_MichaelVoice';
        $cachedir = "/tts";        
        $CURL = "/usr/bin/curl";
        $WGET = "/usr/bin/wget";
        $FFMPEG = "/usr/bin/ffmpeg";
        if( !file_exists( $FFMPEG) || !file_exists("/usr/bin/sox") ){
           $this->log( "ERROR ERROR ERROR :  Failed to locate: {$FFMPEG} ");  
        }
        $seed = "{$engine}_{$text}..{$voice}.1";
        $filename = md5($seed).'.sln';
        $fullpath = "{$cachedir}/{$filename}";
        if ( file_exists($fullpath) &&  filesize( $fullpath ) < 5 ) {
          unlink($fullpath);
        }        
        //$this->log( " Files seed: [ {$seed} ] ", $debug );
        //$this->log( " Final hash: {$fullpath}" . ( file_exists($fullpath)?' :CACHED':''),$debug);
        $tmp = '/tts/convert_tts.wav';
        $mp3_tmp = "/tts/g_convert.mp3";
         
       // $this->log( $fullpath);

   // Check if we have file in CACHE //
   if(!file_exists($fullpath) ) {
       
        // Detect ENGINE BY LANG ( if not set ) //
        if( isset($engine) && $engine == 'auto'){
      	  if ( $voice == trim($this->ini['tts-google']['default_voice']) ) 
                   $engine = 'google'; 
      	  elseif ( $voice == trim($this->ini['tts-yandex']['default_voice']) ) 
      	     $engine = 'yandex'; 
      	  else
       	     $engine = 'ibm';

          $this->log(" NOTE: AUTO-SELECT [ {$engine} ] by Default voice[{$voice}] "); 
        }

        if( !$this->ini["tts-{$engine}"] ){
           $this->log("TTS Error: Missing [tts-{$engine}] TTS API config ini section!");
           return;
        }

       unlink($mp3_tmp);
       unlink($tmp);
       switch($engine){

        case 'ibm':       
          $voice = ( $voice && $voice != 'default'  ) ? $voice : $this->ini['tts-ibm']['default_voice'];
          $URL=$this->ini['tts-ibm']['url']."&voice={$voice}&text='".urlencode($text)."'";
          list($user,$pw ) = array( $this->ini['tts-ibm']['user'] , $this->ini['tts-ibm']['pass'] );
          $CMD = "${CURL} -X POST -u '{$user}:{$pw}' --output {$tmp}  '{$URL}'";         
          $this->log( " SUBMIT [{$engine}] CMD: {$CMD} ",$debug );       	 
          exec($CMD,$r);
          break;

        case 'google':
          $voice = ( $voice && $voice != 'default'  ) ? $voice : $this->ini['tts-google']['default_voice'];
          $URL = $this->ini['tts-google']['url'] . "&tl={$voice}&q=" . urlencode($text);
          $CMD = "{$WGET}  -U Mozilla  '{$URL}' -O {$mp3_tmp} && {$FFMPEG} -i {$mp3_tmp} {$tmp}"; ;
          $this->log( " SUBMIT [{$engine}] CMD: {$CMD}",$debug );                               
          exec($CMD,$r);          
          break;
        
        case 'yandex':
          $voice = ( $voice && $voice != 'default'  ) ? $voice : $this->ini['tts-yandex']['default_voice'];
          $URL = $this->ini['tts-yandex']['url'] . "&lang={$voice}&text=" . urlencode($text) ;
          $CMD = "{$WGET} --no-check-certificate  '{$URL}' -O {$mp3_tmp} && {$FFMPEG} -i {$mp3_tmp} {$tmp}"; 
          $this->log( " SUBMIT [{$engine}] CMD: {$CMD}", $debug );                               
          exec($CMD,$r);                    
          break;

       }


         // CONVERT WAV IT TO PCM//
    	  if( !file_exists($tmp) ){
    	  	$this->log("ERROR: FAILED to get TTS FROM '{$engine}' [ File:${tmp} ]  ", $debug);
    	  	return;
    	  }else{         
    	    $convert_cmd = "/usr/bin/sox {$tmp} -t raw -q -r 8000 {$fullpath} 2>/dev/null";
    	    $this->log($convert_cmd, $debug);
    	    system($convert_cmd);

    	  } 

     }   

     // Check if File still not exists ( tts  did not work) //
     if( !file_exists( $fullpath ) ) {
       $this->log("ERROR tts: Failed to convert TTS, [{$tmp}]" , $debug);
       return;
     }else{
    	 $this->log(" [{$text}] $escape lang:{$engine}: Play: {$fullpath}", $debug);
     }

     if(  $just_return_file_name  ){
       return  $fullpath;       
     }else{     
       $this->agi->Answer();
       if($escape)
	 return $this->agi->stream_file( trim ( $fullpath, '.sln'), $escape );
       else
	 $this->agi->stream_file( trim ( $fullpath, '.sln') );  
     }  
  
   }

  
    public function Ringing(){
    	$this->agi->exec('Ringing');
    }

    public function CDR( $json_cdr ){    	
    	 foreach( json_decode($json_cdr,true) as $field => $value )
    	   $this->agi->set_variable("CDR({$field})", $value );	
    }

    public function log($entry, $level = 1) {        
        if (!is_numeric($level)||!isset($level)) {
                $level = $this->debug_level;
        }
        if( $level != -1 ){
	        $entry = str_replace(array('"',','),array('\"',' '),$entry);

          if( isset($this->agi) ){
            if( isset($this->ini) && $this->ini['general']['agi-debug'] == 1 )
	             $this->agi->exec("VERBOSE \"DEBUG: $entry\"", $level);
          }

	        $this->logger->putLog( $entry );
	    }    
     }

   public  function set( $variable_name ) {
      
        $r = $this->agi->set_variable( $variable_name );
          if ($r['result'] == 1) {
                  $result = $r['data'];
                  return $result;
          }
   }    
      


    public  function get( $variable_name ) {
    	if( preg_match('/^agi_/', $variable_name ) ){
    	  return isset($this->agi->request[ $variable_name ]) ? $this->agi->request[ $variable_name ]:'';
    	}else{	
	      $r = $this->agi->get_variable( $variable_name );
	        if ($r['result'] == 1) {
	                $result = $r['data'];
	                return $result;
	        }
	    }    
         return '';
    }


	public function callerid(){
	     return $this->agi->request['agi_callerid'];
	}  

    function __set($name,$value){
	    if(method_exists($this, $name)){
	      $this->$name($value);
	    }else{
	       $this->$name = $value;
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



class Logger
{

    private
        $file,
        $timestamp;

    public function __construct($filename)
    {
        $this->file = $filename;
    }


    public function putLog($insert)
    {
        $ts = date("D M d y h:i A");
        file_put_contents($this->file, $ts . ': ' . $insert . "\n", FILE_APPEND);

    }

    public function getLog()
    {
        $content = @file_get_contents($this->file);
        return $content;
    }

}

?>
