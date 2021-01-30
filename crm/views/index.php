<?php
 include_once('include/check-login.php');
?><!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Cloud PBX system | Operator WEB Panel with Voice Communication ">
        <meta name="author" content="Coderthemes">
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <title><?= _l("Customer Care | Callcenter ");?></title>
        <!--Morris Chart CSS -->
		    <link rel="stylesheet" href="assets/plugins/morris/morris.css">
        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />      

        <!-- DataTables -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/datatables/datatables.min.css"/>
<!--
        <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />        
        <link href="assets/plugins/datatables/fixedHeader.dataTables.min.css" rel="stylesheet" type="text/css" />            
        <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />        
        <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />        
        <link href="assets/plugins/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />        
        <link href="assets/plugins/datatables/select.dataTables.min.css" rel="stylesheet" type="text/css" />
-->


        <!-- Custom box css -->
        <link href="assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">
        <!-- JQuery Accordion -->
        <link href="assets/css/accordionhorz.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="assets/css/tooltipster.bundle.min.css"></script>
        <link rel="stylesheet" type="text/css" href="assets/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-punk.min.css"></script>        
        
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />        
        <link href="assets/css/crm-styles.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/crm-dark-styles.css" rel="stylesheet" type="text/css" />        

      <script type="text/javascript">
           $('.tooltip').tooltipster({ 
             contentCloning: true
           });
      </script>  

</head>
<body class="fixed-left"  onload="updateTimer()" >
        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">
   
                <!-- LOGO -->
                <div class="topbar-left topbar-shorter "    >
	           	   <a href="" class="logo"><span><?php echo $PBX->ini['general']['company'] ;?></span>
                 <i class="mdi mdi-layers"></i></a>
                  <i class="mdi mdi-dialpad fa-2x float-left show-phone-panel" style='cursor:pointer;cursor: pointer; top: 42px; position: absolute; left: 4px;display:none;' title='Show WEB Phone' id='show-phone'></i>
                 
                </div><!-- Button mobile view to collapse sidebar menu -->
                <nav class="navbar navbar-default ">
                    <div class="container-fluid" >
                        
                        <!-- Page title -->
                        <ul class="nav navbar-nav list-inline navbar-left" style="margin:0; ">
                            <li class="list-inline-item">
                                <button class="button-menu-mobile open-left">
                                    <i class="mdi mdi-menu"></i>
                                </button>
                            </li>                            
                        </ul>
 
                          
                       <ul class="list-unstyled float-left" id='groups' style="margin:0; white-space: nowrap;">    
                         <div class="alert alert-primary " style='float: left; width:100%;' role="alert"> Loading ... </div>                            
                        </ul>
                       
                        <nav class="navbar-custom float-right">                            
                            <ul class="list-unstyled topbar-right-menu topbar-adjust mb-0">
                                <li> 

                                  <div class="Clock-Wrapper D7MI">
                                    <span class="Clock-Time-Background ">88:88 </span></span>
                                    <span id="DSEGClock" class="Clock-Time-Front "></span>
                                    <span class="Clock-Year-Background"><span ">2088-88-88</span><span class="D14MI"> ~~~</span></span>
                                    <span id="DSEGClock-Year" class="Clock-Year-Front"></span>
                                  </div>

                                </li>
                                <li>                                    
                                    <!-- Notification -->
                                    <div class="notification-box">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a href="javascript:void(0);" class="right-bar-toggle">
                                                    <i class="mdi mdi-bell-outline noti-icon"></i>
                                                </a>
                                                <div class="noti-dot">
                                                    <span class="dot"></span>
                                                    <span class="pulse"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- End Notification bar -->
                                </li>
                                
                                <li class="hide-phone">
                                    <form class="app-search " id='dt_topsearch'>
                                        <input type="text" id='dt_topsearch_text' placeholder="<?=_l('Поиск...');?>"
                                               class="form-control m-l-0 app-search-adjust" >
                                        <button style='padding-top:2px;' >
                                           <i  class="fa fa-search" ></i></button>
                                    </form>

                                </li>
                                <li>
                                    <ul class="list-inline user-list m-l-10 m-t-20">
                                      <!-- 
                                        <li class="list-inline-item">                                
                                              <a href="#" class="user-list-item" >
                                                <div class="icon bg-info">           
                                                   <i class="mdi mdi-settings"></i>
                                                </div> 
                                              </a>                                 
                                        </li>
                                      -->

                                        <li class="list-inline-item">
                                            <a href="#" class="user-list-item" onclick="logout();">
                                                <div class="icon bg-danger">           
                                                  <i class="mdi mdi-power"></i>
                                                </div> 
                                            </a>
                                        </li>
                                    </ul>
                                </li> 

                                <!--
                                <li>
                                    <div>
                                      <ul class="language-select" style="margin-top: 10px;"> 
                                          <li class="<?php echo ( $_COOKIE['language'] == 'ru' ) ? 'active':'';?>" data-lang="ru">рус</li> 
                                          <li class="<?php echo ( $_COOKIE['language'] == 'ua' ) ? 'active':'';?>" data-lang="ua">укр</li>
                                          <li class="<?php echo ( $_COOKIE['language'] == 'en' ) ? 'active':'';?>" data-lang="en">eng</li>
                                      </ul>
                                     </div> 
                                </li>
                                 -->

                            </ul>
                        </nav>
                        
                    </div><!-- end container -->
                </nav><!-- end navbar -->
            </div>
            <!-- Top Bar End -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu" style="padding-top: 25px;overflow: hidden;">  
              <i class="ti   ti-shift-left-alt fa-2x float-right" style='cursor:pointer;cursor: pointer; top: 23px; position: relative; left: 5px;' title='Hide WEB Phone' id='hide-phone'></i>
                <div class="sidebar-inner slimScrollDiv">

                    <!-- User -->
                    <div class="user-box " >
                        <div class="user-img" style="float: left">
                           <img src="assets/images/users/no-avatar.jpg" alt="user-img" title="<?= $_SESSION['CRM_user']['full_name'];?>" class="rounded-circle img-thumbnail img-responsive ">
                           <div  id='user-status'  class="user-status offline"><i class="mdi mdi-adjust"></i></div>                           
                        </div>
                        <div class='wid-u-info text-left roboto'>
                              <h5 class='m-0 p-0 crm-user'><a href="#"><?=$_SESSION['CRM_user']['user'];?></a></h5>
                              <div class="small text-muted m-b-0 "><?=$_SESSION['CRM_user']['role_name'];?></div>
                              <a href="#custom-modal" data-animation="flash" data-plugin="custommodal"
                                     data-overlaySpeed="100" data-overlayColor="#36404a">
                                <span class="text-info roboto" > 
                                  <i class="fa fa-cog  fa-spin "></i>&nbsp;<?=$_SESSION['CRM_user']['sip']['name'];?> </span>
                              </a>                                      
                              <span class="small m-b-0 "><?=$_SESSION['CRM_user']['email'];?></span>  
                              <!-- <span class="text-muted"> 23232</span> -->

                        </div>
                    </div>
                    
                   <!-- End User -->

                <!--- Sidemenu -->
                <div id="sidebar-menu" class="text-center" >
                    
                </div>    
                <!-- End User -->

                <div class="panel panel-primary">
                    <audio id="remoteAudio"></audio>
                    <audio id="localAudio" muted="muted"></audio>                    
                    <audio id="dtmf_audio" preload="auto">
                        <source src="sounds/dtmf.wav" type="audio/mpeg" >
                    </audio>
                    <audio id="incoming_audio" loop >
                        <source src="sounds/ringback.mp3" type="audio/mpeg" >
                    </audio>
                    <audio id="outbound_audio" loop >
                        <source src="sounds/ringbacktone.mp3" type="audio/mpeg" >
                    </audio>

                        <input id='sipName' type=hidden value="<?php echo($_SESSION['CRM_user']['sip']['name']);?>" >
                        <input id='sippw'   type=hidden value="<?php echo($_SESSION['CRM_user']['sip']['secret']);?>" >
                     
                    <div class="panel-body">                        
                        <table  class="table">
                              <tr><td>
                                <div  class="alert alert-default text-success text-center m-0 p-0" id="status"><i></i></div>
                                   <div id=exten ></div>
                                   <div class='alert alert-danger mb-0 p-2 ' id="uastatus" >
                                         <i class='mdi mdi-lan-disconnect'>&nbsp;<?=_l('Отключен');?></i>
                                         <a style="float:right; display:inline" href='#' onclick='regclicked();return false;' >

                                          <i style='float:right' id='registerbutton' class='mdi mdi-power'></i>                                          
                                         </a>
                                         <i id='echo' class='mdi mdi-surround-sound'></i>
                                   </div>                    
                                  <?php echo isset($_SESSION['CRM_user']['warning']) ? "<span class='text text-warning small'>{$_SESSION['CRM_user']['warning']}</span>" : '';?>
                               </td></tr>
                              <tr><td>
                                <input id="phonenumbertxt" class="form-control" placeholder="<?=_l('Набрать номер');?>" type="text"></input>
                              </td></tr>
                        </table>
                           
                        <table  class="table  dial-pad">
                            <tr>                 
                             <td colspan=3 style="white-space: nowrap;">
                                <button id="callbutton" onclick="call()" class="btn btn-block btn-success " ><i class=" mdi mdi-phone"></i> </button>
                            
                                 <button id="holdbutton" onclick="hold()" class="btn btn-block  btn-warning invisible"><i class=" mdi mdi-pause"></i></button>                    
                            
                                 <button id="hangupbutton" onclick="hangup()" class="btn btn-block btn-danger" ><i class="mdi mdi-phone-hangup"></i></button>
                                    
                              </td>
                            </tr>                
                            <tr>                    
                                <td>                        
                                        <button onclick="adddigit(this)" class="btn btn-block btn-default key" value="1">1</button>
                                        </td><td>
                                        <button onclick="adddigit(this)" class="btn btn-block  btn-default key" value="2">2</button>
                                        </td><td>
                                        <button onclick="adddigit(this)"  class="btn btn-block  btn-default key" value="3">3</button>                        
                                </td>
                            </tr>
                            <tr>                    
                                <td>                       
                                        <button onclick="adddigit(this)" class="btn btn-block btn-default key" value="4">4</button>
                                        </td><td>
                                        <button onclick="adddigit(this)" class="btn btn-block  btn-default key" value="5">5</button>
                                        </td><td>
                                        <button onclick="adddigit(this)" class="btn btn-block  btn-default key" value="6">6</button>                        
                                </td>
                            </tr>
                            <tr>                                
                                <td>                        
                                        <button onclick="adddigit(this)" class="btn btn-block btn-default key" value="7">7</button>
                                        </td><td>
                                        <button onclick="adddigit(this)" class="btn btn-block  btn-default key" value="8">8</button>
                                        </td><td>
                                        <button onclick="adddigit(this)" class="btn btn-block  btn-default key" value="9">9</button>                        
                                </td>
                            </tr>
                            <tr>                                
                                <td>                        
                                        <button onclick="adddigit(this)" class="btn btn-block btn-default key" value="*">*</button>
                                        </td><td>
                                        <button onclick="adddigit(this)" class="btn btn-block  btn-default key" value="0">0</button>
                                        </td><td>
                                        <button onclick="adddigit(this)" class="btn btn-block  btn-default key" value="#">#</button>                        
                                </td>
                            </tr>
                            <tr>
                                <td colspan=3 width="100%" class='extrabtn'>


                                  <div class="btn-group call-controls" style='width:100%'>
                                    <button type="button" id="mutebutton" onclick="mute()" class="btn btn-primary invisible"  title='Mute sound'><i class="fa fa-microphone"></i></button>
                                    <button type="button" id="xferbtn"    onclick="transfer()" class="btn btn-primary invisible" title='Безусловная переадресация'><i class=" fa fa-send-o"></i></button>
                                    <button type="button" id="attxferbtn" onclick="attXfer()" class="btn btn-primary invisible" title='Условная переадресация'><i class=" fa fa-send"></i></button>
                                    <button type="button" id="confbtn" onclick="conf()" class="btn btn-primary invisible" title='Создать конференцию'><i class=" fa fa-users"></i></button>
                                  </div>
                            
                            </tr>
                        </table>

                    </div>
                </div>    
                    
                <!-- Sidebar -->
                <div class="clearfix"></div>
            </div>

            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            
             <div class="content-page">
                <!-- Start content -->                 
                    <div id='content-area' class=" container-fluid" >                        

                     
               

                    </div> <!-- container -->
             </div> <!-- content -->

             <footer class="footer text-right">
                    <?php echo date('Y'); echo " ©  " . $PBX->ini['general']['company']?> 
             </footer>

            <!-- End content -->

            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar  SLIDER!! NOTIFICATIONS-->
            <div class="side-bar right-bar">
                <a href="javascript:$('#wrapper').toggleClass('right-bar-enabled');" class="right-bar-toggle">
                    <i class="mdi mdi-close-circle-outline"></i>
                </a>
                <h4 class=""><?php echo _l('Уведомления');?></h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list">

                        <li class="list-group-item">                           
                          <button style="float:right;line-height:8px" class='btn btn-sm btn-outline-success ack'><i class="mdi mdi-check"></i></button>
                            <a href="#" class="user-list-item">
                                <div class="icon bg-danger">
                                   <i class="mdi mdi-phone-missed"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">+380506643222</span>
                                    <span class="desc"><?=_l('Пропущенный зконок');?></span>
                                    <span class="time">2 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">                              
                             <button style="float:right;line-height:8px" class='btn btn-sm btn-outline-success ack'><i class="mdi mdi-check"></i></button>
                             <a href="#" class="user-list-item">
                                <div class="icon bg-danger">
                                    <i class="mdi  mdi-file-excel-box"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name"><?=_l('Оповещение');?></span>
                                    <span class="desc"><?=_l('Пропал UP-линк !');?></span>
                                    <span class="time">5 hours ago</span>
                                </div>
                            </a>
                        </li>
                        

                    </ul>
                     <ul class="list-group list-no-border user-list">
                       <li class="list-group-item"> 


                       </li>
                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->




        <!-- Modals -->
            <div id="custom-modal" class="modal-demo ">
                <button type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="custom-modal-title"><?=_l('Настройки телефона');?></h4>
                <div class="custom-modal-text">                                    
                        <div class="row">
                            <div class="col-12">
                                <div class="p-20">
                                    <form class="form-horizontal" role="form" style="text-align: left !important;">
                                        <div class="form-group row" >
                                            <label class="col-4 col-form-label"><?=_l('Локальный номер');?></label>
                                            <div class="col-7">
                                                <h4 ><?php echo($_SESSION['CRM_user']['sip']['extension']);?> </h4>
                                            </div>
                                        </div>                                        
                                        <div class="form-group row">
                                            <label class="col-4 col-form-label"><?=_l('SIP Логин');?></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control" value="<?php echo($_SESSION['CRM_user']['sip']['name']);?>">
                                            </div>
                                        </div>                                        
                                        <div class="form-group row">
                                            <label class="col-4 col-form-label"><?=_l('SIP пароль');?></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control" value="<?php echo($_SESSION['CRM_user']['sip']['secret']);?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-4 col-form-label"><?=_l('SIP Сервер');?></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control" value="<?php echo ( $PBX ? $PBX->ini['general']['domain'] : 'localhost' );?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-4 col-form-label" for="example-email">Email</label>
                                            <div class="col-7">
                                                <input type="email" id="example-email" name="example-email" class="form-control" placeholder="Email" value="<?php echo($_SESSION['CRM_user']['email']);?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">                                        
                                           <label class="col-4 col-form-label" for="example-email"></label>
                                            <div class="col-7">                                            
                                              <div class="custom-control custom-switch" style="text-align: left !important;">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1" style="display:none">
                                                <label class="custom-control-label" for="customSwitch1"><?=_l('Не беспокоить');?></label>
                                            </div>
                                              </div>
                                        </div>
                                        <div class="form-group row">                                        
                                        <div class="text-muted">
                                          <a href='http://translate.google.com/translate?sl=en&tl=ru&u=https://www.zoiper.com/en/support/home/article/97/click%20to%20dial%20%2F%20callto%3A%20sip%3A%20handlers%20are%20not%20working#windows' target="about:blank">tel://  Widows </a>
                                        </div>
                                      </div>

                                        <div class="form-group row">                                                       
                                            <div class="col-12 text-right">                                            
                                                <button class='btn ' onclick="Custombox.close();return false;"><?=_l('Отмена');?></button>
                                                <button class='btn btn-primary ' onclick="Custombox.close();return false;"><?=_l('Сохранить');?></button>

                                            </div>
                                            
                                        </div>




                                    </form>
                                </div>
                            </div>

                        </div>
                        <!-- end row -->                    
                </div>
            </div>

        <script src="assets/js/jquery-3.4.1.min.js"></script>
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>                   

<!-- Required datatable js -->
        <script type="text/javascript" src="assets/plugins/datatables/datatables.min.js"></script>                
        <script src="assets/js/fastclick.js"></script>        

        <!-- App js -->
        <!-- jQuery - Loaded with datatables  -->
      <!--  
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>         
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/popper.min.js"></script>        
         <script src="assets/js/waves.js"></script> 
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>        
        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>         
        <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>        
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>        
        <script src="assets/plugins/datatables/dataTables.select.min.js"></script>                
        <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>   
      -->            

        <!-- KNOB JS -->
        <!--[if IE]>
           <script type="text/javascript" src="assets/plugins/jquery-knob/excanvas.js"></script>
        <![endif]-->
        <!-- <script src="assets/plugins/jquery-knob/jquery.knob.js"></script> -->

        <!--Morris Chart-->
	    	<!-- <script src="assets/plugins/morris/morris.min.js"></script> -->
        <!-- Dashboard init -->
        <!-- <script src="assets/pages/jquery.dashboard.js"></script>  --> 

		    <script src="assets/plugins/raphael/raphael-min.js"></script>

       <!-- Modal-Effect -->
        <script src="assets/plugins/custombox/dist/custombox.min.js"></script>
        <script src="assets/plugins/custombox/dist/legacy.min.js"></script>
        
        <script src="assets/js/moment.min.js"></script>                
        <script src="assets/js/modernizr.min.js"></script>        
        <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.min.js"></script>
	      <script src="assets/js/sip-0.15.9.js"></script> 
         <!-- ToolTipster -->
        <script src="assets/js/tooltipster.bundle.min.js"></script>

        

        <!-- CRM js -->

         <script type='text/javascript'>

          $('.show-phone-panel').on('click',function() {
             $( ".side-menu" ).animate({
                width: '235px'
              },{duration:200, queue:false});

              $( ".content-page" ).animate(
               { marginLeft: '235px'},
               {duration:200, queue:false}
              );
              $('.show-phone-panel').fadeOut();
          });


          $('#hide-phone').on('click',function() {
             $( ".side-menu" ).animate({
                width: '0'
              },{duration:200, queue:false});

              $( ".content-page" ).animate(
               { marginLeft: '0'},
               {duration:200, queue:false}
              );
              $('.show-phone-panel').fadeIn();
          });


          
          

        // App configureatin //            
           var serverip =    '<?php echo ( $PBX ? $PBX->ini['general']['domain'] : 'localhost' ); ?>';
         
           var my_name = '<?php echo($_SESSION['CRM_user']['sip']['name']);?>';
           var translateGUI_to = '<?php echo ( $PBX ? $PBX->ini['general']['default_lang'] : 'ru' ); ?>';
           var doTranslate = ( translateGUI_to != 'ru' ) ? true : false;
           var webphone_enable = <?php echo $WEBrtcStatus? 'true':'false';  ?>;                      
           if( webphone_enable ){
             $.post('rtcSwitch/' + my_name + '/rtc');
           }

           $('.ack').on('click',function(){
              $(this).parent().slideUp('slow');
           });

        
        $('.btn-group button').first().addClass('radius-left').end().last().addClass('radius-right');
           

        </script> 
        <script src="assets/js/colResizable-1.5.min.js"></script>        
        <script src="assets/js/crm.dictionary.js"></script>        
        <script src="assets/js/crm.app.js"></script>
        <script src="assets/js/crm.phone.app.js"></script>
        <script src="assets/js/crm.datatables.js"></script>
        <script src="assets/js/digital-clock.js"></script>
        
        
   </body>
</html>
