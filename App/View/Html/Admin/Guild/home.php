<?php

use Core\Auth\CryptAuth;
use Core\Render\Render;
use Core\Render\Url;

?>

        <table id="item-table" class="table">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>nom</th>
                    <th>action</th>
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
