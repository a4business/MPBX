
  var LGDefault = {
	       backgroundColor: "white",         
          padding:8,
		    height:'*',
		    border: "none",
		    autoDraw: false,		    
		    alternateRecordStyles:true, 
		    showAllRecords:true,
		    autoFetchData: true, 
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
            isc.IButton.create({ title: "Add Conference",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('Conferences');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblConferences.removeSelectedData();TriggerEvent('reload_conferences');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblConferences",
		    dataSource: DSConferences,
		    fields: [{name:'conference'},{name:'description'},{name:'users'} ],
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Conferences');
               frmConferences.editSelectedData(tblConferences);
		    }
		})




// INBOUND ROUTING Header//
    
    isc.HLayout.create(HLDefault, {
        ID: 'tblInboundHeader',            
        members: [
            isc.IButton.create({ title: "Create Inbound Route",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('Inbound');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblInbound.removeSelectedData();TriggerEvent('reload_inbound');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
 
// Inbound ROUTING // 
 		isc.ListGrid.create(LGDefault,{
		    ID: "tblInbound",
          drawAheadRatio: 4,
		    dataSource: DSInbound,
		    canEdit: true,
          modalEditing: true,
          editEvent: "click",
          listEndEditAction: "next",
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
					             listEndEditAction: "next",
					             canFocusInEmptyGrid:true,
					             canRemoveRecords: true,
					             useAllDataSourceFields: true,
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
													 case "dialtone":
													 case "unassigned":  return { canEdit:false,editorType:"StaticText"};
													 
													 case "play_tts": 
													 case "play_rec": 
						              	   	 case "number":   	return { canEdit:true,editorType:"TextItem"};	
						              	   	 
						              	   	 case "extension":
						                      case "ringgroup":
						                      case "ivrmenu":
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
				                      {name:"action", redrawOnChange:true },
				                      {name:"week_day_from", width:55 },{name:"week_day_to", width:55 },
				                      {name:"day_time_from", width:65 },{name:"day_time_to", width:65 },
				                      {name:"destination", type:'select',   editorType: "SelectItem",
				                         optionDataSource: getTenantDS('items'),  
				 								 displayField:"name", valueField:"id"
				                      }				                      
						             ]
				          	  });
          	  rulesGrid.fetchRelatedData(record, 	DSInbound );  
				  //routesGrid.fetchData({route_id: record.id});
          	  var RulesHLayout = isc.HLayout.create({
          	  	      align: "center",padding:4,
          	  	      membersMargin: 6,
          	  	      members: [
          	  	           isc.IButton.create({ title:"Save",  click: function(){ rulesGrid.saveAllEdits();} }),
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
		
// Tenant Queues//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblQueuesHeader',
        members: [
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
		    }
		})

// DEFAULT Tenant Queues//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblFeaturesdefHeader',
        members: [
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
		
		



// Tenant TimeFilters//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblTimeFiltersHeader',
        members: [
            isc.IButton.create({ title: "Add Rule",autoDraw:false,icon:"[SKIN]actions/add.png", click: "new_object('TimeFilters');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblTimeFilters.removeSelectedData();TriggerEvent('reload_timefilters');",icon:"[SKIN]actions/remove.png"})            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblTimeFilters",
		    dataSource: DSTimeFilters,
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('TimeFilters');
               frmTimeFilters.editSelectedData(tblTimeFilters);
		    }
		})


// OUTBOUND ROUTING Header//    
    isc.HLayout.create(HLDefault, {
        ID: 'tblRouteHeader',            
        members: [
            isc.IButton.create({ title: "Create Route",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('Route');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblRoute.removeSelectedData();TriggerEvent('reload_route');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
	   
 // OUTBOUND ROUTING Table
 		isc.ListGrid.create(LGDefault,{
		    ID: "tblRoute",
          drawAheadRatio: 4,
		    dataSource: DSRoute,
		    canEdit: true,
          modalEditing: true,
          editEvent: "click",
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
          	  	           isc.IButton.create({ title:"Save",click: function(){ routesGrid.saveAllEdits();} }),
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
                   {name:"file_name"},{name:"size"},{name:"format"},{name:"duration"},
                   {name:'playIcon', width:22, title:"", canEdit:false, readOnlyDisplay:'static'} 
                 ],
          showRecordComponents: true,
          showRecordComponentsByCell: true,
          alternateRecordStyles:true,
	       createRecordComponent : function (record, colNum){
	       	        var PlayerID = this.SoundsTypeID ;
				        if ( this.getFieldName(colNum)  == "playIcon" )  
	                     return   isc.ImgButton.create({
	                     	             src: "[SKIN]/pickers/play_picker.png",
								                showDown: false, showRollOver: false,autoDraw:false,
								                layoutAlign: "center",prompt: "Play Item Media ", height: 16, width: 16, grid: this,
 													 click : function (){		
  													          //isc.Sound.play( 'media/play.php?mode=recording&play_text=' + record['file_name'] )														
 														       window[PlayerID+'Player'].src = 'media/play.php?mode=recording&play_text=' + record['file_name'] + '&play_dir=' + PlayerID ;
                                                 window[PlayerID+'Player'].play();
                                                }   
		                            })
  	       }
    });
		            
      
         
 // SOUNDS  Media files TableHEADER for DefaultSounds  orSoundsDefault  //         
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
 										SoundsTypeID: 'SndDefault'   });

	
	
	isc.HLayout.create(HLDefault,{ ID: 'tblSndTenantsHeader',
	   members: [
            getMediaUploader('tenant','SndTenants'),
            getSearchForm('SndTenants'),				                                                    
            isc.HTMLFlow.create({ autoDraw:false, contents:"<audio style='box-shadow: 5px 4px 3px silver;' id='SndTenantsPlayer'  controls> </audio>",width:"40%"}),
            isc.IButton.create({ autoDraw:false, title: "Remove",autoDraw:false, click: "tblSndTenants.removeSelectedData();tblSndTenants.Refresh()",icon:"[SKIN]actions/remove.png"}),
       ] });
 	 isc.SoundListGrid.create({ ID: "tblSndTenants", autoFetchData: true,
 	 								    dataSource: ShowSNDFilesTenantsDS, 
 	 								    SoundsTypeID: 'SndTenants'  });
	 			
	 			
 	


// DIDs //    
    isc.HLayout.create(HLDefault, {
        ID: 'tblDIDsHeader',               
        members: [
            isc.IButton.create({ title: "Add DID",autoDraw:false,icon:"[SKIN]actions/add.png", click: "edit_object('DIDs');"   }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblDIDs.removeSelectedData();TriggerEvent('reload_dids');",icon:"[SKIN]actions/remove.png"})
            
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblDIDs",
		    dataSource: DSDIDs,		    		   
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
               //frmTrunks.fetchData( {id:record.id} ); // Locate certain trunk to edit 
				   edit_object('DIDs');
               frmDIDs.editSelectedData(tblDIDs);
		   	 }
		})
		





// TRUNKS //    
    isc.HLayout.create(HLDefault,{
        ID: 'tblTrunksHeader',               
        members: [
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
		





///// TENANTS
     isc.HLayout.create(HLDefault, {
        ID: 'tblTenantsHeader',               
        members: [
            isc.IButton.create({ title: "Create Tenant",autoDraw:false,icon:"[SKIN]actions/add.png", click: function () { edit_object('Tenants');  } }),
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblTenants.removeSelectedData();TriggerEvent('reload_tenants');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
	   		
	   		
		isc.ListGrid.create(LGDefault, {
		    ID: "tblTenants",		    
		    dataSource: DSTenants,		    
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
				   edit_object('Tenants');				                  
				   //frmTenants.editSelectedData(tblTenants);  //
               VMgrTenants.editSelectedData(tblTenants);
               TenantMOHPickList.pickListCriteria = {'id': VMgrTenants.getValues()['id'] };
		   	 }
		})
   // END Tenants //		
   
   
 // Extensions    
    isc.HLayout.create(HLDefault,{
        ID: 'tblExtensionsHeader',               
        members: [
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
            isc.IButton.create({ title: "Remove",autoDraw:false, click: "tblExtensions.removeSelectedData();TriggerEvent('reload_extensions');",icon:"[SKIN]actions/remove.png"})
         ]
	   });
 
 		isc.ListGrid.create(LGDefault, {
		    ID: "tblExtensions",		    
		    dataSource: DSExtensions,		    
		    recordDoubleClick: function (viewer, record, rowNum, field, fieldNum, value, rawValue) {
               //frmExtensions.fetchData( {id:record.id} ); // same as next ?
				   edit_object('Extensions');               
               VMgrExtensions.editSelectedData(tblExtensions);  //frmExtensions.editSelectedData(tblExtensions);               
               frmExtCallerID.setValue( (VMgrExtensions.getValues()['outbound_callerid'] != '') ? 'Specify:' : 'Use Default' );
               frmExtCallerName.setValue( (VMgrExtensions.getValues()['outbound_callername'] != '') ? 'Specify:' : 'Use Default' );
               frmIntCallerID.setValue( (VMgrExtensions.getValues()['internal_callerid'] != '') ? 'Specify:' : 'Use Default' );
               frmIntCallerName.setValue( (VMgrExtensions.getValues()['internal_callername'] != '') ? 'Specify:' : 'Use Default' );
                              
               // Prepare VoiceMail sub-table:
               frmvm_users.fetchData({'mailbox': frmExtensions.getValues()['extension'] }, function(){
                 if( !frmvm_users.getValues()['mailbox'] ){
                   frmvm_users.setValue('mailbox',frmExtensions.getValues()['extension'] );
                   frmvm_users.setValue('password', Math.floor(Math.random() * 90000) + 10000 );
                 }                    
               });
		   	 }
		})
		
		
   // END Extensions //		
   
   
 	isc.SectionStack.create({
	    ID: "Sections",
	    visibilityMode: "mutex",
	    width: "100%", height: "100%",
	    border:"none",
	    backgroundColor: "silver",
	    expanded:function(){isc.say('hi');},
	    sections: [
			  {showHeader:false,title: "Tenants",    ID: "section_tenants",    items: [ tblTenantsHeader,   tblTenants ] },
			  {showHeader:false,title: "DIDs",       ID: "section_dids",       items: [ tblDIDsHeader,      tblDIDs   ] },
			  {showHeader:false,title: "Trunks",     ID: "section_trunks",     items: [ tblTrunksHeader,    tblTrunks ] },
			  {showHeader:false,title: "Default MOH",ID: "section_mohdefault", items: [ getMohDefault()               ] },
			  {showHeader:false,title: "Tenants MOH",ID: "section_moh",        items: [ tblMOHHeader,        tblMOH    ] },
			  {showHeader:false,title: "Default SND",ID: "section_snddefault", items: [ tblSndDefaultHeader, tblSndDefault ] },
			  {showHeader:false,title: "Tenants SND",ID: "section_sndtenants", items: [ tblSndTenantsHeader, tblSndTenants ] },
			  {showHeader:false,title: "Inbound",    ID: "section_inbound",    items: [ tblInboundHeader,    tblInbound ] },
			  {showHeader:false,title: "Routes",     ID: "section_routes",     items: [ tblRouteHeader,      tblRoute  ] },
           {showHeader:false,title: "Extensions", ID: "section_extensions", items: [ tblExtensionsHeader, tblExtensions ] },
           {showHeader:false,title: "IVR Menues", ID: "section_ivrmenu",    items: [ tblIVRMenuHeader,    tblIVRMenu ] },
           {showHeader:false,title: "Time Filter",ID: "section_timefilters",items: [ tblTimeFiltersHeader,tblTimeFilters ] },
           {showHeader:false,title: "Queues",     ID: "section_queues",     items: [ tblQueuesHeader,     tblQueues ] },
           {showHeader:false,title: "Ring Groups",ID: "section_ringgroups", items: [ tblRinggroupsHeader, tblRinggroups ] },
           {showHeader:false,title: "Conferences",ID: "section_conferences",items: [ tblConferencesHeader,tblConferences ] },
           {showHeader:false,title: "FeatureCode",ID: "section_features",   items: [ tblFeaturesHeader,   tblFeatures ] },
           {showHeader:false,title: "FeatureDflt",ID: "section_featuresdef",items: [ tblFeaturesdefHeader,tblFeaturesdef ] }
		 ] 
	});	 