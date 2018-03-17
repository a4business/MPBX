
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
         
  
  switch ( object_name ){  	 
    case 'Trunks' :
		 theForm = isc.DynamicForm.create(frm_default_options, 
		 									{ numCols: 4 },		       	             
                                 {fields: [
	   										{name:"name"}, {name:"trunk_type"},{name:"host" },{name:"dial_timeout"},{name:"status"},
	   										{name:"context"},{ name:"defaultuser" },{name:"secret"},	   										
					  							{name:"domain"}, {name:"description"},
					  							{name:"sip_register",colSpan:"*", width:"415"},
					  							{name:"other_options",editorType:"TextAreaItem", colSpan:"*"},					  							
	                                 //{defaultValue: "Trunk Limits", type:"section", sectionExpanded:false, width:"100%",colSpan:"*",
	                                 //itemIds: ["inTenants","max_concurrent_calls", "max_call_duration" ] },
	                                 {name:"max_concurrent_calls", type:"integer", title:"Max Concurrent calls", defaultValue:0},
			    				            {name:"max_call_duration", type:"integer", title:"Max Call duration", defaultValue:0},
			                           {name:"inTenants", type:"select", title:"Available in Tenants:", 
				                             ID: 'inTenField',
				                             colSpan:5, width:415,
				                   		     multiple:true,		
				                   		     multipleAppearance:"picklist",
				                   		     editorType: "SelectItem",
				                   		     optionDataSource: "ShowTenantsDS", 
				                   		     displayField:"title", valueField:"id"
			                   		    }
			                   		   
                                 ]}
                             );   
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
												            			{name:'announcement_file', icons: [{ click: function(form){ CONFplayer.src = 'media/play.php?mode=recording&play_text=' + form.getValue('announcement_file') + '&play_dir=tenant'; }, src: "[SKIN]/pickers/play_picker.png", }] },                            
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
                                        {name:'name'},
                                        {name:'announcement_file',  icons: [{ src: "[SKIN]/pickers/play_picker.png",
																            click:  function(form){ RGplayer.src = 'media/play.php?mode=recording&play_text=' + form.getValue('announcement_file') + '&play_dir=tenant'; }
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
															            			{name:'announcement_file', icons: [{ click: function(form){ RGplayer.src = 'media/play.php?mode=recording&play_text=' + form.getValue('announcement_file') + '&play_dir=tenant'; }, src: "[SKIN]/pickers/play_picker.png", }] },                            
				                                                      {name:'timeout'},
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
	                           fields:[{ name:'default_action', change: function(viewer,item,value){
	                           	           theRGForm3.setFields( getDynamicField(value) ); 
	                           	           theRGForm3.setValue('default_action_data',''); },
	                           	         redrawOnChange:true  }
	                           	    ]
                 });
	              theRGForm3 = isc.DynamicForm.create({  valuesManager: 'frm'+object_name, autoDraw: false, wrapItemTitles:false });
	              theRGForm3.setFields( getDynamicField( tblRinggroups.getSelectedRecord()['default_action'] ) );
	              
	              theForm = isc.VLayout.create({ height:'100%', members: [
	                            theRGForm,
	                            theButtons,
	                            theGrid,
	                            isc.HStack.create({ membersMargin:10, height:25, padding:4, align:'center',  autoDraw:false,members:[theRGForm2,theRGForm3 ]})
	                          ]  
		                    }); 
		                    
	                return theForm;
              break;		 
		    
         case 'Queues':
		           //isc.ValuesManager.create({ ID: "VMgrIVRMenu", dataSource: "DSIVRMenu" });
		           //valuesManager: "VMgrExtensions",

                // Queues Form
		           theForm = isc.DynamicForm.create(frm_default_options,{ numCols: 4 });
                // Queues members :		                       
                  if ( !isNew ){
						      isc.defineClass("QMDragListGrid","ListGrid").addProperties({
								    width:"43%", 
								    border:"1px solid silver",
								    alternateRecordStyles:true, 
								    canReorderRecords: true,
								    leaveScrollbarGap:false,
								    emptyMessage:"Drag &amp; drop Queue Members here",
								    canAcceptDroppedRecords: true,
									 canDragRecordsOut: true,
								});
								
							var QmembersBox =	isc.HStack.create({membersMargin:1, height:200, boder:'none',padding:10, align:'center',  autoDraw:false,
							                    members:[
														    isc.QMDragListGrid.create({														    	  
														        ID:"tblQMembersAvailable",
														        dataSource: ShowTenantQueuesmembers,
														        fields:[   {name:"membername"}, {name:"interface"},{name:"paused",hidden:true} ]
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
														        fields:[  {name:"membername"}, {name:"interface"},  {name:"paused", width:'*', canEdit:true } ]										        
														    })
								                    ]
								                 });
								   
								
    						 theForm = isc.SectionStack.create({
										    width: "100%", height: "100%",  border:"none",  autoDraw:false,  visibilityMode: "multiple",					     
										    ID:"IVRMenuFormSections",		    
										    sections: [																	       
										       { showHeader:false, title: "-",  items: [ frmQueues ] },
										       { showHeader:true,  title: "Queue Members "},
										       { showHeader:false, title: "-",  items: [ QmembersBox ] } 
										    ]
	                    });                  	
                  	
                  	
                  }else{
                      theForm = frmQueues;
                  }          
		                       		    
		        break;
  
		    case 'IVRMenu':
		          // IVRMenu Form 
		           //isc.ValuesManager.create({ ID: "VMgrIVRMenu", dataSource: "DSIVRMenu" });
		           isc.DynamicForm.create(frm_default_options,{ 
		                               //valuesManager: "VMgrExtensions",
						                   numCols: 4,
						                   fields: [ 
						                      {name:"name"},{name:"description"},
						                      {type:'header', defaultValue:'Welcome announcement:'}, 
						                      {name:"announcement_type", redrawOnChange:true,
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
											             "moh":       "<b>Play Music on Hold </b><p> Play Music on Hold untill user make any selection (igmore other item audio)</p>"
											         }},
											       {name:"moh_class",         showIf: "form.getValue('announcement_type') == 'moh'"  },
											      // {name:"voicemail_box"},
								                {name:"announcement",      showIf: "form.getValue('announcement_type') != 'moh'"  },	  // has picker Icon Via datasource //
								                {name:"delay_before_start"},
								                {name:"announcement_lang", showIf: "form.getValue('announcement_type') == 'tts'"  },
								                {type:'header', defaultValue:'Options:'},
								               // {name:"recordings_lang",   showIf: "form.getValue('announcement_type') == 'recording' || form.getValue('announcement_type') == 'upload'"},
								                {name:"menu_timeout"},
                                       // {name:"ring_while_wait"},
                                        {name:"allow_dialing_exten"},
                                        {name:"allow_dialing_external"},
                                        {name:"allow_dialing_featurecode"}
                                        
								                //{name: "PlayiconField",    title: "Play", width: 110 }
								             ]   
		                       });
		                       
		                       
                  // Show related IVRMenu items only if we have IVRMenu  //                              
                 if ( !isNew  ) {
							           isc.ListGrid.create(LGDefault,{
							           	                       ID: "IVRMenuItemsGrid",
																	    // autoFitFieldWidths: true,
																	     height:200,		  
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
																					 case "dialtone":
																					 case "unassigned":  return { canEdit:false,editorType:"StaticText"};
																					  
														              	   	 case "number":   	return { canEdit:true,editorType:"TextItem"};	
														              	   	 
														              	   	 case "extension":
														                      case "ringgroup":
														                      case "ivrmenu":
														                      case "featurecode":
														                      case "queue":
														                      case "conference":
														                      case "checvm":
														                      case "followme":
														                      case "dirbyname":														                      
																					 case "voicemail":   return { editorType:"SelectItem",
																				                                  canEdit:true,
																											            // optionDataSource:"ShowTenantItemsDS",
																											            optionDataSource: getTenantDS('items'), 
						 																									 displayField:"name", valueField:"name",
																											             getPickListFilterCriteria : function () {
																											                var current_item_type = this.grid.getEditedCell(this.rowNum, "item_action");
																											                return { item_type:current_item_type };
																											             }
																											         };
														                  }
														              },   														               
														              fields: [ 
														                        {name:'t_ivrmenu_id', defaultValue: tblIVRMenu.getSelectedRecord()['id'], hidden:true },
														                        {name:'selection',width:25 },
														                        {name:'announcement_type'},
														                        {name:'announcement',width:200},
														              				{name:'playIcon', width:22, title:"", canEdit:false, readOnlyDisplay:'static'},
														                        {name:'item_action', redrawOnChange:true},
														                        {name:"item_data"}
														                        //{name:'destination'},
														                      
														              			 ],
														              sortField: 'selection',
          															  sortDirection: "ascending",			 
														              createRecordComponent : function (record, colNum){
																			        if ( this.getFieldName(colNum)  == "playIcon" )  
																                     return   isc.ImgButton.create({
																							                showDown: false, showRollOver: false,
																							                layoutAlign: "center",
																							                src: "[SKIN]/pickers/play_picker.png",
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
					             	  
							            isc.HLayout.create({  ID:"IVRItemsBtnHLayout",
							            							 align: "center",padding:4, membersMargin: 6, autoDraw:false,
											          	  	       members: [
											          	  	           isc.IButton.create({ title:"Save",click: function(){ IVRMenuItemsGrid.saveAllEdits();} }),
																			  isc.IButton.create({ title:"New", click: function(){ IVRMenuItemsGrid.startEditingNew(); } }),
																			  isc.IButton.create({ title:"Delete",click: function(){
																		             if ( IVRMenuItemsGrid.getSelection().getLength() > 0 )     
																		                   IVRMenuItemsGrid.getSelection().map(function (item) { IVRMenuItemsGrid.removeData(item) });
																			           } 
																			       }),
											          	  	           isc.IButton.create({ title:"Discard",click: function(){ IVRMenuItemsGrid.discardAllEdits(); } })
											          	  	      ]          	  	           
					                    });
					                    
							            theForm = isc.SectionStack.create({
																	    width: "100%", height: "100%",  border:"none",  autoDraw:false,  visibilityMode: "multiple",					     
																	    ID:"IVRMenuFormSections",		    
																	    sections: [																	       
																	       { showHeader:false, title: "-",  items: [ frmIVRMenu  ] },
																	       { showHeader:true, title: "special # :    i - invalid selection pressed;   t - no input(timeout reached); "},
																	       { showHeader:false, title: "-",  items: [ isc.VLayout.create({ members: [ IVRMenuItemsGrid, IVRItemsBtnHLayout ] }) ] } 
																	    ]
								                    });
					 // We have a new IVRMenu //			                    
			         }else{
                    theForm = frmIVRMenu;   
			         }	           					       
		          
              break;               
		    
		    case 'MOH' :
		    case 'MOHDefault':
               var MohFolder = ( object_name == 'MOH' ) ? tblMOH.getSelectedRecord().directory : tblMohdefault.getSelectedRecord().directory;		         
		         theForm = isc.SectionStack.create({
								    width: "100%", height: "100%",
								    border:"none",			
								    visibilityMode: "multiple",					     
								    ID:"MOHFormSections",		    
								    sections: [
								       { showHeader:false, title: "Music on Hold Files",  items: [ isc.DynamicForm.create(frm_default_options,{numCols:4,dataSource:"DSMOH"}) ]},
								       { showHeader:false, title: "Upload MOH file",
								          items: [
   								        isc.ListGrid.create(LGDefault,{   								        	
												     ID: "tblMOHFiles",
												     height:200,		   
												     canRemoveRecords: true, 
												     dataSource: ShowMOHFilesDS,
												     Refresh: function () {
												    	this.getDataSource().invalidateCache();
												      this.setData(isc.ResultSet.create({ dataSource : this.getDataSource() }) );		    	
												    }											     
											  }),     	      
                                   isNew ? null : getMediaUploader( MohFolder, 'moh' )
                                   
							          ]} 
							       ]
			      })
		    break;      
		      
		   case 'Extensions' :
		       isc.ValuesManager.create({ ID: "VMgrExtensions", dataSource: "DSExtensions" });
				 theForm = isc.TabSet.create({
						    ID: "theExtTabs",
						    width:"100%", height:"100%",
						    tabs: [
						        { title: "General",  
						          pane: isc.HLayout.create({ align: "center",padding:4, membersMargin: 6, autoDraw:false, 
							               members:[ 						             
									           isc.DynamicForm.create(frm_default_options, {
									          	 numCols: 2, 
									          	 width:"60%",
									          	 valuesManager: "VMgrExtensions",
									          	  fields: [
									          	      {name: "extension",width:100},{name:"first_name",width:250},{name:"last_name",width:250},
									          	      {name: "email",width:250}, {name: "email_pager",width:250}
									          	  ]
									          	 }),
									            isc.Img.create({
													    opacity:30,
													    width:"40%", height:170,
													    useOpacityFilter:true,
													    src: "phone.png"
												  	})
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
                                       {name:"outbound_callerid", showTitle:false,showIf:"frmExtCallerID.getValue() != 'Use default'",  startRow:false },
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
						        { title: "Device", 
						          pane: isc.DynamicForm.create({
											   ID: 'frmext_device',
						                  autoDraw: false,
						                  numCols: 4,
						                  valuesManager: "VMgrExtensions",
						                  width:"100%", height:"100%",
						 						wrapItemTitles:false,
						 						items: [
						 						 {name: "name"},{name: "nat"},
						 						 {name: "secret"}, {name: "videosupport"},
						 						 {name: "allow"}, {name: "qualify"},
						          	       {name: "dtmfmode"},{name: "enable_mwi"},
						          	       {name: "other_options", editorType:"TextAreaItem", colSpan:"*"},
						          	       {type: "header", defaultValue: "Device registration information"}, 
						          	       {name:"useragent",canEdit:false,readOnlyDisplay:'static'},
						          	       {name:"ipaddr",canEdit:false,readOnlyDisplay:'static'}
						 					]
						           })    
						        },
						        { title: "VoiceMail", 
						          pane: isc.DynamicForm.create({
											   ID: 'frmvm_users',
											   dataSource: "DSvm_users",
						                  autoDraw: false,
						                  width:"100%", height:"100%",
						 						wrapItemTitles:false
						         })    
						        },
						         { title: "Routing", 
						          pane: isc.DynamicForm.create({
											   valuesManager: "VMgrExtensions",
						                  autoDraw: false,
						                  width:"100%", height:"100%",
						 						wrapItemTitles:false,
						 						fields: [                                       
                                       {name:"outbound_route"},
                                       {name:"did_id"} /// ?????
						 						]
						         })     
						        }
						        
						    ]
              });
		   
		      break;
		    
		   case 'Route':
		       theForm = isc.DynamicForm.create( frm_default_options, {
		       							 numCols: 2, 
                                  fields: [
                                    {name:"name"},
                                    {name:"route_enbaled"},
                                    {name:"outbound_callerid"},
                                    {defaultValue: "Route entries", type:"section", sectionExpanded:false, width:"100%",
                                     itemIds: [ "route_entries" ]}
                                  ]}
                             );
             break;    
             
                  
		   case 'Inbound':
		       theForm = isc.DynamicForm.create( frm_default_options, {
		       							 numCols: 2,
                                  fields: [
                                    {name:"description"},
                                    {name:"did_id",editorType:"SelectItem", 
                                            canEdit:true, title: "Inbound DID",
														  optionDataSource: getTenantDS('items'),  
														  optionCriteria: { 'item_type' : "did" },
				 										  displayField:"name", valueField:"id"},
                                    {name:"is_enabled"},
                                    {defaultValue: "Inbound Rules", type:"section", sectionExpanded:false, width:"100%",
                                     itemIds: [ "route_entries" ]}
                                  ]}
                             );
             break;    
             
             
		      
		   case 'Tenants':   
             isc.ValuesManager.create({ ID: "VMgrTenants", dataSource: "DSTenants" });             
             theForm = isc.TabSet.create({
								    ID: "theTenantTabs",
								    width:"100%", height:"100%",
								    tabs:[
								        { title: "General",  
								          pane: isc.DynamicForm.create( frm_default_options, {
								                       numCols: 4,autoDraw: false,	 
								                       valuesManager: "VMgrTenants",
								                       fields: [ {name:"id",canEdit:false,readOnlyDisplay:'static'},
								                                 {name:'ref_id'},
								                                 {name:"title"},
								                       				{name:"max_extensions"},
								                       				{name:"sounds_language"},
								                       				{name:"general_error_message"},
								                       				{name:"outbound_callerid"},
								                       				{name:"outbound_callername"}
								                       				
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
								 						     				{name:"parkedmusicclass"}
								 						     			 ]
								 						})
								         }
								     ]         
				         })              
		      break;
		      default:
		         theForm = isc.DynamicForm.create(frm_default_options);
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
																	    icon: "icons/16/approved.png",				    
																	    contents: "<span style='color:#668B8B;font-size:18px;font-weight:bold;'>Edit <i>" + object_name + "</i> </span>"
													     }),
													    ( object_name == 'IVRMenu')    ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow: 5px 4px 3px silver;' autoplay id='IVRplayer'  controls> </audio>&nbsp;&nbsp;"}) : '',
													    ( object_name == 'Ringgroups') ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow: 5px 4px 3px silver;' autoplay id='RGplayer'  controls> </audio>&nbsp;&nbsp;"}) : '',
													    ( object_name == 'Conferences') ? isc.HTMLFlow.create({ contents:"&nbsp;<audio style='width:197px;box-shadow:5px 4px 3px silver;' autoplay id='CONFplayer'  controls> </audio>&nbsp;&nbsp;"}) : ''
													    
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
  	       onClickSave = onClickSave +  "frmvm_users.saveData();VMgrExtensions.saveData();";  	     
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
			                                isc.ToolStripButton.create({ title: "Save",   click: onClickSave, icon:"[SKIN]actions/save.png"}),
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

function getMediaUploader( media_folder, ctrlID ){
	return  isc.DynamicForm.create({													
				                             encoding: "multipart",
		 											  canSubmit: true,	
		 											  maxHeight:20,			
		 											  autoDraw:false,	
		 											  wrapItemTitles:false,	  
				                             action: "fileManager.php",
				                             numCols: 4,
				                             target: "pbxUploadCallBackFrame",												     
									              fields: [ {name: "upload_file", align:'left', title:"Upload file", startRow:false, endRow:false,height:18,width:120, editorType:"UploadItem", required:true },
									                        {name: "dst_filename", ID: 'MediaUplDst'+ctrlID, hint:"(new name)",type:"string",showHintInField:true,width:90,showTitle:false},
									              			   {name: "directory", type:"hidden", defaultValue: media_folder },
									              			   {name: "obj", type:"hidden", defaultValue:ctrlID },
									                        {title:'Upload', ID: 'MediaUplBTN'+ctrlID, type: "button", target: "javascript", startRow:false, endRow:false, hint:"", showHintInField:true,
									                                click: function(){
									                                	  if ( this.form.getValues()['upload_file'] == null ){
																							isc.say("File name is required!");
																				   		return false;
									                                	  }
									                                	      this.form.setValue('directory' , media_folder );
									                                	      this.setHint(' Uploading... ' );
									                                	      this.form.submitForm();									                                	        
									                                }
									                        }        
									                      ]
							               });
 }
 
 
function ShowMediaUploadWnd(media_folder, sender){
        isc.Window.create({
        					 ID: "wndMediaUpload",
						    title: "Upload Media file" ,
		                autoDraw: true, autoSize: true,  autoCenter: true,canDragReposition: true, canDragResize: true, isModal: true, redrawOnResize:false, showModalMask: true,		    
						    items: [ getMediaUploader( media_folder, sender ) ]
         });
     //MediaWND.show();    
 }	
 				
 // This Function called  in response from pbxUploadCallBackFrame 
function uploadComplete(fileName, directory, sender_obj){
			   console.log(sender_obj + ' File Uploading completed: '+fileName );
			   if ( sender_obj == 'moh' ){
			     MediaUplBTNmoh.setHint('');			     
			     MediaUplDstmoh.clearValue() ;
			     tblMOHFiles.Refresh();
			     tblMOHFiles.fetchData({'directory' : directory});
			     
            }
            if ( sender_obj == 'SndDefault' ){
            	MediaUplBTNSndDefault.setHint('');
            	MediaUplDstSndDefault.clearValue() ;            	
            	tblSndDefault.Refresh();
            }    	
            if ( sender_obj == 'SndTenants' ){
            	MediaUplBTNSndTenants.setHint('');
            	MediaUplDstSndTenants.clearValue() ;            	
            	tblSndTenants.Refresh();
            }    	
            if ( sender_obj == 'WndIvrMedia' ){
            	frmIVRMenu.setValue( 'announcement', fileName);
               //frmIVRMenu.setValue( 'recordings_lang', 'tenant');
               frmIVRMenu.setValue( 'announcement_type', 'recording');
            	wndMediaUpload.clear();
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
   case 'MOH' :
   case 'MOHDefault' :
     theWnd.resizeTo(540, 450); // Trunks     
     break;
   case 'Trunks' :
     theWnd.resizeTo(560, 440); // Trunks     
     break;
   
   case 'Conferences' :        
   case 'Extensions' :
      theWnd.resizeTo(578, 410); //
      break;
   case 'Tenants' :
      theWnd.resizeTo(400,410); // 
      
   case 'Queues' :
      theWnd.resizeTo(790,570); //
   
      break;
   case 'IVRMenu' :
      theWnd.resizeTo(800,550); //
      break;   
      
   case 'Ringgroups' :
      theWnd.resizeTo(650,534); //
      break;
            
   case 'Inbound' :   
   case 'TimeFilters' :
   case 'Route' :       
   case 'Features' :
   case 'Featuresdef' :
   case 'DIDs' :   
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
	
	
	
  function getDynamicField( action ){
	                                	 fields = new Array();
	                                	 switch( action ){
	                                	 	    case "repeat":
                                       	 	  fields.push({name:'default_action_data',type:'string', editorType:"TextItem",title:'How many times',optionDataSource:''});
						 									  break;
	                                	 	    case "number":
	                                	 	    case "play_tts":
	                                	 	    case "exec_cmd":   	
	                                	 	        fields.push({name:'default_action_data',type:'string', editorType:"TextItem",title:'Value',optionDataSource:''});
						 									  break;
						 									  
						 								 case "play_rec":
											        	 case "extension":
											        	 case "moh":
							                      case "ringgroup":
							                      case "ivrmenu":
							                      case "featurecode":
							                      case "queue":
							                      case "conference":
							                      case "checvm":
							                      case "followme":
							                      case "dirbyname":														                      
														 case "voicemail":	 
																 fields.push({name:'default_action_data',
																			      editorType:"SelectItem",title:'Name',
																	            optionDataSource: getTenantDS('items'),
																	            displayField:"name", valueField:"id",
				 																   pickListCriteria : { item_type: action }
						 										  });  	
						 									   break;
						 							    default:
						 							        fields.push({name:'default_action_data',hidden:true});
						 	                 }									
	                                   return fields;  
 }	