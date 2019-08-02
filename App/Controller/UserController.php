<?php
/**
 * Created by PhpStorm.
 * UserEntity: admin
 * Date: 28/04/2019
 * Time: 10:58
 */

namespace App\Controller;

use App;
use Core\Auth\DatabaseAuth;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;

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
            header("location:?p=test.fiche");

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

            if($this->UserService->login($log,$mdp)){

                if( $this->auth->login( $this->UserService->getUser() ) ) {

                    if ($this->auth->hasRole('admin'))
                        Redirect::getInstance()->setDom("admin")->setCtl("article")->setAct("index")->send();
                    else
                        Redirect::getInstance()->setCtl("test")->setAct("fiche")->send();
                }
            }
            else
            {
               $this->error = true ;
            }
        }

        die();

        $this->form = new Form(Post::getInstance()->content());

        $this->categories = App::getInstance()->getTable("Blog\Categorie")->all();
    }

}