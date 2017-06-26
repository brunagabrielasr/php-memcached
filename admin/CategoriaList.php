<?php

require_once 'autoload.php';
include_once './Cabecalho.php';

if (isset($_GET["mensagem"])) {
    echo $_GET["mensagem"] . "<br>";
}
?>

<a href="CategoriaForm.php" class="btn btn-success"> Nova Categoria </a>
<br><br>

<table class="table table-hover">
    <thead>
        <tr>
            <th class="col-md-2"> Id </th>
            <th class="col-md-8"> Descrição </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (CategoriaService::obterLista() as $categoria) {
            ?>
            <tr>
                <td> <?php echo $categoria->getIdCategoria() ?> </td>
                <td> <?php echo $categoria->getDescricao() ?> </td>
                <td>  
                    <a href="CategoriaForm.php?cmd=U&idCategoria=<?php echo $categoria->getIdCategoria() ?>" class="btn btn-warning"> Alterar </a>
                    <a href="CategoriaForm.php?cmd=D&idCategoria=<?php echo $categoria->getIdCategoria() ?>" class="btn btn-danger"> Excluir </a>
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