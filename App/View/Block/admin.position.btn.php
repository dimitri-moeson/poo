<form method="post" class="btn-group" action="?p=admin.<?php echo $p ?>.position">


    <button name="pos-mov" title='remonter <?php echo $p ?>' type="submit" value="<?php echo $id ?>|+" class="btn btn-success">
        <i  class="glyphicon glyphicon-arrow-up"></i>
    </button>
    <button name="pos-mov" title='descendre <?php echo $p ?>' type="submit" value="<?php echo $id ?>|-" class="btn btn-info">
        <i  class="glyphicon glyphicon-arrow-down"></i>
    </button>

</form>