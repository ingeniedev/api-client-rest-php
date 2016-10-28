<?php

require __DIR__ . '/vendor/autoload.php';

require 'config.inc.php';

$objApiClient = new Ingenie\Api\ApiManager();

//------------------------
// Connexion à l'API
//------------------------
try {
    $objApiClient->connect(API_USERNAME, API_PASSWORD, API_ORGANISME);
    //echo 'Token : '.$objApiClient->getToken()."\n";
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Erreur Login : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
    exit;
}
