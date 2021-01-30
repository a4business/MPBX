<?php

  include_once(dirname( __DIR__)  . '/include/config.php'); 

  function gen_ivrmenus_context(){
  	
     $res = mysql_query("SELECT *,
                                 t_ivrmenu.id as ivr_id, 
                                 t_ivrmenu.name as ivr_name,
                                 trim(fc.app) as app,
                                 tenants.id as tenant_id
	                      FROM  t_ivrmenu
                                LEFT JOIN feature_codes as fc ON trim(fc.appdata) =  trim(t_ivrmenu.context_script),
                             tenants
	                      WHERE  t_ivrmenu.tenant_id = tenants.id
	                      ORDER BY tenants.id ");
	  if( !$res ) die('Mysql ERR: ' . mysql_error()."\n");      
	  										  
	    while ($i = mysql_fetch_assoc($res)){
    	    	$_tenant = $i['ref_id']; 
    	    	$_timeout = ($i['menu_timeout']) ? $i['menu_timeout'] : 20;
    	    	$_delay = ($i['delay_before_start']) ? $i['delay_before_start'] : 500;
    	    	$_moh =  $i['moh_class'] ? $i['moh_class'] : 'default';
    	    	$_tts_voice =  $i['announcement_lang'] ? $i['announcement_lang'] : 'en-US_AllisonVoice';
    	    	
    	    	echo "[internal-{$_tenant}-ivrmenu-{$i['ivr_id']}]\n\n";
    	    	
    	    	if ( $i['allow_dialing_exten'] == 'yes' )
    	    	  echo " include => internal-{$_tenant}-local\n";
    	    	  
    	    	if ( $i['allow_dialing_external'] == 'yes' )
    	    	  echo " include => internal-{$_tenant}-outbound\n";
    	    	  
    	    	if ( $i['allow_dialing_featurecode'] == 'yes' )  
    	    	  echo " include => internal-{$_tenant}-features\n";
    	    		
    	    	echo "  exten => s,1,Verbose(3, [ {$_tenant} ] Starting IVR-Menu: '{$i['ivr_name']}' [id: {$i['ivr_id']} ] )\n";
            echo "  same => n,Answer({$_delay})\n";
            echo "  same => n,Set(CHANNEL(language)=ru)\n";
            echo "  same => n,Set(CDR(userfield)={$_tenant}::{$i['ivr_name']}::\${INBOUND_DID} )\n";
            echo "  same => n,Set(CDR(tenant_id)={$i['tenant_id']})\n";
            echo "  same => n,ExecIf(\$[ \"\${INBOUND_DID}\" != \"\" ]?Set(CDR(INBOUND_DID)=\${INBOUND_DID}))\n";
            echo "  same => n,Set(__IVRMENU={$i['ivr_name']})\n";
           	   
    	      
    	     $dialplan = array();
    	     $media_rec = array();
           $media_txt = array();
            
           $items_r = mysql_query("SELECT *,t_ivrmenu_items.id as t_ivrmenu_items_id,t_ivrmenu_items.announcement as announcement, 
                                           t_ivrmenu_items.announcement_type as announcement_type,
                                           t_ivrmenu_main.announcement_lang as announcement_lang,
                                           t_queues.name as queue_name, 
                                           t_queues.queue_welcome as queue_welcome, 
                                           t_queues.id as queue_id,
                                           t_ivrmenu.id as ivr_id,
                                           t_ivrmenu_main.allow_dialing_exten, t_ivrmenu_main.allow_dialing_external,
                                           t_conferences.id as conf_id,
                                           t_ringgroups.id as ringgrp_id,
                                           tenants.parkext_announce as parkext_announce,
                                           tenants.paging_retry_count as paging_retry_count,
                                           tenants.paging_interval as paging_interval                                       
                                    FROM t_ivrmenu_items
                                    	    LEFT JOIN tenants on tenants.id = t_ivrmenu_items.tenant_id 
                                         LEFT JOIN t_queues ON t_queues.name = t_ivrmenu_items.item_data AND t_ivrmenu_items.item_action = 'queue'
                                         LEFT JOIN t_ringgroups ON t_ringgroups.name = t_ivrmenu_items.item_data AND t_ivrmenu_items.item_action = 'ringgroup' and t_ivrmenu_items.tenant_id = t_ringgroups.tenant_id  
                                         LEFT JOIN t_ivrmenu as t_ivrmenu_main ON t_ivrmenu_main.id = t_ivrmenu_items.t_ivrmenu_id
                                         LEFT JOIN t_ivrmenu ON t_ivrmenu.name = t_ivrmenu_items.item_data AND t_ivrmenu_items.item_action = 'ivrmenu'
                                         LEFT JOIN t_conferences ON t_conferences.conference = t_ivrmenu_items.item_data AND t_conferences.tenant_id = t_ivrmenu_items.tenant_id AND t_ivrmenu_items.item_action = 'conference'
                                         
                                    WHERE t_ivrmenu_id = {$i['ivr_id']}
                                    ORDER BY selection");  	      
    	     if (!$items_r) die('Mysql ERR:' . mysql_error()."\n");
    	     
    	     $dialplan[] = ';; IVR Selections Array here;;';
    	     	      
    	     while($item = mysql_fetch_assoc($items_r) ){
              $SELECTION = $item['selection'];
    	     	  $VAR =  preg_replace('/SIP\/|Local\//','', $item['item_data'] );
    	     	  
    	     	  if( $item['announcement'] && $item['announcement_type'] != 'no' ){     	  
    	     	   /// $_media_file = ( $item['announcement_type'] == 'recording' || $item['announcement_type'] == 'upload' ) ? "snd_{$_tenant}/{$item['announcement']}" : '/tts/' . md5($item['announcement'].'..en-US_LisaVoice.1');
    	     	    $_media_file = ( $item['announcement_type'] == 'recording' || $item['announcement_type'] == 'upload' ) ? "snd_{$_tenant}/{$item['announcement']}" : '/tts/' . md5('auto_'.$item['announcement'].'..'.$item['announcement_lang'].'.1');   
    	     	    $media_rec[] = $_media_file;
    	     	    $media_txt[] = " {$item['announcement_lang']}  {$item['announcement_type']}:[{$item['announcement']}] "; /// {$_media_file} \n"; 
    	        } 
    	         	    
              
               switch( $item['item_action'] ) {
                case 'extension':
                   $dialplan[] = "exten => {$SELECTION},1,Verbose(4, Menu SELECTION [ {$SELECTION} ] Activated )";  // ,  'media' => $media );
               	   $dialplan[] = "exten => {$SELECTION},n,Dial(Local/{$VAR}@internal-{$_tenant}-local,,tT)";  // ,  'media' => $media ); 
    		        break;
    		        
                case "disa":
                   
                      $arr =  explode(',', $VAR,2);
                      $disa_pin = $arr[0]?$arr[0]:'';
                      $disa_cli = ( count($arr)>1 ) ? $arr[1]:'';
                      
                   // list( $disa_pin, $disa_cli) = explode(',', $VAR);
                                    
                   if ( $item['allow_dialing_exten'] == 'yes' && $item['allow_dialing_external'] == 'yes' )
                     $disa_context = "internal-{$_tenant}";
                   elseif ( $item['allow_dialing_exten'] == 'no' && $item['allow_dialing_external'] == 'yes' )
                     $disa_context = "internal-{$_tenant}-outbound";
                   elseif ( $item['allow_dialing_exten'] == 'yes' && $item['allow_dialing_external'] == 'no' )
                     $disa_context = "internal-{$_tenant}-local";
                     
                   $disa_opt = "{$disa_pin}|{$disa_cli}|{$disa_context}";  
     		         $dialplan[] = "exten => {$SELECTION},1,Gosub(app-pbx-service,s,1({$item['item_action']},{$disa_opt},{$_tenant}))";
     		         $dialplan[] = "exten => {$SELECTION},n,Noop(After DISA: Dialstatus: \${DIALSTATUS})";
     		         $dialplan[] = "exten => {$SELECTION},n,Playback(vm-goodbye)";
     		        break;
     		          
    	         case "park_announce_rec":
    	            if( !preg_match('/PARKED/',$VAR) ){
    	             $VAR .= ':PARKED';  // This variable needed for ParkAndAnnounce() // 
    	            }
    	            $VAR = "snd_{$_tenant}/{$VAR}"; 
    	            
    	            $park_extens = json_decode($item['parkext_announce'],true);
                   $page_exten = is_array($park_extens) ? 'SIP/' . implode('&SIP/', $park_extens) : 'Local/s@general-{$_tenant}-error';
                   
     		         $dialplan[] = "exten => {$SELECTION},1,Gosub(app-pbx-service,s,1(park_announce_rec,{$VAR}|{$page_exten}|{$i['ivr_id']},{$_tenant}))";  //  // This | delimiter used by Macro app-pbx-service, which separate recording and announce exten 
     		        break; 		        
    		        
    		       case "conference":
    		        if ( $VAR )
    		          $dialplan[] = "exten => {$SELECTION},1,Gosub(dialconference,s,1({$item['conf_id']},,,,snd_{$_tenant}))" ;
    		        break; 
    		        
    		       case "ringgroup":		          	       
    		          $dialplan[] = "exten => {$SELECTION},1,Dial(Local/s@internal-{$_tenant}-ringgroup-{$item['ringgrp_id']},60,tT)" ;          	   
    		        break;
    		          	   
    		       case 'ivrmenu':           	   
     	            $dialplan[] = "exten => {$SELECTION},1,Dial(Local/s@internal-{$_tenant}-ivrmenu-{$item['ivr_id']},60,tT)" ;      
    		        break;  
    		        
    		       case "number":
    		         $dialplan[] = "exten => {$SELECTION},1,Set(__FWD_KEEP_CID=1)";
              	   $dialplan[] = "exten => {$SELECTION},n,Dial(Local/{$VAR}@internal-{$_tenant},,tT)" ;          	   
              	   break;   
              	   
              	case "queue":
              	  //$dialplan[] = "exten => {$SELECTION},1,Answer()";
    	          	//$dialplan[] = " exten => {$SELECTION},n,Set(CHANNEL(musicclass)={$row['queue_musiconhold']})";
    	          	//if ( $item['queue_welcome'] )   
                  //   $dialplan[] = "exten => {$SELECTION},n,Playback(snd_{$_tenant}/{$item['queue_welcome']})" ;
                  // $dialplan[] = "exten => {$SELECTION},n,Queue({$VAR})" ;                    	   
                 $dialplan[] = "exten => {$SELECTION},1,NoCDR()";
                 $dialplan[] = "exten => {$SELECTION},n,Dial(Local/s@internal-{$_tenant}-queue-{$item['queue_id']},,tT)";

              	   break; 
              	   
           	   case "voicemail":
              	   //$dialplan[] = "exten => {$SELECTION},1,Voicemail({$VAR})" ;
              	    $dialplan[] = "exten => {$SELECTION},1,Gosub(app-pbx-service,s,1(voicemail,{$VAR},{$_tenant}))";          	   
              	   break;   
              	
               case "dirbyname":               
                    $dialplan[] = "exten => {$SELECTION},1,Gosub(app-pbx-service,s,1(directory,b,{$_tenant})";              
                   break;   
                              	   
               case "play_invalid":
              	   $dialplan[] = "exten => {$SELECTION},1,Playback(invalid)" ;        
              	   $dialplan[] = "exten => {$SELECTION},n,Goto(s,menustart)" ;  	   
              	   break;
              	   
                case "play_rec":
              	   $dialplan[] = "exten => {$SELECTION},1,Playback(snd_{$_tenant}/{$VAR})" ;          	   
    		           $dialplan[] = "same => n,Hangup()";
    		           break;
              	   
              	case "play_tts":
              	   //$tts_file = '/tts/' . md5("{$VAR}..en-US_LisaVoice.1");
              	   //$dialplan[] = "exten => {$SELECTION},1,Playback({$tts_file})   ;;;; tts: $VAR" ;          	   
    		             $dialplan[] = "exten => {$SELECTION},1,AGI(tts.php,{$_tts_voice},{$VAR},0,auto)";
              		   $dialplan[] = "same => n,Hangup()";
    		           break;

     	         case "moh": 	          	      
              	   //$dialplan[] = "exten => {$SELECTION},1,MusicOnHold({$_moh})" ;
              	   $dialplan[] = "exten => {$SELECTION},1,Gosub(app-pbx-service,s,1(moh,{$_moh},{$_tenant}))";          	   
              	   break;
              	   
              	case "hangup":
              	   $dialplan[] = "exten => {$SELECTION},1,Verbose(2,'HANGUP Action selected')" ;          	   
                   $dialplan[] = "exten => {$SELECTION},n,Hangup" ;
              	   break;
              	   
              	case "repeat":
              	   $dialplan[] = "exten => {$SELECTION},1,Goto(s,menustart)" ;          	   
              	   break;   	     	   
    	       }
	    }

       	    
       $i['announcement'] = trim($i['announcement']) ;
       $MOH = '';
       echo "  same => n(welcome),Verbose(2, PLAY Welcome({$i['announcement_type']}): '{$i['announcement']}' (file:{$_ennounce}) items(" . count( $media_rec ) . "): " . implode(' & ', $media_txt ) . " )\n";

       if( $i['announcement_type'] == 'recording' || $i['announcement_type'] == 'tts' ){
  	    $_ennounce = ( $i['announcement_type'] == 'recording' ) ? "snd_{$_tenant}/{$i['announcement']}" :  '/tts/' . md5("auto_{$i['announcement']}..{$_tts_voice}.1"); 
	    echo "  same => n(menustart),Background({$_ennounce}&" . implode('&', $media_rec ) . ")\n";
       }elseif( $i['announcement_type'] == 'moh' ) {
	    echo "  same => n(menustart),Verbose(2,PLAY MOH Calss [ {$_moh} ] while waiting for sel:)n"; 
 	    echo "  same => n,Set(CHANNEL(musicclass)={$_moh})\n";
            $MOH =  "m({$_moh})";	          
       }elseif( $i['announcement_type'] == 'tts_template' ){
	      echo "  same => n(menustart),Verbose(2,PLAY TTS Template parced By AGI Script)\n";
	      echo "  same => n,AGI(play_tts_template.php,{$_tts_voice},{$i['announcement']} )\n";
       }
       	
      echo "\n\n";
      if($i['context_script']){                
        if( $i['app'] == 'AGI' )
         echo "  same => n,AGI({$i['context_script']})\n";
        elseif( $i['app'] == 'Gosub' )
         echo "  same => n,Gosub({$i['context_script']})\n";

       echo "\n";
      }  

      echo "  same => n,WaitExten({$_timeout},{$MOH})\n";
      echo "  same => n,Goto(t,1)\n\n";

     // Generate Menu entries HERE //            
	    $i=0;
	    if ( is_array($dialplan) &&  count($dialplan) > 0 )
	         foreach($dialplan as $row)  
	            echo '  ' . $row . "\n";
	           
	    echo "\n\n";
        	      
  }	    

}

/*
Field             | Type         | Null | Key | Default | Extra          |
+-------------------+--------------+------+-----+---------+----------------+
| id                | int(11)      | NO   | PRI | NULL    | auto_increment |
| t_ivrmenu_id      | int(11)      | NO   |     | NULL    |                |
| tenant_id         | int(11)      | YES  |     | NULL    |                |
| selection         | varchar(5)   | YES  |     | NULL    |                |
| destination       | varchar(50)  | YES  |     | NULL    |                |
| announcement_type | varchar(20)  | YES  |     | NULL    |                |
| announcement      | varchar(120) | YES  |     | NULL    |                |
| announcement_lang | varchar(30)  | YES  |     | NULL    |                |
| description       | varchar(200) | YES  |     | NULL    |                |
| item_action       | varchar(50)  | YES  |     | NULL    |                |
| item_data
*/
        
?>
