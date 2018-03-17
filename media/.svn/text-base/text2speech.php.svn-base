<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<style>
img{
 vertical-align: baseline;
}
.centered {
  position: fixed; /* or absolute */
  top: 50%;
  left: 50%;
  margin-top: -250px;
  margin-left: -250px;
  text-align:center;
}
 h2 {
 font-size: 1.5em;
 margin: 1em 0 .5em;
 font-weight:bold;
 font-family:verdana;
 }
#explain{
 background-color: #efefef;
 position: relative;
 box-sizing: border-box;
 width:480px;
 text-align:left;
 padding:14px;
}

</style>
<script>
 function play_text() {
         var f = document.getElementById("tts").value;
         var l = document.getElementById("text_lang").value;
         if ( ! f ) return false;
         document.getElementById("player").style.visibility = 'visible';
         document.getElementById("player").src="play.php?play_text=" + f + "&lang=" + l;
         document.getElementById("wav_file").href="play.php?download_format=wav&play_text=" + f + "&lang=" + l;
         document.getElementById("wav_file").style.visibility = 'visible';
         document.getElementById("player").play();
   }

</script>

</head>
<body>
  
  <div class=centered > 
   <audio style='padding:20px 0 0 20px;display:inline;visibility:hidden;' id='player' style='visibility:hidden;margin:auto;'  controls></audio>
   <a style='display:inline;visibility:hidden;' href='' border=0  id=wav_file  >  <img src="wav2.png" >  </a>
   <br><br>
   <textarea class="form-control" id=tts rows=4 cols=50 ></textarea><br><small style='color:gray'> Note: Please use commas and full stops for sentence breaks. <br>Type Telephone Numbers with spaces between the numbers. Example: 3 0 5 6 7 8 1 1 7 4</small><br><br>
   <select class="form-control" id="text_lang" style="float:left;width:160px"> <option value="en-US"> Default </option><option value="de-DE_BirgitVoice"> de-DE_BirgitVoice - German,Female </option><option value="de-DE_DieterVoice"> de-DE_DieterVoice - German,Male </option><option value="en-GB_KateVoice"> en-GB_KateVoice - English(British dialect),Female </option><option value="en-US_AllisonVoice"> en-US_AllisonVoice - English (US dialect),Female </option><option value="en-US_LisaVoice" selected=""> en-US_LisaVoice - English (US dialect),Female </option><option value="en-US_MichaelVoice"> en-US_MichaelVoice - English (US dialect) (Default),Male </option><option value="es-ES_EnriqueVoice"> es-ES_EnriqueVoice - Spanish (Castilian dialect),Male </option><option value="es-ES_LauraVoice"> es-ES_LauraVoice - Spanish (Castilian dialect),Female </option><option value="es-LA_SofiaVoice"> es-LA_SofiaVoice - Spanish (Latin American dialect),Female </option><option value="es-US_SofiaVoice"> es-US_SofiaVoice - Spanish (North American dialect),Female </option><option value="fr-FR_ReneeVoice"> fr-FR_ReneeVoice - French,Female </option><option value="it-IT_FrancescaVoice"> it-IT_FrancescaVoice - Italian,Female </option><option value="ja-JP_EmiVoice"> ja-JP_EmiVoice - Japanese ,Female </option><option value="pt-BR_IsabelaVoice"> pt-BR_IsabelaVoice - Brazilian Portuguese,Female </option> </select>
   <button type="button" style='float:right;' class="btn btn-success" onclick="play_text();">Play</button>
 </div>
<?php


?>

</body>
</html>
