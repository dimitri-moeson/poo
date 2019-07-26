<?php

use Core\Render\Render;

?>
<div class="row">
    <div class="col-sm-4">
        <?php echo Render::getInstance()->block("admin.del.form", array(

            "p" => "page",
            "id" => $post->id

        )); ?>
    </div>

    <div class="col-sm-8">

        <table id="item-list" class="table">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>Titre</th>
                    <th>Icone</th>
                    <th>
                        <a href="?p=admin.page.add" class="btn btn-success">Add</a>
                    </th>
                </tr>

            </thead>
            <tbody>
            <?php foreach ($posts as $post) { ?>

                <tr>
                    <td><?php echo $post->id ?></td>
                    <th><a href="<?php echo $post->url ?>"><?php echo $post->nom ?></a></th>
                    <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                            "p" => "page",
                            "id" => $post->id

                        )); ?></td>
                </tr>

            <?php } // endforeach ?>
            </tbody>
        </table>
    </div>
</div>