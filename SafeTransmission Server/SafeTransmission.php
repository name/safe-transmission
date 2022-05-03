<?php

  /*
    SafeTransmission:
      Protect transmissions of data over HTTP by sending encrypted json data between client and server.
      Author: Charlie Maddex
      https://github.com/name/SafeTransmission
      
  */

class SafeTransmission {
      
  var $auth;
  var $enc;
  
  function __construct($key, $iv = null) {
    $this->enc = new Encryption($key);
    if ($iv !== null)
      if (count($iv) == 16)
        $this->enc->SetIV($iv);
    $_POST = $this->getPOST();
    $this->auth = $this->enc->DecryptString($_POST['authentication_key']);   
  }
  
  function getPOST() {
    $data = file_get_contents('php://input');
    $data = $this->enc->DecryptString($data);
    return json_decode($data, true);
  }
  
  function result($status, $message, $extras = null) {
    $response = array('status' => $status, 'message' => $message);
    if ($extras != null)
      fuse_array($response, $extras);
    $response['authentication_key'] = $this->auth;
    $data = json_encode($response);
    $data = $this->enc->EncryptString($data);
    die($data);
  }
}

class Encryption {
    
  var $_key;
  var $_iv;

  function __construct($key) {
    $this->_key = substr(hash('sha256', $key, true), 0, 32);
    $this->_iv = $this->bytes2string(array(0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0, 0x0));
  }

  function EncryptString($plainText) {
    return base64_encode(openssl_encrypt($plainText, 'aes-256-cbc', $this->_key, OPENSSL_RAW_DATA, $this->_iv));
  }

  function DecryptString($cipherText) {
    return openssl_decrypt(base64_decode($cipherText), 'aes-256-cbc', $this->_key, OPENSSL_RAW_DATA, $this->_iv);
  }

  function SetIV($iv) {
    $this->_iv = $this->bytes2string($iv);
  }

  function bytes2string($bytes) {
    return implode(array_map("chr", $bytes));
  }

}

function fuse_array(&$arr1, $arr2) {
  foreach ($arr2 as $key => $value)
    $arr1[$key] = $value;
}

?>