<h1><?php use Core\Render\Url;

    echo $categorie->nom ?></h1>
<div class="row">
    <div class="col-sm-8">

        <?php foreach ($posts as $post) { ?>

                <h2><a href="<?php echo $post->url ?>"><?php echo $post->titre ?></a></h2>
                <em> <?php echo $post->cat_titre ?></em>
                <p><?php echo $post->description ?></p>

        <?php } // endforeach ?>

        <p><a href="/">home</a></p>
    </div>

    <div class="col-sm-4 sidebar">
        <ul class="nav nav-sidebar">
            <?php foreach ($categories as $categorie) { ?>

                    <li><a href="<?php echo $categorie->url ?>"><?php echo $categorie->titre ?></a></li>

            <?php } // endforeach ?>
        </ul>

        <?php foreach ($clouds as $key) { ?>

            <a style="font-size:<?php echo ($key->called+1)*6 ?>px"
               href="<?php echo Url::generate("keyword", "article","blog", $key->id ) ?>">[<?php echo trim($key->mot) ?>]</a>

        <?php } // endforeach ?>

    </div>
</div>
