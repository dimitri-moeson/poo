<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 09:48
 */

namespace Core\Controller;

use Core\Auth\CryptAuth;
use Core\Debugger\Debugger;
use Core\HTML\Template\Template;
use Core\Render\Render;

/**
 * Class Controller
 * @package Core\Controller
 */
class Controller
{
    public function render(Array $page = array() ){

        //echo "render::".print_r($page,1);
        Render::getInstance($page)->exec($this);
    }

    /**
     * @param $ref
     */
    public function notFound($ref = "content")
    {
        header('HTTP/1.0 404 Not Found');
        require_once ROOT.'/Core/HTML/Error/404.notFound.php';
        die("Introuvable : ".$ref);
    }

    /**
     *
     */
    public function forbidden(){

        header('HTTP/1.0 403 Forbidden' );
        require_once ROOT.'/Core/HTML/Error/403.forbidden.php';
        die("Interdit");
    }


}