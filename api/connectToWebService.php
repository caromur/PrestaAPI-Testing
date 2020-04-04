<?php

require __DIR__ . '/../vendor/autoload.php';

$url = 'http://localhost/AdsPresta';
$key  = 'A363IBM5XWUBW4661U8VDQPGTEW825JD';
$debug = false;

try
    {
       $webService = new PrestaShopWebservice($url, $key, $debug);
    }
    catch(Exception $e)
    {
        $error_message = $e->getMessage();
        include ('database_error.php');
        exit();
    }
    


