<?php

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

        $resultado->bindValue(":idPost", $comentario->getIdPost()->getIdPost(), PDO::PARAM_INT);
        $resultado->bindValue(":nome", $comentario->getNome(), PDO::PARAM_STR);
        $resultado->bindValue(":descricao", $comentario->getDescricao(), PDO::PARAM_STR);
        $resultado->bindValue(":dataComentario", $comentario->getDataComentario(), PDO::PARAM_STR);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("comentario_lista");
    }
    
    public static function editar($idComentario) {
        $cache = Singleton::getInstancia()->getCache();

        if (!$comentarioEdit = $cache->get("comentario")) {

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
                
                $cache->set("comentario", $comentarioEdit);
                return $comentario;                
            } 
            
            return false;
        } 
    }
    
    public static function excluir($idComentario) {
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM comentario WHERE idComentario=:idComentario";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idComentario", $idComentario, PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("comentario_{idComentario}");

        return $resultado->rowCount() == 1;
    }
    
    public static function listar() {
        $cache = Singleton::getInstancia()->getCache();

        if (!$comentarios = $cache->get("comentario_lista")) {
        
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
        } 
        
        return $comentarios;        
    }
}
