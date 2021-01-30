 $('#loginForm').on('submit', function(e){
                
               console.log( "Request Manager ....");

               //doAjaxCall();
            
                console.log( $(this).serialize() );
                $.post('login', 
                       //{ "login": $('#login').val(), "password" : $('#passwd').val() } ,
                       $(this).serialize(),
                        function(data){                        
                         data = $.parseJSON( data );
                         if(data.error){
                            console.log('Error: ' + data.message);
                            $('#alert').html(data.message).fadeTo('fast',1).css('visibility','visible').fadeIn("slow");
                            setTimeout(function(){ $('#alert').html('&nbsp;').fadeTo('slow',0);},3000);
                         }else
                          location.reload();                     
                });
            

                return false;
   });

    $('.language-select').click(function(){
       $(this).toggleClass('open');
    })


// Switch Language to antoher //
    $('.language-select  li').click(function(){

        if( ! $(this).hasClass('active') ){
          var setLang = $('.language-select').data('lang');
          dataLangSelect = $(this).data('lang');
          $('.language-select').data('lang', dataLangSelect);
          $('.language-select li').removeClass('active');          
          $(this).toggleClass('active');
          console.log( 'Switching to ...' +  dataLangSelect + ' lang');
          $.post('switchLang/'+dataLangSelect,  
                  function(){
                    location.reload();
                  }
           );
        }      
        
    });







