
// Inside application code //

window.onbeforeunload = function() {
   return l("Закрыть телефон?");
   //if we return nothing here (just calling return;) then there will be no pop-up question at all
   return;
};


String.prototype.trunc = 
      function(n){
          return this.substr(0,n-1)+(this.length>n?'&hellip;':'');
      };

// StartUp
$(document).ready(function(){ 
  if( webphone_enable ){
    registerua();   
  }else{
     console.log('WEB Phone Disabled!!!');
  }
   $('#content-area').load('cdrs');
  
   $('.tooltip').tooltipster({ 
     contentCloning: true
   });

 });

function logout(){
  $.post('rtcSwitch/' + my_name + '/sip');
  $.get('logout', function(){ location.reload();}); 
  return false;
}


$(document).on('click', '.play-rec', function(){
   player = $("div.cdr-player audio").attr('src', 'play/'+$(this).attr('ref') )[0];
   $(player).css("visibility", "visible");
   $(player).attr('preload','auto');
   console.log('Play it...');
   player.play();
});



 function hangupChan(chan){
     $.post('CallHangup', chan )
 }

// Translation Function uses inDictionary array, previously loaded from crm.dictionary.js  //
function l( the_text ){  
  // doTranslate is set in index.php  by php ( GUI language taked from from config ini )
  if(!doTranslate)
      return the_text;  
   
  if( ! inDictionary[ the_text ] ){   
    $.ajaxSetup({async: false});  
     $.post('translate', {'translateText': the_text}, 
        function(result){
          inDictionary[ the_text ] =  result ;          
       ///   console.log("  Translated LIVE: '" + the_text + "' -> '" + result +'"');        
      });
     $.ajaxSetup({async: true});  
     
  }else{
    // console.log('  Translate CACHED:[ ' + the_text + " -> " + inDictionary[ the_text ]  + ' ]');
  }     

  return  ( inDictionary[ the_text ] ==  null) ? the_text : inDictionary[ the_text ] ;
}



function callNotify( msg ) {

  var options = {
           body: "Notification",
           icon:  "assets/images/incoming.png",
           badge: "assets/images/incoming-xs.png",
           body:  msg,
           requireInteraction: true,
           dir : "ltr"  
           //actions: [ fire ]
        };
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert(l("This browser does not support desktop notification"));
  }
  // Let's check whether notification permissions have already been granted
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
    var notification = new Notification( msg, options );
        notification.onclick = function (a) {
          console.log('Notifxation clicked!');          
          console.log(a+" this:"+this );
          window.focus();
          //window.open("https://manager.company.com/");
          ///Maximize();
        };
  }

  // Otherwise, we need to ask the user for permission
  else if (Notification.permission !== "denied") {
    Notification.requestPermission().then(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {        
        var notification = new Notification( msg, options );
        notification.onclick = function (a) {                  
          window.focus();
          console.log(a+"this:"+this );
          //window.open("https://manager.company.com/");
          ///Maximize();
        };
      }
    });
  }

  // At last, if the user has denied notifications, and you 
  // want to be respectful there is no need to bother them any more.
}


$(document).keyup(function(e) {
  //if (e.keyCode === 13) $('.save').click();     // enter
  if (e.keyCode === 13){
    if( document.getElementById('phonenumbertxt').value  && $('#phonenumbertxt').is(":focus") ){
       console.log( ' Call:' + document.getElementById('phonenumbertxt').value  ) ;
       call();
    }
  }
  if (e.keyCode === 27){
  	console.log("Esc pressed..");
  	$('#wrapper').toggleClass('right-bar-enabled');
  } 
});






$('.phone').on("click", function(e){
  console.log("Calling to " + $(this).attr('sip') ) ;
  call($(this).attr('sip'));
});





$('.navigation').on("click", function(e){	
	console.log('Navigate to: ' + $(this).attr('navigate-to') ) ;
	$('#content-area').load( $(this).attr('navigate-to') );
	return false;
})



$('#save_realoperator').on('click', function(e){
	$.post('save',$(this).serialize(),function(data){
        console.log(data);
	});
})

