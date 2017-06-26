<?php

class Comentario {
    private $idComentario;
    private $idPost;
    private $nome;
    private $descricao;
    private $dataComentario;
    
    function getIdComentario() {
        return $this->idComentario;
    }

    function getIdPost() {
        return $this->idPost;
    }

    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getDataComentario() {
        return $this->dataComentario;
    }

    function setIdComentario($idComentario) {
        $this->idComentario = $idComentario;
    }

    function setIdPost($idPost) {
        $this->idPost = $idPost;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setDataComentario($dataComentario) {
        $this->dataComentario = $dataComentario;
    }
}
