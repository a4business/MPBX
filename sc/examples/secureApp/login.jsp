<%@ taglib uri="/WEB-INF/iscTaglib.xml" prefix="isomorphic" %>
<HTML><HEAD><TITLE>SmartClient Secure Login Example</TITLE>
<isomorphic:loadISC skin="Enterprise" />
</HEAD><BODY>
<!--<a href="register.jsp">Register</a><br />
<a href="lostpassword.jsp">Lost Password?<br />-->

The following test account may be used:
<p>
Username: john.smith@isomorphic.com<br>
password: smith<br>
<p>
For the password reset button to successfully send the email containing the new password,
a SMTP server (defaulting to localhost) must be configured. Ensure that an SMTP server
is configured in the server.properties file. Please see the SmartClient Server documentation
on the MailMessage class for additional information.

<!-- Ensure that the following line appears verbatim in the login page downloaded by the
     browser. As requests for authenticated content are redirected to this page if the
     authentication has expired, SmartClient watches for this line to detect if
     a relogin is necessary. -->
<SCRIPT>//'"]]>>isc_loginRequired
<isomorphic:loadDS ID="user"/>

<jsp:include page="loginDialog.js" flush="true" />

isc.RPCManager.createLoginDialog();

</SCRIPT>
</BODY>
</HTML>