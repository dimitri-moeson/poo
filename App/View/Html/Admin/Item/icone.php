<?php

use Core\Render\Render;
use Core\Render\Url;
use Core\Session\FlashBuilder;

?>

    <h1><?php echo isset($post) ? $post->titre : "Nouvel item" ?></h1>

<?php echo FlashBuilder::create()->get() ?>
<?php echo Render::getInstance()->block("admin.itm.tabs", array(

    "p" => "icone",
    "post" => $post
)); ?>

    <div class="tab-content col-sm-12">

    <form action="" method="post">
        <?php echo $form ?>
    </form>

    </div><?php
