<%@ taglib uri="/WEB-INF/iscTaglib.xml" prefix="isomorphic" %>
<HTML><HEAD><TITLE>SmartClient Secure Application Example</TITLE>
<isomorphic:loadISC skin="Enterprise" />
</HEAD><BODY>
<SCRIPT>
<isomorphic:loadDS ID="user"/>

<jsp:include page="loginDialog.js" flush="true" />

isc.VLayout.create({
membersMargin: 8,
margin: 10,
members: [
    isc.Label.create({
        contents: "<H1>Secure Application Example: Main Page</H1>",
        wrap: false, autoFit: true, height: 1
    }),
    isc.Button.create({
        title: "My Account",
        click: "editMyAccount()",
        autoFit: true
    }),
    isc.Button.create({
        title: "Logout",
        autoFit: true,
        click: function () {
            isc.DMI.call({
                appID: "SecureAppDMI",
                className: "SecureAppDMI",
                methodName: "logout",
                requestParams: { actionURL: "/examples/secureApp/IDACall" },
                callback : function (response, data) {
                    if (response.status == isc.RPCResponse.STATUS_SUCCESS) {
                        isc.say("You have successfully logged out.",
                            'window.location = "login.jsp"');
                    } else {
                        isc.say("An error occurred in the logout process.");
                    }
                }
            });
        }
    }),
    isc.Label.create({
        contents:"<a href=\"admin.jsp\">Admin page (requires admin privileges)</a>",
        wrap: false, autoFit: true, height: 1
    })
]});

function editMyAccount() {
    // grab existing user info (to be displayed in edit form)
    // DMI call must use special IDACall location for correct authentication.
    isc.DMI.call({
        appID: "SecureAppDMI",
        className: "SecureAppDMI",
        methodName: "getUserInfo",
        requestParams: { actionURL: "/examples/secureApp/IDACall" },
        callback : function (response, data) {
            if (response.status == isc.RPCResponse.STATUS_SUCCESS) {
                openEditWindow(data);
            } else {
                isc.say("Unable to retrieve account information.",
                    window.location = "login.jsp");
            }
        }
    });
}

editMyAccountWindow = null;
editMyAccountForm = null;
function openEditWindow(userInfo) {
    if (editMyAccountWindow == null){
        editMyAccountWindow = isc.Window.create({
            title: "Edit My Account",
            autoSize:true,
            autoCenter:true,
            bodyDefaults: { padding: 16 }
        });
        editMyAccountForm = isc.DynamicForm.create({
            dataSource: "user",
            wrapItemTitles:false,
            saveOnEnter:true,
            fields: [
                {name:"username", required: true, width: 250 },
                {name:"password", type: "password", required: true, width: 250 },
                {name: "password2", title: "Password Again", required: true,
                    type: "password", length: 20, width: 250,
                    validators: [{
                        type: "matchesField",
                        otherField: "password",
                        errorMessage: "Passwords do not match"
                }]},
                {name: "profile", type: "textArea", width: 250 },
                {name:"spacer", type: "rowSpacer" },
                {name:"saveBtn", title:"Save Changes", type:"button", 
                    colSpan: 0, endRow: false,
                    click: "form.validate() && form.doSave()" },
                {name: "cancelBtn", title: "Cancel Changes", type:"button", 
                    align: "right", colSpan: 0, startRow: false, 
                    click:"editMyAccountWindow.hide()"}
            ],
            doSave : function () {
                var _this = this;
                this.saveData(function (dsResponse, data, dsRequest) {
                    if (dsResponse < 0 || data == null) {
                        isc.warn("Could not update account details.  Contact your " +
                                 "administrator if this problem persists");
                        return;
                    }
                    isc.say("User " + data.username + " updated.",
                        'editMyAccountWindow.hide()');
                    _this.editRecord(data);
                }, { actionURL: "authenticatedOperations.jsp" });
            }
        });
        editMyAccountWindow.addItem(editMyAccountForm);
    }
    // call editRecord directly, rather than issuing a fetch, so that an extra
    // round trip is avoided, and to avoid the fetch operation on user.ds.xml
    // (which will send the password).
    editMyAccountForm.editRecord(userInfo);
    editMyAccountWindow.show();
}

</SCRIPT>
</BODY>
</HTML>