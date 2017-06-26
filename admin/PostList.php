<?php
require_once 'autoload.php';
include_once './Cabecalho.php';

if (isset($_GET["mensagem"])) {
    echo $_GET["mensagem"] . "<br>";
}
?>

<a href="PostForm.php" class="btn btn-success"> Novo Post </a>
<br><br>

<table class="table table-hover table-responsive" style="overflow-wrap: break-word">
    <thead>
        <tr>
            <th class="col-md-1"> Id </th>
            <th class="col-md-1"> Categoria </th>
            <th class="col-md-2"> Titulo </th>
            <th class="col-md-4"> Post </th>
            <th class="col-md-1"> Data </th>
            <th class="col-md-2"> Ativo </th>
            <th class="col-md-1"> Tags </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (PostService::obterLista() as $post) {
            ?>
            <tr>
                <td> <?php echo $post->getIdPost() ?> </td>
                <td> <?php echo $post->getIdCategoria() ?> </td>
                <td> <?php echo $post->getTitulo() ?> </td>
                <td> <?php echo $post->getPost() ?> </td>
                <td> <?php echo $post->getDataPost() ?> </td>
                <td> <?php echo $post->getAtivo() ?> </td>
                <td> <?php echo $post->getTags() ?> </td>
                <td>  
                    <a href="PostForm.php?cmd=U&idPost=<?php echo $post->getIdPost() ?>" class="btn btn-warning"> Alterar </a>
                    <a href="PostForm.php?cmd=D&idPost=<?php echo $post->getIdPost() ?>" class="btn btn-danger"> Excluir </a>
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