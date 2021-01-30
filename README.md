# MPBX
Multi-tenant hosted PBX based on asterisk.  
One server for 100+  offices PBX.

More details at http://a4business.com/?p=1070 
 
 

<center>
<img src='http://a4business.com/wp-content/uploads/2018/08/how-hosted-pbx-works.png'>
<br>

 <img src='http://a4business.com/wp-content/uploads/2018/08/Selection_346.png'>
 <br>
  <img src='http://a4business.com/wp-content/uploads/2018/08/Selection_344.png'>
</center>


Each client (or separate office, tenant) has a dedicated, isolated cloud PBX , with separate :
- exntensions (with PBX functions — followme, Forward, Voice/Video Mail, Blacklists etc..)
- VoiceMail boxes,
- Call parkign spaces, Park&Announce
- Auto-attendants/Virtual Offices,
- Page/Ring Groups,
- Queues,
- multiple Music-on-Hold classes on separate storage,
- Conferences
- Inbound/Outbound routing logic

All clients/offices share the same server and the same asterisk instance and managemed in one place.
With a multitenant architecture, a software application is designed to virtually partition its data and configuration, and each client works with a customized virtual application instance. Using the same asterisk instance for all the clients is a real cost and resource saving solution.
Some more key features:
- supports custom scripting, i.e. assigning a dialplan contexts to Feture codes, which connects extensions with unlimited functionality of the asterisk dialplan.
- Call recording (full or by one-touch on demand recording while conversation).

Uppong receiving call, manager can activate inbound route with actions:


[Demo VIdeo](http://a4business.com/wp-content/uploads/2018/08/PBXDemo-en.ogv?_=1)
The video demonstrates following steps:

- Create PBX Cloud
- Create Trunks
- Add Inbound Numbers ( DID )
- Create extensions
- Create IVR menu, Queues
- Create Inbound Routes
- Create Outbuond Routing
- Assigne Context Script to get external Info by json API
- Receive INBOUND call via Queue from CRM WEB phone, check recording
Now I am dialing inbound GSM line to reach the virtual office and support queue .
answering...
- Call Forward using DTMF digits: , Call Parking/Unparking
Pressed #77 to park the call, got played 703 . this is position where call has been parked
to pickup, we just dial 703 now. ... connected with customer now again /
To forward call to another location, we use DTMF:
**1 - unattendant transfer
**0 - attanded transfer
- Reporting module , Call History



