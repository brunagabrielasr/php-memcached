<?php

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
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("categoria_lista");
    }
    
    public static function editar($idCategoria) {
        $cache = Singleton::getInstancia()->getCache();
        echo $idCategoria;
        var_dump($categoriaEdit = $cache->get("categoria"));
        exit;

        if (!$categoriaEdit = $cache->get("categoria")) {
            $pdo = Singleton::getInstancia()->getPdo();
            $sql = "SELECT descricao, idCategoria FROM categoria WHERE idCategoria=:idCategoria";
            $resultado = $pdo->prepare($sql);
            $resultado->bindValue(":idCategoria", $idCategoria, PDO::PARAM_INT);
            $resultado->execute();

            if ($categoriaBD = $resultado->fetch(PDO::FETCH_OBJ)) {
                $categoria = new Categoria();
                $categoria->setIdCategoria($categoriaBD->idCategoria);
                $categoria->setDescricao($categoriaBD->descricao); 
                
                $cache->set("categoria", $categoriaEdit);
                return $categoria;                
            } 
            
            return false;
        } 
    }
    
    public static function excluir($idCategoria) {
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM categoria WHERE idCategoria=:idCategoria";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idCategoria", $idCategoria, PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("categoria_{$idCategoria}");

        return $resultado->rowCount() == 1;
    }
    
    public static function listar() {
        $cache = Singleton::getInstancia()->getCache();

        if (!$categorias = $cache->get("categoria_lista")) {
        
            $pdo = Singleton::getInstancia()->getPdo();
            $sql = "SELECT idCategoria, descricao FROM categoria ORDER BY idCategoria";
            $resultado = $pdo->prepare($sql);
            $resultado->execute();

            $cidades = [];

            while ($categoriaBD = $resultado->fetch(PDO::FETCH_OBJ)) {
                $categoria = new Categoria();
                $categoria->setIdCategoria($categoriaBD->idCategoria);
                $categoria->setDescricao($categoriaBD->descricao);
                

                $categorias[] = $categoria;
            }            
            
            $cache->set("categoria_lista", $cidades);
        } 
        
        return $categorias;        
    }
}
