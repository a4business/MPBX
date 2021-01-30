<?php
 //session_start();


 if ( isset($_SESSION['UID']) ) {
    header("Location: index.php");
 }

 include_once('include/config.php');

 $ip_blocked = mysql_fetch_assoc( mysql_query("SELECT * FROM blacklist WHERE ip = '{$_SERVER['REMOTE_ADDR']}' AND ip != '' LIMIT 1") );
 if( $ip_blocked ){
   mysql_query("UPDATE blacklist SET hit_count = ifnull(hit_count,0) + 1, last_hit = now() WHERE  ip = '{$_SERVER['REMOTE_ADDR']}' ");
   if( isset($ip_blocked['block_web_access']) && $ip_blocked['block_web_access']  ){
     $location = $ip_blocked['redirect_to'] ? $ip_blocked['redirect_to'] : 'http://webkay.robinlinus.com/';
     session_destroy();
     header("Location: {$location} ");
     return;
   }
 }
 

?>
<html>
<HEAD>
<SCRIPT>var isomorphicDir="sc/";</SCRIPT>
<SCRIPT SRC='sc/system/modules/ISC_Core.js'></SCRIPT>
<SCRIPT SRC='sc/system/modules/ISC_Foundation.js'></SCRIPT>
<SCRIPT SRC='sc/system/modules/ISC_Containers.js'></SCRIPT>
<SCRIPT SRC='sc/system/modules/ISC_Forms.js'></SCRIPT>
<SCRIPT SRC='sc/system/modules/ISC_DataBinding.js'></SCRIPT>
<SCRIPT SRC='sc/skins/<?php echo $config->getLoginSkin(); ?>/load_skin.js'></SCRIPT>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<link type="text/css" rel="stylesheet" href="include/styles.css">
</head>
<body style='background-color: silver;'>
<center><img src="images/get-logo.php" style='width:200px;top:20px;opacity:0.5;'></center>
<script type="text/javascript" >
     r = isc.showLoginDialog(function (credentials, dialogCallback) {                             
           $('#isc_B table tr td').text('Checking...');
           $.post('jaxer.php', credentials, function (data) {
 	            console.log('Login: ' + data.success + ' : ' + data.msg);                          
		   $('#isc_B table tr td').text('Log in');
        	    dialogCallback(data.success, data.msg );
             	    if (data.success) 
                      window.location = "./";
		
	   },'json');
        },{
        	 dismissable: false,
		 title: 'Login to CloudPBX',
        	 errorMessage: '<div class=LoginError >Wrong Username or Password!</div>' ,
        	 errorStyle: 'LoginError',        	 
        	 loginFormProperties: {width: 370, titleWidth: 170, textAlign:'center' } ,
           	 headerIconDefaults: {height:16, width: 16, src:"/images/get-logo.php"}
        });

</script>
</body>
</html>
