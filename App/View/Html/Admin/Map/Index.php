<?php


use Core\Render\Render;

$x = 0;
$y = 0;

$max_x = $x + 4;
$min_x = $x - 5;

$max_y = $y + 4;
$min_y = $y - 5;
?>

<div class="row">
    <div class="col-md-2"><?php
echo Render::getInstance()->block("boussole", array(

    "center" => "<div class='row' >t</div>"
));
        ?></div>

    <div class="col-md-10">
        <?php

echo Render::getInstance()->block("map",array(

    'x' => $x,
    'y' => $y,
    'alentours' => $alentours,
    'block' => 'admin',

));
        ?></div>
</div>