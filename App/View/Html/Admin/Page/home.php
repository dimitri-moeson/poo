<?php
use Core\Render\Render;
?>

        <table id="item-table" class="table">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>Titre</th>
                    <th>Categorie</th>
                    <th colspan="2">Position</th>
                    <th>
                        <a href="?p=admin.article.add" class="btn btn-success">Add</a>
                    </th>
                </tr>

            </thead>
            <tbody>
            <?php foreach ($posts as $post) { ?>

                <tr>
                    <td><?php echo $post->id ?></td>
                    <th><a href="<?php echo $post->url ?>"><?php echo $post->titre ?></a></th>
                    <td><?php echo $post->cat_titre ?></td>
                    <td><?php echo $post->position ?></td>
                    <td><?php echo Render::getInstance()->block("admin.position.btn", array(

                            "p" => "page",
                            "id" => $post->id

                        )); ?></td>
                    <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                            "p" => "page",
                            "id" => $post->id

                        )); ?></td>

                </tr>

            <?php } // endforeach ?>
            </tbody>
        </table>
