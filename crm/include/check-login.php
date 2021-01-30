<?php

  if( !isset($_SESSION['CRM_user']) ){
    session_start();
  }
 
  if( !isset($_SESSION['CRM_user']) || !$_SESSION['CRM_user'] )
  {
     header('Location: welcome');
     return;
     exit;
  }

?>