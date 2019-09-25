<?php use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Entity\UserEntity;
use Core\Render\Url;
use Core\Session\FlashBuilder; ?>

<h1>Inscription</h1>

<div class="row">

    <div class="col-sm-8">
        <?php echo FlashBuilder::create()->get() ?>
        <?php echo $_SESSION['inscription']['user_id'] ?? "" ?> - <?php echo $_SESSION['inscription']['perso_id'] ?? "" ?>
        <hr/>
        <?php echo $form ?>

        <!--form method="post" action="<?php echo Url::generate("login","user") ?>" class="navbar-form navbar-right">
            <div class="form-group">
                <input type="text" placeholder="login" name="login" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" name="pswd" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
        </form-->
    </div>

    <div class="col-sm-4 sidebar">
        <ul class="nav nav-sidebar">

            <li><a href="<?php echo Url::generate("login","Inscription") ?>">Compte  <?php if($player && $player instanceof UserEntity ) echo $player->login ?></a></li>
            <li><a href="<?php echo Url::generate("faction","Inscription") ?>">Faction <?php if($legolas && $legolas instanceof PersonnageEntity ) echo $legolas->faction->name ?></a></li>
            <li><a href="<?php echo Url::generate("classe","Inscription") ?>">classe <?php if($legolas && $legolas instanceof PersonnageEntity ) echo $legolas->classe->name ?></a></li>
            <li><a href="<?php echo Url::generate("race","Inscription") ?>">race <?php if($legolas && $legolas instanceof PersonnageEntity ) echo $legolas->race->name ?></a></li>
            <li><a href="<?php echo Url::generate("sexe","Inscription") ?>">sexe <?php if($legolas && $legolas instanceof PersonnageEntity ) echo ( $legolas->sexe == 1 ? "homme" : ($legolas->sexe == 2 ? "femme" : "scion")) ?></a></li>
            <li><a href="<?php echo Url::generate("personnage","Inscription") ?>">personnage <?php if($legolas && $legolas instanceof PersonnageEntity ) echo $legolas->name ?></a></li>

        </ul>
    </div>

</div>
