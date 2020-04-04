<?php
require 'Slim/Slim.php';
require 'labelRequests.php';
require 'connectToWebService.php';
use Slim\Slim;
\Slim\Slim::registerAutoloader();

$app = new Slim();
/*$app->get('/products', 'getProducts');
$app->get('/products/:id',  'getProduct');
$app->post('/products', 'addProduct');
$app->delete('/products/:id', 'deleteProduct');
$app->get('/games/platform/:platform',  'viewPs1Games');
$app->put('/products/:id',  'updateDetails');
$app->run();*/
?>