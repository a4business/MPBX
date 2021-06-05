<html>
<head>
    <meta name="viewport" content="width=device-width">
    
  
 
    

 <!-- App css -->    

    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    

<!-- Required datatable Bundle - with plugins and Jquery jQuery-1.12.4  
     <link rel="stylesheet" type="text/css" href="assets/plugins/datatables/datatables.css"/>   
     <script type="text/javascript" src="assets/plugins/datatables/datatables.js"></script>
   -->

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/se-2.2.13/jq-3.3.1/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-colvis-1.6.5/b-html5-1.6.5/b-print-1.6.5/fh-3.1.7/r-2.2.6/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/se-2.2.13/jq-3.3.1/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-colvis-1.6.5/b-html5-1.6.5/b-print-1.6.5/fh-3.1.7/r-2.2.6/datatables.min.js"></script>

     <script type="text/javascript" src="assets/plugins/moment/min/moment.min.js"></script>
     <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

    <script src="assets/js/audioplayer.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/audioplayer.css" >
<style type="text/css">
  .audio{
      max-height: 28px;  
   border-radius: 43px 4px;
   overflow: hidden;
   display: flex;
  justify-content: center;
   align-items: center; 
   
   /* filter: sepia(20%) saturate(70%) grayscale(1) contrast(99%) invert(12%);    */
    

  }

  .ui.input input{
    padding: 3px!important;
  }
  td{ white-space: nowrap; }
</style>

</head
<body style="margin:20px !important;padding:20px !important;">
<?php
  include("include/config.php");
  $operators = $config->getExtensions( $_SESSION['tenantid'] );
?>


 <div class='ui top attached  segment '> <div class=' title'><img src='images/rec.png' style='vertical-align: middle;width:25px'>&nbsp; Recordings:</div></div> 
 <div class='ui attached segment'>
    <table class="tbl ui celled table compact " >
        <thead><tr>
            <td>#</td>
            <td>
            <div class="ui calendar" id="example1">
  <div class="ui input left icon">
    <i class="calendar icon"></i>
    <input  class="ui search-param" type="date" type="text"  name="search[date]" placeholder="Date ">
  </div>
</div>
                
            </td>
            <td>Direction</td>
            <td> From               
                <div class="ui  left icon input">
                  <i class="search icon"></i>
                  <input type="text" class='search-param'  type="text" name="search[src]" placeholder="From...">            
             </div>
            </td>
            <td>
                To                
                <div class="ui  left icon input">
                <i class="search icon"></i>
                 <input type="text" class='search-param'  type="text" name="search[dst]" placeholder="To...">
               </div>
            </td>
            <td>Wait time</td>
            <td>Talk Time</td>
            <td>Status</td>
            <td>Source Channel</td>
            <td>Connected with </td>            
            <td>Recording</td>
        </tr></thead>
    </table>
  </div>

</div>


<script type="text/javascript">

$(document).ready(function() {
     $.fn.dataTable.ext.errMode = 'none';

 
    // Setup - add a text input to each footer cell
    $('.tbl tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    });        

   var tbl =  $('.tbl').DataTable({
        "ajax": {
            url : "ds_tables.php?tbl=recordings",            
            type : 'GET'            
        },
       bJQueryUI: true,
       bDeferRender: true,
       sPaginationType: "full_numbers",   
        scrollCollapse: true,
        fixedHeader: true,
        fixedFooter: true,
        pageLength: 20,
        lengthMenu: [ 15, 50, 200, 1000, 10000, 50000, 100000  ],
        AutoWidth: false,       
	"language": {
      	  'loadingRecords': '&nbsp;',
          'processing': '<div class="spinner"></div> <div class="roboFont"> Processing .</div>'
	},
        buttons: [ {
                extend: 'collection',
                text: '<i title= class=" mdi mdi-cloud-download">Export</i> ',
                buttons: [ 'copy', 'csv', 'excel', 'print' ],                            
                fade : true,
                autoClose: true
              } ,
              {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
              }

              //'refresh', 'ingress','outgress','missed','queues','mine','all'
        ],     
        serverSide: true,
        processing: true,
        dom: "<'ui grid'"+
    "<'row'B"+
    "<l>"+
    "<'bottom aligned center aligned eight wide column'<'titlebar'>>"+
    "<'right aligned four wide column'f>"+
    ">"+
    "<'row dt-table'"+
    "<'sixteen wide column'tr>"+
    ">"+
    "<'row'"+
  
    "<'five wide column'i>"+
    "<'right aligned eight wide column'p>"+
    ">>",        
        "order": [[ 1, "desc" ]],
	lengthMenu: [ 15, 50, 200, 1000, 10000, 50000, 100000  ], 
        columnDefs: [{
                        "targets": '_all', 
                        "sortable": true,
                        "createdCell": function (td, cellData, rowData, row, col) {
                                    //$(td).css('padding', '3px');                            
                                    if( col == 1 && rowData[0] )
                                       $(td).prop("title", rowData[0] );

                                  if(col == 2 && cellData ){
                                     $(td).attr("nowrap","nowrap");
                                        if( cellData.match(/INBOUND/))
                                         $(td).css('color', 'green').css('font-weight','bold');
                                       if( cellData.match(/OUTBOUND/))
                                         $(td).css('color', 'blue').css('font-weight','bold');
                                     } 
                                       
                                    if( col == 4 && cellData && cellData.match(/mdi-account-card-details/) ){
                                       $(td).addClass("bordered");                                        
                                     }
                                       
                                     if(col == 6 ){
                                        if( rowData[0] && rowData[0].match(/no_service/))
                                         $(td).css('color', 'red').css('font-weight','bold');
                                       if( rowData[0] && rowData[0].match(/on_service/))
                                         $(td).css('color', 'green').css('font-weight','bold');
                                     }  

                                }
                        },
                        { "targets": 0, "visible": false },                        
                        { "targets": 1, 
                          "render": function (data) {                                                       
                                 return moment(data).format("YYYY/MM/DD HH:mm"); 
                               }
                        },
                        { "targets": 9, "visible": true }   
                    ],
        columns: [
                    {data: "uniqueid" },
                    {data: "calldate", type: "date",sortable:false,width:100},
                    {data: "direction"},
                    {data: "src",sortable:false,width:100},
                    {data: "dst",sortable:false},
                    {data: "duration"},
                    {data: "billsec"},
                    {data: "disposition"},                    
                    {data: "channel"},
                    {data: "dstchannel"},
                    {data: "recording",width:200}
            ]

            ,
         initComplete: function () {
           //  $( 'audio' ).audioPlayer();           
           console.log('Loaded!');
        }

    });


    $('.search-param').on('change keyup clear', function() {        
          tbl.columns( 1 ).search( $('[name="search[date]"]').val() );
          tbl.columns( 3 ).search( $('[name="search[src]"]').val() );
          tbl.columns( 4 ).search( $('[name="search[dst]"]').val() );        
          tbl.draw();
    });



</script>


</body>
