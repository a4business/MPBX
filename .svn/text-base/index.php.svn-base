<?php
 session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location:entrance.php");
 }
 
/*
 // session
 $res = mysql_query("SELECT * FROM etor_users WHERE id = {$_SESSION['UID']} AND 
                     HOUR(TIMEDIFF(now(), ifnull(last_access,now())) < 1" );
 $u = mysql_fetch_assoc($res);
 if ( !$u['id']  ){
    session_destroy();
    unset($_SESSION['UID']);    
    header("Location:entrance.php");
 }
 */
?><html>
<HEAD>
	<SCRIPT>var isomorphicDir="sc/";</SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Core.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Foundation.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Containers.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Grids.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Forms.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_DataBinding.js'></SCRIPT>
	<SCRIPT SRC='sc/skins/EnterpriseBlue/load_skin.js'></SCRIPT>
	<link type="text/css" rel="stylesheet" href="include/styles.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

</HEAD>

<BODY>

 
<iframe  name="pbxUploadCallBackFrame" id="pbxUploadCallBackFrame" tabIndex='-1' style="position:absolute;width:0;height:0;border:0"></iframe>

<script type='text/javascript'>




// Just a test... 
 function MyEvent(options) {
  var self = this;
  self.options = options || {};
  self.state = 'INIT';
  console.log('INIT');
  self.openit();
 };

MyEvent.prototype.openit = function() {
 var self = this;
 console.log('Opening...');
 alert('Gap!');

};

function CallMyEvent(){
 var eventCaller = new MyEvent();
 eventCaller.openit();
};
</script>
<!-- <span onclick="CallMyEvent();">startUp</span> -->

<script src="datasources.js"></script>
<script src="edit_forms.js?<?php echo date("U"); ?>"></script>
<script src="sections.js"></script>

<script type="text/javascript" >


     		
		isc.HTMLFlow.create({
		        	 ID: "LogoIMG",
		        	 width:"195",
		        	 autoDraw:false,
		        	 align:"center",
		        	 contents: "<img src='../images/eTor-logo-1-png.png' style='width:70px;padding-left:20px;padding-top:8px;'> "
		  });
		  
		  
		isc.AdaptiveMenu.create({
		    ID: "aMenu",
		    autoDraw:false,
		    defaultLayoutAlign: "left",    
		    height:30,    
		    items: [
		        {title: "Add Tenant", click: " edit_object('Tenants'); frmTenants.clear();wndTenants.show();"},
		        {title: "Reload PBX", click: "run('reload_dialplan')"}       
		        
		    ]
		 });      
		  
		  
		isc.ToolStrip.create({
				   ID: "MyToolStrip",
		        	 width:"100%",
		        	 autoDraw:false,
		          height:70,
		        	 members: [
			        	  LogoIMG,        	  
			           "separator",
			           aMenu,
			           frmSwitchTenant,		           
	                 "separator",
			           isc.Button.create({ title:'Logout', width:70,  
			                               click: function () { $.post('jaxer.php',{'logout':1}, 
			                                                     function (data) { window.location = "/";} 
			                                                     ,'json');              	              
			                                                    }
			            })
		          ]
		 });  
		   
		
		
		
	 isc.TreeGrid.create({
		    ID: "mTree",
		    dataSource: "mTreeDS",
		    autoDraw: false,
		    autoFetchData: true,
		    showHeader:false,
		    loadDataOnDemand: false,
		    width: 200,
		    height: "100%",		    
		    nodeIcon: "[SKIN]/icons/node.gif",
		   // folderIcon: "[SKIN]/icons/folder.png",		    
          nodeClick: "TriggerEvent('show_'+node.action); Sections.expandSection('section_' + node.action);"
		    
		});		
		
// The Main Container constructor //		
		 isc.VLayout.create({
		    width: "100%",
		    height: "100%",    
		    members: [
		        MyToolStrip,
		        isc.HLayout.create({
		        		ID: "WorkGround",
		            width: "100%",            
		            members: [
		                mTree,
		                Sections
		            ]
		        })
		    ]
		});
		



</script>



</BODY>
</html>
