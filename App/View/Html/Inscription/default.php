<h1>Inscription</h1>

<div class="row">

    <div class="col-sm-8">
        <?php echo $_SESSION['inscription']['user_id'] ?> - <?php echo $_SESSION['inscription']['perso_id'] ?>
        <hr/>
        <?php echo $form ?>
    </div>

    <div class="col-sm-4 sidebar">
        <ul class="nav nav-sidebar">

            <li class="active"><a href="#">Compte</a></li>
            <li><a href="#">Faction</a></li>
            <li><a href="#">classe</a></li>
            <li><a href="#">race</a></li>
            <li><a href="#">sexe</a></li>
            <li><a href="#">personnage</a></li>

        </ul>
    </div>

</div>
