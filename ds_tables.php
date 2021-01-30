<?php

//error_reporting(E_ALL);
//
//  DataSources for DataTables()   he!  for those systems, where upgrade not possible and integrating just one page is  best method! 
// 

 if(!$_SESSION)
   session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location: index.php");
 }

 include_once('vendor/autoload.php');
 include_once('include/config.php');
 include_once('include/datatables.php');

  Epi::init('api', 'route', 'database', 'template');
  Epi::setSetting('exceptions', true);
  EpiDatabase::employ('mysql', $config->ini['DB']['name'],
                               $config->ini['DB']['host'],
                               $config->ini['DB']['user'],
                               $config->ini['DB']['password']
                           ) or die(' DB Employ ERROR');

  
  $operators = $config->getExtensions( $_SESSION['tenantid'] );
  $_tenant_id = (int)$_SESSION['tenantid'];
  $_tenant_name = $_SESSION['tenantname'];;


    
// Merge Requests together //
$params_array = array_merge($_POST,$_GET);
// Explode into var names: 
foreach ( $params_array as $key => $value) { ${$key} = $value; }   // strymno



header('Content-Type: application/json');
// Table switcher : // 
switch( $tbl ){

   case 'recordings':
        
      $json = array();
      $data = array();

      $fields = array( 
        'uniqueid' => '#ID#',
        'calldate' => 'Call date',
        'direction' => 'Type',
        'src' => 'From',
        'dst' => 'To',
        'duration'  =>  'Duration',
        'billsec'   => 'Talk',
        'disposition' => 'Result',        
        'channel'  => 'Channel',
        'dstchannel'  => 'Channel',
        'recording' => 'Recording',
        'tenant_id' => 'Recording'
      );

      $LIMIT = $_GET['length'] ? $_GET['length'] : 100;
      $OPTIONS = DataTables::getSorter($fields); 
      $WHERE   = DataTables::getFilter($fields);      
      

      $SQL = "SELECT " . implode(',', array_keys($fields)) . "  FROM t_cdrs ";

      try {
         $_rows = getDatabase()->all( "{$SQL} {$WHERE} {$OPTIONS}" ) ;
      }catch(Exception $e) {
         echo 'Exception: ' . $e->getMessage() ;
         return;
      }    
  
      if ( $_rows )
    	  foreach( $_rows as $_row){
	   $record = null;
 	   if( isset($_row['recording']) && file_exists( "/var/spool/asterisk/monitor/{$_row['recording']}" ) &&
					    filesize( "/var/spool/asterisk/monitor/{$_row['recording']}" ) > 61 ){
             $record = $_row['recording'];
           }
	   if(!$record && file_exists("/var/spool/asterisk/monitor/{$_row['uniqueid']}.WAV") && 
                           filesize("/var/spool/asterisk/monitor/{$_row['uniqueid']}.WAV") > 61 ){
	     $record = $_row['uniqueid'];
           }

           $_row['recording'] = ($record) ? "<div class=audio ><audio src='media/play.php?mode=cdrs&uniqueid={$_row['recording']}' preload='auto' controls></audio></div>" : '' ; 
          unset( $_row['tenant_id']);

          //$_row['has_recording']  = $is_recorded;
           $_row['dstchannel'] =  DataTables::chanDescribed($_row['dstchannel'], $_row['channel'], $_tenant_id );
           $_row['channel']    =  DataTables::chanDescribed($_row['channel'], $_row['dstchannel'], $_tenant_id );

          if( (count($data)) < $LIMIT  )           
            $data[] = $_row;
         }
       else{
         $data = array( 'response' => array( 'status' => 'NO DATA', 'message' => "NO ROWS! {$SQL} {$WHERE} {$OPTIONS} " )  ) ;  
       }


   break;

 }


  

  $json = array();
  $json['data'] = $data;
  $json['draw'] = $_GET['draw'] ? $_GET['draw'] : 1;
  $json['recordsTotal'] = count($data);
  $json['recordsFiltered'] = getDatabase()->one( "SELECT count(*) AS cnt FROM t_cdrs {$WHERE}" )['cnt'] ;

  echo  json_encode($json);



?>
