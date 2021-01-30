/*
    Isomorphic SmartClient secure application DMI layer
    
    This class demonstrates DMI methods to develop SmartClient applications with
    authentication. The form-based authentication interface, located at
    isomorphic/login/iscAuth/*, relies on the server redirecting the client to various
    pages after login. The use of a DMI interface allows SmartClient applications
    fewer page transitions, greater flexibility, and a more seamless user interface.
    
    This authentication approach requires IDACall to be protected with AuthenticationFilter.
    To allow some IDACall requests to be authenticated but not others, it is suggested
    that a second IDACall mapping is created within the authenticated part of the site.
    See /WEB-INF/web.xml for more details.
*/

package com.isomorphic.examples;

import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

import com.isomorphic.util.*;
import com.isomorphic.auth.*;
import com.isomorphic.servlet.*;

public class SecureAppDMI {
	public SecureAppDMI() { }
    
    /* Call this method to attempt to log in to the system. See /examples/secureApp/login.jsp
       for example usage.
       
       By the time this is called, AuthenticationFilter has already run and accepted/rejected
       the credentials provided by the user. All that is left to do is to check its status
       and return useful information if successful.
   */
    public Map login(RequestContext context) throws Exception {
        if (Authentication.isAuthenticated(context)) return getUserInfo(context);
        return null;
    }
    
    /* Fetch information about the currently logged-in user. Only the user ID, user name,
       and user profile are returned. The password is stripped. */
    public Map getUserInfo(RequestContext context) throws Exception {
        Map user = (Map)Authentication.getUser(context);
        if (user == null) return null;

        // strip out the password
        List props = DataTools.buildList("id", "username", "profile");
        user = DataTools.subsetMap(user, props);
        
        return user;
    }
    /* Logs the user out (clears all authentication info). */
    public void logout(RequestContext context) throws Exception {
        Authentication.clearAuthInfo(context);
    }
}
