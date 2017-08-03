<?php

require_once '../../connection.inc.php';

// Attention l'import des clients se fait sur une url d'API
// La connexion reste sur l'url principales
$objApiClient->setUrlApi('https://[ADRESSE SERVEUR A CONFIGURER]/api');

/**
 * search :  Recherche de dispo sur une période
 */

// Création d'un nouveau client
try {

    $reponse = $objApiClient->get('v2/booking/search?cid=1&datedeb=2017-01-07&datefin=2017-01-14&personnes=4&types=H');
    var_dump($reponse);
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}