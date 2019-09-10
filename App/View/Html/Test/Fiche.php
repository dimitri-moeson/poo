<?php

use App\Model\Service\EquipementService;
use App\Model\Service\InventaireService;
use App\Model\Service\PersonnageService;
use Core\HTML\Env\Get;
use Core\Render\Render;
use Core\Render\Url;


$_place = Get::getInstance()->val('place');

?>

<div class="row">

    <div class="col-md-3">

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title">Equipement
                    <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-equip" aria-expanded="true" aria-controls="collapse-equip">-</button>
                </div>
            </div>

            <div id="collapse-equip" class="panel-body collapse">
                <?php  if($EquipementService instanceof EquipementService) echo $EquipementService->equiped($legolas); ?>
            </div>
        </div>

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php echo $legolas->getName() ?>
                    <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-stat" aria-expanded="true" aria-controls="collapse-stat">-</button>
                </div>
            </div>

            <div id="collapse-stat" class="panel-body collapse">
                <?php  if($PersonnageService instanceof PersonnageService) echo $PersonnageService->status($legolas); ?></div>
        </div>

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"></div>
            </div>

            <div class="panel-body">

                <li><a href="<?php echo Url::generate("recolte","test") ?>"><i class="ra ra-sickle"></i>&nbsp;Recolte</a></li>

            </div>
        </div>

    </div>

    <div class="col-md-7">

        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <div class="panel-title">Carte
                            <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-map" aria-expanded="true" aria-controls="collapse-map">-</button>
                        </div>
                    </div>

                    <div id="collapse-map" class="panel-body collapse">

                        <div class="col-md-3"><?php echo Render::getInstance()->block("boussole", array(

                            "center" => '<i class="fa fa-male"></i>',
                        )) ?></div>

                        <div class="col-md-9">
                        <?php echo Render::getInstance()->block("map", array(

                            'x' => $legolas->getPosition()->x,
                            'y' =>  $legolas->getPosition()->y,
                            'alentours' => $alentours,
                            'block' => 'game',
                            'MapService' => $MapService ,

                        )); ?></div>

                    </div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-4">

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Grimoire
                            <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-spells" aria-expanded="true" aria-controls="collapse-spells">-</button>
                        </div>
                    </div>

                    <div id="collapse-spells" class="panel-body collapse"><?php if($InventaireService instanceof InventaireService) echo $InventaireService->listing("grimoire", $legolas->getSpellBook()); ?> </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Recette
                            <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-craft" aria-expanded="true" aria-controls="collapse-craft">-</button>
                        </div>
                    </div>

                    <div id="collapse-craft" class="panel-body collapse"> <?php if($InventaireService instanceof InventaireService) echo $InventaireService->listing("recette", $legolas->getKnows()); ?> </div>

                </div>

            </div>

            <div class="col-md-8">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Sac - [<?php echo $_place ?>]
                            <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-sac" aria-expanded="true" aria-controls="collapse-sac">-</button>
                        </div>
                    </div>

                    <div id="collapse-sac" class="panel-body collapse" <?php echo (!empty(trim($_place)) && !is_null($_place) ? 'aria-expanded="true"' : '') ?>">

                        <div class="col-md-8 ">
                            <?php if($InventaireService instanceof InventaireService) echo $InventaireService->table("inventaire", $sacoche); ?>
                        </div>
                        <div class="col-md-4 sidebar">

                            <ul class="nav nav-sidebar">

                                <?php foreach ($legolas->getEquipement()->getContainer() as $place => $osef) { ?>

                                    <li role="presentation" <?php echo $_place == $place ? 'class="active"' : '' ?> >
                                        <a href="<?php echo Url::generate("fiche","test")->setParams(array("place" => $place)) ?>"><?php echo $place ?></a>
                                    </li>

                                <?php } ?>

                                <li role="presentation" <?php echo $_place == "consommable" ? 'class="active"' : '' ?> >
                                    <a href="<?php echo Url::generate("fiche","test")->setParams(array("place" => "consommable")) ?>">consommable</a>
                                </li>

                                <li role="presentation" <?php echo $_place == "composant" ? 'class="active"' : '' ?> >
                                    <a href="<?php echo Url::generate("fiche","test")->setParams(array("place" => "composant")) ?>">composant</a>
                                </li>

                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <?php echo($viewText ?? ''); ?>

    </div>



</div>

