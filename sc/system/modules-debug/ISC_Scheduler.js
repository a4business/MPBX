/*

  SmartClient Ajax RIA system
  Version v11.1p_2017-12-22/LGPL Deployment (2017-12-22)

  Copyright 2000 and beyond Isomorphic Software, Inc. All rights reserved.
  "SmartClient" is a trademark of Isomorphic Software, Inc.

  LICENSE NOTICE
     INSTALLATION OR USE OF THIS SOFTWARE INDICATES YOUR ACCEPTANCE OF
     ISOMORPHIC SOFTWARE LICENSE TERMS. If you have received this file
     without an accompanying Isomorphic Software license file, please
     contact licensing@isomorphic.com for details. Unauthorized copying and
     use of this software is a violation of international copyright law.

  DEVELOPMENT ONLY - DO NOT DEPLOY
     This software is provided for evaluation, training, and development
     purposes only. It may include supplementary components that are not
     licensed for deployment. The separate DEPLOY package for this release
     contains SmartClient components that are licensed for deployment.

  PROPRIETARY & PROTECTED MATERIAL
     This software contains proprietary materials that are protected by
     contract and intellectual property law. You are expressly prohibited
     from attempting to reverse engineer this software or modify this
     software for human readability.

  CONTACT ISOMORPHIC
     For more information regarding license rights and restrictions, or to
     report possible license violations, please contact Isomorphic Software
     by email (licensing@isomorphic.com) or web (www.isomorphic.com).

*/

if(window.isc&&window.isc.module_Core&&!window.isc.module_Scheduler){isc.module_Scheduler=1;isc._moduleStart=isc._Scheduler_start=(isc.timestamp?isc.timestamp():new Date().getTime());if(isc._moduleEnd&&(!isc.Log||(isc.Log && isc.Log.logIsDebugEnabled('loadTime')))){isc._pTM={ message:'Scheduler load/parse time: ' + (isc._moduleStart-isc._moduleEnd) + 'ms', category:'loadTime'};
if(isc.Log && isc.Log.logDebug)isc.Log.logDebug(isc._pTM.message,'loadTime');
else if(isc._preLog)isc._preLog[isc._preLog.length]=isc._pTM;
else isc._preLog=[isc._pTM]}isc.definingFramework=true;isc.DataSource.create({
    operationBindings:[
        {
            operationId:"start",
            operationType:"custom"
        },
        {
            operationId:"shutdown",
            operationType:"custom"
        },
        {
            operationId:"standby",
            operationType:"custom"
        },
        {
            operationId:"doit",
            operationType:"custom"
        }
    ],
    allowAdvancedCriteria:true,
    ID:"QuartzScheduler",
    fields:[
        {
            name:"name",
            type:"text",
            validators:[
            ],
            canEdit:false
        },
        {
            name:"state",
            valueMap:{
                "0":"Shutdown",
                "1":"Standby",
                "2":"Started"
            },
            type:"intEnum",
            validators:[
            ],
            canEdit:false
        }
    ]
})
isc.DataSource.create({
    allowAdvancedCriteria:true,
    ID:"QuartzJobs",
    fields:[
        {
            name:"group",
            type:"string",
            required:true,
            validators:[
            ],
            primaryKey:true
        },
        {
            name:"name",
            type:"string",
            required:true,
            validators:[
            ],
            primaryKey:true
        },
        {
            name:"description",
            type:"string",
            validators:[
            ]
        },
        {
            name:"className",
            type:"string",
            required:true,
            validators:[
            ]
        },
        {
            name:"durability",
            type:"boolean",
            defaultValue:"true",
            validators:[
            ]
        },
        {
            name:"recover",
            type:"boolean",
            defaultValue:"true",
            validators:[
            ]
        },
        {
            name:"dataMap",
            showIf:"false",
            type:"Object",
            validators:[
            ]
        }
    ]
})
isc.DataSource.create({
    allowAdvancedCriteria:true,
    ID:"QuartzTriggers",
    fields:[
        {
            name:"jobGroup",
            showIf:"false",
            type:"string",
            required:true,
            validators:[
            ]
        },
        {
            name:"jobName",
            showIf:"false",
            type:"string",
            required:true,
            validators:[
            ]
        },
        {
            name:"group",
            type:"string",
            required:true,
            validators:[
            ],
            primaryKey:true
        },
        {
            name:"name",
            type:"string",
            required:true,
            validators:[
            ],
            primaryKey:true
        },
        {
            name:"description",
            type:"string",
            validators:[
            ]
        },
        {
            name:"dataMap",
            showIf:"false",
            type:"Object",
            validators:[
            ]
        },
        {
            name:"startTime",
            type:"datetime",
            validators:[
            ]
        },
        {
            name:"endTime",
            type:"datetime",
            validators:[
            ]
        },
        {
            name:"cronExpression",
            type:"text",
            required:true,
            validators:[
            ]
        },
        {
            addUnknownValues:"false",
            textMatchStyle:"substring",
            validators:[
            ],
            name:"timeZone",
            displayField:"ID",
            optionDataSource:"QuartzTimeZone",
            editorType:"ComboBoxItem",
            type:"text",
            valueField:"ID"
        },
        {
            name:"misfireInstruction",
            valueMap:{
                "0":"MISFIRE_INSTRUCTION_SMART_POLICY",
                "-1":"MISFIRE_INSTRUCTION_IGNORE_MISFIRE_POLICY",
                "1":"MISFIRE_INSTRUCTION_FIRE_ONCE_NOW",
                "2":"MISFIRE_INSTRUCTION_DO_NOTHING"
            },
            type:"intEnum",
            defaultValue:"0",
            validators:[
            ]
        },
        {
            name:"state",
            valueMap:{
                "0":"Normal",
                "1":"Paused",
                "2":"Complete",
                "3":"Error",
                "4":"Blocked",
                "-1":"None"
            },
            type:"intEnum",
            validators:[
            ],
            canEdit:false
        }
    ]
})
isc.DataSource.create({
    operationBindings:[
        {
            operationType:"fetch"
        }
    ],
    allowAdvancedCriteria:true,
    ID:"QuartzTimeZone",
    fields:[
        {
            name:"ID",
            type:"string",
            validators:[
            ],
            primaryKey:true
        }
    ]
})
isc.defineClass("QuartzManager","SectionStack");
isc.A=isc.QuartzManager.getPrototype();
isc.B=isc._allFuncs;
isc.C=isc.B._maxIndex;
isc.D=isc._funcClasses;
isc.D[isc.C]=isc.A.Class;
isc.A.visibilityMode="multiple";
isc.A.jobsPauseBtnDefaults={
    _constructor:"IButton",
    title:"Pause Job",
    prompt:"Suspends all triggers associated with selected job",
    click:function(){
        var jobsGrid=this.creator.jobsGrid;
        if(!jobsGrid.anySelected()){
            isc.say("Please select a job first");
            return;
        }
        var job=jobsGrid.getSelectedRecord();
        var _this=this;
        QuartzJobs.performCustomOperation("pauseJob",{group:job.group,name:job.name},function(dsResponse){
            _this.creator.triggersGrid.invalidateCache();
            isc.say('Job Paused');
        });
    }
};
isc.A.jobsResumeBtnDefaults={
    _constructor:"IButton",
    title:"Resume Job",
    prompt:"Resumes all triggers associated with selected job",
    click:function(){
        var jobsGrid=this.creator.jobsGrid;
        if(!jobsGrid.anySelected()){
            isc.say("Please select a job first");
            return;
        }
        var job=jobsGrid.getSelectedRecord();
        var _this=this;
        QuartzJobs.performCustomOperation("resumeJob",{group:job.group,name:job.name},function(dsResponse){
            _this.creator.triggersGrid.invalidateCache();
            isc.say('Job Resumed');
        });
    }
};
isc.A.jobsTriggerBtnDefaults={
    _constructor:"IButton",
    title:"Trigger Job",
    prompt:"Triggers selected job immediately",
    click:function(){
        var jobsGrid=this.creator.jobsGrid;
        if(!jobsGrid.anySelected()){
            isc.say("Please select a job first");
            return;
        }
        var job=jobsGrid.getSelectedRecord();
        QuartzJobs.performCustomOperation("triggerJob",{group:job.group,name:job.name},function(dsResponse){
            isc.say('Job Triggered');
        });
    }
};
isc.A.jobsRefreshBtnDefaults={
    _constructor:"ImgButton",
    showRollOver:false,
    size:16,
    src:"[SKIN]actions/refresh.png",
    prompt:"Refresh jobs",
    click:function(){
        this.creator.jobsGrid.invalidateCache();
        this.creator.triggersGrid.setData([]);
    }
};
isc.A.jobsAddBtnDefaults={
    _constructor:"ImgButton",
    size:16,
    showRollOver:false,
    src:"[SKIN]actions/add.png",
    prompt:"Add job",
    click:"this.creator.jobsGrid.startEditingNew()"
};
isc.A.jobsRemoveBtnDefaults={
    _constructor:"ImgButton",
    size:16,
    showRollOver:false,
    src:"[SKIN]actions/remove.png",
    prompt:"Remove job",
    click:function(){
        var _this=this;
        isc.ask("Are you sure you wish to delete the selected job?  This will remove all"
          +" triggers associated with this job.",function(yes){
             if(yes)_this.creator.jobsGrid.removeSelectedData(function(dsResponse){
                 _this.creator.triggersGrid.setData([]);
             });
        });
    }
};
isc.A.jobsGridDefaults={
    _constructor:"ListGrid",
    autoDraw:false,
    height:"30%",
    dataSource:"QuartzJobs",
    useAllDataSourceFields:true,
    autoFetchData:true,
    selectionType:"single",
    recordDoubleClick:function(){
        isc.say("The Quartz APIs do not allow modification of job metadata without destroying"
          +" all triggers attached to the job, so you must remove and re-create the job if"
          +" that's your intention");
        return;
    },
    selectionChanged:function(record,state){
        if(state){
            this.creator.triggersGrid.filterData({
                jobGroup:record.group,
                jobName:record.name
            });
        }else{
            this.creator.triggersGrid.setData([]);
        }
    },
    remove:function(){
    }
};
isc.A.triggersPauseBtnDefaults={
    _constructor:"IButton",
    title:"Pause Trigger",
    prompt:"Suspends selected trigger",
    click:function(){
        var triggersGrid=this.creator.triggersGrid;
        if(!triggersGrid.anySelected()){
            isc.say("Please select a trigger first");
            return;
        }
        var trigger=triggersGrid.getSelectedRecord();
        QuartzTriggers.performCustomOperation("pauseTrigger",{group:trigger.group,name:trigger.name},function(dsResponse){
            triggersGrid.invalidateCache();
            isc.say('Trigger Paused');
        });
    }
};
isc.A.triggersResumeBtnDefaults={
    _constructor:"IButton",
    title:"Resume Trigger",
    prompt:"Resumes selected trigger",
    click:function(){
        var triggersGrid=this.creator.triggersGrid;
        if(!triggersGrid.anySelected()){
            isc.say("Please select a trigger first");
            return;
        }
        var trigger=triggersGrid.getSelectedRecord();
        QuartzTriggers.performCustomOperation("resumeTrigger",{group:trigger.group,name:trigger.name},function(dsResponse){
            triggersGrid.invalidateCache();
            isc.say('Trigger Resumed');
        });
    }
};
isc.A.triggersRefreshBtnDefaults={
    _constructor:"ImgButton",
    showRollOver:false,
    size:16,
    src:"[SKIN]actions/refresh.png",
    prompt:"Refresh jobs",
    click:"this.creator.triggersGrid.invalidateCache()"
};
isc.A.triggersAddBtnDefaults={
    _constructor:"ImgButton",
    size:16,
    showRollOver:false,
    src:"[SKIN]actions/add.png",
    prompt:"Add trigger",
    click:function(){
        var jobsGrid=this.creator.jobsGrid;
        if(!jobsGrid.anySelected()){
            isc.say("Please select a job first");
            return;
        }
        var job=jobsGrid.getSelectedRecord();
        this.creator.triggersGrid.startEditingNew({
            jobGroup:job.group,
            jobName:job.name
        });
    }
};
isc.A.triggersRemoveBtnDefaults={
    _constructor:"ImgButton",
    size:16,
    showRollOver:false,
    src:"[SKIN]actions/remove.png",
    prompt:"Remove job",
    click:function(){
        var _this=this;
        isc.ask("Are you sure you wish to remove the selected trigger?",function(yes){
             if(yes)_this.creator.triggersGrid.removeSelectedData(function(dsResponse){
                 _this.creator.triggersGrid.invalidateCache();
             });
        });
    }
};
isc.A.triggersGridDefaults={
    _constructor:"ListGrid",
    canEdit:true,
    autoDraw:false,
    dataSource:"QuartzTriggers",
    useAllDataSourceFields:true,
    selectionType:"single",
    remove:function(){
    }
};
isc.B.push(isc.A.initWidget=function isc_QuartzManager_initWidget(){
    this.Super("initWidget",arguments);
    this.jobsPauseBtn=this.createAutoChild("jobsPauseBtn");
    this.jobsResumeBtn=this.createAutoChild("jobsResumeBtn");
    this.jobsTriggerBtn=this.createAutoChild("jobsTriggerBtn");
    this.jobsRefreshBtn=this.createAutoChild("jobsRefreshBtn");
    this.jobsAddBtn=this.createAutoChild("jobsAddBtn");
    this.jobsRemoveBtn=this.createAutoChild("jobsRemoveBtn");
    this.jobsGrid=this.createAutoChild("jobsGrid",{autoFetchData:this.autoFetchData!==false});
    this.addSection({
        title:"Jobs",
        expanded:true,
        items:[this.jobsGrid],
        controls:[this.jobsPauseBtn,this.jobsResumeBtn,this.jobsTriggerBtn,this.jobsRefreshBtn,this.jobsAddBtn,this.jobsRemoveBtn]
    });
    this.triggersPauseBtn=this.createAutoChild("triggersPauseBtn");
    this.triggersResumeBtn=this.createAutoChild("triggersResumeBtn");
    this.triggersRefreshBtn=this.createAutoChild("triggersRefreshBtn");
    this.triggersAddBtn=this.createAutoChild("triggersAddBtn");
    this.triggersRemoveBtn=this.createAutoChild("triggersRemoveBtn");
    this.triggersGrid=this.createAutoChild("triggersGrid");
    this.addSection({
        title:"Triggers",
        expanded:true,
        items:[this.triggersGrid],
        controls:[this.triggersPauseBtn,this.triggersResumeBtn,this.triggersRefreshBtn,this.triggersAddBtn,this.triggersRemoveBtn]
    });
}
);
isc.B._maxIndex=isc.C+1;

isc._debugModules = (isc._debugModules != null ? isc._debugModules : []);isc._debugModules.push('Scheduler');isc.checkForDebugAndNonDebugModules();isc._moduleEnd=isc._Scheduler_end=(isc.timestamp?isc.timestamp():new Date().getTime());if(isc.Log&&isc.Log.logIsInfoEnabled('loadTime'))isc.Log.logInfo('Scheduler module init time: ' + (isc._moduleEnd-isc._moduleStart) + 'ms','loadTime');delete isc.definingFramework;if (isc.Page) isc.Page.handleEvent(null, "moduleLoaded", { moduleName: 'Scheduler', loadTime: (isc._moduleEnd-isc._moduleStart)});}else{if(window.isc && isc.Log && isc.Log.logWarn)isc.Log.logWarn("Duplicate load of module 'Scheduler'.");}
/*

  SmartClient Ajax RIA system
  Version v11.1p_2017-12-22/LGPL Deployment (2017-12-22)

  Copyright 2000 and beyond Isomorphic Software, Inc. All rights reserved.
  "SmartClient" is a trademark of Isomorphic Software, Inc.

  LICENSE NOTICE
     INSTALLATION OR USE OF THIS SOFTWARE INDICATES YOUR ACCEPTANCE OF
     ISOMORPHIC SOFTWARE LICENSE TERMS. If you have received this file
     without an accompanying Isomorphic Software license file, please
     contact licensing@isomorphic.com for details. Unauthorized copying and
     use of this software is a violation of international copyright law.

  DEVELOPMENT ONLY - DO NOT DEPLOY
     This software is provided for evaluation, training, and development
     purposes only. It may include supplementary components that are not
     licensed for deployment. The separate DEPLOY package for this release
     contains SmartClient components that are licensed for deployment.

  PROPRIETARY & PROTECTED MATERIAL
     This software contains proprietary materials that are protected by
     contract and intellectual property law. You are expressly prohibited
     from attempting to reverse engineer this software or modify this
     software for human readability.

  CONTACT ISOMORPHIC
     For more information regarding license rights and restrictions, or to
     report possible license violations, please contact Isomorphic Software
     by email (licensing@isomorphic.com) or web (www.isomorphic.com).

*/

