isc.Time.setDefaultDisplayTimezone("00:00");


/// GLOBAL GUI EVENTS handler ///
function TriggerEvent(EventName){
   console.log('Trigger event: '+ EventName);
	switch(EventName.toLowerCase()){
	  case 'show_extensions':
	      DSInbound.invalidateCache();
	      tblTenants.Refresh();	
         tblExtensions.Refresh();         
			break;
			
    case 'show_adminusers':
         DSAdminusers.invalidateCache();
         tblAdminusers.Refresh();
			break;			
			
     case 'show_cdrs':         
        
         DSCDRs.invalidateCache();
         // Send Limit Rows Data here:
         DSCDRs.transformRequest = function (dsRequest) {             
               dsRequest.data = isc.addProperties({}, dsRequest.data, { 'crit[]' : dsRequest.data['criteria']} ,  {  set_data: {'limit': frm_cdrs_show.getValues()['max_rows']} } );
               console.log( dsRequest.data );
               return this.Super("transformRequest", arguments);
         },   
         tblCDRs.Refresh();

			break;
			
    case 'show_recordings':
       
        htmlContainer4.setContentsURL('tbl_recordings.php');        
        // DSCDRs.invalidateCache();
        // tblRecordings.Refresh();
        // tblRecordings.fetchData({'has_recording':1});
	      //DSCDRs.fetchData({'has_recording':1});
        
			break;			
			
	  case 'reload_campaigns':
     case 'show_campaigns':
	      tblCampaigns.Refresh();
         DSCampaigns.invalidateCache();
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
         
      case 'show_queues':
         tblQueues.Refresh();
         break;
         
     case 'show_conferences':
         tblConferences.Refresh();
         break;
          
     case 'show_ivrmenu':
         tblIVRMenu.Refresh();
         break;    

     case 'reload_blacklist':
     case 'show_blacklist':
         tblBlacklist.Refresh();
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
         
     case 'show_lostcalls':
         tblLostCalls.Refresh();
         break;
         
     case 'reload_timefilters':
         console.log('Reload filters...');
         ShowTenantitemsDS.fetchData();
     
        break;
         
     case 'show_dashboard':
         htmlContainer.setContentsURL('dashboard.php');
        break;    
         
     case 'show_summary_reports':
         htmlContainer2.setContentsURL('dashboard_summary.php');
        break; 

     case 'show_dids_usage':
         htmlContainer3.setContentsURL('dids_usage.php');
        break;   

     case 'show_extenstatus':
         htmlCntExtenStatus.setContentsURL('exten_status.php');
        break;
 
     case 'show_shifts':
         tblShifts.Refresh();
         break;
         
     case 'refresh_tenants':
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
                       "disa"        :"DISA -2nd Dialtone", // Preserve callerid for outbound ???//
		                 "ivrmenu"     :"Auto-Attendant",
		                 "park_announce_rec":"Park with Announce",
                       //"park_announce_tts":"Park & Announce",
                       "featurecode" :"Feature Code",
		                 "number"		 :"Dial Number",
                       "queue"		 :"Queue",
                       "pagegroup"	 :"Page Group",
                       "followme"	 :"Followme",
                       "voicemail"   :"VoiceMail Box",
							      "conference"	 :"Conference",
		                 "repeat"		 :"Repeat",
                       "checkvm"		 :"Check VoiceMail",
                       "dirbyname"	 :"Directory Search",
		                 "play_invalid":"Playback Invalid",
                       "play_rec"    :"Playback Recording",
                       "play_tts"    :"Playback Text",
                       "moh"    :"Play Music On Hold",
		                 "hangup"      :"Hangup Call",
		                 "exec_cmd"    :"Execute Server Command",
		                 "unassigned"  :"Unassigned" 
		               };


var tts_languages = { 
                'ru_RU' : 'Русский(yandex)',
	              'ru-RU' : 'Русский(google)', 
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
 	          'mon' : 'Monday',
 	          'tue' : 'Tuesday',
 	          'wed' : 'Wednesday',
 	          'thu' : 'Thusday',
 	          'fri' : 'Friday',
 	          'sat' : 'Saturday',
 	          'sun' : 'Sunday' }
 	          
var callBlockingMode = { 0:'Send caller to voicemail box',
								 1:'Play "busy" to caller',
								 2:'Play "ringing" to caller, then disconnect',
								 3:'Play "ringing" to caller, then send to voicemail',
								 3:'Play "ringing" to caller, then send to voicemail'
			} 	          
 	          
var DSTransformator = { 
          transformRequest : function (dsRequest) {
          	 if(dsRequest.operationType == 'update' || dsRequest.operationType == 'remove' || dsRequest.operationType == 'add'  ) {
          	 	if (  typeof reloadConf != "undefined" )
          	       reloadConf.animateShow('slide');
          	 }      
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
             if( status == 'LOGOUT' ){
             	isc.logWarn( isc.XMLTools.selectObjects(jsonData, "/response/message") );     
					    isc.say('WARNING: '+ isc.XMLTools.selectObjects(jsonData, "/response/message") );
					    setTimeout(function(){ window.location.replace("/entrance.php"); }, 3000);               
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
    
    
    
   // Admin Users //
   	isc.DataSource.create({
		    ID:"DSAdminusers",
		    dataURL:"ds.php?get=admin_users",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		      {name:"editIcon", title:"", type:"icon" },
            {name:'id', type:"integer",hidden:true, primaryKey:true  },
            {name:"last_login", type:"datetime",canEdit:false,readOnlyDisplay:'static'},            
            {name:"last_login_ip", type:"datetime",canEdit:false,readOnlyDisplay:'static'},
            {name:"status", type:"string", canEdit:false, readOnlyDisplay:'static' },
            {name:"user_sip_exten", type:"string", canEdit:false, readOnlyDisplay:'static' },
		      {name:'user', type:"string"},
          {name:'user_fname', type:"string"},
          {name:'user_lname', type:"string"},
		      {name:"pass", type:"password", title:"Password" },
          {name:'email',type:"string"},
		      {name:'default_tenant_id', type:"select", defaultValue:61,
		      			   title:"Default Tenant",
		      			   editorType: "SelectItem",
		      			   optionDataSource: "ShowTenantsDS", 
		      			   displayField:"title", valueField:"id"  },		      
          {name:"sip_user_id", ID:"UserExtensionPiklist",
                         title:"Tenant extension", 
                         defaultValue: 0,
                         editorType: "SelectItem",
                         optionDataSource: getTenantDS('admin_sip'),
                         //pickListCriteria : { 'current_sip_id': 0 },
                         getPickListFilterCriteria : function () {                           
                           return { 
                              'current_sip_id': ( tblAdminusers.getSelectedRecord() ) ? tblAdminusers.getSelectedRecord()['sip_user_id'] : 0 , 
                              'user_def_tenant_id': ( tblAdminusers.getSelectedRecord() ) ? tblAdminusers.getSelectedRecord()['default_tenant_id'] : 0
                           }
                         },
                         displayField: "name", 
                         valueField: "id" 
               },                
          {name:"role", title:"Role", defaultValue: 2, 
                         editorType: "SelectItem",
                         optionDataSource: getTenantDS('items'),
                         pickListCriteria : { 'item_type': 'role' },
                      displayField:"name", valueField: "id" },       
          {name:"allowed_sections", colSpan:4, width:280,
	             		     multiple:true,
	             		     multipleAppearance:"picklist",
	             		     optionDataSource: getTenantDS('items'),
	             		     displayField:"name", valueField:"id",
								  pickListCriteria: { item_type: 'sections' },
								  separateSpecialValues:true,
								  specialValues: {  1:"All", 0: "None" }  
             },
          {name:'gui_style', title:"GUI Style",  type:"select", 
                valueMap:['Enterprise','EnterpriseBlue','Graphite','Mobile','SilverWave','Simplicity','SmartClient','standard','Tahoe','ToolSkin','ToolSkinNative','TreeFrog' ]
			       },
		    ],
		    //getBindings('admin_users'),
		     operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=admin_users", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=admin_users", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=admin_users", dataProtocol:"postParams", customValueFields:"status"},
               {operationType:"remove",dataURL:"ds.php?del=admin_users", dataProtocol:"postParams"}
          ]} ,
		    DSTransformator);

    // Admin User Logs //
    isc.DataSource.create({
        ID:"DSAdminuserLOG",
        dataURL:"ds.php?get=admin_user_log",     
        dataFormat:"json",
        idField: "id",
        sparseUpdates: true,
        fields:[ {name:'id', type:"integer",hidden:true, primaryKey:true},
                 {name:"user_id", type:"integer", title:" ID", foreignKey:"DSAdminusers.id", hidden:true},
                 {name:'tstamp', type:"datetime"},
                 {name:'method', type:"string",width:50},
                 {name:'from_ip', type:"string",width:90},
                 {name:'request_data',type:"string",width:120},
                 {name:'user_agent',type:"string",width:200}
                ]},
        getBindings('admin_user_log'),
        DSTransformator
    );  

          
   
		 

   // PDialer //
   	isc.DataSource.create({
		    ID:"DSCampaigns",
		    dataURL:"ds.php?get=t_campaigns",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		      {name:"editIcon", title:"", type:"icon" },
            {name:'id', type:"integer", title:" ID", hidden:true, primaryKey:true  },
		      {name:'name',type:"string"},
		      {name:"completed", type:"number",   title:"Completed", width:40,  formatCellValue: "isc.NumberUtil.format(value, ',0') + '%'" },
		      {name:"progress", type:"image", imageWidth: "completed",   imageURLSuffix: ".png",canEdit:false,readOnlyDisplay:'static' },
		      {name:"campaign_status", type:"RadioGroup", valueMap:{'RUNNING':'<span style="color:green"><b>RUNNING<b></span>','PAUSED':'<span style="color:orange"><b>PAUSED<b></span>','STOPPED':'<span style="color:black"><b>STOPED<b></span>' }, defaultValue:"STOPPED",vertical:false},
		      {name:'default_action',type:"select", title:"Send answered call to",valueMap: IVRItemActions, defaultValue:'hangup' },
		      {name:"default_action_data",type:"string",hidden:true},
		      {name:"max_active_calls",type:"integer"},
            {name:"max_call_duration",type:"integer"},
		      {name:"leads_total",type:"integer"},
		      {name:"leads_dialed",type:"integer"},
            {name:"leads_answered",type:"integer"},
          //  {name:"lead_field_names", type:"select", multiple:true,editorType: "SelectItem", width:400,hidden:true,
	       //      		     multipleAppearance:"picklist", 
	       //      		     title:"Lead Field Names",
	       //      		     valueMap:['Phone Number','First Name', 'Last name', 'Address', 'Country','State','Gender','Company name'],
	       //      		     defaultValue:'Phone Number' },
	         {name:"lead_field_names",type: "string",title:'Field labels'},                 
            {name:'description', type:"string"},
            {name:'phone_field_idx',
                   title:'Phone field position', 
                   type:"select", 
                   valueMap:{ 1:'Field #1', 2:'Field #2', 3:'Field #3', 4:'Field #4',5:'Field #5',6:'Field #6',7:'Field #7',8:'Field #8'}
             }
		      
		    ]},
		    getBindings('t_campaigns'),DSTransformator);  

   // PDialer-Leads //
   	isc.DataSource.create({
		    ID:"DSCampaignLeads",
		    dataURL:"ds.php?get=t_campaign_leads",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
            {name:'id', type:"integer", title:" ID", hidden:true, primaryKey:true  },
		      {name:'t_campaign_id',type:"integer",hidden:true},
		      {name:"phone", type:"string"},
		      {name:'result',type:"string"},
		      {name:"status",type:"string"},
		      {name:"last_dialed",type:"date"},
		      {name:"timeout",type:"integer"},
		      {name:"field1",type:"string"},
            {name:"field2",type:"string"}
		    ]},
		    getBindings('t_campaign_leads'),DSTransformator);  

	//  Conferences //
		isc.DataSource.create({
		    ID:"DSConferences",
		    dataURL:"ds.php?get=t_conferences",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[ 
		        {name:"editIcon", title:"", type:"icon" },
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
		       {name:"editIcon", title:"", type:"icon",canEdit:false},
		       {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
		       {name:'exten',type:'string',title:'Code',width:60}, 
		       {name:'description',type:'string'},		       
		       {name:'appdata',type:'select',title:'Application name',
		             editorType: "SelectItem",
			           optionDataSource: getTenantDS('items'),
						     displayField:"name", valueField:"id",
 						     pickListCriteria : { 'item_type': 'feature_code' }
		       }
		     ]
		}, getBindings('t_extensions'), DSTransformator );
		
		
  //  Default Feature Codes  //
		isc.DataSource.create({
		    ID:"DSFeaturesdef",
		    dataURL:"ds.php?get=feature_codes",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		       {name:"editIcon", title:"", type:"icon" },
		       {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
		       {name:'exten',type:'string',title:'DTMF key# ',width:150 },           
           {name:'app',  type:'select',title:'Application', valueMap:['Gosub', 'AGI' ] ,width:150}, 
		       {name:'appdata',type:'string',title:'Application Param', width:200}, 
           {name:'description',type:'string',title:'Short info',width:200 },
           {name:'about', type:'text', editorType:"TextAreaItem", colSpan:"*",width:360,title:'Details'}
		       
		       
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
		        {name:"editIcon", title:"", type:"icon" }, 
		        {name:"id",   type:"integer", title:" ID",  primaryKey:true ,hidden:true},
		        {name:"name", type:"string", title:"Name*"},
		        {name:"description", type:"string"},            
		        {name:"stats_email",type:'string', title:"Mail daily stats to"},
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
		        {name:"editIcon", title:"", type:"icon" },    
		        {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
              {name:"t_ringgroups_id", type:"integer", title:" ID", foreignKey:"DSRinggroups.id", hidden:true},
              {name:"description", type:"string", title:"Hunt lists"},
              {name:"ignore_redirects",type:"checkbox",labelAsTitle:true },
		        {name:"announcement_file", title:"Play announcement", defaultValue:"",
						       editorType: "SelectItem",
			                optionDataSource: getTenantDS('items'),
								 displayField:"name", valueField:"id",
 						       pickListCriteria : { 'item_type': 'play_rec' },
			                specialValues: { 0: "None"} 
			              },
		        {name:"extensions", type:"string",title:"Extensions to Ring",defaultValue:''},
		        {name:"extension_method", type:"select", valueMap:{'exten_based':'Exntesion based(via PBX)','device_based':'Device based(SIP direct)'}, title:"Exten Dial method" },
		        {name:"phone_numbers", type:"string", title:"Other numbers to Ring"},
		        {name:"timeout", type:"string",title:"Ring timeout", defaultValue:60},
              {name:"group_type", type:"string",hidden:true},
		        
		    ], 
		    operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_ringgroup_lists", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_ringgroup_lists", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_ringgroup_lists", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_ringgroup_lists", dataProtocol:"postParams"}
          ]
       }, DSTransformator );      
		    
		    
		//  Pagegroups  //
		isc.DataSource.create({
		    ID:"DSPagegroups",
		    dataURL:"ds.php?get=t_pagegroups",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name:"editIcon", title:"", type:"icon" },
		        {name:"pg_extension", title:"Page Group access number ",type:"string"},
				  {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true },
              {name:"name", type:"string", title:"Name", defaultValue:'Page Group1'  },    
              {name:"full_duplex", type:"checkbox", valueMap:{1:true,0:false},title:"Full duplex audio(instead muted)" },
              {name:"no_beep", type:"checkbox", valueMap:{1:true,0:false}, title:"Disable beep on start" },
		     	  {name:"announce", type:"string", title:"Announce to member before answer", defaultVaule:''}
		    ]}, 	  
		    getBindings('t_pagegroups'),
		    DSTransformator );	  	    

      //  Pagegroups Members //
		isc.DataSource.create({
		    ID:"DSPagegroupMembers",
		    dataURL:"ds.php?get=t_pagegroup_members",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name:"id",  type:"integer", title:" ID", hidden:true,primaryKey:true  },
				  {name:"membername",type:"string", title:"Extension"},
				  {name:"pagegroup_id",type:"integer", foreignKey: "DSPagegroups.id", hidden:true },
				  {name:"interface", type:"string", title:"Device" },
		    ]}, 	  
		    getBindings('t_pagegroup_members'),
		    DSTransformator );	  	    


		    
		//  Queues  //
		isc.DataSource.create({
		    ID:"DSQueues",
		    dataURL:"ds.php?get=t_queues",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name:"editIcon", title:"", type:"icon" },
				    {name:"id",   type:"integer", title:" ID", hidden:true,primaryKey:true  },
            {name:"name", type:"string", title:"Queue name", width:130 },				  
            {name:"qlabel", type:"string", title:"Queue RefID", width:130 },
            {name:"strategy", type:"select", title:"Strategy", width:80, valueMap: queuesStrategies , defaultValue:'random'},
  			    {name:"queue_welcome", type:"string", title:"Queue welcome message"},
			      {name:"announce", type:"string",title:"Play to member before  connect", defaultVaule:''},
            {name:"musiconhold", optionDataSource: "ShowTenantMOHDS", title:"Music on Hold profile", defaultValue:"default",
						   editorType: "SelectItem", 
			         displayField:"moh_name", 
			         valueField: "moh_name" },
           {name:"queue_calltag", type:"string", title:"Append text to caller name(tag)", width:130 },          
			     {name:"timeout", type:"integer", title:"Timeout", defaultValue:30},
			     {name:"retry", type:"integer", title:"Retry timeout,s", defaultValue:5 },
			     {name:"announce_frequency",type:"integer", title:"Position announce interval,s(0=off)", defaultValue:60 },
			     {name:"queue_youarenext", type:"string", title:'Play when first in line', defaultValue:'queue-youarenext'},
			     {name:"wrapuptime",type:"integer", title:"Member delay before next call", defaultValue:'5'},
			     {name:"autopause",type:"select",valueMap:['yes','no'], title:"Autopause failed member",defaultValue:'yes'},
              {name:"maxlen", type:"integer", title:"Max members",defaultValue:10},
              {name:"announce_holdtime", type:"select", valueMap:['yes','no','once'], title:"Say estimated hold time", defaultValue:'once'},
              {name:"joinempty",type:"select", valueMap:['yes','no'], title:'Allow join empty queue',defaultValue:'yes'},
              {name:"leavewhenempty",type:"select", valueMap:{ 'penalty,paused,invalid':'Yes','':"No" }, title:'Force to leave empty queue' , defaultValue:'no'},
              {name:"reportholdtime",type:"select", valueMap:['yes','no'],title:"Report hold time to agent ",defaultValue:'yes'},
              {name:"ringinuse", type:"select", valueMap:['yes','no'],title:"Ring inuse member", defaultValue:'no'},
              {name:"stats_email",type:'string', title:"Email for Queue reports"},
              {name:"default_action", type:"select", title:"When no-answer", valueMap: IVRItemActions, defaultValue:'hangup' ,hidden:true},
              {name:"default_action_data",type:"string", hidden:true},
              {name:"context_script",  type:'select', 
                    editorType: "SelectItem",
                    optionDataSource: getTenantDS('items'),
                    displayField:"name", valueField:"id",
                    pickListCriteria : { 'item_type': 'feature_code' } ,
                    specialValues: { 0: "None"}
              }
		    ]},
		    getBindings('t_queues'),
		    DSTransformator);
  	           
  	                       
  	                       
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
				  {name:"penalty",   type:"string",title:"Priority", hidden:true, defaultValue:'0' },
				  {name:"paused",    type:"select", title:"Paused?", valueMap:{1:'yes',0:'no'}, defaultValue:'no' }
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
	  	  ID: "ShowTenantPagegroupMembers",
	  	   dataURL: "ds.php?get=view_tenant_pagegroupmembers",
				       dataFormat: "json", 
				       idField: "membername",		  
				       fields:[
				         {name:"membername", title: 'Extension', type:"string",primaryKey:true },
				         {name:"pagegroup_id", title: 'ID', foreignKey:"DSPagegroups.id", type:"string",hidden:true },
				         {name:"interface", title: 'Device', type:"string" },
				       ],
				     transformRequest : function (dsRequest) {
		            dsRequest.data = isc.addProperties({}, dsRequest.data, { set_data: dsRequest.data});
		             return this.Super("transformRequest", arguments);
		        },
		       operationBindings:[
                {operationType:"add",dataURL:"ds.php?add=view_tenant_queuesmembers", dataProtocol:"postParams"}
             ]	
		  });		

		
	  isc.DataSource.create({
	  	  ID: "ShowTenantQueuesmembers",
	  	   dataURL: "ds.php?get=view_tenant_queuesmembers",
				       dataFormat: "json", 
				       idField: "membername",		                 
               sparseUpdates: true,  
				       fields:[
				         {name:"membername", title: 'Extension', type:"string",primaryKey:true },
				         {name:"queue_name", title: 'ID', foreignKey:"DSQueues.name", type:"string",hidden:true },
				         {name:"interface", title: 'Device', type:"string" },
				         {name:"paused",    type:"select", hidden:true, canEdit:false, valueMap:{1:'yes',0:'no'}, defaultValue: 'no' },
                 {name:"penalty",   title: 'Priority', type:"string" , defaultValue:'0' },
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
		        {name:"editIcon", title:"", type:"icon" },
				  {name:"id",                type:"integer",title:" ID", hidden:true, primaryKey:true, },
				  {name:"name",              type:"string", title:"Auto-Attendant name",width:140 },
				  
//		        {name:"voicemail_box",     type: "string",title:"Default VoiceMail box" },
	           //{name:"voicemail_box", optionDataSource: getTenantDS('items'), title:"Default VoiceMail box",
	  //           pickListCriteria: {'item_type':'voicemail'}, 
    //	 	       ID: "IVRMenuVMPickList",
		//		       editorType: "SelectItem", 
	  //           displayField:"name", 
	  //           valueField: "name" },
		        
				  {name:"announcement_type", type:"select", title:"Media type",  defaultValue: "recording", valueMap:{'recording' : 'Play Recording','tts_template':'Text2Speech text by template','tts' :'Text to speech','moh':'Play Music On Hold', 'upload' : 'Upload  ..,' } },
				  {name:"announcement_lang", type:"select", valueMap: tts_languages , defaultValue:'en-US_MichaelVoice', title: "Default TTS Language" },
          
				  {name:"announcement",   type:"string",editorType:"TextAreaItem", title:"Welcome message", showPickerIcon: true, width:200,
				             pickerIconSrc: "play_picker.png",
				             pickerIconClick:"IVRplayer.src = 'media/play.php?mode=' + frmIVRMenu.getValues()['announcement_type']  + '&play_text=' + frmIVRMenu.getValues()['announcement'] + '&ttslang=' + frmIVRMenu.getValues()['announcement_lang'] + '&play_dir=tenant';"+
				                             "IVRplayer.Play();" 
				  },
		        {name:"moh_class", optionDataSource: "ShowTenantMOHDS", title:"Default Music On Hold", defaultValue:"default",
              		       ID: "IVRMenuMOHPickList",
						       editorType: "SelectItem", 
			                displayField:"moh_name", 
			                valueField: "moh_name" },
//			     {name:"recordings_lang",type:"string", valueMap: [ 'en','tenant' ], defaultValue:'en', title:'Media folder'},
              {name:"digit_timeout",title:"Digit timeout,ms", type: "integer",defaultValue:5},
              {name:"menu_timeout",title:"How long wait for selection,s", type: "integer",defaultValue:30},
              {name:"delay_before_start",title:"Delay before playing,ms", type: "select", valueMap: [ '0','500','1000'], defaultValue: '0'},
              {name:"ring_while_wait",title:"Ring While Wait",type: "select", valueMap: [ 'yes','no'], defaultValue: 'yes'},
              {name:"allow_dialing_exten",title:"Allow dial local extension",type: "select", valueMap: [ 'yes','no'], defaultValue: 'yes'},
              {name:"allow_dialing_featurecode",title:"Allow feature codes",type: "select", valueMap: [ 'yes','no'], defaultValue: 'yes'},
              {name:"allow_dialing_external",title:"Allow dial external numbers",type: "select", valueMap: [ 'yes','no'], defaultValue: 'no'},
              {name:"description",       type:"string", title:"Description" },
              {name:"context_script",    type:'select',
                    editorType: "SelectItem",
                    optionDataSource: getTenantDS('items'),
                    displayField:"name", valueField:"id",
                    pickListCriteria : { 'item_type': 'feature_code' } ,
                    specialValues: { 0: "None"}
              }
			                
		    ]},
		    getBindings('t_ivrmenu'),
		    DSTransformator);	
		
		
		//  IVRMenuItems  //
		isc.DataSource.create({
		    ID:"DSIVRMenuItems",
		    dataURL:"ds.php?get=t_ivrmenu_items",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,	
		   // sparseUpdates: true,	  
		    fields:[
		        {name:"editIcon", title:"", type:"icon" },
				  {name:"id",                type:"integer", title:" ID", hidden:true, primaryKey:true, },				  
				  {name:"t_ivrmenu_id",      type:"integer", foreignKey: "DSIVRMenu.id", hidden:true },
				  {name:"item_action",		  type:"select", valueMap: IVRItemActions, title:"Action" },
				  {name:"selection",         type:"string", title:"#",   required:true },
				  {name:"item_data",         title:"Destination/Options" },
				  {name:"announcement_type", type:"select", title:"Play type", defaultValue: "no", valueMap:{ 'no':'no', 'recording' : 'Play Recording','tts' :'Text to speech','upload':'Upload ...' } },
				//{name:"announcement_lang", type:"select", valueMap: tts_languages , defaultValue:'en-US_MichaelVoice', title: "Speech Language" },
				  {name:"announcement",      type:"string", title:"Play in menu"}   //, editorType:"TextAreaItem" } 
		    ]},
		    getBindings('t_ivrmenu_items'),
		    DSTransformator);	


//  MOH  //
		isc.DataSource.create({
		    ID:"DSMOH",
		    dataURL:"ds.php?get=t_moh",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,	  
		    fields:[
		        {name:"editIcon", title:"", type:"icon" },
				  {name:"id", primaryKey:true, type:"integer", title:" ID", hidden:true },
				  {name:"name", type:"string", title:"MoH Profile name" },
				  {name:"directory", type: "string",  title:"Directory with files" },
				  //{name:"application", type:"string", title:" Stream URL",hidden:true }, // It is pulated based on mg123 location //				  
				  {name:"mode", type:"select", title:"Media source", defaultValue: "files", valueMap: {'files':'Files from local directory',"custom":'Network Media stream' } },				  
				  {name:"sort", type: "select", valueMap: ["random", "alpha"], defaultValue:'random', title: "Sort files",width:70},
              {name:"network_media_url", type:"string", title:" Stream URL",width:260,hidden:true,  defaultValue:"" },
				  //{name:"application", type:"string", title: "Feed URL"},
		        {name:"format", type: "select", valueMap: {'gsm':"GSM",'ulaw':"G711U", 'ulaw':"G711A", 'mp3':"MP3", 'slin':'WAV, 16bit 8kHz PCM'}, defaultValue:'slin', title:"Files media format"},				  
				  //{name:"announcement", type:"string", title:"Play file between songs", defaultValue:""},
				  {name:"digit", type:"string", title:"Shortcut key", defaultValue:"" }
		    ]},
		    getBindings('t_moh'),
		    DSTransformator
		    );		
		



// OUTBOUND Routes //	
//		isc.DataSource.create({
//		    ID:"DSTimeFilters",
//		    dataURL:"ds.php?get=t_timefilters",		  
//		    dataFormat:"json",
//		    idField: "id",
//		    sparseUpdates: true,
//		    fields:[
//		        {name:"editIcon", title:"", type:"icon" },
//				  {name:"id", primaryKey:true, type:"integer", title:"Filter ID", hidden:true },
//		        {name:"name", type:"string",  title: "Filter Name"},
//		        {name:"description",    type: "string" },	        
//			     {name:"time_period", type:"string"}
//		    ]},
//		    getBindings('t_timefilters'),
//		    DSTransformator);


// DIDs  //
	
		isc.DataSource.create({
		    ID:"DSDIDs",
		    dataURL:"ds.php?get=dids",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name:"editIcon", title:"", type:"icon" },
				  {name:"id", primaryKey:true, type:"integer", title:"DID ID",hidden:true },
		        {name:"DID",    type: "string", title: "Did number" },			
		        {name:"tenant_id", type:"select", title:"Available in Tenant",
				                   		     //multiple:true,		
				                   		     //multipleAppearance:"picklist",
				                   		     editorType: "SelectItem",
				                   		     optionDataSource: "ShowTenantsDS", 
				                   		     displayField:"title", valueField:"id"
			      },     
		        {name:"description",    type: "string", title: "Note" },
		        {name:"assigned_destination", type:"string",title:"Assigned to", canEdit:false}
			     
			     				     
		    ]},
		    getBindings('dids'),
		    DSTransformator );


//INBOUND  Routes //	
		isc.DataSource.create({
		    ID:"DSInbound",
		    dataURL:"ds.php?get=t_inbound",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name:"editIcon", title:"", type:"icon" },
				  {name:"id", primaryKey:true, type:"integer", title:"route ID", hidden:true },
		        {name:"did_id", title: "Inbound DID",                    		        		               
		              optionDataSource: 'DSDIDs', 
		              editorType: "SelectItem",
		              displayField:"DID", valueField:"id" },
            {name:'tag', type:'string', title:'Append CNAME Tag'} ,
		        {name:"is_enabled", type:"select", title: "Is enabled?", valueMap: { 1: 'yes',0: 'no' }, defaultValue: '1' },	      
            {name:"context_script", 
              type:'select', 
              editorType: "SelectItem",
              optionDataSource: getTenantDS('items'),
              displayField:"name", valueField:"id",
              pickListCriteria : { 'item_type': 'feature_code' },
              specialValues: { 0: "None"}
            },
			     {name:"description",type:"string", title: "Description"}
		    ]},
		    getBindings('t_inbound'),
		    DSTransformator );
		

// InBound Rules List//	
		isc.DataSource.create({
		    ID:"DSInboundRules",
		    dataURL:"ds.php?get=t_inbound_rules",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
				  {name:"id",           type:"integer", primaryKey:true, title:"Rule ID", hidden:true },				  
		        {name:"inbound_id",   type:"integer", foreignKey: "DSInbound.id", hidden:true },
		        //{name:"timefilter_id",type:"select", title: "When:", width:60,
		        {name:"week_day_from",type:"select", title: "From", valueMap: week_days, defaultValue:'mon',width:65},
              {name:"week_day_to",  type:"select", title: "Till", valueMap: week_days,  defaultValue:'sun'},               
              {name:"day_time_from",type:"select", editorType: "TimeItem", minuteIncrement: 1,showSecondItem:false, useTextField: false,title: "Start",defaultValue:'00:00' },
              {name:"day_time_to",  type:"select", editorType: "TimeItem", minuteIncrement: 1,showSecondItem:false, useTextField: false,title: "End",  defaultValue:'23:59'},
			     {name:"action",       type:"select", valueMap: IVRItemActions, title:"Route To",width:110 },
              {name:"destination"},
			     {name:"opt1",         type:"string",  title:"Wait for answer,s", defaultValue:60 },
              {name:"opt2",         type:"string",  title:"Prepend to CallerID" },
              {name:"info",         type:"string",  title:"Rule Description " }			     				     
		    ]},
		   getBindings('t_inbound_rules'),
		   DSTransformator );

	
// OUTBOUND Routes //	
		isc.DataSource.create({
		    ID:"DSRoute",
		    dataURL:"ds.php?get=t_route",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		       {name:"editIcon", title:"", type:"icon" },
				   {name:"id", primaryKey:true, type:"integer", title:"route ID", hidden:true },
		       {name:"name", type:"string",  title: "Route name"},
		       {name:"route_enabled",    type: "select", title: "Status", valueMap: { 2: 'Private', 1: 'Public',0: 'Disabled' }, defaultValue: '1' },	        
			     {name:"outbound_callerid", type:"string", title:"Force Outbound CallerID"},
           {name:"context_script", 
              type:'select', 
              editorType: "SelectItem",
              optionDataSource: getTenantDS('items'),
              displayField:"name", valueField:"id",
              pickListCriteria : { 'item_type': 'feature_code' },
              specialValues: { 0: "None"}
            }
		    ]},
		    getBindings('t_route'),
		    DSTransformator );


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
		       ]},
		        getBindings('routes_list'),
		        DSTransformator );			
		
// Trunks //	
		isc.DataSource.create({
		    ID:"DSTrunks",
		    dataURL:"ds.php?get=trunks",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name:"editIcon", title:"", type:"icon" },
				  {name:"id", primaryKey:true, type:"integer", title:"tenant ID", hidden:true },
		        {name:"name", 		type:"string",  title: "Trunk name", required:true },
		        {name:"trunk_type",    type: "select", title: "Direction", valueMap: { 'friend':"Both", 'user':"InBound",'peer':"OutBound" }, defaultValue: 'peer' },
		        {name:"dial_timeout", type:"integer", title:"Dial timeout,s", defaultValue:60},
				  {name:"host",      type:"string", title:"SIP Host / IP ADDR", required:true },
				  {name:"defaultuser", type:"string", title:"Default User "},				  
				  {name:"secret", type:"string", title:"Secret", hidden:true},
				  {name:"context",   type:"select", title:"Context", type: "select", valueMap: ["from-pstn", "from-internal"], defaultValue:'from-pstn'},
				  {name:"status",   type:"select", title:"Trunk enabled", valueMap: {1:"yes",0:"no"}, defaultValue:1 },
              {name:"domain", type:"String", title:"Domain Name",hidden:true},
              {name:"description",   type:"string", title:"Description"},
              {name:"other_options",   type:"text", title:"Other trunk options",hint:"key=value", showHintInField:true },
              {name:"sip_register", type:"string", hint:"username[@domain][:secret[:authuser]]@host[:port]", showHintInField:true, title: "Register => ", hidde:true},
              {name:"trunk_reg_status", type:"string", canEdit:false, readOnlyDisplay:'static' }
              //{name:"inTenants",   type:"String", title:"Available In Tenants", type:"select" }
              //{name:"max_concurrent_calls", type:"integer", title:"Max Concurrent calls", defaultValue:0, visible:false }, // Added while opening form in section
              //{name:"max_call_duration", type:"integer", title:"Max Call duration", defaultValue:0, hidden:false }
		     ]},
		      getBindings('trunks'),
		      DSTransformator);
		
		
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
			          {name:"fullname", type:"string",title:"Directory name"},
				  {name:"operator", type: "checkbox", valueMap: ["yes", "no"], defaultValue:'yes',title:"Enable Operator on Press 0"},
				  {name:"operator_exten", type:"string", title:"Operator Extension", hint:"Tenant Default" , showHintInField:true },
          {name:"hidefromdir", type: "checkbox", valueMap: ["yes", "no"], defaultValue:'no', title:"Hide in Directory" },
				  //{name:"vm_timeout", primaryKey:true, type:"integer", title:" Forward to VoiceMail after", hint:", sec" }, // Moved to FOrwarding tab
		        {name:"sendvoicemail", type: "checkbox", valueMap: ["yes", "no"], defaultValue:'yes', title:"Send Email notification" },
				  {name:"attach", type: "checkbox", valueMap: ["yes", "no"], defaultValue:'yes', title:"Attach Voice file to email" },
		        {name:"delete", type: "checkbox", valueMap: ["yes", "no"], defaultValue:'yes', title: "Delete after sent"},
		        {name:"operator",type:"checkbox", valueMap: ["yes", "no"], defaultValue:'yes', title: "Allow check msg by press 0"}
		    ],	      
          operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_vmusers", dataProtocol:"postParams"},
               {operationType:"update",dataURL:"ds.php?set=t_vmusers", dataProtocol:"postParams"},
               {operationType:"add",   dataURL:"ds.php?add=t_vmusers", dataProtocol:"postParams"},
               {operationType:"remove",dataURL:"ds.php?del=t_vmusers", dataProtocol:"postParams"}
          ]},
		    getBindings('t_vmusers'),
		    DSTransformator);		
		
	
	// Tenants //	
		isc.DataSource.create({
		    ID:"DSTenants",
		    dataURL:"ds.php?get=tenants",		  
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name: "editIcon",canFilter:false, title:"", type:"icon"},
				    {name: "id", ID:"TID",primaryKey:true, type:"integer", title:"ID", hidden:true },
		        {name: "title", type:"string", title: "Name",width:200 },
		        {name: "ref_id", type:"string", title: "Short tenant ID", required:true, width:100 },
		        {name: "outbound_callerid", type:"string", title: "External CallerID",defaultValue:'',width:120 },
              {name: "outbound_callername", type:"string", title: "External Caller Name",defaultValue:'',width:200  },
		        {name: "logo_image", type:"string", title: "Tenant Logo Image", hidden:true  },
              {name: "extensions_count", canFilter:false,type:"integer",width:100,title:"Extensions" },		        
		        {name: "extensions_count_limit",  hidden:true,type:"integer",defaultValue:20,title:"Max Extension", hidden:true },
              {name: "active_calls",canFilter:false, type:"integer", width:100},
              {name: "active_calls_limit", hidden:true, type:"integer",defaultValue:20 },
		        {name: "sounds_language", type:"select",  hidden:true,title:"IVR promts language set", valueMap: ["en", "ru"],  defaultValue:'en' },
		        {name: "general_error_message", type:"string",  hidden:true,title:"On PBX Error, play text:", defaultValue:"Sorry, we cannot complete your call",hidden:true,
              		       showPickerIcon: true, 
				             pickerIconSrc: "play_picker.png",
				             pickerIconClick:"TENANTPlayer.src = 'media/play.php?mode=tts&play_text=' + VMgrTenants.getValues()['general_error_message'] + '&ttslang=' + VMgrTenants.getValues()['default_tts_lang'] + '&play_dir=tenant';"+
				                             "TENANTPlayer.Play();" },
		        {name: "general_invalid_message", type:"string", hidden:true, title:"On Invalid extension, play text:", defaultValue:"Sorry, that extension number is invalid.",hidden:true,
              		       showPickerIcon: true, 
				             pickerIconSrc: "play_picker.png",
				              pickerIconClick:"TENANTPlayer.src = 'media/play.php?mode=tts&play_text=' + VMgrTenants.getValues()['general_invalid_message'] + '&ttslang=' + VMgrTenants.getValues()['default_tts_lang'] + '&play_dir=tenant';"+
				                             "TENANTPlayer.Play();" },
              {name: "parkext", type:"string",  hidden:true,title:"Call Parking Extension", defaultValue:"700" },
              {name: "parkpos", type:"integer",  hidden:true,editorType: "SpinnerItem", writeStackedIcons: false,   min: 1, max: 200, step: 5, title:"Max Parking spaces" , defaultValue:"10"},
              {name: "parkingtime", type: "integer",  hidden:true, editorType: "SpinnerItem", writeStackedIcons: false,   min: 1, max: 600, step: 5, title:"Max Parking time", hint:' sec', defaultValue: "60"},
              {name: "parkfindslot",type:"select",   hidden:true,title:"Park ext selection method", valueMap: ["next", "first"], defaultValue:'next' },
              {name: "parkcomebacktoorigin", hidden:true,type:"select", title:"On timeout", valueMap: { "yes" : "Reconnect to origin", "no" : "Hangup(default)" }, defaultValue:"no" },              
              {name: "parkedmusicclass",  hidden:true,optionDataSource: "ShowTenantMOHDS", title:"Music on Hold", defaultValue:"default",
              		       ID: "TenantMOHPickList",
						       editorType: "SelectItem", 
			                displayField:"moh_name", 
			                valueField: "moh_name" },
			        {name:"enable_status_subscription",  hidden:true,type:"checkbox",valueMap:{1:true,0:false}, title:'Status Subscription' },           
              {name:"default_call_recording", hidden:true,canFilter:false,type:"radioGroup", valueMap: {1:"Always", 2:"Never", 3:"On-Demand" }, defaultValue:1,title:'Call Recording',defaultValue:2 },
              {name:"default_tts_lang",  hidden:true,type:"select", canFilter:false,valueMap: tts_languages , defaultValue:'en-US_MichaelVoice', title: "Default Text2Speech lang" },
	            {name:"encrypt_sip_secrets", hidden:true,type:"checkbox",valueMap:{1:true,0:false},devaultValue:1 },
              {name:"parkext_announce",  hidden:true,type:"select", canFilter:false,editorType:"SelectItem", title:'On Park, page extensions', hidden:true,
                          multiple:true,		
				              multipleAppearance:"picklist",
	             		     optionDataSource: getTenantDS('extensions'),
	             		     displayField:"name", valueField:"device",	             		     
								  //pickListCriteria: { item_type:'extension', tenant_id:  }
								  getPickListFilterCriteria : function () {
					                 return { tenant_id: tblTenants.getSelectedRecord()['id'] };
							    }       
				  },
				  {name:"paging_retry_count", hidden:true,type:"integer", editorType: "SpinnerItem", writeStackedIcons: false,   min: 1, max: 10, step: 1,title:"Page retry count", defaultValue:5},
				  {name:"paging_interval",  hidden:true,title:"Paging retry timeout", hint:" s",  defaultValue:30, editorType: "SpinnerItem", writeStackedIcons: false,
                  min: 1, max: 90, step: 5},
				  {name:"parked_ontimeout_ivr", hidden:true,type:"select", canFilter:false,	 title:"If no answer, call Autoattendant", defaultValue:"0",
						       editorType: "SelectItem",
			                optionDataSource: getTenantDS('items'),
								 displayField:"name", valueField:"id",
 						       pickListCriteria : { 'item_type': 'ivrmenu' },
			                specialValues: { 0: "Default - To original(if exists)"} 		     
			     },
		           {name:'vm_operator_exten', type:'string' },
			   {name:'shabash', title:'HR Work finish', type:"select",  hidden:true, valueMap: ["06:00","15:00", "16:00","17:00","18:00","19:00","20:00","21:00","22:00" ],  defaultValue:'18:00',hint:'Workers Auto-LogOFF )HR)' },
		           {name:'cdr_rows',canFilter:false, type:"integer"},
			   {name:'archivate_cdrs_after', type:"integer", hint:' days',defaultValue:90 ,width:80, validators:[ {type:"integerRange", min:5, max:500} ] },
         {name:"smtp_port",hint:"default",showHintInField:true},
         {name:"smtp_host",hint:"default",showHintInField:true},
         {name:"smtp_user",hint:"default",showHintInField:true},
         {name:"smtp_password", type:"password",hint:"default",showHintInField:true},
         {name:"smtp_from",hint:"default",showHintInField:true},
         {name:"smtp_from_name",hint:"default",showHintInField:true}
              
		    ]},
		    getBindings('tenants'),
		    DSTransformator
		 );
		
		
		
    isc.DataSource.create({
       ID:"DSShifts",        
       dataURL:"ds.php?get=t_shifts",
       dataFormat:"json",
       idField: "id",
       sparseUpdates: true,
       fields:[
           {name:"editIcon", title:"", type:"icon" },   
           {name:"sendIcon", title:"", type:"icon" },
           {name:"id",   type:"integer", title:" ID", hidden:true, primaryKey:true  },
           {name:"tenant_id", type:"integer", title:" ID", foreignKey:"DSTenants.id", hidden:true, width:1},
           {name:"send_to_email",type:"string",title:'On shift end, send report (in pdf) to Email'},
           {name:"shift_start",width:180,type:"time",timeFormatter: 'toShort24HourTime', useTextField: false},
           {name:"shift_end",width:180,type:"time",timeFormatter: 'toShort24HourTime',useTextField: false}
       ]},
        getBindings('t_shifts'),
        DSTransformator
    );
		
		
		
		
// Extensions //
		isc.DataSource.create({
		    ID:"DSExtensions",
		    dataURL:"ds.php?get=t_sip_users",
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		          {name: "editIcon", title:"", type:"icon",canFilter:false},
		          {name: "id", title: "Extension ID", primaryKey:true, hidden:true},
                {name: "extension", type:'string',title: "Exten",required:true, width:80,validators:[{ type:'isUnique'}] },
                {name: "name", title: "SIP User",    hint:"(autogenerated)",showHintInField:true, width:120},
				      	// {name: "context", title: "DialPlan profile",canEdit:false },
                {name: "type", type: "select", valueMap: ["friend", "peer","user"],defaultValue:'friend',hidden:true},
                {name: "reg_status", type:"string",width:80,canEdit:false, readOnlyDisplay:'static' },
                {name: "first_name", type:'string',width:150},
                {name: "last_name", type:"string",hidden:true},
                {name: "option_timeout", title:'Ring,s', type:"string",width:60},
                {name: "option_forwarding",title:'Forwarding', type:"string"},
                {name: "transport", type:"select",title:'Allowed Protocols',hidden:true,valueMap: ['udp','udp,tls','udp,tls,wss'],defaultValue:'udp,wss'},
                {name: "encryption", type:"select",title:"WEB Phone",valueMap:["yes","no"],defaultValue:"no", width:80},
                {name: "avpf",     type:"select", hidden:true, title:" Enable WEBRTC ",valueMap:['yes','no'],defaultValue:"no"},
                {name: "secret", title: "SIP Password",hint:"(autogenerated)",showHintInField:true, hidden:true },
                {name: "email", type: "string", title: "Default Email"},
                {name: "email_pager", type: "string", title: "Alternative Email",hidden:true},
                {name: "dtmfmode", type: "select",hidden:true, valueMap: ["auto", "rfc2833","inband","info"], title: "DTMF",defaultValue:"rfc2833" },
                {name: "allow", type:"string",hidden:true,title:"Voice Codecs",defaultValue:"ulaw,gsm,h263",width:120},
                {name: "nat",   title: "NAT",valueMap: ["yes", "no","comedia","force_rport","auto_comedia","auto_force_rport"], defaultValue:"force_rport,comedia",hidden:true},
                {name: "enable_mwi",type: "checkbox",hidden:true, valueMap: ["yes", "no"], defaultValue:'yes', title: "MWI"},                
                {name: "qualify", type: "checkbox",hidden:true, valueMap: ["yes", "no"], defaultValue:'yes', title: "Line Status Monitor(qualify)",  hidden:true },
                {name: "videosupport",type: "checkbox", hidden:true, valueMap: ["yes", "no"], defaultValue:'yes', title: "Enable Video"},
                {name: "other_options", type: "string", title: "Other Extension options", hint:"key=value", showHintInField:true,hidden:true },
                //{name: "outbound_route", type: "integer" }
                {name:"outbound_route",canFilter:false, type:"select", title:"Outbound calls:",width:100,
				                   		     //multiple:true,		
				                   		     //multipleAppearance:"picklist",
				                   		     editorType: "SelectItem",
				                   		     optionDataSource: "ShowRouteTablesDS", 
				                   		     displayField:"name", valueField:"id",
				                   		     defaultValue:1
			        },
			        {name: "mohinterpret", canFilter:false, title:"MOH", defaultValue:"default", hidden:true,
                         editorType: "SelectItem",
                         optionDataSource: getTenantDS('items'),
                         pickListCriteria : { 'item_type': 'moh' },
			                displayField:"name", valueField: "name"  
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
			         {name:"internal_callerid",type:"string",defaultValue:'',hidden:true },
			         {name:"internal_callername",type:"string", defaultValue:'',hidden:true },
               {name: "click2dial_enabled", type: "checkbox", valueMap:{1:1,0:0}, defaultValue:0 ,hidden:true},
               {name: "click2dial_url", type: "string" ,hidden:true},
               {name: "click2dial_exten", type: "string" ,hidden:true},
               {name: "click2talk_enabled", type: "checkbox", valueMap:{1:1,0:0}, defaultValue:0 ,hidden:true},
               {name: "click2talk_options", type: "string",hidden:true},
               {name: "crm_enabled", type: "checkbox", valueMap:{1:1,0:0}, defaultValue:0 ,hidden:true},
               {name: "crm_username", type: "string",hidden:true},
               {name: "crm_password", type: "string",hidden:true}
		    ]},
		    getBindings('t_sip_users'),
		    DSTransformator)
		
		
// UserOptions :
		isc.DataSource.create({
		    ID:"DSUserOptions",
		    dataURL:"ds.php?get=t_user_options",
		    dataFormat:"json",
		    idField: "id",
		    sparseUpdates: true,
		    fields:[
		        {name:"id", primaryKey:true, type:"integer", hidden:true },
            {name:"name",type:"string"},
            {name:"t_sip_user_id",type:"integer", hidden:true, foreignKey:"DSExtensions.id", },         
		        {name:"call_blocking",type:"radioGroup",valueMap: {1:"Block", 0:"Allow"}, defaultValue:0 , title:'Inbound calls',
		           change:function(v,i,val){ tblUserBlockList.fetchData({'allowed': val, 't_sip_user_id': tblExtensions.getSelectedRecord()['id'] });
		                                     Ext_CID_list.setContents('<b>CallerID ' + ((val == 0)?'Block':'Allowed') + ' List<b>');
                                           tblUserBlockList.getField('allowed').defaultValue = val;  
		                                   }
		        },
		        {name:"call_blocking_anonym",type:"radioGroup",valueMap: { 1:"Block",0:"Allow"}, defaultValue:1 , title:'Anonymous calls'},
		        {name:"call_blocking_mode",type:"select",valueMap: callBlockingMode, defaultValue:0,title:'Call block method' },
		        {name:"call_screening",type:"radioGroup",valueMap: {1:"Enable",0:"Disable"}, defaultValue:0, title:'Call Screening',
		          change:function(view,i,new_val){   
		          									 tblUserScreenList.fetchData({'screened': ((new_val == 0)?1:0) , 't_sip_user_id': tblExtensions.getSelectedRecord()['id'] });
		                                     Ext_SCREEN_list.setContents( ((new_val == 0)?'<b>ALWAYS':'<b>NEVER') + ' Screen For this CallerID</b>');
                                           tblUserScreenList.getField('screened').defaultValue = ((new_val == 0)?1:0);  
		                                  }
		        },                          
              {name:"call_screening_ask_cid",   type:"radioGroup",valueMap: {0:"Never", 1:"Always",2:"When empty"}, defaultValue:1,title:'If screened, ask Name' },
              {name:"call_screening_ask_cname", type:"radioGroup",valueMap: {0:"Never", 1:"Always",2:"When empty"}, defaultValue:2,title:'If screened, ask CallerID' },

              {name:"call_forwarding", type:"string"},
	            {name:"dnd", type:"integer",title:"Do Not Disturb ",  type:"radioGroup",valueMap: {0:"Off", 1:"On"}, defaultValue:0, vertical:false },
              {name:"call_forward_onbusy", type:"string"},
              {name:"call_forward_timeout", type:"integer",defaultValue:20},
              {name:"call_forward_tag", type:"string",title:"Tag forwarded call ", hint:"(prefix to Caller Name)",showHintInField:true},
              {name:"call_forward_preserve_cid", type:"checkbox", valueMap:{1:true,0:false},title:"Preserve Original caller ID",defaultValue:0 },
              
              {name:"call_followme_status", type:"integer",title:"FollowMe ",  type:"radioGroup",valueMap: {0:"Off", 1:"On"}, defaultValue:0 },
              {name:"call_followme_options", type:"select",title:"FollowMe options", defaultValue:'',title:"FollowMe options",multiple:true,	
                	valueMap: {'s':"Play Status Msg", 'a':"Do Screening",'n':"Play Unreachable Msg"},
				      multipleAppearance:"picklist",
				      editorType: "SelectItem" },
              {name:"pls_hold_prompt", type:"select", valueMap: { 'silence/1':'no', 'beep':'beep','followme/pls-hold-while-try':'pls-hold-while-try'}, defaultValue:'silence/1', title:"Notify Caller<br>about Forwarding" },
              {name:"call_followme_ontimeout", type:"select", title:"When no-answer", valueMap: IVRItemActions, defaultValue:'hangup' },
		        {name:"call_followme_ontimeout_var",type:"string"},
            {name:"call_waiting", type:"checkbox", valueMap:{1:true,0:false}, defaultValue:1, title:'Call Waiting',labelAsTitle:true },
		        {name:"call_recording", type:"radioGroup", valueMap: {0:"PBX Default", 1:"Always", 2:"Never", 3:"On Demand" }, vertical:false, defaultValue:0,title:'Call Recording' }
            
		    ]},
              
		    getBindings('t_user_options'),
		    DSTransformator);   

// User Devices (where it logged in)
     isc.DataSource.create({
        ID:"DSUserDevices",
        dataURL:"ds.php?get=t_sip_user_devices",
        dataFormat:"json", 
        idField: "id",
        sparseUpdates: true,
        fields:[
            {name:"id", primaryKey:true, type:"integer", hidden:true },
            {name:"t_sip_user_id", type:"integer", hidden:false, foreignKey:"DSExtensions.id", width:1},         
            //{name:"exten",type:"string" },
            {name:"exten",type:"select", editorType:"Select", title:'Extension',
                   width:'100%',                                              
                  optionDataSource: getTenantDS('items'),
                  displayField:"name", valueField:"name",
                  pickListCriteria: { item_type: 'extension' }       
            }
           // {name:"device",type:"string" },
           // {name:"time_period", type:"string", defaultValue: 0 }            
        ]},
        getBindings('t_sip_user_devices'),
        DSTransformator);           
		    
// User CallerID BlockList
     isc.DataSource.create({
		    ID:"DSUserBlockList",
		    dataURL:"ds.php?get=t_user_blocklist",
		    dataFormat:"json", idField: "id", sparseUpdates: true,
		    fields:[
		        {name:"id", primaryKey:true, type:"integer", hidden:true },
		        {name:"callerid", type:"string", title:'Number'},
		        {name:"allowed",type:"radioGroup",valueMap: { 0:"Block",1:"Allow"}, defaultValue:0 , title:'mode', vertical:false},
		        {name:"hit_counter",type:"integer",canEdit:false,readOnlyDisplay:'static' },
		        {name:"t_sip_user_id",type:"integer", foreignKey:"DSExtensions.id", width:1}
		    ]},
		    getBindings('t_user_blocklist'),
		    DSTransformator);
		    
 // User CallerID BlockList
     isc.DataSource.create({
		    ID:"DSUserScreenList",
		    dataURL:"ds.php?get=t_user_screening",
		    dataFormat:"json", idField: "id", sparseUpdates: true,
		    fields:[
		        {name:"id", primaryKey:true, type:"integer", hidden:true },
		        {name:"callerid",type:"string", title:'Number'},
		        {name:"screened",type:"radioGroup",valueMap: { 0:"No-screen",1:"Screened"}, defaultValue:0 , title:'action', vertical:false,width:200},
		        {name:"hit_counter",type:"integer",canEdit:false,readOnlyDisplay:'static' },
		        {name:"t_sip_user_id",type:"integer", foreignKey:"DSExtensions.id", width:1}
		    ]},
		    getBindings('t_user_screening'),
		    DSTransformator);
		    
 // User CallerID FollowMe List
     isc.DataSource.create({
		    ID:"DSUserFollowMeList",
		    dataURL:"ds.php?get=t_user_followme",
		    dataFormat:"json", idField: "id", sparseUpdates: true,
		    fields:[
		        {name:"id", primaryKey:true, type:"integer", hidden:true },
		        {name:"t_sip_user_id",type:"integer", foreignKey:"DSExtensions.id", width:1},
		        {name:"name",type:"string",width:1},  // Must be same as t_user_options.name for followmerealtime
		        {name:"phonenumber",type:"string", title:'Number'},
		        {name:"timeout",type:"integer", title:'Ring Timeout',defaultValue:20}
		        //{name:"ordinal",type:"integer",canEdit:false,readOnlyDisplay:'static' },
		    ]},
		    getBindings('t_user_followme'),
		    DSTransformator);
 
 
 		     
 // CDRs Detailed
     isc.DataSource.create({
		    ID:"DSLostCalls",
		    dataURL:"ds.php?get=t_lostcalls_view",
		    dataFormat:"json", idField: "id", sparseUpdates: true,
		    fields:[
		          {name:"cdate",type:"string",title:"Date",align:"left",width:100},
              {name:"call_group_name", type:"string",width:200},
              {name:"total_calls", type:"string",width:100},
              {name:"answered", type:"string",width:100},
              {name:"lost_calls", type:"string",width:100},              
              {name:"lost_percent", type:"string",width:100}
		    ],
		    operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_lostcalls_view", dataProtocol:"postParams" }
	       ]},
		   getBindings('t_lostcalls_view'),	
		    DSTransformator);
 				  		        

// Blacklist IP 
     isc.DataSource.create({
        ID:"DSBlacklist",
        dataURL:"ds.php?get=blacklist",
        dataFormat:"json", idField: "id", sparseUpdates: true,
        fields:[
             {name: "editIcon", title:"", type:"icon",canFilter:false},        
             {name:"id", primaryKey:true, type:"integer", hidden:true },
             {name:"tstamp",type:"date",title:"Block date", format:"MMM d, yyyy hh:mm:ss",align:"left",hidden:true},
             {name:"ip", type:"string"},
             {name:"ip_info", type:"string"},
             {name:"description", type:"string"},
             {name:"hit_count", type:"integer",hidden:true},
             {name:"last_hit", type:"integer",hidden:true},
             {name:"block_sip_registration", type:"checkbox",labelAsTitle:true,valueMap:{1:1,0:0}},            
             {name:"redirect_to", type:"string", title:"WEB redirect to",default:'http://webkay.robinlinus.com/'},
        ],
        operationBindings:[
            {operationType:"fetch", dataURL:"ds.php?get=blacklist", dataProtocol:"postParams" }
         ]},
       getBindings('blacklist'), 
        DSTransformator);
		     


 // CDRs Detailed
     isc.DataSource.create({
		    ID:"DSCDRs",
		    dataURL:"ds.php?get=t_cdrs_view",
		    dataFormat:"json", idField: "id", sparseUpdates: true,
		    fields:[
		        {name:"id", primaryKey:true, type:"integer", hidden:true },
		        {name:"calldate",type:"date",title:"Call date", format:"yyyy-MM-dd HH:mm:ss",align:"left"},
              {name:"clid", type:"string",title:"Caller ID"},
              {name:"src",  type:"string",title:"Source"},              
				  {name:"dst",  type:"string",title:"Destination"},
				  {name:"dcontext",  type:"string",hidden:true},
				  {name:"channel",  type:"string",hidden:true},
				  {name:"dstchannel",  type:"string"},
				  {name:"lastapp",  type:"string",hidden:true},
				  {name:"lastdata",  type:"string",hidden:true},
				  {name:"duration",  type:"integer", title:"Total duration,s"},
				  {name:"billsec",  type:"integer", title:"Time,s "},          
				  {name:"disposition",  type:"string"},
				  {name:"amaflags",  type:"string", hidden:true},
				  {name:"accountcode",  type:"string", hidden:true},
				  {name:"uniqueid",  type:"string", hidden:true},
				  {name:"userfield",  type:"string", hidden:true},
				  {name:"did", type:"string", hiden:true,title:"DID"},
				  {name:"from_ip", type:"string", hiden:true,title:"SIP Source IP"},
              {name:"recvip",    type:"string", width:100, hiden:true,title:"Received From IP"},
              {name:"rtpsource", type:"string", width:100,hiden:true,title:"Local RTP Address"}, 
              {name:"rtpdest", type:"string", hiden:true,title:"Remote RTP Address"},
              {name:"peername", type:"string", hiden:true,title:"Line"},
              {name:"title", type:"string", title:"Company"},
              {name:"service_status", type:"string"},
              {name:"direction",type:"string"}
		    ],
		    operationBindings:[
	      		{operationType:"fetch", dataURL:"ds.php?get=t_cdrs_view", dataProtocol:"postParams" }
	       ]},
		   // getBindings('t_cdrs'),		
		    DSTransformator);
 
   	
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
                autoCacheAllData: false, 
			       dataURL: "ds.php?get=view_tenant_" + DStype,
			       dataFormat: "json",
					 idField: "id",		  
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
		 		
		
