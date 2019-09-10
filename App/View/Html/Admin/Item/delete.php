<?php   use Core\Render\Render;
use Core\Render\Url; ?>
<div class="col-sm-4">
    <?php

    echo Render::getInstance()->block("admin.del.form", array(

        "p" => "item",
        "id" => $post->id

    )); ?>
</div>

<div class="col-sm-7">

    <table id="item-list" class="table">

        <thead>

        <tr>
            <th>Titre</th>
            <th>Icone</th>
            <th>
                <a href="<?php echo Url::generate("add","item","admin") ?>" class="btn btn-success">Add</a>
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