<?php

use Core\Render\Render;
use Core\Render\Url;

?>
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

        <p><a href="/">home</a></p>


        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title"> <h3>Commentaires</h3></div>
            </div>

            <div class="panel-body">

                <p><?php echo $form ?></p>

                <hr/>
                <?php foreach ($comments as $comment) { ?>

            <li><?php echo $comment->contenu ?></li>

        <?php } // endforeach ?>

            </div>
        </div>
    </div>

    <div class="col-sm-4 sidebar">
        <ul class="nav nav-sidebar">
            <?php foreach ($categories as $categorie) { ?>

                    <li><a href="<?php echo $categorie->url ?>"><?php echo $categorie->titre ?></a></li>

            <?php } // endforeach ?>
        </ul>
            <?php echo Render::getInstance()->block("keywords.cloud", array(

                "clouds" => $clouds ,
            )) ?>

    </div>
</div>