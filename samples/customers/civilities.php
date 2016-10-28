<?php

 require_once '../../connection.inc.php';
 
/**
 * civilities : Récupération des civilités 
 */
 
try {
    $reponse = $objApiClient->get('v2/customers/civilities');
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}

