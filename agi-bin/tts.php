#!/usr/bin/php -q
<?php

 set_time_limit(60);
 ob_implicit_flush(false);  //  turns off output buffering
 error_reporting(0);

  require_once('lib/functions-agi.php');

  $call = new AGIInterface('/var/lib/asterisk/agi-bin/config.ini');
  $call->log("Playing.. LANG: {$argv[1]} Text: {$argv[2]} ");
  $call->tts($argv[2], $argv[1], 0, 'auto');


?>

