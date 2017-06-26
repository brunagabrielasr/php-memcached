<?php
include_once './Singleton.php';
include_once './Link.php';
include_once './LinkService.php';

$link = new Link();

if ($_POST) {

    //$id = isset($_POST["id"]) ? $_POST["id"] : false;
    //$nome = isset($_POST["nome"]) ? $_POST["nome"] : false;

    extract($_POST);

    $link->setIdLink($idLink);
    $link->setTitulo($titulo);
    $link->setUrl($url);
    LinkService::salvar($link);

    header("location: LinkList.php");
    exit();
}

if ($_GET) {

    if (isset($_GET["idLink"]) && isset($_GET["cmd"])) {

        extract($_GET);

        switch ($cmd) {
            case "U":

                if (!$link = LinkService::obter($idLink)) {
                    header("location:LinkList.php");
                    exit();
                }

                break;

            case "D":

                if (LinkService::excluir($idLink)) {
                    header("location:LinkList.php");
                    exit();
                } else {
                    header("location:LinkList.php?mensagem=Erro ao Excluir");
                    exit();
                }

                break;

            default :

                header("location:LinkList.php");
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
            <input class="form-control" type="text" name="idLink" value="<?php echo $link->getIdLink() ?>" readonly>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 form-group">
            <label>Titulo</label>
            <input class="form-control" type="text" name="titulo" value="<?php echo $link->getTitulo() ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 form-group">
            <label>URL</label>
            <input class="form-control" type="text" name="url" value="<?php echo $link->getUrl() ?>">
        </div>
    </div>

    <a class="btn btn-danger" href="LinkList.php"> Cancelar </a>
    <button class="btn btn-success" type="submit"> Salvar </button>

</form>

<?php
include_once './Rodape.php';
?>