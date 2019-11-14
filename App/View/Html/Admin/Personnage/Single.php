<h1><?php echo $post->titre ?></h1>

<?php if ($success) { ?>

    <div class="alert alert-success">
        enregistrement.
    </div>


<?php } ?>

<div class="row">

        <form action="" method="post">

            <?php echo $form ?>

        </form>

</div>