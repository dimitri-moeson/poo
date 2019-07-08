
<div class="row">
Confirmer ?

<form method="post" action="?p=post.delete">
    <input type="hidden" name="id" value="<?php echo $post->id ?>">
    <input type="hidden" name="conf">
    <input type="submit" class="btn btn-danger" value="Confirmer"/>
    <a href="admin.php" class="btn btn-warning">cancel</a>
</form>


</div>