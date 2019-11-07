<?php

use App\View\Form\ItemForm;
use Core\HTML\Form\Form;
use Core\Render\Render;
use Core\Render\Url;
use Core\Request\Request;
use Core\Session\FlashBuilder;

?>

<h1><?php echo isset($post) ? $post->name : "Nouvel item" ?></h1>

<?php echo FlashBuilder::create()->get() ?>
<?php echo Render::getInstance()->block("admin.itm.tabs", array(

    "p" => Request::getInstance()->getAction(),
    "post" => $post
)); ?>


<div class="tab-content col-sm-12">

    <?php

    foreach ($forms as $form)
    {
        echo "<h2>".$form["label"]."</h2>";
        echo $form["form"] ;
    }
    ?>
</div>