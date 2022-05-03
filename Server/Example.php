<?php

  include 'SafeTransmission.php';
  define('ENC_KEY', 'your encryption key here');
  $sr = new SafeRequest(ENC_KEY);

  // access decrypted post data
  $sr->result(true, $_POST['some_key']);

  // return normal encrypted strings
  $sr->result(true, 'your message here');

?>