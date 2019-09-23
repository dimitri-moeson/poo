<?php
    use Core\Config;
    use Core\Render\Url;
?>

<div class="row">
    <?php echo $form ?>
</div>
<?php if (isset($files) && is_array($files)) { ?>

    <div class="row">
<?php
        foreach ($files as $file)
        {
            if (!is_null($file))
            {
                if (file_exists(Config::VIEW_DIR . "/Assets/" . ucfirst($file->type . "s") . "/" . $file->src))
                {
?>
                    <img src="<?php echo Url::generate("index","img", "Src" )->setParams(array($file->nom)) ?>"/>
                    <br/><?php echo $file->src ; ?><br/>
<?php
                }
            }
        }
?>
    </div>

<?php } ?>
