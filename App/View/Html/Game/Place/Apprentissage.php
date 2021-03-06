<div class="row">

    <div class="col-md-4">
        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php use Core\Render\Render;

                    echo $legolas->getName() ?></div>
            </div>

            <div class="panel-body">
                <?php echo $PersonnageService->status($legolas); ?></div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Recette</div>
            </div>

            <div class="panel-body"><?php  echo $InventaireService->listing("Recette", $legolas->getKnows()); ?></div>
        </div>

    </div>

    <div class="col-md-8">
        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php echo ($viewText ?? '') ; ?></div>
            </div>

            <div class="panel-body">
                <?php foreach ($craftables as $craftable) { ?>

                    <div class="col-sm-1">
                        <?php echo Render::getInstance()->block("item.btn", array(

                            "equip" => $craftable,
                            "block" => "apprentissage",
                            "ItemService" => $ItemService,
                            "known" => $legolas->getKnows()->offsetExists($craftable->id)
                        )) ;  ?>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>

</div>