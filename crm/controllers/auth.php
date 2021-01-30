<?php

 class Auth 
 {

    public static function Login(){

       global $PBX;

       if( !( getParam('login') && getParam('password') ) ){
        warn('Failed to login  - login/password can not be empty!!!');
        exit;
       }

       $l = mysql_escape_string( getParam('login') ) ;
       $p = mysql_escape_string( getParam('password') );   

  // Check if we have REMOTE AUTHENTICATION Procedure enabled
      if( isset($PBX->ini['manager']['enabled']) &&  $PBX->ini['manager']['enabled'] != 0 ){
         try{
              // We SEPARATE EACH remote CONNECT BY COMPANY PREFIX (Tenant representation)
              // Example:  SOHO_  
              $_pref_id = $PBX->ini['manager']['company_prefix'] ? $PBX->ini['manager']['company_prefix']  : '' ;
              // Try  External DB AUTHH method //
              $link = mysql_connect($PBX->ini['manager']['host'], 
                                    $PBX->ini['manager']['user'], 
                                    $PBX->ini['manager']['password']);

              if(!$link)
                throw new Exception( "Failed to connect to Remote Mysql:" . mysql_error() );            

              $switch  = mysql_select_db( $PBX->ini['manager']['name'] );
              if(!$switch)
                 throw new Exception( "Failed Switch to Manager DB: " . mysql_error() );

               mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $link);

              $res = mysql_query("SELECT * FROM user WHERE email = '{$l}';");
              if(!$res)
                 throw new Exception( "Failed Locate user in  External Manager by Email:[{$l}]  " . mysql_error() );

              if( !mysql_affected_rows() ) {
                  warn("External Manager: Such User not found!  ", true);
              }else{
                // WE've GOT RESPONSE!!!! COMPARE HASH AND GET DATA //
                $m_user = mysql_fetch_assoc($res);                              
                if( password_verify($p, $m_user['password']) ){                  
                   $EXT = $m_user['sip'];  
                   $SIP_NAME = $_pref_id . $EXT;
                   $_SESSION['CRM_user']['manager_user']  =  $m_user;  
                   $_SESSION['CRM_user']['user'] = $m_user['first_name'] . ' ' . $m_user['last_name'];
                   $_SESSION['CRM_user']['email'] =          $m_user['email'] ;
                   //$_SESSION['CRM_user']['sip'] = array('name' => $SIP_NAME);
                   $_SESSION['CRM_user']['sip']['name'] =  $SIP_NAME;
                   $_SESSION['CRM_user']['sip']['extension'] =  $EXT;

                   // MAP External  WITH  CLOUD by ref_id as 'SOHO_'  AND GET SIP name as : 'SOHO-101')                  
                   $cloud_id = getDatabase()->one("SELECT id FROM tenants WHERE concat(ref_id,'-') = '{$_pref_id}'")['id'];
                   if($cloud_id){
                          $in_cloud  = getDatabase()->one("SELECT t_sip_users.id as sip_id,
                                                                 roles.name as role_name,
                                                                 roles.id as role_id,
                                                                 users.user as user_name,
                                                                 secret
                                                          FROM t_sip_users
                                                                 LEFT JOIN admin_users      users ON users.sip_user_id = t_sip_users.id 
                                                                 LEFT JOIN admin_user_roles roles ON roles.id =  users.role
                                                          WHERE t_sip_users.tenant_id = 0{$cloud_id} AND            
                                                               ( t_sip_users.extension = '{$EXT}' OR t_sip_users.name = '{$SIP_NAME}') "
                                                      );
                          if(count($in_cloud)){
                            $_SESSION['CRM_user']['default_tenant_id']  = $cloud_id;
                            $_SESSION['CRM_user']['sip']['secret'] = $in_cloud['secret'] ;  // !!!WARING!! Not recomended BUT WORKS //
                            $_SESSION['CRM_user']['role_name'] =     $in_cloud['role_name'] ;
                            $_SESSION['CRM_user']['role_id'] =       $in_cloud['role_id'] ;                   
                            // Populate local Cloud with External Data 
                            getDatabase()->execute("UPDATE t_sip_users 
                                                            SET first_name = '{$m_user['first_name']}',
                                                                last_name =  '{$m_user['last_name']}',
                                                                email = '{$m_user['email']}'
                                                          WHERE id = 0{$in_cloud['sip_id']} ");
                            getDatabase()->execute("UPDATE admin_users set email = '{$m_user['email']}' WHERE sip_user_id = 0{$in_cloud['sip_id']}"); 

                           }else{
                             // Still allow local ACCESS to CRM without PHONE SETTINGS
                              $_SESSION['CRM_user']['warning'] = 'Failed to Populate ACCOUNT in SIP Cloud!';
                              $_SESSION['CRM_user']['role'] =    'Operator' ; // Default 
                              $_SESSION['CRM_user']['role_id'] = 4 ;// Default 
                           }      
                    }else{
                           $_SESSION['CRM_user']['warning'] = "Failed to Identify Cloud by: [ {$_pref_id} ]!";
                    }
                    
                   
                   
                   
                }else{
                  warn("Manager: Wrong password! " ,true);
                }   

                
              }

          }catch( Exception $e){
             warn('Manager: Excetion:' . $e->getMessage() );          
             mysql_close($link);
             return;
          }finally{             
             mysql_close($link);
          }

      }else{ // Try  Local User  AUTH method //

           $SQL = "SELECT *,
                         admin_user_roles.name as  role_name,
                         admin_users.role as  role_id,
                         admin_users.email  admin_user_email
                    FROM admin_users, admin_user_roles 
                    WHERE 
                         admin_users.role = admin_user_roles.id AND 
                         user = :login AND 
                         ( (pass = UPPER(SHA1(UNHEX(SHA1((concat('PASSW0RD!salt', :password)))))) )  OR
                            pass = :password 
                          )";      

 
           $_SESSION['CRM_user'] = getDatabase()->one($SQL,  array( ':login' => getParam('login'), ':password' => getParam('password') )) ;
           
           if( !$_SESSION['CRM_user'] ){
               warn('Wrong Login or Password!', true);
           }else{            
            // Load SIP settings into the Session //
             if( isset($_SESSION['CRM_user']['sip_user_id']) ){
               $_SESSION['CRM_user']['sip'] = getDatabase()->one("SELECT * FROM t_sip_users WHERE id = :id ",array(':id' => $_SESSION['CRM_user']['sip_user_id']));              
              if( $_SESSION['CRM_user']['admin_user_email'] )    
                getDatabase()->execute("UPDATE t_sip_users 
                                                    SET first_name ='{$_SESSION['CRM_user']['user_fname']}',
                                                        last_name = '{$_SESSION['CRM_user']['user_lname']}',
                                                        email =     '{$_SESSION['CRM_user']['admin_user_email']}'
                                                  WHERE id = {$_SESSION['CRM_user']['sip_user_id']} AND
                                                        ( ifnull(first_name,'') = '' OR 
                                                          ifnull(email,'') = ''  )");
                                                  
             }  
           }  

      }     


      if( $_SESSION['CRM_user'] )
         isay('OK');
      else   
         warn('Failed to authenticate');
      

    }





    public static function Logout(){
       unset($_SESSION['CRM_user']);
    }





 }


?>
