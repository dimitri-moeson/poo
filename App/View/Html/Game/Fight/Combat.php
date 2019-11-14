<?php

use App\Model\Entity\Game\Combat\Defi;
use App\Model\Entity\Game\Item\ItemEntity;
use App\Model\Entity\Game\Personnage\PersonnageEntity;
use App\Model\Entity\Journal;
use Core\HTML\Env\Post;
use Core\Render\Url;

if ($defi instanceof Defi) {

    $joueur = $defi->getFight()->current()->current()->getPerso();

    $suivi = "";

    if ($defi->count() > 1) {

        $suivi = "Round : " . $defi->getFight()->count() . " - Passe : " . ($defi->getFight()->current()->key() + 1) . "/" . $defi->getFight()->current()->count();
    }
    ?>
    <div class="row">

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

            <div class="row">

                <div class="col-md-5">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title"><?php echo $suivi ?></div>
                        </div>

                        <div class="panel-body"><?php

                            echo Journal::getInstance()->view();
                            echo $CombatService->resume();

                        ?></div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title"><?php echo $joueur->getName() ?></div>
                        </div>

                        <div class="panel-body">

                                <?php if ($defi->count() > 1) { ?>

                            <form method="post" action="<?php echo Url::generate("deroule","fight","game") ?>">
                                <input type="hidden" name="action" value="combat"/>
                                <button type="submit"><i class="ra ra-sword"></i></button>
                            </form>

                            <form method="post" action="<?php echo Url::generate("fuite","fight","game") ?>">
                                <input type="hidden" name="action" value="fuite"/>
                                <button type="submit"><i class="ra ra-player-lift"></i></button>
                            </form>

                                <?php } elseif (Post::getInstance()->has('bilan')) { ?>

                            <form method="post" action="<?php echo Url::generate("bilan","fight","game") ?>">
                                <input type="submit" name="bilan" value="Victoire"/>
                                <button type="submit"><i class="ra ra-player-lift"></i></button>
                            </form>

                                <?php } ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-4">

                    <div class="panel panel-info">

                        <div class="panel-heading">
                            <div class="panel-title"><?php echo $legolas->getName() ?></div>
                        </div>

                        <div class="panel-body">
                            <?php echo $PersonnageService->status($legolas); ?></div>
                    </div>

                </div>
                    <?php foreach ($defi->participants() as $x => $pe) {
                        if ($pe->id != $legolas->id) { ?>
                    <div class="col-md-4">

                            <div class="panel panel-info">

                                <div class="panel-heading">
                                    <div class="panel-title"><?php echo $pe->getName() ?></div>
                                </div>

                                <div class="panel-body">


                                        <?php if($legolas->id === $joueur->id) {?>
                                        <form class="pull-right" method="post" action="<?php echo Url::generate("attaque","fight","game") ?>">
                                            <input type="hidden" name="rank" value="<?php echo $x ?>"/>
                                            <input type="hidden" name="cible" value="<?php echo $pe->id ?>"/>
                                            <input type="hidden" name="action" value="attaque"/>
                                            <button type="submit"><i class="ra ra-crossed-swords"></i></button>
                                        </form>
                                        <?php } ?>

                                    <?php


                                    if($pe instanceof PersonnageEntity )
                                        echo $PersonnageService->status($pe);

                                    if($pe instanceof ItemEntity )
                                        echo $MonstreService->status($pe);

                                    ?>
                                </div>
                            </div>
                    </div>

                        <?php }
                    } ?>
            </div>

            <?php echo($viewText ?? ''); ?>


        </div>

        <div class="col-md-2">

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Grimoire</div>
                </div>

                <div class="panel-body"><?php echo $InventaireService->listing("grimoire", $legolas->getSpellBook()); ?> </div>
            </div>

            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="panel-title">Sac</div>
                </div>

                <div class="panel-body"><?php echo $InventaireService->table("inventaire", $sacoche); ?> </div>
            </div>


        </div>

    </div>

    <?php

}