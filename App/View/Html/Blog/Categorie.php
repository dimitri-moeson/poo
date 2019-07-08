<h1><?php echo $categorie->nom ?></h1>
<div class="row">
    <div class="col-sm-8">

        <?php foreach ($posts as $post) { ?>

                <h2><a href="<?php echo $post->url ?>"><?php echo $post->titre ?></a></h2>
                <em> <?php echo $post->cat_titre ?></em>
                <p><?php echo $post->extrait ?></p>

        <?php } // endforeach ?>

        <p><a href="index.php">home</a></p>
    </div>

    <div class="col-sm-4">
        <ul>
            <?php foreach ($categories as $categorie) { ?>

                    <li><a href="<?php echo $categorie->url ?>"><?php echo $categorie->nom ?></a></li>

            <?php } // endforeach ?>
        </ul>

    </div>
</div>
