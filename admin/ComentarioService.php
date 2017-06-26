<?php

require_once 'autoload.php';

class ComentarioService {
    public static function salvar(Comentario $comentario) {
        
        $pdo = Singleton::getInstancia()->getPdo();
        if (!$comentario->getIdComentario()) {
            $sql = "INSERT INTO comentario(idPost, nome, descricao, dataComentario) VALUES(:idPost, :nome, :descricao, :dataComentario)";
            $resultado = $pdo->prepare($sql);
        } else {
            $sql = "UPDATE comentario SET idPost=:idPost, nome=:nome, descricao=:descricao, dataComentario=:dataComentario WHERE idComentario=:idComentario";
            $resultado = $pdo->prepare($sql);
            $resultado->bindValue(":idComentario", $comentario->getIdComentario(), PDO::PARAM_INT);
        }

        $resultado->bindValue(":idPost", $comentario->getIdPost(), PDO::PARAM_INT);
        $resultado->bindValue(":nome", $comentario->getNome(), PDO::PARAM_STR);
        $resultado->bindValue(":descricao", $comentario->getDescricao(), PDO::PARAM_STR);
        $resultado->bindValue(":dataComentario", $comentario->getDataComentario(), PDO::PARAM_STR);
        $resultado->execute();
        
        $comentario->setIdComentario($pdo->lastInsertId());
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->set('comentario_'.$comentario->getIdComentario(), $comentario);
        $cache->delete("comentario_lista");
        $cache->delete("comentarios_post_".$comentario->getIdPost());
    }
    
    public static function obter($idComentario) {
        
        $cache      = Singleton::getInstancia()->getCache();
        $cacheKey   = "comentario_{$idComentario}";
        $fromCache  = $cache->get($cacheKey);
        
        if ($fromCache) {
            return $fromCache;
        }

        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "SELECT idComentario, idPost, nome, descricao, dataComentario FROM comentario WHERE idComentario=:idComentario";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idComentario", $idComentario, PDO::PARAM_INT);
        $resultado->execute();

        if ($comentarioBD = $resultado->fetch(PDO::FETCH_OBJ)) {
            $comentario = new Comentario();
            $comentario->setIdComentario($comentarioBD->idComentario);
            $comentario->setIdPost($comentarioBD->idPost);
            $comentario->setNome($comentarioBD->nome);
            $comentario->setDescricao($comentarioBD->descricao); 
            $comentario->setDataComentario($comentarioBD->dataComentario);

            $cache->set($cacheKey, $comentario);
            return $comentario;                
        } 

        throw new InvalidArgumentException('Comentário não encontrado: ' . (int)$idComentario);
    }
    
    
    /**
     * @param int $idPost
     * @return array
     */
    public static function obterPorPost($idPost) {
        
        $cache      = Singleton::getInstancia()->getCache();
        $cacheKey   = "comentarios_post_{$idPost}";
        $fromCache  = $cache->get($cacheKey);
        
        if ($fromCache) {
            return $fromCache;
        }
        
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "SELECT idComentario, idPost, nome, descricao, dataComentario FROM comentario WHERE idPost = :idPost ORDER BY dataComentario";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idPost", $idPost, PDO::PARAM_INT);
        $resultado->execute();

        $comentarios = [];

        while ($comentarioBD = $resultado->fetch(PDO::FETCH_OBJ)) {
            $comentario = new Comentario();
            $comentario->setIdComentario($comentarioBD->idComentario);
            $comentario->setIdPost($comentarioBD->idPost);
            $comentario->setNome($comentarioBD->nome);
            $comentario->setDescricao($comentarioBD->descricao); 
            $comentario->setDataComentario($comentarioBD->dataComentario);

            $comentarios[] = $comentario;
        }
        
        $cache->set($cacheKey, $comentarios);
        
        return $comentarios;
    }
    
    
    public static function excluir($idComentario) {
        
        $comentario = self::obter($idComentario);
        
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM comentario WHERE idComentario=:idComentario";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idComentario", $comentario->getIdComentario(), PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        
        $cache->delete("comentario_{$idComentario}");
        $cache->delete("comentario_lista");
        $cache->delete("comentarios_post_".$comentario->getIdPost());

        return $resultado->rowCount() == 1;
    }
    
    public static function obterLista() {
        $cache = Singleton::getInstancia()->getCache();
        $fromCache = $cache->get("comentario_lista");

        if ($fromCache) {
            return $fromCache;
        }
        
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "SELECT idComentario, idPost, nome, descricao, dataComentario FROM comentario ORDER BY dataComentario";
        $resultado = $pdo->prepare($sql);
        $resultado->execute();

        $comentarios = [];

        while ($comentarioBD = $resultado->fetch(PDO::FETCH_OBJ)) {
            $comentario = new Comentario();
            $comentario->setIdComentario($comentarioBD->idComentario);
            $comentario->setIdPost($comentarioBD->idPost);
            $comentario->setNome($comentarioBD->nome);
            $comentario->setDescricao($comentarioBD->descricao); 
            $comentario->setDataComentario($comentarioBD->dataComentario);

            $comentarios[] = $comentario;
        }            

        $cache->set("comentario_lista", $comentarios);
        
        return $comentarios;        
    }
}
