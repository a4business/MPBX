<?php 
session_start();

 if ( !isset($_SESSION['UID']) ) {
    header("Location:entrance.php");
 }
 include_once('include/config.php');
 $my = mysql_query("SELECT allowed_sections FROM admin_users 
                    WHERE id = {$_SESSION['UID']} AND
                          allowed_sections  like '%501%' OR 
                          allowed_sections  = '[1]' ");  // REDO!!

 if(!mysql_affected_rows()){
 	echo "<span style='color:red'>Permission Denied!</span>";
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

        <title>Dashboard -Summary</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Plugins css-->
        <link href="assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
        <link href="assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
        <link href="assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
        
        <link href="assets/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet"
        >
        
        <!-- Custom box css -->
        <link href="assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">

        <link href="assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
        <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
            <!-- Table Responsive css -->
        <link href="assets/plugins/responsive-table/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">


        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

        <script src="assets/js/modernizr.min.js"></script>

<style type="text/css">
    .search-form {
        width:300px;
        margin: 0 20px;

    }
.report-results{
    visibility: hidden;
}

#ms-my_multi_select3 {
    width:260px;
}
  .row{
  	  maring:0;
  	  padding:8px;
  }
  .table td{
      padding:0 10px;
  }
  .ms-list{
    width:115px;
  }

  .summary {
  border: 5px solid #ddd !important;
 }

 .repo_buttons{
   opacity:0.5;
 }

 .repo_buttons:hover{
    opacity:1 !important;
 }


.scheduler th, .repo_results th{
  border:1px solid silver !important;

}

.scheduler td{
  vertical-align: middle;
}

.scheduler {
      margin-bottom: 10px;
    border: 1px solid silver !important;
    padding: 5px 7px !important;
    margin: 0 !important;
   
}


</style>
</head>
 <body>
<div style='padding:0 20px'>
<?php 
 $res = mysql_query("SELECT *, DATE_FORMAT(tstamp,'%H:%i:%s') as tstamp,
          concat('<span class=text-muted >',TIME(last_sent),'<br><small>(', TIMESTAMPDIFF(HOUR, last_sent, now() ),'h ago)</small></span>') as last_sent  FROM t_scheduler WHERE tenant_id  = {$_SESSION['tenantid']}");
 $cnt = mysql_num_rows($res);
?>
  <div style='float:left;padding-left:12px;font-family:Courier;font-size:12px;' class='text-muted'>Server time: <?php echo date("Y-m-d H:i:s");?></div>
   &nbsp;&nbsp;
    <div class="fa  fa-arrow-circle-o-down text-info" style="
    font-size: 23px; float:right;
    color: #1576c2;
    cursor: pointer;" onclick="$('.scheduler').animate({'height': 'toggle'});$(this).toggleClass('fa-arrow-circle-o-down fa-arrow-circle-o-up');">
    <span class='badge badge-info' style='margin: 7px 2px;
    margin-bottom: 2px; vertical-align: middle;'><?php echo $cnt;?></span> <span>Daily Reports</span></div>
    <table class='table table-striped table-responsive p-2 b-0 scheduler' style='display:none;' id='scheduled_reports'>
        <tr style='background-color:silver;'>
            <th> Type </th>
            <th> Send at</th>
            <th nowrap> Last sent </th>
            <th> Email(s) </th>
            <th width="100%"> Report details</th>
            <th width=100px > Actions</th>
        </tr>
    <?php  
       while($row = mysql_fetch_assoc($res)){
        $params = preg_match("/extensions=(.*)&out/", $row['action_params'], $m);
        $type = preg_match("/rtype=(.*)&/", $row['action_params'], $t);
        echo "<tr><td class='text-info'>{$t[1]}</td><td nowrap id='ts{$row['id']}'>{$row['tstamp']}</td><td>{$row['last_sent']}</td><td id='email{$row['id']}' class='text-primary emails'>{$row['emails']}</td><td class='small'>{$m[1]}</td><td nowrap class='repo_buttons' >
          <button class='btn btn-xs btn-success btn-bordred waves-effect waves-light runReport' style='cursor:pointer;font-weight:bold;line-height:6px' reportID={$row['id']} >Run</button>          
            <a href='#custom-modal' data-animation='flash' data-plugin='custommodal'
                                     data-overlaySpeed='100' data-overlayColor='#36404a'>
               <button class='btn btn-xs btn-info btn-bordred waves-effect waves-light editReport' style='cursor:pointer;font-weight:bold;line-height:6px' reportID={$row['id']}  >edit</button>
            </a>                                     
           <button class='btn btn-xs btn-danger btn-bordred waves-effect waves-light delReport' style='cursor:pointer;font-weight:bold;line-height:6px;' reportID={$row['id']} >Delete</button> 
        </td></tr>\n";        
       } 
    ?>   
    </table>
</div>    

  <!-- Modals -->
            <div id="custom-modal" class="modal-demo ">
                <button type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="custom-modal-title">Report settings</h4>
                <div class="custom-modal-text" >
                        <div class="row">
                            <div class="col-12">
                               
                                    <form class="form-horizontal" role="form" style="text-align: left !important;">
                                        <input type=hidden id=repoID value=0>
                                        <div class="form-group row">
                                            <label class="col-4 col-form-label">Time to RUN:</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control" id='toRun' value="">
                                            </div>
                                        </div>                                        
                                        <div class="form-group row">
                                            <label class="col-4 col-form-label">Emails to send:</label>
                                            <div class="col-7">
                                                <textarea  class="form-control" id='toEmails'>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">                          
                                            <div class="col-12 text-right">                                            
                                                <button class='btn ' onclick="Custombox.close();return false;">Cancel</button>
                                                <button class='btn btn-primary ' onclick="saveReport();Custombox.close();return false;">Save</button>
                                            </div>                                            
                                        </div>
                                    </form>
                                
                            </div>
                        </div>
                        <!-- end row -->                    
                </div>
            </div>


<div class="row">
  <div class="col-lg-25" id='select_menu'>
    <div class="card-box search-form">
     <h5><b>Exten Inbound/Outbound calls:</b></h5>
     <small>Select extension(s) for report:</small>
   <select name="country" class="multi-select" multiple="" id="my_multi_select3" > 

    <?php  
      $res = mysql_query("SELECT extension,name from t_sip_users WHERE tenant_id  = {$_SESSION['tenantid']}");
       if($res){
        while($ext = mysql_fetch_assoc($res)){
            echo "<option value='${ext['name']}'> ${ext['extension']} </option>\n";
        }
       }
    ?>
                            
    </select>
    <div id="reportrange" class="pull-right form-control" style='margin: 10px 0; float:none;text-align: center; word-spacing: 10px;'>
     <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
      <span></span>
    </div>
  
    <button  type="button" report_type='in'  style='display:flex;margin:auto; width:60%;text-align:center;' class="btn btn-custom btn waves-effect waves-light w-md m-b-5 get_report" >Inbound Calls</button>

    <button  type="button" report_type='inbound_summary' style='display:flex;margin:auto;width:60%;text-align:center;' class="btn btn-primary btn-bordred waves-effect waves-light w-md m-b-5 get_report">Inbound Call Summary</button>

    <button  type="button" report_type='out'  style='display:flex;margin:auto; width:60%;text-align:center;' class="btn btn-custom btn-bordred waves-effect waves-light w-md m-b-5 get_report">Outbound Calls</button>

    <button  type="button" report_type='queue_activity'  style='display:flex;margin:auto;width:60%;text-align:center;' class="btn btn-custom btn-bordred waves-effect waves-light w-md m-b-5 get_report">Queues Activity</button>

    <button  type="button" report_type='hr_activity'  style='display:flex;margin:auto;width:60%;text-align:center;' class="btn btn-custom btn-bordred waves-effect waves-light w-md m-b-5 get_report">HR Activity</button>
  </div>
</div><!-- end col -->
 <span class='fa  fa-arrow-circle-o-left' style='
    font-size: 40px;
    color: #a7dfff;
    padding-right:18px;
    cursor: pointer;' 
    onclick="$('#select_menu').animate({'width': 'toggle'}); $(this).toggleClass('fa-arrow-circle-o-left fa-arrow-circle-o-right');"
    ></span>
<div class="">
    <div class="card-box report-results" style="min-width: 450px">        
         <span  style='float:right; visibility: hidden;display:inline-grid;' id='export_panel' >                 
                 <button  output_format='to_email' report_type='' type='button' style='padding: 0 11px;margin:2px' class='btn btn-custom btn-bordred waves-effect waves-light  m-b-1 get_report '  >Add to Daily Reports</button> 
                 <button  output_format='pdf' report_type='in'  type='button' style='padding: 0;margin:2px' class='btn btn-custom btn-bordred waves-effect waves-light w-md m-b-1 get_report '  >Export to PDF</button> 

                 <button  output_format='csv' report_type='in'  type='button' style='padding: 0;margin:2px' class='btn btn-custom btn-bordred waves-effect waves-light w-md m-b-1 get_report '  >Export to CSV</button>

         </span>         
        <div style='width:100%' id=summary_report>
          
        </div>
    </div>
</div>



        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>

        <!-- Modal-Effect -->
        <script src="assets/plugins/custombox/dist/custombox.min.js"></script>
        <script src="assets/plugins/custombox/dist/legacy.min.js"></script>

        <!-- Plugins Js -->
        <script src="assets/plugins/switchery/switchery.min.js"></script>
        <script src="assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
        <script type="text/javascript" src="assets/plugins/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script src="assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script src="assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="assets/plugins/moment/moment.js"></script>
        <script src="assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
  <!-- responsive-table-->
        <script src="assets/plugins/responsive-table/js/rwd-table.min.js" type="text/javascript"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>


<script>
    function saveReport(){
                     today = new Date().toISOString().slice(0,10);
                     at = today + ' ' + $('#toRun').val();
                     to = $('#toEmails').val();
                     id = $('#repoID').val();
                     data = '{"emails": "'+ to + '","tstamp":"' + at + '","id": "'+id+'"}';
                     $.post( 'ds.php?set=t_scheduler', { set_data: data },
                             function(result){ 
                               console.log(result); 
                               $('#ts' + id ).text( $('#toRun').val() );
                               $('#email' + id ).text( to );
                             }
                     );
                     
                 }  

            jQuery(document).ready(function() {

                 $('.delReport').on("click", function(){
                     $.get('jaxer.php?delScheduler='+ $(this).attr('reportID') );
		                 $(this).parent().parent().fadeOut();
                 });
                 
                 $('.runReport').on("click", function(){
    		             $(this).html('<i class="fa-li fa fa-spinner fa-spin"></i>');
                     $.get('jaxer.php?runScheduler='+ $(this).attr('reportID'), function(){
                         setTimeout(function(){$('.runReport').text('Run');}, 2000);                         
                     });
                 });

                 $('.editReport').on("click", function(){
                     //alert('Load form with ' + $(this).attr('reportID') );
                     id = $(this).attr('reportID') ;
                     $('#repoID').val( id );
                     $('#toRun').val( $('#ts' + id ).text() );
                     $('#toEmails').val( $('#email' + id ).text() );
                 });


                $('.get_report').on("click", function(){
                     
                     function gen(){
                        $('#get_report').click();    
                     }
                    //$('#summary_report').load('jaxer.php?extensions='+$('#my_multi_select3').val()+'&drange='+ $('#reportrange span').html() , true); 
                     var r = $('#reportrange span').html();
                     var t = $(this).attr('report_type');                     
                     var f = $(this).attr('output_format') ? $(this).attr('output_format'):'html';
                     var ext = $('#my_multi_select3').val();
                     var URL = 'jaxer.php?extensions='+ ext + '&output_format='+f+'&rtype='+t+'&drange=' + r.replace(/\s+/g,'');
                     
                    if( f == 'to_email'){
                      var emails = prompt("Email(s) to send this daily report?");                     
                      URL = 'jaxer.php?extensions='+ ext + '&rtype='+t+'&output_format='+f;

                      console.log('Adding SCHEDULE FOR url: ' + URL + 'to database');
                      $.post('ds.php?add=t_scheduler', { set_data: '{"emails": "'+emails+ '","action":"reports","action_params": "'+URL+'"}' },
                            function(result){ console.log(result);  } );
                    }else if( f == 'pdf' ){
                        download_file( URL, 'report.pdf');
                    }else{ // html
                      $('.report-results').css('visibility', 'visible');
                      $('#summary_report').html('Loading...  ' + f );
                      $('#summary_report').load( URL , function(){ console.log('Report Loaded!');  }  
                     ); 
                    } 

                    console.log(  'Selected:' + ext );
                    console.log(  'Range:' + $('#reportrange span').html() );
                 
                });

                //advance multiselect start
                $('#my_multi_select3').multiSelect({
                    selectableHeader: "<input type='text' class='form-control search-input' style='width:100%' autocomplete='off' placeholder='search...'>",
                    selectionHeader: "<input type='text' class='form-control search-input' style='width:100%' autocomplete='off' placeholder='search...'>",
                    afterInit: function (ms) {
                        var that = this,
                                $selectableSearch = that.$selectableUl.prev(),
                                $selectionSearch = that.$selectionUl.prev(),
                                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                                .on('keydown', function (e) {
                                    if (e.which === 40) {
                                        that.$selectableUl.focus();
                                        return false;
                                    }
                                });

                        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                                .on('keydown', function (e) {
                                    if (e.which == 40) {
                                        that.$selectionUl.focus();
                                        return false;
                                    }
                                });
                    },
                    afterSelect: function () {
                        this.qs1.cache();
                        this.qs2.cache();
                    },
                    afterDeselect: function () {
                        this.qs1.cache();
                        this.qs2.cache();
                    }
                });

                $('#my_multi_select3').width=300;

                // Select2
                $(".select2").select2({
                    placeholder: 'This is my placeholder',
                    allowClear: true
                });

                $(".select2-limiting").select2({
                    maximumSelectionLength: 30
                });


                $('#reportrange span').html(moment().format('YYYY-MM-DD') + ' to ' + moment().format('YYYY-MM-DD'));

            $('#reportrange').daterangepicker({
                format: 'YYYY-MM-DD',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2019',
                maxDate: '01/01/2023',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-success',
                cancelClass: 'btn-secondary',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('YYYY-MM-DD') + '  to  ' + end.format('YYYY-MM-DD'));
            });



            });

function download_file(fileURL, fileName) {
    // for non-IE
    if (!window.ActiveXObject) {
        var save = document.createElement('a');
        save.href = fileURL;
        save.target = '_blank';
        var filename = fileURL.substring(fileURL.lastIndexOf('/')+1);
        save.download = fileName || filename;
           if ( navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/) && navigator.userAgent.search("Chrome") < 0) {
                document.location = save.href; 
// window event not working here
            }else{
                var evt = new MouseEvent('click', {
                    'view': window,
                    'bubbles': true,
                    'cancelable': false
                });
                save.dispatchEvent(evt);
                (window.URL || window.webkitURL).revokeObjectURL(save.href);
            }   
    }

    // for IE < 11
    else if ( !! window.ActiveXObject && document.execCommand)     {
        var _window = window.open(fileURL, '_blank');
        _window.document.close();
        _window.document.execCommand('SaveAs', true, fileName || fileURL)
        _window.close();
    }
}


  </script>
     
    </body>
</html>
