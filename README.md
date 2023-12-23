# MPBX #
Multi-tenant hosted PBX based on asterisk.
Single server for 100+ undepnded offices PBX.
########




# <h2>[ This PROJECT HAS BEEN ARCHIVED !]</h2> #


UPDATES NO longer available on this Public repo.
Code have been re-imported into the private projects. 



Some description about this project functions:
=========================================================================================
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
- Email SMTP account to delivery Reports/Notifications/Alerts

All clients/offices share the same server and the same asterisk instance and managemed in one place.
With a multitenant architecture, a software application is designed to virtually partition its data and configuration, and each client works with a customized virtual application instance. Using the same asterisk instance for all the clients is a real cost and resource saving solution.

Demo screencast of main PBX functions demonstration : <br>
[<img src="http://a4business.com/wp-content/uploads/2021/02/Selection_200.png" width="80%">](https://vimeo.com/516227435)
<br>
The video demonstrates following actions:

- Create PBX Cloud
- Create Trunks
- Add Inbound Numbers ( DID )
- Create extensions
- Create IVR menu, Queues
- Create Inbound Routes
- Create Outbuond Routing
- Assigne Context Script to get external Info by json API
- Receive INBOUND call via Queue from CRM WEB phone, check recording
- Call inbound GSM line to reach the virtual office and support queue .
- Answer the call 
- Call Forward using DTMF digits: , Call Parking/Unparking
Pressed #77 to park the call, got played 703 . this is position where call has been parked
to pickup, we just dial 703 now. ... connected with customer now again /
To forward call to another location, we use DTMF:
**1 - unattendant transfer
**0 - attanded transfer
- Reporting module , Call History



