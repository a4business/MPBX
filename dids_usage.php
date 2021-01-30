<?php 
session_start();

 if ( !isset($_SESSION['UID']) ) {
    header("Location:entrance.php");
 }
 include_once('include/config.php');
 $my = mysql_query("SELECT allowed_sections FROM admin_users 
                    WHERE id = {$_SESSION['UID']} AND
                          allowed_sections  like '%510%' OR 
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
        <meta name="description" content="A fully featured admin theme for PBX">
        <meta name="author" content="Georgr" >

        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <title>DIDs Usage Summary report</title>
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
        <link href="assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
        <link href="assets/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
        <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
            <!-- Table Responsive css -->
        <link href="assets/plugins/responsive-table/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">


        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

        <script src="assets/js/modernizr.min.js"></script>

          <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>

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


<style type="text/css">
    .search-form {
        width:340px;
        margin: 0 20px;
    }
#ms-my_multi_select3 {
    width:300px;
}
  .row{
  	  maring:0;
  	  padding:8px;
  }
  .table td{
      padding:0 10px;
  }
  .ms-list{
    width:135px;
  }
</style>
</head>
 <body>

<div class="row">

  <div class="col-lg-25" id='select_menu'>   
    <div class="card-box search-form">
 <h5><b> Inbound DID numbers</b></h5>

 <small>Select DID(s) for report:</small>


   <select name="country" class="multi-select" multiple="" id="my_multi_select3" > 

    <?php  
      $res = mysql_query("SELECT DISTINCT DID  FROM dids WHERE tenant_id  = {$_SESSION['tenantid']}");
       if($res){
        while($did = mysql_fetch_assoc($res)){
            echo "<option value='${did['DID']}'> ${did['DID']} </option>\n";
        }
       }
    ?>
                            
    </select>
    <div id="reportrange" class="pull-right form-control" style='margin: 10px 0; float:none;text-align: center; word-spacing: 10px;'>
     <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
      <span></span>
    </div>
  
    <button  type="button" report_type='in'  style='display:flex;margin:auto; width:60%;text-align:center;' class="btn btn-custom btn waves-effect waves-light w-md m-b-5 get_report" >DID Calls</button>

    <button  type="button" report_type='inbound_summary' style='display:flex;margin:auto;width:60%;text-align:center;' class="btn btn-primary btn-bordred waves-effect waves-light w-md m-b-5 get_report">DID calls Summary</button>

    
  </div>
</div><!-- end col -->
 <span class='glyphicon glyphicon-calendar fa fa-chevron-left' style='
    font-size: 23px;
    color: #1576c2;
    cursor: pointer;' 
    onclick="$('#select_menu').animate({'width': 'toggle'});" 
    ></span>
<div class="col-lg-7">
    <div class="card-box">        
         <span  style='float:right; visibility: hidden;' id='export_panel' >
                 <button  output_format='pdf' report_type='in'  type='button' style='padding: 0;margin:2px' class='btn btn-custom btn-bordred waves-effect waves-light w-md m-b-1 get_report '  >Export to PDF</button> 

                 <button  output_format='csv' report_type='in'  type='button' style='padding: 0;margin:2px' class='btn btn-custom btn-bordred waves-effect waves-light w-md m-b-1 get_report '  >Export to CSV</button>

         </span>         
        <div style='width:100%' id=summary_report>
          
        </div>
    </div>
</div>



      

<script>

            jQuery(document).ready(function() {
                            

                $('.get_report').on("click", function(){

                     function gen(){
                        $('#get_report').click();    
                     }

                    // $('#summary_report').load('jaxer.php?extensions='+$('#my_multi_select3').val()+'&drange='+ $('#reportrange span').html() , true); 
                     var r = $('#reportrange span').html();
                     var t = $(this).attr('report_type');                     
                     var f = $(this).attr('output_format') ? $(this).attr('output_format') : 'html';
                     var URL = 'jaxer.php?dids_usage='+$('#my_multi_select3').val() + '&output_format='+f+'&rtype='+t+'&drange=' + r.replace(/\s+/g,'');
                     

                    if( f != 'html' ){
                        download_file( URL, 'report.pdf');
                    }else{
                     $('#summary_report').html('Loading...  ' + f );
                     $('#summary_report').load( URL ,
                         function() {
                          // $(".table-resp").responsiveTable({   addDisplayAllBtn: "btn btn-secondary" }); 
                            console.log('Report Loaded!');
                          }  
                     ); 
                    } 
                    console.log(  'Selected:' + $('#my_multi_select3').val() );
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
