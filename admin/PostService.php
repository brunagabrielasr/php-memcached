<?php

class PostService {
    
    /**
     * @param Post $post
     */
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
        
        $resultado->bindValue(":idCategoria", $post->getIdCategoria(), PDO::PARAM_INT);
        $resultado->bindValue(":titulo", $post->getTitulo(), PDO::PARAM_STR);
        $resultado->bindValue(":post", $post->getPost(), PDO::PARAM_STR);
        $resultado->bindValue(":dataPost", $post->getDataPost(), PDO::PARAM_STR);
        $resultado->bindValue(":ativo", $post->getAtivo(), PDO::PARAM_STR);
        $resultado->bindValue(":tags", $post->getTags(), PDO::PARAM_STR);
        $resultado->execute();
        
        $post->setIdPost($pdo->lastInsertId());
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->set('post_'.$post->getIdPost(), $post);
        $cache->delete("post_lista");
    }
    
    
    /**
     * @param int $idPost
     * @return Post
     * @throws InvalidArgumentException
     */
    public static function obter($idPost) {
        $cache      = Singleton::getInstancia()->getCache();
        $cacheKey   = "post_{$idPost}";
        $fromCache  = $cache->get($cacheKey);
        
        if ($fromCache) {
            return $fromCache;
        }

        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "SELECT idPost, post.idCategoria, titulo, post, dataPost, ativo, tags FROM post WHERE post.idPost=:idPost";
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

            $cache->set($cacheKey, $post);
            return $post;
        }

        throw new InvalidArgumentException('Post não encontrado: ' . (int)$idPost);
    }
    
    /**
     * @param int $idPost
     * @return bool
     */
    public static function excluir($idPost) {
        $pdo = Singleton::getInstancia()->getPdo();
        $sql = "DELETE FROM post WHERE idPost=:idPost";
        $resultado = $pdo->prepare($sql);
        $resultado->bindValue(":idPost", $idPost, PDO::PARAM_INT);
        $resultado->execute();
        
        $cache = Singleton::getInstancia()->getCache();
        $cache->delete("post_{$idPost}");
        $cache->delete("post_lista");

        return $resultado->rowCount() == 1;
    }
    
    /**
     * @return array
     */
    public static function obterLista() {
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
