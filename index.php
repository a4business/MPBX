<?php
 session_start();
 if ( !isset($_SESSION['UID']) ) {
    header("Location: entrance.php");
 }
 
 include_once('include/config.php');
 
?><html>
<HEAD>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<SCRIPT>var isomorphicDir="sc/";</SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Core.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Foundation.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Containers.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Grids.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_Forms.js'></SCRIPT>
	<SCRIPT SRC='sc/system/modules/ISC_DataBinding.js'></SCRIPT>
	<SCRIPT SRC='sc/skins/<?php echo $config->getSkin(); ?>/load_skin.js'></SCRIPT>
	<link type="text/css" rel="stylesheet" href="include/styles.css">
	<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>

	<SCRIPT SRC='sc/system/modules/ISC_Forms.js'></SCRIPT>

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
		        	 autoDraw:false,
		        	 align:"center",
		        	 contents: "<img style='padding:10px;opacity:0.6' src='images/get-logo.php' > "
		  });


//      isc.HTMLFlow.create({
//                    ID:"aMenu",
//                    autoDraw:false,
//                    defaultLayoutAlign: "center",
//                    height:30,
//		            width: "80%",
//	                align:"left",
//		            contents: ""
//               });


isc.AdaptiveMenu.create({
                    ID: "aMenu",
                    autoDraw:false,
                    defaultLayoutAlign: "center",
                    height:30,
                    items: [
                        <?php if ( $config->isAdmin() ){  ?> { title: "Create new PBX", click: " edit_object('Tenants'); frmTenants.clear();wndTenants.show();" },<?php } ?>
                        { id:'reloadBTN', title: "Reload PBX", click: "run('reload_asterisk');" }
                    ]
                 });

		  
		  
 function colorIt(){
 	  isc.say('color');
 	  reloadBTN.title = 'New';
  }
 	
		  
		isc.ToolStrip.create({
				   ID: "MyToolStrip",
		        	 width:"100%",
		        	 autoDraw:false,
		             height:70,
		        	 members: [
			        	  LogoIMG,        	  
			           "separator",
			           aMenu,
			           <?php if ( $config->auto_reload != 1 ){ ?>
			            isc.Button.create({ID:'reloadConf', width:200,click:"run('reload_conf');reloadConf.animateHide('fade');" , title:'<span class=blink >Commit </span>',  width:150, height:20, icon:"[SKIN]actions/refresh.png", visibility:"hidden" }),
			            <?php } ?>
			            <?php
			             if ( $config->isAdmin() ) 
			               echo 'frmSwitchTenant,' ;
 			             else
 			               echo "isc.Label.create({ padding:'17px 0',height:'100%', contents:'<h2>{$_SESSION['tenantname']} </h2>' }), "; 
 			            ?>		           
	                 "separator",
	                 isc.VLayout.create({ align: "center", members: [
			               isc.Button.create({ title:'<?php session_start(); echo $_SESSION["USERNAME"]; ?> ', width:100,  icon:"logout.png",
			                                  click: function () { $.post('jaxer.php',{ 'logout':1 }, 
			                                                               function (data) { window.location = "/";}, 
			                                                              'json');              	              
                                                              }
                                          }),			                                
			              <?php if ( $config->isTenantUser() ){ ?>   isc.Label.create({ align:'center',height:20, width:200,contents:'<span style="color:blue;cursor:pointer">Change Password</span>',click: "ShowChangeUserPassWnd(111);" } ) <?php } ?>
			              ]
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
		    width: 205,
		    height: "100%",	
		    styleName:'myTreeBorder',	    
		    iconSize: 16,
		    nodeIcon: "[SKIN]/icons/node.gif",
		    //  folderIcon: "[SKIN]/folder.png",
		    customIconProperty: 'nodeIcon',		
		    animateFolders:true,		    
		          nodeClick: "if ( node.action != '' ){ TriggerEvent('show_'+node.action); Sections.expandSection('section_' + node.action) };"
		    
		});		
		
// The Main Container constructor //		
		 isc.VLayout.create({
		    width: "99%",
		    height: "99%",    
		    border: 0,
		    overFlow: 'HIDDEN',
		    members: [
		        MyToolStrip,
		        isc.HLayout.create({
		        	ID: "WorkGround",
		            width: "100%",     
		            backgroundColor:'white',     
		            members: [
		                mTree,
		                Sections
		            ]
		        })
		    ]
		});
		
	<?php  
	    if ( $_SESSION['default_section'] ){   
	  	 echo "  TriggerEvent('show_{$_SESSION['default_section']}');\n";
	  	 echo "  Sections.expandSection('section_{$_SESSION['default_section']}');\n";

	   } 
	 ?>	



</script>

<style type='text/css'>
   .cell, .cellSelected, .treeCell, .cellOver, .cellSelectedOver, .treeCellOver,
   .treeCellSelected{
      overflow:visible !important;
    }
 </sctyle>



</BODY>
</html>
