<?php

 require_once '../../connection.inc.php';
 
/**
 * countries : Récupération des pays 
 */
 
try {
    $reponse = $objApiClient->get('v2/customers/countries');
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}

