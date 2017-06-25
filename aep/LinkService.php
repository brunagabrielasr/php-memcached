<?php

class LinkService {
    public static function salvar(Link $link) {
        
        $pdo = Singleton::getInstancia()->getPdo();
        if (!$link->getIdLink()) {
            $sql = "INSERT INTO link(titulo, url) VALUES(:titulo, :url)";
            $resultado = $pdo->prepare($sql);
        } else {
            $sql = "UPDATE link SET titulo=:titulo, url=:url WHERE idLink=:idLink";
            $resultado = $pdo->prepare($sql);
            $resultado->bindValue(":idLink", $link->getIdLink(), PDO::PARAM_INT);
        }

        $resultado->bindValue(":titulo", $link->getTitulo(), PDO::PARAM_STR);
        $resultado->bindValue(":url", $link->getUrl(), PDO::PARAM_STR);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("link_lista");
    }
    
    public static function editar($idLink) {
        $cache = Singleton::getInstancia()->getCache();

        if (!$linkEdit = $cache->get("link")) {

            $pdo = Singleton::getInstancia()->getPdo();
            $sql = "SELECT idLink, titulo, url FROM link WHERE idLink=:idLink";
            $resultado = $pdo->prepare($sql);
            $resultado->bindValue(":idLink", $idLink, PDO::PARAM_INT);
            $resultado->execute();

            if ($linkBD = $resultado->fetch(PDO::FETCH_OBJ)) {
                $link = new Link();
                $link->setIdLink($linkBD->idLink);
                $link->setTitulo($linkBD->titulo);
                $link->setUrl($linkBD->url);
                
                $cache->set("link", $linkEdit);
                return $link;                
            } 
            
            return false;
        } 
    }
    
    public static function excluir($idLink) {
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM link WHERE idLink=:idLink";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idLink", $idLink, PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("link_{$idLink}");

        return $resultado->rowCount() == 1;
    }
    
    public static function listar() {
        $cache = Singleton::getInstancia()->getCache();

        if (!$links = $cache->get("link_lista")) {
        
            $pdo = Singleton::getInstancia()->getPdo();
            $sql = "SELECT idLink, titulo, url FROM link ORDER BY idLink";
            $resultado = $pdo->prepare($sql);
            $resultado->execute();

            $links = [];

            while ($linkBD = $resultado->fetch(PDO::FETCH_OBJ)) {
                $link = new Link();
                $link->setIdLink($linkBD->idLink);
                $link->setTitulo($linkBD->titulo);
                $link->setUrl($linkBD->url);
                

                $links[] = $link;
            }            
            
            $cache->set("link_lista", $links);
        } 
        
        return $links;        
    }
}