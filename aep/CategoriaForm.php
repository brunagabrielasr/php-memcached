<?php
include_once './Singleton.php';
include_once './Categoria.php';
include_once './CategoriaService.php';

$categoria = new Categoria();

if ($_POST) {

    //$id = isset($_POST["id"]) ? $_POST["id"] : false;
    //$nome = isset($_POST["nome"]) ? $_POST["nome"] : false;

    extract($_POST);

    $categoria->setIdCategoria($idCategoria);
    $categoria->setDescricao($descricao);
    CategoriaService::salvar($categoria);

    header("location: CategoriaList.php");
    exit();
}

if ($_GET) {

    if (isset($_GET["idCategoria"]) && isset($_GET["cmd"])) {

        extract($_GET);

        switch ($cmd) {
            case "U":

                if (!$categoria = CategoriaService::editar($idCategoria)) {
                    header("location:CategoriaList.php");
                    exit();
                }

                break;

            case "D":

                if (CategoriaService::excluir($idCategoria)) {
                    header("location:CategoriaList.php");
                    exit();
                } else {
                    header("location:CategoriaList.php?mensagem=Erro ao Excluir");
                    exit();
                }

                break;

            default :

                header("location:CategoriaList.php");
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
        <div class="col-md-2 form-group">
            <label>ID</label>
            <input class="form-control" type="text" name="idCategoria" value="<?php echo $categoria->getIdCategoria() ?>" readonly>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 form-group">
            <label>Nome</label>
            <input class="form-control" type="text" name="descricao" value="<?php echo $categoria->getDescricao() ?>">
        </div>        
    </div>


    <a class="btn btn-danger" href="CategoriaList.php"> Cancelar </a>
    <button class="btn btn-success" type="submit"> Salvar </button>

</form>

<?php
include_once './Rodape.php';
?>