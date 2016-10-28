<?php

 require_once '../../connection.inc.php';
 
 // Attention l'import des clients se fait sur une url d'API
 // La connexion reste sur l'url principales
 $objApiClient->setUrlApi('https://[ADRESSE SERVEUR A CONFIGURER]/api');
 
/**
 * import_customers :  Import des clients
 */
 
// Création d'un nouveau client
try {
    
    $customer = array();
    $customer['category'] = 'P';
    $customer['civility'] = 'M';
    $customer['first_name'] = 'Jean';
    $customer['last_name'] = 'Dupond';
    $customer['email'] = 'jean.dupond@ingenie.fr';
    $customer['phones'] = array(array('type' => 'main', 'num', '04 38 729 100'));
    $customer['address'] = array(array('type' => 'main', 
                                        'lines' => array('Rue des sept laux','ZA Les Pérelles'),
                                        'postal_code' => '38570', 
                                        'city' => 'LE CHEYLAS', 
                                        'country' => 'FRA'
                            ));
    $reponse = $objApiClient->post('v2/customers/',  json_encode($customer));
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}

// Modification d'un client
/*
try {
    
    $customer = array();
    $customer['category'] = 'P';
    $customer['civility'] = 'M';
    $customer['first_name'] = 'Jean';
    $customer['last_name'] = 'Dupond';
    $customer['email'] = 'info@ingenie.fr';
    $customer['phones'] = array(array('type' => 'main', 'num', '04 38 729 100'));
    $customer['address'] = array(array('type' => 'main', 
                                        'lines' => array('Rue des sept laux','ZA Les Pérelles'),
                                        'postal_code' => '38570', 
                                        'city' => 'LE CHEYLAS', 
                                        'country' => 'FRA'
                            ));
    $idClient = 68502; // A changer suivant le contexte
    $reponse = $objApiClient->put('v2/customers/'.$idClient,  json_encode($customer));
    print_r($reponse->getData());
} catch (\Ingenie\Api\ApiException $ex) {
    echo 'Code : '.$ex->getCode().' / Message :  '.$ex->getMessage()."\n";
}

