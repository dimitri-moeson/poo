<?php

use App\Model\Service\EquipementService;
use App\Model\Service\InventaireService;
use App\Model\Service\PersonnageService;
use Core\HTML\Env\Get;
use Core\Render\Render;

    $_place = Get::getInstance()->val('place');

?>

<div class="row">

    <div class="col-md-3">

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title">Equipement</div>
            </div>

            <div class="panel-body">
                <?php  if($EquipementService instanceof EquipementService) echo $EquipementService->equiped($legolas); ?>
            </div>
        </div>

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php echo $legolas->getName() ?></div>
            </div>

            <div class="panel-body">
                <?php  if($PersonnageService instanceof PersonnageService) echo $PersonnageService->status($legolas); ?></div>
        </div>

        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"></div>
            </div>

            <div class="panel-body">

                <li><a href="?p=test.recolte"><i class="ra ra-sickle"></i>&nbsp;Recolte</a></li>

            </div>
        </div>

    </div>

    <div class="col-md-7">

        <div class="row">

            <div class="col-md-3">


                <div class="panel panel-info">

                    <div class="panel-heading">
                        <div class="panel-title">Bousole</div>
                    </div>

                    <div class="panel-body">

                        <?php echo Render::getInstance()->block("boussole", array(

                                "center" => '<i class="fa fa-male"></i>',
                        )) ?>
                    </div>
                </div>

            </div>

            <div class="col-md-9">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <div class="panel-title">Carte</div>
                    </div>

                    <div class="panel-body"><?php echo Render::getInstance()->block("map", array(

                            'x' => $legolas->getPosition()->x,
                            'y' =>  $legolas->getPosition()->y,
                            'alentours' => $alentours,
                            'block' => 'game',
                            'MapService' => $MapService ,

                        )); ?></div>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Grimoire</div>
                    </div>

                    <div class="panel-body"><?php if($InventaireService instanceof InventaireService) echo $InventaireService->listing("grimoire", $legolas->getSpellBook()); ?> </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Sac</div>
                    </div>

                    <div class="panel-body"><?php if($InventaireService instanceof InventaireService) echo $InventaireService->table("inventaire", $sacoche); ?> </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Recette</div>
                    </div>

                    <div class="panel-body"> <?php if($InventaireService instanceof InventaireService) echo $InventaireService->listing("recette", $legolas->getKnows()); ?> </div>

                </div>
            </div>

        </div>

        <?php echo($viewText ?? ''); ?>

    </div>

    <div class="col-md-2 sidebar">

        <ul class="nav nav-sidebar">

            <?php foreach ($legolas->getEquipement()->getContainer() as $place => $osef) { ?>
                <li role="presentation" <?php echo $_place == $place ? 'class="active"' : '' ?> >
                    <a href="?p=test.fiche&place=<?php echo $place ?>"><?php echo $place ?></a>
                </li>

            <?php } ?>

            <li role="presentation" <?php echo $_place == "consommable" ? 'class="active"' : '' ?> >
                <a href="?p=test.fiche&place=consommable">consommable</a>
            </li>

            <li role="presentation" <?php echo $_place == "composant" ? 'class="active"' : '' ?> >
                <a href="?p=test.fiche&place=composant">composant</a>
            </li>

        </ul>

    </div>

</div>

