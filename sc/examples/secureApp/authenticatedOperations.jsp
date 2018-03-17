<%@ page import="java.util.*" %>
<%@ page import="com.isomorphic.auth.*" %>
<%@ page import="com.isomorphic.rpc.*" %>
<%@ page import="com.isomorphic.datasource.*" %>
<%@ page import="com.isomorphic.sql.*" %>
<%@ page import="com.isomorphic.util.*" %>
<%@ page import="com.isomorphic.examples.*" %>

<%

RPCManager rpc;
try {
    rpc = new RPCManager(request, response, out);
    rpc.setAuthenticated(true);
    rpc.setUserId(Authentication.getUsername(rpc.getContext()));
    Map user = (Map) Authentication.getUser(rpc.getContext());
    if (user != null && user.get("id") != null) {
        DataSource rolesDS = rpc.getDataSource("roles");
        List rolesList = rolesDS.select("id", user.get("id"));
        String rolesString="";
        if (rolesList == null) rpc.setUserRoles("");
        else {
            for (Iterator i=rolesList.iterator(); i.hasNext();) {
                Map r = (Map) i.next();
                rolesString += r.get("role") + ",";
            }
            if (rolesString.length()>0) 
                rolesString = rolesString.substring(0, rolesString.length()-1);
            rpc.setUserRoles(rolesString);
        }
    }
} catch (ClientMustResubmitException e) { 
    return; 
}

for(Iterator i = rpc.getRequests().iterator(); i.hasNext();) {
    // To be completely safe, check what kind of request we received.  As the developer
    // you have complete control over which requests go where, but if you have a single
    // request dispatcher for RPCRequests and DSRequests, you'll need this check.
    Object req = i.next();
    if(req instanceof RPCRequest) 
         throw new Exception("This example expects only DSRequest");
    
    DSRequest dsRequest = (DSRequest)req;
    DSResponse res = new DSResponse();
    try {
        res = dsRequest.execute();
    } catch (java.lang.SecurityException e) {
        res.setStatus(DSResponse.STATUS_LOGIN_REQUIRED);
    }
    rpc.send(dsRequest, res);
}


%>