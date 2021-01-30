<?php
session_start();

 if ( !isset($_SESSION['UID']) ) {
    header("Location: entrance.php");
    return;
 }
  include_once('include/config.php');
  
   $my = mysql_query("SELECT allowed_sections FROM admin_users WHERE id = {$_SESSION['UID']} AND
                     ( allowed_sections = '[1]' OR 
			allowed_sections like '[5,%' OR
			allowed_sections like '%,5,%' OR 
			allowed_sections like '%,5]' )"
                     ) or die(mysql_error() );

 if(!mysql_affected_rows()){
  echo "<span style='color:red'>Permission Denied!</span>";
  return;
 }

 include("jaxer.php");
 include("core/AMI.php");

 $AMI = new AMI($config);

 function getExtensions(){
   global $AMI;
   $EXTS = array();
    foreach( $AMI->getExtensions() as $ext){
     
     $ext['block'] = $ext['call_blocking']?'<img src="/images/Inbound-blocked.png" width=20px alt="Inbound Blocked" title="Inbound Calls Blocked"</img>':'';
     $ext['block'] .= ($ext['outbound_route']==0)?'<img src="/images/Outbound-blocked.png" width=20px alt="Outbound Blocked" title="Outbound Calls Blocked"</img>':'';
     $ext['block'] .= ($ext['outbound_route']==-1)?'<div style="color:green;">Only Internal</div>':'';
    $ext['block'] = $ext['block'] ? $ext['block'] : '<span style="color:silver">ready</span>';
     if($ext['data']){
                  $ext['chan_reg_class'] = 'success';
                  $ext['chan_reg_status'] = '<span class="oncall">ON-CALL</span>';
                  $ctype  = $ext['data']['dnid']?'Outbound':'Inbound';
                  $num = $ext['data']['dnid'] ? $ext['data']['dnid'] : $ext['data']['exten'];
		  $time = (($ext['data']['channelstatedesc']=='Up')? gmdate('H:i:s',$ext['data']['seconds']):'') ;
         $ext['call_info'] = "<div><img src='/images/{$ctype}-call.png' width=20px alt='{$ctype} Call' title='{$ctype} Call'</img>{$num}</div> <div> {$ext['data']['channelstatedesc']} {$time} </div>";
     }
     $DND = ($ext['dnd'])? '<div class="dnd" >&nbsp;DND&nbsp;</div>' : '';
     $regClass = ($ext['chan_reg_status']=='ONLINE') ? 'success' : 'warning';
     $ext['reg_info'] = " <div><span style='white-space:nowrap;' class='label label-{$regClass} '> {$ext['chan_reg_status']} {$DND}</span> </div>";
    $ext['name'] = $ext['first_name'] ?  $ext['first_name'] . ' ' . $ext['last_name'] : $ext['name'] ;
    $EXTS[] = $ext;
    }
  return $EXTS;

}

  $fields = array( 'extension' => '#Exten ',
		   'name' => 'Name',
		   'block' => 'Status',
                   'call_info' => 'Calls',
                   'reg_info' => 'Reg',
                 );
  $header='';
  
  foreach( array_values($fields) as $key)
      $header .= "<th> {$key} </th>";


// Generate table of users/exten sattus //
 if($_GET['extensions']){

   echo "<tr><th>#</th> {$header} </tr>\n";
   $extens = getExtensions();
   $i=1;
   foreach( $extens as $exten ){
     $row_class = $exten['dnd'] ? 'dndrow' : '';
     if(!trim($exten['name'])) continue;
     echo "<tr><td class={$row_class} style='color:gray;'>#" . ($i++) ."</td>";
    
     foreach( array_keys($fields) as $key)
       echo "<td  class='{$row_class}'> {$exten[$key]}  </td>";
     echo "</tr>\n"; 
     
   }

   return;
  }


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
  .oncall{
     color:white;
     font-weight:bold;
     background-color:green;
     padding:2px;
  }
  .dndrow{
    background-color: #FEF1F1;
  }
  .dnd{
    color:white;
    background-color:red;
    padding:5px;
    display:inline-table;
    max-width:50px;
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
                        <button onclick="$('#extensions').text('Loading...');loadData();" class="btn btn-trans btn-rounded btn-info waves-effect waves-light"> <i class="ti ti-reload  m-r-5"></i> <span>Refresh</span> </button>

                            <button type="button" class="btn btn-custom btn-trans btn-info btn-rounded dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Settings </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0);" class="dropdown-item">Auto-Refresh</a>
                                <a href="javascript:void(0);" class="dropdown-item">Reset counters</a>
                                <a href="javascript:void(0);" class="dropdown-item">Reload PBX</a>
                            </div>
                        </div>
                        <h4 class="page-title">
				User Status
                         </h4>
                    </div>


                <!-- end row -->


     <div class="row">

                <!-- Extensions -->
                    <div class="col-xl-4 col-md-10 p-10">
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
                        </div> <!-- card box -->
                    </div> <!-- end col -->


 </div> <!-- end row -->


 <!-- jQuery -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>


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
             if( parent.Sections.getVisibleSections() == 'section_extenstatus' ){
                 console.log('Updating Exten status...');
               $('#extensions').load('?extensions=1');
             }

            }

           $(document).ready(function(){
            loadData();
            setInterval(function(){
	      console.log('Loading...');
              loadData();
             },5000);
           });
        </script>

    </body>
</html>
