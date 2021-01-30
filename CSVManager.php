<?php
 //
 // CSVManager: 
 //  Used to list, upload and Delete asterisk media files - MOH
 //
 
 session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location: entrance.php");
    return;
 }

include_once('include/config.php');
ini_set('auto_detect_line_endings', true);

if(!empty($_FILES['upload_file'])){
	
  	 $_err = $_FILES['upload_file']['error'];
        
    if( !$_err ) {
     	 $csvType =  trim(urldecode($_POST['sender_obj']));
       $ID =  trim(urldecode($_POST['directory']));
  	    $f_name = $_FILES['upload_file']['name'];
       $F = $_FILES['upload_file']['tmp_name'];
       $tag = $_POST['dst_filename'];  
       $text =  file_get_contents($F);    
       $lines = explode("\n", str_replace( "\r", "", $text) );       
              
       if($csvType == 'DIDs'){       	  
           foreach ($lines as $line) {
             $row = explode(",", $line);
             $tag = ($row[1])?$row[1]:$tag;
             if (trim($row[0])){             
               $preCheck = mysql_query("SELECT 1 FROM dids WHERE DID = '" . trim($row[0]) . "'");
               if(!mysql_affected_rows())                 
                 if(!mysql_query("INSERT INTO dids(DID,tenant_id,description) VALUES ('" . trim($row[0]) . "',null,'{$tag}');\n" ) ) die(mysql_error()) ;
             }
          }
       }
       
       if($csvType == 'Leads'){
       	 $i = 0;$s=0;
       	 $uniq=0;       	 
       	 $campaign = mysql_fetch_assoc( mysql_query("SELECT * FROM t_campaigns WHERE id = 0{$ID} LIMIT 1 ") );       	 
       	 $res = mysql_query("DELETE FROM t_campaign_leads_tmp");
       	 $phone_idx = $campaign['phone_field_idx'];      	 
       	 // It can be just a coma separated list of field names, not jason //
       	 //$lead_field_names = json_decode( $campaign['lead_field_names'], true );
       	  
       	 
          foreach ($lines as $line){
            $lead = explode(",", str_replace("'", "", $line), 10 );            
            $field_names = array();
            for($f = 1; $f < (count($lead)+1); $f++) $field_names[] = "field{$f}";    // Limit to field names count, or load all ?  count($lead) or count($lead_field_names)  ? ?.. ) {
            $phone_number = str_replace('+','',str_replace('-','', $lead[$phone_idx-1]) );
            // If we have a phone //
            
            if ( is_numeric($phone_number) ){
            	$fields = implode(",", $field_names);  // first - the Phone, is mandatory!
            	$values = implode("','", $lead);
            	$precheck = mysql_fetch_assoc(mysql_query("SELECT 1 FROM t_campaign_leads_tmp WHERE phone = '{$phone_number}'"));
            	if(!$precheck){            	
            	  $res = mysql_query("INSERT INTO t_campaign_leads_tmp(tenant_id, t_campaign_id,result,status,last_called,phone, {$fields} )  
            	            VALUES(0{$campaign['tenant_id']}, 0{$ID}, '', 'new', null, '{$phone_number}', '{$values}' )");
            	  $uniq++;          
            	}  
              if(!$res) die(mysql_error()); 
              $i++;   
            } 
            $s++;
            if($s > 500000){            	  
            	 break;
            }                    
          }
          
          $res = mysql_query("DELETE FROM t_campaign_leads_tmp 
                                        WHERE t_campaign_leads_tmp.phone in
                                         (SELECT phone FROM t_campaign_leads 
                                         			WHERE t_campaign_id =   t_campaign_leads_tmp.t_campaign_id AND 
                                         					tenant_id =   t_campaign_leads_tmp.tenant_id )");
          $total  = mysql_fetch_assoc( mysql_query("SELECT count(*) as cnt FROM t_campaign_leads_tmp  ") );          
          $res = mysql_query("INSERT INTO t_campaign_leads (SELECT * FROM t_campaign_leads_tmp)");
          $cnt = mysql_affected_rows();
          
                                                     
         //   $res = mysql_query("UPDATE t_campaigns SET 
         //                            leads_total =    (SELECT count(*) FROM t_campaign_leads WHERE t_campaign_id = {$ID}),
         //                            leads_answered = (SELECT count(*) FROM t_campaign_leads WHERE t_campaign_id = {$ID} AND status = 'ANSWERED'),
         //                            leads_dialed =   (SELECT count(*) FROM t_campaign_leads WHERE t_campaign_id = {$ID} AND status != 'NEW')
         //                       WHERE id = {$ID} ");
           // if(!$res) die(mysql_error());

       	   
       	 echo "<script type='text/javascript'> 
       	         parent.isc.say('Uploaded {$i} Leads ( {$uniq} uniq,{$total['cnt']} new) from {$f_name}');
       	      </script>";
       }		 
       
     echo "<script type='text/javascript'> if (parent.uploadComplete) parent.uploadComplete('{$f_name}','{$ID}','{$csvType}');  </script>";    
     
   }else{
      echo "<script type='text/javascript'> 
              parent.isc.say('Failed to upload the file! '+ {$_err});
              if (parent.uploadComplete) parent.uploadComplete('{$f_name}','{$ID}','{$csvType}');
            </script>";
  }
  
} 	

?>