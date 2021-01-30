#!/usr/bin/php -q
<?php


  include_once(dirname( __DIR__)  . '/include/config.php');
  
  $dialplans = array( 'inbound', 'internal', 'outbound', 'ivrmenus', 'ringgroups', 'queues','res_parking' );
  
 
  // Default execution   of this script - without options,  
  // It calls ITELSEF in Recursion with different options,
  // YOU can also call it with  options as well for debug (all in one file - is it so good??)  
  $options = getopt('t:t:', $dialplans );
  
  if ( !count($options)  ){
  	
  	// Dynamic scripts
  	  foreach( $dialplans as $dialplan ){
       exec(__DIR__ . "/../core/gen_extensions.php --{$dialplan} > /etc/asterisk/{$dialplan}.include 2>/dev/null");
       if($dialplan != 'res_parking')  // We include the generated res_parking.include in res_parking.conf statically      
         echo "#include {$dialplan}.include\n";
     }
     
   // Static macros
     if(file_exists('/etc/asterisk/conferences.include'))
       echo "#include conferences.include\n";
     
     if(file_exists('/etc/asterisk/macros.include'))
       echo "#include macros.include\n";

   return;
    	
  }	
  
  if ( isset($options['res_parking']) ){
       include_once( __DIR__  . '/gen_res_parking.php');
       gen_res_parking();
   }
  
   if ( isset($options['inbound']) ){
       include_once( __DIR__  . '/gen_inbound.php');
       gen_inbound_context();
   }
   
   if ( isset($options['ivrmenus'])  ) {
       include_once( __DIR__  . '/gen_ivrmenus.php');
       gen_ivrmenus_context();
   }
  
   if ( isset($options['internal'])  ) {
       include_once( __DIR__  . '/gen_internal.php');
       gen_internal_context();
   }
	 
   if ( isset($options['outbound']) ){
       include_once( __DIR__  . '/gen_outbound.php');
       gen_outbound_context();
   }
   
   if ( isset($options['ringgroups']) ){
       include_once( __DIR__  . '/gen_ringgroups.php');
       gen_ringgroups_context();
   }
   
   if ( isset($options['queues']) ){
       include_once( __DIR__  . '/gen_queues.php');
       gen_queues_context();
   }
	    	 
	    	 
 
?>
