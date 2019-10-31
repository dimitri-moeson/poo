<?php

use Core\Render\Render;
use Core\Render\Url;
use Core\Session\FlashBuilder;

?>

<h1><?php echo isset($post) ? $post->titre : "Nouvel item" ?></h1>

<?php echo FlashBuilder::create()->get() ?>

<?php if(isset($post)){ ?>
    <ul class='nav nav-tabs nav-justified'>
        <li class='active'><a href='<?php echo Url::generate("single","Item","admin",$post->id);?>' >Params</a></li>
             <li class=''><a href='<?php echo Url::generate("descript","Item","admin",$post->id);?>' >Descript</a></li>
        <li class=''><a href='<?php echo Url::generate("icone","Item","admin",$post->id);?>' >Icone</a></li>
    </ul>
<?php } ?>

<div class="tab-content col-sm-12">


    <div class="col-sm-7 pull-right">

        <table id="item-list" class="table">

            <thead>

                <tr>
                    <th>Titre</th>
                    <th>Icone</th>
                    <th>
                        <a href="<?php echo Url::generate("add","item","admin" , $type  ) ?>" class="btn btn-success">Add</a>
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

    <form action="" method="post">
        <?php echo $form ?>
    </form>

</div>