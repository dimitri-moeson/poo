<?php


namespace App\Controller;


use App\Controller\AppController;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Exception;

/**
 * Class InscriptionController
 * @package App\Controller
 */
class InscriptionController extends AppController
{
    /**
     * InscriptionController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        //Render::getInstance()->setTemplate('default');
        Render::getInstance()->setView("Inscription/default");

        $this->loadModel("User");
        $this->loadModel("Game\Item\Item");

        $this->loadService("User");
    }

    /**
     *
     */
    public function login(){

        if(Post::getInstance()->submit()){

            Redirect::getInstance()->setAct("faction")->send();
        }

        $this->form = new Form();

        $this->form
            ->input("login")
            ->input("email")
            ->pswd("pswd_new", array("conf" => true ))
            ->submit("suivant")
        ;
    }

    /**
     *
     */
    public function faction(){
        if(Post::getInstance()->submit()){

            Redirect::getInstance()->setAct("classe")->send();
        }

        $this->factions = $this->Item->listFaction();

        $this->form = new Form();

        foreach ( $this->factions as $faction) {

            $this->form->input("faction_".$faction->id);
        }
    }

    /**
     * @brief selection de la classe du personnage
     */
    public function classe(){
        if(Post::getInstance()->submit()){

            Redirect::getInstance()->setAct("race")->send();
        }

        $this->classes = $this->Item->listClasse();

        $this->form = new Form();

        foreach ( $this->classes as $classe) {

            $this->form->input("classe_".$classe->id);
        }
    }
    public function race(){

        if(Post::getInstance()->submit()){

            Redirect::getInstance()->setAct("sexe")->send();
        }

    }

    /**
     *
     */
    public function sexe(){

        if(Post::getInstance()->submit()){

            Redirect::getInstance()->setAct("personnage")->send();
        }

    }

    /**
     *
     */
    public function personnage(){

        if(Post::getInstance()->submit()){

            Redirect::getInstance()->setAct("save")->send();
        }

    }
    public function save(){}

    public function confirm(){}

}