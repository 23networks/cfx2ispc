<?php
$username = 'admin';
$password = 'sososecret';
$soap_location = 'https://server.example.com:8080/remote/index.php';
$soap_uri = 'http://server.example.com:8080/remote/';

$client = new SoapClient(null, array('location' => $soap_location,
        'uri'      => $soap_uri,
        'trace' => 1,
        'exceptions' => 1));

try {
    if($session_id = $client->login($username, $password)) {
        echo 'Logged successfull. Session ID:'.$session_id.'<br />';
    }

    $app = $client->mail_user_get($session_id, 1192);
    print_r($app);

    if($client->logout($session_id)) {
        echo 'Logged out.<br />';
    }


    } catch (SoapFault $e) {
        echo $client->__getLastResponse();
        die('SOAP Error: '.$e->getMessage());
    }
?>
