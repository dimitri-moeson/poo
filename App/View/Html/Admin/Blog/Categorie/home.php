<?php

use App\Model\Entity\Blog\ArticleEntity;
use App\Model\Entity\Blog\CategorieEntity;
use Core\Render\Render;


?>
<h1>Categrie Admin</h1>

        <table id="item-table" class="table">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>Titre</th>
                    <th>
                        <a href="?p=admin.categorie.add" class="btn btn-success">Add</a>
                    </th>
                </tr>

            </thead>
            <tbody>
            <?php foreach ($categories as $post) { ?>

                <?php if ($post instanceof CategorieEntity) { ?>
                    <tr>
                        <td><?php echo $post->id ?></td>
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
