<%@ taglib uri="/WEB-INF/iscTaglib.xml" prefix="isomorphic" %>
<HTML><HEAD><TITLE>
    SmartClient SalesForce Navigator
</TITLE><isomorphic:loadISC skin="SmartClient" includeModules="SalesForce"/></HEAD><BODY BGCOLOR=#DFEDC8>

<SCRIPT>

isc.setAutoDraw(false);

window.service = isc.WebService.get("urn:partner.soap.sforce.com");

window.sApp = isc.Class.create();
sApp.addProperties({
    start : function (loginData) {
        if (loginData == false) {
            loginForm.showItem("loginFailure");
            return;
        } else {
            loginForm.hideItem("loginFailure");
            loginWindow.hide();
        }
        service.getEntityList("sApp.showEntityList(list)");
    },
    showEntityList : function (list) {
        this.logWarn("global types: " + this.echoAll(list));
        typeSelectorForm.setValueMap("typeSelect", list);
    },
    showSchema : function (objectType) {
        service.getEntity(objectType, "sApp.showSchemaReply(schema)");
    },
    showSchemaReply : function (schema) {
        schemaGrid.setData(schema.sfFields);
        searchGrid.setDataSource(schema);
        searchGrid.fetchData();
    }
});

isc.DynamicForm.create({
    ID:"typeSelectorForm", 
    items : [
        { name:"typeSelect", wrapTitle:false, title:"SalesForce Object to View", 
          type:"select", change:"sApp.showSchema(value)" }
    ]
});

var fieldSchema = service.getSchema("Field");
isc.getValues(fieldSchema.getFields()).setProperty("width", 100);
isc.ListGrid.create({
    ID:"schemaGrid", 
    dataSource : fieldSchema,
    useAllDataSourceFields: true,
    fields : [
        { name : "name" },
        { name : "label" },
        { name : "type" },
        { name : "soapType" },
        { name : "referenceTo" }
    ]
});

isc.ListGrid.create({
    ID:"searchGrid",
    canEdit : true,
    listEndEditAction:"next",
    contextMenu:isc.Menu.create({
        data:[
            { title:"Delete", click:"target.deleteSelected()" }
        ]
    })
});

isc.VLayout.create({
    autoDraw:true,
    width:"100%", height:"100%",
    members : [typeSelectorForm, schemaGrid, searchGrid]
});

// Login interface
// ---------------------------------------------------------------------------------------

this.loginForm = isc.DynamicForm.create({
    numCols: 2,
    autoDraw: false,
    autoFocus:true,
    saveOnEnter:true,
    submit : function () {
        service.login(loginForm.getValue("username"), loginForm.getValue("password"), 
                      "sApp.start(loginData)");
    },
    fields : [
        { name:"loginFailure", type:"blurb", cellStyle:"formCellError",
          defaultValue: "Invalid username or password", colSpan: 2,
          visible:false },
        { name:"username", title:"Username", titleOrientation: "left",
          keyPress : function (item, form, keyName) {
            if (keyName == "Enter") {
                form.focusInItem("password");
                return false;
            }
        }},
        { name:"password", title:"Password", titleOrientation: "left", type:"password" },
         { type:"button", type:"submit", title:"Log in" }
    ]
});
this.loginWindow = isc.Window.create({
    autoDraw:true,
    title: "Please log in",
    autoCenter: true,
    autoSize: true,
    isModal: true,
    items: [ this.loginForm ]
});

</SCRIPT>

</BODY></HTML>
