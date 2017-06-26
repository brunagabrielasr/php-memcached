<?php
include_once './Singleton.php';
include_once './Post.php';
include_once './PostService.php';
include_once './Comentario.php';
include_once './ComentarioService.php';

$comentario = new Comentario();

if ($_POST) {

    extract($_POST);
    
    $post = PostService::obter($postId);

    $comentario->setIdPost($post->getIdPost());
    $comentario->setIdComentario($idComentario);
    $comentario->setDescricao($descricao);
    $comentario->setNome($nome);
    $comentario->setDataComentario($dataComentario);
    
    ComentarioService::salvar($comentario);

    header("location: ComentarioList.php");
    exit();
}

if ($_GET) {

    if (isset($_GET["idComentario"]) && isset($_GET["cmd"])) {

        extract($_GET);

        switch ($cmd) {
            case "U":

                if (!$comentario = ComentarioService::obter($idComentario)) {
                    header("location:ComentarioList.php");
                    exit();
                }

                break;

            case "D":

                if (ComentarioService::excluir($idComentario)) {
                    header("location:ComentarioList.php");
                    exit();
                } else {
                    header("location:ComentarioList.php?mensagem=Erro ao Excluir");
                    exit();
                }

                break;

            default :

                header("location:ComentarioList.php");
                exit();
        }
    }
}
?>

<?php
include_once './Cabecalho.php';
?>

<form action="" method="POST">

    <div class="row">
        <div class="col-md-4 form-group">
            <label>ID</label>
            <input class="form-control" type="text" name="idComentario" value="<?php echo $comentario->getIdComentario() ?>" readonly>
        </div>
        <div class="col-md-8 form-group">
            <label>Post</label>                
            <select name="postId" class="form-control">
                <?php
                foreach (PostService::listar() as $post) {
                    $selected = ($comentario->getIdPost() == $post->getIdPost()) ? "selected" : "";
                    echo "<option value='{$post->getIdPost()}' {$selected}>{$post->getTitulo()}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group">
            <label>Nome</label>
            <input class="form-control" type="text" name="nome" value="<?php echo $comentario->getNome() ?>">
        </div>        
    </div>
    
    <div class="row">
        <div class="col-md-12 form-group">
            <label>Descrição</label>
            <input class="form-control" type="text" name="descricao" value="<?php echo $comentario->getDescricao() ?>">
        </div>        
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Data</label>
            <input class="form-control" type="text" name="dataComentario" value="<?php echo $comentario->getDataComentario() ?>">
        </div>        
    </div>


    <a class="btn btn-danger" href="ComentarioList.php"> Cancelar </a>
    <button class="btn btn-success" type="submit"> Salvar </button>

</form>

<?php
include_once './Rodape.php';
?>