// This code uses SmartClient's LoginDialog class and relogin facility to
// present a common, SmartClient-based login UI to a DMI-based authentication system.
// isomorphic/login/reloginFlow.js is used as a template for the implementation of
// loginRequired() and related code, but unlike the reference login implementation
// which includes reloginFlow.js, login.html et al., this implementation affords
// a login interface written entirely in SmartClient.
//
// This file is to be included in every page demanding
// authentication; the "user" datasource must also bec included beforehand.
// The login page simply calls "isc.RPCManager.createLoginDialog()"
// to display the dialog. No other modifications are required for other pages.

isc.ClassFactory.defineClass("SecureAppLoginDialog", "LoginDialog").addProperties({ 
    dismissable: false,
    showRegistrationLink: true,
    registrationItemTitle: "Click here to register.",
    // Default behavior of registration link is to call dialog.register().
    // instead, link to register.jsp.
    registrationItemProperties: {
        target: "_self",
        defaultValue: "register.jsp"
    },
    showLostPasswordLink: true,
    showModalMask: true,
    modalMaskOpacity: 25,
    
    lostPasswordItemTitle: "Lost password? Enter username and click here.",
    lostPassword : function (values, form) {
        if (!values.usernameItem) {
            form.setFieldErrors(
                "usernameItem", 
                "Username must be specified to request lost password",
                true);
            return;
        }
        isc.DataSource.get("user").updateData(
            {username: values.usernameItem},
            function (dsResponse, data, dsRequest) {
                if (data && data.length>0) isc.say("Password reset for user " + 
                    form.getItem("usernameItem").getValue() + 
                    ". An email containing the new password has been sent.");
                else isc.say("Unable to locate user " + 
                    form.getItem("usernameItem").getValue() + " for password reset.");
            }, { operationId: "resetPassword"});
    },
    loginFunc: function (credentials, dialogCallback) {
        if (credentials == null) return;
        var form = this;
        // Issue DMI call to log in.
        // Custom actionURL is necessary to pass through authentication as documented in web.xml.
        isc.DMI.call({
            appID: "SecureAppDMI",
            className: "SecureAppDMI",
            methodName: "login",
            requestParams: {
                actionURL: "/examples/secureApp/IDACall",
                containsCredentials: true,
                params : {
                    username: credentials.username,
                    password: credentials.password
                }
            },
            callback : function (response, data) {
                if (form){
                    //form.clearValues();
                    form.clearErrors(true);
                }
                
                if (response.status == isc.RPCResponse.STATUS_SUCCESS) {
                    isc.RPCManager.doResendTransaction();
                    delete this.transactionsToResubmit;
                    dialogCallback(true);
                } else {
                    if (form) {
                        form.setFieldErrors("usernameItem",
                            "Login failed: please check username and password.", false);
                        form.setFieldErrors("passwordItem",
                            "Login failed: please check username and password.", true);
                    }
                    dialogCallback(false);
                }
            }
        });
    }
});


isc.RPCManager.addClassProperties({
    credentialsURL: "/examples/secureApp/IDACall",
    loginRequired: function (transactionNum, rpcRequest, rpcResponse) {
        if (this.loginWindow && this.loginWindow.isVisible() && this.loginWindow.isDrawn())
            return;
        
        if (!this.loginWindow) this.createLoginDialog();
        
        // only clear the form and re-focus in the username field if we're not already showing
        // it such that background RPCs occurring during our login attempt don't clear out the
        // form
        if (!(this.loginWindow.isVisible() && this.loginWindow.isDrawn())) {
            this.loginWindow.loginForm.clearValues();
            this.loginWindow.loginForm.delayCall("focusInItem", ["username"]);
            this.loginWindow.show();
        }
        
        this.loginWindow.show();
        this.loginWindow.bringToFront();
    },
    createLoginDialog : function () {
        this.loginWindow = isc.SecureAppLoginDialog.create({});
    },
    doResendTransaction : function () {
        // assume that all suspended transactions should be resumed. If this is
        // not the case, keep track of which ones must be resent in loginRequired().
        this.delayCall("resendTransaction");
    }
});
