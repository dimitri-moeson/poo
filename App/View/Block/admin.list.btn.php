<?php
use Core\Render\Url;

$urldel = Url::generate("delete",$p,"admin");
$urlEdi = Url::generate("single",$p,"admin")->setParams(array('id' => $id ));
?>


<form method="post" action="?p=<?php echo $urldel ?>">
    <a title='Edit <?php echo $p ?>' href="?p=<?php echo $urlEdi ?>" class="btn btn-primary">
        <i class="glyphicon glyphicon-pencil"></i>
    </a>
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <button title='Supp <?php echo $p ?>' type="submit" class="btn btn-danger" value="Supp">
        <i  class="glyphicon glyphicon-trash"></i>
    </button>
</form>