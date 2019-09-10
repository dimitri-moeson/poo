<?php
use Core\Render\Render;
use Core\Render\Url;
use Core\Session\FlashBuilder;
?>

<h1><?php  echo isset($post) ? $post->titre : "blog - Ajout" ?></h1>

<?php if ($success) { ?>

    <div class="alert alert-success">
        enregistrement.
    </div>


<?php } ?>

<?php echo FlashBuilder::create()->get() ?>

<div class="col-sm-7">

    <form action="" method="post">

        <?php echo $form ?>

    </form>

</div>

<div class="col-sm-5">

    <table id="item-list" class="table">

        <thead>

        <tr>
            <th>Titre</th>
            <th>
                <a href="<?php echo Url::generate("add","article","admin") ?>" class="btn btn-success">Add</a>
            </th>
        </tr>

        </thead>
        <tbody>
        <?php foreach ($posts as $post) { ?>

            <tr>
                <th><a href="<?php echo $post->url ?>"><?php echo $post->titre ?></a></th>
                <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                        "p" => "article",
                        "id" => $post->id

                    )); ?></td>
            </tr>

        <?php } // endforeach ?>
        </tbody>
    </table>
</div>