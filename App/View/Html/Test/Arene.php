<div class="col-md-3">

    <div class="panel panel-info">

        <div class="panel-heading">
            <div class="panel-title">Equipement</div>
        </div>

        <div class="panel-body">
            <?php echo $EquipementService->equiped(); ?>
        </div>
    </div>

</div>


<div class="col-md-7">

    <div class="panel panel-info">

        <div class="panel-heading">
            <div class="panel-title"><?php echo ($viewText ?? '') ; ?></div>
        </div>

        <div class="panel-body">
            <?php foreach ($sacoche as $craftable) { ?>

                <div class="col-sm-1">
                    <?php echo \Core\Render\Render::getInstance()->block("item.btn", array(

                        "equip" => $craftable,
                        "block" => "arene",
                        "ItemService" => $ItemService,
                    )) ;  ?>
                </div>

            <?php } ?>


        </div>
    </div>

</div>