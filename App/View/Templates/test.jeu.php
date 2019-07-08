<?php

use Core\Auth\DatabaseAuth;
use Core\Request\Request;

$ctl_ = Request::getInstance()->getCtrl();
$act_ = Request::getInstance()->getAction();
    $title = App::getInstance()->getTitle();

    $auth = new DatabaseAuth(App::getInstance()->getDb());


?><!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://getbootstrap.com/docs/3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">


    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- rpg awesome -->
    <link rel="stylesheet" type="text/css" media="screen"
          href="https://nagoshiashumari.github.io/Rpg-Awesome/stylesheets/rpg-awesome.min.css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://getbootstrap.com/docs/3.3/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/3.3/examples/starter-template/starter-template.css" rel="stylesheet">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="https://getbootstrap.com/docs/3.3/assets/js/ie-emulation-modes-warning.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    <?php echo App::getInstance()->call_css(); ?>

    <title><?php echo $title ?></title>

</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="?p=test.fiche">Project</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?php echo $ctl_ == "article" ? 'class="active"' : '' ?> ><a href="?p=blog.article.index">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
<?php if($auth->logged()) {?>
            <form method="post" action="?p=user.logout" class="navbar-form navbar-right">
                <a href="?p=admin.item.index" class="btn btn-success"><i class="fa fa-cog"></i>Parametres</a>
                <button type="submit" name="logout" value="logout" class="btn btn-success"><i class="glyphicon glyphicon-off"></i>Sign out</button>
            </form>

<?php } else { ?>
            <form method="post" action="?p=user.login" class="navbar-form navbar-right">
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
        <div class="row">

            <br/>
            <div class="row">

                <ul class="nav nav-tabs">
                    <li role="presentation" <?php echo $act_ == "fiche" ? 'class="active"' : '' ?> >
                        <a href="?p=test.fiche"><i class="ra ra-aura"></i>&nbsp;Fiche</a>
                    </li>
                    <li role="presentation" <?php echo $act_ == "deplacer" ? 'class="active"' : '' ?> >
                        <a href="?p=test.deplacer"><i class="ra ra-compass"></i>&nbsp;Deplacer</a>
                    </li>
                    <li role="presentation" <?php echo $act_ == "equiper" ? 'class="active"' : '' ?> >
                        <a href="?p=test.equiper"><i class="ra ra-vest"></i>&nbsp;Equiper</a>
                    </li>
                    <li role="presentation" <?php echo $act_ == "sac" ? 'class="active"' : '' ?> >
                        <a href="?p=test.sac"><i class="ra ra-ammo-bag"></i>&nbsp;Sac</a>
                    </li>

                    <!--li role="presentation" <?php echo $act_ == "combat" ? 'class="active"' : '' ?> >
                        <a href="?p=test.combat"><i class="ra ra-crossed-swords"></i>&nbsp;Combat</a>
                    </li-->

                    <!--li role="presentation" <?php echo $act_ == "recolte" ? 'class="active"' : '' ?> >
                        <a href="?p=test.recolte"><i class="ra ra-sickle"></i>&nbsp;Recolte</a>
                    </li-->                    <!--li role="presentation" <?php echo $act_ == "apprentissage" ? 'class="active"' : '' ?> >
                        <a href="?p=test.apprentissage"><i class="ra ra-scroll-unfurled"></i>&nbsp;Apprentissage</a>
                    </li-->
                    <!--li role="presentation" <?php echo $act_ == "craft" ? 'class="active"' : '' ?> >
                        <a href="?p=test.craft"><i class="ra ra-anvil"></i>&nbsp;Craft</a>
                    </li-->
                    <!--li role="presentation" <?php echo $act_ == "quest" ? 'class="active"' : '' ?> >
                        <a href="?p=test.quest"><i class="ra ra-book"></i>&nbsp;Quetes</a>
                    </li-->
                </ul>

            </div>
            <br/>
            <div class="row">
                <?php echo $content ?>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<?php echo App::getInstance()->call_js(); ?>

</body>
</html>