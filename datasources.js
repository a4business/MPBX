
/// GLOBAL GUI EVENTS handler ///
function TriggerEvent(EventName){
   console.log('Trigger event: '+ EventName);
	switch(EventName.toLowerCase()){
	  case 'show_extensions':
	      DSInbound.invalidateCache();
         tblExtensions.Refresh();         
			break;

	  case 'show_routes':
         tblRoute.Refresh();
         ShowTrunksDS.invalidateCache();
			break;
			
	  case 'show_features':
         tblFeatures.Refresh();
			break;
		
	  case 'show_featuresdef':
         tblFeaturesdef.Refresh();
			break;
		
		
     case 'show_inbound':
         tblInbound.Refresh();
         break;
     
     case 'show_moh':
         tblMOH.Refresh();
         break;

     case 'show_ringgroups':
         tblRinggroups.Refresh();
         break;
         
     case 'show_conferences':
         tblConferences.Refresh();
         break;
          
     case 'show_ivrmenu':
         tblIVRMenu.Refresh();
         break;    
         
     case 'show_sndtenants':
         tblSndTenants.Refresh();
         break;

         
     case 'show_dids':
         tblDIDs.Refresh();
         break;
         
     case 'show_trunks':
         tblTrunks.Refresh();
         ShowTrunksDS.invalidateCache();
         break;

     case 'show_tenants':
         tblTenants.Refresh();
         break;
         
     case 'reload_timefilters':
         console.log('Reload filters...');
         ShowTenantitemsDS.fetchData();
     
         break;
         
     
     case 'reload_tenants':
         // Generate staff inside asterisk for the  tenants, scheck active one (if deleted )) //        				
         //run('reload_dialplan');    This is done in ds, when updating certain tables     
		   frmSwitchTenant.Refresh();
         tblTenants.Refresh();			           	
         break;
   }

 }
 
 function run(action){
    $.post('jaxer.php',  {'run': action },
				 	  function (data) {
								if ( isc.XMLTools.selectObjects(data, "/response/status") == 'OK' ){
                         isc.logWarn('Success:' + isc.XMLTools.selectObjects(data, "/response/message") );
								}else{
							    isc.say("ERROR: Failed to reload!" + isc.XMLTools.selectObjects(data, "/response/message") );		 
								}   
							},
				    'json' );
}

var IVRItemActions = { "extension"   :"Extension",
                       "ringgroup"   :"RingGroup",
		                 "ivrmenu"     :"Auto-Attendant",
                       "featurecode" :"Feature Code",
		                 "number"		 :"Dial Number",
                       "queue"		 :"Queue",
                       "followme"	 :"Followme",
                       "voicemail"   :"VoiceMail Box",
							  "conference"	 :"Conference",
		                 "repeat"		 :"Repeat",
                       "checkvm"		 :"Check VoiceMail",
                       "dirbyname"	 :"Directory By FirstName",
		                 "play_invalid":"Playback Invalid",
                       "play_rec"    :"Playback Recording",
                       "play_tts"    :"Playback Text",
                       "moh"    :"Play Music On Hold",
		                 "hangup"      :"Hangup Call",
		                 "dialtone"    :"Provide second Dial tone on",   // Preserve callerid for outbound //
		                 "exec_cmd"    :"Execute Server Command",
		                 "unassigned"  :"Unassigned" 
		               };

var tts_languages = { 
                'de-DE_BirgitVoice': 'German,Female',
                'de-DE_DieterVoice' : 'German,Male',
                'en-GB_KateVoice' : 'English(British dialect),Female',
                'en-US_AllisonVoice' : 'English (US dialect 1),Female',
                'en-US_LisaVoice' : 'English (US dialect 2),Female',
                'en-US_MichaelVoice' : 'English (US dialect) (Default),Male',
                'es-ES_EnriqueVoice' : 'Spanish (Castilian dialect),Male',
                'es-ES_LauraVoice' : 'Spanish (Castilian dialect),Female',
                'es-LA_SofiaVoice' : 'Spanish (Latin American dialect),Female',
                'es-US_SofiaVoice' :  'Spanish (North American dialect),Female',
                'fr-FR_ReneeVoice' : 'French,Female',
                'it-IT_FrancescaVoice' : 'Italian,Female',
                'ja-JP_EmiVoice' : 'Japanese ,Female',
                'pt-BR_IsabelaVoice' : 'Brazilian Portuguese,Female'
             }
             
 var week_days = {
 	          1 : 'Monday',
 	          2 : 'Tuesday',
 	          3 : 'Wednesday',
 	          4 : 'Thusday',
 	          5 : 'Friday',
 	          6 : 'Saturday',
 	          7 : 'Sunday' }
 	          
var DSTransformator = { 
          transformRequest : function (dsRequest) {
             //query: dsRequest.operationType = 'fetch','update','add','remove' //
          	 dsRequest.data = isc.addProperties({}, dsRequest.data, {  set_data: dsRequest.data } );
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData){  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if( status == 'FAIL' ){
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
          }
     }      

var queuesStrategies = {
         'ringall' : 'Ring All',
         'leastrecent' : 'Ring which recent hang up',
         'fewestcalls' : 'Ring fewest completed calls',
         'random'  : 'Random',
         'rrmemory': 'Round-robin with memory',
         'linear' : 'Rings linear, one by one by order'
	 }

 function getBindings(instance){
 	 	  return { operationBindings: [
	      		  {operationType:"fetch", dataURL:"ds.php?get="+instance, dataProtocol:"postParams"},
                 {operationType:"update",dataURL:"ds.php?set="+instance, dataProtocol:"postParams"},
                 {operationType:"add",   dataURL:"ds.php?add="+instance, dataProtocol:"postParams"},
                 {operationType:"remove",dataURL:"ds.php?del="+instance, dataProtocol:"postParams"}
               ]};
    }             
		 



	//  Conferences //
		isc.DataSource.create({
		    ID:"DSConferences",
		    dataURL:"ds.php?get=t_conferences",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[ 
		        {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
		        {name:"conference", type:"string", title:"Room#"},
//{name:"name",         type:"string"},
              {name:"maxusers",     type:"integer", title:'Maximum Participants',defaultValue:10},
              {name:"description", type:"string", title:"Description"},
              {name:"enable_moh", type:"checkbox",labelAsTitle:true,title:'Play MOH for a single caller'},		        
              {name:"moh_class",title:"Music On Hold", defaultValue:"default", 
                         editorType: "SelectItem",
                         optionDataSource: getTenantDS('items'),
                         pickListCriteria : { 'item_type': 'moh' },
			                displayField:"name", valueField: "name" }, //????
		        {name:"announcement_file", defaultValue: 0,
						       editorType: "SelectItem",title:'Play before start conference',
			                optionDataSource: getTenantDS('items'),
								 displayField:"name", valueField:"id",
 						       pickListCriteria : { 'item_type': 'play_rec' },
 						       specialValues: { 0: "None"} 
			      },
		        {name:"users",type:"select", editorType:"SelectItem", title:'Conference Owner(s)',
	                       colSpan:4, width:380,
	             		     multiple:true,
	             		     multipleAppearance:"picklist",
	             		     optionDataSource: getTenantDS('items'),
	             		     displayField:"name", valueField:"name",
								  pickListCriteria: { item_type: 'extension' }       
				  },
              {name:"password",     type:"string", title:"PIN"},
              {name:"admin_password", type:"string", title:"Administrator's PIN"},
          //    {name:"options",      type:"string"},           
		        {name:"status",       type:"checkbox",labelAsTitle:true},
              {name:"enable_recording",type:"checkbox",labelAsTitle:true},
              {name:"announce_count",type:"checkbox",labelAsTitle:true,title:'Announce user count on joining '},
              {name:"announce_join",type:"checkbox", labelAsTitle:true},
              {name:"enable_menu",  type:"checkbox", labelAsTitle:true},
              {name:"wait_marked",  type:"checkbox", labelAsTitle:true, title:'Wait for a leader'},
              {name:"end_marked",   type:"checkbox", labelAsTitle:true, title:'End when a leader exits'},       
              {name:"detect_talker",type:"checkbox", labelAsTitle:true}
		    ]
       }, getBindings('t_conferences'),DSTransformator ); 
       
       
       
    //  Feature Codes  //
		isc.DataSource.create({
		    ID:"DSFeatures",
		    dataURL:"ds.php?get=t_extensions",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		       {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
		       {name:'exten',type:'string',title:'Feature code'}, 
		       {name:'description',type:'string'},		       
		       {name:'appdata',type:'select',title:'Application name',
		              editorType: "SelectItem",
			           optionDataSource: getTenantDS('items'),
						  displayField:"name", valueField:"id",
 						  pickListCriteria : { 'item_type': 'feature_code' }
		         }
		     ]
		}, getBindings('t_extensions'), DSTransformator );
		
		
  //  Feature Codes  //
		isc.DataSource.create({
		    ID:"DSFeaturesdef",
		    dataURL:"ds.php?get=feature_codes",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		       {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
		       {name:'exten',type:'string',title:'Feature code'}, 
		       {name:'description',type:'string'},
		       {name:'appdata',type:'string',title:'Application name'}
		     ]
		}, getBindings('feature_codes'), DSTransformator );
		
 	
	//  RingGroups  //
		isc.DataSource.create({
		    ID:"DSRinggroups",
		    dataURL:"ds.php?get=t_ringgroups",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[ 
		        {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
		        {name:"name", type:"string", title:"Name"},
		        {name:"description", type:"string"},
		        {name:"announcement_file", title:"Play announcement", //defaultValue:"",
						       editorType: "SelectItem",
			                optionDataSource: getTenantDS('items'),
								 displayField:"name", valueField:"id",
 						       pickListCriteria : { 'item_type': 'play_rec' }
			              },
		        {name:"default_action", type:"select", title:"When no-answer", valueMap: IVRItemActions, defaultValue:'hangup' },
		        {name:"default_action_data",type:"string",hidden:true}
		    ], 
		    operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_ringgroups", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_ringgroups", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_ringgroups", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_ringgroups", dataProtocol:"postParams"}
          ]
       }, DSTransformator ); 
       
       
       
 	//  RingGroup Lists  //
		isc.DataSource.create({
		    ID:"DSRinggroupLists",
		    dataURL:"ds.php?get=t_ringgroup_lists",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[ 
		        {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
              {name:"t_ringgroups_id", type:"integer", title:" ID", foreignKey:"DSRinggroups.id", hidden:true},
              {name:"description", type:"string", title:"Hunt lists"},
		        {name:"announcement_file", title:"Play announcement", defaultValue:"",
						       editorType: "SelectItem",
			                optionDataSource: getTenantDS('items'),
								 displayField:"name", valueField:"id",
 						       pickListCriteria : { 'item_type': 'play_rec' },
			                specialValues: { 0: "None"} 
			              },			                
		        {name:"extensions", type:"string",title:"Extensions to Ring",defaultValue:''},
		        {name:"phone_numbers", type:"string", title:"Other numbers to Ring"},
		        {name:"timeout", type:"string",title:"Ring timeout",defaultValue:60},
              {name:"group_type", type:"string",hidden:true},
		        
		    ], 
		    operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_ringgroup_lists", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_ringgroup_lists", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_ringgroup_lists", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_ringgroup_lists", dataProtocol:"postParams"}
          ]
       }, DSTransformator );      
		    
		    
		    
		//  Queues  //
		isc.DataSource.create({
		    ID:"DSQueues",
		    dataURL:"ds.php?get=t_queues",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id",   type:"integer", title:" ID", hidden:true,primaryKey:true  },
              {name:"name", type:"string", title:"Name" },				  
              {name:"strategy", type:"select", title:"Strategy",  valueMap: queuesStrategies , defaultValue:'random'},
  			     {name:"queue_welcome", type:"string", title:"Welcome to caller"},
			     {name:"announce", type:"string",title:"Announce to member before answer", defaultVaule:''},
              {name:"musiconhold", optionDataSource: "ShowTenantMOHDS", title:"Music On Hold", defaultValue:"default",
						       editorType: "SelectItem", 
			                displayField:"moh_name", 
			                valueField: "moh_name" },
			     {name:"timeout", type:"integer", title:"Member timeout", defaultValue:30},
			     {name:"retry", type:"integer", title:"Retry timeout,s", defaultValue:5 },
			     {name:"announce_frequency",type:"integer", title:"Position announce interval,s(0=off)", defaultValue:60 },
			     {name:"queue_youarenext", type:"string", title:'Play when first in line', defaultValue:'queue-youarenext'},
			     {name:"wrapuptime",type:"integer", title:"Member delay before next call", defaultValue:'5'},
			     {name:"autopause",type:"select",valueMap:['yes','no'], title:"Autopause failed member",defaultValue:'yes'},
              {name:"maxlen", type:"integer", title:"Maximum waiting callers",defaultValue:10},
              {name:"announce_holdtime", type:"select", valueMap:['yes','no','once'], title:"Include estimated hold time announcement", defaultValue:'once'},
              {name:"joinempty",type:"select", valueMap:{'':'Yes','penalty,paused,invalid':"No"}, title:'Allow join empty queue',defaultValue:'no'},
              {name:"leavewhenempty",type:"select", valueMap:{'penalty,paused,invalid':'Yes','':"No"}, title:'Force to leave empty queue' , defaultValue:'no'},
              {name:"reportholdtime",type:"select", valueMap:['yes','no'],title:"Report hold time to member before connect",defaultValue:'yes'},
              {name:"ringinuse", type:"select", valueMap:['yes','no'],title:"Ring inuse member", defaultValue:'no'}
		    ],      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_queues", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_queues", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_queues", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_queues", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             //query: dsRequest.operationType = 'fetch','update','add','remove' //
          	 dsRequest.data = isc.addProperties({}, dsRequest.data, {  set_data: dsRequest.data } );
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
		});	
  	           
  	                       
  	                       
  	//  QueuesMembers  //
		isc.DataSource.create({
		    ID:"DSQueueMembers",
		    dataURL:"ds.php?get=t_queue_members",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id",  type:"integer", title:" ID", hidden:true,primaryKey:true  },
				  {name:"membername",type:"string", title:"Extension"},
				  {name:"queue_name",type:"string", foreignKey: "DSQueues.name", hidden:true },
				  {name:"interface", type:"string", title:"Device" },
				  {name:"paused",    type:"select", title:"Is Paused?", valueMap:{1:'yes',0:'no'}, defaultValue:'no' }
  		    ],      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_queue_members", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_queue_members", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_queue_members", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_queue_members", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
          	 dsRequest.data = isc.addProperties({}, dsRequest.data, {  set_data: dsRequest.data } );
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
		});	


		
	  isc.DataSource.create({
	  	  ID: "ShowTenantQueuesmembers",
	  	   dataURL: "ds.php?get=view_tenant_queuesmembers",
				       dataFormat: "json", 
				       idField: "membername",		  
				       fields:[
				         {name:"membername", title: 'Extension', type:"string",primaryKey:true },
				         {name:"queue_name", title: 'ID', foreignKey:"DSQueues.name", type:"string",hidden:true },
				         {name:"interface", title: 'Device', type:"string" },
				         {name:"paused",    type:"select", hidden:true,canEdit:false, valueMap:{1:'yes',0:'no'}, defaultValue:'no' }
				       ],
				     transformRequest : function (dsRequest) {
		            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
		             return this.Super("transformRequest", arguments);
		        },
		       operationBindings:[
                {operationType:"add",dataURL:"ds.php?add=view_tenant_queuesmembers", dataProtocol:"postParams"}
             ]	
		  });		
				  

//  IVRMenu  //
		isc.DataSource.create({
		    ID:"DSIVRMenu",
		    dataURL:"ds.php?get=t_ivrmenu",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,	  
		    fields:[
				  {name:"id",                type:"integer",title:" ID", hidden:true, primaryKey:true, },
				  {name:"name",              type:"string", title:"Auto-Attendant name" },
				  
//		        {name:"voicemail_box",     type: "string",title:"Default VoiceMail box" },
	           //{name:"voicemail_box", optionDataSource: getTenantDS('items'), title:"Default VoiceMail box",
	           //           pickListCriteria: {'item_type':'voicemail'}, 
              //		       ID: "IVRMenuVMPickList",
				//		       editorType: "SelectItem", 
			    //            displayField:"name", 
			     //           valueField: "name" },
		        
				  {name:"announcement_type", type:"select", title:"Announcement type", defaultValue: "recording", valueMap:{'recording' : 'Play Recording','tts' :'Text to speech','moh':'Play Music On Hold', 'upload' : 'Upload  ..,' } },
				  {name:"announcement_lang", type:"select", valueMap: tts_languages , defaultValue:'en-US_MichaelVoice', title: "Speech Language" },
				  {name:"announcement",      type:"string", title:"Announcement", showPickerIcon: true, 
				             pickerIconSrc: "[SKIN]/pickers/play_picker.png",
				             pickerIconClick:"IVRplayer.src = 'media/play.php?mode=' + frmIVRMenu.getValues()['announcement_type']  + '&play_text=' + frmIVRMenu.getValues()['announcement'] + '&ttslang=' + frmIVRMenu.getValues()['announcement_lang'] + '&play_dir=tenant';"+
				                             "IVRplayer.Play();" 
				  },
		        {name:"moh_class", optionDataSource: "ShowTenantMOHDS", title:"Default Music On Hold", defaultValue:"default",
              		       ID: "IVRMenuMOHPickList",
						       editorType: "SelectItem", 
			                displayField:"moh_name", 
			                valueField: "moh_name" },
//			     {name:"recordings_lang",type:"string", valueMap: [ 'en','tenant' ], defaultValue:'en', title:'Media folder'},
              {name:"digit_timeout",title:"Digit timeout,ms", type: "integer",defaultValue:1000},
              {name:"menu_timeout",title:"How long wait for selection,s", type: "integer",defaultValue:30},
              {name:"delay_before_start",title:"Delay before playing,ms", type: "select", valueMap: [ '0','500','1000'], defaultValue: '0'},
              {name:"ring_while_wait",title:"Ring While Wait",type: "select", valueMap: [ 'yes','no'], defaultValue: 'yes'},
              {name:"allow_dialing_exten",title:"Allow dial local extension",type: "select", valueMap: [ 'yes','no'], defaultValue: 'yes'},
              {name:"allow_dialing_featurecode",title:"Allow feature codes",type: "select", valueMap: [ 'yes','no'], defaultValue: 'yes'},
              {name:"allow_dialing_external",title:"Allow dial external numbers",type: "select", valueMap: [ 'yes','no'], defaultValue: 'no'},
              {name:"description",       type:"string", title:"Description" }
			                
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_ivrmenu", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_ivrmenu", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_ivrmenu", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_ivrmenu", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             //query: dsRequest.operationType = 'fetch','update','add','remove' //
          	 dsRequest.data = isc.addProperties({}, dsRequest.data, {  set_data: dsRequest.data } );
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
		});	
		
		
		//  IVRMenuItems  //
		isc.DataSource.create({
		    ID:"DSIVRMenuItems",
		    dataURL:"ds.php?get=t_ivrmenu_items",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,	
		   // sparseUpdates: true,	  
		    fields:[
				  {name:"id",                type:"integer", title:" ID", hidden:true, primaryKey:true, },				  
				  {name:"t_ivrmenu_id",      type:"integer", foreignKey: "DSIVRMenu.id", hidden:true },
				  {name:"selection",         type:"string", title:"#",   required:true },
				  {name:"item_action",		  type:"select", valueMap: IVRItemActions, title:"Action" },
				  {name:"item_data",         title:"Destination"  },
				  {name:"announcement_type", type:"select", title:"Play", defaultValue: "no", valueMap:{ 'no':'no', 'recording' : 'Play Recording','tts' :'Text to speech','upload':'Upload ...' } },
				//  {name:"announcement_lang", type:"select", valueMap: tts_languages , defaultValue:'en-US_MichaelVoice', title: "Speech Language" },
				  {name:"announcement",      type:"string", title:"Announcement"}   //, editorType:"TextAreaItem" } 
		    ],      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_ivrmenu_items", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_ivrmenu_items", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_ivrmenu_items", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_ivrmenu_items", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             //query: dsRequest.operationType = 'fetch','update','add','remove' //
          	 dsRequest.data = isc.addProperties({}, dsRequest.data, {  set_data: dsRequest.data } );
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
		});	


//  MOH  //
		isc.DataSource.create({
		    ID:"DSMOH",
		    dataURL:"ds.php?get=t_moh",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,	  
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:" ID", hidden:true },
				  {name:"name", type:"string", title:"MoH Profile name" },
				  {name:"directory", type: "string",  title:"Directory with files" },
				  {name:"application", type:"string", title:" Application for play",hidden:true },
				  {name:"mode", type:"select", title:"Profile Mode", defaultValue: "files", valueMap: {'files':'Read Files from Directory','mp3':'Loud mp3','quietmp3':'Quietmp3','mp3nb':'unbuffered Mp3','quietmp3nb':'quiet unbuffered',"custom":'Run custom APplication' } },
				  {name:"sort", type: "select", valueMap: ["random", "alpha"], defaultValue:'random', title: "Sort files"},
		        {name:"format", type: "select", valueMap: {'gsm':"GSM",'ulaw':"G711U", 'ulaw':"G711A", 'mp3':"MP3", 'slin':'WAV, 16bit 8kHz PCM'}, defaultValue:'slin', title:"Files media format"},				  
				  //{name:"announcement", type:"string", title:"Play file between songs", defaultValue:""},
				  {name:"digit", type:"string", title:"Key to switch", defaultValue:"" }
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_moh", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_moh", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_moh", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_moh", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             //query: dsRequest.operationType = 'fetch','update','add','remove' //
          	 dsRequest.data = isc.addProperties({}, dsRequest.data, {  set_data: dsRequest.data } );
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
		});		
		



// OUTBOUND Routes //	
		isc.DataSource.create({
		    ID:"DSTimeFilters",
		    dataURL:"ds.php?get=t_timefilters",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:"Filter ID", hidden:true },
		        {name:"name", type:"string",  title: "Filter Name"},
		        {name:"description",    type: "string" },	        
			     {name:"time_period", type:"string"}
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_timefilters", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_timefilters", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_timefilters", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_timefilters", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {
             if ( isc.XMLTools.selectObjects(jsonData, "/response/status") == 'FAIL' ){                    
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }else{ 
				   isc.logWarn('Success:' + isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }   
           return dsResponse;  
         }
		});


// DIDs  //
	
		isc.DataSource.create({
		    ID:"DSDIDs",
		    dataURL:"ds.php?get=dids",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:"DID ID",hidden:true },
		        {name:"DID",    type: "string", title: "Did number" },			
		        {name:"tenant_id", type:"select", title:"Available in Tenant",
				                   		     //multiple:true,		
				                   		     //multipleAppearance:"picklist",
				                   		     editorType: "SelectItem",
				                   		     optionDataSource: "ShowTenantsDS", 
				                   		     displayField:"title", valueField:"id"
			      },     
		        {name:"description",    type: "string", title: "Short info" },
		        {name:"assigned_destination", type:"string",title:"Assigned to", canEdit:false}
			     
			     				     
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=dids", dataProtocol:"postParams"},  // We use _t_ prefix to remote tanant filter 
               {operationType:"update",dataURL:"ds.php?set=dids", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=dids", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=dids", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             dsRequest.data = isc.addProperties({}, dsRequest.data, { operation_type: dsRequest.operationType, set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {
             if ( isc.XMLTools.selectObjects(jsonData, "/response/status") == 'FAIL' ){                    
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }else{ 
				   isc.logWarn('Success:' + isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }   
           return dsResponse;  
         }
		});


//INBOUND  Routes //	
		isc.DataSource.create({
		    ID:"DSInbound",
		    dataURL:"ds.php?get=t_inbound",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:"route ID", hidden:true },
		        {name:"did_id", title: "Inbound DID",                    		        		               
		              optionDataSource: 'DSDIDs', 
		              editorType: "SelectItem",
		              displayField:"DID", valueField:"id" },
		        {name:"is_enabled", type:"select", title: "Is enabled?", valueMap: { 1: 'yes',0: 'no' }, defaultValue: '1' },	        
			     {name:"description",type:"string", title: "Description"}
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_inbound", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_inbound", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_inbound", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_inbound", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {
             if ( isc.XMLTools.selectObjects(jsonData, "/response/status") == 'FAIL' ){                    
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }else{ 
				   isc.logWarn('Success:' + isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }   
           return dsResponse;  
         }
		});
		

// InBound Rules List//	
		isc.DataSource.create({
		    ID:"DSInboundRules",
		    dataURL:"ds.php?get=t_inbound_rules",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", type:"integer", primaryKey:true, title:"Rule ID",hidden:true },				  
		        {name:"inbound_id", type:"integer", foreignKey: "DSInbound.id", hidden:true },
		        //{name:"timefilter_id",type:"select", title: "When:", width:60,
		        {name:"week_day_from", type:"select", title: "From", valueMap: week_days, defaultValue:1},
              {name:"week_day_to", type:"select", title: "Till", valueMap: week_days, defaultValue:7},               
              {name:"day_time_from", type:"select", editorType: "TimeItem", minuteIncrement: 15,showSecondItem:false,useTextField: false,title: "Start",defualtValue:'00:00:00' },
              {name:"day_time_to",   type:"select", editorType: "TimeItem", minuteIncrement: 15,showSecondItem:false,useTextField: false,title: "End",defualtValue:'23:59:59'},
			     {name:"action",       type:"select", valueMap: IVRItemActions, title:"Route To",width:110 },
              {name:"destination"},
			     {name:"opt1",       type:"string",  title:"Wait for answer,s", defaultValue:60 },
              {name:"opt2",       type:"string",  title:"Prepend to CallerID" },
              {name:"info",       type:"string",  title:"Rule Description " }			     				     
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_inbound_rules", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_inbound_rules", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_inbound_rules", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_inbound_rules", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
          	// if ( dsRequest.operationType == 'add' ){
           	//  dsRequest.data = isc.addProperties({}, dsRequest.data, { route_id: tblRoute.getSelectedRecord().id });     
          	// }
             dsRequest.data = isc.addProperties({}, dsRequest.data, { operation_type: dsRequest.operationType, set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {
             if ( isc.XMLTools.selectObjects(jsonData, "/response/status") == 'FAIL' ){                    
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }else{ 
				   isc.logWarn('Success:' + isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }   
           return dsResponse;  
         }
		});

	
// OUTBOUND Routes //	
		isc.DataSource.create({
		    ID:"DSRoute",
		    dataURL:"ds.php?get=t_route",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:"route ID", hidden:true },
		        {name:"name", type:"string",  title: "Route name"},
		        {name:"route_enabled",    type: "select", title: "Enabled", valueMap: { 1: 'yes',0: 'no' }, defaultValue: '1' },	        
			     {name:"outbound_callerid", type:"string", title:"Force Outbound CallerID"}
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_route", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_route", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_route", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_route", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {
             if ( isc.XMLTools.selectObjects(jsonData, "/response/status") == 'FAIL' ){                    
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }else{ 
				   isc.logWarn('Success:' + isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }   
           return dsResponse;  
         }
		});


// OutBound Routes List//	
		isc.DataSource.create({
		    ID:"DSRoutesList",
		    dataURL:"ds.php?get=routes_list",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:"routeList ID",hidden:true },				  
		        {name:"route_id", type:"integer", foreignKey: "DSRoute.id", hidden:true },
		        {name:"dial_pattern",    type: "string", title: "Asterisk Dialplan pattern", defaultValue: '_1X.' },
			     {name:"strip", type:"integer", title:"Strip digits"},
			     {name:"add_prefix", type:"string",  title:"Add Prefix" },
			     {name:"trunk_id", optionDataSource: "ShowTrunksDS",
						       editorType: "SelectItem",
			                displayField:"name", 
			                valueField: "id",
			                title: "Trunk" },
			     {name:"trunk2_id", optionDataSource: "ShowTrunksDS",
						       editorType: "SelectItem",
			                displayField:"name", 
			                valueField: "id",			                
			                title: "Failover Trunk" }				     
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=routes_list", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=routes_list", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=routes_list", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=routes_list", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
          	// if ( dsRequest.operationType == 'add' ){
           	//  dsRequest.data = isc.addProperties({}, dsRequest.data, { route_id: tblRoute.getSelectedRecord().id });     
          	// }
             dsRequest.data = isc.addProperties({}, dsRequest.data, { operation_type: dsRequest.operationType, set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {
             if ( isc.XMLTools.selectObjects(jsonData, "/response/status") == 'FAIL' ){                    
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }else{ 
				   isc.logWarn('Success:' + isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }   
           return dsResponse;  
         }
		});			
		
// Trunks //	
		isc.DataSource.create({
		    ID:"DSTrunks",
		    dataURL:"ds.php?get=trunks",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:"tenant ID", hidden:true },
		        {name:"name", 		type:"String",  title: "Trunk name", required:true },
		        {name:"trunk_type",    type: "select", title: "Direction", valueMap: { 'friend':"Both", 'user':"InBound",'peer':"OutBound" }, defaultValue: 'peer' },
		        {name:"dial_timeout", type:"integer", title:"Dial timeout,s", defaultValue:60},
				  {name:"host",      type:"string", title:"SIP Gateway", required:true },
				  {name:"defaultuser", type:"string", title:"Default User "},				  
				  {name:"secret", type:"string", title:"Secret", hidden:true},
				  {name:"context",   type:"select", title:"Context", type: "select", valueMap: ["from-pstn", "from-internal"], defaultValue:'from-pstn'},
				  {name:"status",   type:"select", title:"Trunk enabled", valueMap: {1:"yes",0:"no"}, defaultValue:1 },
              {name:"domain", type:"String", title:"Domain Name",hidden:true},
              {name:"description",   type:"string", title:"Description"},
              {name:"other_options",   type:"text", title:"Other trunk options",hint:"key=value", showHintInField:true },
              {name:"sip_register", type:"string", hint:"username[@domain][:secret[:authuser]]@host[:port]", showHintInField:true, title: "Register => ", hidde:true}
              //{name:"inTenants",   type:"String", title:"Available In Tenants", type:"select" }
              //{name:"max_concurrent_calls", type:"integer", title:"Max Concurrent calls", defaultValue:0, visible:false }, // Added while opening form in section
              //{name:"max_call_duration", type:"integer", title:"Max Call duration", defaultValue:0, hidden:false }
              
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=trunks", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=trunks", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=trunks", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=trunks", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {
             if ( isc.XMLTools.selectObjects(jsonData, "/response/status") == 'FAIL' ){                    
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }else{ 
				   isc.logWarn('Success:' + isc.XMLTools.selectObjects(jsonData, "/response/message") );
				 }   
           return dsResponse;  
         }
		});
		
		
// VoiceMail Users //	
		isc.DataSource.create({
		    ID:"DSvm_users",
		    dataURL:"ds.php?get=t_vmusers",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,	  
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:" ID", hidden:true },
				  {name:"email", type:"string", hidden:true },
				  {name:"mailbox", type: "integer",  title:"VoiceMail box number", hidden:false },
				  {name:"password", type:"string", title:" VoiceMail box PIN" },
				  {name:"vm_timeout", primaryKey:true, type:"integer", title:" Forward to VoiceMail after", hint:", sec" },
		        {name:"sendvoicemail", type: "string", valueMap: ["yes", "no"], defaultValue:'yes', title:"Send Email notification" },
				  {name:"attach", type: "string", valueMap: ["yes", "no"], defaultValue:'yes', title:"Attach Voice file to email" },
		        {name:"delete", type: "string", valueMap: ["yes", "no"], defaultValue:'yes', title: "Delete after sent"}
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_vmusers", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_vmusers", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_vmusers", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_vmusers", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
             //query: dsRequest.operationType = 'fetch','update','add','remove' //
          	 dsRequest.data = isc.addProperties({}, dsRequest.data, {  set_data: dsRequest.data } );
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
		});		
		
	
	// Tenants //	
		isc.DataSource.create({
		    ID:"DSTenants",
		    dataURL:"ds.php?get=tenants",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,	  
		    
		    fields:[
				  {name: "id", primaryKey:true, type:"integer", title:"ID", hidden:true },
		        {name: "title", type:"string", title: "Tenant Description/title" },
		        {name: "ref_id", type:"string", title: "Unique reference name", required:true },		        
		        {name: "max_extensions", type:"integer", title:"Extension Limits" },
		        {name: "sounds_language", type:"select", title:"IVR promts language set", valueMap: ["en", "ru"],  defaultValue:'en' },
		        {name: "general_error_message", type:"string", title:"When general Error happens, play:", defaultValue:"cannot-complete-otherend-error",hidden:true },
		        {name: "outbound_callerid", type:"string", title: "External CallerID Number",defaultValue:'' },
              {name: "outbound_callername", type:"string", title: "External CallerID Name",defaultValue:''  },
              {name: "parkext", type:"string", title:"Call Parking Extension", defaultValue:"700" },
              {name: "parkpos", type:"select", valueMap:[ 2,4,6,10,20,30 ], title:"Parking lot capacity" , defaultValue:"10", hint:', extensions' },
              {name: "parkingtime", type: "select", valueMap:[ 30,60,120,180,300,600,1200 ], title:"Parking time", hint:', seconds', defaultValue: "60"},
              {name: "parkfindslot",type:"select", title:"Park ext selection method", valueMap: ["next", "first"], defaultValue:'next' },
              {name: "parkcomebacktoorigin", type:"select", title:"When parked call is timed out", valueMap: { "yes" : "Reconnect to origin", "no" : "Hangup(default)" }, defaultValue:"no" },              
              {name: "parkedmusicclass", optionDataSource: "ShowTenantMOHDS", title:"MOH on the parked channel", defaultValue:"default",
              		       ID: "TenantMOHPickList",
						       editorType: "SelectItem", 
			                displayField:"moh_name", 
			                valueField: "moh_name" }
			                
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=tenants", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=tenants", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=tenants", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=tenants", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
		});
		
		
		
		
		
		
		
// Extensions //
		isc.DataSource.create({
		    ID:"DSExtensions",
		    dataURL:"ds.php?get=t_sip_users",
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		          {name: "id", title: "Extension ID", primaryKey:true, hidden:true},
                {name: "extension", title: "Extension Number",required:true, validators:[{ type:'isUnique'}] },
                {name: "name",   title: "SIP Username",hint:"(autogenerated)",showHintInField:true},
                {name: "nat",   title: "NAT",valueMap: ["yes", "no","comedia","force_rport","auto_comedia","auto_force_rport"], defaultValue:"force_rport,comedia"},
                {name: "secret", title: "SIP Password",hint:"(autogenerated)",showHintInField:true, hidden:true },
                {name: "dtmfmode", type: "select", valueMap: ["auto", "rfc2833","inband","info"], title: "DTMF mode",defaultValue:"auto" },
					// {name: "context", title: "DialPlan profile",canEdit:false },
					 {name: "qualify", type:"string", title: "Qualify", defaultValue:"yes"},
                {name: "type", type: "select", valueMap: ["friend", "peer","user"],defaultValue:'friend',hidden:true},
                {name: "email", type: "string", title: "Default Email"},
                {name: "email_pager", type: "string", title: "Alternative Email"},
                {name: "other_options", type: "string", title: "Other Extension options", hint:"key=value", showHintInField:true },
                {name: "enable_mwi",type: "string", valueMap: ["yes", "no"], defaultValue:'yes', title: "Enable MWI"},
                {name: "videosupport",type: "string", valueMap: ["yes", "no"], defaultValue:'yes', title: "Video support"},
                {name: "allow", type:"string", title:"Voice Codecs",defaultValue:"ulaw,gsm,h263"},
                //{name: "outbound_route", type: "integer" }
                {name:"outbound_route", type:"select", title:"Outbound route:",
				                   		     //multiple:true,		
				                   		     //multipleAppearance:"picklist",
				                   		     editorType: "SelectItem",
				                   		     optionDataSource: "ShowRouteTablesDS", 
				                   		     displayField:"name", valueField:"id"
			        },
			        {name:"did_id", type:"select", title:"Assigned DID:", validators:[{ type:'isUnique'}], hidden:true,
				                   		     //multiple:true,		
				                   		     //multipleAppearance:"picklist",
				                   		     //optionCriteria: { only_available_did: 1} ,
				                   		     editorType: "SelectItem",				                   		     
				                   		     optionDataSource: "ShowDIDsDS",   // TODO switch to view_tenant_items with picklistcriteria = item_type = DID
				                   		     displayField:  "DID", valueField:"id",
				                   		     pickListWidth: 200,
				                   		     pickListFields:[{name:"DID"},{name:"assigned_to"}],
				                   		     specialValues: { 0: "None"}
			        },
			         {name:"useragent", type:"string", title:"User agent info" },
			         {name:"ipaddr", type:"string", title:"Endpoint IP:" },
			         {name:"internal_callerid",type:"string",defaultValue:'',hidden:true},
			         {name:"internal_callername",type:"string", defaultValue:'',hidden:true},
			         {name:"first_name", type:'string'},{name:"last_name", type:"string"}
		    ],
	      operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_sip_users", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_sip_users", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_sip_users", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_sip_users", dataProtocol:"postParams"}
          ],
          transformRequest : function (dsRequest) {
            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
          },
          transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
            
		});
		
		
/// Presudo-datasources (custom)

// Show DIDs for Current Tenant //
	// TODO - convert INTO view_tenant_items with picklist filter by item_type=did
		isc.DataSource.create({
		    ID:"ShowDIDsDS",
		    dataURL:"ds.php?get=view_dids",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id", primaryKey:true, type:"integer", title:"DID ID",hidden:true },
		        {name:"DID",    type: "string", title: "DID" },
		        {name:"assigned_to", type: "string", title:"Assigned To", canEdit:false},
		        {name:"description",    type: "string", title: "Short info" }
		        //{name:"assigned_destination", type:"string",title:"Assigned to", canEdit:false}
		    ],	      
    	     transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
          
        
		});
		
 var MEDIA_DS = {  
 	                 dataFormat: "json",
						  idField: "file_name",		  
						  fields:[
						    {name:"file_name", type:"string", title: 'File name', primaryKey:true },		     
						    {name:"size",      type:"string", title: 'Size' },
				          {name:"format",    type:"string", title: 'Media format' },
						    {name:"duration",  type:"string", title: 'Duration' }
						  ],
						  transformRequest : function (dsRequest) {
				            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
				             return this.Super("transformRequest", arguments);
				       },
			          transformResponse : function (dsResponse, dsRequest, jsonData){ 
					     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
			             if ( status == 'FAIL' ) {
			               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
								isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
			             }
			          }    
                 } ;   		
		
 // Show Moh files // 
  isc.DataSource.create(MEDIA_DS,{
		  ID: "ShowMOHFilesDS",  
		  dataURL: "fileManager.php?show=_mohfiles",
		  operationBindings:[
               {operationType:"remove",dataURL:"fileManager.php?del=_mohfiles", dataProtocol:"postParams"}
         ]
		});	
		
		
// Show SND files (default - en folder, or tenant  ? // 
  isc.DataSource.create(MEDIA_DS, {
  	                ID: "ShowSNDFilesDefaultDS",  
		             dataURL: "fileManager.php?show=_sndfilesDefault",
		  operationBindings:[
               {operationType:"remove", dataURL:"fileManager.php?del=_sndfilesDefault", dataProtocol:"postParams"},
               {operationType:"fetch",  dataURL:"fileManager.php?show=_sndfilesDefault", dataProtocol:"postParams"}
         ]
		});
		
 	
	// Show SND files (default - en folder, or tenant  ? // 
  isc.DataSource.create(MEDIA_DS, {
  	                ID: "ShowSNDFilesTenantsDS",  
		             dataURL: "fileManager.php?show=_sndfilesTenants",
		  operationBindings:[
               {operationType:"remove", dataURL:"fileManager.php?del=_sndfilesTenants", dataProtocol:"postParams"},
               {operationType:"fetch",  dataURL:"fileManager.php?show=_sndfilesTenants", dataProtocol:"postParams"}
         ]
		});	


//  isc.DataSource.create({
//			       ID: "ShowTenantItemsDS",  
//			       dataURL: "ds.php?get=view_tenant_items",
//			       dataFormat: "json", idField: "id",		  
//			       fields:[
//			         {name:"id", title: 'ID', primaryKey:true, type:"integer" },
//			         {name:"item_type", title: 'type', type:"string" },
//			         {name:"name", title: 'Name', type:"string" }
//			       ],
//			     transformRequest : function (dsRequest) {
//	            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
//	             return this.Super("transformRequest", arguments);
//	        }	
//			});	


//// items ponimaesh!!!!!!!!!!!!!!!
	function getTenantDS(DStype){
		 return isc.DataSource.create({
			       ID: "ShowTenant"+DStype+"DS",  
             cacheAllData:false, 
			       dataURL: "ds.php?get=view_tenant_" + DStype,
			       dataFormat: "json", idField: "id",		  
			       fields:[
			         {name:"id", title: 'ID', primaryKey:true, type:"integer" },
			         {name:"item_type", title: 'type', type:"string" },
			         {name:"name", title: 'Name', type:"string" }
			       ],
			     transformRequest : function (dsRequest) {
	            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
	             return this.Super("transformRequest", arguments);
	        }	
			});		
	}		
		

// Show trunks in current Tenant  // 
 isc.DataSource.create({
		  ID: "ShowTrunksDS",  
		  dataURL: "ds.php?get=view_trunks",
		  dataFormat: "json",
		  idField: "id",		  
		  fields:[
		    {name:"id", title: 'ID', primaryKey:true, type:"integer" },		     
		    {name:"name" }
		  ]	
		});	

		
// Tenants MOH list - for tenant  SelectItem
 isc.DataSource.create({
		  ID: "ShowTenantMOHDS",  
		  dataURL: "ds.php?get=view_tenantmoh",
		  dataFormat: "json",
		  idField: "id",		  
		  fields:[
		    {name:"id", title: 'ID', primaryKey:true, type:"integer" },
		    {name:"moh_name", title: 'MOH', type:"string" }
		  ],
		  transformRequest : function (dsRequest) {
            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
             return this.Super("transformRequest", arguments);
        }	
		});	



// Tenants list
 isc.DataSource.create({
		  ID: "ShowTenantsDS",  
		  dataURL: "ds.php?get=view_tenants",
		  dataFormat: "json",
		  idField: "id",		  
		  fields:[
		    {name:"id", title: 'ID', primaryKey:true, type:"integer" },		     
		    {name:"ref_id" },
			 {name:"title", title: 'Name', type:"string" },
		    {name:"is_selected", type:"integer" }
		  ]	
		});	
		
		
// Route Table list		
  isc.DataSource.create({
		  ID: "ShowRouteTablesDS",  
		  dataURL: "ds.php?get=view_route_tables",
		  dataFormat: "json",
		  idField: "id",		  
		  fields:[
		    {name:"id", primaryKey:true, type:"integer" },
			 {name:"name", type:"string" },
		    {name:"is_selected", type:"integer" }
		  ]	
		});

// Tree
  isc.DataSource.create({
		    ID:"mTreeDS",
		    dataURL:"ds.php?get=mtree",    
		    fields:[
		        {name:"name"},
		        {name:"action"},
		        {name:"id", primaryKey:true, type:"integer", title:"TreeNode ID"},
		        {name:"parent_id", foreignKey:"mTreeDS.id", type:"integer", title:"Parent node"}
		    ],
		    transformResponse : function (dsResponse, dsRequest, jsonData) {  
		     var status = isc.XMLTools.selectObjects(jsonData, "/response/status");
             if ( status == 'FAIL' ) {
               isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					isc.say('Error: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );               
             } 
         }
	 });
		 		
		