<%@ taglib uri="/WEB-INF/iscTaglib.xml" prefix="isomorphic" %>
<HTML><HEAD><TITLE>SmartClient Secure Login Example</TITLE>
<isomorphic:loadISC skin="Enterprise" />
</HEAD><BODY>
<SCRIPT>

<isomorphic:loadDS ID="user"/>
isc.DynamicForm.create({
    ID: "boundForm",
    width: 400,
    height: 400,
    dataSource: "user",
    useAllDataSourceFields: true,
    fields: [
        {type:"header", defaultValue:"Registration Form"},
        {name:"id", hidden:true},
        {name: "password", type: "password", required: true},
        {name: "password2", title: "Password Again", type: "password", required: true, 
         length: 20, validators: [{
             type: "matchesField",
             otherField: "password",
             errorMessage: "Passwords do not match"
         }]
        },
        {name: "profile" },
        {name:"addBtn", title:"Register", type:"button", click: "form.validate() && form.doRegister()" }
    ]
,    doRegister : function () {
        var form=this;
        this.getDataSource().addData(this.getValues(), 
            function (dsResponse, data, dsRequest) {
                isc.say("User " + data[0].username + " created.",
                    'window.location = "index.jsp"');
            }, { operationId: "register" });
    }
});
</SCRIPT>
</BODY>
</HTML>

