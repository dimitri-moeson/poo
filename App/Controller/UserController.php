<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:58
 */

namespace App\Controller;

use App;
use App\Model\Service\UserService;
use Core\Auth\DatabaseAuth;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AppController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->auth = new DatabaseAuth(App::getInstance()->getDb());

        $this->loadService("User");
    }

    /**
     *
     */
    public function logout()
    {
        if(Post::getInstance()->submit())
        {
            $this->auth->logout();

            Redirect::getInstance()->setCtl("default")->setAct("index")->send();

            die("deconnexion...<br/>");
        }
    }

    /**
     *
     */
    public function login(){

        $this->error = false ;

        if(Post::getInstance()->submit()){

            $log = Post::getInstance()->val("login");
            $mdp = Post::getInstance()->val("pswd");

            if($this->UserService instanceof UserService)
            {
                if ($this->UserService->login($log, $mdp))
                {
                    if ($this->auth->login($this->UserService->getUser()))
                    {
                        if ($this->auth->hasRole('admin'))
                        {
                            Redirect::getInstance()->setCtl("article")->setAct("index")->setDom("admin");
                        }
                        else
                        {
                            Redirect::getInstance()->setCtl("test")->setAct("fiche");
                        }
                        Redirect::getInstance()->send();
                    }
                }
            }
        }

        $this->form = new Form(Post::getInstance()->content());

        $this->categories = App::getInstance()->getTable("Blog\Categorie")->all();
    }

}