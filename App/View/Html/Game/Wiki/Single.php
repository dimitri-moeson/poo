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

        <?php if(isset($linked)) { ?>

            <table class="table">

            <?php foreach($linked as $typ => $links) { ?>

                <tr>
                    <th colspan="3"><h2><?php echo $typ ?></h2></th>
                </tr>
                <tr>
                    <th>child</th>
                    <th>val</th>
                    <th>caract</th>
                </tr>

            <?php foreach($links as $x => $link) { ?>

                    <tr>
                        <td><?php echo $Item->find($link->child_id)->name; ?></td>
                        <td><?php echo $link->val ?></td>
                        <td><?php echo $link->caract ?></td>
                    </tr>

                    <?php /**if( in_array($post->type , ItemEntity::type_arr["aventure"]) ) { ?>

                    <?php } elseif( in_array($post->type , ItemEntity::type_arr["politique"]) ) { ?>

                    <?php } elseif( in_array($post->type , ItemEntity::type_arr["classe"]) ) { ?>

                    <?php } elseif( in_array($post->type , ItemEntity::type_arr["faune"]) ) { ?>

                    <?php } elseif( in_array($post->type , ItemEntity::type_arr["arme_1_main"]) ) { ?>

                    <?php } elseif( in_array($post->type , ItemEntity::type_arr["arme_2_main"]) ) { ?>

                    <?php } elseif( in_array($post->type , ItemEntity::type_arr["equipement"]) ) { ?>

                    <?php } elseif( in_array($post->type , ItemEntity::type_arr["batiment"]) ) { ?>

                    <?php }**/ ?>

                <?php } ?>

            <?php } ?>
            </table>

        <?php } ?>

        <hr/>

        <?php foreach ($posts as $post) { ?>

            <a class="btn btn-default"
               data-id="<?php echo $post->id ?>"
               data-toggle="tooltip"
               data-html="true"
               title="<?php echo $post->name ?><br/><?php echo $post->type ?>"
               href="<?php echo Url::generate("single","wiki", "game", $post->id ) ?>">
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
                                <a class="" href="<?php echo Url::generate("index","wiki","game" , $item ) ?>">
                                    <?php echo ucfirst($item) ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

            <?php } ?>

        </ul>
    </nav>

</div>