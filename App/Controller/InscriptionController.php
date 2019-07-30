<?php


namespace App\Controller\Game;


use App\Controller\AppController;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;

class InscriptionController extends AppController
{
    /**
     * InscriptionController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        Render::getInstance()->setTemplate('default');

        $this->loadModel("User");
        $this->loadService("User");
    }

    /**
     *
     */
    public function login(){

        if(Post::getInstance()->submitted()){

            Redirect::getInstance()->setAct("faction")->send();
        }

        $this->form = new Form();

        $this->form->input("login")->input("pswd")->input("pswd_copy");

    }

    /**
     *
     */
    public function faction(){
        if(Post::getInstance()->submitted()){

            Redirect::getInstance()->setAct("classe")->send();
        }
    }
    public function classe(){
        if(Post::getInstance()->submitted()){

            Redirect::getInstance()->setAct("race")->send();
        }
    }
    public function race(){

        if(Post::getInstance()->submitted()){

            Redirect::getInstance()->setAct("sexe")->send();
        }

    }

    /**
     *
     */
    public function sexe(){

        if(Post::getInstance()->submitted()){

            Redirect::getInstance()->setAct("personnage")->send();
        }

    }

    /**
     *
     */
    public function personnage(){

        if(Post::getInstance()->submitted()){

            Redirect::getInstance()->setAct("save")->send();
        }

    }
    public function save(){}

    public function confirm(){}

}