<?php

/**
 *  TEST DE L'API INGENIE
 */

require_once 'lib/Ingenie/ApiClientRest/IngenieClientRest.php';
use Ingenie\ApiClientRest\IngenieClientRest;

// Code d'accés envoyés par Ingénie
define('API_USERNAME','');
define('API_PASSWORD','');
define('API_ORGANISME','');
define('API_PROJET_ID','');

$objApiClient = new IngenieClientRest();

//------------------------
// Connexion à l'API
//------------------------
try {
    $objApiClient->connect(API_USERNAME, API_PASSWORD, API_ORGANISME);
    echo 'Token : '.$objApiClient->getToken()."\n";
} catch (\Exception $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
    exit;
}

//---------------------------------------------------
// Récupération de toutes les sélections d'un projet
// sans les objets touristiques
//---------------------------------------------------
/*
try {
    $reponse = $objApiClient->get('/v2/objets_touristiques/selections?id_projet='.API_PROJET_ID.'&fields=-objets_touristiques');
    $data = $objApiClient->checkReponse($reponse);
    print_r($data);
} catch (\Exception $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}
*/
//----------------------------------------------
// Récupération des sélections
// avec uniquent les ids d'objets touristiques
//----------------------------------------------

/*
try {
    $reponse = $objApiClient->get('/v2/objets_touristiques/selections?id_projet='.API_PROJET_ID.'&ids=68P,69P&fields=objets_touristiques');
    $data = $objApiClient->checkReponse($reponse);
    print_r($data);
} catch (\Exception $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}*/

//-------------------------------------------------
// Récupération des id d'objets d'une sélection
// avec uniquent les ids d'objets touristiques
//-------------------------------------------------
/*
try {
    $reponse = $objApiClient->get('/v2/objets_touristiques/selections/68P?fields=objets_touristiques');
    $data = $objApiClient->checkReponse($reponse);
    print_r($data);
} catch (\Exception $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}*/

//----------------------------------------------
// Récupération des objets modifiés depuis 72h 
// uniquement avec les champs id, libelle
//----------------------------------------------

try {
    $reponse = $objApiClient->get('/v2/objets_touristiques?id_projet='.API_PROJET_ID.'&update_from=72h&fields=id,libelle');
    $data = $objApiClient->checkReponse($reponse);
    print_r($data);
} catch (\Exception $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}

//----------------------------------------------
// Récupération des objets par un id 
//----------------------------------------------
/*
try {
    $reponse = $objApiClient->get('/v2/objets_touristiques/H|RLDC');
    $data = $objApiClient->checkReponse($reponse);
    print_r($data);
} catch (\Exception $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}
*/

