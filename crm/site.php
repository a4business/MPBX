<?php
  if(!isset($_SESSION['CRM_user']))
     session_start();

  error_reporting(1);
  

  require_once __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/include/config.php';  
  require_once __DIR__ . '/include/language.php';
  require_once __DIR__ . '/controllers/auth.php';
  require_once __DIR__ . '/controllers/crm.php';  
  require_once __DIR__ . '/controllers/CDR.php';
  require_once __DIR__ . '/controllers/realtime.php';
  require_once __DIR__ . '/controllers/media.php';
  

  try{

  
   if( !$PBX->ini ){
     warn("Failed to INIT Site Config file!",true);
   }


    Epi::init('api', 'route', 'database', 'template');
    Epi::setSetting('exceptions', true);    
    Epi::setPath('view', 'views');  
    EpiDatabase::employ('mysql', $PBX->ini['DB']['name'], 
                                 $PBX->ini['DB']['host'], 
                                 $PBX->ini['DB']['user'], 
                                 $PBX->ini['DB']['password']
	                     );
//    getDatabase()->execute("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $link);


    //$PBX->log(' Init Route:' . $_GET['__route__'] );

  // Main pages
    getRoute()->get('/',                  array('CRM', 'view' ) );    
    getRoute()->get('/crm',               array('CRM', 'view' ) );        
    getRoute()->get('/index',             array('CRM', 'view' ) );    
    getRoute()->get('/index.php',         array('CRM', 'view' ) );    
    getRoute()->get('/index.html',        array('CRM', 'view' ) );    
    
    getRoute()->get('/welcome',   array('CRM', 'view' ) );        
    
    getRoute()->post('/switchLang/(.*)',  array('crm', 'switchLang') );


    getRoute()->get('/phone',     array('CRM', 'view' ) );    
    getRoute()->get('/dashboard', array('CRM', 'view' ) );    
    getRoute()->get('/manager',   array('CRM', 'view' ) );    
    getRoute()->get('/operators', array('CRM', 'view' ) );    
    getRoute()->get('/cdrs',      array('CRM', 'view' ) );    

    
  // Site Authentication Procedures //
    getRoute()->post('/login',     array('Auth' , 'Login') );    
    getRoute()->get('/logout',     array('Auth',  'Logout') );    

    getRoute()->post('/save',     'saveDB' );

    getRoute()->post('/hangup/(.*)',    'CallHangup' );
    getRoute()->post('/rtcSwitch/(.*)/(.*)',  array('CRM', 'rtcSwitch' ) );
    
  // Call Groups on Top
    getRoute()->get('/groups',       array('CRM', 'GetGroups' ) );
    getRoute()->get('/groups/(.*)',  array('CRM', 'GetGroups' ) );

  // Call Queues - Inter-tenants as well    //
    getRoute()->get('/queues',       array('Realtime', 'getQueues' ) );
    getRoute()->get('/queues/(.*)',  array('Realtime', 'getQueues' ) );

 // OLD one 
    //getRoute()->get('/ccData',        array('CDR', 'runData' ) );    
    getRoute()->get('/ccData',        array('Realtime', 'getCalls' )  );    


    getRoute()->get('/realtime',      array('Realtime', 'getCalls' ) );    
    getRoute()->get('/cdrData/(.*)',  array('CDR', 'getCDRs' ) );
    getRoute()->get('/cdrData',       array('CDR', 'getCDRs' ) );    

    getRoute()->get('/play/(.*)',     array('media', 'play' ) );    


    getRoute()->post('/translate',  '_l' );    
    getRoute()->post('/translate/(.*)',  '_l' );   
    getRoute()->get("/assets/js/crm.dictionary.js",  'getDictionary' );

    //getRoute()->get('/get',       'showCDRdata' );
    //getRoute()->get('/get/(.*)',  'showCDRdata' );           
    

    getRoute()->run();

  } catch (Exception $e) {
    $PBX->log('['.basename(__FILE__) .']: Caught exception: '. $e->getMessage() ) ;
    warn( '['.basename(__FILE__)  . ']:  Caught exception: '. $e->getMessage() ) ;
    //die('Error!');
  }



 



 function saveDB(){

   var_dump($_POST);

 }


 function isay($text){
   echo  json_encode( array('error' => false,'message' => $text ) );
 }

 function warn($text, $shutdown = false ){
   echo json_encode( array('error' => true,'message' => $text ) );
   if($shutdown) exit;
 }

 function getParam($key, $defaultValue = null ) {
  if(isset($_POST[$key]))
    return $_POST[$key];
  else
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $defaultValue;
 }



?>
