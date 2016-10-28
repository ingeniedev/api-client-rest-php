<?php

namespace Ingenie\Api;

/**
 * Classe de gestion de la pagination vi header Link
 *
 * @author Ingenie
 */
class ApiLinkPage {
    
    const DELIM_LINKS = ", ";

    const DELIM_LINK_PARAM = ";";
    const HEADER_LINK = "Link";
    const META_REL = "rel";
    const META_FIRST = "first";
    const META_LAST = "last";
    const META_NEXT = "next";
    const META_PREV = "prev";
    const HEADER_LAST = "X-Last";
    const HEADER_NEXT = "X-Next";

    private $first = null;
    private $last = null;
    private $next = null;
    private $prev = null;

    /**
     * Vérifie les liens de pagination dans les headers
     */
    public function __construct($headers) {
        
        if (isset($headers[self::HEADER_LINK])) {
           
            $linkHeader = $headers[self::HEADER_LINK];
            $links = explode(self::DELIM_LINKS,$linkHeader);
            
            foreach($links as $link) {
                $segments = explode(self::DELIM_LINK_PARAM,$link);
                
                if (count($segments) < 2) {
                    continue;
                }

                $linkPart = trim($segments[0]);
                if (strpos($linkPart, "<") === false || strpos($linkPart, ">") === false) {
                    continue;
                }
                
                $linkPart = substr($linkPart,1, (strlen($linkPart) - 2));
                
                for ($i = 1; $i < count($segments); $i++) {
                    $rel = explode("=",trim($segments[$i]));
                    if (count($rel) < 2 ||  $rel[0] != self::META_REL) {
                        continue;
                    }
                    $relValue = $rel[1];
                    if (stripos($relValue,"\"") !== false && strripos($relValue,"\"") !== false) {
                        $relValue = substr($relValue,1, strlen($relValue) - 2);
                    }
                    if ($relValue == self::META_FIRST) {
                        $this->first = $linkPart;
                    } elseif ($relValue == self::META_LAST) {
                        $this->last = $linkPart;
                    } elseif ($relValue == self::META_NEXT) {
                        $this->next = $linkPart;
                    } else if ($relValue == self::META_PREV) {
                        $this->prev = $linkPart;
                    }
                }
            }
        } else {
            $this->next = $headers[self::HEADER_NEXT];
            $this->last = $headers[self::HEADER_LAST];
        }
    }

    /**
     * @return first
     */
    public function getFirst() {
        return $this->first;
    }

    /**
     * @return last
     */
    public function getLast() {
        return $this->last;
    }

    /**
     * @return next
     */
    public function getNext() {
        return $this->next;
    }

    /**
     * Retourne le prochaine numéro de page
     *
     * @return Le numéro de page
     */
    public function getNextPage() {
        if ($this->next == null) {
            return null;
        } else {
            $pageSplit = explode("&page=",$this->next);
            if (count($pageSplit) == 2) {
                return $pageSplit[1];
            }
        }
        return null;
    }

    /**
     * @return prev
     */
    public function getPrev() {
        return $this->prev;
    }
    
}


