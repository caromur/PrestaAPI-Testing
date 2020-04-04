<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// Getting the Categories
/*require 'connection.php';
require __DIR__ . '/../api/index.php';
require __DIR__ . '/../api/connectToWebService.php';*/

function readResource()
{
    global $webService;
    $xmlResponse = $webService->get(['resource' => 'addresses']);
    foreach ($xmlResponse->addresses->address as $address) {
        $addressId = (int) $address['id'];//$address['5'];
        $addressXmlResponse = $webService->get(['resource' => 'addresses', 'id' => $addressId]);
        $address = $addressXmlResponse->address[0];

        echo ' <div class="login-page">
            <div class="the-login-form">
                <form class="login-form" method="post" action="">
                    <table class="register-table">
                        <tr>
                            <td class="cus-reg-field">First Name: </td>
                            <td><input type="text" placeholder="Name" name="cus_name" id='.$addressId.'" onclick="findById(this.id)" value='.$address->firstname.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Second Name: </td>
                            <td><input type="text" placeholder="Email" name="cus_email" id="lastname" value='.$address->lastname.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Address Line 1: </td>
                            <td><input type="text" placeholder="Password" name="cus_pass" id="address1" value='.$address->address1.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Address Line 2: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" id="address2" value='.$address->address2.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">City: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" id="city" value='.$address->city.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Post Code: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" id="postcode" value='.$address->postcode.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Phone Number: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" id="phone" value='.$address->phone.' required/></td>
                        </tr>
                    </table>
                </form>
                <a href="index.php?generateXML='.$addressId.'"><button name="register" id="testprintLabel">Print Label</button></a>
            </div>
        </div>';

        
     
        //echo sprintf('ID: %s, alias: %s' . PHP_EOL, $address->lastname, $address->lastname);
    }
}

function generateXML()
{
       if(isset($_GET['generateXML']))
    {
           $custId = $_GET['generateXML'];
           readSingleAddress($custId);
    }
}

function readSingleAddress($id)
{
    global $webService;
    $xmlResponse = $webService->get(['resource' => 'addresses/', 'id' => $id]);
    $addressXML = $xmlResponse->address[0];
    $firstName = $addressXML->firstname;
    $lastName = $addressXML->lastname;
    $address1 = $addressXML->address1;
    $address2 = $addressXML->address2;
    $city = $addressXML->city;
    $postcode = $addressXML->postcode;
    $phone = $addressXML->phone;
    
    $xmlRequest = "<firstname>".$firstName."</firstname>\n".
            "<lastname>".$lastName."</lastname>\n".
            "<address1>".$address1."</address1>\n".
            "<address2>".$address2."</address2>\n".
            "<city>".$city."</city>\n".
            "<postcode>".$postcode."</postcode>\n".
            "<phone>".$phone."</phone>\n";
    
    echo '<script>alert("NewTextFile saved");</script>';
    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $xmlRequest);
    fclose($myfile);
}

?>