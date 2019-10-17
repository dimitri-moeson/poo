<?php
use Core\Render\Render;
use Core\Render\Url;
use Core\Session\FlashBuilder;
?>

<h1><?php  echo isset($post) ? $post->titre : "blog - Ajout" ?></h1>

<?php echo FlashBuilder::create()->get() ?>

<?php if(isset($post)){ ?>

<ul class='nav nav-tabs nav-justified'>
    <li class='active'><a href='<?php echo Url::generate("single","article","admin",$post->id);?>' >Params</a></li>
    <li class=''><a href='<?php echo Url::generate("content","article","admin",$post->id);?>' >Content</a></li>
</ul>

<?php } ?>

<div class="tab-content col-sm-12">


    <div class="col-sm-5 pull-right">
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
    <div class="col-sm-7 pull-left">
        <form action="" method="post">

            <?php echo $form ?>

        </form>
    </div>


</div>