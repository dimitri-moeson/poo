<h1>home</h1>
<div class="row">
    <div class="col-sm-8">
            <?php foreach ($posts as $post) { ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"><h2><a href="<?php echo $post->url ?>"><?php echo $post->titre ?></a></h2></div>
            </div>

            <div class="panel-body">

                        <em> <?php echo $post->cat_titre ?></em>
                        <p><?php echo $post->extrait ?></p>
            </div>
        </div>
            <?php } // endforeach ?>

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
