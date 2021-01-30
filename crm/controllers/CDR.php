<?php


 use PAMI\Message\Action\GetVarAction;

   
class CDR{

    /*// CONCURRENT CALLS:::: Currently  running Calls data //
  function runData( $sdata = null){

        global $PBX;
        $json = array();
        $data = array();

        // UGLY METHOD! BUT FAST RESULTS!!!! HELP HELP!!!!!!!! FIX FIX GIX!!
        exec("/usr/sbin/rasterisk -rx 'cdr show active' 2>&1|egrep -ve 'Asterisk ending|Channels with Call|LastApp|-----------'", $ret);

     try{        
        $PBX->pami->open();
        foreach ($ret as $row ) 
         if($row){

           if($f[2] == 'Dial')
             $f[2] = 'Dial ' .  $f[1];

           $f = preg_split('/\s+/', $row );
           if(count( $f ) == 7 ){  //Must be 8!!
             $f[]=$f[6];
             $f[6]=$f[5];             
             $f[5]='-';
           }
           if( (int)$f[6] === 0 )
             $f[4] = '-';

           if( count($f) < 8 )            
             $f[]='-';

           

           $channel = $f[1];
           $caller = $f[0];
           //if( preg_match('/^SIP/', $channel ) ){
              $isQueue =       $PBX->pami->send(new GetVarAction('QUEUE',          $channel ) )->getKeys()['value'];
              $TAGS =          $PBX->pami->send(new GetVarAction('X-CRM-TAGS',     $channel ) )->getKeys()['value'];
              $isIvr =         $PBX->pami->send(new GetVarAction('IVR',            $channel ) )->getKeys()['value'];
              $tenant =        $PBX->pami->send(new GetVarAction('uniqueid',       $channel ) )->getKeys()['value'];
              $callType =      $PBX->pami->send(new GetVarAction('DIRECTION',      $channel ) )->getKeys()['value'];
              $X_CRM_Link =    $PBX->pami->send(new GetVarAction('SIPADDHEADER01', $channel ) )->getKeys()['value'];
              $X_CRM_Balance = $PBX->pami->send(new GetVarAction('SIPADDHEADER03', $channel ) )->getKeys()['value'];
              $X_CRM_Comment = $PBX->pami->send(new GetVarAction('SIPADDHEADER04', $channel ) )->getKeys()['value'];

              if( $TAGS && is_array(json_decode($TAGS,true)) ){
               $TAGS_arr =  json_decode($TAGS,true);            
              }
              
              //$PBX->log( $f[1] . '-> App:' . $appName . ' Type:' . $callType . ' CRMdata:' . $X_CRM_Link);
              //$f[1] = $VAR['value'];

            if( preg_match('/^SIP/', $channel ) ){
              $f[1] =  "  <span title='{$f[1]}'><i class='mdi  mdi-headset'></i> ". implode('-', explode('-', preg_replace('/SIP\//','',$channel), -1 ) ) .'</span>';
            }  

           // convert SIP/TTTT-101-0001232  => TTT-101
            if( preg_match('/^SIP/', $caller ) )
              $f[0] = $tenant  . '<i class="mdi  mdi-headset"></i> ' . implode('-', explode('-', preg_replace('/SIP\//','',$caller), -1 ) );              

          

           $f[3] = $callType;           

            if($isQueue != '')
                $f[2] = '<span style="width:180px;padding:3px 10px !important" class="alert  alert-info "><i class="mdi mdi-account-multiple-outline"></i>[ ' . $isQueue . ' ]</span>';
            

            if( $isIvr != '' )
                $f[2] = $f[2] . '<span style="width:180px;padding:3px 10px !important" class="alert  alert-info "><i class="mdi mdi-account-multiple-outline"></i>ivr:[ ' . $isIvr . ' ]</span>';
              
           

           // APPEND data & Control Buttons  //
           $X_CRM_Link = preg_replace('/X-CRM-Link:/','', $X_CRM_Link );
           
           if(  preg_match('/X-CRM-Balance:/', $X_CRM_Balance ) ) {
             $X_CRM_Balance = ' ('. preg_replace('/X-CRM-Balance:/', '', $X_CRM_Balance )  . ' грн)';
           }

           $X_CRM_Comment = preg_replace('/X-CRM-Comment:/', '', $X_CRM_Comment ) ;

           
           $details  = $X_CRM_Link ? "<span title='{$X_CRM_Comment}'><i class='mdi mdi-account-card-details '></i> {$X_CRM_Link}  {$X_CRM_Balance} </span> {$TAGS_arr['num']}" : ''; 

           
           $f[] = "<span class='alert alert-danger p-1 m-1 hangup' style='cursor:pointer;' data='{$f[0]}'><i class='mdi mdi-phone-hangup'></i></span>";
           $f[0] = $X_CRM_Link ? "<span title='{$X_CRM_Comment}'> {$details} </span> " :  $f[0] ;

          $skipped = 0;

          if(count($f) == 9){
                $data[] = $f;
          }
         
        }

       $PBX->pami->close();        

      }catch (Exception $e) {
	echo 'PAMI Caught exception: ';
       //$PBX->log("PAMI Caught exception:" . $e->getMessage() . "\n" );
      } 

        $json['data'] = $data;
        $json['draw'] = $_GET['draw'] ? $_GET['draw'] : 1;        
        $json['recordsTotal'] = count($data);
        $json['recordsFiltered'] = count($data);
                 
        echo (count($json) == 1) ? json_encode($json[0]) : json_encode($json);

      }*/


  // This procedure checks if such ANSWERED Dialed channel has else-where answered CDR(for example - in queue application CDR)
  static function checkDuplication( $dstChannel ){
    foreach($cdrs as $c)
      if( $c['dstchannel'] == $dstChannel && $c['lastapp'] != 'Dial' && $c['disposition'] == 'ANSWERED' )
       return true;
  }


  function getCDRs( $sdata = null){
           global $PBX;
                  $json = array();
                  $data = array();
                  $fields = array( 
                                   'tenant_id' => 'Cloud',
                                   'uniqueid' => '#ID#',
                                   'calldate' => 'Дата',                                   
                                   'direction' => 'Direction',
                                   'src' => 'From',                                   
                                   'dst' => 'To',
                                   'dstchannel'  => 'Соединен с',
                                   'duration'  => 'Длительность',                                   
                                   'disposition' => 'Статуc',                                   
                                   'tags' => 'Call tags',
                                   'lastdata' => 'App data',
				                           'lastapp' => 'Appl',
                                   'service_status' => 'сервис',
                                   'channel'  => 'Соединен с',
                                   'clid' => 'cid',
				                           'INBOUND_DID' => 'did',
                                   'billsec'   => 'Разговор',
                                 );

                  $datatable_fields = array(                                   
                                   'uniqueid' => '#ID#',
                                   'calldate' => 'Дата',                                   
                                   'direction' => 'Direction',
                                   'src' => 'From',                                   
                                   'dst' => 'To',
                                   'dstchannel'  => 'Соединен с',
                                   'disposition' => 'Статуc',                                   
                                   'service_status' => 'Сервис'  ,
                                   'billsec' => 'rec'
                                  );
                 
                  $OPTIONS = CDR::getSorter($datatable_fields); 
                  $WHERE   = CDR::getFilter($datatable_fields);  
                  $LIMIT = $_GET['length'] ? $_GET['length'] : 100;
                  $SQL = "SELECT " . implode(',', array_keys($fields)) . "  FROM t_cdrs ";
                  
                  $cdrs = getDatabase()->all( "{$SQL} {$WHERE} {$OPTIONS}" );
//		 echo "{$SQL} {$WHERE} {$OPTIONS}" ;
                  
              // return CDRs //
                 foreach($cdrs as $cdr){       
                    $file='';     
                    $chan_describe = '';
		                $tagged_info = '';                    

		          // Normalize numbers to Local format //
                    $cdr['dst']  = preg_replace("/^\+?38/",'', $cdr['dst']);
                    $cdr['src']  = preg_replace("/^\+?38/",'', $cdr['src']);
                    $src_orig   = $cdr['src'];
                    $uniq_orig  = $cdr['uniqueid'];
                    $date_orig  = $cdr['calldate'];
                 
              // Run SUB queries for getting details   //
                    $src_described = getDatabase()->one("SELECT concat('<i class=\"bordered mdi mdi-headset\" onclick=\"call(\'', sip.extension, '\');\"></i> ', sip.extension,' ', sip.first_name) as sip_name
                                                   FROM t_sip_users sip
                                                    LEFT JOIN admin_users au ON au.default_tenant_id = 0{$cdr['tenant_id']} AND au.sip_user_id = sip.id
                                                   WHERE tenant_id = 0{$cdr['tenant_id']} AND 
                                                         ( sip.name = '{$cdr['src']}' OR sip.extension = '{$cdr['src']}') ")['sip_name'];
                    
                    
                       
                    $dst_described =  getDatabase()->one("SELECT concat('<i class=\"bordered mdi mdi-headset\" onclick=\"call(\'', sip.extension, '\');\"></i> ',sip.extension,' ', sip.first_name) as sip_name
                                                   FROM t_sip_users sip
                                                    LEFT JOIN admin_users au ON au.default_tenant_id = 0{$cdr['tenant_id']} AND au.sip_user_id = sip.id
                                                   WHERE tenant_id = 0{$cdr['tenant_id']} AND 
                                                         ( sip.name = '{$cdr['dst']}' OR sip.extension = '{$cdr['dst']}' ) ")['sip_name'];
                    
                    
                    unset($m1);
                    unset($m2);
                    unset($q1);
                    unset($q2);
                    $service_cdr = false;
                    switch( true ){

                      
                      case ( $cdr['lastapp'] == 'Dial' && self::checkDuplication( $cdrs, $cdr['dstchannel'] )):
                           $service_cdr = true;
                           break;
                          
                      
                      // Echo-test
                      case ( preg_match('/demo-echotest/', $cdr['lastdata'], $m ) === 1 ):   
                            $chan_describe = "<i class=\"  mdi mdi-surround-sound\"></i> "._l("Эхо тест",true) ." </i>";
                          break;  

                      // VoiceMail
                      case ( preg_match('/(.*)\@(.*)-vmdefault/', $cdr['lastdata'], $m ) === 1 ):   // 777@SOHO-vmdefault
                            $chan_describe = "<i class=\" mdi mdi-email-check\"></i> "._l("Сообщение",true) ." {$m[1]}@{$m[2]}</i>";
                          break;      

                      // Destinaton channel Expanding
                      case ( preg_match('/SIP\/(.*)-/', $cdr['dstchannel'], $m) === 1 ): 
                         // Compare with SIP name
                          $chan_describe = getDatabase()->one("SELECT concat('<i class=\"bordered mdi mdi-headset\" tel=\"',sip.extension,'\"></i>',sip.extension,' ', sip.first_name) as sip_name
                                                          FROM t_sip_users sip 
                                                             LEFT JOIN admin_users au ON au.default_tenant_id = 0{$cdr['tenant_id']} AND au.sip_user_id = sip.id
                                                         WHERE  tenant_id = 0{$cdr['tenant_id']} AND  
                                                                ( '{$cdr['dstchannel']}' LIKE  concat('SIP/', sip.name, '-%') ) ")['sip_name'];
                         // Find  Trunk name matches 
                          if(!$chan_describe){
                            $chan_describe = getDatabase()->one("SELECT
                                                              concat('<i class=\"mdi mdi-server\"></i> ', ifnull(description,name ))  as trunk_name 
                                                                FROM trunks 
                                                                 WHERE '{$cdr['dstchannel']}' like concat('SIP/',name,'-%')")['trunk_name'];
                                                                
                          }
                          // Make SIP AS Undefined SIP Peer
                          if(!$chan_describe)
                            $chan_describe = "<i class=\" mdi mdi-cloud-question\"></i> {$m[1]}</i>";

                          break;
                          ;;

                     // IVR MENU
                      case ( preg_match( '/Local\/(.*)-ivrmenu-(.*)-/', $cdr['dstchannel'], $m1 ) === 1 || preg_match( '/Local\/(.*)-ivrmenu-(.*)-/', $cdr['channel'], $m2) === 1):  
                           $chan_matched = $m1 ? $cdr['dstchannel'] : $cdr['channel'] ;
                           $id = $m1[2]? $m1[2] : $m2[2];
                           $chan_describe = getDatabase()->one("SELECT concat(\"<i class=\'mdi mdi-arrow-decision\'></i>\", name) as name 
                                                                FROM t_ivrmenu                                                              
                                                                WHERE tenant_id = 0{$cdr['tenant_id']} AND  
                                                                  ( id = 0{$id} OR '{$chan_matched}' LIKE   concat('%-ivrmenu-',id,'-%') ) ")['name'];
                           
                           $service_cdr = true;
                           break;
                          ;; 
                        // QUEUE
                      case ( preg_match('/Local\/(.*)-queue-(.*)-/', $cdr['dstchannel'], $q1 ) === 1 || preg_match('/Local\/(.*)-queue-(.*)-/', $cdr['channel'], $q2 ) === 1 ):  
                           $chan_matched = $q1 ? $cdr['dstchannel'] : $cdr['channel'] ;
                           $id = $q1[2]? $q1[2] : $q2[2];
                           $chan_describe = getDatabase()->one("SELECT  concat('<i class=\"mdi mdi-account-multiple-outline\"></i> ', name) as name        
                                                            FROM t_queues
                                                            WHERE tenant_id = 0{$cdr['tenant_id']} AND  
                                                                 '{$chan_matched}' LIKE concat('%-queue-',id,'-%')   ")['name'];
                           $service_cdr = true;
                           break;
                          ;; 


                        // LOCAL resources 
                      case ( preg_match('/Local\/(.*)\@internal-(.*)-(.*)/', $cdr['dstchannel'], $m ) === 1 ): 
                            $chan_describe = "<i class=\" mdi mdi-cloud-question\"></i> {$m[1]}@{$m[2]}</i>";
                          break;

                      
                         ;;
                    }

                   $undef_ico = '<i class=" mdi mdi-cloud-question"></i>';
                   
                   $cdr['src'] =          $src_described ? $src_described :  $cdr['src'];
                   $cdr['dst'] =          $dst_described ? $dst_described :  $cdr['dst'];
                   $cdr['dstchannel']  =  $chan_describe ? $chan_describe :  $cdr['dstchannel']  ;

                   /* 
                    $clear_src = preg_replace('/'.$cdr['src'].'/','',$cdr['clid']);
                    if($clear_src)
                       $cdr['src'] = $cdr['src'] . ' ' . preg_replace('/<>/','',$clear_src);
		    */

       		   // Correct Disposition according to  Queue Service status  ( Qeue  Was answered elsewhere )
    		    // if( preg_match('/\:ANSWERED/',$cdr['service_status'])  ){
    		    //          $cdr['disposition'] = 'ANSWERED';
    	            // }

                   if( preg_match('/ABANDONED/',$cdr['service_status']) && ( $cdr['duration'] > 4  ||  $cdr['duration'] <=  4 && $cdr['dst'] == 's'  ) ){
                    $cdr['disposition'] = 'NO ANSWER';
                    //Search For answered :
                    $src_orig = preg_replace('/[^0-9]/','',$src_orig);
                    if( getDatabase()->one( "SELECT count(*) AS cnt FROM t_cdrs 
			              		     WHERE tenant_id = {$cdr['tenant_id']} AND 
								 datediff(calldate,'{$cdr['calldate']}') = 0 AND  
						   (billsec = 0 OR billsec > 3 ) AND
                                                   uniqueid != {$uniq_orig}  AND
                                                   calldate > '{$date_orig}' AND 
                                              ( ( ( src = '{$src_orig}' OR src = '+38{$src_orig}' OR src='048{$src_orig}' ) AND  ( service_status like '%:ANSWERED' ) ) OR
                                                ( ( dst = '{$src_orig}' OR dst = '+38{$src_orig}' ) AND  disposition = 'ANSWERED' ) )"  )['cnt'] > 0 ){

                        $cdr['uniqueid'] = $cdr['uniqueid'] . ' on_service' ;                    
                    }else{
                        $cdr['uniqueid'] = $cdr['uniqueid'] . ' no_service' ;
                    }    

		    }
                  

		    
                   // Write duration near dispo = answered
                   $duration = ( $cdr['disposition'] == 'ANSWERED') ? $cdr['billsec'] . 's' : '';
                   
                   
                        
                   // FIND  Recording
                //    if(glob("/var/spool/asterisk/monitor/{$cdr['uniqueid']}.*") && $cdr['billsec'] > 0  && $cdr['disposition'] == 'ANSWERED' ) {
                //     $file = " <div title=" . _l("Прослушать",true) . " class='play-rec ' ref='{$cdr['uniqueid']}' ><i class='cdr-play-icon fa  fa-play fa-1x '> </i></div> ";
		   //    }  
		   $VARIANT1="/var/spool/asterisk/monitor/{$cdr['uniqueid']}.wav";
		   $VARIANT2="/var/spool/asterisk/monitor/{$cdr['uniqueid']}.WAV";
		   if( ( file_exists( $VARIANT1) || file_exists( $VARIANT2 ) )
			       && $cdr['billsec'] > 0  && $cdr['disposition'] == 'ANSWERED' ) {
                     $file = " <div title=" . _l("Прослушать",true) . " class='play-rec ' ref='{$cdr['uniqueid']}' ><i class='cdr-play-icon fa  fa-play fa-1x '> </i></div> ";
                   }


                  // Translate Asterisk dispositions
                    if( $PBX->ini['general']['default_lang'] != 'en' ){                      
                      $cdr['disposition'] = _l( $cdr['disposition'], true,'en');
                      $direction_translation = _l( $cdr['direction'], true,'en');
                       if( $cdr['direction'] == 'INBOUND')
                          $cdr['direction'] = "<i class=' fa fa-arrow-circle-o-right fa-2x text-primary INBOUND' title='{$direction_translation}' style='color: #188ae277 !important;font-size:24px !important'></i> ";
                       if( $cdr['direction'] == 'OUTBOUND')
                          $cdr['direction'] = "<i class=' fa fa-arrow-circle-o-left fa-2x text-info OUTBOUND' title='{$direction_translation}' style='color: #35b8e077 !important;font-size:24px !important'></i> ";                      
                    }
                    $cdr['disposition'] .=  ',  ' . $duration   ;
                    
                      
                    $cdr['billsec']  =  $cdr['billsec'] ? $file ."<div style='float:right;display:inline;margin:auto;text-align:center;' title='Total duration:{$cdr['duration']}'> </div>" : ''; 

		                $cdr['uniqueid'] = $cdr['uniqueid'] . ':'.$cdr['tenant_id'] ;



                    // READ CDR TAGS and EXPAND USER tagged DATA , 
                  if( preg_match("/\{/", $cdr['tags'] ) ){
                       $tags = json_decode( $cdr['tags'] , true);

                       $tags['num'] = preg_replace('/^38/','',$tags['num']);
                       $balance = isset($tags['balance'])?round($tags['balance'],2):0;                       
                       $bal_class  = ($balance < 0)? 'text-danger' : 'text-success';

                       if($tags['url']){
                         $B['uid'] = ( !$B['uid'] && preg_match('/\?id=(\d+)/', $B['url'], $m ) ) ? $m[1] : '' ;
                        
                        switch( true ){
                          case ( (int)$tags['astatus']  === -1  ):  // No billing account, not yet connected client 
                              $type_class =  ' mdi-account-outline bordered' ;                             

                              $type_color = 'text-muted '; 
                              $title = " {$tags['num']}, НЕПОДКЛЮЧЕН, ID:{$tags['uid']} \n{$tags['comments']}\n ${tags['actual_address']} ";
                              break;
                            ;;
                          case ( (int)$tags['astatus']  === 0  ):  // ACTIVE Account, non blocked                               
                              $type_class =  ' mdi-account-card-details ' ;
                              $type_color = ( (int)$tags['istatus']  === 1) ? 'text-primary ' :'text-muted ' ; // Internet Blocked only
                              
                              $title = " {$tags['num']}, АКТИВНЫЙ,  {$balance}грн, ID:{$tags['uid']} \n{$tags['comments']}\n ${tags['actual_address']} "; 
                              break;
                            ;;  
                          case ( (int)$tags['astatus']  === 1  ):  // BLOCKED  Account!
                              $type_class =  ' mdi-account-card-details ' ;
                              $type_color = 'text-danger '; // Account Blocked  totally
                              $title =" {$tags['num']}, ЗАБЛОКИРОВАН, {$balance}грн, ID:{$tags['uid']} \n{$tags['comments']}\n ${tags['actual_address']} ";
                              break;
                            ;;
                        }

                         $titled_url = preg_replace("/<a /","<a title='{$title}'", $tags['url'] );                         

			                 // astatus = account status(client or not),  istatus = internet status(blocked or not)  tstatus = TV status(on/off)
                         $REGION_INFO = (isset($tags['firm']) || isset($tags['district']) ) ? "<span class='float-right text-muted'><i class='fa fa-map-marker'></i> {$tags['firm']}, {$tags['district']} </span>"  : '';

                         if( !$REGION_INFO && isset($tags['actual_address']) ){                                                    
                          $actual_addr = preg_replace('/ул. |\'|\"/','', $tags['actual_address']);                          
                          $actual_addr_link = explode('\r\n',$actual_addr)[0];
                          $REGION_INFO = "<span class='float-right text-muted location-label' title='{$actual_addr}'><a target=about_blank href='https://maps.google.com/?q={$actual_addr_link}'><i class='fa fa-map-marker'></i> {$actual_addr} </a></span>";
                         }

			 // If dst is a service string( 's' or 'default' ) , then call to src or tags(num) //
               		 $num_tocall = ( (int)$cdr['dst'] == '' ) ? $cdr['src'] : $cdr['dst'];
			             $num_tocall = ( (int)$num_tocall == '' ) ? $tags['num'] : $num_tocall ;
                         $tagged_info = "<i class='mdi mdi-phone bordered tags-tel text-secondary font-bold' onclick='call(\"{$num_tocall}\");' title='Dial {$tags['num']}'></i><i class='filter-it' title='"._l( 'Поиск:' , true )." {$tags['num']}'>{$tags['num']}</i> <i class='mdi {$type_class} {$type_color}' ></i> " . $titled_url . "<i class='mdi mdi-open-in-new text-primary'></i>,<span class='tags-bal {$bal_class}'>{$balance}грн </span>${REGION_INFO}";

                       }

                       // Try to Guess SOURCE  Number
                        if(strlen($tagged_info)){
                          if(  $tags['num'] == preg_replace('/^38/','',$cdr['dst']) )
                            $cdr['dst'] = $tagged_info; 
                          if(  $tags['num'] == preg_replace('/^38/','',$cdr['src']) )
                            $cdr['src'] = $tagged_info; 
                        }


                  }

                  // Auto add Call() function or numeric dst/src 
                  if( preg_replace('/[^0-9]/','', $cdr['src']) == $cdr['src'] && strlen($cdr['src']) > 2 )
                     $cdr['src'] = "<i class='mdi mdi-phone bordered tags-tel ' titile='Make Call' onclick='call(\"{$cdr['src']}\");'> &nbsp;{$cdr['src']}</i>" ;

                  // If source has tags, then he calls to DID, do not highligh it!
                  if( preg_replace('/[^0-9]/','', $cdr['dst']) == $cdr['dst'] && strlen($cdr['dst']) > 2  &&
                      $tags['num'] != preg_replace('/^38/','', $src_orig)  )
                    $cdr['dst'] = "<i class='mdi mdi-phone bordered tags-tel ' onclick='call(\"{$cdr['dst']}\");'> &nbsp;{$cdr['dst']} </i>";

		   
    		   // Try to Guess SERVICE  DESTINATION Number
                        if( $cdr['dst'] == 's' ){

                           if( preg_match('/\[(.*)\]/',$cdr['clid'],$match ) ){
                            $cdr['dst'] = $match[1] . $cdr['INBOUND_DID'];
                           }elseIf(isset($tags['did']) && $tags['did'] != ''){
                            $cdr['dst'] = "<i class='mdi mdi-server' title='Outbound Sevice Lines'> {$tags['did']}</i>" ;
                           }elseIf( $cdr['lastapp'] == 'BackGround' ){
                             $cdr['dst'] = "<i class='mdi mdi-music' title='Play media'> Playback ";
                            	 $cdr['dstchannel'] =  strlen($cdr['lastdata']) > 18 ? substr($cdr['lastdata'],0,18)."..." : $cdr['lastdata'];
                           }elseIf($cdr['INBOUND_DID'] != ''){
			                       $cdr['dst'] = $cdr['INBOUND_DID'];
			                     }

                           if( $cdr['dst'] == 's' )
                             $service_cdr = true;
                         }

                                   
                    // Output only common fields  and skip unreleted calls dropped in ivrs //
                   if( ( !$service_cdr || preg_match('/service/', $cdr['uniqueid'] )  )&& (count($data)) < $LIMIT  )      
                     $data[] = array_values( array_intersect_key($cdr, $datatable_fields) );                                       
                    
                  }

                  $json['data'] = $data;
                  $json['draw'] = $_GET['draw'] ? $_GET['draw'] : 1;        
                  $json['recordsTotal'] = count($data);
                  $json['recordsFiltered'] = getDatabase()->one( "SELECT count(*) AS cnt FROM t_cdrs {$WHERE}" )['cnt'] ;
                 

                 echo (count($json) == 1) ? json_encode($json[0]) : json_encode($json);
     }




    public function getFilter($fields){
                global $_GET;
                $FILTER  = array();
                $glob_search_fields =  array_intersect( array_keys($fields), array('src','dst') ) ;
                  ///
                 // Search DATA API request, return only in Json
                 ///
                $FieldSearch = array();
                $GloblSearch = array();                 
              // certain Field search   (filters) //
                if( isset($_GET['columns']) && is_array($_GET['columns']) )                    
                  foreach($_GET['columns'] as $col)
                    if(trim($col['search']['value']) != ''){
                      if( array_keys($fields)[ $col['data']  ] == 'calldate'){
                         $CALLDATE_FILTER = array_keys($fields)[ $col['data']  ] . " BETWEEN " . $col['search']['value'];
                      }else{
                        if($_GET['search']['regex'])
                          $FieldSearch[] = array_keys($fields)[ $col['data']  ] . " LIKE '%" . $col['search']['value'] . "%'";
                        else
                          $FieldSearch[] = array_keys($fields)[ $col['data']  ] . " = '" . $col['search']['value'] . "'";
                      }
                    }
               // Global search by all fields //
                if(isset($_GET['search']['value']) && $_GET['search']['value'] != '' )
                   foreach( $glob_search_fields as $f)              
                     $GloblSearch[] = " `{$f}` LIKE '%{$_GET['search']['value']}%' ";                      


                if( $GloblSearch )
                  $WHERE = " (" . implode(" OR ", $GloblSearch ) . " ) ";                    

                if($FieldSearch){
                  $OPERATOR = (isset($_GET['search']['regex']) && $_GET['search']['regex'] ) ? 'OR':'AND';
                  $WHERE =  ($WHERE ? $WHERE . ' AND ':'') . " (" . implode(" {$OPERATOR} ", $FieldSearch ) . " ) ";                  
                }

                
               if($WHERE)
                  $FILTER[] = $WHERE;  

                if($CALLDATE_FILTER)
                  $FILTER[] = $CALLDATE_FILTER;

               //  if( $cdr['src'] != 't' && $cdr['dst'] != 't' && $cdr['src'] && $cdr['dst'] && $cdr['dstchannel'] )
                 // AND dstchannel != ''  - service numbers
               $FILTER[] =  " ( src != 't' AND src != 't' AND src != '' AND dst != ''  )";
               // Remove VoiceMail created sub-channels with recordings
               $FILTER[] =  " NOT ( dstchannel  = '' AND lastapp  = 'Dial' ) ";
               // Remove Service CDRs TODO - not add to CDRs
               //$FILTER[] =  " NOT ( dstchannel  like  '%-ivrmenu-%' OR dstchannel  like  '%-queue-%' ) ";
               //$FILTER[] =  " NOT ( channel  like  '%-ivrmenu-%' OR channel  like  '%-queue-%' ) ";


              /// DATA PERMISSIONS / RESTRICTIONS // 
               $me = $_SESSION['CRM_user']['sip']['name'] ;
               $myExten = $_SESSION['CRM_user']['sip']['extension'] ;
               // Role: 0,1,2 -  can see everything!(including empty tenatn_id)  //
               // Role: 3 and 4 :   admins sees only his tenant data  
               if( $_SESSION['CRM_user']['role_id'] > 2 ) 
                  $FILTER[] = ' ( ifnull(t_cdrs.tenant_id,0) = 0' . $_SESSION['CRM_user']['default_tenant_id']  . ') ' ;    

               // #2 CRM Phone ROLE  only CRM, his calls and his Queues  //
               if( $_SESSION['CRM_user']['role_id'] == 5  ){
                  $FILTER[] = " ( src like '%{$me}%' OR dst like '%{$me}%' OR dst like '%{$myExten}%' OR channel like 'SIP/{$me}-%' OR dstchannel like 'SIP/{$me}-%' OR  SUBSTRING_INDEX(service_status,'-',-1) IN (SELECT q.id  FROM t_queues q,t_queue_members m WHERE q.name = m.queue_name AND m.interface = 'SIP/{$me}' )) " ;   


               }
               
                

              return count($FILTER) ? " WHERE " . implode(' AND ',  $FILTER ) : '';

    }


    public static  function getSorter($fields){
                 global $_GET;                 
                 ///
                 // ORDER DATA API request by return index 
                 ///
                  $p_start =  isset($_GET['start'])?(int)$_GET['start'] : 0;
                  $p_size = ( isset($_GET['length'])?(int)$_GET['length']: 100) * 3 ; // We increase Limit to compensate filtered out rows ( json return will be limited to length )
                  $PLIMIT = "{$p_start}, {$p_size}";

                  if( isset($_GET['order'])  ){
                    $order = $_GET['order'];                    
                    $ORDER = array_keys($fields)[ $order[0]['column'] ] . " " . $order[0]["dir"];
                  }else{
                    $ORDER =' `calldate` desc, uniqueid, duration, billsec';
                  }                  
               return  " ORDER BY {$ORDER},dst LIMIT {$PLIMIT} ";
       }



}

