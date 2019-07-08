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
use Core\Render\Render;

class AppController extends \App\Controller\AppController
{
    protected $template = 'admin';

    public function __construct()
    {
        parent::__construct();

        // Auth
        $auth = new DatabaseAuth(App::getInstance()->getDb());

        if(!$auth->logged()){

            $this->forbidden();
        }

        Render::getInstance()->setTemplate( 'admin') ;
        App::getInstance()->add_js("tooltip");
        App::getInstance()->add_css("blade");

        $this->success = false ;

    }
}