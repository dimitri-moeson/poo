<?php

use App\Model\Service\EquipementService;
use App\Model\Service\InventaireService;
use App\Model\Service\PersonnageService;
use Core\HTML\Env\Get;
use Core\Render\Link;
use Core\Render\Render;
use Core\Render\Url;
use Core\Request\Request;

$_place = Get::getInstance()->val('place');
$slug = Request::getInstance()->getSlug();

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
                <div class="panel-title"><?php echo $legolas->getName() ?? "Personnage" ?>
                    <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-stat" aria-expanded="true" aria-controls="collapse-stat">-</button>
                </div>
            </div>

            <div id="collapse-stat" class="panel-body collapse">
                <?php  if($PersonnageService instanceof PersonnageService) echo $PersonnageService->status($legolas); ?></div>
        </div>

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title">Comp&eacute;tence
                    <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-skill" aria-expanded="true" aria-controls="collapse-skill">-</button>
                </div>
            </div>

            <div id="collapse-skill" class="panel-body">

                <li><a href="<?php echo Url::generate("recolte","skill","game") ?>"><i class="ra ra-sickle"></i>&nbsp;Recolte</a></li>

                <li><?php echo new Link( Url::generate("recolte","skill","game") , "recolte" ) ?></li>

            </div>
        </div>

    </div>

    <div class="col-md-7">

        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <div class="panel-title">Carte
                            <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-map" <?php echo $slug=="move" ? 'aria-expanded="true"' : '' ?> aria-controls="collapse-map">-</button>
                        </div>
                    </div>

                    <div id="collapse-map" class="panel-body collapse <?php echo $slug=="move" ? 'in' : '' ?>">

                        <div class="col-md-3"><?php echo Render::getInstance()->block("boussole", array(

                                "center" => '<i class="fa fa-male"></i>',
                            )) ?>
                            <hr/>
                            <?php if (!is_null($legolas->getPosition())) { ?>
                                <i data-placement="top" data-toggle="tooltip" class="glyphicon glyphicon-map-marker" title="Position" ></i>: [<?php echo $legolas->getPosition()->x ?>:<?php echo $legolas->getPosition()->y ?>]<br/>
                            <?php } ?>
                        </div>

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
                            <button class="btn btn-link pull-right" data-toggle="collapse" data-target="#collapse-sac" <?php echo (!empty(trim($_place)) && !is_null($_place) ? 'aria-expanded="true"' : '') ?> aria-controls="collapse-sac">-</button>
                        </div>
                    </div>

                    <div id="collapse-sac" class="panel-body collapse <?php echo (!empty(trim($_place)) && !is_null($_place) ? 'in' : '') ?>" <?php //echo (!empty(trim($_place)) && !is_null($_place) ? 'aria-expanded="true"' : '') ?> >

                    <div class="col-md-8 ">
                        <?php if($InventaireService instanceof InventaireService) echo $InventaireService->table("inventaire", $sacoche); ?>
                    </div>
                    <div class="col-md-4 sidebar">

                        <ul class="nav nav-sidebar">

                            <?php foreach ($legolas->getEquipement()->getContainer() as $place => $osef) { ?>

                                <li role="presentation" <?php echo $_place == $place ? 'class="active"' : '' ?> >
                                    <a href="<?php echo Url::generate("index","default","game")->setParams(array("place" => $place)) ?>"><?php echo $place ?></a>
                                </li>

                            <?php } ?>

                            <li role="presentation" <?php echo $_place == "consommable" ? 'class="active"' : '' ?> >
                                <a href="<?php echo Url::generate("index","default","game")->setParams(array("place" => "consommable")) ?>">consommable</a>
                            </li>

                            <li role="presentation" <?php echo $_place == "composant" ? 'class="active"' : '' ?> >
                                <a href="<?php echo Url::generate("index","default","game")->setParams(array("place" => "composant")) ?>">composant</a>
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

