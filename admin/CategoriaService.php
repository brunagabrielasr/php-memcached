<?php

require_once 'autoload.php';

class CategoriaService {
    public static function salvar(Categoria $categoria) {
        
        $pdo = Singleton::getInstancia()->getPdo();
        if (!$categoria->getIdCategoria()) {
            $sql = "INSERT INTO categoria(descricao) VALUES(:descricao)";
            $resultado = $pdo->prepare($sql);
        } else {
            echo $sql = "UPDATE categoria SET descricao=:descricao WHERE idCategoria=:idCategoria";
            $resultado = $pdo->prepare($sql);
            $resultado->bindValue(":idCategoria", $categoria->getIdCategoria(), PDO::PARAM_INT);
        }

        $resultado->bindValue(":descricao", $categoria->getDescricao(), PDO::PARAM_STR);
        $resultado->execute();
        
        $categoria->setIdCategoria($pdo->lastInsertId());
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->set('categoria_'.$categoria->getIdCategoria(), $categoria);
        $cache->delete("categoria_lista");
    }
    
    public static function obter($idCategoria) {
        
        $cache      = Singleton::getInstancia()->getCache();
        $cacheKey   = "categoria_{$idCategoria}";
        $fromCache  = $cache->get($cacheKey);
        
        if ($fromCache) {
            return $fromCache;
        }
        
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "SELECT descricao, idCategoria FROM categoria WHERE idCategoria=:idCategoria";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idCategoria", $idCategoria, PDO::PARAM_INT);
        $resultado->execute();

        if ($categoriaBD = $resultado->fetch(PDO::FETCH_OBJ)) {
            
            $categoria = new Categoria();
            $categoria->setIdCategoria($categoriaBD->idCategoria);
            $categoria->setDescricao($categoriaBD->descricao); 
            
            $cache->set($cacheKey, $categoria);
            
            return $categoria;
        }
        
        throw new InvalidArgumentException('Categoria não encontrada: ' . (int)$idCategoria);
    }
    
    public static function excluir($idCategoria) {
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM categoria WHERE idCategoria=:idCategoria";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idCategoria", $idCategoria, PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        
        $cache->delete("categoria_{$idCategoria}");
        $cache->delete("categoria_lista");
        
        return $resultado->rowCount() == 1;
    }
    
    public static function obterLista() {
        $cache = Singleton::getInstancia()->getCache();

        if (!$categorias = $cache->get("categoria_lista")) {
        
            $pdo = Singleton::getInstancia()->getPdo();
            $sql = "SELECT idCategoria, descricao FROM categoria ORDER BY idCategoria";
            $resultado = $pdo->prepare($sql);
            $resultado->execute();

            $categorias = [];

            while ($categoriaBD = $resultado->fetch(PDO::FETCH_OBJ)) {
                $categoria = new Categoria();
                $categoria->setIdCategoria($categoriaBD->idCategoria);
                $categoria->setDescricao($categoriaBD->descricao);
                

                $categorias[] = $categoria;
            }            
            
            $cache->set("categoria_lista", $categorias);
        } 
        
        return $categorias;        
    }
}
