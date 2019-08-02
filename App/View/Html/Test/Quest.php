<?php

use App\Model\Service\InventaireService;
use App\Model\Service\PersonnageService;

?><div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php echo $legolas->getName() ?></div>
            </div>

            <div class="panel-body">
                <?php if ($PersonnageService instanceof PersonnageService) echo $PersonnageService->status($legolas); ?>
            </div>
        </div>
        <div class="panel panel-info">

            <div class="panel-heading">
                <div class="panel-title"><?php echo $legolas->getName() ?></div>
            </div>

            <div class="panel-body">
                <?php if ($InventaireService instanceof InventaireService) echo $InventaireService->listing("Inventaire", $legolas->getInventaire()); ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">

        <?php echo($viewText ?? ''); ?>

        <?php foreach ($questable as $quest) { ?>
            <div class="panel panel-info">

                <div class="panel-heading">
                    <div class="panel-title"><?php echo $quest->getName() ?></div>
                </div>

                <div class="panel-body">
                    <p><?php echo nl2br($quest->getDescription()); ?></p>

                    <?php if (!$legolas->getQuestBook()->disponible($quest)) { ?>
                        <form method="post">
                            <input name="quest" type="hidden" value="<?php echo $quest->id ?>"/>
                            <input name="accept" type="submit" value="Accepter"/>
                        </form>
                    <?php } ?>

                </div>
            </div>
        <?php } ?>
    </div>

    <pre><?php var_dump($legolas->getQuestBook()) ?></pre>

</div>