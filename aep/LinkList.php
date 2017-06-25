<?php
include_once './Singleton.php';
include_once './Link.php';
include_once './LinkService.php';

include_once './Cabecalho.php';

if (isset($_GET["mensagem"])) {
    echo $_GET["mensagem"] . "<br>";
}
?>

<a href="LinkForm.php" class="btn btn-success"> Novo Link </a>
<br><br>

<table class="table table-hover">
    <thead>
        <tr>
            <th class="col-md-2"> Id </th>
            <th class="col-md-4"> Titulo </th>
            <th class="col-md-4"> URL </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (LinkService::listar() as $link) {
            ?>
            <tr>
                <td> <?php echo $link->getIdLink() ?> </td>
                <td> <?php echo $link->getTitulo() ?> </td>
                <td> <?php echo $link->getUrl() ?> </td>
                <td>  
                    <a href="LinkForm.php?cmd=U&idLink=<?php echo $link->getIdLink() ?>" class="btn btn-warning"> Alterar </a>
                    <a href="LinkForm.php?cmd=D&idLink=<?php echo $link->getIdLink() ?>" class="btn btn-danger"> Excluir </a>
                </td>
            </tr>
            <?php
        }
        ?>

    </tbody>
</table>


<?php
include_once './Rodape.php';
?>