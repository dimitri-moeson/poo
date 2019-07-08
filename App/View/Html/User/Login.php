<h1>home</h1>
<div class="row">
    <div class="col-sm-8">

        <?php if ($error) { ?>

            <div class="alert alert-danger">
                Identifiants incorrect.
            </div>

        <?php } ?>

        <form method="post">

            <?php echo $form->input("login", array('label' => "Nom")) ?>
            <?php echo $form->input("pswd", array('type' => 'password', 'label' => "Mot de passe")); ?>
            <?php echo $form->submit() ?>

        </form>
    </div>

    <div class="col-sm-4">
        <ul>
            <?php foreach ($categories as $categorie) { ?>

                <?php if ($categorie instanceof CategorieEntity) { ?>

                    <li><a href="<?php echo $categorie->url ?>"><?php echo $categorie->nom ?></a></li>

                <?php } ?>

            <?php } // endforeach ?>
        </ul>

    </div>
</div>
