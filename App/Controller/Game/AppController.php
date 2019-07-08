<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:40
 */
namespace App\Controller\Game;

use App;
use Core\Auth\DatabaseAuth;

class AppController extends \App\Controller\AppController
{
    protected $template = 'game';

    public function __construct()
    {
        parent::__construct();

        // Auth
        $auth = new DatabaseAuth(App::getInstance()->getDb());

        if(!$auth->logged()){

            $this->forbidden();
        }

        $this->template = 'game';

    }
}