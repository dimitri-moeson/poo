<?php use Core\Render\Render; ?>

<?php foreach ( $sacoche as $item) { ?>

    <div class="col-sm-1">
    <?php echo Render::getInstance()->block("item.btn",array(

        "equip" => $item,
        "block" => "inventaire"

    ));  ?>
    </div>

<?php } ?>
