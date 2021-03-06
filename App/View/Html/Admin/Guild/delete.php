<?php
use Core\Render\Render;
use Core\Render\Url;

?>
<div class="col-sm-4">
    <?php echo Render::getInstance()->block("admin.del.form", array(

        "p" => "guild",
        "id" => $post->id

    )); ?>
</div>

<div class="col-sm-8">

    <table id="guild-list" class="table">

        <thead>

            <tr>
                <th>Id</th>
                <th>Login</th>
            </tr>

        </thead>
        <tbody>
        <?php foreach ($posts as $post) { ?>

            <tr>
                <td><?php echo $post->id ?></td>
                <th><?php echo $post->name ?></th>
                <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                        "p" => "guild",
                        "id" => $post->id
                    )); ?></td>
            </tr>

        <?php } // endforeach ?>
        </tbody>
    </table>

</div>