<?php
include_once './Singleton.php';
include_once './Post.php';
include_once './PostService.php';
include_once './Categoria.php';
include_once './CategoriaService.php';

$post_new = new Post();

if ($_POST) {

    extract($_POST);
    
    $categoria = CategoriaService::obter($categoriaId);

    $post_new->setIdCategoria($categoria->getIdCategoria());
    
    $post_new->setIdPost($idPost);
    $post_new->setTitulo($titulo);
    $post_new->setPost($post_content);
    $post_new->setDataPost($dataPost);
    $post_new->setAtivo($ativo);
    $post_new->setTags($tags);
    
    PostService::salvar($post_new);

    header("location: PostList.php");
    exit();
}

if ($_GET) {

    if (isset($_GET["idPost"]) && isset($_GET["cmd"])) {

        extract($_GET);

        switch ($cmd) {
            case "U":

                if (!$post_new = PostService::obter($idPost)) {
                    header("location:PostList.php?mensagem=Post não encontrado: {$idPost}");
                    //exit();
                }
                break;

            case "D":

                if (PostService::excluir($idPost)) {
                    header("location:PostList.php");
                    exit();
                } else {
                    header("location:PostList.php?mensagem=Erro ao Excluir");
                    exit();
                }

                break;

            default :

                header("location:PostList.php");
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
            <input class="form-control" type="text" name="idPost" value="<?php echo $post_new->getIdPost() ?>" readonly>
        </div>
        <div class="col-md-8 form-group">
            <label>Categoria</label>                
            <select name="categoriaId" class="form-control">
                <?php
                foreach (CategoriaService::listar() as $categoria) {
                    $selected = $post_new->getIdCategoria() == $categoria->getIdCategoria() ? "selected" : "";
                    echo "<option value='{$categoria->getIdCategoria()}' {$selected}>{$categoria->getDescricao()}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group">
            <label>Título</label>
            <input class="form-control" type="text" name="titulo" value="<?php echo $post_new->getTitulo() ?>">
        </div>        
    </div>
    
    <div class="row">
        <div class="col-md-12 form-group">
            <label>Post</label>
            <input class="form-control" type="text" name="post_content" value="<?php echo $post_new->getPost() ?>">
        </div>        
    </div>
    
    <div class="row">
        <div class="col-md-12 form-group">
            <label>Tags</label>
            <input class="form-control" type="text" name="tags" value="<?php echo $post_new->getTags() ?>">
        </div>        
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Ativo</label>
            <input class="form-control" type="text" name="ativo" value="<?php echo $post_new->getAtivo() ?>">
        </div>
        <div class="col-md-6 form-group">
            <label>Data</label>
            <input class="form-control" type="text" name="dataPost" value="<?php echo $post_new->getDataPost() ?>">
        </div>        
    </div>


    <a class="btn btn-danger" href="PostList.php"> Cancelar </a>
    <button class="btn btn-success" type="submit"> Salvar </button>

</form>

<?php
include_once './Rodape.php';
?>