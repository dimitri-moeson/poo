<?php

use Core\Render\Url;

$urldel = Url::generate("delete",$p,"admin");
$urlAbo = Url::generate("index",$p,"admin");
?>

<div class="panel panel-info">

    <div class="panel-heading">
        <div class="panel-title">Confirmer supression ?</div>
    </div>

    <div class="panel-body">

        <p><?php echo $p ?> - <?php echo $id ?></p>
        <form method="post" action="<?php echo $urldel ?>">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="conf" value="1"/>
            <input type="submit" class="btn btn-danger" value="Confirmer"/>
            <a href="<?php echo $urlAbo ?>" class="btn btn-warning">Annuler</a>
        </form>

    </div>
</div>

