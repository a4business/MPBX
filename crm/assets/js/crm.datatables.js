

$.fn.dataTable.ext.errMode = 'none';

function loadDataTables(){
  

  $.fn.dataTable.ext.buttons.refresh = {
    text: '<i title="' +l('Обновить') +'" class="mdi  mdi-sync"> </i> ',
     action: function ( e, dt, node, config ) {      
      dt.ajax.reload().draw();
    }
   };
  $.fn.dataTable.ext.buttons.outgress = {
    text: '<i title="' +l('Исходящие') +'" class="fa fa-arrow-circle-o-left fa-2x text-info "></i>',
     action: function ( e, dt, node, config ) {
      dt.columns([0,2,3,4,5,6,7]).search('');
      dt.columns( 2 ).search('OUTBOUND').draw();      
    }
   };
   $.fn.dataTable.ext.buttons.missed = {
    text: '<i title="' + l('Неотвеченные ') + '" class="mdi mdi-phone-missed text-danger    "></i>',
     action: function ( e, dt, node, config ) {
      dt.columns([2,3,4,5,6,7]).search('');
      dt.columns( 7 ).search('ABANDONED').draw();
      
    }
   };
   $.fn.dataTable.ext.buttons.ingress = {
    text: '<i title="' +l('Входящие') +'"  class="fa fa-arrow-circle-o-right fa-2x text-primary"></i>',
     action: function ( e, dt, node, config ) {      
      dt.columns([0,2,3,4,5,6,7]).search('');
      dt.columns( 2 ).search('INBOUND').draw();      
     }
   };
   $.fn.dataTable.ext.buttons.queues = {
    text: '<i title="' +l('Очереди') +'" class="mdi mdi-account-group "></i> ',
     action: function ( e, dt, node, config ) {      
      dt.columns([0,2,3,4,5,6]).search('');
      dt.columns( 7 ).search(':A',true).draw();
     }
   };

  $.fn.dataTable.ext.buttons.all = {
     text: '<i title="' +l('Показать все звонки') +'" class="mdi ">'+l('Все')+'</i> ',
     action: function ( e, dt, node, config ) {
      dt.columns([0,2,3,4,5,6,7]).search('').draw();      
     }
   }; 

  $.fn.dataTable.ext.buttons.mine = {
     text: '<i title="' +l('Показать только мои звонки') +'" class="mdi ">'+l('Мои')+'</i> ',
     action: function ( e, dt, node, config ) {
       dt.columns([0,2,3,4,5,6]).search('');
       dt.columns(5).search(my_name,true);
       dt.columns(3).search(my_name,true);
       dt.search('',true).draw();
     }
   }; 
    


 

// Current Calls //
  var cc = $('.table-сс').DataTable({
		scrollY:        '100px',
        scrollCollapse: true,
                "ajax": {
                    url : "ccData",
                    type : 'GET',
                    data: { type : window.location.search }
                },
                dom: "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-2'>>",
                "language": {
                     emptyTable: l("Нет активных звонков"),
                     search:  l("Поиск")
                 },
                serverSide: true,
                processing: false,                
                columnDefs: [
                            { "targets"     : '_all',                        
                               "createdCell": function (td, cellData, rowData, row, col){
                                   $(td).css('padding', '3px');
                                   if( col == 1 )
                                       $(td).prop("title", rowData[0] );
                                   //$(td).on("click", 'span.hangup', hangupChan( $(this).attr('data') ) );
                                  },
                             },
                             { "targets": 0, "visible": false },
                             { "targets": 1, "width": "300px" },   

                 ]
     
            });
 
 

// Call history //
  var cdr = $('.table-cdr').DataTable({
                "ajax": {
                    url : "cdrData",
                    type : 'GET',
                    data: { type : window.location.search }
                },
                createdRow: function(row, data, dataIndex){                    
                    if(  data[2] && data[2].match(/OUTBOUND/) && data[4].length > 400){                                                                         
                         this.api().cell($('td:eq(2)', row)).data( data[3] + '-->' + data[4] );
                        this.api().cell($('td:eq(3)', row)).data('');                         
                       // $('td:eq(2)', row).attr('colspan', 2);
                       // $('td:eq(3)', row).css('display', 'none');                                                
                    }
                },                
                scrollCollapse: true,
                fixedHeader: true,
                fixedFooter: true,
                pageLength: 15,    
                AutoWidth: false, 
		         //   stateSave: true,                     
                //Blfrtip     
                buttons: [ {
                            extend: 'collection',
                            text: '<i title="' +l("Экспорт данных") +'" class=" mdi mdi-cloud-download-outline"></i> ',
                            buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ],                            
                            fade : true,
                            autoClose: true
                          },  
                          'refresh', 'ingress','outgress','missed','queues','mine','all'
                         ],
                dom: "<'row'<'col-sm-12'B<'cdr-player'><'cdr-dtime-range'>t>>" + "<'row'<r'col-xm-4'><'col-sm-4'i><'col-sm-6'p>>",                  
                "language": {
                     'emptyTable': l("Нет истории звонков"),
                     'search':  l("Поиск"),
                     'infoEmpty': l("Отображено") + " 0 to 0 of 0 " + l("записей"),
                     "infoFiltered": '_MAX_',
                     "lengthMenu": l("Показать") + ' _MENU_ ' +  l("записей"),
                     'info':     l("Отображено") + ' ' + l("c") +' _START_ ' +l("по") + ' _END_ of _TOTAL_ ' + l("записей"),
                     'loadingRecords': l("Загружается") + '...' ,
                     'paginate': {
                          "first":      l("Первая"),
                          "last":       l("Послед."),
                          "next":       ' > ',
                          "previous":   ' < '
                      }
                },
                serverSide: true,
                select: true,
                lengthMenu: [ [ 15, 50, 200, 1000, 10000, 50000, 100000  ], [15, 50, 200, 1000, 10000, 50000, l("Все")] ],
                processing: true,
                "order": [[ 1, "desc" ]],
                columnDefs: [{
                        "targets": '_all',                
                        "createdCell": function (td, cellData, rowData, row, col) {
                                    //$(td).css('padding', '3px');                            
                                    if( col == 1 )
                                       $(td).prop("title", rowData[0] );

                                  if(col == 3 ){
                                     $(td).attr("nowrap","nowrap");
                                        if( cellData.match(/ВХОД/))
                                         $(td).css('color', 'green').css('font-weight','bold');
                                       if( cellData.match(/ИСХ/))
                                         $(td).css('color', 'blue').css('font-weight','bold');
                                     } 

                                       
                                    if( col == 4 && cellData.match(/mdi-account-card-details/) ){
                                       $(td).addClass("bordered");                                        
                                     }
                                       
                                     if(col == 6 ){
                                        if( rowData[0].match(/no_service/))
                                         $(td).css('color', 'red').css('font-weight','bold');
                                       if( rowData[0].match(/on_service/))
                                         $(td).css('color', 'green').css('font-weight','bold');
                                     }  

                                }
                        },        
                        { "targets": 0, "visible": false },                        
                        { "targets": 1, "orderable": true,
                          "render": function (data) {                                                       
                             return moment(data).format("Y DD  HH:mm"); 
                           }
                        },{ "targets": 7, "visible": false }

                  ],
                  columns : [
                        { "width": 0 },
                        { "width": 80 },
                        { "width": 30 },
                        { "width": 700 }, // From 
                        { "width": 130 }, // to
                        { "width": 180 }, // connected with
                        { "width": 0 }, // disposition
                        { "width": 100 },
                        { "width": 100 },

                      ]
                
            });



 //  CONTROLS //
  $("div.cdr-dtime-range").html("<div id='reportrange' class='pull-right form-control' > "+ 
                                  "<i class='glyphicon glyphicon-calendar fa fa-calendar'></i> " +
                                  "<span></span> "+
                                "</div>");
  $("div.cdr-player").html("<audio preload='auto' controls > </audio>");

 // // Setup default Filters // //
  $('#reportrange span').html(moment().format('YYYY-MM-DD') + ' to ' + moment().format('YYYY-MM-DD')); 
  cdr.columns(1).search('"' + moment().format('YYYY-MM-DD 00:00') + '" AND "' + moment().format('YYYY-MM-DD 23:59')+'"',true).draw();
 // Show only mine calls 
 // cdr.columns(5).search( my_name, true);
 // cdr.columns(3).search( my_name, true).draw();


  var range_names = {
                    [l('Сегодня')]:          [moment().toDate(), moment().toDate()],
                    [l('Вчера')]:            [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    [l('Последние 7 дней')]: [moment().subtract(6, 'days'), moment()],
                    [l('Последние 30 дней')]:[moment().subtract(29, 'days'), moment()],
                    [l('Текущий месяц')]:    [moment().startOf('month'), moment().toDate()],
                    [l('Прошедший месяц')]:  [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    [l('С начала года')]:    [moment().startOf('year'), moment().toDate()],
                };

 // Date-Time  filter  //
  $('#reportrange').daterangepicker({
                format: 'YYYY-MM-DD',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2019',
                maxDate: '01/01/2023',
                dateLimit: {
                    days: 190
                },
                showDropdowns: true,                
                timePicker: true,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: range_names,
                opens: 'center',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-success',
                cancelClass: 'btn-secondary',
                separator: ' to ',
                locale: {
                    applyLabel: l('Применить'),
                    cancelLabel:l('Отменить'),
                    fromLabel: l('от'),
                    toLabel:   l('до'),
                    customRangeLabel: l('Другая..'),
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: [ l('Январь'), l('Февраль'), l('Март'), l('Апрель'), l('Май'), l('Июнь'), l('Июль'), l('Август'), l('Сентябрь'), l('Октябрь'), l('Ноябрь'), l('Декабрь')],
                    firstDay: 1
                }
            }, function (start, end, label) {
              //console.log(start.toISOString(), end.toISOString(), label );
               if( l('Другая..') == label ){
                  $('#reportrange span').html(start.format('YYYY-MM-DD HH:mm') + ' - ' + end.format('YYYY-MM-DD H:mm'));
                  cdr.columns( 1  ).search( '"' + start.format('YYYY-MM-DD HH:mm') + '" AND "' + end.format('YYYY-MM-DD HH:mm')+'"',true).draw();
               }else{
                  $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                  cdr.columns( 1  ).search('"' + start.format('YYYY-MM-DD 00:00') + '" AND "' + end.format('YYYY-MM-DD 23:59')+'"',true).draw();
               }               
               $('#reportrange span').css({'color':'#007bff','font-weight':'bold'});
     });

    
    
    
// Prevent form  reload on submition
$('#dt_topsearch').on( 'submit', function (e) {
    e.preventDefault();  
    return false;
 });

//Clear Search pressed //
 $("#dt_topsearch button i").on('click', function(e) {
   if( $('#dt_topsearch_text').val() ){         
         $("#dt_topsearch").trigger("reset");
         cdr.search('').draw();         
         $("#dt_topsearch button i").removeClass("fa-times");
         $("#dt_topsearch button i").addClass("fa-search");    
    }  
    
 });

$('#dt_topsearch_text').change(function() {      
  cdr.search( $(this).val() ).draw();
 });

$('#dt_topsearch_text').keyup(function() {      
  cdr.search( $(this).val() ).draw();
  if( $(this).val() ){  
    $("#dt_topsearch button i").removeClass("fa-search");
    $("#dt_topsearch button i").addClass("fa-times");        
    $(this).addClass("bg-search");
    
  }else{
    $("#dt_topsearch button i").removeClass("fa-times");
    $("#dt_topsearch button i").addClass("fa-search");        
  }  
 }); 

   
 $.get('groups #acc1','',function(data){ 
      $('#groups').html(data);
      $('.tooltip').tooltipster({
                     animation: 'fade',
                     delay: 100,
                     theme:[ 'tooltipster-punk', 'tooltipster-punk-customized']
        });
  });
 
 cc_count = 0;
 setInterval(function() {
     cc.ajax.reload();
     if(cc_count > cc.data().count() ){       
       cdr.ajax.reload();
     }

     cc_count = cc.data().count();
     $.get('groups #acc1','',function(data){
       $('#groups').html(data);
       $('.tooltip').tooltipster({
                     animation: 'fade',
                     delay: 100,
                     theme:[ 'tooltipster-punk', 'tooltipster-punk-customized']
        });
      });      
  }, 2000 );


}
