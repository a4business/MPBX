<?php
 //session_start();
 if ( isset($_SESSION['UID']) ) {
    header("Location:/");
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
<SCRIPT SRC='sc/skins/EnterpriseBlue/load_skin.js'></SCRIPT>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

</head>
<body style='background-color: silver;'>
<center><img src="images/eTor-logo-1-png.png" style='width:200px;top:20px;opacity:0.5;'></center>
<script type="text/javascript" >
     isc.showLoginDialog(function (credentials, dialogCallback) {  
           $.post('jaxer.php',credentials, function (data) {
             console.log('Login: ' + data.success);             
             dialogCallback(data.success);
             if (data.success) window.location = "/";
           },'json');
        },{
        	 dismissable:false,        	 
        	 loginFormProperties: {width: 370, titleWidth: 170, } 
        })    
</script>
</html>