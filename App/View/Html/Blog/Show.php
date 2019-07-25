
<h1><?php echo $post->titre ?></h1>
<div class="row">
    <div class="col-sm-8">

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"> <h2><?php echo $categorie->titre ?></h2></div>
            </div>

            <div class="panel-body">

                <p><?php echo nl2br($post->contenu) ?></p>

            </div>
        </div>

        <p><a href="index.php">home</a></p>

    </div>

    <div class="col-sm-4">
        <ul>
            <?php foreach ($categories as $categorie) { ?>

                    <li><a href="<?php echo $categorie->url ?>"><?php echo $categorie->titre ?></a></li>

            <?php } // endforeach ?>
        </ul>

        <?php foreach ($clouds as $key) { ?>

            <a href="<?php echo $categorie->url ?>"><?php echo $key->mot ?></a>(<?php echo $key->called ?>)

        <?php } // endforeach ?>


    </div>
</div>