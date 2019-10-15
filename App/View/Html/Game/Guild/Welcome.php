
<?php use Core\Render\Url; ?>

<?php if(!$has) { ?>

    <a href="<?php echo Url::generate("creer","guild") ?>">creer</a>

<?php } ?>