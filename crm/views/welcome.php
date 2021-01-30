<?php
 session_start();
 if( isset($_SESSION['CRM_user']) && is_array($_SESSION['CRM_user']) && count($_SESSION['CRM_user'])  ){
	 header('Location: ./');
 }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured Telephony in a cloud">
        <meta name="author" content="Coderthemes">
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <title><?= _l("Отдел по работе с абонентами | Callcenter ");?></title>
        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/crm-styles.css" rel="stylesheet" type="text/css" />
        <script src="assets/js/modernizr.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>

    </head>

    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">

            <div class="text-center">
	    <a href="index.html" class="logo"><span><?php echo $PBX->ini['general']['company'];?></span></a>
		      <h5 class="text-muted m-t-0 font-600"><?= _l('Абонентский отдел');?></h5>
            </div>
        	<div class="m-t-40 card-box">
                <div class="text-center">
                    <img src='assets/images/cloud.png'><br>
		         <div class="alert alert-danger " id=alert style="visibility: hidden" ><?=_l('Оператор');?></div>
                </div>
                <div class="p-10">
                    <form class="form-horizontal m-t-20" action="" id=loginForm >
                        
                        <div class="form-group">
                            <div class="col-xs-12">
			                    <input class="form-control" name=login id=login   type="text" required="" placeholder="<?=_l('Логин');?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name=password id=passwd  type="password" required="" placeholder="<?=_l('пароль');?>">
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="col-xs-12">
                                <div class="checkbox checkbox-custom">
                                    <input id="checkbox-signup" type="checkbox">
                                    <label for="checkbox-signup">
                                        <?=_l('Запомнить меня');?>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit"><?=_l('Войти');?></button>
                            </div>
                        </div>

                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12">
                                <a href="page-recoverpw.html" class="text-muted"><i class="fa fa-lock m-r-5"></i> 
                                    <?=_l('Забыл пароль?');?></a>
                            </div>
                        <!--    <div>
                                <ul class="language-select" style="margin-top: -29px;"> 
				  <li class="<?php echo ( $_COOKIE['language'] == 'en' ) ? 'active':'';?>" data-lang="en">eng</li>
                                  <li class="<?php echo ( $_COOKIE['language'] == 'ru' ) ? 'active':'';?>" data-lang="ru">рус</li> 
                                  <li class="<?php echo ( $_COOKIE['language'] == 'ua' ) ? 'active':'';?>" data-lang="ua">укр</li>
                                </ul>
                            </div>
                           --> 
                        </div>
                    </form>

                </div>
            </div>
            <!-- end card-box-->

            <div class="">
                <div class="col-sm-8">
                    <p class="text-muted"><?=_l('Нет учетной записи?');?> <a href="page-register.html" class="text-primary m-l-5"><b><?=_l('Зарегистрироватся');?></b></a></p>
                </div>
            </div>
            
        </div>
        <!-- end wrapper page -->

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
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>       
        <script src="assets/js/crm.login.js"></script>        
	
	</body>
</html>
