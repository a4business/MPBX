<?php
 error_reporting(0);
 include_once('../include/config.php');
 session_start();
 $logo_image = $_SESSION['logo_image'];
 $default_logo = $config->getDefaultLogo();
 
 if ( $logo_image == '' ||  !file_exists( $logo_image) ) {
   $row = mysql_fetch_assoc (mysql_query("SELECT ifnull(logo_image,'{$default_logo}') as logo_image 
					  FROM tenants 
					  WHERE id = {$_SESSION['default_tenant_id']}") 
					);
   $logo_image = $row['logo_image'] ? $row['logo_image']:$default_logo;   
 }  

 
 $logo_image_file =  file_exists( $logo_image) ? $logo_image : 'Hosted-PBX-2017.png';


 $fp = fopen($logo_image_file, 'rb');

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($logo_image_file));
fpassthru($fp);
exit;


?>
