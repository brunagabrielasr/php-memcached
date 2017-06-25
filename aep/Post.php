<?php

class Post {
    private $idPost;
    private $idCategoria;
    private $titulo;
    private $post;
    private $dataPost;
    private $ativo;
    private $tags;
    
    function getIdPost() {
        return $this->idPost;
    }

    function getIdCategoria() {
        return $this->idCategoria;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getPost() {
        return $this->post;
    }

    function getDataPost() {
        return $this->dataPost;
    }

    function getAtivo() {
        return $this->ativo;
    }

    function getTags() {
        return $this->tags;
    }

    function setIdPost($idPost) {
        $this->idPost = $idPost;
    }

    function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setPost($post) {
        $this->post = $post;
    }

    function setDataPost($dataPost) {
        $this->dataPost = $dataPost;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    function setTags($tags) {
        $this->tags = $tags;
    }
}
