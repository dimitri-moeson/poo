<?php

use App\Model\Entity\Game\Item\ItemEntity;
use Core\Auth\DatabaseAuth;
use Core\HTML\Header\Header;
use Core\Render\Url;use Core\Request\Request;

$ctl_ = Request::getInstance()->getCtrl();
$act_ = Request::getInstance()->getAction();
$title = Header::getInstance()->getTitle();

$auth = new DatabaseAuth(App::getInstance()->getDb());

$blog_rub = $ctl_ === "article" || $ctl_ === "categorie";
$game_rub = $ctl_ === "personnage" || $ctl_ === "map"  || $ctl_ === "item";
$item_rub = $ctl_ === "item";
$sql_rub  = $ctl_ === "sql";
$file_rub  = $ctl_ === "file";

?><!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://getbootstrap.com/docs/3.3/dist/css/bootstrap.min.css" />

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"/>
    <!-- rpg awesome -->
    <link rel="stylesheet" href="https://nagoshiashumari.github.io/Rpg-Awesome/stylesheets/rpg-awesome.min.css"
          type="text/css" media="screen"/>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link rel="stylesheet" href="https://getbootstrap.com/docs/3.3/assets/css/ie10-viewport-bug-workaround.css" />

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="https://getbootstrap.com/docs/3.3/examples/starter-template/starter-template.css" />

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie-emulation-modes-warning.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script>tinymce.init({selector: 'textarea.editor'});</script>


    <?php echo Header::getInstance()->call_css(); ?>

    <title><?php echo Header::getInstance()->getTitle() ?></title>
    <meta name="description" content="<?php echo Header::getInstance()->getDescription() ?>" />
    <meta name="keywords" content="<?php echo Header::getInstance()->getKeywords() ?>" />
    <meta name="DC.Title" lang="fr" content="<?php echo Header::getInstance()->getTitle() ?>" />
    <meta name="DC.Date.created" scheme="W3CDTF" content="<?php echo Header::getInstance()->getCreated()->format("Y-m-d") ?>" />
    <meta name="DC.Date.modified" scheme="W3CDTF" content="<?php echo Header::getInstance()->getUpdated()->format("Y-m-d") ?>" />
    <meta name="robots" content="none" />

</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo Url::generate("fiche","test") ?>">Project</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">

            <?php if ($auth->logged()) { ?>
                <form method="post" action="<?php echo Url::generate("logout","user") ?>" class="navbar-form navbar-right">
                    <a href="<?php echo Url::generate("index","item","admin") ?>" class="btn btn-success"><i class="fa fa-cog"></i>Parametres</a>
                    <button type="submit" name="logout" value="logout" class="btn btn-success"><span
                                class="glyphicon glyphicon-off"></span>Sign out
                    </button>
                </form>

            <?php } else { ?>
                <form method="post" action="<?php echo Url::generate("login","user") ?>" class="navbar-form navbar-right">
                    <div class="form-group">
                        <input type="text" placeholder="login" name="login" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Password" name="pswd" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Sign in</button>
                </form>
            <?php } ?>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <div class="container-fluid">
        <h1><?php echo Header::getInstance()->getTitle() ?></h1>
        <div class="row">

            <div class="col-sm-8">
                <?php echo $content ?>
            </div>

            <nav id="sidebar" class="col-sm-4">

                <ul class="nav nav-sidebar">

                    <li>
                        <a href="#blogSubmenu" data-toggle="collapse" aria-expanded="<?php echo $blog_rub ? "true" : "false" ?>" class="dropdown-toggle">Blog<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                        <ul class="<?php echo $blog_rub ? "in" : "collapse" ?>" id="blogSubmenu">

                            <li><a href="<?php echo Url::generate("index","categorie","admin") ?>">Categorie</a></li>
                            <li><a href="<?php echo Url::generate("index","article","admin") ?>">Article</a></li>

                        </ul>
                    </li>

                    <li><a href="<?php echo Url::generate("index","page","admin") ?>">Page</a></li>

                    <li>
                        <a href="#gameSubmenu" data-toggle="collapse" aria-expanded="<?php echo $game_rub ? "true" : "false" ?>" class=" active dropdown-toggle">Game<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                        <ul class="<?php echo $game_rub ? "in" : "collapse" ?>" id="gameSubmenu">
                            <li><a href="<?php echo Url::generate("index","personnage","admin") ?>">Personnage</a></li>
                            <li><a class="active" href="<?php echo Url::generate("index","map","admin") ?>">Map</a></li>
                            <li><a href="#itmSubmenu" data-toggle="collapse" aria-expanded="<?php echo $item_rub ? "true" : "false" ?>" class="dropdown-toggle">Item<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                                <ul class="<?php echo $item_rub ? "in" : "collapse" ?>" id="itmSubmenu">
                                    <?php foreach (ItemEntity::type_arr as $cat => $items) { ?>

                                        <li>
                                            <a href="#<?php echo $cat ?>Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?php echo $cat ?><i class="fa fa-fw fa-angle-down pull-right"></i></a>
                                            <ul class="collapse" id="<?php echo $cat ?>Submenu">
                                                <?php foreach ($items as $k => $item) { ?>
                                                    <li>
                                                        <a class="" href="<?php echo Url::generate("index","item","admin" , $item ) ?>">
                                                            <?php echo ucfirst($item) ?></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>

                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li><a href="<?php echo Url::generate("index","user","admin") ?>">User</a></li>

                    <li>
                        <a href="#fileSubmenu" data-toggle="collapse" aria-expanded="<?php echo $file_rub ? "true" : "false" ?>" class="dropdown-toggle">Fichier<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                        <ul class="<?php echo $file_rub ? "in" : "collapse" ?>" id="fileSubmenu">
                            <li><a href="<?php echo Url::generate("picture","file","admin") ?>">Image</a></li>
                            <li><a href="<?php echo Url::generate("style","file","admin") ?>">Css</a></li>
                            <li><a href="<?php echo Url::generate("script","file","admin") ?>">Js</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#otherSubmenu" data-toggle="collapse" aria-expanded="<?php echo $sql_rub ? "true" : "false" ?>" class="dropdown-toggle">Base de données<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                        <ul class="<?php echo $sql_rub ? "in" : "collapse" ?>" id="otherSubmenu">
                            <li><a href="<?php echo Url::generate("index","sql","admin") ?>">Requête</a></li>
                            <li><a href="<?php echo Url::generate("save","sql","admin") ?>">Export</a></li>
                        </ul>
                    </li>

                </ul>
            </nav>

        </div>
    </div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<?php echo Header::getInstance()->call_js(); ?>


</body>
</html>
