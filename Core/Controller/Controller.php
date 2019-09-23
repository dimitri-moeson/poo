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
     * @param $ref
     */
    public function notFound($ref = "content")
    {
        header('HTTP/1.0 404 Not Found');
        require_once Config::CORE_DIR.'/HTML/Error/404.notFound.php';
        if (DEBUG)
        {
            Debugger::getInstance()->view();
        }
        die("Introuvable : ".$ref);
    }

    /**
     *
     */
    public function forbidden($ref = "content"){

        header('HTTP/1.0 403 Forbidden' );
        require_once Config::CORE_DIR.'/HTML/Error/403.forbidden.php';
        if (DEBUG)
        {
            Debugger::getInstance()->view();
        }
        die("Interdit: ".$ref);
    }


}