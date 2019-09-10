<?php

use App\Model\Entity\Blog\CategorieEntity;
use Core\Render\Render;
use Core\Render\Url;

?>
<div class="row">
    <div class="col-sm-7">
        <?php echo Render::getInstance()->block("admin.del.form", array(

            "p" => "categorie",
            "id" => $post->id

        )); ?>
    </div>


    <div class="col-sm-5">

        <table id="item-list" class="table">

            <thead>

            <tr>
                <th>Titre</th>
                <th>
                    <a href="<?php echo Url::generate("add","categorie","admin") ?>" class="btn btn-success">Add</a>
                </th>
            </tr>

            </thead>
            <tbody>
            <?php foreach ($categories as $post) { ?>

                <?php if ($post instanceof ArticleEntity) { ?>
                    <tr>
                        <th><a href="<?php echo $post->url ?>"><?php echo $post->nom ?></a></th>
                        <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                                "p" => "categorie",
                                "id" => $post->id

                            )); ?></td>
                    </tr>
                <?php } ?>

            <?php } // endforeach ?>
            </tbody>
        </table>
    </div>
</div>