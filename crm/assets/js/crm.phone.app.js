



var session;
var userAgent;

var serverip ;
var wuser ;
var wpass ;

var isRegistered = "0";
var connected = "0";
var dialing = "0";
var concount = "0";
var incomingcall = false;
var incomingfrom = "";

var onhold=false;
var onmute=false;
var regcount=0;
///var regclick = true;
var autoreg=true;

var talkBtns = [ "mutebutton", "xferbtn", "attxferbtn", "confbtn", "holdbutton" ];

setInterval(function(){

    if(dialing != 1)	
      document.getElementById('outbound_audio').pause();	    	 

    if (connected=="1"){  
        dialing = 0;      
        var date = new Date(null);
        date.setSeconds(concount);
  
        document.getElementById('incoming_audio').pause();	      	  
        document.getElementById('outbound_audio').pause();	    

        var myDate = date.toISOString().substr(11, 8);
        if( typeof(incomingfrom) !== "undefined" && incomingfrom != '' )
           party =  (typeof(clid) !== "undefined" ) ? clid :  incomingfrom;
        else	
	   party = document.getElementById('phonenumbertxt').value;

        document.getElementById('status').innerHTML =  l("Разговор с") + ":<br>" + party + "<br>" + myDate ;
        document.getElementById('status').classList.add('activeCall');
        for (var i = 0; i < talkBtns.length; i++) 
          if( document.getElementById( talkBtns[i] ).classList.contains('invisible') )
              document.getElementById( talkBtns[i] ).classList.remove('invisible');

        concount++;

    }else{
		document.getElementById('status').classList.remove('activeCall');
	   for (var i = 0; i < talkBtns.length; i++) 
	      if( !document.getElementById( talkBtns[i] ).classList.contains('invisible') )
	         document.getElementById( talkBtns[i] ).classList.add('invisible');

	}

}, 1000);



window.addEventListener('load', function() {
 // registerua();

})



function echo_clicked(){
	call('*600');
}



function regclicked(){
	user = document.getElementById('sipName').value;

	if( isRegistered != "1" || typeof userAgent === "undefined" ){
		console.log( user +' Register:' + isRegistered);
		registerua();
		$.post('rtcSwitch/' + user + '/rtc');
	}else{		
        console.log( user +' UnRegister:' + isRegistered); 
		userAgent.unregister( { 'all': true } );
		$.post('rtcSwitch/' + user + '/sip');
	}
	
}

function registerua(){
	
		wuser = document.getElementById('sipName').value;
		wpass = document.getElementById('sippw').value;

		if(serverip==""){
			// Dev server //
			serverip = "app.a4business.com";	
		}

		
		if(!wpass || !wuser ){
			document.getElementById('uastatus').innerHTML = "<i class='mdi mdi-lan-disconnect'>&nbsp;&nbsp; "+l("Нет SIP Телефона")+"!</i> ";
			return;
		}
		
		document.getElementById('exten').innerHTML =  wuser.substring(wuser.indexOf("-") + 1);
		  
		userAgent = new SIP.UA({
			  uri: wuser +'@'+serverip,
			  authorizationUser: wuser,
			  password: wpass ,			  
			  transportOptions: {
			      wsServers: [ 'wss://' + serverip + ':8443/ws' ],
			      maxReconnectionAttempts: 999,
			      reconnectionTimeout: 3,
			      keepAliveInterval: 5,
			      traceSip: false
			  },    
			  userAgentString: 'Cloud CRM ',			
			  register: true,
			  expires: 15,
			  rtcpMuxPolicy: 'yes',
			  hackIpInContact: true,				  
			  hackWssInTransport: true,			  
			  //stunServers: ["stun:turn.redleaftelecom.com:19302"],
		          //stunServers: [ "stun:38.113.171.241:19302" ],
			  turnServers: [ { urls:"stun:" + serverip + ":19302" },
				  	 { urls: "turn:" + serverip + ":19302?transport=udp",
					 	 username: "turn",
					  	 password: "secured",
					  	 credential: "secured"
			  }],
			  
			  // Acoording to :  https://groups.google.com/forum/#!topic/sip_js/VYMi4UMbxvM
			  sessionDescriptionHandlerFactoryOptions: {
			    peerConnectionOptions: {
		              iceCheckingTimeout:2000,
			          rtcConfiguration:{
				         iceTransportPolicy: "relay",
			             iceServers:
					        [
					          { urls:"stun:" + serverip + ":19302" },
					          {
					            urls: "turn:38.113.171.247:19302?transport=udp",
						        username: "turn",
						        credential: "secured"
					            
					          }
					        ]
			      }
			    }
			  }
           
		});

		//sessionDescriptionHandlerFactoryOptions: {"constraints":{},"peerConnectionOptions":{}}

		regToggleBtn = "</i> <a href='#' style='float:right'  title='"+l('Отключить телефон')+"' onclick='regclicked();return false;'><i id='registerbutton' class='mdi mdi-power'></i></a>";
		echoTestBTN = "<i id='echo' onclick='echo_clicked();' title='"+l('Эхо тест')+"' class='mdi mdi-surround-sound'>";
		userAgent.on('registered', function () {
			isRegistered = 1;
			document.getElementById('uastatus').innerHTML = "<i class='mdi mdi-lan-connect'>&nbsp;&nbsp;" +l("Онлайн")+ "</i><i id='missed' class='blink hidden badge badge-danger m-l-5'><i class='mdi mdi-phone-missed'></i></i>" + regToggleBtn + echoTestBTN;
            document.getElementById("uastatus").classList.remove('alert-danger');
            document.getElementById("uastatus").classList.add('alert-info');
            document.getElementById("user-status").classList.add('online');
            document.getElementById("user-status").classList.remove('offline');
			//document.getElementById('registerbutton').innerHTML="Unregister";
		});
		userAgent.on('unregistered', function (response, cause) {
			isRegistered = 0;
			document.getElementById('uastatus').innerHTML = "<i class='mdi mdi-lan-disconnect'>&nbsp;&nbsp;" +l("Отключен")+ " </i> " + regToggleBtn;
			document.getElementById("uastatus").classList.remove('alert-info');
            document.getElementById("uastatus").classList.add('alert-danger');
            document.getElementById("user-status").classList.remove('online');
            document.getElementById("user-status").classList.add('offline');
			//document.getElementById('registerbutton').innerHTML="<i class='mdi mdi-power'></i>";
		});


		userAgent.on('invite', function (insession) {

         if ( connected=="1" || dialing=="1" ){
              document.getElementById("missed").classList.remove('hidden');	
         	  console.log("  REJECTING Incoming CALL FROM["+ insession.request.getHeader('Contact') +"] WHEN CONNECTED or DIALING status is on!");
              insession.reject();
              
            return;
         }
         
			session = insession;
			caller_name = ( typeof session.remoteIdentity.displayName == 'undefined')? '' :session.remoteIdentity.displayName;
			caller_user = ( typeof session.remoteIdentity.uri.user == 'undefined')? '' : session.remoteIdentity.uri.user;
			_link =  session.request.getHeader('X-CRM-Link') || '';
			_contact = session.request.getHeader('Contact') || '';

			clid ='';
			if( _contact != '' )
			  _contact  = _contact.match("sip:(.*)@")[1];
			
            
			if( _link.trim() != '' ){
                         clid = "<i class='mdi mdi-24px mdi-account-card-details blink title='"+ clid + "'></i>  " + _link  + '<br>' + _contact;
			}else{
			  clid = _contact + ' ' + caller_name + ' ' + caller_user ;
			}
            
              
            document.getElementById('status').innerHTML = "<i class=\"mdi mdi-phone\"></i> " +l("Звонок:") + '<br>' + clid; 
            document.getElementById('status').classList.add('incomingCall');
            if( _contact.match('href="(.*)"') ) {
              document.querySelector('#status a').setAttribute("target", "_blank");
            }  

			incomingcall = true;
            
			
			document.getElementById('callbutton').innerHTML= "<i class=\"mdi mdi-phone\"></i>";			
			document.getElementById('hangupbutton').innerHTML="<i class=\"mdi mdi-phone-hangup\"></i>";

			var remoteAudio = document.getElementById('remoteAudio');
			var localAudio = document.getElementById('localAudio');

			// We answer Click2Call silantly calls atonce allow to connect another side /
             if( session.remoteIdentity.displayName == 'c2c'){
             	call();
             }else{
             	document.getElementById('incoming_audio').currentTime = 0;
  	            document.getElementById('incoming_audio').play();
  	            var tmp = document.createElement("DIV");
                    tmp.innerHTML = clid;                
  	            callNotify( l("Входящий:") + ( tmp.textContent || tmp.innerText || "" ) );
             }


            session.on('ringing', function() {
            	console.log('Remote END is ringing...');
            })

            session.on('progress', function (response) {
				//if(incomingcall){
				  //document.getElementById('incoming_audio').play();
				//}
				call_type = ( incomingcall ) ? 'Incoming ':'Outgoing';
				console.log(" Progressing!!");
			});

			session.on('accepted', function (data) {
				connected = "1";
				dialing = 0;
				document.getElementById('outbound_audio').pause();
				document.getElementById('incoming_audio').pause();
				concount = "0";
				incomingcall = false;
				incomingfrom = clid;				
				
				//document.getElementById('callbutton').innerHTML="<i class=\"mdi mdi-phone\"></i> ";
				//document.getElementById('hangupbutton').innerHTML="<i class=\"mdi mdi-phone-hangup\"></i>";

			 	var pc = session.sessionDescriptionHandler.peerConnection;

		  		var remoteStream = new MediaStream();
		  		pc.getReceivers().forEach(function(receiver) {
		    		remoteStream.addTrack(receiver.track);
		  		});

		  		remoteAudio.srcObject = remoteStream;
		  		remoteAudio.play();


		  		var localStream = new MediaStream();
		  		pc.getSenders().forEach(function(sender) {
		    		localStream.addTrack(sender.track);
		  		});

		  		localAudio.srcObject = localStream;
		  		localAudio.play();

				
			});



			session.on('terminated', function(message, cause) {
				connected = "0";
				dialing = 0;
				concount = "0";
				incomingcall = false;
				document.getElementById('status').innerHTML ="";
				document.getElementById('status').classList.remove('incomingCall');
				document.getElementById('phonenumbertxt').value="";
				document.getElementById('mutebutton').innerHTML="<i class=\"fa fa-microphone\"></i>";
				document.getElementById('callbutton').innerHTML="<i class=\"mdi mdi-phone\"></i>";
		        document.getElementById('hangupbutton').innerHTML="<i class=\"mdi mdi-phone-hangup\"></i>";
		        document.getElementById('incoming_audio').pause();
		        document.getElementById('outbound_audio').pause();
		        console.log('Call Terminated!');

		        if( typeof notification !== 'undefined' )
		          notification.close();
			});

			session.on('failed', function (request) {
			  var cause = request.cause; //sometimes this is request.reason_phrase  https://sipjs.com/api/0.15.0/causes/
			  //if (cause === SIP.C.causes.REJECTED) {
			    console.log('CRMMMMMMMMMMMM: I am FAILED! with code:' + request )
			    document.getElementById('incoming_audio').pause();
		        document.getElementById('outbound_audio').pause();

			 // }
			});
			
			session.on('bye', function (request) {
		                connected = "0";
		                dialing = "0";
		                concount = "0";
		                document.getElementById('status').innerHTML ="";
		                document.getElementById('status').classList.remove('incomingCall');
		                document.getElementById('incoming_audio').pause();
		        });

			

			session.on('reinvite', function(session) {
				if (incomingcall){
					console.log("incoming call");
					document.getElementById('incoming_audio').play();
				}
				console.log("reinvite!!!!! LETR AVOID IT!!");
			
			});
						var remoteAudio = document.getElementById('remoteAudio');
						var localAudio = document.getElementById('localAudio');
				
		   });

}

function hangup(){

    if( isRegistered != "1" ){
    	alert("No active registration!");
    	return;
    }

  if( (typeof session != 'undefined') ){
	if (incomingcall){
		console.log('Reject Incoin  call');
		session.reject();		
		incomingcall=false;
		//session.terminate();
	}else{

		if( connected=="1" ){
			clid = '';
			session.bye();	
			session.terminate();    		
		}
		
		if( dialing=="1" )
			session.cancel();		  

		
		  //session.terminate();
		 // session.close();
		
			

		incomingcall=false;
	}
  }	
	document.getElementById('incoming_audio').pause();

}

function hold(){
	if( isRegistered != "1" ){
    	alert("No active registration!");
    	return;
     }
	if (connected=="1"){
		onhold =! onhold;
		var icon = onhold ? 'phone-paused' : 'pause';
		document.getElementById('holdbutton').innerHTML="<i class=\"mdi mdi-"+icon+"\"></i>";
		document.getElementById('holdbutton').classList.toggle('btn-warning');
		document.getElementById('holdbutton').classList.toggle('btn-primary');
		if(onhold){
			session.hold();			
        }else{
			session.unhold();			        
		}
	}else{
		alert("No call connected");
	}
	
}

function mute(){

	   if( isRegistered != "1" ){
    	alert("No active registration!");
    	return;
       }


        if (connected=="1"){
		pc1 = this.session.sessionDescriptionHandler.peerConnection;
		pc1.getLocalStreams().forEach(function (stream) {
    		stream.getAudioTracks().forEach(function (track) {

		if (track.enabled){
                        document.getElementById('mutebutton').innerHTML="<i class=\"fa fa-microphone-slash\">Unmute</i>";
                }else{
                        document.getElementById('mutebutton').innerHTML="<i class=\"fa fa-microphone\">Mute</i>";
                        }
        try {
            	track.enabled = !track.enabled;
        	} catch (e) {
            	//	toastr.error('Error occured in executing this command.');
           		 console.log(e);
        			}
    			});
		});

        }else{
                alert("No call connected");
        }
}

function transfer(){
	if( isRegistered != "1" ){
    	alert(l("Ваш телефон Оффлайн!"));
    	return;
    }
	
	if (connected=="1"){		
		var texten = prompt(l("Введите номер для переадресации звонка") , "");
		texten = texten + '@' + serverip;
		session.refer( texten );
	}else{
		alert(l("Нет активных звонков"));
	}	
}


function attXfer(){
	if( isRegistered != "1" ){
    	alert(l("Ваш телефон Оффлайн!"));
    	return;
    }
	
	if (connected=="1"){		
		var texten = prompt(l("Введите номер для условной переадресации текущего звонка") , "");
		//texten = texten + '@' + serverip;
	    session.dtmf('**2'+texten);
	}else{
		alert(l("Нет активных звонков"));
	}	

}

function call(to){
    
	var remoteAudio = document.getElementById('remoteAudio');
	var localAudio = document.getElementById('localAudio');

    //if( !userAgent.isRegistered() ){
    if( isRegistered != "1" ){
    	alert("No active registration!");
    	return;
    }
    if(dialing == "1" || connected=="1"){
    	console.log(l("Звонок еще не завершен") + dialing);
    	return;
    }

	if(incomingcall){
      	  session.accept(
		{
		sessionDescriptionHandlerOptions: {
    		constraints: {
      			audio: true,
      			video: false
			}}
		  	,
        	media: {
		constraints: {
			audio: true,
			video: false
			}
			,
			
            	render: {
                	remote: document.getElementById('remoteAudio'),
                	local: document.getElementById('localAudio')
            			}
        		}
    		}
	  );
	
	}else{
		var num = to?to.toString():document.getElementById('phonenumbertxt').value ;	
		num = num.replace(/[^0-9\*#]/g,'');
		if( !num.match("/^0/") && num.length == 9 ) 
		   num = '0' + num;
		document.getElementById('phonenumbertxt').value = num;
		console.log(' OUTBOUND Call to ' + to);
		if(!num){
		  alert("Phone is Empty!");
    	  return;
		}
        document.getElementById('status').innerHTML ="<i class='spinner-grow spinner-grow-sm'></i>" + l("Набираем номер") + ": " + num ;
		var options = {
  		sessionDescriptionHandlerOptions: {
	  	  rtcConfiguration:{
                                     iceTransportPolicy: "relay",
                                     iceServers:
                                                [
                                                  { urls:"stun:" + serverip + ":19302" },
                                                  {
                                                    urls: "turn:" + serverip + ":19302?transport=udp",
                                                        username: "turn",
                                                        credential: "secured"

                                                  }
                                                ]
                              },
                   iceCheckingTimeout: 1000,
    		constraints: {
      			audio: true,
      			video: false
  		}},
		media: {
				constraints: {
					audio: true,
					video: false
				},
				render: {
					remote: document.getElementById('remoteAudio'),
					local: document.getElementById('localAudio')
				}
			}
		};
		
		dialing = 1;  // Dialing.. 
        session = null;
		 
		  //session.bye();
		session = userAgent.invite('sip:'+num+'@'+serverip, options);
  	            
  	    document.getElementById('outbound_audio').currentTime = 0;
		document.getElementById('outbound_audio').play();	

		session.on('trackAdded', function() {

           try{
              
        	
			  var pc = session.sessionDescriptionHandler.peerConnection;
			  var remoteStream = new MediaStream();
			  pc.getReceivers().forEach(function(receiver) {
			    remoteStream.addTrack(receiver.track);
			  });
			  remoteAudio.srcObject = remoteStream;
			  remoteAudio.play();
			  var localStream = new MediaStream();
			  pc.getSenders().forEach(function(sender) {
			    localStream.addTrack(sender.track);
			  });
			  localAudio.srcObject = localStream;
			  localAudio.play();

		   } catch (e) {
           	 //	toastr.error('Error While Answering(trackAdd) ');
           	 console.log(' Error While Answering(trackAdd)ERR:' + e);
   		   }
    	   
		});

	

   session.on('progress', function (response) {
    // InviteClientContext#receiveInviteResponse
    console.log('GOT Progress status_code:' + response.statusCode );
    /*
    if (response.statusCode === 183 && response.body && this.hasOffer && !this.dialog) {
      if (!response.hasHeader('require') || response.getHeader('require').indexOf('100rel') === -1) {
        if (this.sessionDescriptionHandler.hasDescription(response.getHeader('Content-Type'))) {
          console.log(" Progressing 183..... Early Media");         
          // this ensures that 200 will not try to set description
         session.hasAnswer = true
          // @hack: https://github.com/onsip/SIP.js/issues/242
          //this.status = Session.C.STATUS_EARLY_MEDIA
          //this.mute()
		 document.getElementById('outbound_audio').pause();

         session.sessionDescriptionHandler.setDescription(response.body, session.sessionDescriptionHandlerOptions, session.modifiers)
            .catch((reason) => {
              this.logger.warn(reason)
              this.failed(response, C.causes.BAD_MEDIA_DESCRIPTION)
              this.terminate({ status_code: 488, reason_phrase: 'Bad Media Description' })
            })
        }
      }
    }
    */
  });

		session.on('accepted', function (data) {
			connected = "1";
			concount = "0";
			dialing = "0";
			document.getElementById('incoming_audio').pause();
		        document.getElementById('outbound_audio').pause();
			incomingcall=false;
			incomingfrom = num;
		});


		session.on('terminated', function(message, cause) {
			connected = "0";
			concount = "0";
			dialing = "0";
			document.getElementById('status').innerHTML ="";
			document.getElementById('status').classList.remove('incomingCall');
			document.getElementById('phonenumbertxt').value="";
		});
		session.on('bye', function(request) {
                	connected = "0";
                	concount = "0";
                	document.getElementById('status').innerHTML =l("Звонок окончен");
                	document.getElementById('phonenumbertxt').value="";
        	});

	}
}

function adddigit(digit){
	
	if(connected=="1"){
			document.getElementById('dtmf_audio').play();
			document.getElementById('phonenumbertxt').value = document.getElementById('phonenumbertxt').value + digit.value;
			session.dtmf(digit.value);
			console.log(digit.value);
			
	}else{
		document.getElementById('dtmf_audio').play();
		document.getElementById('phonenumbertxt').value = document.getElementById('phonenumbertxt').value + digit.value;
	}
}

