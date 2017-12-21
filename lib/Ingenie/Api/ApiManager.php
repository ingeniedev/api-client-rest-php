<?php

namespace Ingenie\Api;

/**
 * Classe de connection à l'API Ingénie
 *
 * @author Ingenie
 */
class ApiManager {

    private $token = null;
    private $urlApi = self::URL_API;
    private $headers = array();
    private $isConnected = false;

    const URL_API = "https://api.ingenie.fr";
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const HEADER_JSON = 'Content-Type: application/json';
    
    const HTTP_OK = 200;
    const HTTP_OK_PARTIAL = 206;
    
    /**
     * Init de l'objet
     * @param string $urlApi Url de l'API
     */
    public function __construct($urlApi = self::URL_API) {
        $this->urlApi = $urlApi;
        $this->headers = array(self::HEADER_JSON);
    }
    
    /**
     * Changement de l'url de l'API
     * @param string $url
     */
    public function setUrlApi($url) {
         $this->urlApi = $url;
    }
    
    /**
     * Ajoute une valeur en header
     * @param string $header
     */
    public function addHeader($header) {
        $this->headers[] = $header;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
        $this->isConnected = true;
        $this->headers = array(self::HEADER_JSON);
        $this->addHeader('Authorization: Bearer ' . $this->token);
    }

    /**
     * Connection à l'API
     * @param string $username Nom d'utilisateur 
     * @param string $password Mot de passe
     * @param string $organisme Id de l'organisme
     * @throws ApiException
     */
    public function connect($username, $password, $organisme) {

        $this->headers = array(self::HEADER_JSON);

        $reponse = $this->doRequest(self::POST, $this->urlApi . '/login', json_encode(
                        array('username' => $username, 'password' => $password, 'organisme' => $organisme)
        ));
        if ($reponse !== null && $reponse->getStatus() == self::HTTP_OK) {
            $data = $reponse->getData(true);
            if (isset($data['token'])) {
                $this->setToken($data['token']);
            }
        } else {
            $msg = 'Impossible de se connecter à l\'API';
            if (($data = $reponse->getData(true)) !== null) {
                if (isset($data['error'])) {
                    $msg = '<br />' . $data['error'] . ' : ';
                }
                if (isset($data['error_description'])) {
                    $msg .= $data['error_description'];
                }
            }
            throw new ApiException($msg);
        }
    }
    
    /**
     * Requête GET
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws ApiException
     */
    public function get($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::GET, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new ApiException('Vous devez vous connecter à l\'API et demander un token');
        }
    }
    
    /**
     * Requête POST
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws ApiException
     */
    public function post($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::POST, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new ApiException('Vous devez vous connecter à l\'API et demander un token');
        }
    }
    
    /**
     * Requête PUT
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws ApiException
     */
    public function put($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::PUT, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new ApiException('Vous devez vous connecter à l\'API et demander un token');
        }
    }
    
    /**
     * Requête DELETE
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws ApiException
     */
    public function delete($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::DELETE, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new ApiException('Vous devez vous connecter à l\'API et demander un token');
        }
    }

    /**
     * Faire une requête
     * @param string $type POST|GET|PUT|DELETE
     * @param string $url Url de requête
     * @param array $params paramétre
     * @return ApiResponse
     * @throws ApiException
     */
    protected function doRequest($type, $url, $params = array()) {
        $s = curl_init();
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, $type);
        switch ($type) {
            case self::DELETE:
                $query = $url;
                if (count($params)) {
                    $query .= '?' . http_build_query($params);
                }
                curl_setopt($s, CURLOPT_URL, $query);
                break;
            case self::POST:
                curl_setopt($s, CURLOPT_URL, $url);
                curl_setopt($s, CURLOPT_POST, true);
                curl_setopt($s, CURLOPT_POSTFIELDS, $params);
                break;
            case self::PUT:
                curl_setopt($s, CURLOPT_URL, $url);
                curl_setopt($s, CURLOPT_POST, true);
                curl_setopt($s, CURLOPT_POSTFIELDS, $params);
                break;
            case self::GET:
                $query = $url;
                if (count($params)) {
                    $query .= '?' . http_build_query($params);
                }
                curl_setopt($s, CURLOPT_URL, $query);
                break;
            default :
                throw new ApiException("Type non valide");
        }
        curl_setopt($s, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($s, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($s,CURLOPT_HEADER,true);
        
        $reponseCurl = curl_exec($s);
        if(curl_errno($s)) {
            throw new ApiException('Curl: ' . curl_error($s));
        }
        // Récupération du status de la requêtes
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        // Taille du header
        $headerSize = curl_getinfo($s, CURLINFO_HEADER_SIZE);
        // Extraction du header
        $headers = $this->_parseHeader(substr($reponseCurl, 0, $headerSize));
        
        // Extraction des données
        $data = substr($reponseCurl, $headerSize);
        if(json_decode($data) === null) {
           $data = null; 
        }
        curl_close($s);
        return new ApiResponse($status,$data,$headers);
    }
    
    /**
     * Pour passer les headers
     * @param string $strHeader
     * @return array Tableau de headers sous forme de clé / valeur
     */
    private function _parseHeader($strHeader) {
        $headers = array();
        $tabTmpHead = substr($strHeader, stripos($strHeader, "\r\n"));
        $tabTmpHead = explode("\r\n", $tabTmpHead);
        foreach ($tabTmpHead as $h) {
            if($h !== '') {
                list($v, $val) = explode(": ", $h);
                if ($v == null) continue;
                $headers[$v] = $val;
            }
        }
        return $headers;
    }
   
}