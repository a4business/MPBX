
// FormConstructor:




function getForm( object_name, isNew) {
	
	  var LGDefault = {
	       backgroundColor: "white",         
          padding:8,
		    height:'*',
		    border: "none",
		    autoDraw: false,		    
		    alternateRecordStyles:true, 
		    showAllRecords:true,
		    autoFetchData: true,
		    autoFitFieldWidths: true 
   }
	
	 var frm_default_options =  {  
	 					ID: 'frm'+object_name,
                  autoDraw: false,
                  width:"100%", height:"100%",
 						wrapItemTitles:false,
 						dataSource: "DS" + object_name                   
         };

var menuInfo ="<b>User Menu</b>:<br>\
&nbsp;*1=toggle_mute<br>\
&nbsp;1=toggle_mute<br>\
&nbsp;*4=decrease_listening_volume<br>\
&nbsp;4=decrease_listening_volume<br>\
&nbsp;*6=increase_listening_volume<br>\
&nbsp;6=increase_listening_volume<br>\
&nbsp;*7=decrease_talking_volume<br>\
&nbsp;7=decrease_talking_volume<br>\
&nbsp;*8=leave_conference<br>\
&nbsp;8=leave_conferencev\
&nbsp;*9=increase_talking_volume<br>\
&nbsp;9=increase_talking_volume<br>\
<br>\
<b>Admin Menu:</b><br>\
&nbsp;*1=toggle_mute<br>\
&nbsp;1=toggle_mute<br>\
&nbsp;*2=admin_toggle_conference_lock ; only applied to admin users<br>\
&nbsp;2=admin_toggle_conference_lock  ; only applied to admin users<br>\
&nbsp;*3=admin_kick_last       ; only applied to admin users<br>\
&nbsp;3=admin_kick_last        ; only applied to admin users<br>\
&nbsp;*4=decrease_listening_volume<br>\
&nbsp;4=decrease_listening_volume<br>\
&nbsp;*6=increase_listening_volume<br>\
&nbsp;6=increase_listening_volume<br>\
&nbsp;*7=decrease_talking_volume<br>\
&nbsp;7=decrease_talking_volume<br>\
&nbsp;*8=no_op<br>\
&nbsp;8=no_op<br>\
&nbsp;*9=increase_talking_volume<br>\
&nbsp;9=increase_talking_volume" ;
         
// BIG Case of form constructore /
   
  switch ( object_name ){
  	
  	
  	case 'Campaigns' :
  	    isc.ValuesManager.create({ ID: 'frm'+object_name, dataSource: "DS" + object_name  });
		 myForm = isc.DynamicForm.create({ height:"100%",valuesManager:'frm'+object_name,  wrapItemTitles:false,
		 									 numCols:4,		       	             
                                  fields: [
                                    {name:"campaign_status" },
	   										{name:"name",startRow:true},  {name:"leads_total",canEdit:false,editorType:"StaticText",width:30},
	   										{name:"description"},         {name:"leads_dialed",canEdit:false,editorType:"StaticText",width:30},
	   										{name:"max_active_calls"},    {name:"leads_answered",canEdit:false,editorType:"StaticText",width:30},
	   										{name:'phone_field_idx'},	
	   										{name:"max_call_duration"}, 
                                    {name:'lead_field_names',  colSpan:"*", width:"473",startRow:true,endRow:true}
	   									//	{name:'lead_field_names',   colSpan:"*",
	   									//	  dataArrived:  function(f,l,rows){  
 	   									//	                  if (!isNew )  this.setValue(JSON.parse(this.getValue()));}
 	   									//	                }
 	   									     											   										
                                 ]}
                             );
                                    
            UploadBtn = isNew ?  isc.HTMLFlow.create({ contents:"Save new Campaign first, reopen it and upload leads "}) : getMediaUploader( tblCampaigns.getSelectedRecord()['id'], "Leads", "CSVManager.php" );  
                             
            myStrip =  isc.ToolStrip.create({
				             height:30,  width:"100%", align: "right",membersAlign: "right",
				                members: [
	                             isc.HStack.create({ membersMargin:10, height:25, padding:4, align:'center',  autoDraw:false,         
		                                  members:[
		                                    // Control
		                                     isc.DynamicForm.create({
								        	            valuesManager: 'frm'+object_name,
								        	            autoDraw: false,
								        	            wrapItemTitles:false,
						                           fields:[{ name:'default_action', redrawOnChange:true,
						                                    change: function(viewer,item,value){
						                           	                CampDefActData.setFields(getDynamicField(value,'default_action_data')); 
						                           	                CampDefActData.setValue('default_action_data',''); 
						                           	              }
						                           	     }]
	                                         }),
	                                       // Data  
		                                    isc.DynamicForm.create({ID:'CampDefActData',
		                                       valuesManager: 'frm'+object_name,
		                                       autoDraw:false, wrapItemTitles:false,
		                                       fields:[{name:'default_action_data'}]              
		                                     })
		                                  ]
		                                })  // Default Action Control End //
		                       ]
		                     });
		      CampDefActData.setFields(getDynamicField( 'conference', 'default_action_data'));                             
            theForm = isc.VLayout.create({ width:"100%",height:"100%", members:[ myForm, UploadBtn, myStrip ] });                    
                                
         break;
  	  	 
    case 'Trunks' :
		 theForm = isc.DynamicForm.create(frm_default_options, 		 	
		 									{ numCols: 4 },		       	             
                                            {fields: [
		   										{name:"name", hint:'Letters or digits dot or dash',showHintInField:true},
		   										{name:"trunk_type"},{name:"host" },{name:"dial_timeout"},{name:"status"},
		   										{name:"context"},{ name:"defaultuser" },{name:"secret"},	   										
						  							{name:"domain"}, {name:"description"},
						  							{name:"sip_register",colSpan:"*", width:490},
						  							{name:"trunk_reg_status",colSpan:"*", width:490, showIf: "form.getValue('sip_register') != ''"},
						  							{name:"other_options", editorType:"TextAreaItem", colSpan:"*",numCols: 4, width:490 },
		                                 //{defaultValue: "Trunk Limits", type:"section", sectionExpanded:false, width:"100%",colSpan:"*",
		                                 //itemIds: ["inTenants","max_concurrent_calls", "max_call_duration" ] },
		                                        {name:"max_concurrent_calls", type:"integer", title:"Max Concurrent calls", defaultValue:0},
				    				            {name:"max_call_duration", type:"integer", title:"Max Call duration", defaultValue:0},
				                                {name:"inTenants", type:"select", title:"Available in Tenants:", 
						                             ID: 'inTenField',
						                             colSpan:5, width:500,
						                   		     multiple:true,		
						                   		     multipleAppearance:"picklist",
						                   		     editorType: "SelectItem",
						                   		     optionDataSource: "ShowTenantsDS", 
						                   		     displayField:"title", valueField:"id"
				                   		        }			                   		   
                                           ]}
                             );   
   		       break;
   		       
   		          		          
		    case 'CDRs' :
          case 'Recordings' :
               DSRecordings = DSCDRs;
		         theForm = isc.DynamicForm.create(frm_default_options, 
		 									{ width:'90%',numCols: 2,		       	             
			                                  fields: [
				   								{name:"calldate",   canEdit:false,editorType:"StaticText"}, 
				   								{name:"source_name",canEdit:false,editorType:"StaticText"},
												{name:"uniqueid",   canEdit:false,editorType:"StaticText"},
						                        {name:"src",        canEdit:false,editorType:"StaticText"},
						                   		{name:"dst",        canEdit:false,editorType:"StaticText"},
											    {name:"did",        canEdit:false,editorType:"StaticText"},
			                                    {name:"duration",   canEdit:false,editorType:"StaticText"},                                    
			                                    {name:"talk_time_str",canEdit:false,editorType:"StaticText"},
			                                    {name:"billsec",    canEdit:false,editorType:"StaticText"},
			                                    {name:"lastdata",   canEdit:false,editorType:"StaticText"},
			                                    {name:"channel",    canEdit:false,editorType:"StaticText"},
			                                    {name:"lastapp",    canEdit:false,editorType:"StaticText"},
			                                    {name:"dstchannel", canEdit:false,editorType:"StaticText"},
			                                    {name:"dcontext",   canEdit:false,editorType:"StaticText"},
			                                    {name:"accountcode",canEdit:false,editorType:"StaticText"},
			                                    {name:"recvip",     canEdit:false,editorType:"StaticText"},
			                                    {name:"rtpsource",  canEdit:false,editorType:"StaticText"},
			                                    {name:"rtpdest",    canEdit:false,editorType:"StaticText"},
			                                    {name:"tenant_name",canEdit:false,editorType:"StaticText"}
			                                  ]
                                });                     
		          break;
		          
		                   
		    case 'DIDs' :
		         theForm = isc.DynamicForm.create(frm_default_options, 
		 									{ numCols: 2,		       	             
                                  fields: [
	   										{name:"DID"}, 
	   										{name:"description"},
			                           {name:"tenant_id"},
			                   		   {name:"assigned_destination"}
                                  ]
                                });   
		          break;
		   
		   case 'Conferences' :       
		          isc.ValuesManager.create({ ID: 'frm'+object_name, dataSource: "DS" + object_name  }); 
		         
		         theForm = isc.TabSet.create({
								        ID: "theConfTabs",
								        width:"100%", height:"100%",
								        tabs: [
								         { title: "Conference",  
								            pane: isc.HLayout.create({ align: "center",padding:4, membersMargin: 6, autoDraw:false, 
									               members:[ 						             
											           isc.DynamicForm.create({
											          	 numCols: 2, wrapItemTitles:false,
											          	 autoDraw: false,                  
											          	 width:"60%",
											          	 valuesManager: 'frm'+object_name,									          	  
												          fields: [
												                     {name:'conference'},{name:'description'},
												                     {name:"maxusers"},
												            			{name:'announcement_file', icons: [{ click: function(form){ CONFplayer.src = 'media/play.php?mode=recording&play_text=' + form.getValue('announcement_file') + '&play_dir=tenant'; }, src: "play_picker.png", }] },                            
						                                       {name:"password"},
						                                       {name:"admin_password"},
												                     {name:"users",  dataArrived:  function(f,l,rows){       	     
															           	    if (!isNew )
															           	         this.setValue(JSON.parse(this.getValue()));
															           	 }
															           	} 
												              ]
											          	 })
													  	]
											      })	  		 
								        },
								        { title: "Options", 
								           pane: isc.DynamicForm.create({
		                                 wrapItemTitles:false,
									            saveOnEnter:true,numCols: 2, 
									            valuesManager: 'frm'+object_name,
											      fields:[
												        {name:"enable_moh"},
										              {name:"moh_class"},												   
													     //{name:"name"},
										              //{name:"options"},
												        {name:"enable_menu",  itemHoverHTML : function(){ return menuInfo;} },
										              {name:"enable_recording"},
										              {name:"announce_count"},
										              {name:"announce_join"},										          
										              {name:"wait_marked"},
										              {name:"end_marked"},       
										              {name:"detect_talker"}
											      ]													            
									         })		          
									     } 
									    ]
								  });    
								  
					 //  theForm.setValue('users', ( record.extensions != 'null' && record.extensions != '' && record.extensions != 'undefined' ) ? JSON.parse(record.extensions) :'' );				        
						  
				    return theForm;		     
                break;
		          
		    case 'Ringgroups':       
                 isc.ValuesManager.create({ ID: 'frm'+object_name, dataSource: "DS" + object_name  });
                 theRGForm = isc.DynamicForm.create({
                                    autoDraw: false,width:"100%", 
                                    height:50, numCols: 4,
                                    wrapItemTitles:false,
                                    valuesManager: 'frm'+object_name,
                                    fields:[ 
                                        {name:'id',hidden:true},
                                        {name:'name'},
                                        {name:'announcement_file',  icons: [{ src: "play_picker.png",
																            click:  function(form){ RGplayer.src = 'media/play.php?mode=recording&play_text=' + form.getValue('announcement_file') + '&play_dir=tenant'; }
																         }]    
                                        },
                                        {name:'stats_email',width:183,
                                           icons: [{ src: "send_email.png", title: 'Send now',width:30,height:26,
																     click:  function(form){
																     	          var report_name = form.getValue('name') + '-' + form.getValue('id');
																     	          console.log('Sending call stats report "' + report_name + '"  to: ' + form.getValue('stats_email'));
																     	          $.post('jaxer.php',{'get_stats': 1, 'to_email': form.getValue('stats_email'), 'report_name': report_name },
																     	                function (data) {
														                              isc.say(data.error);
														                            },
														                     'json');
																     	       }
														         }]    
                                         }
                                       // {name:'description'} 
                                    ]     
                                });
                                   
                // Ringgroup Add HuntList Buttons //
                 theButtons = isc.HStack.create({ membersMargin:10, height:25, padding:4, align:'center',  autoDraw:false,
                          members:[
                              isc.IButton.create({ title:"Add Extensions",icon:"[SKIN]actions/add.png", click: "frmRinggroups.saveData(function(response,data,request){ tblRinggroups.selectRecord(data);RinggroupListGrid.startEditingNew({'t_ringgroups_id':data.id,'group_type':'extension' });  })"     }),
                              isc.IButton.create({ title:"Add Queue",     icon:"[SKIN]actions/add.png", click: "frmRinggroups.saveData(function(response,data,request){ tblRinggroups.selectRecord(data);RinggroupListGrid.startEditingNew({'t_ringgroups_id':data.id,'group_type':'queue'     });  })"     }),
                              isc.IButton.create({ title:"Expand all",     icon:"[SKIN]actions/add.png", click: "RinggroupListGrid.selectAllRecords();RinggroupListGrid.expandRecords( RinggroupListGrid.getSelectedRecords() );"})
                             ] 
                          });    
                               
                theGrid = isc.ListGrid.create(LGDefault,{
           	                       ID: "RinggroupListGrid",
           	                       dataSource: DSRinggroupLists,	
										     height:'90%',		  
										     autoDraw: false, 
										     showRollOver:false,
										     canEdit:true,
										     alternateRecordStyles:true,
							              editEvent: "doubleClick",
							              autoFetchData:false,				
							              recordComponentPosition:'within',	
							              canRemoveRecords: true,		            
  											  defaultFields: [{name: "description"}],
  											  editComplete: "RinggroupListGrid.selectAllRecords();RinggroupListGrid.expandRecords( RinggroupListGrid.getSelectedRecords() );", 
											  showRecordComponents: true,										
											  canExpandRecords: true,
											  canExpandMultipleRecords: true,
											  selectOnExpandRecord:true,
							              getExpansionComponent: function(record) {
							              	 
											        var frm = isc.DynamicForm.create({
															            wrapItemTitles:false,showEdges:true,  edgeImage:"[SKIN]/edge.png",  edgeSize:4, edgeOffset:3,
															            saveOnEnter:true,
																	      numCols:4,
															            dataSource: DSRinggroupLists,
															            fields: [											                 
															            			{name:'announcement_file',width:215, icons: [{ click: function(form){ RGplayer.src = 'media/play.php?mode=recording&play_text=' + form.getValue('announcement_file') + '&play_dir=tenant'; }, src: "play_picker.png", }] },                            
				                                                      {name:'timeout',width:117},
				                                                      {name:'extension_method',width:215, showIf: "form.getValue('group_type') == 'extension'" },
				                                                      {name:"ignore_redirects",type:"checkbox",labelAsTitle:true,width:117 },
															                     {name:"extensions",
															                          type:"select", editorType: "SelectItem",
															                          title: "Ring " + record.group_type+'(s)',
														                             colSpan:4, width:415,
														                   		     multiple:(['queue','ivrmenu'].indexOf(record.group_type)==-1),
														                   		     multipleAppearance:"picklist",
														                   		     optionDataSource: getTenantDS('items'),
														                   		     displayField:"name", valueField:"id",
																							  pickListCriteria: { item_type: record.group_type }
													                   		   },
															                     {name:'phone_numbers', showIf:"form.getValue('group_type') == 'extension'" , width:415,colSpan:4},
															                     {type:'submit'}
															            ]																		            
															        });
											       frm.editRecord(record);
											       frm.setValue('extensions', ( record.extensions != 'null' && record.extensions != '' && record.extensions != 'undefined' ) ? JSON.parse(record.extensions) :'' );											       
											       return  frm;
											       
										   	}
       			 });
       			                     
	              // Fetch Slave Table:
	              if ( !isNew )  RinggroupListGrid.fetchRelatedData( tblRinggroups.getSelectedRecord(), DSRinggroups);
			              
			           
			         theRGForm2 = isc.DynamicForm.create({
			        	            valuesManager: 'frm'+object_name, autoDraw: false,wrapItemTitles:false,
	                                fields:[{ name:'default_action',
	                                        change: function(viewer,item,value){
		                           	             theRGForm3.setFields( getDynamicField(value) ); 
		                           	             theRGForm3.setValue('default_action_data',''); },
		                           	             redrawOnChange:true 
	                           	             }
	                           	    ]
                     });

	              theRGForm3 = isc.DynamicForm.create({  valuesManager: 'frm'+object_name, autoDraw: false, wrapItemTitles:false });
	               if ( !isNew ) theRGForm3.setFields( getDynamicField( tblRinggroups.getSelectedRecord()['default_action'] ) );
	              
	              theForm = isc.VLayout.create({ height:'100%', members: [
	                            theRGForm,
	                            isc.Label.create({height:30, contents:" <span style='color:gray;padding-left:20px;font-size:12px'> *Names, starting from digits (length:4+,like: '4000 Sales RGroup'), allows direct Ringgroup access by dialing it: 4000 </span>"}),
	                            theButtons,
	                            theGrid,
	                            ( !isNew) ? isc.HStack.create({ membersMargin:10, height:25, padding:4, align:'center',  autoDraw:false,members:[
 	                                theRGForm2,
 	                                theRGForm3
 	                              ]
 	                            }) :  ''
	                          ]  
		                    }); 
		                    
	                return theForm;
              break;		 
              
             case 'Pagegroups':
		         
                // Pagegroups Form
		           theForm = isc.DynamicForm.create(frm_default_options,{ numCols: 4 });
		           if(theForm.getItem('editIcon')){
                   theForm.hideItem('editIcon');
                 }
                // Pagegroups members :		                       
                  if ( !isNew ){
						      isc.defineClass("PGMDragListGrid","ListGrid").addProperties({
								    width:"43%", 
								    border:"1px solid silver",
								    alternateRecordStyles:true, 
								    canReorderRecords: true,
								    leaveScrollbarGap:false,
								    emptyMessage:"Drag &amp; drop Page Group Members here",
								    canAcceptDroppedRecords: true,
									 canDragRecordsOut: true,
								});
								
							var PGmembersBox =	isc.HStack.create({membersMargin:1, height:200, boder:'none',padding:10, align:'center',  autoDraw:false,
							                    members:[
														    isc.PGMDragListGrid.create({														    	  
														        ID:"tblPGMembersAvailable",
														        dataSource: ShowTenantPagegroupMembers,
														          alternateRecordStyles:true, 
																    showAllRecords:true,
																  //  autoFetchData: true, 
														        fields:[   {name:"membername"},
														        			    {name:"interface"},
														        			    {name:"paused",hidden:true} 
														        ]
														    }),
														    isc.VStack.create({ align:'middle', members: [														      
														        isc.Img.create({src:"[SKIN]/TransferIcons/left.png", width:32, height:32, layoutAlign:"center",
														           border:"2px",cursor:'pointer',
														           click:"tblPGMembersAvailable.transferSelectedData(tblPGMembersAssigned);"
														        }),
														        isc.Img.create({src:"[SKIN]/TransferIcons/right.png", width:32, height:32, layoutAlign:"center",
														           border:"2px",cursor:'pointer',
														           click:"tblPGMembersAssigned.transferSelectedData(tblPGMembersAvailable);"
														        }) 
														       ]
														    }),   
														    isc.PGMDragListGrid.create({
														        ID:"tblPGMembersAssigned",
														        dataSource: DSPagegroupMembers,
														        left:300,
														        fields:[  {name:"membername"}, 
														                  {name:"interface"},  
														                  {name:"paused", width:'*', 
														                  canEdit:true }
														        ]										        
														    })
								                    ]
								                 });
								   
								
    						 theForm = isc.SectionStack.create({
										    width: "100%", height: "100%",  border:"none",  autoDraw:false,  visibilityMode: "multiple",					     
										    ID:"PGFormSections",		    
										    sections: [																	       
										       { showHeader:false, title: "-",  items: [ frmPagegroups ] },
										       { showHeader:true,  title: "Page Group Members "},
										       { showHeader:false, title: "-",  items: [ PGmembersBox ] } 
										    ]
	                    });                  	
                  	
                  	
                  }else{
                      theForm = frmPagegroups;
                  }          
		                       		    
		        break;     
		    
         case 'Queues':
		       // Queues Form
		          isc.ValuesManager.create({ ID: 'frm'+object_name, dataSource: "DS" + object_name  });               


 		          theQEForm1_1 = isc.DynamicForm.create({ 
 		          	           autoDraw: false, 
 		          	           width:"100%", 
 		          	           height:"100%",	
 		          	           wrapItemTitles:false, 
 		          	           valuesManager: 'frm'+object_name, 
 		          	           numCols: 4 ,
 		          	           fields:[ 		          	   
 		          	           	  {name:"name",       width:250 },
 		          	           	  {name:"qlabel",     width:100 },
 		          	           	  {name:"strategy",   width:250 }, 		          	           	  
 		          	           	  {name:"ringinuse",  width:100 },  		          	           	  		          	           	  
                                  {name:"queue_calltag",width:250 },
 		          	           	  {name:"timeout",    width:100 },
 		          	           	  {name:"musiconhold",width:250 }, 		          	           	  
 		          	           	  {name:"retry",      width:100 },
 		          	           	  {name:"queue_welcome",width:250 },
 		          	           	  {name:"joinempty",  width:100 },        	              
 		          	           	  {name:"context_script",startRow:true,endRow:true, width:470,colSpan:4 }
 		          	           ]
 		          	});
 		           theQEForm1_2 = isc.DynamicForm.create({ 
 		          	           autoDraw: false, 
 		          	           width:"100%", 
 		          	           height:"100%",	
 		          	           wrapItemTitles:false, 
 		          	           valuesManager: 'frm'+object_name, 
 		          	           numCols: 4,
 		          	           fields:[ 		          	              
 		          	              {name:"announce"}, 		          	              
 		          	              {name:"announce_frequency"},
 		          	              {name:"queue_youarenext"},
 		          	              {name:"wrapuptime"},
 		          	              {name:"autopause"},
 		          	              {name:"maxlen"},
 		          	              {name:"announce_holdtime"}, 		          	              
 		          	              {name:"reportholdtime"} ,
 		          	              {name:"leavewhenempty" },
 		          	              {name:"stats_email"}
 		          	           ]
 		          	});


		          theQEForm1 = isc.TabSet.create({
								    ID: "theQueuesTabs",
								    width:"100%", height:"100%",
								    tabs:[
								          { title: "Options", pane: theQEForm1_1 },
								          { title: "More ...", pane: theQEForm1_2 },
								          { title: "Dynamic members", pane: isc.Label.create({height:30, contents:" Coming Soon ....."}) }
								     ]       
								  });   


		          
                
                // Queues members :		                       
                  if ( !isNew ){
						      isc.defineClass("QMDragListGrid","ListGrid").addProperties({
								    width:"43%", 
								    saveOnEnter:true,  
								    border:"1px solid silver",
								    alternateRecordStyles:true, 
								    canReorderRecords: true,
								    leaveScrollbarGap: false,								    
								    emptyMessage:"Drag &amp; drop Queue Members here",
								    canAcceptDroppedRecords: true,
									canDragRecordsOut: true,
								});
								
							var QmembersBox =	isc.HStack.create({membersMargin:1, height:180, 
													boder:'none',padding:5, align:'center',  autoDraw:false,
							                    members:[
														    isc.QMDragListGrid.create({														    	  
														        ID:"tblQMembersAvailable",
														        dataSource: ShowTenantQueuesmembers,
														        alternateRecordStyles:true, 
															    showAllRecords:true,
															    autoFetchData: true,		     
													            useClientFiltering:false,
													            showFilterEditor: true,
													            fetchDelay: 500,
													            filterOnKeypress: true,																  
														        fields:[ { name:"membername",title:'Available Agents' }, 
														                 { name:"interface"},
														                 { name:"penalty",canEdit:true,width:1},
														                 { name:"paused" ,width:1}
														                ]
														    }),
														    isc.VStack.create({ align:'middle', members: [														      
														        isc.Img.create({src:"[SKIN]/TransferIcons/left.png", width:32, height:32, layoutAlign:"center",
														           border:"2px",cursor:'pointer',
														           click:"tblQMembersAvailable.transferSelectedData(tblQMembersAssigned);"
														        }),
														        isc.Img.create({src:"[SKIN]/TransferIcons/right.png", width:32, height:32, layoutAlign:"center",
														           border:"2px",cursor:'pointer',
														           click:"tblQMembersAssigned.transferSelectedData(tblQMembersAvailable);"
														        }) 
														       ]
														    }),   
														    isc.QMDragListGrid.create({
														        ID:"tblQMembersAssigned",
														        dataSource: DSQueueMembers,
														        left:300,
														        fields:[  {name:"membername", title:'Assigned Agent' }, 
														                  {name:"interface"},														                  
														                  {name:"paused", width:'*', canEdit:true },
														                  {name:"penalty",canEdit:true}

														               ]										        
									 					    })
								                    ]
								                 });


					         theQEForm2 = isc.DynamicForm.create({
					        	            valuesManager: 'frm'+object_name, autoDraw: false,wrapItemTitles:false,
			                                fields:[{ name:'default_action',
			                                        change: function(viewer,item,value){
				                           	             theQEForm3.setFields( getDynamicField(value) ); 
				                           	             theQEForm3.setValue('default_action_data',''); },
				                           	             redrawOnChange:true 
			                           	             }
			                           	    ]
		                     });
		                     
			                theQEForm3 = isc.DynamicForm.create({  valuesManager: 'frm'+object_name, autoDraw: false, wrapItemTitles:false });
			                if ( !isNew ) theQEForm3.setFields( getDynamicField( tblQueues.getSelectedRecord()['default_action'] ) );
								   
								
    						 theForm = isc.SectionStack.create({
										    width: "100%", height: "100%",  border:"none",  autoDraw:false,  visibilityMode: "multiple",					     
										    ID:"QueueMembersSections",		    
										    sections: [	
										       { showHeader:false, title: "-",  items: [ theQEForm1 ] },										       
										       { showHeader:false, title: 'missed calls',  textAlign:'right', align:'right', padding:4,items: [  
										                                                            isc.IButton.create({ title:"Send Missed Calls",icon:"images/missed_calls.png",width:180, 
										                                                                click: function(form){
																												     	          var report_name = frmQueues.getValue('name') + '-' + frmQueues.getValue('id');
																												     	          console.log('Sending call stats report "' + report_name + '"  to: ' + frmQueues.getValue('stats_email'));
																												     	          $.post('jaxer.php',{'get_stats': 1, 'to_email': frmQueues.getValue('stats_email'), 'report_name': report_name },
																												     	                   function (data) {
																										                                         isc.say('Report ' +  report_name + ' Mailing Result: ' + data.error );
																										                                   },
																										                           'json');
																												     	       }     
										                                                             }), 
										                                                          ]
										       },
									   
										       { showHeader:true,  title: "Queue Members  " },
										       { showHeader:false, title: "-", items: [ QmembersBox ]} ,
										       { showHeader:false, title: "-", items: [
										          ( !isNew) ? isc.HStack.create({ membersMargin:10, height:25, padding:4, align:'center',  autoDraw:false,
										          	  members:[
					 	                                theQEForm2,
					 	                                theQEForm3
					 	                              ]
					 	                            }) :  ''
										        ]}
										    ]
	                    });                  	
                  	
                  	
                  }else{
                      theForm = isc.SectionStack.create({
										    width: "100%", height: "100%",  border:"none",  autoDraw:false,  visibilityMode: "multiple",					     
										    ID:"QueueMembersSections",		    
										    sections: [	
										       { showHeader:false, title: "-",  items: [ theQEForm1 ] },
										       { showHeader:false, title: "help info", height:30, items: [ isc.Label.create({height:30, contents:" Please set quene name, and save it first, then reopen -  add members to created Queue"}) ] },
										      
										    ]
	                    });                  	
                  }          
		                       		    
		        break;
  
		    case 'IVRMenu':
		          // IVRMenu Form 
		           //isc.ValuesManager.create({ ID: "VMgrIVRMenu", dataSource: "DSIVRMenu" });
		           isc.DynamicForm.create(frm_default_options,{ 
		                               //valuesManager: "VMgrExtensions",
						                   numCols: 4,
						                   fields: [ 
						                      {name:"name",endRow:true, width:575,colSpan:4}, //{name:"delay_before_start",width:200},
						                      //{type:'header', defaultValue:'&nbspWelcome announcement:'}, 
						                      {name:"announcement_type", redrawOnChange:true,width:200,
						                          change: function(viewer,item,value){
						                        	   if (value == 'upload')
 						                        	     ShowMediaUploadWnd("tenant",'WndIvrMedia');  // automaticallu makes sounds/<tenant> //
						                          },
						                          itemHoverHTML : function () {
											             return this.hoverText[this.getValue()];
											         },
											      hoverText: {
											             "recording": "<b>Play Recording</b><p> Announcement text is a recording file name to be played (without extension)</p> ",							                          
											             "tts":       "<b>Text to Speech</b><p> Announcement text is a text to Synthesize audio from it (cool!)</p>",
											             "tts_template" : "<b>TEMPLATE Text to Speech</b><p> Announcement text is a TEMPLATE with variables to be replaced from SIP Channel to Synthesize audio from it<br> If there is no channel variable X-CRM-TAGS (get_tags sets it from external sources) with json inside  the call, the default text part afer delimiter |  is played.<br> Template is played by php agi script: play_tts_template.php,  extracted variables from X-CRM-TAGS have name: array('alert','alert1','alert2','alert3','alert4','alert5' ) and firm </p>",
											             "moh":       "<b>Play Music on Hold </b><p> Play Music on Hold untill user make any selection (igmore other item audio)</p>"
											      }
											    },
											    {name:"announcement_lang", showIf: "form.getValue('announcement_type') == 'tts' || form.getValue('announcement_type') == 'tts_template'" ,width:200},
											      // {name:"voicemail_box"},
								                {name:"announcement",      showIf: "form.getValue('announcement_type') != 'moh'", startRow:true,endRow:true, width:600,colSpan:4 },	  // has picker Icon Via datasource //
								                {name:"moh_class",         showIf: "form.getValue('announcement_type') == 'moh'",width:200},
								                {name:"context_script",startRow:true,endRow:true, width:575,colSpan:4 },
								                //{type:'header', defaultValue:'Options:'},
								               // {name:"recordings_lang",   showIf: "form.getValue('announcement_type') == 'recording' || form.getValue('announcement_type') == 'upload'"},
								                {name:"menu_timeout",width:200},
		                                       // {name:"ring_while_wait"},
		                                        {name:"allow_dialing_exten",width:200},
		                                        {name:"allow_dialing_external",width:200},
		                                        {name:"allow_dialing_featurecode",width:200}                                        
								                //{name: "PlayiconField",    title: "Play", width: 110 }
								             ]   
		                       });
		                       
		                       
                  // Show related IVRMenu items only if we have IVRMenu already created  //                              
                 if ( !isNew  ) {
							           isc.ListGrid.create(LGDefault,{
			           	                       ID: "IVRMenuItemsGrid",
													    // autoFitFieldWidths: true,
													     height:230,		  
													     autoDraw: false, 
													     canEdit:true,
													     editByCell:true,
													     canRemoveRecords: true,					
	 											        showRecordComponents: true,
													     showRecordComponentsByCell: true,
														  autoSaveEdits: true,
													     modalEditing: true, // any mouse click outside of the open cell editors will end editing mode
										              editEvent: "click",
										             //listEndEditAction: "next",									             
										             // useAllDataSourceFields: true,
										              dataSource: DSIVRMenuItems,
										              getEditorProperties:function(editField, editedRecord, rowNum){
										              	  if ( editField.name == 'item_action' )
										              	       return { redrawOnChange:true,
										                               change:function(values,item,value){
	                                                                  item.grid.setEditValue(this.rowNum, 'item_data', null);
	                                                               }
	                                                      };
	                                          if ( editField.name == 'announcement_type' )
																	 return { redrawOnChange:true,
										                               change:function(values,item,value){
	                                                                 if (value == 'upload')
					 						                        	           ShowMediaUploadWnd("tenant",'WndIvrMediaItems');  // automaticallu makes sounds/<tenant> //
					 						                        	        }
					 						                           };	                  
										              	   if ( editField.name == 'item_data' && editedRecord != null )
										              	   	switch(editedRecord.item_action){
										              	   	 case "no":
										              	   	 case "repeat":
															 case "play_invalid":
															 case "hangup":
															 case "unassigned":  return { canEdit:false,editorType:"StaticText"};																	 
															 case "disa":     return { canEdit:true,editorType:"TextItem",title:"Value",hint:"[pin],[CALLERNAME <CALLER-ID>]",showHintInField:true}; 
								              	   	         case "number":   return { canEdit:true,editorType:"TextItem",title:"Value"};										              	   	 	
															 case "park_announce_rec":
															 case "play_rec": 
										              	   	 case "extension":
										                      case "ringgroup":
										                      case "ivrmenu":
										                      case "featurecode":										                      
										                      case "conference":
										                      case "checvm":
										                      case "followme":
										                      case "dirbyname":														                      
										                      case "queue":
															  case "voicemail":   return { editorType:"SelectItem",
																                                  canEdit:true,
																							            // optionDataSource:"ShowTenantItemsDS",
																							            optionDataSource: getTenantDS('items'), 
		 																									 displayField:"name", valueField:"name",
																							             getPickListFilterCriteria : function () {
																							                var current_item_type = this.grid.getEditedCell(this.rowNum, "item_action");
																							                return { item_type:current_item_type };
																							             },
																							             change: function(viewer,item,value){																																	
																			                            if (value == 'Upload new....')
													 						                        	     ShowMediaUploadWnd("tenant",'WndIvrMediaItem');  // automaticallu makes sounds/<tenant> //
																			                         }
																							         };
                                                              		
										                  }
										              },   														               
										              fields: [ 
										                        {name:'t_ivrmenu_id', defaultValue: tblIVRMenu.getSelectedRecord()['id'], hidden:true },
										                        {name:'selection',width:30 },
										                        {name:'announcement_type',width:100},
										                        {name:'announcement', width:200},
										              		    {name:'playIcon', width:22, title:"Preview", canEdit:false, readOnlyDisplay:'static',width:66},
										                        {name:'item_action', redrawOnChange:true, width:100},
										                        {name:"item_data",   redrawOnChange:true,
												                        change: function(viewer,item,value){
																						console.log('val' + value);
												                        	   if (value == 'Upload new....'){
						 						                        	     ShowMediaUploadWnd("tenant",'WndIvrMediaItem');  // automaticallu makes sounds/<tenant> //
						 						                        	     console.log('SMTH is DONE');
						 						                        	   }  
												                        }
		                                                 }
										                        //{name:'destination'},
										                      
										              			 ],
										              sortField: 'selection',
														  sortDirection: "ascending",			 
										              createRecordComponent : function (record, colNum){
															        if ( this.getFieldName(colNum)  == "playIcon" )  
												                     return   isc.ImgButton.create({
																			                showDown: false, showRollOver: false,
																			                layoutAlign: "center",
																			                src: "play_picker.png",
																			                prompt: "Play Item Media ", height: 16, width: 16, grid: this,
		 																				       click : function (){ 																									
																		                	       IVRplayer.src = 'media/play.php?mode=' + record['announcement_type'] + '&play_text=' + record['announcement'] + '&ttslang=' + tblIVRMenu.getSelectedRecord()['announcement_lang']+'&play_dir=tenant' ;
	                                                                      IVRplayer.play();
			                                                                }   
																			              })
		  	                                            }
					           	                             
					             				});
					             	    // Fetch Items in Slave Table:				  
					             		IVRMenuItemsGrid.fetchRelatedData(tblIVRMenu.getSelectedRecord(), DSIVRMenu);  
					             	  
							            //isc.HLayout.create({  ID:"IVRItemsBtnHLayout",
							            //							 align: "center",padding:4, membersMargin: 6, autoDraw:false,
											 //         	  	       members: [
						          	  	           isc.ImgButton.create({ ID: 'SaveIVRBtn',   click: function(){ IVRMenuItemsGrid.saveAllEdits();},     size:16,  src:"[SKIN]actions/save.png",    showFocused:false, showRollOver:false, showDown:false });
														  isc.ImgButton.create({ ID: 'NewIVRBtn',    click: function(){ IVRMenuItemsGrid.startEditingNew(); }, size:16, src:"[SKIN]actions/add.png",    showFocused:false, showRollOver:false, showDown:false });
														  isc.ImgButton.create({ ID: 'DeleteIVRBtn', click: function(){
													             if ( IVRMenuItemsGrid.getSelection().getLength() > 0 )     
													                   IVRMenuItemsGrid.getSelection().map(function (item) { IVRMenuItemsGrid.removeData(item) });
														           },
														           src:"[SKIN]actions/remove.png",size:16  
														       });
						          	  	           isc.ImgButton.create({ ID: 'DiscardIVRBtn', title:"Discard",click: function(){ IVRMenuItemsGrid.discardAllEdits(); },src:"[SKIN]actions/undo.png", size:16 });
											 //         	  	      ]          	  	           
					                   // });
					                    
							            theForm = isc.SectionStack.create({
																	    width: "100%", height: "100%",  border:"none",  autoDraw:false,  visibilityMode: "multiple",					     
																	    ID:"IVRMenuFormSections",		    
																	    sections: [																	       
																	       { showHeader:false, title: "-",  items: [ frmIVRMenu  ] },
																	       { showHeader:true, title: "special selection:    i - invalid selection pressed;   t - no input(timeout reached); ", 
																	            controls: [ SaveIVRBtn, NewIVRBtn, DeleteIVRBtn,  DiscardIVRBtn ]  },
																	       { showHeader:false, title: "-",  items: [ isc.VLayout.create({ members: [ IVRMenuItemsGrid ] }) ] } 
																	    ]
								                    });
					 // We have a new IVRMenu //			                    
			         }else{
                    theForm = frmIVRMenu;   
			         }	           					       
		          
              break;               
		    
		    case 'MOH' :
		    case 'MOHDefault':
               var frm = isc.DynamicForm.create(frm_default_options,{numCols:4,
                  dataSource:"DSMOH",
                  fields:[
                     {name:"name"},
                     {name:"mode",redrawOnChange:true,change:function(frm,itm,val){ if (val == 'custom' ){ MOHFormSections.hideSection(1) }else{ MOHFormSections.showSection(1) } } },                
                     {name:"directory",showIf: "form.getValue('mode') != 'custom'",  canEdit:false, readOnlyDisplay:'static' },
                   //  {name:"application", showIf: "form.getValue('mode') == 'custom'", colSpan:4, width:400}, 
                     {name:"network_media_url", showIf: "form.getValue('mode') == 'custom'", colSpan:4, width:400},
				         {name:"sort", showIf: "form.getValue('mode') != 'custom'"},
				         {name:"format",showIf: "form.getValue('mode') != 'custom'"},
				         {name:"digit"}
                   ]  
               });
               if( frm.getItem('editIcon')) frm.hideItem('editIcon');
               
		         theForm = isc.SectionStack.create({
								    width: "100%", height: "100%",
								    border:"none",			
								    visibilityMode: "multiple",					     
								    ID:"MOHFormSections",		    
								    sections: [
								       { showHeader:false, title: "Music on Hold Files",  items: [ frm ]},
								       { showHeader:false, title: "Upload MOH file",
								          items: [
								           isc.IButton.create({ autoDraw:false, title: " Refresh Listing",autoDraw:false, click: function(){ tblMOHFiles.Refresh(); } ,icon:"[SKIN]actions/refresh.png"}),
   								        isc.ListGrid.create(LGDefault,{   								        	
												     ID: "tblMOHFiles",
												     height:170,		   
												     canRemoveRecords: true, 
												     showRecordComponents: true,
													  showRecordComponentsByCell: true,
												     dataSource: ShowMOHFilesDS,
												     fields: [
												      {name:"file_name"},		     
							   					   {name:"size"},
				          						   {name:"format"},
						                        {name:"duration"},
												      {name:'Play', width:22, title:"", canEdit:false, readOnlyDisplay:'static'}
												     ],     
												     createRecordComponent : function(record, colNum){
																			        if ( this.getFieldName(colNum)  == "Play" )  
																                     return   isc.ImgButton.create({
																							                showDown: false, showRollOver: false,
																							                layoutAlign: "center",
																							                src: "play_picker.png",
																							                prompt: "Play Item Media ", height: 16, width: 16, grid: this,
						 																				       click : function (){ 																									
	 																				                	       MOHplayer.src = 'media/play.php?mode=moh&play_text=' + record['file_name'] + '&play_dir=' + record['directory'];                                                                                                        
		                                                                                  MOHplayer.play();
							                                                                }   
																							              })
						  	                    },
												     Refresh: function () {
												    	this.getDataSource().invalidateCache();
												      this.setData(isc.ResultSet.create({ dataSource : this.getDataSource() }) );
												      this.fetchData({'directory': tblMOH.getSelectedRecord().directory });		    	
												    }											     
											  }),     	      
                                   isNew ? null : getMediaUploader( ( object_name == 'MOH' ) ? tblMOH.getSelectedRecord().directory : tblMohdefault.getSelectedRecord().directory , 'moh', 'fileManager.php' )
                                   
							          ]} 
							       ]
			      });
			        
		    break;      
		      
		   case 'Extensions' :		     
		       	                                       
		        isc.ValuesManager.create({ ID: "VMgrUserOptions", dataSource: "DSUserOptions" });
		        isc.ValuesManager.create({ ID: "VMgrExtensions", dataSource: "DSExtensions" });
		         
				 theForm = isc.TabSet.create({
						    ID: "theExtTabs",
						    width:"100%", height:"100%",
						    tabs: [
						        { title: "General",  
						          pane: isc.VLayout.create({ align: "center",padding:4, membersMargin: 6, autoDraw:false,
						                  members:[
						                    isc.HLayout.create({ align: "center",padding:4, membersMargin: 6, autoDraw:false, 
							                   members:[ 						             
									                  isc.DynamicForm.create(frm_default_options, {
											          	 numCols: 4, 
											          	 width:"100%",
											          	 valuesManager: "VMgrExtensions",
											          	  fields: [
											          	      {name: "extension",width:100},
											          	      {name:"first_name",width:180, startRow:true},{name:"last_name",width:180},
											          	      {name: "email",width:180, startRow:true,},{name:"mohinterpret",width:180},
											          	      //{name: "email_pager",width:250},
											          	       {name:"crm_enabled", startRow:true, ID:'frmExtCRM_enabled', redrawOnChange:true , title:"Enable CRM access",
											          	       helpText: "CRM access is a separate Operator Panel, where agent can handle inbound/outbound calls",
															   itemHoverHTML : function () {															             
															             return this.helpText;
															         },
															   icons: [{
														            src: "[SKIN]actions/help.png",
														            click: "isc.say(item.helpText)"
														         }]
											          	      },
											          	      {name:"crm_username",startRow:true,  title:"CRM Login",showIf:"frmExtCRM_enabled.getValue() " , width: 180},
											          	      {name:"crm_password",endRow:true, startRow:false,title:"CRM password",showIf:"frmExtCRM_enabled.getValue() " , width: 180},

											          	      {name:"click2dial_enabled", startRow:true, ID:'frmExtC2C_enabled', redrawOnChange:true , title:"Enable <a target='about_blank' href='https://chrome.google.com/webstore/detail/asterisk-click2call/hlnmjkbpmnbgeondjeceaomhafdacmlj'>Click2dial Chrome plugin</a>",
											          	       helpText: "GoogleChrome browser plugin, which calls a phone number highlighted on a web page and connects it with your PBX extension <br><img src='images/c2c_picture.png'>",
															   itemHoverHTML : function () {															             
															             return this.helpText;
															         },
															   icons: [{
														            src: "[SKIN]actions/help.png",
														            click: "isc.say(item.helpText)"
														         }]
											          	      },
											          	      {name:"click2dial_exten",startRow:true,title:"Click2Dial Exten",showIf:"frmExtC2C_enabled.getValue() " , width: 180},
											          	      {name:"click2dial_url",  title:"AMI script URL",showIf:"frmExtC2C_enabled.getValue()  ", width: 180 },
											          	      {name:"click2talk_enabled", startRow:true,ID:'frmExtC2T_enabled', redrawOnChange:true , title:"Enable [ Call ME ] WEB button",
											          	        helpText: " A Call-Us button allows anyone to call you from browser WEB page. It uses WEBrtc technology, supported in many browsers, except IE. <br> You will receive a voice call on your PBX extension, which can be forwarded to your  mobile or landline. <br>WEB button can be integrated on the company front-end, Facebook, Twitter or any other your social WEB resources ",
											          	        itemHoverHTML : function () {															             
															             return this.helpText;
															      },
															    icons: [{
														            src: "[SKIN]actions/help.png",
														            click: "isc.say(item.helpText)"
														       }]
														      },
														      {name: "click2talk_options",title:'Embeding code', editorType:"TextAreaItem", colspan:4,startRow:true,endRow:true,width:200,height:100,
														       showIf:"frmExtC2T_enabled.getValue() ",
														   //    icons: [{														            
														   //         src: 'images/callUsShow.png',
														   //        click: "isc.say('testing page')",
														   //         width:80,
														   //         height:40
														   //    }]
														      },
														      
														    //{type: "header", defaultValue: "Device registration information"},
											          	      {name: "useragent",startRow:true,canEdit:false,readOnlyDisplay:'static'},
											          	      {name: "ipaddr",canEdit:false,readOnlyDisplay:'static'}
											          	  ]
											          	 })
													  	]
									           }),
									           isc.DynamicForm.create({ valuesManager: "VMgrUserOptions", fields: [
									           	   {name:"call_recording"},
									           	   {name:"call_waiting"},
											       {name:"dnd"}
									           	   ]})
									         ]  
									        })           	  		 
						        },
						        { title: "Caller ID", 
						          pane: isc.DynamicForm.create({
											   valuesManager: "VMgrExtensions",
						                  autoDraw: false, numCols: 4,
						                  width:"100%", height:"100%",
						 						wrapItemTitles:false,
						 						fields: [        
						 						   {type:'header', defaultValue:'External CallerID',align:'center'},  
						 						   {type:"radioGroup", title:'CallerID Number',ID:'frmExtCallerID',startRow:true,
											        valueMap:[ "Use default","Specify:"], vertical:false,
											        defaultValue:"Use default", redrawOnChange:true},                            
                                       {name:"outbound_callerid", showTitle:false,showIf:"frmExtCallerID.getValue() != 'Use defauls'",  startRow:false },
													{type:"radioGroup", title:'CallerID Name', ID:'frmExtCallerName', startRow:true,
											        valueMap:[ "Use default","Specify:"], vertical:false,
											        defaultValue:"Use default",redrawOnChange:true  },
                                       {name:"outbound_callername", showTitle:false, showIf:"frmExtCallerName.getValue() != 'Use default'", startRow:false },
                                       {type:'header',defaultValue:'Internal CallerID',align:'center'},
                                       {type:"radioGroup", title:'CallerID Number ',ID:'frmIntCallerID',startRow:true,
											        valueMap:[ "Use default","Specify:"], vertical:false,
											        defaultValue:"Use default", redrawOnChange:true},                            
                                       {name:"internal_callerid", showTitle:false,showIf:"frmIntCallerID.getValue() != 'Use default'",  startRow:false },
													{type:"radioGroup", title:'CallerID Name', ID:'frmIntCallerName', startRow:true,
											        valueMap:[ "Use default","Specify:"],vertical:false,
											        defaultValue:"Use default",redrawOnChange:true  },
                                       {name:"internal_callername", showTitle:false, showIf:"frmIntCallerName.getValue() != 'Use default'", startRow:false }
						 				   ]
						         })     
						        },
						        { title: "Blocking", 
						          pane: isc.VStack.create({width:"400",membersAlign: "center",
						          	members:[
		                                  isc.DynamicForm.create({ valuesManager: "VMgrExtensions",items:[{name:"outbound_route",width:300}],autoDraw: false}),
								                isc.DynamicForm.create({
													   ID: 'frmUserOpts',
												      width:"100%", 
								                  autoDraw: false,
								                  numCols: 2,
								                  valuesManager: "VMgrUserOptions",
								 						wrapItemTitles:false,
								 						items: [
								 						 {name:"t_sip_user_id", hidden:true },
								 						 {name:"call_blocking", vertical:false},
								 						 {name:"call_blocking_anonym", vertical:false},
								 						 {name:'call_blocking_mode', width:300} 
								 					   ]
								               }),
		                                 isc.ToolStrip.create({
					                           height:30, width:"100%", align: "right",membersAlign: "right",
					                           members: [
					                                isc.Label.create({ID:'Ext_CID_list',align:"left",valign:"left",width:280, contents:"<b>CallerID BlockList<b>"}),
					                                isc.ToolStripButton.create({ title: "Add", click: function(){ tblUserBlockList.startEditingNew(); }, icon:"[SKIN]actions/add.png"}),
					                                isc.ToolStripButton.create({ title: "Del", click: function(){ tblUserBlockList.removeSelectedData(); },icon:"[SKIN]actions/cancel.png" }),
					                                ]
					                        }),        
								               isc.ListGrid.create({height:180,autoSaveEdits:false, canEdit:true, ID:'tblUserBlockList', dataSource:"DSUserBlockList"})
						             ]}
						            )       
						        },
						         { title: "Screening", 
						          pane: isc.VStack.create({width:"400",membersAlign: "center",
						          	 members:[
						                isc.DynamicForm.create({
											   ID: 'frmUserScreening',
										      width:"100%", 
						                  autoDraw: false,
						                  numCols: 2,
						                  valuesManager: "VMgrUserOptions",
						 						wrapItemTitles:false,
						 						items: [
						 						 {name:"call_screening",vertical:false},
						 						 {name:"call_screening_ask_cid", vertical:false},
                                                 {name:"call_screening_ask_cname", vertical:false}
						 					   ]
						                }),
                                       isc.ToolStrip.create({
			                              height:30, width:"100%", align: "right",membersAlign: "right",
			                              members: [
			                                isc.Label.create({ID:'Ext_SCREEN_list',align:"left",valign:"left",width:280, contents:"<b>ALWAYS Screen For this CallerID</b>"}),
			                                isc.ToolStripButton.create({ title: "Add", click: function(){ tblUserScreenList.startEditingNew(); }, icon:"[SKIN]actions/add.png"}),
			                                isc.ToolStripButton.create({ title: "Del", click: function(){ tblUserScreenList.removeSelectedData(); },icon:"[SKIN]actions/cancel.png" }),
			                              ]
			                          }),        
						                isc.ListGrid.create({height:180,autoSaveEdits:false, canEdit:true, ID:'tblUserScreenList', dataSource:"DSUserScreenList"})
						             ]}
						            )       
						        },
						         { title: "Forwarding", 
						          pane: isc.VStack.create({width:300,membersAlign: "center",
						             members:[
							               isc.DynamicForm.create({
											      width:200, height:100,
							                  autoDraw: false,
							                  numCols: 4,
							                  valuesManager: "VMgrUserOptions",
							 						wrapItemTitles:false,
							 						items: [
							 						  {name:"call_forward_timeout",startRow:true,width:100,title:"Forward timeout,s",hint:"0 - for instant forward"},
							 						  {type:"radioGroup", title:'Always Forward',ID:'frmUserOpt_AlwaysFWD', width:270,startRow:true,
							 						         change:function(frm,itm,val){ frm.setValue('call_forwarding',((val<2)?val:'') );}, 
											               valueMap:{0:"Off",1:"To VMail",2:"To number"}, vertical:false, defaultValue:0, redrawOnChange:true},
									              {name:"call_forwarding",showIf:"frmUserOpt_AlwaysFWD.getValue() == 2", showTitle:false },
													  {type:"radioGroup", title:'Forward when BUSY',ID:'frmUserOpt_BusyFWD',startRow:true,
													         change:function(frm,itm,val){ frm.setValue('call_forward_onbusy',((val<2)?val:'') );},
											               valueMap:{0:"Off",1:"To VMail",2:"To number"}, vertical:false, defaultValue:0, redrawOnChange:true},									              									              
									              {name:"call_forward_onbusy", showIf:"frmUserOpt_BusyFWD.getValue() == 2",  showTitle:false},
									              {name:"call_forward_tag",startRow:true},
									              {name:"call_forward_preserve_cid",showTitle:false},
									              {name:"call_followme_status", vertical:false,startRow:true, redrawOnChange:true,width:120},									              
									              {name:"call_followme_options",showIf:"form.getValue('call_followme_status') == 1",startRow:true,width:220},
									              {name:"pls_hold_prompt", showIf:"form.getValue('call_followme_status') == 1",startRow:true,width:220}
							 					   ]
							               }),
							               
	                                 isc.ToolStrip.create({
				                           height:30,  width:"450", align: "right",membersAlign: "right",
				                           members: [
				                                isc.Label.create({ID:'Ext_FollowMe_list',align:"left",valign:"left",width:330, contents:"<b>FollowMe List</b><i><small> (Use phone2&phone2&phone2.. per line to ringAll per step)</small></i>"}),
				                                isc.ToolStripButton.create({ title: "Add", click: function(){ tblUserFollowMeList.startEditingNew(); }, icon:"[SKIN]actions/add.png"}),
				                                isc.ToolStripButton.create({ title: "Del", click: function(){ tblUserFollowMeList.removeSelectedData(); },icon:"[SKIN]actions/cancel.png" }),
				                                ]
				                        }),        
							               isc.ListGrid.create({ ID:'tblUserFollowMeList', height:100,width:"450",autoSaveEdits:false, canEdit:true, dataSource:"DSUserFollowMeList",
							                 fields:[{name:'phonenumber'},{name:'timeout'},{name:'name'},{name:'t_sip_user_id'}]
							               }),
							                isc.ToolStrip.create({
				                           height:30,  width:"450", align: "right",membersAlign: "right",
				                           members: [
	                                         isc.HStack.create({ membersMargin:10, height:25, padding:4, align:'center',  autoDraw:false,         
					                                   members:[
					                                    // Control
					                                     isc.DynamicForm.create({
											        	            valuesManager: "VMgrUserOptions", autoDraw: false, wrapItemTitles:false,
									                           fields:[{ name:'call_followme_ontimeout', redrawOnChange:true,
									                                    change: function(viewer,item,value){
									                           	                VMUserOptsDefActData.setFields(getDynamicField(value,'call_followme_ontimeout_var')); 
									                           	                VMUserOptsDefActData.setValue('call_followme_ontimeout_var',''); 
									                           	              }
									                           	     }]
				                                         }),
				                                       // Data  
					                                     isc.DynamicForm.create({ID:'VMUserOptsDefActData',valuesManager: "VMgrUserOptions",
					                                                 autoDraw: true, wrapItemTitles:false,
					                                                 fields:[{name:'call_followme_ontimeout_var'}]              
					                                      })
					                                    ]
					                                })  // Default Action Control End //
					                       ]
					                     }) // End of second Tool strip for Def act controls           
	                            ]})   // Pane's VStack with members : "Fordward/FolloMe"
						        },
						        { title: "SIP Devices", 
						           pane: isc.VStack.create({ width:"400",membersAlign: "center",
								          	members:[
								               isc.DynamicForm.create({
													  ID: 'frmext_device',
									                  autoDraw: false,
									                  numCols: 4,
									                  valuesManager: "VMgrExtensions",
									                  width:"100%",
								 					  wrapItemTitles:false,
								 						items: [
								 						 {name: "name",width:150}, {name: "nat"},
								 						 {name: "secret",width:150},
								 						 //{name: "videosupport"},
								 						 {name: "qualify"},
								 						 {name: "allow",width:150}, {name:"transport"},
								 						// {name: "qualify"}, {name:'avpf'},
									          	         {name: "dtmfmode",width:150},  {name: "encryption"},								          	       
									          	       // {name:"outbound_route"}, // Moved to call block
									          	         {name: "other_options", height:80,editorType:"TextAreaItem",
									          	                 colspan:"*",startRow:true},
									          	         {name: "enable_mwi"}								          	     
								 				     	]
								               }), 
								               isc.HStack.create({width:"100%", members:[
							                               isc.Label.create({ID:'Ext_DEV_list',width:100,align:"left",valign:"left", contents:"<span style='white-space:nowrap;width:100%;text-align:left;'>Logged into other devices:</span><br><small>(Hot-Desking)</small>"}),
											               isc.VStack.create({width:"100%", members:[								               	   
											                   isc.ToolStrip.create({
										                           width:200, align: "right", membersAlign: "right",
										                           members: [	
										                                isc.ToolStripButton.create({ prompt:'Login into exten...', click: function(){ tblUserDevices.startEditingNew(); }, icon:"[SKIN]actions/add.png"}),
										                                isc.ToolStripButton.create({ prompt:'Logout from selected', click: function(){ tblUserDevices.removeSelectedData(); },icon:"[SKIN]actions/cancel.png" }),
										                                isc.ToolStripButton.create({ title: "Logout from all", click: function(){ tblUserDevices.selectAllRecords(); tblUserDevices.removeSelectedData(); },icon:"[SKIN]actions/undo.png" }),
										                                ]
										                       }), 
													           isc.ListGrid.create({height:120,width:200,autoSaveEdits:true, canEdit:true, ID:'tblUserDevices', dataSource:"DSUserDevices"})
			                                               ]})
			                                   ]})            
										   ]     
										 })             
						        },
						        { title: "Voice/Video Mail", 
						          pane: isc.DynamicForm.create({
											   ID: 'frmvm_users',
											   dataSource: "DSvm_users",
						                  autoDraw: false,
						                  width:"100%", height:"100%",
						 						wrapItemTitles:false
						         })    
						        }
						        /*
						         { title: "Call routing", 
						          pane: isc.DynamicForm.create({
											   valuesManager: "VMgrExtensions",
						                  autoDraw: false,
						                  width:"100%", height:"100%",
						 						wrapItemTitles:false,
						 						fields: [                                       
                                       
                                       {name:"did_id"} /// ?????
						 						]
						         })						                
						        }
						       */
						        
						    ]
              });
              VMUserOptsDefActData.setFields(getDynamicField( 'conference','call_followme_ontimeout_var'));
		   
		      break;
		    
		   case 'Route':
		       theForm = isc.DynamicForm.create( frm_default_options, {
		       					  numCols: 2, 
                                  fields: [
                                    {name:"name", width:250},
                                    {name:"route_enabled"},
                                    {name:"outbound_callerid"},
                                    {name:"context_script", width:250}
                                    
                                  ]}
                             );
             break;    
             
                  
		   case 'Inbound':
		       theForm = isc.DynamicForm.create( frm_default_options, {
		       							 numCols: 2,
                                  fields: [                                    
                                    {name:"did_id",editorType:"SelectItem", width:300,
                                            canEdit:true, title: "Inbound DID",
														  optionDataSource: getTenantDS('items'),  
														  optionCriteria: { 'item_type' : "did" },
				 										  displayField:"name", valueField:"id"},				 			        
				 			        {name:"description",width:300},
				 			        {name:"context_script",width:300},
				 					{name:'tag'},
                                    {name:"is_enabled"}                                    
                                  ]}
                             );
             break;    
             
             
		      
		   case 'Tenants':   
             isc.ValuesManager.create({ ID: "VMgrTenants", dataSource: "DSTenants" });             
             theForm = isc.TabSet.create({
								    ID: "theTenantTabs",
								    width:"100%", height:"100%",
								    tabs:[
								        { title: "PBX options",  
								          pane: isc.DynamicForm.create( frm_default_options, {
								                       numCols: 4,autoDraw: false,	 
								                       valuesManager: "VMgrTenants",
								                       fields: [
								                                 {name:"id",canEdit:false, readOnlyDisplay:'static'},								                       			 
								                                 {name:"title"},		
								                                 {name:"intertenant_routing", type:"select", title:"Allow calls exchange with:", 
										                             ID: 'InterExchangeRouting',
										                             width:180,
										                   		     multiple:true,		
										                   		     multipleAppearance:"picklist",
										                   		     editorType: "SelectItem",										                   		     
										                   		     optionDataSource: "ShowTenantsDS", 
										                   		     displayField:"title", valueField:"id"
								                   		         },						                                 
								                                 {name:'ref_id'},
								                       			 {name:"outbound_callerid", width:180},								                       			 
														         {name:"encrypt_sip_secrets",labelAsTitle:true},
														         {name:"outbound_callername", width:180},
								                                 {name:"enable_status_subscription", labelAsTitle:true},
								                       			 {name:"logo_image", width:180 },								                       			 
								                       			 {name:"vm_operator_exten"},
								                       			 {name:"default_call_recording"}

								                       			]
								                })
								         },
								         { title: 'IVR Prompts',
								           pane: isc.DynamicForm.create({
								           			numCols: 2,autoDraw: false,	 
								           			valuesManager: "VMgrTenants",
								                     fields: [ 
								                        {name:"default_tts_lang", width:300},
								                        {name:"sounds_language", width:300},
								                        {name:"general_error_message", width:300},
								                        {name:"general_invalid_message", width:300}
								           			 ]
								                })
								     	 },
								         { title: "Call Parking", 
								           pane: isc.DynamicForm.create({								           				 
													   numCols: 4, 
								                       valuesManager: "VMgrTenants",	
								                       wrapItemTitles:false,
								                       autoDraw: false,					                  
								 						     fields: [ {name:"parkext"},
								 						     				{name:"parkpos"},
								 						     				{name:"parkfindslot"},
								 						     				{name:"parkingtime"},
								 						     				{name:"parkcomebacktoorigin"},
								 						     				{name:"parkedmusicclass"},
								 						     				{type:'header', defaultValue:'Park & Announce options',align:'center'},
								 						     				{name:"parkext_announce",  showIf: isNew?"1==0":"1==1",
								 						     				       //dataArrived:  function(f,l,rows){  
 	   										                            //   if (!isNew )  this.setValue(JSON.parse(this.getValue()));}
 	   										                     },
 	   										                     {name: "paging_interval"},
 	   										                     {name: "paging_retry_count"},
 	   										                     {name: "parked_ontimeout_ivr"}
								 						     			 ]
								 						})
								         },
								         { title: "PBX Limits", 
								           pane: isc.DynamicForm.create({								           				 
										        numCols: 4, 
								                       valuesManager: "VMgrTenants",	
								                       wrapItemTitles:false,
								                       autoDraw: false,					                  
								 						     fields: [ {name:"active_calls_limit",title:"Max Active calls<br>Inbound + Outbound"},
								 						               {name:"active_calls",canEdit:false,readOnlyDisplay:'static',title:"Current active calls"},
								 						               {name:"extensions_count_limit"},
								 						     		{name:"extensions_count",canEdit:false,readOnlyDisplay:'static'},
																{name:"shabash"},
																{name:"cdr_rows"},
																{name:"archivate_cdrs_after"}
								 						     				
								 						     			 ]
								 						})
								         }

								        // { title: "Missed call reports",
								         // pane: isc.VStack.create({width:"100%",membersAlign: "center", members:[
			                             //    isc.ToolStrip.create({
						                 //          height:30, width:"100%", align: "right",membersAlign: "right",
						                 //          members: [
						                 //               isc.Label.create({ID:'shift_reports',align:"left",valign:"left",width:'100%', contents:" Report missed call in queues and ringgroups, group by shifts time intervals, send to email"}),
						                 //               isc.ToolStripButton.create({ title: "Add Shift", click: function(){ 								       //tblTenant_shiftreports.startEditingNew(); }, icon:"[SKIN]actions/add.png"}),
						                 //               isc.ToolStripButton.create({ title: "Delete", click: function(){tblTenant_shiftreports.removeSelectedData(); },icon:"[SKIN]actions/cancel.png" }),
						                 //               ]
						                 //       }),        
									      //         isc.ListGrid.create({
									       //        	                    ID:'tblTenant_shiftreports',
									       //        	                    dataSource:"DSTenantShiftReports",
									        //       	                    timeFormatter: 'TOSHORT24HOURTIME',
									       //        						height:180,
									       //        						width:"100%",
									       //        						autoSaveEdits:false,
									       //       						canEdit:true, 
									       //                             fields:[{name:'shift_start',width:100},
									       //                                     {name:'shift_end',width:100},
									       //                                     {name:'send_to_email'},
									       //                                     {name:'tenant_id'} 
									       //                             ]
									       //         })
									       //      ]}
									      //)       
								         //}
								     ]         
				         })              
		      break;

		      case 'Adminusers':   
             theForm = isc.TabSet.create({
								    ID: "theAdminUsersTabs",
								    width:"100%", height:"100%",
								    tabs:[
								         { title: "Account Settings",
								         	pane: isc.DynamicForm.create({
								         		     numCols:4,
								         	         fields:[ {name:'user'},{name:'pass'},
								         	                  {name:'user_fname'},{name:'user_lname'},
								         	                  {name:'email'},{name:'role'},
								         			          {name:'default_tenant_id'},{name:'sip_user_id'},{name:'last_login'},{name:'status'},	
								         			          {name:'gui_style'},{name:'allowed_sections'}  
								         			        ] 
								         			 },
								         			 frm_default_options )

								         },
								         { title: "User actions log",  								         
								            pane: isc.VStack.create({width:"100%",
									             members:[
							                        isc.DynamicForm.create({ numCols:4,dataSource:'DSAdminusers',fields:[{name:'status',},{name:'last_login_ip'}] }),
	 						                        isc.ListGrid.create({ID:'tblAdminUserLogs', dataSource:"DSAdminuserLOG", timeFormatter: 'toShort24HourTime',width:"100%",height:"80%",canEdit:false }),
	 						                        isc.ToolStripButton.create({ memberAlign:'right',title: "Clear user action log ", icon:"[SKIN]actions/clear.png",
	 						                                                      click: function(){ $.post('jaxer.php', { 'clear_admin_log' :  frmAdminusers.getValues()['id'] },
	 						                                                      	                         function(data){
	 						                                                      	                         	isc.say(data.error);
	 						                                                      	                         	tblAdminUserLogs.getDataSource().invalidateCache();
		      																									tblAdminUserLogs.setData(isc.ResultSet.create({ dataSource : tblAdminUserLogs.getDataSource() }) );	
		      																									tblAdminUserLogs.fetchRelatedData( tblAdminusers.getSelectedRecord(), DSAdminusers);
 
	 						                                                      	                         },
	 						                                                      	                        'json')} })
										               ]}
									      )
								       }
								     ]         
				         })              
		      break;
		      
		      default:
		         theForm = isc.DynamicForm.create(frm_default_options);
		         if(theForm.getItem('editIcon'))
                      theForm.hideItem('editIcon');
                 if(theForm.getItem('sendIcon'))
                      theForm.hideItem('sendIcon');
               
     }
     
        
     return theForm;
}



function getFormTitle( object_name ){
	 return isc.ToolStrip.create({
			                           height:40,
			                           width:"100%",
			                           align: "center",
											   membersalign: "center",
			                           members: [  
														 isc.Label.create({
																	    height: "100%", width:"100%",
																	    padding: 2,autoDraw:false,
																	    align: "center", valign: "center",
																	    wrap: false,
																	    //icon: "icons/16/approved.png",				    
																		 icon:  object_name + ".png",
																	    contents: "<span style='color:#668B8B;font-size:18px;font-weight:bold;'>Edit <i>" + object_name + "</i> </span>"
													     }),
													    ( object_name == 'IVRMenu')    ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow: 5px 4px 3px silver;' autoplay id='IVRplayer'  controls> </audio>&nbsp;&nbsp;"}) : '',													    
													    ( object_name == 'Tenants')    ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow: 5px 4px 3px silver;' autoplay id='TENANTPlayer'  controls> </audio>&nbsp;&nbsp;"}) : '',
													    ( object_name == 'Ringgroups') ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow: 5px 4px 3px silver;' autoplay id='RGplayer'  controls> </audio>&nbsp;&nbsp;"}) : '',
													    ( object_name == 'Conferences') ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow:5px 4px 3px silver;' autoplay id='CONFplayer'  controls> </audio>&nbsp;&nbsp;"}) : '',
													    ( object_name == 'CDRs') ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow:5px 4px 3px silver;visibility:hidden;' id='CDRplayer'  controls> </audio>&nbsp;&nbsp;"}) : '',
														 ( object_name == 'Recordings') ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow:5px 4px 3px silver;' id='RecordingPlayer' controls> </audio>&nbsp;&nbsp; "}) : '',
														 ( object_name == 'MOH')    ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='padding:0 3px;opacity:0.5;2width:197px;box-shadow: 5px 4px 3px silver;' autoplay id='MOHplayer'  controls> </audio>&nbsp;&nbsp;"}) : '',
													    
                                      ]
                                    })
}



function getFormButtons(object_name){	
 // var  OnClickSave;
 // Define Save Data code action (example: callback function after Save pressed. or save several forms ) )
	switch(object_name){

    case 'Extensions':
	   var onClickSave = "";
  	       onClickSave = onClickSave +  "frmvm_users.setValue( 'email',frmExtensions.getValues()['email'] );";  	        
          onClickSave = onClickSave +  " if ( frmExtCallerID.getValue() == 'Use default' ) { VMgrExtensions.setValue('outbound_callerid','' );} "; 
          onClickSave = onClickSave +  " if ( frmExtCallerName.getValue() == 'Use default' ) { VMgrExtensions.setValue('outbound_callername','' );} ";  	      
          onClickSave = onClickSave +  " if ( frmIntCallerID.getValue() == 'Use default' ) { VMgrExtensions.setValue('internal_callerid','' );} "; 
          onClickSave = onClickSave +  " if ( frmIntCallerName.getValue() == 'Use default' ) { VMgrExtensions.setValue('internal_callername','' );} ";
          onClickSave = onClickSave +  "VMgrExtensions.saveData(function(response,data){ VMgrUserOptions.setValue('t_sip_user_id',data.id); VMgrUserOptions.saveData();  ";
          onClickSave = onClickSave +  "                                                 tblUserBlockList.saveAllEdits(); tblUserScreenList.saveAllEdits();tblUserFollowMeList.saveAllEdits();  });";
           // To Do: Set default foreignKey for userBlockList when adding new record, when existing - we set defaultValue on open form //
           // /*tblUserBlockList.getAllEditRows().map(function(recNum){ tblUserBlockList.getRecord(recNum).t_sip_user_id = 123;});
          
  	       onClickSave = onClickSave +  "frmvm_users.saveData();tblUserDevices.saveAllEdits();";
  	       onClickSave = onClickSave +  "wndExtensions.clear();TriggerEvent('reload_Extensions');";
  	       
  	       
             	       
  	       
	    break;
	    
	 case 'Tenants':
	    var onClickSave = "";        
           onClickSave = onClickSave +  "VMgrTenants.saveData();wndTenants.clear();TriggerEvent('reload_tenants');";
       break;
       
	 default:
	    var onClickSave = "frm" + object_name + ".saveData(); wnd" + object_name + ".clear(); TriggerEvent('reload_" + object_name + "')";
	    
	}
	
	console.log( 'for ' + object_name + ' Configured Submit actions:' + onClickSave );

// Return controls:    
    return isc.HLayout.create({
					         	width:"100%",
					         	members: [
			                        isc.ToolStrip.create({
			                           height:40,
			                           width:"100%",
			                           align: "center",
											   membersalign: "center",
			                           members: [
			                                (object_name!='CDRs')?isc.ToolStripButton.create({ title: "Save",   click: onClickSave, icon:"[SKIN]actions/save.png"}):'null',
			                                isc.ToolStripButton.create({ title: "Cancel", click: "wnd"+object_name+".destroy();",icon:"[SKIN]actions/cancel.png" }),
			                                isc.ToolStripButton.create({ title: "Debug", click: "console.log( wnd"+object_name+".getWidth() + ' - ' + wnd"+object_name+".getHeight() );",icon:"[SKIN]actions/cancel.png"})													  
			                              ]                
			                        })
			                      ]
			                 })
}




function getSearchForm(GridName){
 	return isc.DynamicForm.create({ autoDraw:false,  
 	                          items: [{ name:"search",type:"string", title:"Search", showPickerIcon: true, pickerIconSrc:"[SKIN]/pickers/search_picker.png",
				                           pickerIconClick:  "tbl"+GridName+".fetchData({ 'file_name' : form.getValue('search').asHTML() }, tbl"+GridName+".Refresh() );" }]  
				              });
 }

function getMediaUploader( reference, ctrlID, action_script ){
	return  isc.DynamicForm.create({													
				                             encoding: "multipart",
		 											  canSubmit: true,	
		 											  maxHeight:20,			
		 											  autoDraw:false,	
		 											  wrapItemTitles:false,	  
				                             action: action_script,
				                             numCols: 4,
				                             target: "pbxUploadCallBackFrame",												     
									              fields: [ {name: "upload_file", align:'left', title: "Upload " +ctrlID, startRow:false, endRow:false,height:18,width:100, 
									                                editorType:"UploadItem", required:true },
									                      //  {name: "dst_filename", ID: 'MediaUplDst'+reference, hint:"tag",type:"string",showHintInField:true,width:100,showTitle:false},
									              			   {name: "directory", type:"hidden", defaultValue: reference },
									              			   {name: "sender_obj", type:"hidden", defaultValue:ctrlID },
									                        {title:'Upload', ID: 'MediaUplBTN'+ctrlID, type: "button",
									                                target: "javascript", startRow:false, 
									                                endRow:false, hint:"", showHintInField:true,
									                                click: function(){
									                                	  if ( this.form.getValues()['upload_file'] == null ){
																							isc.say("File name is required!");
																				   		return false;
									                                	  }
									                                	      this.form.setValue('directory' , reference );
									                                	      this.setHint('Uploading... ' );
									                                	      this.form.submitForm();									                                	        
									                                }
									                        }        
									                      ]
							               });
 }
 
 
 function ShowChangeUserPassWnd( UID ){
 	isc.Window.create({
        					 ID: "wndChangeUserPass",
						    title: "Change current password" ,
		                autoDraw: true, autoSize: true,
		                saveOnEnter:true,  
		                autoCenter: true, 
		                isModal: true, 		    
						    items: [
						      isc.DynamicForm.create({
		 											  canSubmit: true,
		 											  ID: "frmChangeUPass",
		 											  autoDraw:false,	
		 											  wrapItemTitles:false,	  
				                             action: 'jaxer.php',				                             												     
									              fields: [ {title:" New Password:",name:"change_password",type:"password",required: true }, 
									                        {title:" Confirm Password:",name:"confirm",type:"password", required: true,validators: [{ type: "matchesField",  otherField: "change_password", }]  },
									                        {title:'Update', type: "button", click: function(){ 
									                                if ( this.form.validate() ) {
									                                    $.post('jaxer.php', { 'change_passwd' :  this.form.getValues()['change_password'] },
																				     function (data) {
																		 			   if (data.success)
																		 	           wndChangeUserPass.clear();
																					   else{
  																		     			   isc.say('ERROR: Failed to update password: ' + data.error );
  																		     			   wndChangeUserPass.clear();
  																		     			}   
																	              },
																	             'json');
																	          }   
									                               }
									                        }  
									              ]})
						     ]
         });
 	 
 }

function change_pwd(pwd){ 
    
 }																	   

function ShowCSVUploadWnd(csvObjType = 'DIDs',refRowID = 0){
        isc.Window.create({
        					 ID: "wndCSVUpload",
						    title: "Upload "+csvObjType+" from CSV file" ,
		                autoDraw: true, autoSize: true,  
		                autoCenter: true,canDragReposition: true, canDragResize: true, 
		                isModal: true, redrawOnResize:false, 
		                showModalMask: true,		    
						    items: [ getMediaUploader( refRowID, csvObjType, "CSVManager.php"  ) ]
         });
     //MediaWND.show();    
 }	

 
function ShowMediaUploadWnd(media_folder, sender_obj){
        isc.Window.create({
        					 ID: "wndMediaUpload",
						    title: "Upload Media file" ,
		                autoDraw: true, autoSize: true,  autoCenter: true,canDragReposition: true, canDragResize: true, isModal: true, redrawOnResize:false, showModalMask: true,		    
						    items: [ getMediaUploader( media_folder, sender_obj, "fileManager.php" ) ]
         });
     //MediaWND.show();    
 }	
 				
 // This Function called  in response from pbxUploadCallBackFrame 
function uploadComplete(fileName, directory, sender_obj){
			   console.log(sender_obj + ' File Uploading completed: '+fileName + '  ' + directory );
			   
			   if ( sender_obj == 'moh' ){
			     MediaUplBTNmoh.setHint('');			     
//			     MediaUplDstmoh.clearValue() ;
//				  ShowMOHFilesDS.invalidateCache();
//              tblMOHFiles.fetchData({'directory': directory });
              tblMOHFiles.Refresh()
            }
            if ( sender_obj == 'SndDefault' ){
            	MediaUplBTNSndDefault.setHint('');
  //          	MediaUplDstSndDefault.clearValue() ;            	
            	tblSndDefault.Refresh();
            }    	
            if ( sender_obj == 'SndTenants' ){
            	MediaUplBTNSndTenants.setHint('');
  //          	MediaUplDstSndTenants.clearValue() ;            	
            	tblSndTenants.Refresh();
            }    	
            if ( sender_obj == 'WndIvrMedia' ){
            	frmIVRMenu.setValue( 'announcement', fileName);
               //frmIVRMenu.setValue( 'recordings_lang', 'tenant');
               frmIVRMenu.setValue( 'announcement_type', 'recording');
            	wndMediaUpload.clear();
            }	
            if ( sender_obj == 'WndIvrMediaItem' ){
               console.log('item_data set to ' + fileName);
               IVRMenuItemsGrid.setEditValue(IVRMenuItemsGrid.getEditRow(),'item_data',fileName);
               IVRMenuItemsGrid.saveAllEdits();
            	wndMediaUpload.clear();
            }
            
            if (sender_obj == 'DIDs') {
            	wndCSVUpload.clear();
            	TriggerEvent('show_dids');
            }
            
            if (sender_obj == 'Leads') {
            	//wndCSVUpload.clear();            	
            	MediaUplBTNLeads.setHint('');
  //          	TriggerEvent('show_campaigns');
               frmCampaigns.saveData(function(response,data,request){ tblCampaigns.selectRecord(data);  
                                                                      frmCampaigns.editSelectedData(tblCampaigns);
                                                                      frmCampaigns.setValue('lead_field_names', JSON.parse(frmCampaigns.getValues()['lead_field_names']) );
                                                                     })            	
            	
//            	frmCampaigns.setValue('leads_total', tblCampaigns.getSelectedRecord()['leads_total'] );
 
            }
            
            if ( sender_obj == 'WndIvrMediaItems' ){
               IVRMenuItemsGrid.setEditValue(IVRMenuItemsGrid.getEditRow(),'announcement',fileName);
               IVRMenuItemsGrid.setEditValue(IVRMenuItemsGrid.getEditRow(),'announcement_type', 'recording');
            	wndMediaUpload.clear();
            }	
}		               


function new_object(obj_name){
	 edit_object(obj_name, true);
}



function edit_object(obj_name, isNew){
  
   
  console.log('Edit object: ' + obj_name );
 // Default Window Form Container//
  var theWnd =  isc.Window.create({
		    ID: "wnd"+obj_name,
		    title: obj_name ,
		    autoSize: false,
		    autoCenter: true,		    
		    canDragReposition: true,
          canDragResize: true,
		    isModal: true,
		    autoDraw:false,
		    redrawOnResize:false,
		    showModalMask: true,
		    showShadow:true,
          shadowDepth: 8,		
          headerIconDefaults: {width:16, height: 16, src: obj_name+'.png'},    
		    items: [
		         isc.VLayout.create({		         	
		         	height:"100%",
		         	members: [
		         	       getFormTitle( obj_name ),
						   getForm( obj_name, isNew ),
			               getFormButtons( obj_name )
                        ]
                    })
                 ]
            })  
            
 // RESIZERRRRRRRRRRRRRRRRRRRRRRRRR //           
// Window customization ( b/c autosize  does not work ): 
  switch ( obj_name ){
   case 'Campaigns' :
     theWnd.resizeTo(630, 340); //      
     break;
  	
   case 'MOH' :
   case 'MOHDefault' :
     theWnd.resizeTo(540, 450); //      
     break;
     
   case 'Trunks' :
     theWnd.resizeTo(700, 440); //      
     break;
   
   case 'Conferences' :        
   case 'Extensions' :
      theWnd.resizeTo(650, 510); //
      break;
   
   case 'CDRs' :   
   case 'Recordings' :
      theWnd.resizeTo(450,550); // 
      break;
      
   case 'Tenants' :
      theWnd.resizeTo(690,410); // 
      break;
      
   case 'Queues' :
      theWnd.resizeTo(667, 587); //   
      break;
      
   case 'Pagegroups' :
      theWnd.resizeTo(740,420); //   
      break;   
      
   case 'IVRMenu' :
      theWnd.resizeTo(767,550); //
      break;   
      
   case 'Ringgroups' :
      theWnd.resizeTo(650,534); //
      break;
      
   case 'Featuresdef' :
      theWnd.resizeTo(544,350); //
      break;
            
   case 'Adminusers' :
      theWnd.resizeTo(540,400); //
      break;       

   case 'Shifts' :         
      theWnd.resizeTo(550,250); //
      break;       
      
   case 'Route' :       
      theWnd.resizeTo(400,250); //
      break;       

   case 'Inbound' :    
      theWnd.resizeTo(430,300); //
      break;       
            
   
   case 'Features':
   case 'DIDs' :   
   case 'Blacklist' :
      theWnd.resizeTo(320,250); // 
      break;
     
      
  }                
            
  theWnd.show();
 
}




// Tenant Selector DropDown	
 isc.DynamicForm.create({
	  	  ID: "frmSwitchTenant",
	  	  autoDraw: false,
	  	  padding: "22 0",
	  	  height: "100%",
	     wrapItemTitles:false,
	     items: [  { name: "title",
						  ID: "selected_tenant",
		              autoFetchData:true,
		              editable: false,
			      	 // defaultToFirstOption : true,
			      	  title: "<b>Current Tenant:</b>",	      	  
			           optionDataSource: "ShowTenantsDS",
						  editorType: "SelectItem",
			           displayField:"title", 
			           valueField: "id",
			           Refresh: function () {
                 		    	ShowTenantsDS.invalidateCache();
 		                     this.setOptionData(isc.ResultSet.create({ dataSource : ShowTenantsDS }) );
 
             	    },
			           dataArrived:  function(f,l,tenants_list){
			           	     var active = tenants_list.find("is_selected", 1);
			           	     if( active )  
			           	        this.setValue(active.id);
			           	     else   
			           	        this.setValue(1);
			           	},
			           changed: function (form,item,value) {
		                  //isc.say("Changed to " + value);
								$.post('jaxer.php',
										 { 'switch_tenant_to':value },
										 function (data) {
										 	if (data.success){
										 		
										 		TriggerEvent('show_'+Sections.getVisibleSections().get(0).replace('section_',''));
										 		
										 		  
		                            
		                             // var i = inbound_did_id.getOptionDataSource();
		                             // i.fetchData({ 'item_type':'did'}, function (){ console.log('sdsdsd'); } );   
										 		// IVRMenuVMPickList.getOptionsDatasource().fetchData();
										 	  //mTree.deselectAllRecords();
											  //mTree.selectRecord(0); // Select first node //
											  //tblTenants.selectRecord(2);
											  //RefreshSection('tenants');
											  //Sections.expandSection('section_tenants');
											}else{
										     isc.say("ERROR: Failed to switch tenant");		 
											}   
									    },
									   'json' ); 
			           	},
			           pickListProperties: {formatCellValue: function(value,record){ 
							           		    		var ret = " [ "+record.id+" ] " + record.title + " " ;
							           		    		if ( record.is_selected ) return "<b>"+ret+"</b>" 
							           		    		else return ret;
			                                  }
			                               }                        
	        }],
	        Refresh: function(){ 
	                 var i = this.getItem('title'); 
		              i.getOptionDataSource().invalidateCache();
		              i.fetchData();
		   }            
		   
  })
	
	
	
  function getDynamicField( action, field_name = 'default_action_data' ){
	                                	 fields = new Array();
	                                	 switch( action ){
	                                	 	    case "repeat":
                                       	 	  fields.push({name:field_name,type:'string', editorType:"TextItem",title:'How many times',optionDataSource:''});
						 									  break;
						 									  
	                                	 	    case "number":
	                                	 	    case "play_tts":
	                                	 	    case "disa":
	                                	 	    case "exec_cmd":   	
	                                	 	        fields.push({name:field_name,type:'string', editorType:"TextItem",title:'Option',optionDataSource:''});
						 									  break;
						 									  
						 								 case "play_rec":
											        	 case "extension":
											        	 case "moh":
							                      case "ringgroup":
														 case "pagegroup":
							                      case "ivrmenu":
							                      case "featurecode":
							                      case "queue":
							                      case "conference":
							                      case "checvm":
							                      case "followme":
							                      case "dirbyname":														                      
														 case "voicemail":	 
																 fields.push({name:field_name,
																			      editorType:"SelectItem",title:'Name',
																	            optionDataSource: getTenantDS('items'),
																	            displayField:"name", valueField:"id",
				 																   pickListCriteria : { item_type: action }
						 										  });  	
						 									   break;
						 							    default:
						 							        fields.push({name:field_name, hidden:true});
						 	                 }			
						 	                 console.log('Got ctrl for:' + action +' fname:' + field_name );						
	                                   return fields;  
 }	
