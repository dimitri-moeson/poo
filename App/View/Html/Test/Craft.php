

<div class="row">

    <div class="col-md-4">

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php echo $legolas->getName() ?></div>
            </div>

            <div class="panel-body">
                <?php echo $PersonnageService->status($legolas); ?></div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Sac</div>
            </div>

            <div class="panel-body"><?php echo $InventaireService->table("inventaire", $sacoche); ?> </div>
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
            <?php echo \Core\Render\Render::getInstance()->block("item.btn", array(

                "equip" => $craftable,
                "block" => "fabrique",
                "ItemService" => $ItemService
        )) ;  ?>
        </div>

        <?php } ?>


            </div>
        </div>
    </div>

</div>


