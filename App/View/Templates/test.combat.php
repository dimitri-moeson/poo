<?php


use Core\Auth\DatabaseAuth;
use Core\HTML\Header\Header;
use Core\Render\Url;
use Core\Request\Request ;
use Core\Session\FlashBuilder;

$ctl_ = Request::getInstance()->getCtrl();
$act_ = Request::getInstance()->getAction();
$title = Header::getInstance()->getTitle();

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

    <?php echo Header::getInstance()->call_css(); ?>

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
            <a class="navbar-brand" href="/">Project</a>
        </div>

<?php if($auth->logged()) {?>
            <form method="post" action="<?php echo Url::generate("logout","user", "community") ?>" class="navbar-form navbar-right">
                <a href="<?php echo Url::generate("index","item","admin") ?>" class="btn btn-success">
                    <i class="fa fa-cog"></i>Parametres</a>
                <button type="submit" name="logout" value="logout" class="btn btn-success"><span class="glyphicon glyphicon-off"></span>Sign out</button>
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
        <div class="row">
            <br/>
            <?php echo FlashBuilder::create()->get() ?>
            <div class="row">
                <?php echo $content ?>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<?php echo Header::getInstance()->call_js(); ?>

</body>
</html>