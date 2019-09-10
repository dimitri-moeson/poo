<?php

define("DEBUG", true );
define("REWRITE", true );

define("ROOT", dirname(__DIR__));

require_once ROOT . '/App/App.php';
App::getInstance()->load()->exec();

die();
echo "test";
?>