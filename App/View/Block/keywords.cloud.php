<?php use Core\Render\Url; ?>


    <p>

<?php foreach ($clouds as $key) { ?>

    <a style="font-size:<?php echo ($key->called+1)*6 ?>px"
       href="<?php echo Url::generate("keywords", "article","blog", $key->mot ) ?>"
    >[<?php echo trim($key->mot) ?>]</a>

<?php } // endforeach ?>

    </p>
