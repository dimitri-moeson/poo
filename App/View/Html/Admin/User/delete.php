<?php   use Core\Render\Render;
use Core\Render\Url; ?>
<div class="col-sm-4">
    <?php

    echo Render::getInstance()->block("admin.del.form", array(

        "p" => "user",
        "id" => $post->id

    )); ?>
</div>

<div class="col-sm-7">

    <table id="user-list" class="table">

        <thead>

        <tr>
            <th>id</th>
            <th>login</th>
            <th>
                <a href="<?php echo Url::generate("add","user","admin") ?>" class="btn btn-success">Add</a>
            </th>
        </tr>

        </thead>
        <tbody>
        <?php foreach ($posts as $post) { ?>

            <tr>
                <td><?php echo $post->id ?></td>
                <th><?php echo $post->login ?></th>
                <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                        "p" => "user",
                        "id" => $post->id
                    )); ?></td>
            </tr>

        <?php } // endforeach ?>
        </tbody>
    </table>
</div>