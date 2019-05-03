<?php
$token = 'aevgre45w5gw455gersg4w5tghyuytrjhbw45g';
if(isset($_POST['token']) && isset($_POST['user_name']) && isset($_POST['user_phone'])){
  if ($_POST['token'] == $token) {
    $message = '';
    if ($_POST['action'] == 'zakaz') {
      $message = 'Jūsu pasūtījums ir pieņemts. Tuvākajā laikā ar jums sazināsies konsultants ! Paldies par uzticību ! iEnter.lv';
    }
    elseif ($_POST['action'] == 'consult') {
      $message = 'Tuvākajā laikā ar jums sazināsies konsultants ! iEnter.lv';
    }
    else{
      die();
    }

    $message = $_POST['user_name'].', '.$message;

    $sUrl  = 'https://letsads.com/api';
    $sXML  = '<?xml version="1.0" encoding="UTF-8"?>
              <request>
                  <auth>
                      <login>37129727419</login>
                      <password>488158</password>
                  </auth>
                  <message>
                      <from>iEnter.lv</from>
                      <text>'.$message.'</text>
                      <recipient>'.$_POST['user_phone'].'</recipient>
                  </message>
              </request>';

    $rCurl = curl_init($sUrl);
    curl_setopt($rCurl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($rCurl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($rCurl, CURLOPT_HEADER, 0);
    curl_setopt($rCurl, CURLOPT_POSTFIELDS, $sXML);
    curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($rCurl, CURLOPT_POST, 1);
    $sAnswer = curl_exec($rCurl);
    curl_close($rCurl);


    echo $sAnswer;
  }
  else{
    echo 'error';
    die();
  }
}
else{
  echo 'error';
  die();
}

?>
