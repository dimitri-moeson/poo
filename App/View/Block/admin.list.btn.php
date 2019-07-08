<form method="post" action="?p=admin.<?php echo $p ?>.delete">
    <a title='Edit <?php echo $p ?>' href="?p=admin.<?php echo $p ?>.single&id=<?php echo $id ?>" class="btn btn-primary">
        <i class="glyphicon glyphicon-pencil"></i>
    </a>
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <button title='Supp <?php echo $p ?>' type="submit" class="btn btn-danger" value="Supp">
        <i  class="glyphicon glyphicon-trash"></i>
    </button>
</form>