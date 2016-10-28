<?php

require_once '../../connection.inc.php';

/** 
 * selections : Exemples d'utilisation des requêtes de sélections 
 */

//---------------------------------------------------
// Exemple 1
// Récupération de toutes les sélections d'un projet
// sans les objets touristiques
//---------------------------------------------------
/*
try {
    $reponse = $objApiClient->get('v2/objets_touristiques/selections?id_projet='.API_PROJET_ID.'&fields=-objets_touristiques');
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}
 */
 
//----------------------------------------------
// Exemple 2
// Récupération des sélections
// avec uniquent les ids d'objets touristiques
//----------------------------------------------
/*
try {
    $reponse = $objApiClient->get('v2/objets_touristiques/selections?id_projet='.API_PROJET_ID.'&ids=15P,18P&fields=objets_touristiques');
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}
*/
//-------------------------------------------------
// Exemple 3
// Récupération des id d'objets d'une sélection
// avec uniquent les ids d'objets touristiques
//-------------------------------------------------

try {
    $reponse = $objApiClient->get('v2/objets_touristiques/selections/15P?fields=objets_touristiques');
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}


