<?php use Core\Render\Render;

$x = 0;
$y = 0;

$max_x = $x + 4;
$min_x = $x - 5;

$max_y = $y + 4;
$min_y = $y - 5;
?>

<h1><?php echo "Ajout Carte" ?></h1>

<?php if ($success) { ?>

    <div class="alert alert-success">
        enregistrement.
    </div>


<?php } ?>

<div class="col-sm-4">

    <form action="" method="post">

        <?php echo $form ?>

    </form>

</div>

<div class="col-sm-8"><?php echo Render::getInstance()->block("map", array(

        'x' => $x,
        'y' => $y,
        'alentours' => $alentours,
        'block' => 'admin',

    )); ?></div>