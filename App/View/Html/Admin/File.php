<div class="row">
    <?php echo $form ?>
</div>
<?php if (isset($files) && is_array($files)) { ?>

    <div class="row">
        <?php foreach ($files as $file) { ?>

            <?php

            if (!is_null($file))
            {
                if (file_exists(ROOT . "/App/View/Assets/" . ucfirst($file->type . "s") . "/" . $file->src))
                {
                    ?><img src="?p=img.index&src=<?php echo $file->nom ?>"/><?php
                }
            }

            ?><br/>

        <?php } ?>
    </div>

<?php } ?>
