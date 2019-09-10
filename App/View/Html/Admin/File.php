<div class="row">
    <?php use Core\Config;
    use Core\Render\Url;

    echo $form ?>
</div>
<?php if (isset($files) && is_array($files)) { ?>

    <div class="row">
        <?php foreach ($files as $file) { ?>

            <?php

            if (!is_null($file))
            {
                if (file_exists(Config::VIEW_DIR . "/Assets/" . ucfirst($file->type . "s") . "/" . $file->src))
                {
                    ?><img src="<?php echo Url::generate("index","img", $file->nom ) ?>"/><?php
                }
            }

            ?><br/>

        <?php } ?>
    </div>

<?php } ?>
