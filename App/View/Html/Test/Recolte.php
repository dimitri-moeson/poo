

<div class="row">

    <div class="col-md-4">
        <?php echo $PersonnageService->status($legolas); ?>
        <?php echo $InventaireService->listing("Inventaire",$legolas->getInventaire()); ?>
    </div>

    <div class="col-md-8">
        <?php echo ($viewText ?? '') ; ?>

        <form method="post" action="">

                <input type="submit" name="recolte" value="Ramasse">

        </form>

    </div>

</div>


