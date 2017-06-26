<?php

class LinkService {
    
    /**
     * @param Link $link
     */
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
        
        $link->setIdLink($pdo->lastInsertId());
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->set('link_'.$link->getIdLink(), $link);
        $cache->delete("link_lista");
    }
    
    
    /**
     * @param int $idLink
     * @return Link
     * @throws InvalidArgumentException
     */
    public static function obter($idLink) {
        $cache      = Singleton::getInstancia()->getCache();
        $cacheKey   = "link_{$idLink}";
        $fromCache  = $cache->get($cacheKey);
        
        if ($fromCache) {
            return $fromCache;
        }

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

            $cache->set($cacheKey, $link);
            return $link;
        }
        
        throw new InvalidArgumentException('Link não encontrado: ' . (int)$idLink);
    }
    
    
    /**
     * @param int $idLink
     * @return bool
     */
    public static function excluir($idLink) {
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM link WHERE idLink=:idLink";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idLink", $idLink, PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        
        $cache->delete("link_{$idLink}");
        $cache->delete("link_lista");

        return $resultado->rowCount() == 1;
    }
    
    
    /**
     * @return array
     */
    public static function listar() {
        $cache = Singleton::getInstancia()->getCache();
        $fromCache = $cache->get("link_lista");
        
        if ($fromCache) {
            return $fromCache;
        } 
        
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
        
        return $links;        
    }
}