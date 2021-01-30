<?php

class CRM{

   	  // Left Menu definition - custom for each account role /
       private  $navigation ;


       // Switch GUI interface Text Language //
       // Called From login(welcome) page //
       public static function switchLang( $lang = 'ru' ){
           setcookie('language', $lang, time()+3600*24*100 , '/') or die('unable to create cookie!');     
        }   


       public  static function rtcSwitch( $sip_name, $mode = 'rtc' )
         {
          global $PBX;
           $status = ($mode == 'rtc') ? 'yes' : 'no';
           getDatabase()->execute("UPDATE t_sip_users SET 
                                         avpf = '{$status}',
                                         force_avp = '{$status}',
                                         dtlsenable = '{$status}',
                                         encryption = '{$status}'
                                        WHERE 
                                         name = '{$sip_name}' "); 
          // $PBX->run('sip reload');
           exec('/usr/sbin/rasterisk -rx "sip reload"' );
           echo 'Done';
         }



       public static function GetGroups(){         
          global $PBX;
          //$r = getDatabase()->one("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
          $rows = getDatabase()->all( "SELECT t_queues.id as id, t_queues.name as name,
                                              tenants.ref_id as tenant_name ,
                                              tenants.id as tenant_id
                                       FROM t_queues, tenants
                                       WHERE t_queues.tenant_id  = tenants.id AND
                                             name IN ( SELECT queue_name
                                                       FROM t_queue_members 
                                                       WHERE tenant_id = tenants.id AND
                                                             interface =  concat('SIP/','{$_SESSION['CRM_user']['sip']['name']}') )" 
                                     );

          foreach($rows as $row){
               $qmembers = getDatabase()->all("SELECT ifnull(t_sip_users.extension,'') as exten, 
                                                      ifnull(paused,0) as paused, 
                                                      interface, 
                                                      ifnull(concat(ifnull(user_fname,first_name),' ',ifnull(user_lname,last_name)),'') as crm_user_name,
                                                    (CASE
                                                       WHEN IFNULL(t_sip_users.ipaddr,'') = '' THEN 'OFF'
                                                        WHEN ( (regseconds - UNIX_TIMESTAMP() ) >  0) THEN 'ON'
                                                        WHEN ( (regseconds - UNIX_TIMESTAMP() ) > -1) THEN 'ON'
                                                        WHEN ( (regseconds - UNIX_TIMESTAMP() ) < -5) THEN 'OFF'
                                                    END) AS chan_reg_status,
                                                    concat(ipaddr,' ',useragent) as user_dev_info
                                              FROM t_queue_members 
                                                     LEFT JOIN t_sip_users ON t_sip_users.name = REPLACE(t_queue_members.interface,'SIP/','')
                                                     LEFT JOIN admin_users ON admin_users.sip_user_id = t_sip_users.id 
                                              WHERE  queue_name = '{$row['name']}' " );

             /// Generate HTML of members //
                $m_html='';
                foreach ($qmembers as $mem){                                          
                   $minimized = (count($Q['members']) > 6) ? 'small' :'';
                   $status = ($mem['chan_reg_status'] == 'OFF') ? 'disabled' : 'primary';
                   $status = ($mem['paused'] == 1) ? 'warning' : $status;
                   $status_txt = ($mem['paused'] == 1) ? "ON-PAUSE\n" : $status;
                   $is_me = ( $_SESSION['CRM_user']['sip']['extension'] == $mem['exten'] ); 
                   $is_online = ( $status == 'primary' && !$is_me ) ? 'phone' : '';         
                   $m_html .= "<p class=' {$is_online} list-inline-item m-0 {$minimized} badge badge-{$status} badge-adjust bordered' sip='{$mem['exten']}' title='{$status_txt} {$mem['crm_user_name']} \n{$mem['user_dev_info']} ' onclick='call({$mem['exten']});'>";
                   $m_html .=    "<i class='mdi mdi-" . ($is_me?'headphones-box  ':'headphones') ." ' ></i>{$mem['exten']}";
                   $m_html .= "</p>" ;                 
                  }

               // Cound Queue answered  and Missed calls    
                 $q_answered = getDatabase()->one("SELECT count(*) as ans 
                                                   FROM t_cdrs 
                                                   WHERE datediff(calldate,now()) = 0 AND
                                                    tenant_id = {$row['tenant_id']} AND
                                                    service_status = '{$row['tenant_name']}-queue-{$row['id']}:ANSWERED'")['ans'];
                 $q_total_missed = getDatabase()->one("SELECT count(*) as miss FROM t_cdrs
                                                       WHERE datediff(calldate,now()) = 0 AND 
                                                       tenant_id = {$row['tenant_id']} AND
                                                       service_status = '{$row['tenant_name']}-queue-{$row['id']}:ABANDONED'")['miss'];

		 /*
                 $q_missed = getDatabase()->one("SELECT count(*) as missed FROM t_cdrs WHERE 
                                                  datediff(calldate,now()) = 0 AND 
                                                  billsec > 3 AND 
                                                  service_status = '{$row['tenant_name']}-queue-{$row['id']}:ABANDONED' AND
                                                  src NOT IN ( SELECT src FROM t_cdrs t2
                                                                                  WHERE calldate > t_cdrs.calldate AND
                                                                                        tenant_id = t_cdrs.tenant_id AND
                                                                                        service_status like '%:ANSWERED'
                                                               UNION 
                                                               SELECT dst FROM t_cdrs t3
                                                                  WHERE calldate > t_cdrs.calldate AND
                                                                     tenant_id = t_cdrs.tenant_id AND
								     disposition = 'ANSWERED'
                                                              ) 
							      ")['missed'];
		  */

		 $missed_check =  getDatabase()->all("SELECT src , calldate, tenant_id , tags,id
			 				          FROM t_cdrs WHERE 
							              	datediff(calldate,now()) = 0 AND 
                              tenant_id = {$row['tenant_id']} AND
								              ( billsec > 3 OR duration > 3 or dst = 's' ) AND
								              service_status like '%queue-{$row['id']}:ABANDONED'
                      ");
		 $real_missed = 0;
		 $missed=array();
		 $d='';

		 foreach($missed_check as $m_check){
			 $m_check_striped = preg_replace('/^\+?38/','', $m_check['src'] ) ;
			 // if He called again and has been answered
			 $is_recall = getDatabase()->one("SELECT count(*) as cnt 
            		          FROM t_cdrs WHERE
            				 			tenant_id = {$m_check['tenant_id']} AND
            							datediff(calldate,'{$m_check['calldate']}') = 0 AND 
            							calldate > '{$m_check['calldate']}' AND
            							( billsec = 0 OR billsec > 3 ) AND 
            							( src = '{$m_check['src']}' OR src = '38{$m_check['src']}' OR src = '{$m_check_striped}' ) AND
            							ifnull(service_status,'') like '%:ANSWERED' 
            							")['cnt'];

       // if We called HIM Back
			 $is_calledback = getDatabase()->one("SELECT count(*) as cnt FROM t_cdrs WHERE
                                                        tenant_id = {$m_check['tenant_id']} AND
                                                        datediff(calldate,'{$m_check['calldate']}' ) = 0 AND 
                                                        calldate > '{$m_check['calldate']}' AND
                                                        ( billsec = 0 OR billsec > 3 ) AND
                                          							( dst = '{$m_check['src']}' OR dst = '38{$m_check['src']}' OR dst = '{$m_check_striped}' ) AND
                                          							disposition = 'ANSWERED' 
							
							")['cnt'];

      			 if( ($is_recall == 0 && $is_calledback == 0 ) && !in_array($m_check['src'] , $missed ) ){
      				 $real_missed++;
				 $missed[] = $m_check['src'];

				 $d .= $m_check_striped ."\n";
				 //getDatabase()->execute("INSERT INTO t_cdrs_archive SELECT * FROM t_cdrs where id = 0{$m_check['id']}");  
				 //getDatabase()->execute("DELETE FROM t_cdrs WHERE id = 0{$m_check['id']}");
      			 }
		   }

       $Qinfo = Realtime::getQueues( $row['name'] );
       $Queues[] = array( 'id'=> $row['id'], 
                          'qname' => $row['name'], 
                          'members' => $qmembers , 
                          'members_html' => $m_html, 
                          'q_missed' => $real_missed,
                          'q_answered' => $q_answered, 
                          'q_total_missed' => $q_total_missed, 
                          'q_missed_list' => $d, 
                          'q_stats' =>  $Qinfo['stats'],
                          'q_active_calls' =>  $Qinfo['calls'] 
                        );

     }
          
          $lbl = array();
          $lbl['missed']=  'Unanswered calls (no callback)';
          $lbl['answered'] = 'Answers';
          $lbl['missed'] = 'Total unanswered numbers';
          $lbl['missed_calls'] = 'Missed';
          $lbl['missed_from'] = 'Phones of unanswered calls';

          // If default not EN, translate labels..
           if( $PBX->ini['general']['default_lang'] != 'en' )              
             foreach($lbl as $name=>$text)
               $lbl[$name] = _l( $text, true,'en');
           
          $i =  1;
           
         /// ACCORDION ? 
          if( count($Queues) > 3 ){
            echo "<li class='d-inline'><article class='accordion'>\n";
            foreach ($Queues as $Q ){ 
              echo "\t <section id='acc{$i}'> 
                       <h4 class='qstats'>                           
                        <a href='#acc{$i}' title='{$lbl['answered']}: {$Q['q_answered']}\n{$lbl['missed_calls']}: {$Q['q_total_missed']}\n{$lbl['missed']}: {$Q['q_missed']},\n{$lbl['missed_from']}:\n{$Q['q_missed_list']}' >
                          <span class='icon text-danger' style='font-size:20px;'></i>{$Q['q_missed']}</span>/<span class='icon text-success'>{$Q['q_answered']}</span>{$Q['qname']}
                         </a></h4>
                       <div class='cnt'> {$Q['members_html']} </div>
                       <p class='accnav'><a href='#acc" . ++$i . "'>&#10151;</a></p>\n    </section>\n";                  
            }
            echo "</article></li> \n";
          }else{  /// Group listing  ///                
            foreach ($Queues as $Q )  
             echo "<li class='d-inline'>\n
                     <div class='card card-xs qstats tooltiper  tooltipstered' data-tooltip-content='#qtip_{$Q['id']}'  style='float:left;margin: 2px !important;' > \n
                       <div class='card-header p-0 ' title='{$lbl['answered']}: {$Q['q_answered']}\n{$lbl['missed_calls']}: {$Q['q_total_missed']}\n{$lbl['missed']}: {$Q['q_missed']},\n{$lbl['missed_from']}:\n{$Q['q_missed_list']}'> 
                           <span class='icon text-danger ' style='font-size:24px;'></i>{$Q['q_missed']}</span>:
                           <span class='icon text-success' style='font-size:24px;'>{$Q['q_answered']}</span>
                           <i>{$Q['qname']}</i>
                       </div>\n 
                       <div class='card-body card-text p-1'><span class='text-success font-bold '>{$Q['q_active_calls']}</span>{$Q['members_html']}</div>
                    </div>\n
                   </li>" .
                   "<div style='display:none;'>                     
                     <span id='qtip_{$Q['id']}'> 
                       {$lbl['answered']}: {$Q['q_answered']},{$lbl['missed_calls']}: {$Q['q_total_missed']}<br>
                       {$lbl['missed']}: {$Q['q_missed']},<br>{$lbl['missed_from']}:<br>{$Q['q_missed_list']}<br>
                       {$Q['q_stats']}
                     </span>                     
                    </div> " ;
          }
        
      //if( $_SERVER['REMOTE_ADDR'] =='192.168.11.243' )

      }



      // This Loads all Pages and containers requeted by Ajax 

       public static function view(){          

    	    global $main_menu;
    	    global $PBX;

          $r =  pathinfo( $_GET['__route__'] , PATHINFO_FILENAME);                  
          $_route = $r  ? $r : 'index' ;
          $_route = preg_replace('/^crm$/','index',$_route);
    	    
          
  
          $navigation = array( 
                          array('ref' => 'dashboard', 'txt' => 'Состояние' ),
                          array('ref' => 'manager',   'txt' => 'Мэнеджер'),
                          array('ref' => 'phone',     'txt' => 'Телефон') 
                      );

         
         if( isset( $PBX->ini['general']['webrtc_auto_on'] ) && $PBX->ini['general']['webrtc_auto_on'] )
          $WEBrtcStatus = 'on';
         else
          $WEBrtcStatus = getDatabase()->one("SELECT 'on' as stat
                                               FROM t_sip_users 
                                               WHERE name = '{$_SESSION['CRM_user']['sip']['name']}'  AND
                                                     tenant_id = 0{$_SESSION['CRM_user']['default_tenant_id']} AND
                                                     encryption = 'yes'")['stat'];

    	    getTemplate()->display( $_route  . '.php', 
    	    	                     array(
    	    	                     	'WEBrtcStatus'   =>$WEBrtcStatus ? true : false, 
                                  't' =>$WEBrtcStatus  ,
    	    	                      'PBX'            => $PBX                                  
    	    	                     )
    	    	                   );
        }


        public static function sub_view(){
            $file = $_GET['__route__'] ? $_GET['__route__'] . '.php' : 'dashboard.php';

            getTemplate()->display( $file );

        }




      function CallHangup($channel){
        exec("/usr/sbin/rasterisk -rx 'channel request hangup ${channel}' 2>&1",$m );
        echo json_encode( $m );
      }




   
   }


?>
