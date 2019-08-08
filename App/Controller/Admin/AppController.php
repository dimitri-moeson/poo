<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */
namespace App\Controller\Admin;

use App;
use Core\Auth\DatabaseAuth;
use Core\HTML\Header\Header;
use Core\Render\Render;

/**
 * Class AppController
 * @package App\Controller\Admin
 */
class AppController extends \App\Controller\AppController
{
    protected $template = 'admin';

    public function __construct()
    {
        parent::__construct();

        if($this->ctrLog()) {

            Render::getInstance()->setTemplate('admin');
            Header::getInstance()->add_js("tooltip");
            Header::getInstance()->add_css("blade");

            $this->success = false;
        }
    }
}