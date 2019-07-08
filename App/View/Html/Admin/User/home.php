<?php use Core\Auth\CryptAuth;
use Core\Render\Render; ?>

        <table id="item-table" class="table">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>login</th>
                    <th>pswd</th>
                    <th>
                        <a href="?p=admin.user.add" class="btn btn-success">Add</a>
                    </th>
                </tr>

            </thead>
            <tbody>
            <?php

            foreach ($posts as $post) { ?>

                    <tr>
                        <td><?php echo $post->id ?></td>
                        <th><?php echo $post->login ?></th>
                        <td><input type="text" value="<?php echo CryptAuth::getInstance($auth->getEncryptionKey())->decrypt($post->pswd) ?>" readonly /></td>
                        <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                                "p" => "user",
                                "id" => $post->id
                            )); ?></td>
                    </tr>

            <?php } // endforeach ?>
            </tbody>
        </table>
