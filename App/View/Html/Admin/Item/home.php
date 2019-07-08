<?php
use Core\Render\Render;
?>

        <table id="item-table" class="table">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>Titre</th>
                    <th>Icone</th>
                    <th>Type</th>
                    <th>Objet</th>
                    <th>
                        <a href="?p=admin.item.add" class="btn btn-success">Add</a>
                    </th>
                </tr>

            </thead>
            <tbody>
            <?php foreach ($posts as $post) { ?>

                    <tr>
                        <td><?php echo $post->id ?></td>
                        <th><a href="<?php echo $post->url ?>"><?php echo $post->name ?></a></th>
                        <td><i class="<?php echo $post->img ?>"></i></td>
                        <td><?php echo $post->type ?></td>
                        <td><?php echo $post->objet ?></td>
                        <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                                "p" => "item",
                                "id" => $post->id
                            )); ?></td>
                    </tr>

            <?php } // endforeach ?>
            </tbody>
        </table>
