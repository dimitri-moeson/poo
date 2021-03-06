<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 09:48
 */

namespace Core\Controller;

use Core\Config;
use Core\Debugger\Debugger;
use Core\HTML\Template\Template;

/**
 * Class Controller
 * @package Core\Controller
 */
class Controller
{
    /**
     * @param string $ref
     */
    public function notFound(?string $ref = "content")
    {
        header('HTTP/1.0 404 Not Found');

        echo Config::VIEW_DIR.'/Html/Error/404.notFound.php';

        if(file_exists(Config::VIEW_DIR.'/Html/Error/404.notFound.php')) {
            require_once Config::VIEW_DIR . '/Html/Error/404.notFound.php';
        }
        else {
            require_once Config::CORE_DIR . '/HTML/Error/404.notFound.php';
        }

        if (DEBUG)
        {
            Debugger::getInstance()->view();
        }
        die("Introuvable : ".$ref);
    }

    /**
     * @param string $ref
     */
    public function forbidden(?string $ref = "content"){

        header('HTTP/1.0 403 Forbidden' );

        echo Config::VIEW_DIR.'/Html/Error/403.forbidden.php';

        if(file_exists(Config::VIEW_DIR.'/Html/Error/403.forbidden.php')) {
            require_once Config::VIEW_DIR . '/Html/Error/403.forbidden.php';
        }
        else{
            require_once Config::CORE_DIR . '/HTML/Error/403.forbidden.php';
        }

        if (DEBUG)
        {
            Debugger::getInstance()->view();
        }
        die("Interdit: ".$ref);
    }


}