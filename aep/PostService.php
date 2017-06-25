<?php

class PostService {
    public static function salvar(Post $post) {
        
        $pdo = Singleton::getInstancia()->getPdo();
        if (!$post->getIdPost()) {
            $sql = "INSERT INTO post(idCategoria, titulo, post, dataPost, ativo, tags) VALUES(:idCategoria, :titulo, :post, :dataPost, :ativo, :tags)";
            $resultado = $pdo->prepare($sql);
        } else {
            $sql = "UPDATE post SET idCategoria=:idCategoria, titulo=:titulo, post=:post, dataPost=:dataPost, ativo=:ativo, tags=:tags WHERE idPost=:idPost";
            $resultado = $pdo->prepare($sql);
            $resultado->bindValue(":idPost", $post->getIdPost(), PDO::PARAM_INT);
        }

        $resultado->bindValue(":idCategoria", $post->getIdCategoria()->getIdCategoria(), PDO::PARAM_INT);
        $resultado->bindValue(":titulo", $post->getTitulo(), PDO::PARAM_STR);
        $resultado->bindValue(":post", $post->getPost(), PDO::PARAM_STR);
        $resultado->bindValue(":dataPost", $post->getDataPost(), PDO::PARAM_STR);
        $resultado->bindValue(":ativo", $post->getAtivo(), PDO::PARAM_STR);
        $resultado->bindValue(":tags", $post->getTags(), PDO::PARAM_STR);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("post_lista");
    }
    
    public static function editar($idPost) {
        echo 'teste';
        $cache = Singleton::getInstancia()->getCache();

        if (!$postEdit = $cache->get("post")) {

            $pdo = Singleton::getInstancia()->getPdo();
            $sql = "SELECT idPost, post.idCategoria, titulo, post, dataPost, ativo, tags FROM post INNER JOIN categoria ON post.idCategoria = categoria.idCategoria WHERE post.idPost=:idPost";
            $resultado = $pdo->prepare($sql);
            $resultado->bindValue(":idPost", $idPost, PDO::PARAM_INT);
            $resultado->execute();
            
            if ($postBD = $resultado->fetch(PDO::FETCH_OBJ)) {
                $post = new Post();
                $post->setIdPost($postBD->idPost);
                $post->setIdCategoria($postBD->idCategoria);  //chamar funcao de editar e colocar o id da categoria
                $post->setAtivo($postBD->ativo);
                $post->setDataPost($postBD->dataPost);
                $post->setPost($postBD->post);
                $post->setTags($postBD->tags);
                $post->setTitulo($postBD->titulo);
                
                $cache->set("post", $postEdit);
                return $post;                
            } 
            
            return false;
        } 
    }
    
    public static function excluir($idPost) {
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM post WHERE idPost=:idPost";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idPost", $idPost, PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("post_{idPost}");

        return $resultado->rowCount() == 1;
    }
    
    public static function listar() {
        $cache = Singleton::getInstancia()->getCache();

        if (!$posts = $cache->get("post_lista")) {
        
            $pdo = Singleton::getInstancia()->getPdo();
            $sql = "SELECT idPost, idCategoria, titulo, post, dataPost, ativo, tags FROM post ORDER BY dataPost";
            $resultado = $pdo->prepare($sql);
            $resultado->execute();

            $posts = [];

            while ($postBD = $resultado->fetch(PDO::FETCH_OBJ)) {
                $post = new Post();
                $post->setIdPost($postBD->idPost);
                $post->setIdCategoria($postBD->idCategoria);
                $post->setAtivo($postBD->ativo);
                $post->setDataPost($postBD->dataPost);
                $post->setPost($postBD->post);
                $post->setTags($postBD->tags);
                $post->setTitulo($postBD->titulo);

                $posts[] = $post;
            }            
            
            $cache->set("post_lista", $posts);
        } 
        
        return $posts;        
    }
}
