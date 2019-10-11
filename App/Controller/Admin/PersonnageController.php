<?php


namespace App\Controller\Admin;

use App;
use Core\HTML\Env\Get;
use Core\HTML\Env\Post;
use Core\HTML\Form\Form;
use Core\HTML\Header\Header;
use Core\Redirect\Redirect;
use Core\Render\Render;
use Core\Session\FlashBuilder;

class PersonnageController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModel("Game\Personnage\Personnage");
    }

    private function form_perso($post)
    {
        $form = new Form($post);

        $form->input("titre", array('name' => "Nom"))
            ->choice("sexe", array('name' => "genre"),array( 1 => "homme" , 2 => "femme"))
            ->submit("Enregistrer");

        return $form ;
    }

    public function index()
    {
        $this->personnages = $this->Personnage->all();
    }

    public function delete(){

        if(Post::getInstance()->submit()) {

            if(Post::getInstance()->has('id')) {

                $post = $this->Personnage->find(Post::getInstance()->val('id'));
                if (!$post) $this->notFound("delete perso");
            }

            if(Post::getInstance()->has('conf')) {

                if ($this->Personnage->archive(Post::getInstance()->val('id'))) {

                    FlashBuilder::create("perso suppr","success");

                    Redirect::getInstance()
                        ->setDom("admin")->setAct("index")->setCtl("personnage")
                        ->send();

                }
            }

        }
    }

    /**
     *
     */
    public function single($id)
    {
        if(Post::getInstance()->submit()) {

            if($this->Personnage->update($id, Post::getInstance()->content())){

                $this->success = true ;

            }

        }

        if(!is_null($id)) {

            $this->post = $this->Personnage->find($id);
            if (!$this->post) $this->notFound("single perso");
        }

        $this->categories = $this->Personnage->list('id','nom');

        Header::getInstance()->setTitle($this->post->titre);

        $this->form = $this->form_perso($this->post);
    }
}