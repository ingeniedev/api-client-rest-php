<?php

 require_once '../../connection.inc.php';
 
/**
 * objets : Exemple d'utilisation des requêtes sur les objets touristiques 
 */
 
//---------------------------------------------------
// Exemple 1
// Récupération de tous les objets d'un projet.
// Utilisé par exemple lors d'un import initial
// A éviter par la suite !
//---------------------------------------------------
 
try {
    $reponse = $objApiClient->get('/v2/objets_touristiques?id_projet='.API_PROJET_ID);
    // On si le résultat est partiel ou pas (pagination)
    if($reponse->isPartialContent()) {
       // On doit faire une boucle tant qu'il y a du contenu à lire
       while(($url = $reponse->getNextLink()) !== null) {
           $reponse = $objApiClient->get($url);
           // Récupération des données
           $data = $reponse->getData();
           echo 'Nb objet(s) ('.$url.'): '.count($data)."\n";
           //print_r($data);
       }
    } else {
       // Récupération des données
       $data = $reponse->getData();
       //print_r($data);
       echo 'Nb objet(s) : '.count($data)."\n";
    }
    
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}
 
//------------------------------------------------------------------
// Exemple 2
// Récupération de tous les objets d'un projet modifié depuis 24h
//------------------------------------------------------------------
/*
try {
    $reponse = $objApiClient->get('/v2/objets_touristiques?id_projet='.API_PROJET_ID.'&update_from=24h');
    if($reponse->isPartialContent()) {
       // On doit faire une boucle tant qu'il y a du contenu à lire
       while(($url = $reponse->getNextLink()) !== null) {
           $reponse = $objApiClient->get($url);
           // Récupération des données
           $data = $reponse->getData();
           echo 'Nb objet(s) ('.$url.'): '.count($data)."\n";
           //print_r($data);
       }
    } else {
       // Récupération des données
       $data = $reponse->getData();
       //print_r($data);
       echo 'Nb objet(s) : '.count($data)."\n";
    }
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}
*/
//----------------------------------------------
// Exemple 3
// Récupération d'un objet par son id 
//---------------------------------------------
    
/*
try {
    //  Id objet à modifier suivant le contexte
    $idObjet = 'A|FRANCERAFT';
    $reponse = $objApiClient->get('/v2/objets_touristiques/'.$idObjet);
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}*/


