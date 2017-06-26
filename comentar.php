<?php
require_once __DIR__.'/admin/autoload.php';

if ($_POST) {
    
    extract($_POST);
    
    try {
        $post = PostService::obter($idPost);
    } catch (Exception $ex) {
        //@todo
    }
    
    //$comentario->setIdComentario($idComentario);
    $comentario = new Comentario();
    $comentario->setIdPost($post->getIdPost());
    $comentario->setDescricao($descricao);
    $comentario->setDataComentario(date('Y-m-d H:i:s', time()));
    $comentario->setNome('Nome do user logado');
    
    ComentarioService::salvar($comentario);
}
header("location: index.php?postId=".$idPost);