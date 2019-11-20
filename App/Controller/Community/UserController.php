<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:58
 */

namespace App\Controller\Community;

use App;
use App\Model\Service\UserService;
use Core\Auth\DatabaseAuth;
use Core\HTML\Env\Post;
use Core\Redirect\Redirect;

/**
 * Class PlayerController
 * @package App\Controller
 */
class UserController extends AppController
{
    /**
     * PlayerController constructor.
     */
    public function __construct()
    {
        $this->auth = new DatabaseAuth(App::getInstance()->getDb());
        $this->loadService("User");
    }

    /**
     * deconnexion et redirection vers la racine du site
     */
    public function logout()
    {
        if(Post::getInstance()->submit())
        {
            $this->auth->logout();
        }
        Redirect::getInstance()->setCtl("default")->setAct("index")->setDom(null )->send();

        die();
    }

    /**
     *
     */
    public function login(){

        if(Post::getInstance()->submit()){

            $log = Post::getInstance()->val("login");
            $mdp = Post::getInstance()->val("pswd");

            if($this->UserService instanceof UserService)
            {
                if ($this->UserService->login($log, $mdp))
                {
                    if ($this->auth->login($this->UserService->getUser()))
                    {
                        Redirect::getInstance()->setCtl("default")->setAct("index");

                        if ($this->auth->hasRole('admin'))
                        {
                            Redirect::getInstance()->setDom("admin");
                        }
                        else
                        {
                            Redirect::getInstance()->setDom("game");
                        }
                    }
                }
            }
        }

        Redirect::getInstance()->send();

        //$this->form = new Form(Post::getInstance()->content());
        //$this->categories = App::getInstance()->getTable("Blog\Categorie")->all();
    }

}