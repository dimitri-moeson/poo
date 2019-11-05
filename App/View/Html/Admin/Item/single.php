<?php

use Core\Render\Render;
use Core\Render\Url;
use Core\Session\FlashBuilder;

?>

<h1><?php echo isset($post) ? $post->titre : "Nouvel item" ?></h1>

<?php echo FlashBuilder::create()->get() ?>

<?php echo Render::getInstance()->block("admin.itm.tabs", array(

    "p" => "single",
    "post" => $post
)); ?>

<div class="tab-content col-sm-12">


    <div class="col-sm-7 pull-right">

        <table id="item-list" class="table">

            <thead>

                <tr>
                    <th>Titre</th>
                    <th>Icone</th>
                    <th>
                        <a href="<?php echo Url::generate("add","item","admin" , $type  ) ?>" class="btn btn-success">Add</a>
                    </th>
                </tr>

            </thead>
            <tbody>

            <?php foreach ($posts as $post) { ?>

                <tr>
                    <th><a href="<?php echo $post->url ?>"><?php echo $post->name ?></a></th>
                    <td><i class="<?php echo $post->img ?>"></i></td>
                    <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                            "p" => "item",
                            "id" => $post->id

                        )); ?></td>
                </tr>

            <?php } // endforeach ?>

            </tbody>
        </table>
    </div>

    <form action="" method="post">
        <?php echo $form ?>
    </form>

</div>