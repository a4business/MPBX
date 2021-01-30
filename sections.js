
  var LGDefault = {
	       backgroundColor: "white",         
           //padding:2,
		    height:'*',
		    border:0,
		    autoDraw: false,		    
		    alternateRecordStyles:true, 
		    showAllRecords:true,
		    autoFetchData: true,
          showRecordComponents: true,
          showRecordComponentsByCell: true,
          alternateRecordStyles:true,
          getValueIcon:function(field,value,record) {
          	 if(field.name == 'reg_status') return "../images/" + record.reg_status + ".png";
          	 if(field.name == 'editIcon') return "[SKIN]/../actions/edit.png";
          	 if(field.name == 'sendIcon') return "../images/send_email.png";
             if(field.name == 'name' && this.ID == 'tblExtensions' ) return "../images/Extensions.png";
             if(field.name == 'description' && value != null && value != '' ) return "[SKIN]/../DynamicForm/text_control.gif";
          },
           getCellStyle: function(record,rowNum,colNum) {
                  if( this.getFieldName(colNum)  == "editIcon" || this.getFieldName(colNum)  == "sendIcon") 
                    return "cell_pointer "+this.Super("getCellStyle",arguments);
                  else
                    return this.Super("getCellStyle",arguments) ;
            },	
          cellClick: function (record, rowNum, colNum) {
            if( this.getFieldName(colNum)  == "editIcon")  
              this.recordDoubleClick(null,record,rowNum,null,colNum);
            
          },		    
		    Refresh: function () {
		      this.getDataSource().invalidateCache();
		      this.setData(isc.ResultSet.create({ dataSource : this.getDataSource() }) );		    	
		    }
   }		    
 
   var HLDefault = {
 	     autoDraw:false,
        height:20, width:"100%",        
        membersMargin: 10,
		  backgroundColor: "white",      
        padding:10, 
        align: "right"
   }


// Tenant Conference Rooms//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblConferencesHeader',
        members: [
            isc.Label.create({ icon:  "Conferences.png", contents: "<span style='color:#668B8B;font-size:18px;font-weight:bold;'><i>Conferences</i> </span>", autoDraw:false,align: "left", iconSize:32,width:'100%'}),
            isc.IButton.create({ title: "Add Conference",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Conferences');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblConferences.removeSelectedData();TriggerEvent('reload_conferences');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblConferences",
		    dataSource: DSConferences,
		    fields: [{name:'editIcon'},{name:'conference'},{name:'description'},{name:'users'} ],
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Conferences');
               frmConferences.editSelectedData(tblConferences);
		    }
		})


  // Tenant Conference Rooms//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblCampaignsHeader',
        members: [
            get_header_label('Campaigns'),
            isc.IButton.create({ title: "Add Campaign",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Campaigns');"   }),                        
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblCampaigns.removeSelectedData();TriggerEvent('reload_campaigns');",icon:"[SKIN]actions/remove.png"}),
            isc.IButton.create({ title: "Refresh",autoDraw:false,icon:"[SKIN]actions/refresh.png", click: "TriggerEvent('reload_campaigns');"  })
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblCampaigns",
		    dataSource: DSCampaigns,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Campaigns');
               frmCampaigns.editSelectedData(tblCampaigns);
               frmCampaigns.setValue('lead_field_names', JSON.parse(frmCampaigns.getValues()['lead_field_names']) );
		    },
		    getCellCSSText: function (record, rowNum, colNum) {
			    if(this.getFieldName(colNum) == "campaign_status"){
			    	if( record['campaign_status'] == "STOP" ) 
			        return "font-weight:bold; color:black;text-align:center;align:center";
               if( record['campaign_status'] == "RUN" ) 
			        return "font-weight:bold; color:green;text-align:center;align:center";
               if( record['campaign_status'] == "PAUSE" ) 
			        return "font-weight:bold; color:orange; text-align:center;align:center";
			    }    			            
			 }
		})




// INBOUND ROUTING Header//
    
    isc.HLayout.create(HLDefault, {
        ID: 'tblInboundHeader',            
        members: [
            get_header_label("Inbound"),
            isc.IButton.create({ title: "Create Inbound Route",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('Inbound');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblInbound.removeSelectedData();TriggerEvent('reload_inbound');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
 
// Inbound ROUTING // 
 		isc.ListGrid.create(LGDefault,{
		    ID: "tblInbound",
            drawAheadRatio: 4,
		    dataSource: DSInbound,
		    canEdit: false,
          //editEvent: "click",
          //listEndEditAction: "next",
		    expansionFieldImageShowSelected:true,        
		    expansionMode: "editor",
		    canExpandRecords: true,
		    autoFetchDisplayMap: true,
		    getEditorProperties:function(editField, editedRecord, rowNum){
						          	  if ( editField.name == 'did_id' )
                                   return { editorType:"SelectItem", 
                                            canEdit:true, title: "Inbound DID",
														  optionDataSource: getTenantDS('items'),  
														  optionCriteria: { 'item_type' : "did" },
				 										  displayField:"name", valueField:"id"
													   }
			  },	    
          getExpansionComponent : function (record) {
          	   //ShowTrunksDS.invalidateCache();
          	   var rulesGrid = isc.ListGrid.create({
			          	   	 height:244,
					             autoDraw: false, 
			          	   	 dataSource: DSInboundRules,
			          	   	 canEdit: true,
			 				       editByCell:true,
			 				       autoSaveEdits: true,
					             modalEditing: true,
					             editEvent: "click",
					            // listEndEditAction: "next",
					             canFocusInEmptyGrid:true,
					             canRemoveRecords: true,
					            // useAllDataSourceFields: true,
				 					 showRecordComponents: true,
				 					 
								    showRecordComponentsByCell: true,						  
					             getEditorProperties:function(editField, editedRecord, rowNum){
						              	  if ( editField.name == 'action' )
						              	       return { redrawOnChange:true,
						                               change:function(values,item,value){
				                                               item.grid.setEditValue(this.rowNum, 'destination', null);
				                                            }
				                                   };     
						              	   if ( editField.name == 'destination' && editedRecord != null )
						              	   	switch(editedRecord.action){
						              	   	 case "repeat":
													 case "play_invalid":
													 case "hangup":
													 case "unassigned":  return { canEdit:false,editorType:"StaticText"};
													 
													 case "play_tts":
													 case "disa":  
						              	   	 case "number":   	return { canEdit:true,editorType:"TextItem",title:"Value"};	
						              	   	 
						              	   	 case "extension":
						                      case "ringgroup":
						                      case "pagegroup":
						                      case "ivrmenu":
						                      case "play_rec":
						                      case "park_announce_rec":
						                      case "featurecode":						                     
						                      case "conference":
						                      case "checkvm":
						                      case "followme":
						                      case "dirbyname":														                      
                                              case "moh":
											  case "voicemail": 
											  case "queue":  return { editorType:"SelectItem", canEdit:true,
																			            optionDataSource: getTenantDS('items'),  
				 																			displayField:"name", valueField:"id",
																			            getPickListFilterCriteria : function () {
																			                var current_item_type = this.grid.getEditedCell(this.rowNum, "action");
																			                //console.log('crit:' + current_item_type );
																			                return { item_type:current_item_type };
																			           }
																			         }
						                    }
						             },   
						             fields: [
				                      {name:"inbound_id",  defaultValue: record.id, hidden:true },				                      
				                      {name:"week_day_from", width:55 },{name:"week_day_to", width:100 },
				                      {name:"day_time_from", width:120 },{name:"day_time_to",  width:120 },
				                      {name:"action", redrawOnChange:true },
				                      {name:"destination", type:'select',   editorType: "SelectItem",
				                         optionDataSource: getTenantDS('items'),  
				 								 displayField:"name", valueField:"id", canHover:true,showHover:true,
				 								 hoverHTML : function (record, value, rowNum, colNum, grid) {
											             return this.hoverText[record.action];
											         },
											    hoverText: {
											             "park_announce_rec": "<b>Announce to play for Park&Announce(up to 30s duration)</b><p style='width:300'> The recording, played to the  extensions configured extensions (paging exten) uppon incoming call parked, Note: auto-appended with number of parked position at the end of recording.</p> ",							                          
											             "disa":       "<b>Direct Inward System Access</b><p <p style='width:300'> Provide internal second dial tone for the caller  </p>",
											             "play_tts":   "<b>Play text Message via voice </b><p <p style='width:300'> Generate voice message from text using Text-2-Speech Engine </p>"
											    }
				                      }				                      
						             ]
				          	  });
          	  rulesGrid.fetchRelatedData(record, 	DSInbound );  
				  //routesGrid.fetchData({route_id: record.id});
          	  var RulesHLayout = isc.HLayout.create({
          	  	      align: "center",padding:4,
          	  	      membersMargin: 6,
          	  	      members: [
          	  	           isc.IButton.create({ title:"Save",  click: function(){ rulesGrid.endEditing();} }),
								  isc.IButton.create({ title:"New",   click: function(){ rulesGrid.startEditingNew();}, icon:"[SKIN]actions/add.png" }),
								  isc.IButton.create({ title:"Delete",click: function(){
							                if (rulesGrid.getSelection().getLength() > 0)     
							                 rulesGrid.getSelection().map(function (item) { rulesGrid.removeData(item) });
								           } 
								       }),
          	  	           isc.IButton.create({ title:"Discard",click: function(){ rulesGrid.discardAllEdits(); } }),
          	  	      ]          	  	           
               });			    
		         return isc.VLayout.create({ padding:5, members: [rulesGrid, RulesHLayout ] });
		      },   
     
		   recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Inbound');
               frmInbound.editSelectedData(tblInbound);
		   	 }
		});		
		
// Tenant PageGroups//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblPagegroupsHeader',
        members: [
            get_header_label("Pagegroups"),
            isc.IButton.create({ title: "Add Page Group",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Pagegroups');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblPagegroups.removeSelectedData();TriggerEvent('reload_Pagegroups');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblPagegroups",
		    dataSource: DSPagegroups,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Pagegroups');
				   tblPGMembersAssigned.fetchRelatedData(record, 	DSPagegroups );   //tblQMembersAssigned.fetchData(  {'queue_name': record['name']} );
               tblPGMembersAvailable.fetchRelatedData(record, 	DSPagegroups );   //tblQMembersAvailable.fetchData( {'queue_name': record['name']} );
               frmPagegroups.editSelectedData(tblPagegroups);
		    }
		})		
		
isc.Menu.create({
    ID: "statsMenu",
    autoDraw: false,
    showShadow: true,
    shadowDepth: 10,
    itemClick: function(item){
       window.open('jaxer.php?get_stats=1&d='+item.title , '_blank', 'toolbar=no,top=200,left=300,width=600,height=800,title=Lost calls report');
    },
    data: [
        {title: "Today", icon: "missed_calls.png"},        
        {title: new Date( new Date().getTime() - 86400000 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*2 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*3 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*4 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*5 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*6 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*7 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*8 ).toISOString().split('T')[0]  }
    ]
});

isc.Menu.create({
    ID: "activityMenu",
    autoDraw: false,
    showShadow: true,
    shadowDepth: 10,
    itemClick: function(item){    	
       window.open('jaxer.php?extensions=all&output_format=pdf&rtype=queue_activity&drange=' + item.title + 'to' + item.title , '_blank', 
       	           'toolbar=no,top=200,left=300,width=600,height=800,title=Queue Agents Activity Report');
    },
    data: [
        {title: new Date( new Date().getTime() ).toISOString().split('T')[0], icon: "missed_calls.png"},        
        {title: new Date( new Date().getTime() - 86400000 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*2 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*3 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*4 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*5 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*6 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*7 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*8 ).toISOString().split('T')[0]  }
    ]
});

// Tenant Queues//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblQueuesHeader',
        members: [
            get_header_label("Queues"),
            isc.MenuButton.create({ title: "Missed Calls ", menu:statsMenu, width: 110,autoDraw:false, icon:"missed_calls.png"}), 
            isc.MenuButton.create({ title: "Agents activity", menu:activityMenu, width: 110,autoDraw:false, icon:"missed_calls.png"}), 
            isc.IButton.create({ title: "Add Queue",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Queues');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblQueues.removeSelectedData();TriggerEvent('reload_queues');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblQueues",
		    dataSource: DSQueues,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Queues');
				    tblQMembersAssigned.fetchRelatedData(record, 	DSQueues );   //tblQMembersAssigned.fetchData(  {'queue_name': record['name']} );
                    tblQMembersAvailable.fetchRelatedData(record, 	DSQueues );   //tblQMembersAvailable.fetchData( {'queue_name': record['name']} );
                    frmQueues.editSelectedData(tblQueues);
		    },
		    fields:[
		      {name:"editIcon"},
		      {name:"name"},
		      {name:"qlabel"},
		      {name:"strategy"},
		      {name:"timeout"},
		      {name:"default_action"},
		      {name:"default_action_data"},
		      {name:"context_script"},
		      {name:"stats_email"}
		    ]
		})



// DEFAULT Tenant Queues//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblFeaturesdefHeader',
        members: [
            get_header_label("Featuresdef"),
            isc.IButton.create({ title: "Add Feature Code",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Featuresdef');"   }),
            isc.IButton.create({ title: "Re-populate Features to Tenants", width:210,autoDraw:false,icon:"[SKIN]actions/dynamic.png", click: "run('repopulate_features');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblFeaturesdef.removeSelectedData();TriggerEvent('reload_featuresdef');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblFeaturesdef",
		    dataSource: DSFeaturesdef,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Featuresdef');
               frmFeaturesdef.editSelectedData(tblFeaturesdef);
		    }
		})


 // Tenant Queues//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblFeaturesHeader',
        members: [
            get_header_label("Features"),
            isc.IButton.create({ title: "Add Feature Code",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Features');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblFeatures.removeSelectedData();TriggerEvent('reload_features');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblFeatures",
		    dataSource: DSFeatures,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Features');
               frmFeatures.editSelectedData(tblFeatures);
		    }
		})


// Tenant Ringgroups//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblRinggroupsHeader',
        members: [
            get_header_label("Ringgroups"),
            isc.IButton.create({ title: "Missed calls",width: 200,autoDraw:false, click: "window.open('jaxer.php?get_stats=1', '_blank', 'toolbar=no,top=200,left=300,width=600,height=800');",icon:"missed_calls.png"}),
            isc.IButton.create({ title: "Add Ring Group",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Ringgroups');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblRinggroups.removeSelectedData();TriggerEvent('reload_ringgroups');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblRinggroups",
		    dataSource: DSRinggroups,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Ringgroups');
               frmRinggroups.editSelectedData(tblRinggroups);
		    }
		});
		
		


isc.Menu.create({
    ID: "ShiftsMenu",
    autoDraw: false,
    showShadow: true,
    shadowDepth: 10,
    itemClick: function(item){
       var  URL = 'jaxer.php?get_stats=1&shift_id='+ tblShifts.getSelectedRecord()['id'] + '&d='+item.title+'&mode=show' ;
       window.open( URL, '_blank', 'toolbar=no,top=200,left=300,width=600,height=800,title=Shift Missed calls report' );
    },
    data: [
        {title: "Today", icon: "missed_calls.png"},        
        {title: new Date( new Date().getTime() - 86400000 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*2 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*3 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*4 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*5 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*6 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*7 ).toISOString().split('T')[0]  },
        {title: new Date( new Date().getTime() - 86400000*8 ).toISOString().split('T')[0]  }
    ]
});


// Tenant Shifts//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblShiftsHeader',
        members: [
            get_header_label("Shifts"),
            isc.Label.create({ID:'send_indication',animateTime:1000, visibility:"hidden", align:"left",valign:"left",width:70, contents:"<div style='padding:2px 10px;color:#641E16;font-weight:bold;font-size:20px;white-space:nowrap;background-color:#FADBD8;'>Sending ...</div>"}),
            isc.Label.create({ID:'shift_reports', align:"left",valign:"left",width:'100%', contents:"Missed call Report in queues and ringgroups, grouped by shifts time intervals, IS sent to email daily!"}),
            isc.MenuButton.create({ title: "Get selected Shift Report(PDF)", menu:ShiftsMenu, width: 250,autoDraw:false, icon:"missed_calls.png"}), 
            isc.IButton.create({ title: "Add Shift",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Shifts');"   }),
            isc.IButton.create({ title: "Remove", autoDraw:false, click: "tblShifts.removeSelectedData();TriggerEvent('reload_shifts');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblShifts",
		    dataSource: DSShifts,
		    autoDraw: false,
		    timeFormatter: 'TOSHORT24HOURTIME',
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				 edit_object('Shifts');
                 frmShifts.editSelectedData(tblShifts);
		    },		    		   	    
		    cellClick: function (record, rowNum, colNum) {   
		      if( this.getFieldName(colNum)  == "editIcon")  
                  this.recordDoubleClick(null,record,rowNum,null,colNum);         

	          if( this.getFieldName(colNum)  == "sendIcon"){
     	          var  URL = 'jaxer.php?get_stats=1&shift_id='+ tblShifts.getSelectedRecord()['id'] + '&d=Today';
     	          console.log('Exec call stats report:' + URL);
     	          send_indication.animateShow('slide');
     	          $.post('jaxer.php',{
     	          	                   'get_stats': 1, 
     	          	                   'to_email': tblShifts.getSelectedRecord()['send_to_email'] , 								     	          	                   
     	          	                   'shift_id': tblShifts.getSelectedRecord()['id'],
     	          	                   'd': "Today"
     	          	                  },
     	                function (data) {
                             isc.say(data.error);
                             send_indication.animateHide('slide');
                        },
                  'json');
	            }
            
            },			    
			canEdit:false, 
	        fields:[
	                {name:'editIcon'},	                
	        		{name:'id',width:21},
	        		{name:'shift_start',width:80},
	                {name:'shift_end',  width:80},	                
	                {name:'sendIcon', title:'Send Now'},
				    {name:'send_to_email'},
	                {name:'tenant_id',hidden:true}
	        ]
		})


// OUTBOUND ROUTING Header//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblRouteHeader',            
        members: [
            get_header_label("Route"),
            isc.IButton.create({ title: "Create Route",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('Route');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblRoute.removeSelectedData();TriggerEvent('reload_route');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
	   
 // OUTBOUND ROUTING Table
 		isc.ListGrid.create(LGDefault,{
		    ID: "tblRoute",
          drawAheadRatio: 4,
		    dataSource: DSRoute,
		    canEdit: false,
          listEndEditAction: "next",
		    expansionFieldImageShowSelected:true,        
		    expansionMode: "editor",
		    canExpandRecords: true,		    
          getExpansionComponent : function (record) {
          	   ShowTrunksDS.invalidateCache();
          	   var routesGrid = isc.ListGrid.create({
          	   	 height:244,
          	   	 dataSource: DSRoutesList,
          	   	 canEdit: true,
		             modalEditing: true,
		             editEvent: "click",
		             listEndEditAction: "next",
		             //autoSaveEdits: true,
		             canFocusInEmptyGrid:true,
		             useAllDataSourceFields: true,
		             fields: [
                      {name:"route_id", defaultValue: record.id, hidden:true }
		             ]
          	  });
          	  routesGrid.fetchRelatedData(record, 	DSRoute );  
				  //routesGrid.fetchData({route_id: record.id});
          	  var hLayout = isc.HLayout.create({
          	  	      align: "center",padding:4,
          	  	      membersMargin: 6,
          	  	      members: [
          	  	           isc.IButton.create({ title:"Save",click: function(){ routesGrid.endEditing();} }),
								  isc.IButton.create({ title:"New",click: function(){ routesGrid.startEditingNew();} }),
								  isc.IButton.create({ title:"Delete",click: function(){
							             if (routesGrid.getSelection().getLength() > 0)     
							                routesGrid.getSelection().map(function (item) { routesGrid.removeData(item) });
								           } 
								       }),
          	  	           isc.IButton.create({ title:"Discard",click: function(){ routesGrid.discardAllEdits(); } }),
          	  	           isc.IButton.create({ title:"Close",click: function(){ routesGrid.collapseRecord(record); } })
          	  	      ]          	  	           
               });			    
		         var layout = isc.VLayout.create({ padding:5, members: [routesGrid, hLayout] });
		         return layout;
		      },   
     
		   recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Route');
               frmRoute.editSelectedData(tblRoute);
		   	 }
		})
		

		
// Auto-Attendants //    
    isc.HLayout.create(HLDefault, {
        ID: 'tblIVRMenuHeader',
        members: [
            get_header_label("IVRMenu"),
            isc.IButton.create({ title: "Add Auto-attendand",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('IVRMenu');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblIVRMenu.removeSelectedData();TriggerEvent('show_ivrmenu');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblIVRMenu",
		    dataSource: DSIVRMenu,
		    useAllDataSourceFields: true,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
		    	   //IVRMenuItemsGrid.fetchRelatedData(record, DSIVRMenu);
				   edit_object('IVRMenu');               
		         frmIVRMenu.editSelectedData(tblIVRMenu);
		    }
		})



		
// Tenant MOHs //    
    isc.HLayout.create(HLDefault, {
        ID: 'tblMOHHeader',
        members: [
            get_header_label("MOH"),
            isc.IButton.create({ title: "Add MOH Profile",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('MOH'); MOHFormSections.hideSection(1);wndMOH.resizeTo(520, 200)"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblMOH.removeSelectedData();TriggerEvent('reload_moh');",icon:"[SKIN]actions/remove.png"})
            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblMOH",
		    dataSource: DSMOH,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('MOH');
               frmMOH.editSelectedData(tblMOH);
               tblMOHFiles.fetchData({'directory': record.directory });
               // Hide file selection when Media stream s selected //
			      if ( frmMOH.getValues()['mode'] == 'custom' ){
			        MOHFormSections.hideSection(1);
			      }
		    }
		})
		
		
// Default MOH  - we create same Grid with Same Datasouce - but fetch DEFAULT MOH//
  function getMohDefault(){
 	  theGrid = isc.ListGrid.create(LGDefault, {
	         	    ID: "tblMohdefault",
		             dataSource: DSMOH,
		             recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				                edit_object('MOHDefault');
                            frmMOHDefault.editSelectedData(tblMohdefault);
                            tblMOHFiles.fetchData({'directory': record.directory});
                            // Hide file selection when Media stream s selected //
            			      if ( frmMOHDefault.getValues()['mode'] == 'custom' ){
			                     MOHFormSections.hideSection(1);
			                  }
		             }
		      });
		      
   tblMohdefault.fetchData({'id': 0}); 
	return theGrid;	
  }		
		

//// SOUNDS CLASS
  isc.defineClass("SoundListGrid","ListGrid");
  isc.SoundListGrid.addProperties(LGDefault, {
 	       //dataSource: ShowSNDFilesDS,
          autoFetchData: false,
          autoDraw:false,
          useClientFiltering:false,
          showFilterEditor: true,
          filterOnKeypress: true,
          SoundsTypeID: '',   // Reference passed to certain Player control while creating SoundList Grid
          fetchDelay: 500,
          fields:[ 
                   {name:"file_name"},
                   {name:"size"},
                   {name:"format"},
                   {name:"duration"},
                   {name:'playIcon', title:'Play', width:30, canEdit:false, readOnlyDisplay:'static'} 
                 ],
          showRecordComponents: true,
          showRecordComponentsByCell: true,
          alternateRecordStyles:true,
	       createRecordComponent : function (record, colNum){
	       	        var PlayerID = this.SoundsTypeID ;
	       	        var gr = this;
				        if ( this.getFieldName(colNum)  == "playIcon" )  
	                     return   isc.ImgButton.create({
	                     	             src: "play_picker.png",
								                showDown: false, showRollOver: false,autoDraw:false,
								                layoutAlign: "center",prompt: "Play Item Media ", height: 16, width: 16, grid: this,
 													 click : function (){
 													 	      //gr.recordDoubleClick();		
  													          //isc.Sound.play( 'media/play.php?mode=recording&play_text=' + record['file_name'] )														
 														       window[PlayerID+'Player'].src = 'media/play.php?mode=recording&play_text=' + record['file_name'] + '&play_dir=' + PlayerID ;
                                                 			   window[PlayerID+'Player'].play();
                                                }   
		                            })
  	       }
    });
		            
      
         
 // DEFAULT SOUNDS  Media files Header         
	isc.HLayout.create(HLDefault,{ ID: 'tblSndDefaultHeader',
	   members: [
            getMediaUploader('default','SndDefault'),          
            getSearchForm('SndDefault'),            
            isc.HTMLFlow.create({ autoDraw:false, contents:"<audio style='box-shadow: 5px 4px 3px silver;' id='SndDefaultPlayer'  controls> </audio>",width:"40%"}),
            isc.IButton.create({ autoDraw:false, title: "Remove",autoDraw:false, click: "tblSndDefault.removeSelectedData();",icon:"[SKIN]actions/remove.png"})
       ] 
    });
 	isc.SoundListGrid.create({ ID: "tblSndDefault",
 										dataSource: ShowSNDFilesDefaultDS,
 										SoundsTypeID: 'SndDefault'  
 								    });

	
	// Tenant Sounds  Media files Header
	isc.HLayout.create(HLDefault,{ ID: 'tblSndTenantsHeader',
	   members: [
            getMediaUploader('tenant','SndTenants','fileManager.php'),
            getSearchForm('SndTenants'),				                                                    
            isc.HTMLFlow.create({ autoDraw:false, contents:"<audio style='box-shadow: 5px 4px 3px silver;' id='SndTenantsPlayer'  controls> </audio>",width:"40%"}),
            isc.IButton.create({ autoDraw:false, title: "Refresh",autoDraw:false, click: "tblSndTenants.Refresh()",icon:"[SKIN]actions/refresh.png"}),
            isc.IButton.create({ autoDraw:false, title: "Remove",autoDraw:false, click: "tblSndTenants.removeSelectedData();tblSndTenants.Refresh()",icon:"[SKIN]actions/remove.png"}),
       ] });
 	 isc.SoundListGrid.create({ ID: "tblSndTenants", autoFetchData: true,
 	 								    dataSource: ShowSNDFilesTenantsDS, 
 	 								    SoundsTypeID: 'SndTenants'  
 	 						  });



// DIDs //    
    isc.HLayout.create(HLDefault, {
        ID: 'tblDIDsHeader',               
        members: [
            get_header_label("DIDs"),
            isc.IButton.create({ title: "Upload CVS",autoDraw:false,icon:"[SKIN]actions/add.png", click: "ShowCSVUploadWnd('DIDs');"   }),
            isc.IButton.create({ title: "Refresh",autoDraw:false,icon:"[SKIN]actions/refresh.png", click: "TriggerEvent('show_dids');;"   }),
            isc.IButton.create({ title: "Add DID",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('DIDs');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblDIDs.removeSelectedData();TriggerEvent('reload_dids');",icon:"[SKIN]actions/remove.png"})
            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblDIDs",
		    dataSource: DSDIDs,	
		    autoFetchData: true,		     
          useClientFiltering:true,
          showFilterEditor: true,
          fetchDelay: 500,
          filterOnKeypress: true,	    		   
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
               //frmTrunks.fetchData( {id:record.id} ); // Locate certain trunk to edit 
				   edit_object('DIDs');
               frmDIDs.editSelectedData(tblDIDs);
		   	 }
		})
		

getCSV = (filename, arrayOfJson) => {
  // convert JSON to CSV
  const replacer = (key, value) => value === null ? '' : value // specify how you want to handle null values here
  const header = Object.keys(arrayOfJson[0])
  let csv = arrayOfJson.map(   row => header.map(fieldName => 
                                                JSON.stringify(row[fieldName], replacer)).join(','))
  csv.unshift(header.join(',') )
  csv = csv.join('\r')   // or \r\n , but windows ece

  // Create link and download
  var link = document.createElement('a');
  link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv));
  link.setAttribute('download', filename);
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};		

// CDRs //    
    isc.HLayout.create(HLDefault, {
        ID: 'tblCDRsHeader',           
        animateMembers:true,    
        members: [
            isc.HTMLFlow.create({ autoDraw:false, contents:" <h1 style='display:inline;vertical-align: top;'>Call Detailed Records</h1><audio style='margin-left:100px;display:inline !important;box-shadow: 5px 4px 3px silver;visibility:hidden;' id='CDRsPlayer'  controls> </audio>",width:"40%"}),
            isc.Label.create({ ID:"tblCDRsInfoLabel", contents: "loading data...",autoDraw:false,width:100,height:30}),
            isc.DynamicForm.create({ID: "frm_cdrs_show",fields: [ {name:"max_rows",width:130,type:'select',wrapItemTitles:false, title:'Max',hint:'rows', editorType: "ComboBoxItem",defaultValue:'500',valueMap:[300,500,1000,5000,10000,15000,'all']} ]}),            
            isc.IButton.create({ title: "Refresh",autoDraw:false, click: "TriggerEvent('show_cdrs');",icon:"[SKIN]actions/refresh.png"}),
            //isc.IButton.create({ title: "Download in CVS",width:130,autoDraw:false,icon:"[SKIN]actions/download.png", click: "window.open('ds.php?get=t_cdrs_view&format=csv', '_blank', 'width=1,height=1');"   }),            
            isc.IButton.create({ title: "Download in CVS", width:130, autoDraw:false, icon:"[SKIN]actions/download.png", click: " tblCDRs.invalidateCache();tblCDRs.fetchData( tblCDRs.getFilterEditorCriteria(), function(resp,data,req){ getCSV('cdrs.csv', data); });" }),            
            isc.IButton.create({ title: "Apply Filter",width:110,autoDraw:false,icon:"[SKIN]actions/download.png", click: "tblCDRs.invalidateCache();tblCDRs.fetchData( tblCDRs.getFilterEditorCriteria()  );"   })            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblCDRs", 
		    dataSource: DSCDRs,		    		   
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
               //frmTrunks.fetchData( {id:record.id} ); // Locate certain trunk to edit 
			   edit_object('CDRs');
               frmCDRs.editSelectedData(tblCDRs);
               if( record['has_recording'] == 1 ){
                window['CDRplayer'].style.visibility = 'visible';
                window['CDRplayer'].src = 'media/play.php?mode=cdrs&uniqueid=' + record['uniqueid'] ;
               } 
		   	 },
         dataArrived : function(startRow,endRow){
			      tblCDRsInfoLabel.setContents("<span style='color:#668B8B;font-size:10px;'>Total rows:<b>" + tblCDRs.getTotalRows() + "</b> </span>" +
			      	                           "<span style='color:#668B8B;font-size:10px;'>Displayed:<b>" + tblCDRs.getTotalRows() + "</b> </span>");
          },
		    autoDraw:false,
            useClientFiltering:false,
            filterLocalData: false,
            showFilterEditor: true,
            filterOnKeypress: true,
            SoundsTypeID: 'CDRs',   // Reference passed to certain Player control while creating SoundList Grid
            fetchDelay: 500,

            fields:[ {name:"calldate",width:120},{name:'title', width:160},{name:'direction', width:80},
                     {name:"disposition",width:80 },{name:"billsec", width:55},
                     {name:"playIcon",title:"Rec",width:40,align:'center'},
          		     {name:"clid",width:180},{name:"did",width:90},{name:"dst",width:100},
				     {name:"peername"}
				   //{name:"uniqueid",width:90},{name:"dstchannel"}
			 ],
			  getCellCSSText: function (record, rowNum, colNum) {
			    if(this.getFieldName(colNum) == "disposition"){
			    	if( record['disposition'] == "ANSWERED" ) 
			        return "font-weight:bold; color:green;text-align:center;align:center";
               else if( record['disposition'] == "BUSY" || record['disposition'] == "FAILED") 
			        return "font-weight:bold; color:RED;text-align:center;align:center";
               else if( record['disposition'] == "NO ANSWER" ) 
			        return "font-weight:bold; color:orange; text-align:center;align:center";
			    }    			            
			 },
			 showRecordComponents: true,
            showRecordComponentsByCell: true,
            alternateRecordStyles:true,
	       createRecordComponent : function (record, colNum){
	       	        var PlayerID = this.SoundsTypeID ;
	       	        var gr = this;
				        if ( this.getFieldName(colNum)  == "playIcon" && record['has_recording'] == 1 )  
	                     return   isc.ImgButton.create({
	                     	             src: "play_picker.png",
								                showDown: false, showRollOver: false,autoDraw:false,
								                layoutAlign: "center",prompt: "Play Item Media ", height: 16, width: 16, grid: this,
 													 click : function (){
 													 	       //gr.recordDoubleClick();		
  													          //isc.Sound.play( 'media/play.php?mode=recording&play_text=' + record['file_name'] )														
 														       window[PlayerID+'Player'].src = 'media/play.php?mode=cdrs&uniqueid=' + record['uniqueid']  ;
 														       window[PlayerID+'Player'].style.visibility = 'visible';
                                                 window[PlayerID+'Player'].play();
                                                }   
		                            })
  	       }  
          	 
		});
		
		
// Recordings //    
    isc.HLayout.create(HLDefault, {
        ID: 'tblRecordingsHeader',               
        members: [
            get_header_label("Recordings"),
            isc.HTMLFlow.create({ autoDraw:false, contents:"<div style='min-height:30px'> <h1 style='display:inline;vertical-align: top;'>Call Recordings</h1> <audio style='margin-left:100px;display:inline !important;box-shadow: 5px 4px 3px silver;' id='RecordingsPlayer'  controls> </audio> </div>",width:"40%"}),
            isc.IButton.create({ title: "Refresh",autoDraw:false, click: "TriggerEvent('show_recordings');",icon:"[SKIN]actions/refresh.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblRecordings",
		    dataSource: DSCDRs,		
		    recordClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {		    	       
                window['RecordingsPlayer'].src = ( record.has_recording == 1 ) ?  'media/play.php?mode=cdrs&uniqueid=' + record['uniqueid'] : '';
             window['RecordingsPlayer'].style.display = ( record.has_recording == 1 ) ?  "inline" : 'none';
		    },			        		   
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {              
			    edit_object('Recordings');
                frmRecordings.editSelectedData(tblRecordings);
                window['RecordingPlayer'].src = ( record.has_recording == 1 ) ? 'media/play.php?mode=cdrs&uniqueid=' + record['uniqueid'] : '';
                window['RecordingPlayer'].style.display = ( record.has_recording == 1 ) ? "inline" : "none";
		    },
		    autoDraw:false,
          useClientFiltering:false,
          showFilterEditor: true,
          filterOnKeypress: true,
          SoundsTypeID: 'Recordings',   // Reference passed to certain Player control while creating SoundList Grid
          fetchDelay: 500,
	      autoFetchData:true,
	      allowFilterOperators: true,
          initialCriteria:  { "has_recording":"1" },
          fields:[ 
		   {name:"calldate", width:120},
		   {name:'lastapp',width:110},
           {name:"src", width:180},
           {name:"dst", width:180},		
           {name:"disposition", width:100},		       
           {name:'uniqueid', title:'ID', width:130},
           {name:"talk_time_str", width:80},
		   {name:"service_status",width:110},
		   {name:"playIcon", title:"&nbsp;&nbsp;&nbsp;",width:30},
		   {name:"recording"}
		  ],
		  getCellCSSText: function (record, rowNum, colNum) {
			    if(this.getFieldName(colNum) == "disposition"){
			    	if( record['disposition'] == "ANSWERED" ) 
			        return "font-weight:bold; color:green;text-align:center;align:center";
               else if( record['disposition'] == "BUSY" || record['disposition'] == "FAILED") 
			        return "font-weight:bold; color:RED;text-align:center;align:left";
               else if( record['disposition'] == "NO ANSWER" ) 
			        return "font-weight:bold; color:orange; text-align:center;align:center";
			    }    			            
			 },
		  showRecordComponents: true,
          showRecordComponentsByCell: true,
          alternateRecordStyles:true,
	      createRecordComponent : function (record, colNum){
	       	        var PlayerID = this.SoundsTypeID ;
	       	        var gr = this;
			        if ( this.getFieldName(colNum)  == "playIcon" && record['has_recording'] == 1 )  
                     return   isc.ImgButton.create({
                     	            src: "../images/play.png",
					                showDown: false, showRollOver: false,autoDraw:false,
					                align: "right",
					                layoutAlign: "right",
					                prompt: "Play Item Media ", 
					                height: 16,
					                width: 16, 
					                grid: this,
								    click : function (){
									  //gr.recordDoubleClick();		
									  //isc.Sound.play( 'media/play.php?mode=recording&play_text=' + record['file_name'] )														
									  window[PlayerID+'Player'].src = 'media/play.php?mode=cdrs&uniqueid=' + record['uniqueid']  ;
                                      window[PlayerID+'Player'].play();
                                    }   
	                 })
  	       }  
          	 
		});



// TRUNKS //    
    isc.HLayout.create(HLDefault,{
        ID: 'tblTrunksHeader',               
        members: [            
            get_header_label("Trunks"),
            isc.IButton.create({ title: "Create Trunk",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('Trunks');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblTrunks.removeSelectedData();TriggerEvent('reload_trunks');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblTrunks",
		    dataSource: DSTrunks,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
               //frmTrunks.fetchData( {id:record.id} ); // Locate certain trunk to edit 
				   edit_object('Trunks');
               frmTrunks.editSelectedData(tblTrunks);
               frmTrunks.setValue('inTenants', JSON.parse(frmTrunks.getValues()['inTenants']) );
		    }
		})
		
		
 // admins //    
    isc.HLayout.create(HLDefault,{
        ID: 'tblAdminusersHeader',               
        members: [            
            get_header_label("Adminusers"),
            isc.IButton.create({ title: "Add",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('Adminusers');"   }),            
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblAdminusers.removeSelectedData();TriggerEvent('reload_adminusers');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblAdminusers",
		    dataSource: DSAdminusers,
		    fields: [  {name:"editIcon"}, 
		    		   {name:'user'},
		    		   {name:'email'},
		    		   {name:'user_sip_exten'},
		    		   {name:'default_tenant_id'},
		    		   {name:'role'},
		    		   {name:'last_login'},
		    		   {name:'last_login_ip',align:'left'},
		    		   {name:'status'} 
		    		],

		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
               //frmTrunks.fetchData( {id:record.id} ); // Locate certain trunk to edit 
			   edit_object('Adminusers');
			   tblAdminUserLogs.fetchRelatedData( record, DSAdminusers );

			   //UserExtensionPiklist.pickListCriteria = {  'current_sip_id': record.sip_user_id, 'user_def_tenant_id': record.default_tenant_id };

               frmAdminusers.editSelectedData(tblAdminusers);
               frmAdminusers.setValue('allowed_sections', JSON.parse(frmAdminusers.getValues()['allowed_sections']) );
               //frmAdminusers.setValue('default_tenant', JSON.parse(frmTrunks.getValues()['deault_tenant_id']) );
		    },
		    getCellCSSText: function (record, rowNum, colNum) {
			    if(this.getFieldName(colNum) == "status") 
			        return "font-weight:bold; color:green; padding:0 10px;align:center";			            
			 }
			    
		})

		

///// TENANTS
     isc.HLayout.create(HLDefault, {
        ID: 'tblTenantsHeader',               
        members: [
            get_header_label("Tenants"),
            isc.IButton.create({ title: "Refresh",autoDraw:false, click: "TriggerEvent('show_tenants');",icon:"[SKIN]actions/refresh.png"}),
            isc.IButton.create({ title: "Create Tenant",autoDraw:false,icon:"[SKIN]actions/add.png", click: function () { new_object('Tenants');  } }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblTenants.removeSelectedData();TriggerEvent('reload_tenants');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
	   		
	   		
		isc.ListGrid.create(LGDefault, {
		    ID: "tblTenants",		    
		    dataSource: DSTenants,
           autoFetchData: true,		     
          useClientFiltering:true,
          showFilterEditor: true,
          fetchDelay: 500,
          filterOnKeypress: true,
		  recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
			   edit_object('Tenants');				                  
		     //frmTenants.editSelectedData(tblTenants);  //
               VMgrTenants.editSelectedData(tblTenants);
               VMgrTenants.setValue('parkext_announce', JSON.parse(VMgrTenants.getValues()['parkext_announce']) );               
               VMgrTenants.setValue('intertenant_routing', JSON.parse( VMgrTenants.getValues()['intertenant_routing']) );
               //InterExchangeRouting.pickListCriteria = { 'is_selected': 0 };
               TenantMOHPickList.pickListCriteria = {'id': VMgrTenants.getValues()['id'] };
		   	 }
		})
   // END Tenants //		
   
 
 // Tenant Lost Calls Reports//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblLostCallsHeader',
        members: [
            get_header_label("Lostcalls"),
            isc.Label.create({contents:'Daily lost calls inQueues, RingGroups. dbl-click to get report ', width:300,align: "left"}),	    
            isc.IButton.create({ title: "Get todays' Report", width:180,autoDraw:false, click: "window.open('jaxer.php?get_stats=1', '_blank', 'toolbar=no,top=200,left=300,width=600,height=800');",icon:"/images/missed_calls.png"}),
            isc.IButton.create({ title: "Refresh",autoDraw:false, click: "TriggerEvent('show_lostcalls');",icon:"[SKIN]actions/refresh.png"})            
         ]
 
	   });
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblLostCalls",
		    emptyMessage: " No Ringgroups created. Create at least one ringGroup, and make calls to see the report",
		    dataSource: DSLostCalls,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
			  window.open('jaxer.php?get_stats=1&d='+record['cdate'], '_blank', 'toolbar=no,top=200,left=300,width=600,height=800');
		    }
		});
		  
   

   // Tenant IP BlackList //    
    isc.HLayout.create(HLDefault, {
        ID: 'tblBlacklistHeader',
        members: [
            get_header_label("Blacklist"),      
            isc.IButton.create({ title: "Add IP Address",autoDraw:false,icon:"[SKIN]actions/add.png", click: function () { new_object('Blacklist');  } }),      
		 isc.IButton.create({ title: "Delete",autoDraw:false,icon:"[SKIN]actions/delete.png", click: function(){
                                                                     if (tblBlacklist.getSelection().getLength() > 0)
                                                                        tblBlacklist.getSelection().map(function (item) { tblBlacklist.removeData(item) });
                                                                           }
 }),
            isc.IButton.create({ title: "Refresh",autoDraw:false, click: "TriggerEvent('show_lostcalls');",icon:"[SKIN]actions/refresh.png"})            
         ]
 
	   });
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblBlacklist",
		    dataSource: DSBlacklist,
		      recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
			   edit_object('Blacklist');
			   frmBlacklist.editSelectedData(tblBlacklist);  //
			   },
		    fields: [ 
		       {name:"editIcon"},{name:'tstamp',width:120},{name:'ip',width:100},
		       {name:'ip_info'},{name:'description',width:300}, {name:"hit_count"},
               {name:"last_hit"},{name:"redirect_to"},{name:'block_sip_registration'}
		       ]
		});
		  

   
   
 // Extensions    
    isc.HLayout.create(HLDefault,{
        ID: 'tblExtensionsHeader',               
        members: [
            get_header_label("Extensions"),
            isc.IButton.create({ title: "Create Extension",autoDraw:false,icon:"[SKIN]actions/add.png", click: function () {
                edit_object('Extensions');            	 
                //tblExtensions.startEditingNew(); //frmExtensions.editSelectedData(tblExtensions);
                // Do new extension provisioning //               
                $.post('jaxer.php',{'get_next_exten': 1 }, function (data) {
                     console.log('New exten provision[get_next_exten] response: ' + data.extension);
                     VMgrExtensions.setValues(data);
                     frmvm_users.setValue('mailbox', data.extension );
                     frmvm_users.setValue('password', Math.floor(Math.random() * 90000) + 10000 );
                 },'json');
               } 
            }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblExtensions.removeSelectedData();TriggerEvent('reload_extensions');",icon:"[SKIN]actions/remove.png"}),
              isc.IButton.create({ title: "Refresh",autoDraw:false, click: "tblExtensions.Refresh();TriggerEvent('show_extensions');",icon:"[SKIN]actions/refresh.png"})
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblExtensions",		    
		    dataSource: DSExtensions,
		    autoFetchData: true,		     
          useClientFiltering:true,
          showFilterEditor: true,
          fetchDelay: 500,
          
          filterOnKeypress: true,
         //getCellStyle: function (record, rowNum, colNum) {
			//    if(this.getFieldName(colNum) == "reg_status")
			//        return record.reg_status;
			//    else
			//        return this.getBaseStyle(record,rowNum,colNum);     			            
			// },
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
               //frmExtensions.fetchData( {id:record.id} ); // same as next ?
               
              
  		       
				   edit_object('Extensions');               
               VMgrExtensions.editSelectedData(tblExtensions);  //frmExtensions.editSelectedData(tblExtensions);
                              
               frmExtCallerID.setValue( (VMgrExtensions.getValues()['outbound_callerid'] != '') ? 'Specify:' : 'Use Default' );
               frmExtCallerName.setValue( (VMgrExtensions.getValues()['outbound_callername'] != '') ? 'Specify:' : 'Use Default' );
               frmIntCallerID.setValue( (VMgrExtensions.getValues()['internal_callerid'] != '') ? 'Specify:' : 'Use Default' );
               frmIntCallerName.setValue( (VMgrExtensions.getValues()['internal_callername'] != '') ? 'Specify:' : 'Use Default' );
               
                              
               // Prepare sub-tables:
               //frmUserOpts.fetchRelatedData( record, 	DSExtensions);
               //VMgrUserOptions.fetchRelatedData( record, 	DSExtensions);
               VMgrUserOptions.fetchData({'t_sip_user_id':record.id}, function(rs,data,rq){
               	 VMgrUserOptions.setValue('call_followme_options', JSON.parse(data.getProperty("call_followme_options")) );
                   frmUserOpt_AlwaysFWD.setValue( (data.getProperty("call_forwarding") > 1 ) ? 2 : data.getProperty("call_forwarding")       );
                   frmUserOpt_BusyFWD.setValue( ( data.getProperty("call_forward_onbusy") > 1 ) ? 2 : data.getProperty("call_forward_onbusy") );                   
               });   
           
               
               tblUserBlockList.fetchRelatedData( record, DSExtensions, function(){
               	       var defMode = frmUserOpts.getValues()['call_blocking'];
                         tblUserBlockList.getField('t_sip_user_id').defaultValue = record.id;
                         tblUserBlockList.fetchData({'allowed':defMode, 't_sip_user_id':record.id});
                         Ext_CID_list.setContents('<b>CallerID ' + ((defMode == 0)?'Block':'Allowed') + ' List</b>');
                         tblUserBlockList.getField('allowed').defaultValue = (defMode == 1)?1:0;
               } );

               tblUserDevices.fetchRelatedData( record, DSExtensions, function(){               	       
                         tblUserDevices.getField('t_sip_user_id').defaultValue = record.id;
                         tblUserDevices.fetchData({'t_sip_user_id':record.id});
                         //Ext_DEV_list.setContents('<b> Extension Logged in</b>');                         
               } );
               
               tblUserScreenList.fetchRelatedData( record, DSExtensions, function(){
               	       var defMode = (VMgrUserOptions.getValues()['call_screening'] == 1 );
                         tblUserScreenList.getField('t_sip_user_id').defaultValue = record.id;
                         tblUserScreenList.getField('screened').defaultValue = (defMode?0:1);
                         tblUserScreenList.fetchData({'screened':(defMode?0:1), 't_sip_user_id':record.id});
                         Ext_SCREEN_list.setContents('<b>'+ (defMode?'NEVER':'ALWAYS') + ' Screen For this CallerID</b>');
               } );
               
               tblUserFollowMeList.fetchRelatedData( record, DSExtensions, function(){
               	 console.log('defualt sip user for followme list:' + record.id);
               	       tblUserFollowMeList.getField('t_sip_user_id').defaultValue = record.id;
               } );
               
               frmvm_users.fetchData({'mailbox': frmExtensions.getValues()['extension'] }, function(){
                 if( !frmvm_users.getValues()['mailbox'] ){
                   frmvm_users.setValue('mailbox',frmExtensions.getValues()['extension'] );
                   frmvm_users.setValue('password', Math.floor(Math.random() * 90000) + 10000 );
                 }                    
               });
		   	 }
		})
		
   // END Extensions //

function get_header_label( section_title ){
    return isc.Label.create({ icon:  section_title + ".png",
    									contents: "<span style='color:#668B8B;font-size:18px;font-weight:bold;'><i>" + section_title + "</i> </span>",
    									autoDraw:false,
    									align: "left", 
    									iconSize:32,
    									width:'100%'
    								});
}   		
   
  isc.HTMLPane.create({
        ID:"htmlContainer",
        showEdges:false,
        contentsURL:"",
        autoDraw:false,
        contentsType:"page"
    }) 

  isc.HTMLPane.create({
        ID:"htmlContainer2",
        showEdges:false,
        contentsURL:"",
        autoDraw:false,
        contentsType:"page"
    })   

    isc.HTMLPane.create({
        ID:"htmlContainer3",
        showEdges:false,
        contentsURL:"",
        autoDraw:false,
        contentsType:"page"
    })

    isc.HTMLPane.create({
        ID:"htmlContainer4",
        showEdges:false,
        contentsURL:"",
        autoDraw:false,
        contentsType:"page"
    })

  isc.HTMLPane.create({
        ID:"htmlCntExtenStatus",
        showEdges:false,
        contentsURL:"",
        autoDraw:false,
        contentsType:"page"
    })  

   
   
 	isc.SectionStack.create({
	    ID: "Sections",
	    visibilityMode: "mutex",
	    width: "100%", 
	    height: "99%",	   
	    padding:4,
	 //   backgroundColor:'#FFFFFF',
	    styleName:'myStaff',
	    sections: [
	        {showHeader:false,title: "Users",      ID: "section_adminusers", items: [ tblAdminusersHeader,   tblAdminusers ] },
			  {showHeader:false,title: "Tenants",    ID: "section_tenants",    items: [ tblTenantsHeader,   tblTenants ] },
			  {showHeader:false,title: "DIDs",       ID: "section_dids",       items: [ tblDIDsHeader,      tblDIDs   ] },
			  {showHeader:false,title: "Trunks",     ID: "section_trunks",     items: [ tblTrunksHeader,    tblTrunks ] },
			  {showHeader:false,title: "Default MOH",ID: "section_mohdefault", items: [ getMohDefault()               ] },
			  {showHeader:false,title: "Tenants MOH",ID: "section_moh",        items: [ tblMOHHeader,        tblMOH    ] },
			  {showHeader:false,title: "Default SND",ID: "section_snddefault", items: [ tblSndDefaultHeader, tblSndDefault ] },
			  {showHeader:false,title: "Tenants SND",ID: "section_sndtenants", items: [ tblSndTenantsHeader, tblSndTenants ] },
			  {showHeader:false,title: "Inbound",    ID: "section_inbound",    items: [ tblInboundHeader,    tblInbound ] },
			  {showHeader:false,title: "Routes",     ID: "section_routes",     items: [ tblRouteHeader,      tblRoute  ] },
           {showHeader:false,title: "IVR Menues", ID: "section_ivrmenu",    items: [ tblIVRMenuHeader,    tblIVRMenu ] },
           {showHeader:false,title: "Shift Time ", ID: "section_shifts",    items: [ tblShiftsHeader,     tblShifts ] },
           {showHeader:false,title: "Queues",     ID: "section_queues",     items: [ tblQueuesHeader,     tblQueues ] },
           {showHeader:false,title: "Page Groups",ID: "section_pagegroups", items: [ tblPagegroupsHeader, tblPagegroups ] },
           {showHeader:false,title: "Ring Groups",ID: "section_ringgroups", items: [ tblRinggroupsHeader, tblRinggroups ] },
           {showHeader:false,title: "Conferences",ID: "section_conferences",items: [ tblConferencesHeader,tblConferences ] },
           {showHeader:false,title: "FeatureCode",ID: "section_features",   items: [ tblFeaturesHeader,   tblFeatures ] },
           {showHeader:false,title: "FeatureDflt",ID: "section_featuresdef",items: [ tblFeaturesdefHeader,tblFeaturesdef ] },
           //{showHeader:false,title: "Call Record",ID: "section_recordings", items: [ tblRecordingsHeader, tblRecordings ] },
           {showHeader:false,title: "Call Record",ID: "section_recordings", items: [  htmlContainer4 ] },           
           {showHeader:false,title: "CDRs",       ID: "section_cdrs",       items: [ tblCDRsHeader,       tblCDRs ] },
           {showHeader:false,title: "PredictiveD",ID: "section_campaigns",  items: [ tblCampaignsHeader,  tblCampaigns ] },
           {showHeader:false,title: "LostCalls",  ID: "section_lostcalls",  items: [ tblLostCallsHeader,  tblLostCalls ] },
           {showHeader:false,title: "Dashboard",  ID: "section_dashboard",  items: [  htmlContainer ] },
           {showHeader:false,title: "S Reports",  ID: "section_summary_reports", items: [  htmlContainer2 ] },           
           {showHeader:false,title: "DIDs Usage", ID: "section_dids_usage", items: [  htmlContainer3 ] },           
	       {showHeader:false,title: "Extensions", ID: "section_extensions", items: [ tblExtensionsHeader, tblExtensions ] },
           {showHeader:false,title: "Black List", ID: "section_blacklist",  items: [ tblBlacklistHeader, tblBlacklist ] },
	       {showHeader:false,title: "Exten Status", ID: "section_extenstatus",   items: [ htmlCntExtenStatus ] }
         ] 
	});	 
