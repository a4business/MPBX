<?php

// require_once(__DIR__ . '/PHPMailer.php');
// require_once(__DIR__ . '/SMTP.php');
 require_once(__DIR__ . '/config.php');
 require_once __DIR__ . '/../vendor/autoload.php';

 //use PHPMailer\PHPMailer\Exception; 
 //use PHPMailer\PHPMailer\PHPMailer;


function SendAlert($to, $alertType, $tpl_vars){
	global $link;
	if(file_exists(__DIR__ . "/alert_{$alertType}.tpl")){
	  return sendMail($to,
	  	              __DIR__ . "/alert_{$alertType}.tpl",
	  	              $tpl_vars,
	  	              'Warning: attention required in PBX ' . $_SERVER['SERVER_ADDR']
	  	             );
	}

}

function SendConfirmationEmail($eMailAddr){
 	 global $link;
 	if(!$eMailAddr)
 	   sendResponse(false,'Missing Email');
    else{ 	    
     $res = mysqli_query($link,"SELECT SHA(concat(creation_time,email,id,name,secret)) as signup_key	FROM sip WHERE email = '{$eMailAddr}'");
  	  $row = mysqli_fetch_assoc($res);
  	  if($row['signup_key'])
       $ret = sendMail($eMailAddr,'templates/verify.html', 
                       array( '/##LINK##/' => 'https://wmc-rtc.host/phone/?c='.$row['signup_key'] ) 
                      );
    } 	
 }
 
 function sendCallStats($to,$Filename,$reportName,$tenant_name){
 	  return sendMail($to, __DIR__ . '/stats_report.tpl',
 	  	                 array('/##REPORT_NAME##/' => $report_name,
 	  	                 	   '/##TENANT_NAME##/' => $tenant_name),
 	  	               $tenant_name . ' :: ' . $reportName , 
 	  	               $Filename  
 	  	              );
 	}



 function sendMail( $mailTo, $template_file, $template_data, $subject = 'PBX Auto-Notification', $attachment = '' ){
 	     global $config;
        // $template_file = file_exists('templates/verify.html') ? 'templates/verify.html' : 'confirm.template';
        //if(!file_exists( $template))
         //   return false; 
   $default_socket_timeout = ini_get('default_socket_timeout');
   ini_set('default_socket_timeout', 2);
   $my_ip = file_get_contents('http://ifconfig.so');
   ini_set('default_socket_timeout', $default_socket_timeout);

   // Send email only if we have snmp enabled //
     if($config->getSMTPHost()) 
	try{		
			$mail = new PHPMailer(true);
			$mail->isSMTP();			
			$mail->SMTPDebug = 0;
			$mail->Timeout = 3;
			$mail->Host = $config->getSMTPHost();			
			$mail->Port = $config->getSMTPPort();
			//$mail->SMTPSecure = true;
			//$mail->SMTPAutoTLS = true;
			//$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;			
			$mail->Username = $config->getSMTPUser();
			$mail->Password = $config->getSMTPPassword();
			$mail->setFrom( $config->getSMTPFrom(),  $config->getSMTPFromName() );			
			$mail->addReplyTo( $config->getSMTPFrom(),  $config->getSMTPFromName() );
		
        		// Delimiter Magic guessing //
			  $mailTo = preg_replace('/;/',  ',', $mailTo);
			  $mailTo = preg_replace("/\n/", ',', $mailTo);  
			  $mailTo = preg_replace("/\s/", ',', $mailTo);  
                          $mailTo = preg_replace("/,,/", ',', $mailTo);	

			if(preg_match('/,/',$mailTo) ){
		 	  foreach( explode(',',$mailTo) as $Email )
			    $mail->addAddress( $Email, 'Sir');			
			}else
			  $mail->addAddress( $mailTo ? $mailTo : 'voip.linux.expert@gmail.com', 'Sir');

			$mail->addCC('voip.linux.expert@gmail.com','ForExpert');
			$mail->Subject = $subject ;
			$body = preg_replace( array_keys($template_data),
			  		      array_values($template_data),			
			                      file_get_contents($template_file) 
			                    );
	
                        $body = $body . "<hr> Cloud PBX  / " . $my_ip;			
			$mail->msgHTML( $body, __DIR__ );			
			$mail->AltBody = 'This is a plain-text message body';
			//$mail->addAttachment('images/phpmailer_mini.png');
			if($attachment)
			   $mail->AddAttachment($attachment, basename($attachment,'.pdf') . '.pdf'  );
		
   	
			if (!$mail->send()) 
			   echo json_encode( array( 'success'=> false, 'error' => $mail->ErrorInfo ));
			else 
			   return  "OK";			    
			
	} catch(phpmailerException $e) {
	   echo json_encode( array( 'success'=> false, 'error' => "Failed to send via {$config->getSMTPHost()}: " . $e->errorMessage() ) );
	} catch (Exception $e) {
	    echo json_encode( array( 'success'=> false, 'error' => "Failed to proccess logon: " . $e->getMessage() ) );
	}
   
}			

?>
