 <?php use App\Model\Entity\Game\Item\ItemEntity; ?>

 Statut : <?php echo $personnage->getStatus() ?><br/>
 <i data-placement="top" data-toggle="tooltip" class="glyphicon glyphicon-heart" title="Vie" ></i> : <?php echo $personnage->getVie() ?><br/>

<?php if (!is_null($personnage->getPosition())) { ?>
    <i data-placement="top" data-toggle="tooltip" class="glyphicon glyphicon-map-marker" title="Position" ></i>: [<?php echo $personnage->getPosition()->x ?>:<?php echo $personnage->getPosition()->y ?>]<br/>
<?php } ?>

<?php foreach ($personnage->getRessources()->getContainer() as $stat) { ?>
    <?php if ($stat instanceof ItemEntity) { ?>
        <i data-placement="top" data-toggle="tooltip" title="<?php echo $stat->getName() ?>" class='<?php echo $stat->getImg() ?>'></i> : <?php echo $stat->getVal() ?> sur <?php echo $stat->getMaxVal($personnage) ?>
        <br/>
    <?php } ?>
<?php } ?>

<?php foreach ($personnage->getStats()->getContainer() as $stat) { ?>
    <?php if ($stat instanceof ItemEntity) { ?>
        <i data-placement="top" data-toggle="tooltip" title="<?php echo $stat->getName() ?>" class='<?php echo $stat->getImg() ?>'></i> : <?php echo $stat->getVal() ?>
        <br/>
    <?php } ?>
<?php } ?>

