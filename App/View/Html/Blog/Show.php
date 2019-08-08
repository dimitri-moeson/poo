
<h1><?php echo $post->titre ?></h1>
<div class="row">
    <div class="col-sm-8">

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"> <h2><?php echo $categorie->titre ?></h2></div>
            </div>

            <div class="panel-body">

                <i><?php echo nl2br($post->description) ?></i>
                <hr/>
                <p><?php echo nl2br($post->contenu) ?></p>

            </div>
        </div>

        <p><a href="index.php">home</a></p>

        <p><?php echo $form ?></p>

        <hr/>
        <?php foreach ($comments as $comment) { ?>

            <li><?php echo $comment->contenu ?></li>

        <?php } // endforeach ?>

    </div>

    <div class="col-sm-4">
        <ul>
            <?php foreach ($categories as $categorie) { ?>

                    <li><a href="<?php echo $categorie->url ?>"><?php echo $categorie->titre ?></a></li>

            <?php } // endforeach ?>
        </ul>
        <p>
        <?php foreach ($clouds as $key) { ?>

            <a style="font-size:<?php echo ($key->called+1)*6 ?>px" href="?p=blog.article.keyword&id=<?php echo $key->id ?>">[<?php echo trim($key->mot) ?>]</a>

        <?php } // endforeach ?>
        </p>

    </div>
</div>