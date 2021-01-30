<?php   

 

 use PAMI\Client\Impl\ClientImpl as PamiClient;   
 use PAMI\Listener\IEventListener;
 use PAMI\Message\Event\EventMessage; 
 use PAMI\Message\Action\GetVarAction;
 use PAMI\Message\Action\CommandAction;

 $PBX =  new PBXConfig('include/config.ini');     

 class PBXConfig{ 	

         
	     public function __construct( $ini = 'config.ini' ){	     	  
	 	  $this->ini = file_exists($ini) ? parse_ini_file(  $ini , true ) : array();
	          $this->Logger = new Logger( isset($this->ini['general']['log']) ?  $this->ini['general']['log'] : "/var/log/pbx.log"); 	 	  
		try{
	          $this->pami =  new PamiClient( array(
				    'log4php.properties' => __DIR__ . '/log4php.properties',    
				    'host' => 'localhost',  
				    'scheme' => 'tcp://',  
				    'port' => 5038,  
				    'username' => 'pbx-manager-dev',  
				    'secret' => '92jdf3hfdf',  
				    'connect_timeout' => 10000,  
				    'read_timeout' => 10000  
				 ) );	 
	        }catch(Exception $e) {
 		   echo 'Caught exception: ',  $e->getMessage(), "\n";
		} 
	 }
 
         public function log($message){
         	$this->Logger->log($message);
         }         


         public function run($cmd){
         	$this->pami->open();  
         	// Read TImeout error here 
           	$r = $this->pami->send(new CommandAction( $cmd ) );
           	usleep(1000);
           	$this->pami->process();
           	$this->pami->close();  
            return $r;            
         }

 	    public function DBConect(){
	       $this->dblink = mysql_connect( $this->ini['DB']['host'] ,$this->ini['DB']['user'] , $this->ini['DB']['password']);
	       @mysql_select_db( $this->ini['DB']['name'] ) or die( "Unable to select database");
//	       @mysql_set_charset( 'utf8', $this->dblink );
 
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

	    public function log($insert)
	    {
	        $ts = date("m-d-y H:i ");
	        file_put_contents($this->file, $ts . ':[ ' . $_SERVER['REMOTE_ADDR'] .' ] ' . $insert . "\n", FILE_APPEND);
	    }

	    public function getLog()
	    {
	        $content = @file_get_contents($this->file);
	        return $content;
	    }
	}

 ?>
