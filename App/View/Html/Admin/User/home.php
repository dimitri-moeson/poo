<?php use Core\Auth\CryptAuth;
use Core\Render\Render;
use Core\Render\Url; ?>

        <table id="item-table" class="table">

            <thead>

                <tr>
                    <th>Id</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Pswd</th>
                    <th>
                        <a href="<?php echo Url::generate("add","user","admin") ?>" class="btn btn-success">Add</a>
                    </th>
                </tr>

            </thead>
            <tbody>
            <?php

            foreach ($posts as $post) { ?>

                    <tr>
                        <td><?php echo $post->id ?></td>
                        <th><?php echo $post->login ?></th>
                        <th><?php echo $post->mail ?></th>
                        <td><input type="text" value="<?php echo CryptAuth::getInstance($auth->getEncryptionKey())->decrypt($post->pswd) ?>" readonly /></td>
                        <td><?php echo Render::getInstance()->block("admin.list.btn", array(

                                "p" => "user",
                                "id" => $post->id
                            )); ?></td>
                    </tr>

            <?php } // endforeach ?>
            </tbody>
        </table>
