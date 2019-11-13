<?php
use Core\Render\Url;
?>

<h1><?php echo $player->login ?></h1>

<div class="row">

    <div class="col-sm-8">
        <?php echo $form ?>
    </div>

    <div class="col-sm-4 sidebar">
        <ul class="nav nav-sidebar">

            <li><a href="<?php echo Url::generate("pswd","account", "community"); ?>">Mot de passe</a></li>
            <li><a href="<?php echo Url::generate("mail","account", "community"); ?>">Email</a></li>
            <li><a href="<?php echo Url::generate("edit","account", "community"); ?>">Information</a></li>

        </ul>
    </div>

</div>
