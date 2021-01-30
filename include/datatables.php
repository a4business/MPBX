<?php

 require_once(__DIR__ . '/config.php');
 require_once __DIR__ . '/../vendor/autoload.php';


 class DataTables{

   // This procedure checks if such ANSWERED Dialed channel has else-where answered CDR(for example - in queue application CDR)
  static function checkDuplication( $cdrs, $dstChannel ){
    foreach($cdrs as $c)
      if( $c['dstchannel'] == $dstChannel && $c['lastapp'] != 'Dial' && $c['disposition'] == 'ANSWERED' )
       return true;
  }

 // Get channel 
  public static function chanDescribed( $dstchannel, $channel, $tenant_id ){  

     switch( true ){
        /*
                      // Echo-test
                      case ( preg_match('/demo-echotest/', $cdr['lastdata'], $m ) === 1 ):   
                            $chan_describe = "<i class=\"  mdi mdi-surround-sound\"></i> "._l("Эхо тест",true) ." </i>";
                          break;  
         
                      // VoiceMail
                      case ( preg_match('/(.*)\@(.*)-vmdefault/', $cdr['lastdata'], $m ) === 1 ):   // 777@SOHO-vmdefault
                            $chan_describe = "<i class=\" mdi mdi-email-check\"></i> "._l("Сообщение",true) ." {$m[1]}@{$m[2]}</i>";
                          break;      
                */
                      // Destinaton channel Expanding
                      case ( preg_match('/SIP\/(.*)-/', $dstchannel, $m) === 1 ): 
                         // Compare with SIP name
                          $chan_describe = getDatabase()->one("SELECT concat('<i class=\"bordered mdi mdi-headset\" tel=\"',sip.extension,'\"></i>',sip.extension,' ', sip.first_name) as sip_name
                                                          FROM t_sip_users sip 
                                                             LEFT JOIN admin_users au ON au.default_tenant_id = 0{$tenant_id} AND au.sip_user_id = sip.id
                                                         WHERE  tenant_id = 0{$tenant_id} AND  
                                                                ( '{$dstchannel}' LIKE  concat('SIP/', sip.name, '-%') ) ")['sip_name'];
                         // Find  Trunk name matches 
                          if(!$chan_describe){
                            $chan_describe = getDatabase()->one("SELECT
                                                              concat('<i class=\"mdi mdi-server\"></i> ', ifnull(description,name ))  as trunk_name 
                                                                FROM trunks 
                                                                 WHERE '{$dstchannel}' like concat('SIP/',name,'-%')")['trunk_name'];
                                                                
                          }
                          // Make SIP AS Undefined SIP Peer
                          if(!$chan_describe)
                            $chan_describe = "<i class=\" mdi mdi-cloud-question\"></i> {$m[1]}</i>";

                          break;
                          ;;

                     // IVR MENU
                      case ( preg_match( '/Local\/(.*)-ivrmenu-(.*)-/', $dstchannel, $m1 ) === 1 || preg_match( '/Local\/(.*)-ivrmenu-(.*)-/', $channel, $m2) === 1):  
                           $chan_matched = $m1 ? $dstchannel : $channel ;
                           $id = $m1[2]? $m1[2] : $m2[2];
                           $chan_describe = getDatabase()->one("SELECT concat(\"<i class=\'mdi mdi-arrow-decision\'></i>\", name) as name 
                                                                FROM t_ivrmenu                                                              
                                                                WHERE tenant_id = 0{$cdr['tenant_id']} AND  
                                                                  ( id = 0{$id} OR '{$chan_matched}' LIKE   concat('%-ivrmenu-',id,'-%') ) ")['name'];
                           
                           $service_cdr = true;
                           break;
                          ;; 
                        // QUEUE
                      case ( preg_match('/Local\/(.*)-queue-(.*)-/', $dstchannel, $q1 ) === 1 || preg_match('/Local\/(.*)-queue-(.*)-/', $channel, $q2 ) === 1 ):  
                           $chan_matched = $q1 ? $dstchannel : $channel;
                           $id = $q1[2]? $q1[2] : $q2[2];
                           $chan_describe = getDatabase()->one("SELECT  concat('<i class=\"mdi mdi-account-multiple-outline\"></i> ', name) as name        
                                                            FROM t_queues
                                                            WHERE tenant_id = 0{$tenant_id} AND  
                                                                 '{$chan_matched}' LIKE concat('%-queue-',id,'-%')   ")['name'];
                           $service_cdr = true;
                           break;
                          ;; 


                        // LOCAL resources 
                      case ( preg_match('/Local\/(.*)\@internal-(.*)-(.*)/', $dstchannel, $m ) === 1 ): 
                            $chan_describe = "<i class=\" mdi mdi-cloud-question\"></i> {$m[1]}@{$m[2]}</i>";
                          break;                      
                         ;;

          }
          return $chan_describe;
   }


   public static function getFilter($fields){
                global $_GET;
                global $_SESSION;
                $FILTER  = array();
                $glob_search_fields =  array_intersect( array_keys($fields), array('direction','disposition','src','dst') ) ;
               
                  ///
                 // Search DATA API request, return only in Json
                 ///
                $FieldSearch = array();
                $GloblSearch = array();                 
              // certain Field search   (filters) //
                if( isset($_GET['columns']) && is_array($_GET['columns']) )                    
                  foreach($_GET['columns'] as $col)
                    if(trim($col['search']['value']) != ''){
                      $column = ( (int)$col['data'] ===  $col['data'] ) ? array_keys($fields)[ $col['data']  ] :  $col['data'] ;
                      if( $column == 'calldate'){
                         $CALLDATE_FILTER = "   date({$column}) = date('{$col['search']['value']}') ";
                      }else{
                        if($_GET['search']['regex'])
                          $FieldSearch[] = $column . "  LIKE '%" . $col['search']['value'] . "%'";
                        else
                          $FieldSearch[] = $column . " = '" . $col['search']['value'] . "'";
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
                  $OPERATOR  = 'AND';
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
               $me = $_SESSION['user']['sip_user_id'] ;               
               // Role: 1 - GLobal admin  can see everything!(including empty tenatn_id)  //
               // Role: 2,3 and 4 :  tenant admins sees only his default tenant data  
               $current_tenant =$_SESSION['tenantid'] ? $_SESSION['tenantid']  : $_SESSION['user']['default_tenant_id']  ;
             if( $_SESSION['user']['role'] > 0 ) 
                  $FILTER[] = ' ( ifnull(t_cdrs.tenant_id,0) = 0' . $current_tenant . ') ' ;    

               // #2 CRM Phone ROLE  only CRM, his calls and his Queues  //
               if( $_SESSION['user']['role'] == 5  ){
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

                  if( isset($_GET['order']) && is_array($_GET['order']) ){
                    $order = $_GET['order'];                    
                    $ORDER = array_keys($fields)[ $order[0]['column'] ] . " " . $order[0]["dir"];
                  }else{
                    $ORDER =' `calldate` desc, uniqueid, duration, billsec';
                  }                  
               return  " ORDER BY {$ORDER},dst LIMIT {$PLIMIT} ";
       }
	
 }




?>
