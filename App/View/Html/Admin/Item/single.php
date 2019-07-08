<h1><?php use Core\Render\Render;

    echo isset($post) ? $post->titre : "Ajout item" ?></h1>

<?php if ($success) { ?>

    <div class="alert alert-success">
        enregistrement.
    </div>


<?php } ?>

<div class="col-sm-5">

        <form action="" method="post">

            <?php echo $form ?>

        </form>

</div>

<div class="col-sm-7">

    <table id="item-list" class="table">

        <thead>

        <tr>
            <th>Titre</th>
            <th>Icone</th>
            <th>
                <a href="?p=admin.item.add" class="btn btn-success">Add</a>
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