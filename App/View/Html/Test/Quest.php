<div class="row">
        <div class="col-md-4">
            <?php echo $PersonnageService->status($legolas); ?>
            <?php echo $InventaireService->listing("Inventaire",$legolas->getInventaire()); ?>
        </div>
    <div class="col-md-8">

        <?php echo ($viewText ?? '') ; ?>
<?php foreach ($questable as $quest) {?>
        <h2><?php echo $quest->getName()?></h2>

        <p><?php echo nl2br($quest->getDescription()); ?></p>

       <?php if( !$legolas->getQuestBook()->disponible($quest)  ) { ?>
        <form method="post">
            <input name="quest" type="hidden" value="<?php echo $quest->id ?>"/>
            <input name="accept" type="submit" value="Accepter"/>
        </form>
        <?php } ?>
<?php } ?>
    </div>

    <pre><?php var_dump($legolas->getQuestBook()) ?></pre>

</div>