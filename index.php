<?php
require_once __DIR__.'/admin/autoload.php';

$postId         = isset($_GET['postId']) && $_GET['postId'] ? $_GET['postId'] : null;
$post           = $postId ? PostService::obter($postId) : null;
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Post - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/blog-post.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Start Bootstrap</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <?php if ($post) { ?>

                <!-- Blog Post Content Column -->
                <div class="col-lg-8">
                    <?php
                        echo "<h1>{$post->getTitulo()}</h1>";
                        echo "<hr>";
                        echo "<p><span class='glyphicon glyphicon-time'></span> {$post->getDataPost()} </p>";
                        echo "<hr>";
                        echo "<p class='lead'>{$post->getPost()}</p>";
                        echo "<hr>";
                    ?>
                    <!-- Comments Form -->
                    <div class="well">
                        <h4>Leave a Comment:</h4>
                        <form role="form" action="comentar.php" method="POST">
                            <div class="form-group">
                                <textarea class="form-control" name="descricao" rows="3"></textarea>
                            </div>
                            <input type="hidden" name="idPost" value="<?php echo $post->getIdPost();?>">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                    <hr>

                    <!-- Posted Comments -->

                    <?php $comentarios = ComentarioService::obterPorPost($post->getIdPost()); ?>
                    <?php if ($comentarios) { ?>
                        <?php foreach ($comentarios as $comentario) { ?>
                            <!-- Comment -->
                            <div class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object" src="http://placehold.it/64x64" alt="">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><?php echo $comentario->getNome(); ?>
                                        <small><?php echo $comentario->getDataComentario(); ?></small>
                                    </h4>
                                    <?php echo $comentario->getDescricao(); ?>
                                </div>
                            </div>
                            <!-- Comment -->
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Posts</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <?php
                                    foreach (PostService::obterLista() as $post) {
                                        echo '<li><a href="index.php?postId='.$post->getIdPost().'">'.$post->getTitulo().'</a></li>';
                                    }
                                ?>
                            </ul>
                        </div>

                    </div>
                    <!-- /.row -->
                </div>                

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <?php
                                    foreach (CategoriaService::obterLista() as $categoria) {
                                        echo "<li><a href='#'>{$categoria->getDescricao()}</a></li>";
                                    }
                                ?>
                            </ul>
                        </div>

                    </div>
                    <!-- /.row -->
                </div>
                
            </div>
        </div>
        <!-- /.row -->
        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
