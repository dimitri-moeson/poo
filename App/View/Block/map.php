<?php

use App\Model\Service\MapService;

$max_x = $x + ( $block == 'admin' ? 4 : 2);
$min_x = $x - ( $block == 'admin' ? 5: 2);

$max_y = $y + ( $block == 'admin' ? 4: 2);
$min_y = $y - ( $block == 'admin' ? 5: 2);

$size =  ( $block == 'admin' ? 1: 2);

$MapService = MapService::getInstance();
?>

<div class="row">

    <div class="col-xs-1"></div>

    <?php for ($a = $min_x; $a <= $max_x; $a++) { ?>

        <div class="col-xs-<?php echo $size ?> bg-danger text-center"><?php echo $a ?></div>

    <?php } ?>

</div>

<?php for ($b = $min_y; $b <= $max_y; $b++) { ?>

    <div class="row">

        <div class="col-xs-1 text-center"><?php echo $b ?></div>

        <?php for ($a = $min_x; $a <= $max_x; $a++) { ?>

            <div data-placement="top" data-toggle="tooltip" title="<?php echo "$a:$b" ?>"
                 class="col-xs-<?php echo $size ?> bg-success rounded text-center">
                <?php echo $block == 'admin' ? $MapService->adminPlace(($alentours[$a][$b] ?? null), $a, $b) : $MapService->place($alentours[$a][$b] ?? null) ; ?>
            </div>

        <?php } ?>

        <div class="col-xs-1 text-center"><?php echo $b ?></div>

    </div>

<?php } ?>

<div class="row">

    <div class="col-xs-1"></div>

    <?php for ($a = $min_x; $a <= $max_x; $a++) { ?>

        <div class="col-xs-<?php echo $size ?> bg-danger text-center"><?php echo $a ?></div>

    <?php } ?>

</div>
