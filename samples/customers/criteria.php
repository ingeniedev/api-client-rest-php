<?php

 require_once '../../connection.inc.php';
 
/**
 * criteria  : Récupération des critères 
 */
 
try {
    $reponse = $objApiClient->get('v2/customers/criteria');
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}

