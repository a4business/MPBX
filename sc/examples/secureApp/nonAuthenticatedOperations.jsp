<%@ page import="java.util.*" %>
<%@ page import="com.isomorphic.rpc.*" %>
<%@ page import="com.isomorphic.datasource.*" %>
<%@ page import="com.isomorphic.sql.*" %>
<%@ page import="com.isomorphic.log.*" %>
<%@ page import="com.isomorphic.util.*" %>
<%@ page import="com.isomorphic.examples.*" %>

<%

// Instantiate an RPCManager so we can get the DSRequests.
//
RPCManager rpc;
try {
    rpc = new RPCManager(request, response, out);
} catch (ClientMustResubmitException e) { 
    return; 
}

DataSource userDS;

try {
    userDS = rpc.getDataSource("user");
} catch (Exception e) {
    throw new Exception("Could not load user ds");
}

for (Iterator i = rpc.getRequests().iterator(); i.hasNext();) {
    // To be completely safe, check what kind of request we received.  As the developer
    // you have complete control over which requests go where, but if you have a single
    // request dispatcher for RPCRequests and DSRequests, you'll need this check.
    //
    Object req = i.next();
    if (req instanceof RPCRequest) {
        throw new Exception("This example expects only DSRequests");
    }
     
    DSRequest dsRequest = (DSRequest)req;

    // if performing a reset password op, the user supplies a username, but the
    // update op requires a pk (id). Fill it in.
    if (dsRequest.getOperationId().equals("resetPassword") && 
        dsRequest.getDataSourceName().equals("user")) 
    {
    
        Map criteria = dsRequest.getCriteria();
        if (criteria.get("id") == null) {
            Map values = dsRequest.getValues();
            Map userRecord = userDS.fetchSingle("username", values.get("username"));
            if (userRecord != null && userRecord.get("id") != null) {
                criteria.put("id", userRecord.get("id"));
                dsRequest.setCriteria(criteria);
            }
        }
    }
    DSResponse resp = dsRequest.execute();
    // We have to include the newly-generated password in the outputs for the "resetPassword"
    // operation, because the mail subsystem needs it.  However, actually sending that value
    // back to the client is a security risk because it means that anybody can reset a user's 
    // password and simply inspect the response sent back to the client to see the new 
    // password (which should only be available to the person in control of the user's 
    // designated email address).  So strip that value out now.
    Map data = resp.getDataMap();
    if (data != null) {
        data.remove("password");
    }
    rpc.send(dsRequest, resp);
}


%>