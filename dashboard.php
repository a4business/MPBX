<?php 
session_start();

 if ( !isset($_SESSION['UID']) ) {
    header("Location: entrance.php");
 }
 include_once('include/config.php');
 $my = mysql_query("SELECT allowed_sections FROM admin_users WHERE id = {$_SESSION['UID']} AND
                     ( allowed_sections = '[1]' OR 
                       allowed_sections like '[2,%' OR allowed_sections like '%,2,%' OR allowed_sections like '%,2]' )");
 if(!mysql_affected_rows()){
 	echo "<span style='color:red'>Permission Denied!</span>";
 	return;
 }
 
 include("jaxer.php");
 include("core/AMI.php");
 
 $AMI = new AMI($config);
 
 function getExtensionsTable(){
 	 global $AMI;
 	 foreach( $AMI->getExtensions() as $ext){

		  $bg = ($ext['dnd'] ) ? '#FFDAB9' :'';
		  $bg = (isset($ext['data'])) ? '#90EE90' : $bg;
                  
 	 	  echo '  <tr style="border-radius:6px;background-color:'.$bg.'">
 	 	         <td class="col-lg-10" style="padding:10px 0">             
                  <h3 class="text-muted p-0 m-0"  style="display:inline-block;margin:0;  text-overflow: ellipsis; overflow: hidden;"><img width="25px" src="images/extension.png" alt="EXT">'. 
                   trim($ext['extension']) .
                 '</h3>  
                    <span class="m-l-5 text-info" style="text-overflow: ellipsis; white-space:nowrap; overflow: hidden;vertical-align: top;">'.
                    ($ext['first_name']?$ext['first_name']:$ext['name']) . ' ' . $ext['last_name'] .
                    '</span>
               </td>
              <td >';

                $msg = $ext['call_blocking']?'<img src="/images/Inbound-blocked.png" width=20px alt="Inbound Blocked" title="Inbound Calls Blocked"</img>':'';
                $msg .= ($ext['outbound_route']==0)?'<img src="/images/Outbound-blocked.png" width=20px alt="Outbound Blocked" title="Outbound Calls Blocked"</img>':''; 
                $msg .= ($ext['outbound_route']==-1)?'<div style="color:green;">Only Internal</div>':'';
                if($ext['data']){
                  $ext['chan_reg_class'] = 'success';
                  $ext['chan_reg_status'] = '<b>ON-CALL</b>';
                  $ctype = $ext['data']['dnid']?'Outbound':'Inbound';
                  $num = $ext['data']['dnid'] ? $ext['data']['dnid'] : $ext['data']['exten'];
                  $msg .= '<div><img src="/images/'.$ctype.'-call.png" width=20px alt="'.$ctype.' Call" title="'.$ctype.' Call"</img>' . $num .'</div>';
                  $msg .= "<div> {$ext['data']['channelstatedesc']} " . (($ext['data']['channelstatedesc']=='Up')? gmdate('H:i:s',$ext['data']['seconds']):'') . " </div>";
                }   
  
             echo '   <span class="text-custom" style="white-space: nowrap">'.$msg.'</span>';
            // print_r($ext['data']);
                 // <p class="text-muted m-b-5 font-13" style="max-width: 80px; text-overflow: ellipsis; overflow: hidden;vertical-align: top;"><?php echo $ext['email'];</p> 
                 // </div>
             echo '</td>';
             $DND = ($ext['dnd'])? '<div class="dnd">DND</div>' : '';
             echo  " <td> <div><span class='label label-primary '>{$DND} {$ext['chan_reg_status']}</span> </div></td>";
             
             echo "</tr>";
     }
 }
 
 function getCallsTable(){
 	global $AMI;
 	$i=1; 
 	echo ' <table id="active-calls" class="table  table-striped">                                        
                                        <tbody> ';
														 foreach( $AMI->GetActiveCalls($_SESSION['tenantid']) as $key=>$call)
														   if(!preg_match('/^Local\//',trim($call['channel']))) {
														    echo  "<tr><td title='${key}'>".$i++."</td> ".
														               "<td> {$call['callerid']} </td>".
														               "<td> {$call['dialed_number']}</td>".
														               "<td> {$call['duration']} </td>".
														               "<td> {$call['status']}   </td>".
														           " </tr> \n"; 
														   }
   echo '</tbody></table>';       
 }  

 
 if($_GET['calls']) 
 	 getCallsTable();
 
 if($_GET['extensions'])
 	 getExtensionsTable();
 	
 if($_GET['calls'] || $_GET['extensions'] )
   return ;
             
 ?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Georgr" >

        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <title>PBX FOP / Dashboard </title>

        <!-- ION Slider -->
        <link href="assets/plugins/ion-rangeslider/ion.rangeSlider.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/ion-rangeslider/ion.rangeSlider.skinFlat.css" rel="stylesheet" type="text/css"/>

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
        

        <script src="assets/js/modernizr.min.js"></script>
<style type="text/css">
  .dnd{
    color:white;
    background-color:red;
    padding:5px;
    border-radius:4px;
    font-weight:bold;
   font-size:16px;
  }
  .row{
  	  maring:0;
  	  padding:8px;
  }
</style>
    </head>
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-20">
                        
                        <button class="btn btn-trans btn-rounded btn-info waves-effect waves-light"> <i class="fa fa-cloud m-r-5"></i>
                        <span><?php echo $_SESSION['tenantname'];?></span> 
                        </button>
                        <button onclick="location.refresh();" class="btn btn-trans btn-rounded btn-info waves-effect waves-light"> <i class="ti ti-reload  m-r-5"></i>  <span>Refresh</span> </button>
                        
                            <button type="button" class="btn btn-custom btn-trans btn-info btn-rounded dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Settings </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0);" class="dropdown-item">Auto-Refresh</a>
                                
                                <a href="javascript:void(0);" class="dropdown-item">Reset counters</a>
                                <a href="javascript:void(0);" class="dropdown-item">Reload PBX</a>
                            </div>
                        </div>
                        <h4 class="page-title">
                            <img style='width:50px;' src="images/Dashboard.png">&nbsp;Dashboard
                            <a style='margin-left:30px;' href='dashboard_summary.php'><img style='width:50px;' src="images/Dashboard_summary.png">&nbsp;Summary Reports </a>

                         </h4>
                    </div>
                    
                    
     <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <div class="table-rep-plugin">
                                <div class="table-responsive" data-pattern="priority-columns">
                                   <table id="active-calls" class="table  table-striped">
                                     <tr><td> Loading...</td></tr>
                                   </table>
                                </div>
                            </div>
                        </div>
                    </div>
     </div>
                <!-- end row -->
                                               
      
     <div class="row">
     
                <!-- Extensions -->
                    <div class="col-xl-4 col-md-6 p-10">
                     <div class="card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Refresh</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Hangup All</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Unregister All</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Broadcast message</a>
                                </div>
                            </div>
  
                        <h4 class="header-title m-t-0 m-b-10">Extensions</h4>
                        <div class="table-responsive" data-pattern="priority-columns">
                          <table id="extensions" class="table  table-striped">
                            <tr><td>Loading ...</td></tr>
                            </table>
                           </div> 
                        </div>  <!-- card box -->
                    </div>                <!-- end col -->
                    
              <!-- QUEUES -->      
                     <div class="col-xl-3 col-md-6 p-10">
                        <div class="card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Refresh</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Hangup All</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Unregister All</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Broadcast message</a>
                                </div>
                            </div>
                           

                        <h4 class="header-title m-t-0 m-b-10">Queues</h4>
                        <div class="table-responsive" data-pattern="priority-columns">
                          <table id="tech-companies-1" class="table  table-striped">
                        <?php 
                           foreach( $AMI->getQueues($_SESSION['tenantid']) as $queue ){ 
                           ?>
                            <tr style="padding:10px">
                             <td >
                                 <h4 style='display:inline-block;margin:0;max-width: 125px; text-overflow: ellipsis; overflow: hidden;' class="text-muted"><img width="30px" src="/images/Queues.png" alt="EXTEN"> <?php echo $queue['queue'];?></h4>  
                                 <span class="m-l-5 text-info" style="vertical-align: top;"> <?php echo $queue['strategy'];?></span>  

                             </td><td><table>
                             <?php  foreach($queue['members'] as $qmember){ ?>
                             <tr></tr>
                             <tr>
                                <td class='p-0 m-2'>
                                              <span class="text" style="white-space: nowrap;"><img width="13px" src="/images/extension.png" alt="EXT"> <?php echo $qmember['location'] ;?></span>
                                              <p class="text-muted m-b-5 font-13"><?php echo $qmember['status']?></p>
                                </td>
                                <td class='p-0 m-2'>
                                  <button class='btn btn-trans btn-xs waves-effect waves-primary  m-b-5'>X</button>
                                </td>
                              </tr>
                             <?php } ?>
                             </table> 
                            </tr>         
                         <?php  } ?>
                            </table>
                        </div>  <!-- card box -->
                    </div> <!-- end col -->
                
 </div>         <!-- end row -->


 <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <!-- range slider js -->
        <script src="assets/plugins/ion-rangeslider/ion.rangeSlider.min.js"></script>
        <script src="assets/pages/jquery.ui-sliders.js"></script>
            <!-- responsive-table-->
        <script src="assets/plugins/responsive-table/js/rwd-table.min.js" type="text/javascript"></script>


        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        <script type="text/javascript" >
           function loadData(){
           	if(parent.Sections)                
             if( parent.Sections.getVisibleSections() == 'section_dashboard' ){
                 console.log('Updating dashboard...');
          		 $('#active-calls').load('?calls=1');          	
             	 $('#extensions').load('?extensions=1');
             }	 

            }
            
           $(document).ready(function(){
            loadData();
           	setInterval(function(){
              loadData();              
             },6000);
           });
        </script>
       
    </body>
</html>
