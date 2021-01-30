<?php


// Wrapper short hand  function 
 function _l( $text, $return_text=false, $from = 'ru'  ){    
     global $Translator;
     global $PBX;

     if( isset($_POST['translateText']) )
     	  $text =  $_POST['translateText'];
     
     $to = $PBX->ini['general']['default_lang'] ;

     //$lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : $PBX->ini['eneral']['default_lang'];          
     if( !isset($Translator[$from]) ){
      $Translator = array();
     	$Translator[$from] = new SiteTranslator( $from, $to );  // if $from and $to qre equal - we just display //
     } 

   if( $return_text )
	   return  $Translator[$from]->out( $text ,  $return_text );
   else
     echo $Translator[$from]->out( $text ,  $return_text );

 }


// This function generates JS code output to be used on GUI scripts translation by l(); js function
function getDictionary(){

	 global $PBX;
      $default_gui_lang = 'ru';   // from 
      $to = $PBX->ini['general']['default_lang'];  // to

	 if( !isset($Translator[$to]) )
     	$Translator[$to] = new SiteTranslator( $default_gui_lang, $to );  // if $from and $to qre equal - we just display //
    header('Content-Type: application/javascript; charset=utf-8');

     echo "\n// Dictionary Generated from DB for GUI \n";
     echo 'inDictionary = {};'."\n";
     if( !$Translator[$to]->do_translate )
       echo "// Translation model: " . '[ ru(gui default) -> '. $PBX->ini['general']['default_lang'] . " ] Output as is, no translate";
     else
      foreach ($Translator[$to]->translate as $key => $value) 
     	 echo  "\tinDictionary['" . $key . "'] ='" . $value . "'\n" ;
     

}

 Class SiteTranslator {

 	 public $translate ;
         public $do_translate;
 	 public $lang_from;
 	 public $lang_to;

 	// Default language WE display IS , without translations , 
 	// if we have $translate_to  option is set - we search for translation FROM default_language to  $translate_to

 	public function __construct( $default_language, $translate_to ){
 	  GLOBAL $PBX;
 	    $this->do_translate = ( $default_language != $translate_to );
 	    $this->lang_from = $default_language ? $default_language : 'ru';
 	    $this->lang_to   = $translate_to ? $translate_to : 'en';
      	    $this->model =  $this->lang_from . '-' . $this->lang_to;  // 'ru-en'
 	    $this->translate = array();

 	    // Load DICTIONARY //
 	    if($this->do_translate ) {  	         
 	    	   $PBX->log( "   Initialization SiteTranslator FROM Mysql:translations Model:" . $this->model );
 	         $rows = getDatabase()->all( "SELECT * FROM translations WHERE ru != '' OR en != '' or ua != ''  " );  // 
 	         foreach($rows as $row)
 	           $this->translate[ $row[ $default_language ] ] =  $row[ $translate_to ] ;
      	 }

 	 }



    public function out( $text ){
  		GLOBAL $PBX;
  		if( !trim($text) )
  			return;
  		
  		if( !$this->do_translate ){    
  		   return $text;
  		}


  		$text = trim( preg_replace( array('/:/','/_/','/!/','/|/'),'',$text) ); 

        if( !isset($this->translate[ $text ]) || trim($this->translate[ $text ]) === '' ){
          if( !getDatabase()->one("SELECT * FROM  translations  WHERE `{$this->lang_from}` = trim('{$text}') ") )
               $id = getDatabase()->execute("INSERT INTO translations( `{$this->lang_from}`, `{$this->lang_to}` )
                                             VALUES( trim('{$text}'), '' )");
            // // Try to get translation !                      
             // curl --user apikey:iskCM_MNQbccRmBFwqbl5oqQtf1x9P5gQNwoem7zZbPJ --request POST --header "Content-Type: application/json" --data "{\"text\":[\"Hello dear friend\"],\"model_id\":\"en-ru\"}" "https://api.eu-gb.language-translator.watson.cloud.ibm.com/v3/translate?version=2018-05-01"

           if( isset( $PBX->ini['IBM']['url'] ) ){
       	           $url = $PBX->ini['IBM']['url'];
                  $url = $url?$url:"https://api.eu-gb.language-translator.watson.cloud.ibm.com/v3/translate?version=2018-05-01";
                  $key = $PBX->ini['IBM']['apikey'];
                  $key = $key?$key:'iskCM_MNQbccRmBFwqbl5oqQtf1x9P5gQNwoem7zZbPJ';
			
				          
		              $post = array( 
             	              'text' => $text, 
                            'model_id' => $this->model
		                   );

		           $curl = curl_init();
      					curl_setopt($curl, CURLOPT_URL, $url );
      					curl_setopt($curl, CURLOPT_USERPWD, "apikey:{$key}" );
      					curl_setopt($curl, CURLOPT_POST,  true);
      					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post) );			
      					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 			
      					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
      					$result = curl_exec($curl);
      					$RET = json_decode( $result , true );
		            if( isset( $RET['translations'][0]['translation'] ) ){

		               $this->translate[ $text ] = preg_replace("/'/","",$RET['translations'][0]['translation']);		
		               $set = getDatabase()->execute("UPDATE translations SET `{$this->lang_to}` = '{$this->translate[ $text ]}'
		            	                        WHERE `{$this->lang_from}` = '{$text}' "); 
		               $PBX->log( "  Translated LIVE[{$text}]->{$lang}[" . $this->translate[ $text ] .']'.($set?'  +CACHED':' NOT CACHED!!!') );	

		            }else{
		               $PBX->log( "   IBM  LIVE Translate FAILED!::>  {$lang} : {$text} : " . $result);            	
			          }
			
		   	  }elseif( isset($PBX->ini['yandex-translate']['url']) ){
            //curl -sd 'lang=ru-tr&text=привет' https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20190308T095934Z.95ae5cf4e28588ea.9d108fb6e768af347464925e4e98b91edb0013f5   
            $url = $PBX->ini['yandex-translate']['url'];
            $key = $PBX->ini['yandex-translate']['apikey'];
             /*     
               $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url.'?key='.$key );                
                curl_setopt($curl, CURLOPT_POST,  true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, "lang={$this->model}&text={$text}" );      
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);       
           //     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
                $result = curl_exec($curl);
		$RET = json_decode( $result , true );
            */		
                if( isset( $RET['text'][0]) && $RET['code'] == 200 ){

                   $this->translate[ $text ] = preg_replace("/'/", "", $RET['text'][0]);   
                   $set = getDatabase()->execute("UPDATE translations SET `{$this->lang_to}` = '{$this->translate[ $text ]}'
                                          WHERE `{$this->lang_from}` = '{$text}' "); 
                   $PBX->log( "  Translated LIVE[{$text}]->{$lang}[" . $this->translate[ $text ] .']'.($set?'  +CACHED':' NOT CACHED!!!') );  
                   
		// We exit after each translation , making refresh page - we translate each word  one by one to avoid blocking from IBM?
                }else{
                   $PBX->log( " Yandex LIVE Translate FAILED!::>  model: {$this->model} : [{$text}] : result:" . $result);             
                   
                }


          }else{  
           $PBX->log("       WARN: [{$text}]: NO API credentials for translation! " ); 
		 	    }	
		 	//exit;

          }else{
          	$PBX->log( " Translation :CACHED: [model:{$this->lang_from}-{$this->lang_to}] '{$text}' --> {$this->translate[ $text ]} ");
          }	


          if( isset($this->translate[ $text ]) && trim($this->translate[ $text ]) != '' )
             return  $this->translate[ $text ];
          else
             return $text;

          
          
         
    } // End of function out()

}  // End fo Class


?>
