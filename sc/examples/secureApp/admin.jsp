<%@ taglib uri="/WEB-INF/iscTaglib.xml" prefix="isomorphic" %>
<HTML><HEAD><TITLE>SmartClient Secure Login Example - Admin Page</TITLE>
<isomorphic:loadISC skin="Enterprise" />
</HEAD><BODY>
<SCRIPT>

<isomorphic:loadDS ID="user"/>
<isomorphic:loadDS ID="roles"/>
<isomorphic:loadDS ID="allowedRoles"/>

<jsp:include page="loginDialog.js" />

isc.Label.create({
    ID: "titleLabel",
    contents: "<H1>Secure Application Example - Administrative Page</H1>",
    wrap: false, autoFit: true, height: 1
});

isc.ListGrid.create({
    ID: "userGrid",
    width: 600,
    height: 200,
    dataSource: "user",
    useAllDataSourceFields: true,
    showDetailFields: true,
    
    fetchOperation: "adminFetch",
    updateOperation: "adminUpdate",
    removeOperation: "adminRemove",
    addOperation: "adminAdd",
    
    canEdit: true,
    canRemoveRecords: true,
    selectionType: "single",
    longTestEditorThreshold: 32,
    
    autoFetchData: true,
    
    fields: [
    { name: "id", width: 50, hidden:true },
    { name: "username", width: 200 },
    { name: "password", width: 100 },
    { name: "profile", width: 200 },
    { name: "remove", title: "Remove user", isRemoveField: true}
    ]
});


isc.ListGrid.create({
    ID: "roleGrid",
    width: 400,
    height: 200,
    alternateRecordStyles: true,
    canEdit: true,
    autoFetchData: true,
    canRemoveRecords: true,
    selectionType: "single",
    dataSource: "roles",
    fields: [
    { name: "username", canEdit:true, title: "User", width: 200, 
        editorProperties: { 
            optionDataSource: "user",
            optionFilterContext: { operationId: "adminFetch" }
        },
        editorType: "SelectItem",  filterEditorType: "ComboBoxItem"
    },
    { name: "role", width: 150, canEdit: true, 
        optionDataSource: "allowedRoles", displayField: "role" },
    { name: "remove", title: "Remove role", isRemoveField: true }
    ]
});

isc.Button.create({
    ID: "createUserButton",
    title: "Create New User",
    autoFit: true,
    click : function () {
        userGrid.startEditingNew();
    }
});

isc.Button.create({
    ID: "createRoleButton",
    title: "Create New User Role",
    autoFit: true,
    click: "roleGrid.startEditingNew({})"
});

isc.Label.create({
    ID: "userGridLabel",
    contents: "User list", autoFit: true, wrap: false
});

isc.Label.create({
    ID: "roleGridLabel",
    contents: "Role list (select user to filter by user)", autoFit: true, wrap: false
}),

isc.VLayout.create({
    margin: 10,
    membersMargin: 10,
    children: [
        "titleLabel",
        "userGridLabel",
        "userGrid",
        "createUserButton",
        isc.LayoutSpacer.create({height: 10}),
        "roleGridLabel",
        "roleGrid",
        "createRoleButton"
    ]
})

</SCRIPT>
</BODY>
</HTML>

