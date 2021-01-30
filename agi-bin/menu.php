#!/usr/bin/php7.3  -q
<?php

 set_time_limit(60);
 ob_implicit_flush(false);  //  turns off output buffering
 error_reporting(0);


  include_once('lib/functions-agi.php');
  $call = new AGIInterface('/var/lib/asterisk/agi-bin/config.ini');    


 //    We are failed this way - Channel variable can;t pass more then 500 characters !!! Json brakes -it cut, return only part
 // $tags_str = preg_replace('/\\\"/','',$call->get('X-CRM-TAGS') ) ;
 // $tags = json_decode( $tags_str  ,true);
 // $call->log(' MENU  tags:'. $tags_str  );
  //
  //
  // Get tags for the current call :
  $tags = $call->getTags(); 
  if( is_array( $tags ) ){
     $call->log('CALL has Got: ' . count($tags) . ' tags elements');
     // If we got menu tag, try to play in , and 
     if(isset( $tags['menu'] )){
	$call->log("Detected MENU: ".$tags['menu']['name'].':'.$tags['menu']['welcome'] . ' Play it...');
	$action = playMenu($tags['menu']);
	while($action){
    	  $tags = $call->getTags( ($action == 9) ? "StepBack" : "action={$action}&uid={$tags['uid']}"  );
	  $call->log( (($action == 9)?'STEPPED BACK to: ' : 'GOT Next: ') . count($tags) . ' tags elements. ');	  
	  if( isset($tags['menu']) ){
	    $call->log("  Detected Next menu:" . $tags['menu']['name'] . ':'.$tags['menu']['welcome'].'. Play it..');
	    $action = playMenu($tags['menu']);
	  }else{
            $call->log("   DID NOT receive tag[menu] in  API response! ");
	    $action = 0;
          }		  
	}	
        $call->log(" MENU(s) Finished! EXITING Menu and continue operation");
     }else{
	$call->log('THIS CALL Has no tags[menu]. AGI exit..');
     }	     
  }else{ 
    $call->log(' THIS CALL Has no ANY tags. AGI Exit');
  }




 function playMenu($menu){
	 global $call;
	 $actions = '';
	 $key = '';
	 //  Collect all possible actions from this menu for escape sequence
	  $call->log( json_encode($menu) );
	  foreach($menu['items'] as $i)
 	      if(isset($i['items'])){
	        foreach($i['items'] as $ii)
 	          if(isset($ii['action']) && $ii['action'] != -1 )
		      $actions .= $ii['action'];
	      }elseif(isset($i['action']) &&  $ii['action'] != -1){
		    $actions .= $i['action'];
	      }

	  $call->log("   Playing MENU: {$menu['name']}/id:{$menu['id']}  Actions Available[{$actions}] Welcome[ {$menu['welcome']} ]");

          if( trim($menu['welcome']) )
             $call->tts( $menu['welcome'],'ru_RU', 0, 'auto',false, $actions );

	  // Main Menu items - root node :  name, ID, Welcome 
	  foreach(  $menu['items']  as $item ){
		  if( isset($item['items']) ){  // SUbitems inside item //
			  // Sub menu list  - selections list - action,data,items,message
			  $m_items= $item['items'];
			  $call->log("    MENU MULTI-ITEMS".(count($m_items))." MSG[{$item['message']}] action[{$item['action']}] data[{$item['data']}]");
			  foreach($m_items as $el){
				  $call->log( "        ITEM[ msg:'{$el['message']}' action:{$el['action']}  data:{$el['data']}  ] " );
				  $ret = $call->tts( $el['message'],'ru_RU', 0, 'auto',false, $actions );
				  if(isset($ret['result']) && $ret['result'] > 0   ){
				    $key = chr($ret['result']);
				    $call->log(" CALLER selected[ {$key} ]");
				    if((int)$key > -1)
				     break 2;
				  }
			  }

		  }else{
			// Just play message // NO items here with [action,data,message ]
			  $call->log( "    MENU SINGL-ITEM[ action:{$item['action']} data:{$item['data']} MSG:'{$item['message']}' " ) ;
			  $ret = $call->tts( $item['message'], 'ru_RU', 0, 'auto',false, $actions );
			  if(isset($ret['result']) && $ret['result'] > 0   ){
                                    $key = chr($ret['result']);
                                    $call->log(" CALLER selected[ {$key} ]");
                                    if((int)$key > -1)
                                     break;
                          }
		  }
	  }
	  
	  if( $key === '' && $actions != '' ){
		  $ret = $call->agi->stream_file('silence/3', $actions );
		  if(!$ret['result'])
		    $ret = $call->tts("Пожалуста - - - сделайте ваш выбор.. ", 'ru_RU', 0, 'auto',false, $actions );
		  if(!$ret['result'])
		    $ret = $call->agi->stream_file('silence/5', $actions );

		  if(isset($ret['result']) && $ret['result'] ){
                     $key = chr($ret['result']);
                     $call->log(" CALLER (FInally) selected:{$key}");
                  }

	  }


	  $call->log( "    CALLER PRESSED [{$key}] ");
	  return $key; 
//     $call->tts( $welcome . ' ' . $tags['firm'], 'ru_RU', 0, 'auto');          
    }
  
?>
