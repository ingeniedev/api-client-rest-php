<?php

namespace Ingenie\ApiClientRest;

/**
 * Classe de connection à l'API Ingénie
 *
 * @author Ingenie
 */
class IngenieClientRest {

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

    /**
     * Init de l'objet
     * @param type $urlApi Url de l'API
     */
    public function __construct($urlApi = self::URL_API) {
        $this->urlApi = $urlApi;
        $this->headers = array(self::HEADER_JSON);
    }

    /**
     * Ajoute une valeur en header
     * @param string[] $header
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
        $this->addHeader('Authorization : Bearer ' . $this->token);
    }

    /**
     * Connection à l'API
     * @param string $username Nom d'utilisateur 
     * @param string $password Mot de passe
     * @param string $organisme Id de l'organisme
     */
    public function connect($username, $password, $organisme) {

        $this->headers = array(self::HEADER_JSON);

        $reponse = $this->doRequest(self::POST, $this->urlApi . '/login', json_encode(
                        array('username' => $username, 'password' => $password, 'organisme' => $organisme)
        ));

        if ($reponse !== null && $reponse->status == self::HTTP_OK) {
            $data = json_decode($reponse->data, true);
            if (isset($data['token'])) {
                $this->setToken($data['token']);
            }
        } else {
            $msg = 'Impossible de se connecter à l\'API';
            if (isset($reponse->data) && $reponse->data !== null) {
                $data = json_decode($reponse->data, true);
                if (isset($data['error'])) {
                    $msg = '<br />' . $data['error'] . ' : ';
                }
                if (isset($data['error_description'])) {
                    $msg .= $data['error_description'];
                }
            }
            throw new \Exception($msg);
        }
    }
    
    /**
     * Analyse la réponse et retourne le contenu JSON
     * @param mix $reponse
     * @param boolean $modeArray Indique si on est en mode array ou non
     * @return mix json array
     */
    public function checkReponse($reponse,$modeArray = true) {
      if ($reponse->status == self::HTTP_OK) {
           return json_decode($reponse->data, $modeArray);
      } else {
          if($reponse->data !== null) {
              $data = json_decode($reponse->data);
              $code = (double) $reponse->status;
             throw new \Exception($data->error.' => '.$data->error_description,$code);
          } else {
             throw new \Exception('Erreur '.$reponse->status);
          }
      }  
    }
    
    /**
     * Requête GET
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws Exception
     */
    public function get($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::GET, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new \Exception('Vous devez vous connecter à l\'API et demander un token');
        }
    }
    
    /**
     * Requête POST
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws Exception
     */
    public function post($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::POST, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new \Exception('Vous devez vous connecter à l\'API et demander un token');
        }
    }
    
    /**
     * Requête PUT
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws Exception
     */
    public function put($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::PUT, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new \Exception('Vous devez vous connecter à l\'API et demander un token');
        }
    }
    
    /**
     * Requête DELETE
     * @param string $resource 
     * @param array $params
     * @return object
     * @throws Exception
     */
    public function delete($resource, $params = array()) {
        if ($this->isConnected) {
            return $this->doRequest(self::DELETE, $this->urlApi . '/' . $resource, $params);
        } else {
            throw new \Exception('Vous devez vous connecter à l\'API et demander un token');
        }
    }

    /**
     * Faire une requête
     *
     * @param string $type POST|GET|PUT|DELETE
     * @param string $url Url de requête
     * @param array $params paramétre
     * @return $reponse object
     */
    protected function doRequest($type, $url, $params = array()) {
        $headers = $this->headers;
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
                throw new \Exception("Type non valide");
        }

        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($s);
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);
        
        // Récupétation de la reponse
        $reponse = new \stdClass();
        $reponse->status = $status;
        if (($json = json_decode($data)) !== null) {
            $reponse->data = $data;
        } else {
            $reponse->data = null;
        }

        return $reponse;
    }
    
}
