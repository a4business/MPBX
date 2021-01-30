#!/usr/bin/php7.3  -q
<?php

 set_time_limit(60);
 ob_implicit_flush(false);  //  turns off output buffering
 error_reporting(0);


  include_once('lib/functions-agi.php');
  $call = new AGIInterface('/var/lib/asterisk/agi-bin/config.ini');
  
  
  $MODE = $call->get('DIRECTION');

  if( $MODE == 'INBOUND' ){
    $NUM = $call->get('agi_callerid');
  }else{
    $NUM = $call->get('agi_dnid');
  }  

  $NUM = preg_replace('/[^0-9]/','', $NUM );
  $NUM = preg_replace('/^0/','380', $NUM );
  $NUM = (strlen($NUM) == 5) ? '38048' . $NUM : $NUM ;

  
  $DID = $call->get('INBOUND_DID');
  
  
   
  /////// $NUM = ( $NUM == '206') ? '380506426558':'';  /// тестовый 
  ///$NUM = '380506426558';
  ///////
 

    if( !isset($call->ini['external']['billingurl'])  ){
      $call->log("CONTEXT Script FAILED: NO External URL!!! For INCOMING CID: {$NUM} ");  
      $call->agi->verbose("CONTEXT Script FAILED: NO External URL!!! For INCOMING CID: {$NUM} ",5); 
      return;
    } 
 $call->log(' Call FROM:'.$NUM);
   if( isset( $call->ini[$NUM] ) ){
      $URL = $call->ini[$NUM]['billingurl'] . $NUM; 
   }else
      $URL = $call->ini['external']['billingurl'] . $NUM;	   

   if( strlen($NUM) > 3 ){
    $call->log("RUN CONTEXT SCRIPT API(mode:{$MODE}) GET: [ $URL ]  )");
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    $data = curl_exec($ch);
    curl_close($ch);
   } 

    $call->log("GOT FROM billing: [ {$data} ] by {$MODE} CALLERID: {$NUM}  ");  
    //$call->agi->verbose("GOT FROM billing: [ {$data} ] by CALLERID: {$NUM}  ",5); 

  if( !is_array( json_decode($data, true) ) && preg_match('/^048/', $NUM) ){
    $NUM_STRIPPED = preg_replace('/^048/','',$NUM );
    $URL = $call->ini['external']['billingurl'] . $NUM_STRIPPED;
    $call->log("RUN CONTEXT SCRIPT API(mode:{$MODE}) GET: [ $URL ] [ SECOND CHECK ] )");
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

  }
     

  if( is_array( json_decode($data,true) ) ){

      $X_CRM = json_decode($data,true);            
      // Take First Card To SHOW on Incoming call when  count($X_CRM) > 1  ???
      $B = ( is_array($X_CRM) && isset( $X_CRM[0] ) ) ? $X_CRM[0] : $X_CRM;

    

     // Format LINK url html //
      if( isset( $B['link'] ) || isset( $B['url']) ){      
        $URL = isset( $B['link'] ) ? $B['link'] : $B['url'] ;
        if( preg_match('/http/', $URL ) && !preg_match('/href=/', $URL) ){
          $link_txt = isset($B['fio']) ? $B['fio'] : $NUM;
          $URL = "<a href='{$URL}' target='_blank' >{$link_txt}</a>";
        }  
        // Extract:  id from URL

        $B['uid'] = preg_match('/\?id=(\d+)/', $URL , $m ) ? $m[1] : 0 ;
        $B['uid'] = preg_match('/\?index=(\d+)/', $URL, $m ) ? $m[1] : $B['uid'] ;

        
        if( preg_match('/href/', $URL ) && !preg_match('/_blank/', $URL ) )
          $URL = preg_replace('/ href/',' target=_blank  href', $URL );                   
      // Export URL:
        $call->agi->set_variable('__X-CRM-Link', $URL );                
        $B['url'] = $URL;
        unset($B['link']);

      }  
     
     

      if( isset($B['deposit']) || isset($B['balance'])  ){         
         $balance = round( isset($B['deposit']) ?  $B['deposit'] : $B['balance'] ); 
         $call->agi->set_variable('__X-CRM-Balance', $balance );     
         $B['balance'] = $balance;
      }
     
      if( isset( $B['comments'] ) ){
        $B['comments'] = substr($B['comments'],0,150);
	      $call->agi->set_variable('__X-CRM-Comment', $B['comments']);
      }


     // a
     if( array_key_exists('istatus',$B) ||  array_key_exists('astatus',$B) || array_key_exists('tstatus',$B)  ){
	     // astatus: AccountStatus  = -1 not exists(icon)  0 - exists(green), not blocked,  1 - exist, blocked(red); 
	     if( !( isset($B['astatus']) || isset($B['istatus']) || isset($B['tstatus'])) ){
      	       $B['block'] = -1;                                  // nothins is set
	     }else{	
   	       $B['block'] = isset($B['astatus'])?$B['astatus']:0;    // account is by default 0 : if any of tstatus or istatus are set
	     }  
       // istatus: InternetStatus - null(-1) not exists, 0 - exist, blocked, 1 exist, active
	     $B['istatus'] = isset($B['istatus']) ? $B['istatus']:-1;

	     // TV null(-1) not exists, 0 - exist, blocked, 1 exist, active
	     $B['tstatus'] = isset($B['tstatus']) ? $B['tstatus']:-1; 
     }

     //Append Calculated fields:
     $B['astatus'] = isset($B['block']) ? $B['block'] : -1;  // Account Status
     $B['num'] = $NUM;   				   // Link SRC Number 
     
   

     $B['menu'] = '' ; 

  // Asterisk CDR field can't be longer then 1024 chars !!!!//
    if( strlen(json_encode($B)) >  1023 && strlen($B['actual_address']) > 100 )  
       $B['actual_address'] = substr($B['actual_address'],0,100);    
    if( strlen(json_encode($B)) >  1023 && strlen($B['comments']) > 100 )  
       $B['comments'] = substr($B['comments'],0,100);
    
    

    // TO debug ONLY:      
     // This field used under  context script inside IVR , to plat additionally comapny Name BEFOER Welcome  message 
    if(is_array($B))
       foreach( $B as $key=>$tag )
         $call->agi->verbose('           >>'.$key .':'.$tag );

    // Exporting TAGS:: //
     $call->log(" Export TAGS: " . json_encode($B) );     
     $call->agi->set_variable('__X-CRM-TAGS', json_encode($B)  );   
     $call->agi->set_variable('CDR(tags)',    json_encode($B) ); 

  
  // EXPORT SIP Headers for SIP call INFO FOR WEB Dailer Display OnIncoming Call//
    if( $MODE == 'INBOUND' ){
       $call->agi->SIPAddHeader( 'X-CRM-Link',    $B['url'] );                // #SIPADDHEADER01     
       $call->agi->SIPAddHeader( 'X-CRM-Address', $B['actual_address'] );      // #SIPADDHEADER02
       $call->agi->SIPAddHeader( 'X-CRM-Balance', $B['balance']  );                  // #SIPADDHEADER03      
       $call->agi->SIPAddHeader( 'X-CRM-Comment', $B['comments'] );            // #SIPADDHEADER04       
       $call->agi->SIPAddHeader( 'X-CRM-Company', $B['firm'] );                // #SIPADDHEADER05
       $call->agi->SIPAddHeader( 'X-CRM-Region',  $B['district'] );            // #SIPADDHEADER06
     }
       
   } 

?>
