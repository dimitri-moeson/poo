<?php

use App\Model\Entity\Game\Item\ItemEntity;
use Core\Render\Render;
use Core\Render\Url;
use Core\Session\FlashBuilder;

?>

<div class="col-sm-12">

    <div class="col-sm-8">

        <h1><?php echo $post->name ?></h1>

        <p><?php echo $post->description ?></p>

        <hr/>

        <?php foreach ($posts as $post) { ?>

            <a class="btn btn-default"
               data-id="<?php echo $post->id ?>"
               data-toggle="tooltip"
               data-html="true"
               title="<?php echo $post->name ?><br/><?php echo $post->type ?>"
               href="<?php echo Url::generate("single","wiki", null, $post->id ) ?>">
                <i class="<?php echo $post->img ?>"></i>
            </a>

        <?php } // endforeach ?></div>

    <nav id="sidebar" class="col-sm-4">

        <ul class="nav nav-sidebar">


            <?php foreach (ItemEntity::type_arr as $cat => $items) { ?>
                <li>
                    <a href="#<?php echo $cat ?>Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><?php echo $cat ?><i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul class="<?php echo in_array($type ,$items) ? "in" : "collapse" ?>" id="<?php echo $cat ?>Submenu">
                        <?php foreach ($items as $k => $item) { ?>
                            <li>
                                <a class="" href="<?php echo Url::generate("index","wiki",null , $item ) ?>">
                                    <?php echo ucfirst($item) ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

            <?php } ?>

        </ul>
    </nav>

</div>