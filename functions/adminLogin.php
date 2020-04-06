<?php

require __DIR__ . '/../api/index.php';

$token;
$acc_num;

function logIn() {
    if (isset($_POST['admin_login'])) {
        //global $conn;
        //$email = mysqli_real_escape_string($conn, $_POST['admin_email']);
        $acc_num = $_POST['acc_num'];

        //$password = mysqli_real_escape_string($conn, $_POST['admin_pass']);
        $password = $_POST['acc_pass'];

        $token = $_POST['acc_token'];

        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;
        $xml_file_name = 'bearerRequest.xml';
        $root = $dom->createElement('Request');
        $user_node = $dom->createElement('User', $acc_num);
        $password_node = $dom->createElement('Password', $password);
        $type_node = $dom->createElement('Type', 'CUST');

        $root->appendChild($user_node);
        $root->appendChild($password_node);
        $root->appendChild($type_node);
        $dom->appendChild($root);
        $dom->save($xml_file_name);

        getBasicToken($token, $acc_num);
    }
}

function getBasicToken($token, $acc_num) {

    $xmlFile = file_get_contents("bearerRequest.xml");

//The URL that you want to send your XML to.
    $url = 'https://pre-prod-papi.dpd.ie/common/api/authorize';

//Initiate cURL
    $curl = curl_init($url);

// My Custom Headers
    $customHeaders = array("Content-Type: application/xml",
        "Accept: application/xml",
        "Authorization: Basic " . $token);

//Set the Content-Type to text/xml.
    curl_setopt($curl, CURLOPT_HTTPHEADER, $customHeaders);

//Set CURLOPT_POST to true to send a POST request.
//curl_setopt($curl, CURLOPT_POST, true);
//Attach the XML string to the body of our request.
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlFile);

//Tell cURL that we want the response to be returned as
//a string instead of being dumped to the output.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//Execute the POST request and send our XML.
    $result = curl_exec($curl);

    $xml_response = simplexml_load_string($result);
    $response_status = $xml_response->Status;
    $user_id = $xml_response->UserId;
    $access_token = $xml_response->AccessToken;

//Do some basic error checking.
    if (curl_errno($curl)) {
        throw new Exception(curl_error($curl));
    }

//Close the cURL handle.
    curl_close($curl);

    if (strcmp($response_status, 'OK') == 0) {
        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;
        $xml_file_name = 'TokenResponse.xml';
        $root = $dom->createElement('Response');
        $status_node = $dom->createElement('Status', $response_status);
        $user_id_node = $dom->createElement('UserId', $user_id);
        $access_token_node = $dom->createElement('AccessToken', $access_token);
        $root->appendChild($status_node);
        $root->appendChild($user_id_node);
        $root->appendChild($access_token_node);
        $dom->appendChild($root);
        $dom->save($xml_file_name);
        $_SESSION['admin_login'] = $acc_num;
        echo '<script>window.open("index.php", "_self");</script>';
    } else {
        echo 'Incorrect Credentials';
    }

//echo $result;
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

