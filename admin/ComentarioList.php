<?php

require_once 'autoload.php';
include_once './Cabecalho.php';

if (isset($_GET["mensagem"])) {
    echo $_GET["mensagem"] . "<br>";
}
?>

<a href="ComentarioForm.php" class="btn btn-success"> Novo Comentário </a>
<br><br>

<table class="table table-hover">
    <thead>
        <tr>
            <th class="col-md-1"> Id </th>
            <th class="col-md-1"> Post </th>
            <th class="col-md-2"> Nome </th>
            <th class="col-md-3"> Descrição </th>
            <th class="col-md-2"> Data </th>
            <th class="col-md-2"> Ações </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (ComentarioService::obterLista() as $comentario) {
            ?>
            <tr>
                <td> <?php echo $comentario->getIdComentario() ?> </td>
                <td> <?php echo $comentario->getIdPost() ?> </td>
                <td> <?php echo $comentario->getNome() ?> </td>
                <td> <?php echo $comentario->getDescricao() ?> </td>
                <td> <?php echo $comentario->getDataComentario() ?> </td>                
                <td>  
                    <a href="ComentarioForm.php?cmd=U&idComentario=<?php echo $comentario->getIdComentario() ?>" class="btn btn-warning"> Alterar </a>
                    <a href="ComentarioForm.php?cmd=D&idComentario=<?php echo $comentario->getIdComentario() ?>" class="btn btn-danger"> Excluir </a>
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