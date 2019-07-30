<?php
?>

<div class="panel panel-info">

    <div class="panel-heading">
        <div class="panel-title">Confirmer supression ?</div>
    </div>

    <div class="panel-body">

        <?php echo $p ?> <?php echo $id ?>
        <form method="post" action="?p=admin.<?php echo $p ?>.delete">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="conf" value="1"/>
            <input type="submit" class="btn btn-danger" value="Confirmer"/>
            <a href="?p=admin.<?php echo $p ?>.index" class="btn btn-warning">Annuler</a>
        </form>

    </div>
</div>

