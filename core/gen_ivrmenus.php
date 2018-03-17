<?php

  include_once(dirname( __DIR__)  . '/include/config.php'); 
  function gen_ivrmenus_context(){
  	
     $res = mysql_query("SELECT *,t_ivrmenu.id as ivr_id, t_ivrmenu.name as ivr_name 
	                      FROM  t_ivrmenu, tenants 
	                      WHERE  t_ivrmenu.tenant_id = tenants.id
	                      ORDER BY tenants.id ");
	  if (!$res) die('Mysql ERR:' . mysql_error()."\n");      
	  
	  
	  										  
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
	    		
	    	echo " exten => s,1,Verbose(3, [ {$_tenant} ] Starting IVR-Menu: '{$i['ivr_name']}' [id: {$i['ivr_id']} ] )\n";
         echo "  same => n,Answer({$_delay})\n";
       	   
	      
	     $dialplan = array();
	     $media_rec = array();
        $media_txt = array();
        
        $items_r = mysql_query("SELECT *,t_queues.name as queue_name, t_queues.queue_welcome as queue_welcome, 
                                       t_ivrmenu.id as ivr_id,
                                       t_conferences.id as conf_id
                                FROM t_ivrmenu_items
                                     LEFT JOIN t_queues ON t_queues.name = t_ivrmenu_items.item_data AND t_ivrmenu_items.item_action = 'queue'
                                     LEFT JOIN t_ivrmenu ON t_ivrmenu.name = t_ivrmenu_items.item_data AND t_ivrmenu_items.item_action = 'ivrmenu'
                                     LEFT JOIN t_conferences ON t_conferences.conference = t_ivrmenu_items.item_data AND t_conferences.tenant_id = t_ivrmenu_items.tenant_id AND t_ivrmenu_items.item_action = 'conference'
                                WHERE t_ivrmenu_id = {$i['ivr_id']}
                                ORDER BY selection");  	      
	     if (!$items_r) die('Mysql ERR:' . mysql_error()."\n");
	     	      
	     while($item = mysql_fetch_assoc($items_r) ){
           $SELECTION = $item['selection'];
	     	  $VAR =  preg_replace('/SIP\/|Local\//','', $item['item_data'] );
	     	  	
	     	  if( $item['announcement'] ){     	  
	     	    $_media_file = ( $item['announcement_type'] == 'recording' || $item['announcement_type'] == 'upload' ) ? "snd_{$_tenant}/{$item['announcement']}" : '/tts/' . md5($item['announcement'].'..en-US_LisaVoice.1');
	     	    $media_rec[] = $_media_file;
	     	    $media_txt[] = "   {$item['announcement_type']}:[{$item['announcement']}] "; /// {$_media_file} \n"; 
	        }  	    
           
           
           switch( $item['item_action'] ) {
            case 'extension':
               $dialplan[] = " exten => {$SELECTION},1,Verbose(4, Menu SELECTION [ {$SELECTION} ] Activated )";  // ,  'media' => $media );
           	   $dialplan[] = " exten => {$SELECTION},n,Dial(Local/{$VAR}@internal-{$_tenant}-local,,tT)";  // ,  'media' => $media ); 
		        break;
		        
		      case "conference":
		        if ( $VAR )
		          $dialplan[] = " exten => {$SELECTION},1,Macro(dialconference,{$item['conf_id']},,,,snd_{$_tenant})" ;
		        break; 
		          	   
		      case 'ivrmenu':           	   
 	            $dialplan[] = " exten => {$SELECTION},1,Dial(Local/s@internal-{$_tenant}-ivrmenu-{$item['ivr_id']},60,tT)" ;      
		        break;  
		        
		      case "number":
          	   $dialplan[] = " exten => {$SELECTION},1,Dial(Local/{$VAR}@internal-{$_tenant},,tT)" ;          	   
          	   break;   
          	   
          	case "queue":
          	   $dialplan[] = " exten => {$SELECTION},1,Answer()";
	          	//$dialplan[] = " exten => {$SELECTION},n,Set(CHANNEL(musicclass)={$row['queue_musiconhold']})";
	          	if ( $item['queue_welcome'] )   
                 $dialplan[] = " exten => {$SELECTION},n,Playback(snd_{$_tenant}/{$item['queue_welcome']})" ;
		         $dialplan[] = " exten => {$SELECTION},n,Queue({$VAR})" ;                    	   
          	   break; 
          	   
       	   case "voicemail":
          	   $dialplan[] = " exten => {$SELECTION},1,Voicemail({$VAR})" ;          	   
          	   break;   
          	     	   
          	case "ivrmenu":
          	   $dialplan[] = " exten => {$SELECTION},1,Dial(Local/s@internal-{$_tenant}-ivrmenu-{$VAR},,tT)" ;          	   
          	   break;   
          	   
          	case "play_invalid":
          	   $dialplan[] = " exten => {$SELECTION},1,Playback(invalid)" ;        
          	   $dialplan[] = " exten => {$SELECTION},n,Goto(s,loop)" ;  	   
          	   break;
          	   
            case "play_rec":
          	   $dialplan[] = " exten => {$SELECTION},1,Playback(snd_{$_tenant}/{$VAR})" ;          	   
          	   break;
          	   
          	case "play_tts":
          	   $tts_file = '/tts/' . md5("{$VAR}..en-US_LisaVoice.1");
          	   $dialplan[] = " exten => {$SELECTION},n,Playback({$tts_file})   ;;;; tts: $VAR" ;          	   
          	   break;

 	         case "moh": 	          	      
          	   $dialplan[] = " exten => {$SELECTION},n,MusicOnHold({$_moh})" ;          	   
          	   break;
          	   
          	case "hangup":
          	   $dialplan[] = " exten => {$SELECTION},1,Verbose(2,'HANGUP Action selected')" ;          	   
          	   break;
          	   
          	case "repeat":
          	   $dialplan[] = " exten => {$SELECTION},1,Goto(s,loop)" ;          	   
          	   break;   	     	   
	       }
	    }
       	    
         
       if( $i['announcement_type'] == 'recording' || $i['announcement_type'] == 'tts' ){
  	    	   $_ennounce = ( $i['announcement_type'] == 'recording' )? "snd_{$_tenant}/{$i['announcement']}" :  '/tts/' . md5("{$i['announcement']}..{$_tts_voice}.1");
  	         echo "  same => n(loop),Verbose(2,  Play Welcome {$i['announcement_type']}:{$i['announcement']} (file:{$_ennounce}) items(" . count( $media_rec ) . "): " . implode(' & ', $media_txt ) . " )\n";  
    	      echo "  same => n,Background({$_ennounce}&" . implode('&', $media_rec ) . ")\n";
	         echo "  same => n,WaitExten({$_timeout})\n";
       }elseif( $i['announcement_type'] == 'moh' ) {
	      	echo "  same => n(loop),Verbose(2,PLAY MOH Calss [ {$_moh} ] while waiting for sel:)n"; 
	      	echo "  same => n,Set(CHANNEL(musicclass)={$_moh})\n";
	         echo "  same => n,WaitExten({$_timeout},m({$_moh}))\n";
       }   	    
       	    
	    
	    if ( count($dialplan) )  
	       echo implode("\n", $dialplan ) . "\n";
	       
	    
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