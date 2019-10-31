<?php

use Core\Render\Render;
use Core\Render\Url;
use Core\Session\FlashBuilder;

?>

<h1><?php echo isset($post) ? $post->titre : "Nouvel item" ?></h1>

<?php echo FlashBuilder::create()->get() ?>

<ul class='nav nav-tabs nav-justified'>
          <li class=''><a href='<?php echo Url::generate("single","Item","admin",$post->id);?>' >Params</a></li>
    <li class='active'><a href='<?php echo Url::generate("descript","Item","admin",$post->id);?>' >Descript</a></li>
    <li class=''><a href='<?php echo Url::generate("icone","Item","admin",$post->id);?>' >Icone</a></li>

</ul>

<div class="tab-content col-sm-12">

    <form action="" method="post">
        <?php echo $form ?>
    </form>

</div>