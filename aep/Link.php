<?php

class Link {
    private $idLink;
    private $titulo;
    private $url;
    
    function getIdLink() {
        return $this->idLink;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getUrl() {
        return $this->url;
    }

    function setIdLink($idLink) {
        $this->idLink = $idLink;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setUrl($url) {
        $this->url = $url;
    }
}
