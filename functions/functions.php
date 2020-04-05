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
                <a href="index.php?generateXML='.$addressId.'"><button name="register" id="testprintLabel">Generate XML</button></a>
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
    // Delivery Address
    $xmlResponse = $webService->get(['resource' => 'addresses/', 'id' => $id]);
    $addressXML = $xmlResponse->address[0];
    $firstName = $addressXML->firstname;
    $lastName = $addressXML->lastname;
    $address1 = $addressXML->address1;
    $address2 = $addressXML->address2;
    $city = $addressXML->city;
    $postcode = $addressXML->postcode;
    $phone = $addressXML->phone;
    
    //Collection Address
    $xmlResponse = $webService->get(['resource' => 'stores/', 'id' => 1]);
    $addressXML = $xmlResponse->store[0];
    $collName = $addressXML->name->language;
    $collAddress1 = $addressXML->address1->language;
    $collAddress2 = $addressXML->address2->language;
    $collCity = $addressXML->city;
    $collPostcode = $addressXML->postcode;
    $email = $addressXML->email;
    
    $dom = new DOMDocument();
    $dom->encoding = 'utf-8';
    $dom->xmlVersion = '1.0';
    $dom->formatOutput = true;
    $xml_file_name = 'newfile.xml';
    $root = $dom->createElement('PreAdvice');
    $con_node = $dom->createElement('Consignment');
    $record_id_node = $dom->createElement('RecordID', '1');
    $con_node->appendChild($record_id_node);
    
    $customer_id_node = $dom->createElement('CustomerAccount', '101L10');
    $con_node->appendChild($customer_id_node);
    
    $currentDateTime = date('Y-m-d\TH:i:s');
    $con_creation_date_node = $dom->createElement('ConsignmentCreationDateTime', $currentDateTime);
    $con_node->appendChild($con_creation_date_node);
    
    $gazz_type_node = $dom->createElement('GazzType', 'PreAdvice');
    $con_node->appendChild($gazz_type_node);
    
    $tracking_number_node = $dom->createElement('TrackingNumber', '0');
    $con_node->appendChild($tracking_number_node);
    
    $total_parcel_node = $dom->createElement('TotalParcels', '1');
    $con_node->appendChild($total_parcel_node);
    
    $relabel_node = $dom->createElement('Relabel', '0');
    $con_node->appendChild($relabel_node);
    
    $service_option_node = $dom->createElement('ServiceOption', '5');
    $con_node->appendChild($service_option_node);
    
    $service_type_node= $dom->createElement('ServiceType', '1');
    $con_node->appendChild($service_type_node);
    
    // Delivery Address
    $delivery_node = $dom->createElement('DeliveryAddress');
    $con_node->appendChild($delivery_node);
    $del_contact_node = $dom->createElement('Contact', $firstName . " " . $lastName);
    $delivery_node->appendChild($del_contact_node);
    
    $del_telephone_node = $dom->createElement('ContactTelephone', "0871816531");
    $delivery_node->appendChild($del_telephone_node);
    
    $del_email_node = $dom->createElement('ContactEmail', "adamcarolan96@gmail.com");
    $delivery_node->appendChild($del_email_node);
    
    $del_business_name_node = $dom->createElement('BusinessName', "AdsPresta");
    $delivery_node->appendChild($del_business_name_node);
    
    $del_address1_node = $dom->createElement('AddressLine1', $address1);
    $delivery_node->appendChild($del_address1_node);
    
    $del_address2_node = $dom->createElement('AddressLine2', $address2);
    $delivery_node->appendChild($del_address2_node);
    
    $del_address3_node = $dom->createElement('AddressLine3', $city);
    $delivery_node->appendChild($del_address3_node);
    
    $del_address4_node = $dom->createElement('AddressLine4');
    $delivery_node->appendChild($del_address4_node);
    
    $del_postcode_node = $dom->createElement('PostCode', $postcode);
    $delivery_node->appendChild($del_postcode_node);
    
    $del_countrycode_node = $dom->createElement('CountryCode', "IE");
    $delivery_node->appendChild($del_countrycode_node);
    //Exit Delivery Node
    
    // Collection Address
    $collection_node = $dom->createElement('CollectionAddress');
    $con_node->appendChild($collection_node);
    $col_contact_node = $dom->createElement('Contact', $collName);
    $collection_node->appendChild($col_contact_node);
    
    $col_telephone_node = $dom->createElement('ContactTelephone', "12345");
    $collection_node->appendChild($col_telephone_node);
    
    $col_email_node = $dom->createElement('ContactEmail', "adamcarolan96@gmail.com");
    $collection_node->appendChild($col_email_node);
    
    $col_business_name_node = $dom->createElement('BusinessName', "DPD");
    $collection_node->appendChild($col_business_name_node);
    
    $col_address1_node = $dom->createElement('AddressLine1', $collAddress1);
    $collection_node->appendChild($col_address1_node);
    
    $col_address2_node = $dom->createElement('AddressLine2', $collAddress2);
    $collection_node->appendChild($col_address2_node);
    
    $col_address3_node = $dom->createElement('AddressLine3', $collCity);
    $collection_node->appendChild($col_address3_node);
    
    $col_address4_node = $dom->createElement('AddressLine4');
    $collection_node->appendChild($col_address4_node);
    
    $col_postcode_node = $dom->createElement('PostCode', $collPostcode);
    $collection_node->appendChild($col_postcode_node);
    
    $col_countrycode_node = $dom->createElement('CountryCode', "IE");
    $collection_node->appendChild($col_countrycode_node);
    //Exit Collection Node
    
    $packinglist_node = $dom->createElement('PackingList');
    $con_node->appendChild($packinglist_node);
    
    $parcel_details_node = $dom->createElement('ParcelDetails');
    $con_node->appendChild($parcel_details_node);
    
    $references_node = $dom->createElement('References');
    $con_node->appendChild($references_node);
    
    $notes_node = $dom->createElement('Notes');
    $con_node->appendChild($notes_node);
    

    $root->appendChild($con_node);
    $dom->appendChild($root);
    $dom->save($xml_file_name);
    echo '<script>alert("NewXMLFile saved");</script>';
    
    /*$dom = new DOMDocument();
    $dom->encoding = 'utf-8';
    $dom->xmlVersion = '1.0';
    $dom->formatOutput = true;
    $xml_file_name = 'newfile.xml';
    $root = $dom->createElement('Preadvice');
    $con_node = $dom->createElement('Consignment');
    $record_id_node = $dom->createElement('RecordID');
    $record_id = new DOMAttr('$record_id_node', '101L10');
    $record_id_node->setAttributeNode($record_id);
    $dom->save($xml_file_name);
    
    $xmlRequest = "<deliveryAddress>\n"
            . "<firstname>".$firstName."</firstname>\n".
            "<lastname>".$lastName."</lastname>\n".
            "<address1>".$address1."</address1>\n".
            "<address2>".$address2."</address2>\n".
            "<city>".$city."</city>\n".
            "<postcode>".$postcode."</postcode>\n".
            "<phone>".$phone."</phone>\n".
            "</deliveryAddress>\n".
            "<collectionAddress>\n".
            "<collName>".$collName."</collName>\n".
            "<collAddress1>".$collAddress1."</collAddress1>\n".
            "<collAddress2>".$collAddress2."</collAddress2>\n".
            "<collCity>".$collCity."</collCity>\n".
            "<collPostcode>".$collPostcode."</collPostcode>\n".
            "<collEmail>".$email."</collEmail>\n".
            "</collectionAddress>\n";
    
    echo '<script>alert("NewTextFile saved");</script>';
    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $xmlRequest);
    fclose($myfile);*/
}

?>