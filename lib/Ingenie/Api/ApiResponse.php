<?php

namespace Ingenie\Api;

/**
 * Classe de gestion de la réponse HTTP
 *
 * @author Ingenie
 */
class ApiResponse {
    
    private $status;
            
    private $data;
    
    private $headers;
    
    public function __construct($status,$data,$headers) {
        $this->status = $status;
        $this->data = $data;
        $this->headers = $headers;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * Analyse la réponse et retourne le contenu JSON
     * @param boolean $modeArray Indique si on est en mode array ou non
     * @return mix json array
     */
    public function getData($modeArray = true) {
      if ($this->status == \Ingenie\Api\ApiManager::HTTP_OK || $this->isPartialContent()) {
           return json_decode($this->data, $modeArray);
      } else {
          if($this->data !== null) {
              $data = json_decode($this->data);
              $code = (double) $this->status;
             throw new \Ingenie\Api\ApiException($data->error.' => '.$data->error_description,$code);
          } else {
             throw new \Ingenie\Api\ApiException('Erreur '.$this->status);
          }
      }  
    }
    
    public function isPartialContent()
    {
       if($this->status == \Ingenie\Api\ApiManager::HTTP_OK_PARTIAL) {
           return true;
       } 
       return false;
    }
    
    public function getNextLink($urlApi = null)
    {
        if(isset($this->headers['Link'])) {
            $pager = new ApiLinkPage($this->headers);
            if($urlApi === null) {
                $urlApi = \Ingenie\Api\ApiManager::URL_API;
            }
            $link = str_replace($urlApi,'',$pager->getNext());
            if($link != '') {
              return $link;
            }
        }
        return null;
    }
    
}


